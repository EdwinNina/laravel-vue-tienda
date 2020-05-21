<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function get()
    {
        $unreadNotifications = Auth::user()->unreadNotifications;
        $fechaActual = date('Y-m-d');

        foreach ($unreadNotifications as $notification) {
            if($fechaActual != $notification->created_at->toDateString()){
                $notification->markAsRead();
            }
        }


        return Auth::user()->unreadNotifications;
    }


}
