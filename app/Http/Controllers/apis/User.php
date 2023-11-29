<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Http\Models\UserModel;
use App\Http\Models\SettingModel;
use App\Http\Models\UserTempModel;
use App\Http\Models\DepartmentModel;

use Session;

class User extends Controller
{
    public function __construct(){
      $this->setting_model = new SettingModel();
      $this->user_model = new UserModel();
      $this->department_model = new DepartmentModel();
    }

    public function getresume(Request $request){
      $id_penerbit = $request->id_penerbit;
      $login       = $this->user_model->getDetail($request->id_user);

      if($request->range!=""){
        $range  = $request->range;
        $start  = $range[0];
        $end    = $range[1]; 
      }else{
        $start  = date("d-m-Y",time()-(7*86400));
        $end    = date("d-m-Y");
        $range  = [$start, $end];
      }

      if($login){
        if($login->department_code=="BRAND"){
          $referral_code = $login->referral_code;
          $id_department = $this->department_model->getDepartmentByCode($referral_code);
        }
      }else{
        $id_department = "";
      }

      $response = array(
        "status"  => "success",
        "total"   => $this->user_model->getUserResume($request->keyword,$id_penerbit, @$id_department, $start, $end),
        "percent" => "",
        "unit"    => $request->unit,
        "login"   => $login,
        "range"   => $range[0]
      );
      return response()->json($response,200);
    }

    public function inviteuser(Request $request){
      $this->tmp_user = new UserTempModel();
      $data = $this->tmp_user->getData(time(),500);

      $responses = array();

      foreach($data as $indexuser=>$user){
        $password = $this->createPassword();
        $data = array(
          "first_name"      => $user->name,
          "last_name"       => "",
          "username"        => "",
          "email"           => $user->email,
          "password"        => md5($user->no_telp),
          "phone"           => $user->no_telp,
          "id_department"   => $this->department_model->getDepartmentByCode($request->ref),
          "date_created"    => time(),
          "last_update"     => time(),
          "google_id"       => "",
          "activation_code"       => $this->user_model->createActivationCode(),
          "address"         => $user->domisili,
          "nama_bank"       => $user->bank_code,
          "nama_pemilik_rekening"  => $user->account_bank_name,
          "nomor_rekening"  => $user->no_rek,
          "id_job"          => $user->id_job,
          "id_wilayah"      => $user->id_location,
          "gender"          => ($user->avatar!="")?(($user->avatar=="PRIA")?"L":"P"):"",
          "custom_field_1"  => $user->npwp
        );

        $createuser         = $this->user_model->insertData($data);

        //email invitation

        /*
        $email  = $user->email;
        $key    = $this->setting_model->getSettingVal("email_check_api");

        $curl = curl_init();

        curl_setopt_array($curl, [
        	CURLOPT_URL => "https://ajith-verify-email-address-v1.p.rapidapi.com/varifyEmail?email=".$email,
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_FOLLOWLOCATION => true,
        	CURLOPT_ENCODING => "",
        	CURLOPT_MAXREDIRS => 10,
        	CURLOPT_TIMEOUT => 30,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => "GET",
        	CURLOPT_HTTPHEADER => [
        		"x-rapidapi-host: ajith-Verify-email-address-v1.p.rapidapi.com",
        		"x-rapidapi-key: ".$key
        	],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if (@$err) {
        	$hasil['exist'] = "false";
        } else {
        	$hasil = json_decode($response,true);
        }

        if(@$hasil['exist']=="false"){
          $responses[] = "blast to ".$user->email." failed";
        }else{
          $dataemail                    = $data;
          $dataemail['activation_link'] = url('activate-account/'.$data['activation_code']);
          $dataemail['real_password']   = $password;

          $responses[] = "blast to ".$user->email." success";


          Mail::send('emails.user_activation_invitation_byemail', $dataemail, function ($mail) use ($dataemail) {
            $this->settingmodel = new SettingModel();

            $sender             = $this->setting_model->getSettingVal("email_sender_name");
            $senderaddress      = $this->setting_model->getSettingVal("email_sender_address");

            $mail->from($senderaddress, $sender);
            $mail->to($dataemail['email'], $dataemail['first_name']);
            $mail->subject('Invitation, Join and Earn Money from Your Thumb in Minutes!');
          });

        }
        */
        $this->tmp_user->updateData(array("id_member" => $user->id_member, "invited" => 1));

        //email invitation
      }

      return response()->json($responses,200);
    }

    function createPassword(){
      $code = "";
      $length = 8;
      $status = true;
      $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!#$!@%@?";

      do {
          $code = "";
          for ($i = 0; $i < $length; $i++) {
              $code = $code . substr($alphabet, rand(0, strlen($alphabet) - 1), 1);
          }

          $checkavailability = UserModel::where("affiliate_code", $code)->count();
          if ($checkavailability) {
              $status = true;
          } else {
              $status = false;
          }
      } while ($status);

      return $code;
    }

    public function invitePending(Request $request){
      
    }
}
