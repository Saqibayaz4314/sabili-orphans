<?php

namespace App\Http\Controllers;

use App\Models\Orphan;
use Illuminate\Http\Request;

class ViewOrphanController extends Controller
{
    public function registerOrphan(){
        $orphans = Orphan::where('role' , 'registered')->paginate(15);
        return view('pages.orphans.auditing-addition-requests' , compact('orphans'));
    }


    public function waitingOrphan(){
        $orphans = Orphan::where('role' , 'waiting')->paginate(15);
        return view('pages.orphans.waiting_orphan' ,compact('orphans'));
    }

    public function certifiedOrphan(){
        $orphans = Orphan::where('role' , 'certified')->paginate(15);
        return view('pages.orphans.adopted_orphan' ,compact('orphans'));
    }

    public function sponsoredOrphan(){
        $orphans = Orphan::where('role' , 'sponsored')->with('activeSponsorship')->paginate(15);
        return view('pages.orphans.sponsored_orphan' ,compact('orphans'));
    }
}
