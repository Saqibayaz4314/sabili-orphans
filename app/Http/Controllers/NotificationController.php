<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function ShowNotification(){

        $notifications = Auth::user()->notifications->filter(function ($notification) {
            return $notification->type === 'App\Notifications\SponsorshipEndingSoon'
                || $notification->type === 'App\Notifications\SponsorshipEnded';
        })->where('created_at'  , '>=' , now()->subDays(8));
        $this->makeReadNotification(Auth::user());
        return view('pages.notification' , compact('notifications'));

    }


    protected function makeReadNotification($user){
        $notifications = $user->unreadNotifications;
        $notifications->markAsRead();
        return;
    }

}
