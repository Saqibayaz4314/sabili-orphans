<?php

namespace App\Http\Controllers;

use App\Models\Orphan;
use App\Models\Sponsorship;
use Illuminate\Http\Request;

class FinaceController extends Controller
{
    public function index(Request $request){


        $orphan = Orphan::query()
        ->when($request->filled('orphan_code'), function ($query) use ($request) {
            $query->where('orphan_code', $request->orphan_code);
        })
        ->with(['activeSponsorship' , 'image'])
        ->first();

        $total_amounts_paid = Sponsorship::where('status', 'تم التسليم')
            ->get(['duration', 'amount'])
            ->sum(function ($item) {
                return (float) $item->duration * (float) $item->amount;
            });

        $total_overdue_amounts =  Sponsorship::where('status', 'لم يتم التسليم')
            ->get(['duration', 'amount'])
            ->sum(function ($item) {
                return (float) $item->duration * (float) $item->amount;
            });

        $orphan_amount_paid = $orphan->sponsorships()->where('status', 'تم التسليم')
            ->get(['duration', 'amount'])
            ->sum(function ($item) {
                return (float) $item->duration * (float) $item->amount;
            });

        $orphan_overdue_paid = $orphan->sponsorships()->where('status', 'لم يتم التسليم')
            ->get(['duration', 'amount'])
            ->sum(function ($item) {
                return (float) $item->duration * (float) $item->amount;
            });

        $orphan_months_covered = $orphan->sponsorships()->where('status', 'تم التسليم')
         ->get(['duration', 'amount'])
            ->sum(function ($item) {
                return (float) $item->duration;
            });

        $orphan_months_late = $orphan->sponsorships()->where('status',  'لم يتم التسليم')
         ->get(['duration'])
            ->sum(function ($item) {
                return (float) $item->duration ;
            });

        return view('pages.finance.finace' , compact(['orphan' , 'total_amounts_paid' , 'total_overdue_amounts',
            'orphan_amount_paid' , 'orphan_overdue_paid' , 'orphan_months_covered' ,'orphan_months_late']));
    }

    public function deliverySponsorship(Request $request){

        $ids = explode(',', $request->input('sponsorship_ids'));

        $request->merge(['ids' => $ids]);


        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:sponsorships,id',
        ]);



        foreach ($validated['ids'] as $id) {
            $sponsorship = Sponsorship::findOrFail($id);
            if ($sponsorship) {
                $sponsorship->update([
                    'status' => 'تم التسليم',
                    'start_date' => now()->toDateString(),
                ]);
            }
        }

        return back()->with('success', 'تم تسليم  الكفالة لليتيم بنجاح');
    }
}
