<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = Notification::all();
        return response()->json($notifications);
    }

    public function getActivatedNotifs() {
        $notifications = Notification::where('is_active', true)->get();
        return response()->json($notifications);
    }

    /**
     * Store a newly created notification in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $notification = Notification::create($request->all());
        return response()->json($notification, 201);
    }

    /**
     * Display the specified notification.
     */
    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        return response()->json($notification);
    }

    /**
     * Update the specified notification in storage.
     */
    public function activate($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update([
            'is_active' => true,
            'activated_at' => now('Africa/Tunis'),
        ]);
        return response()->json($notification);
    }

    public function deactivate($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update([
            'is_active' => false
        ]);
        return response()->json($notification);
    }

    /**
     * Remove the specified notification from storage.
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return response()->json(null, 204);
    }
}