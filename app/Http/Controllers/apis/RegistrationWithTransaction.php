<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use Validator;
use App\Http\Models\UserModel;
use App\Http\Models\DepartmentModel;
use App\Http\Models\SettingModel;
use App\Http\Models\CampaignTrackerModel;


class RegistrationWithTransaction extends Controller
{

  protected $user_model;
  protected $department_model;
  protected $setting_model;

  public function __construct(){
    $this->setting_model = new SettingModel();
  }

  public function regist3rdparty(Request $request){

    //return response()->json($request->all());

    $this->user_model = new UserModel();
    $this->department_model = new DepartmentModel();

    $department_code = @$request->department_code;
    $encrypted_code  = $request->encrypted_code;
    $password        = rand(11111111,99999999);

    //downline 
    if($encrypted_code!=""){
        $tracker_model   = new CampaignTrackerModel();
        $owner           = $tracker_model->getTrackerInfo($encrypted_code);
        $user            = UserModel::where("id_user",$owner->id_user)->first();
        $referral_code   = $user->activation_code;
    }else{
        $referral_code   = "";
    }
    //downline 

    $data = array(
      "first_name"      => $request->input("name"),
      "last_name"       => $request->department_code,
      "username"        => rand(11111111,99999999),
      "email"           => $request->input("email"),
      "password"        => md5($password),
      "phone"           => $request->input("phone"),
      "id_department"   => $this->department_model->getDefaultDepartment(),
      "date_created"    => time(),
      "last_update"     => time(),
      "google_id"       => $request->google_id,
      "referral_code"   => @$referral_code
    );

    if(@$request->google_id!=""){
      $data['status'] = 'active';
    }else{
      $data['status'] = 'inactive';
    }

    if($department_code){
      $data['id_department']  = $this->department_model->getDepartmentByCode($department_code);
    }

    $rules  = array(
        "first_name"      => "required",
        "username"        => "required|unique:tb_user,username",
        "email"           => "required|email|unique:tb_user,email",
        "password"        => 'required',
        "phone"           => 'required|numeric',
	);
    $messages = array(
      "first_name.required" => "Please fill this field!",
      "username.required"   => "Please fill this field!",
      "username.unique"     => "Username already used by another user! Please select another username.",
      "email.required"      => "Please fill this field!",
      "email.email"         => "Please fill this field with valid email format, example: name@domain.com",
      "email.unique"        => "Email already used by another user! Please select another valid email",
      "password.required"   => "Please fill this field!",
      "phone.required"       => "Please fill this field!",
      "phone.numeric"        => "Please fill this field only with numeric!",
    );

	$validator = Validator::make($data, $rules, $messages);
    if ($validator->fails()) {
        $error = array(
            "status"    => "error_validation",
            "data"      => $data,
            "response"  => $validator->messages()
        );

        return response()->json($error, 200);
    }else{

      //check email
      $email  = $request->email;
      $key    = $this->setting_model->getSettingVal("email_check_api");

      $curl = curl_init();

      // curl_setopt_array($curl, [
      // 	CURLOPT_URL => "https://ajith-verify-email-address-v1.p.rapidapi.com/varifyEmail?email=".$email,
      // 	CURLOPT_RETURNTRANSFER => true,
      // 	CURLOPT_FOLLOWLOCATION => true,
      // 	CURLOPT_ENCODING => "",
      // 	CURLOPT_MAXREDIRS => 10,
      // 	CURLOPT_TIMEOUT => 30,
      // 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      // 	CURLOPT_CUSTOMREQUEST => "GET",
      // 	CURLOPT_HTTPHEADER => [
      // 		"x-rapidapi-host: ajith-Verify-email-address-v1.p.rapidapi.com",
      // 		"x-rapidapi-key: ".$key
      // 	],
      // ]);

      // $response = curl_exec($curl);
      // $err = curl_error($curl);
      //
      // curl_close($curl);
      //
      // if (@$err) {
      // 	$hasil['exist'] = "false";
      // } else {
      // 	$hasil = json_decode($response,true);
      // }
      //
      // if(@$hasil['exist']=="false"){
      //   $error = array(
      //     "status"    => "error_validation",
      //     "response"  => array(
      //       "email"   => array("Email does not exist! Please try with different valid email address")
      //     )
      //   );
      //
      //   return response()->json($error, 200);
      //   exit();
      // }

      $activation_code            = $this->user_model->createActivationCode();
      unset($data['agree']);
      $data['activation_code']    = $activation_code;
      $data['date_created']       = time();
      $data['last_update']        = time();

      if(@$request->verification=="no"){
        $data['status']  = 'active';
      }

      $createuser                 = $this->user_model->insertData($data);

      //$mitra_code                 = $this->mitra_model->createMitraCode();

      //$datamitra['id_user']       = $createuser;
      //$datamitra['mitra_code']    = $mitra_code;

      $dataemail                    = $data;
      $dataemail['real_password']   = $password;
      $dataemail['activation_link'] = url('activate-account/'.$activation_code);

      if(@$request->google_id==""){

        if(@$request->verification!="no"){
          Mail::send('emails.user_activation_invitation_by_transaction', $dataemail, function ($mail) use ($dataemail) {
            $this->settingmodel = new SettingModel();
          
            $sender             = $this->setting_model->getSettingVal("email_sender_name");
            $senderaddress      = $this->setting_model->getSettingVal("email_sender_address");
          
            $mail->from($senderaddress, $sender);
            $mail->to($dataemail['email'], $dataemail['first_name']);
            $mail->subject('Account Activation');
          });
        }

        $return = array(
          "status"    => "success",
          "response"  => "<div class='alert alert-success alert-dismissable'>
                              <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                                Registration almost complete! Please activate your account through your email
                              <a class='alert-link' href='#'>".$data['email']."</a>.
                          </div>",
          "credential" => $createuser
        );
      }else{
        Session::forget("google_register");
        Session::forget("google_email");
        Session::flush();

        $return = array(
          "status"    => "success",
          "response"  => "<div class='alert alert-success alert-dismissable'>
                              <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                                Registration completed! Please sign in from <a href='".url('signin')."'>here</a>.
                          </div>"
        );
      }
      //return response()->json($return,200);
      return $return;
    }

  }
}
