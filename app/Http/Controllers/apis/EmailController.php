<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;

use App\Http\Models\UserModel;
use App\Mail\PersonalizedDemo as MailDemo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Http\Models\SettingModel;

class EmailController extends Controller
{
    public function personaldemo(Request $request) 
    {
        // $receiver = SettingModel::where('code_setting', 'email_demo_receiver')->first();
        $receiver       = SettingModel::getSettingVal('email_demo_receiver');
        $fullName       = $request->first_name." ".$request->last_name;
        $email          = $request->email;
        $companyName    = @$request->companyName;
        $phoneNumber    = $request->phone_number;
        $notes          = $request->notes;
        //send to email default zeals
            Mail::to($receiver)->send(new MailDemo($fullName,$email,$companyName,$phoneNumber,$notes));
            // return true;
            // return new JsonResponse(
            //     [
            //         'success' => true, 
            //         'message' => "Success mengirim email permintaan demo"
            //     ], 
            //     200
            // );
            return response([
            'status' => 'success',
            'response' => 'success send request!'
        ]);
    }
    public function activation($email){
        $dataemail=UserModel::getDetailByEmailInactive($email);
        $dataemail['activation_link'] = url('activate-account/'.$dataemail['activation_code']);
        try{
            Mail::send('emails.user_activation', $dataemail, function ($mail) use ($dataemail) {
                $this->settingmodel = new SettingModel();
      
                $sender             = $this->setting_model->getSettingVal("email_sender_name");
                $senderaddress      = $this->setting_model->getSettingVal("email_sender_address");
      
                $mail->from($senderaddress, $sender);
                $mail->to($dataemail['email'], $dataemail['first_name']);
                $mail->subject('Activate Your Account Today for Exclusive Access!');
            });
        }catch(Exception $e){
            // throw new Exception("Email failed to send");
            return response([
                'status' => 'fail',
                'response' => 'failed to send email'
            ]);
            exit;
        }
        return response([
            'status' => 'success',
            'response' => 'success send email'
        ]);
    }
}
