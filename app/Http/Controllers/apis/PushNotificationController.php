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
            "user_id"   => (@$request->user_id)?$request->user_id:$user->id_user,
            "status"    => "active"
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $error = array(
                "status"    => "error_validation",
                "messages"  => $validator->messages(),
            );

            return response()->json($error, 200);
            exit();
        }

        $create = FirebaseToken::updateOrCreate($data,["user_id" => (@$request->user_id)?$request->user_id:$user->id_user]);
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

        return response()->json($response, 200);
        exit();
    }
}
