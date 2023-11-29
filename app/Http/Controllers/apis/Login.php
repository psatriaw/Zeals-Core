<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use Validator;
use App\Models\{User, Department};
use App\Http\Models\SettingModel;
use Illuminate\Validation\Rule;

use Session;


class Login extends Controller
{

    protected $user_model;
    protected $department_model;
    // protected $setting_model;

    public function __construct(){
        $this->setting_model = new SettingModel();
    }

    public function submit(Request $request){

        $data = array(
            "email"           => $request->input("email"),
            "password"        => $request->input("password"),
        );
  
        $rules  = array(
            "email"           => "required",
            "password"        => 'required',
        );
        $messages = array(
            "email.required"      => "Please fill email/phone field",
            "password.required"   => "Please fill password field",
        );
      
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $return = array(
              "status"    => "error_validation",
              "messages"  => $validator->messages()
            );

            
        }else{
            if(substr($data['email'],0,2)=="08" && $data['email']==$data['password']) {
                $data['email'] = "62".substr($data['email'],1);
            }

            if(substr($data['password'],0,2)=="08" && $data['email']==$data['password']) {
                $data['password'] = "62".substr($data['password'],1);
            }

            $data['password']   = md5($data['password']);
            $user               = User::where(function ($query) use ($data) {
                                    $query->where("phone", $data['email']);
                                    $query->orwhere("email", $data['email']);
                                  })
                                  ->where("password", $data['password'])
                                  ->with('department','saldo')->first();
            
            if($user){
                
                $token = $user->createToken("auth_token")->plainTextToken;
                $user['base_path']  = url('');
                $return = array(
                    "status"    => "success",
                    "messages"  => "Login success",
                    "user"      => $user,
                    "token"     => $token
                );
            }else{
                $return = array(
                    "status"    => "error",
                    "messages"  => "Login error! email/phone number and password not recognized! Please check your credential",
                    "query"     => $data
                );
            }
        }

        return response()->json($return, 200);
    }

    public function googleLoginV2(Request $request){
      $google_id  = $request->id;
      $token      = $request->token;
      
      $user       = (new User())->where("google_id",$google_id)->with('department','saldo')->first();
      if($user){
        $token = $user->createToken("auth_token")->plainTextToken;
  
        $return = array(
            "status"    => "success",
            "messages"  => "Login success",
            "user"      => $user,
            "token"     => $token
        );

      }else{

          //get google data
          $google_api = curl_init();
          curl_setopt($google_api, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token='.$token);

          //https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=y

          // Receive server response ...
          curl_setopt($google_api, CURLOPT_RETURNTRANSFER, true);
          
          $google_data = curl_exec($google_api);
          curl_close($google_api);

          $google_data = json_decode($google_data,true);


          $regist = curl_init();

          $register = false;

          $data = array(
            "first_name"      => $google_data['name'],
            "email"           => $google_data['email'],
            "id_department"   => (new Department())->where("default","yes")->first()->id_department,
            "date_created"    => time(),
            "last_update"     => time(),
            "google_id"       => $google_data['id'],
            "status"          => 'active',
            "password"        => $google_data['id'],
            "activation_code" => "Act1v3",
            "username"        => $google_data['email']
          );
      
          $rules  = array(
            "email"           => ["required",Rule::unique('tb_user')->where("status","active")],
          );
      
          $messages = array(
            "email.unique"          => "Email already used by another user! Please select another valid email",
          );
      
          $validator = Validator::make($data, $rules, $messages);
          if ($validator->fails()) {
            $user = User::where(["status" => "active","email" => $google_data['email']])->first();
            $user->update(["google_id" => $google_data['id']]);

          }else{
            $register   = User::create($data);
          }

          $user   = (new User())->where("google_id",$google_id)->with('department','saldo')->first();
          $token  = $user->createToken("auth_token")->plainTextToken;

          $return = array(
              "status"    => "success",
              "messages"  => "Login success",
              "user"      => $user,
              "token"     => $token,
              "google_data" => $google_data,
              "registrasi" => $register
          );
      }
      return response()->json($return, 200);
    }
    
    public function googleLogin(Request $request){
      $google_id  = $request->id;
      $id_token   = $request->token;

      $CLIENT_ID  = "485827581128-6tru18ug4jrd7ehfgdrqnvr17pj1mks0.apps.googleusercontent.com";//$this->setting_model->getSettingVal('google_api-client_id');//add new record at database
      try{
        $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
          $userid = $payload['sub'];
          // If request specified a G Suite domain:
          //$domain = $payload['hd'];
          $auth           = (new User())->getDetailByGoogleID($google_id);
          if($auth){
            $token = $auth->createToken("auth_token")->plainTextToken;
  
            $return = array(
                "status"    => "success",
                "messages"  => "Login success",
                "user"      => $auth,
                "token"     => $token
            );
          }else{
            try{
              //get google data
              $google_api = curl_init();
              curl_setopt($google_api, CURLOPT_URL, 'https://oauth2.googleapis.com/tokeninfo?id_token='.$id_token);
    
              // Receive server response ...
              curl_setopt($google_api, CURLOPT_RETURNTRANSFER, true);
              
              $google_data = curl_exec($google_api);
              curl_close($google_api);

              //create user
              $regist = curl_init();
              try{
                curl_setopt($regist, CURLOPT_URL, url('api/v1/g-regist') );
                curl_setopt($regist, CURLOPT_POST, 1);
                curl_setopt($regist, CURLOPT_POSTFIELDS,
                            http_build_query(array('google_id'  => $google_id,
                                                  'email'       => $google_data['email'],
                                                  'name'        => $google_data['name'],
                                                  'verified'    => $google_data['email_verified']
                                                )));
      
                // Receive server response ...
                curl_setopt($regist, CURLOPT_RETURNTRANSFER, true);
                
                $server_output = curl_exec($regist);
                curl_close($regist);
              }catch (Exception $e){
                $return = array(
                  "status"    => "error",
                  "messages"  => "Error! Problem with creating User"
                );
              }
              
              $auth  = $this->user_model->getDetailByGoogleID($google_id);
              $token = $auth->createToken("auth_token")->plainTextToken;
    
              $return = array(
                  "status"    => "success",
                  "messages"  => "Login success",
                  "user"      => $auth,
                  "token"     => $token
              );
            }catch (Exception $e){
              $return = array(
                "status"    => "error",
                "messages"  => "Error! Can't fetch Google data",
              );
            }
            
          }
        } else {
          // Invalid ID token
          $return = array(
              "status"    => "error",
              "messages"  => "Login error! Something wrong with Google Sign in",
          );
        }
      }catch (Exception $e) {
        $return = array(
            "status"    => "error",
            "messages"  => "Login error! Something wrong with Google Sign in",
        );
      }
      return response()->json($return, 200);
    }
  
}
