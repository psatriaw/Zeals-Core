<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FcmNotification;

class FcmController extends Controller
{
        public function list() 
        {
                $notification = new FcmNotification();
                $getNotif = $notification->get();

                if($getNotif){
                        $return = array(
                                "status"    => "success",
                                "data"      => $getNotif
                        );
                }else{
                        $return = array(
                            "status"    => "error",
                            "messages"  => "No banner found!"
                        );
                }

                return response()->json($return, 200);
        }
}
