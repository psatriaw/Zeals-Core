<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\{User, Wilayah, Department, Setting};

use App\Http\Models\SettingModel;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(){
      
    }

    public function profile(Request $request){

      $user        = $user = auth('sanctum')->user();

      if($user){
        $response = array(
            "status"    => "success",
            "data"      => User::where("id_user",$user->id_user)->with('saldo')->first(),
            "wilayah"   => Wilayah::orderBy("namaprov","ASC")->orderBy("namakab","ASC")->get()
        );
      }else{
        $response = array(
            "status"    => "error",
            "message"   => "Need login" 
          );
      }

      return response()->json($response,200);
    }


    public function update(Request $request){

        $user        = $user = auth('sanctum')->user();

        $rules  = array(
            "first_name"      => "required",
            "email"           => ["required",Rule::unique('tb_user')->where("status","active")->whereNot("id_user",$user->id_user)],
            "phone"           => 'required|numeric',
        );

        $messages = array(
            "first_name.required" => "Please fill first name field!",
            "email.required"      => "Please fill email field!",
            "email.email"         => "Please fill email field with valid email format, example: name@domain.com",
            "email.unique"        => "Email already used by another user! Please select another valid email",
            "phone.required"      => "Please fill the phone number",
            "c_password.required"   => "Please fill password confirmation field!",
            "new_password.same"     => "Password & confirmation not same!"
        );


        $data        = [
            "first_name"    => $request->first_name,
            "last_name"     => $request->last_name,
            "username"      => $request->email,
            "email"         => $request->email,
            "phone"         => $request->phone,
            "nama_bank"     => $request->nama_bank,
            "nama_pemilik_rekening"     => $request->nama_pemilik_rekening,
            "nomor_rekening"       => $request->nomor_rekening,
            "gender"        => $request->gender,
            "dob"           => $request->dob,
            "id_wilayah"    => $request->id_wilayah
        ];

        if($request->new_password){
            $data['c_password']     = $request->c_password;
            $data['new_password']   = $request->new_password;
            $data['password']       = md5($request->new_password);
            $rules['new_password']  = "same:c_password";
            $rules['c_password']    = "required";
        }

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $error = array(
                "status"    => "error_validation",
                "data"      => $data,
                "messages"  => $validator->messages(),
            );

            return response()->json($error, 200);
            exit();
        }

        unset($data['new_password']);
        unset($data['c_password']);

        $update     = User::where("id_user",$user->id_user)->update($data);
  
        if($update){
          $response = array(
              "status"    => "success",
              "message"   => "Profile updated!"
          );
        }else{
          $response = array(
              "status"    => "error",
              "message"   => "No data update" 
            );
        }
  
        return response()->json($response,200); 
    }

    public function register(Request $request){
      $department_code  = @$request->referral_code;
      $department       = new Department();
      $setting          = new Setting();


      $data = array(
        "first_name"      => $request->input("first_name"),
        "last_name"       => $request->input("last_name"),
        "username"        => $request->input("email"),
        "email"           => $request->input("email"),
        "password"        => md5($request->input("password")),
        "phone"           => $request->input("phone"),
        "id_department"   => $department->where("default","yes")->first()->id_department,
        "date_created"    => time(),
        "last_update"     => time(),
        "google_id"       => @$request->google_id
      );

      if(@$request->google_id!=""){
        $data['status'] = 'active';
      }else{
        $data['status'] = 'inactive';
      }

      if($department_code){
        $dd = $department->where("department_code", $department_code)->first();
        if($dd){
          $data['id_department']  = $dd->id_department; 
        }
      }

      $rules  = array(
        "first_name"      => "required",
        "email"           => ["required",Rule::unique('tb_user')->where("status","active")],
        "password"        => 'required',
        "phone"           => 'required|numeric',
      );
      $messages = array(
        "first_name.required"   => "Please fill first name field!",
        "email.required"        => "Please fill email field!",
        "email.email"           => "Please fill email field with valid email format, example: name@domain.com",
        "email.unique"          => "Email already used by another user! Please select another valid email",
        "password.required"     => "Please fill password field!",
        "phone.required"        => "Please fill phone field!",
        "phone.numeric"         => "Please fill phone field only with numeric!",
      );

      $validator = Validator::make($data, $rules, $messages);
      if ($validator->fails()) {
        $error = array(
          "status"    => "error_validation",
          "messages"  => $validator->messages()
        );

        return response()->json($error, 200);
      }else{
        $email  = $request->email;
        $key    = $setting->where("code_setting","email_check_api")->first()->setting_value;
        $curl   = curl_init();

        $activation_code            = (new User())->createActivationCode();
        unset($data['agree']);
        $data['activation_code']    = $activation_code;
        $data['date_created']       = time();
        $data['last_update']        = time();

        if(@$request->verification=="no"){
          $data['status']  = 'active';
        }
        
        $createuser                 = User::create($data);

        if($createuser){
          $dataemail                    = $data;
          $dataemail['activation_link'] = url('activate-account/'.$activation_code);

          Mail::send('emails.user_activation_invitation', $dataemail, function ($mail) use ($dataemail) {
            $emailsetting = new Setting();
          
            $sender             = $emailsetting->where("code_setting","email_sender_name")->first()->setting_value;
            $senderaddress      = $emailsetting->where("code_setting","email_sender_address")->first()->setting_value;
          
            $mail->from($senderaddress, $sender);
            $mail->to($dataemail['email'], $dataemail['first_name']);
            $mail->subject('Account Activation');
          });

          $response = array(
            "status"    => "success",
            "messages"   => "Account successfully created! Please check your email, we just send you a verification email. Please activate your account through the email."
          );
        }else{
          $response = array(
              "status"    => "error",
              "message"   => "Failed to create account" 
            );
        }

        return response()->json($response,200);
      }
    }
}
