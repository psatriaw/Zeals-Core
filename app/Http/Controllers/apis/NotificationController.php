<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use Validator;
use App\Models\Notification;

use Session;


class NotificationController extends Controller
{

    protected $user_model;
    protected $department_model;
    // protected $setting_model;

    public function __construct(){
        //$this->setting_model = new SettingModel();
    }

    public function list(Request $request){
        $user        = $user = auth('sanctum')->user();
        
        $notifications = Notification::where("id_user",$user->id_user)->orderBy("id_notification","DESC")->get();
        
        if($notifications){

            $return = array(
                "status"    => "success",
                "data"      => $notifications,
                "path"      => url('/')
            );
        }else{
            $return = array(
                "status"    => "error",
                "messages"  => "No notification found!"
            );
        }

        return response()->json($return, 200);
    }

    public function read(Request $request){
        $user        = $user = auth('sanctum')->user();
        $read        = [
            "status"        => "read",
            "last_update"   => time()
        ];

        $update     = Notification::where("id_notification",$request->id_notification)->update($read);
        if($update){
            $return = array(
                "status"        => "success",
                "messages"      => "Read notificatino success"
            );
        }else{
            $return = array(
                "status"    => "error",
                "messages"  => "Failed to read notification"
            );
        }

        return response()->json($return, 200);
    }
}
