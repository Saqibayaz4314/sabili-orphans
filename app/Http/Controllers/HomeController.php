<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Orphan;
use App\Models\Sponsorship;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $orphan_count = Orphan::where('role' , 'sponsored')->count();
        $orphan_wait_count = Orphan::whereIn('role', ['registered', 'waiting'])->count();


        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $deliveredCount = Sponsorship::where('status', 'تم التسليم')
            ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->count();

        $eighteenYearsAgo = Carbon::now()->subYears(18);

        $adultOrphansCount = \App\Models\Orphan::whereDate('birth_date', '<=', $eighteenYearsAgo)->count();

        // dd($orphan_count);
        return view('home' , compact(['orphan_count' , 'orphan_wait_count' ,'deliveredCount' , 'adultOrphansCount']));
    }
}
