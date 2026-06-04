<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->get();
        return view('notification.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $user->markNotificationAsRead($request->id);

        if ($request->from && $request->from == 'notifications') {
            return redirect()->route('notifications.index');
        } else {
            return response()->json(['success' => true]);
        }
    }
    
}
