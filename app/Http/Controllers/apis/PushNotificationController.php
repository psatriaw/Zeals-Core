<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FirebaseToken;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;

class PushNotificationController extends Controller
{
    public function subscribe(Request $request){
        $user   = auth('sanctum')->user();

        $rules  = array(
            "token"                 => "required",
        );

        $messages = array(
            "token.required"       => "Token unavailable. Please provide your token!"
        );

        $data = [
            "token"     => $request->token,
            "user_id"   => (@$request->user_id)?$request->user_id:null,
            "status"    => "active"
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $error = array(
                "status"    => "error_validation",
                "messages"  => $validator->messages(),
            );

            return response()->json($error, 200);
        }

        $create = FirebaseToken::updateOrCreate($data);
        if($create){
            $response = array(
                "status"    => "success",
                "message"   => "Data successfully registered" 
            );
        }else{
            $response = array(
                "status"    => "error",
                "message"   => "Failed to create request" 
            );
        }

				// Subscribe token to topic 'all'
				$headers = array
				('Authorization: key=' . "AAAAcR2UlMg:APA91bF6E_WErBKXH5P_qakSaJwRtk6dHw58t9IcaHBynkA2GsaVRsOm-NjBhojdIeKjf98joQ-YVFZqLhyFnEuoypvKKRLgAGIxKdMJvKoeDZIAFAsJyTdhCYlFC8lHhBiP9EfU13oS",
				  'Content-Type: application/json');
				$ch = curl_init();
				$token = $request->token;
				curl_setopt($ch, CURLOPT_URL, "https://iid.googleapis.com/iid/v1/$token/rel/topics/all");
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_POSTFIELDS, array());
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_exec($ch);

        return response()->json($response, 200);
        exit();
    }
}
