<?php

namespace App\Http\Controllers;

use App\Models\Orphan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Notifications\OrphanEmailNotification;
use Exception;
use Illuminate\Support\Facades\DB;

class ActionOrphanController extends Controller
{
    public function approveOrphans(Request $request)
    {


        $ids = explode(',', $request->input('ids'));
        $request->merge(['ids' => $ids]);

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:orphans,id',
        ]);


        foreach ($validated['ids'] as $id) {
            $orphan = Orphan::findOrFail($id);
            if ($orphan) {
                $orphan->update(['role' => 'certified']);
            }
        }

        return back()->with('success', 'تم اعتماد الأيتام بنجاح');
    }

    public function waitingOrphans(Request $request)
    {

        $ids = explode(',', $request->input('waiting_ids'));

        $request->merge(['ids' => $ids]);

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:orphans,id',
            'waiting_reason' => ['required' , 'string'],
        ]);


        foreach ($validated['ids'] as $id) {
            $orphan = Orphan::findOrFail($id);
            if ($orphan) {

                $orphan->update([
                    'role' => 'waiting',
                    'waiting_reason' => $request->waiting_reason,
                ]);
            }
        }

        return back()->with('success', 'تم تحويل الأيتام الى قائمة الانتظار بنجاح');
    }

    public function sponsorOrphans(Request $request)
    {

        $ids = explode(',', $request->input('ids'));
        $request->merge(['ids' => $ids]);

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:orphans,id',
            'amount' => 'required|string',
            'duration'=> 'required|numeric|min:1'
        ]);



        foreach ($validated['ids'] as $id) {
            $orphan = Orphan::findOrFail($id);
            if ($orphan) {

                try{
                    DB::beginTransaction();

                    $orphan->update(['role' => 'sponsored']);

                    $orphan->sponsorships()->create([
                        'duration' => $validated['duration'],
                        'amount' => $validated['amount'],
                        // 'start_date' => now(),
                        'role' => 'active',
                        'status' => 'لم يتم التسليم'
                    ]);


                    DB::commit();

                }catch(Exception $e){
                    DB::rollBack();
                    dd($e);
                    return redirect()->back()->with('error' , 'فشل تحويل اليتيم الى قائمة الأيتام المكفولين');
                }




            }
        }

        return back()->with('success', 'تم كفالة الأيتام بنجاح');
    }


    public function destroyOrphans(Request $request)
    {

        $ids = explode(',', $request->input('delete_ids'));
        $request->merge(['ids' => $ids]);

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:orphans,id',
        ]);



        foreach ($validated['ids'] as $id) {
            $orphan = Orphan::findOrFail($id);
            dd($orphan);
            $orphan->delete();
        }

        return back()->with('success', 'تم حذف الأيتام بنجاح');
    }

    public function search(Request $request)
    {
        $query = Orphan::query();

        $searchBys = $request->input('search_by', []);
        $conditions = $request->input('condition', []);
        $values = $request->input('search_value', []);


        foreach ($searchBys as $index => $field) {
            $condition = $conditions[$index] ?? '==';
            $value = $values[$index] ?? null;

            if ($value !== null && $value !== '') {
                // فقط شرط تطابق دقيق (==)
                if ($condition == '==') {
                    $query->where($field, $value);
                }
                // يمكن إضافة شروط أخرى هنا لو تحتاج (مثل like, >, < ...)
            }
        }

        $orphans = $query->paginate(15)->appends($request->query());
        // dd($orphans);

        return view('pages.orphans.create-query', compact('orphans', 'searchBys', 'conditions', 'values'));
    }

    public function sendEmail(Request $request)
    {

        $ids = explode(',', $request->orphan_ids);
        $request->merge(['ids' => $ids]);

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:orphans,id',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $orphans = Orphan::whereIn('id', $validated['ids'])->get();

        foreach ($orphans as $orphan) {
            if ($orphan->email) {
                $orphan->notify(new OrphanEmailNotification($request->subject, $request->message));
            }
        }

        return back()->with('success', 'تم إرسال البريد الإلكتروني بنجاح .');
    }







}
