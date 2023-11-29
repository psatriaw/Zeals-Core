<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EmailBroadcast;
use Illuminate\Support\Facades\Mail;

class BroadcasterController extends Controller
{
    public function push(){
        $emaillist = (new EmailBroadcast())->whereNotNull("email")->where("email","psatriaw@gmail.com")->where("status","pending")->orderBy("id","ASC")->limit(20)->skip(0)->get();
        if($emaillist){
            foreach($emaillist as $index=>$email){
                
                    $dataemail['name']  = $email->receiver;
                    $dataemail['email'] = $email->email;

                    if($dataemail['name']==""){
                        $dataemail['name'] = "Sir/Madam"; 
                    }else{
                        $dataemail['name'] = "Mr/Mrs ".$dataemail['name']; 
                    }
                    
                    Mail::send('emails.broadcast.template_satu', $dataemail, function ($mail) use ($dataemail) {
            
                    $sender             = "Zeals Asia";
                    $senderaddress      = "hello@zeals.asia";
            
                    $mail->from($senderaddress, $sender);
                    $mail->to($dataemail['email'], $dataemail['name']);
                    $mail->subject('Hello from ZEALS ASIA!');
                    });

                    // EmailBroadcast::where("id",$email->id)->update(["status" => "sent"]);
                
            }
        }
    }
}
