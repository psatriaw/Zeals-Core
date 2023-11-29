<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use App\Http\Models\PrevilegeModel;
use App\Models\FcmNotification;
use Illuminate\Support\Facades\Http;

class FcmNotificationController extends Controller
{
    public function create(Request $request)
    {
        $login    = Session::get("user");
        if($login){
            return view('backend.notification-form');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'message' => 'required',
            'status' => 'required',
            'link' => 'required'
        ]);

        $notification = FcmNotification::create($validated);
        // dd($notification);
        $response = $this->sendFcmNotification($notification);
        if ($response->successful()) {
            return redirect()->back()->with('success', 'Notification sent successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to send notification.');
        }
    }

    private function sendFcmNotification(FcmNotification $notification)
    {
        $serverKey = 'key=AAAAcR2UlMg:APA91bF6E_WErBKXH5P_qakSaJwRtk6dHw58t9IcaHBynkA2GsaVRsOm-NjBhojdIeKjf98joQ-YVFZqLhyFnEuoypvKKRLgAGIxKdMJvKoeDZIAFAsJyTdhCYlFC8lHhBiP9EfU13oS';
        $url = 'https://fcm.googleapis.com/fcm/send';

        $message = [
            "to" => "/topics/all",
            "notification" => [
                "title" => $notification->title,
                "body" => $notification->message
            ],
	        "data" => [
						"deeplink" => $notification->link
	        ]
        ];

        $response = Http::withBody(json_encode($message), 'application/json')
            ->withHeaders([
                "Authorization" => $serverKey,
            ])
            ->post($url);

        return $response;
    }
}
