<?php
namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Http\Models\UserModel;
use App\Http\Models\DepartmentModel;

class AuthController extends Controller
{

    public function __construct(){
      $this->user_model = new UserModel();
      $this->department_model = new DepartmentModel();

    }

    public function registers(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name'      => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users',
            'password'        => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = $this->user_model->insertData(array(
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'username'    => $request->username,
            'password'    => Hash::make($request->password),
            'email'       => $request->email,
            'affiliate_code'  => "",
            'status'          => "active",
            'phone'           => "",
            'address'         => "",
            'activation_code' => "",
            'id_department'   => $this->department_model->getDefaultDepartment(),
            'referral_code'   => ""
         ));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }

    public function resetPassword(Request $request){      
      $validator = Validator::make($request->all(),[
        "email"        => "required"
      ]);
  
      if ($validator->fails()) {
        $return = array(
          "status"    => "error_validation",
          "message"  => $validator->errors()
        );
      }else{
        $auth    = $this->user_model->getDetailByEmail($request->email);
        if($auth){
          $password  = $this->user_model->getRandomPassword(6);
          $newdata   = array(
            "id_user"  => $auth->id_user,
            "password" => md5($password)
          );
  
          $this->user_model->updateData($newdata);
  
          $dataemail = $auth->toArray();
          $dataemail['password'] = $password;
          try{
            Mail::send('emails.user_reset', $dataemail, function ($mail) use ($dataemail) {
              $this->settingmodel = new SettingModel();

              $sender             = $this->setting_model->getSettingVal("email_sender_name");
              $senderaddress      = $this->setting_model->getSettingVal("email_sender_address");

              $mail->from($senderaddress, $sender);
              $mail->to($dataemail['email'], $dataemail['first_name']);
              $mail->subject('Reset password');
            });
          }catch(Exception $e){
              return response([
                  'status' => 'fail',
                  'message' => 'failed to send email'
              ]);
              exit;
          }
  
          $return = array(
            "status"    => "200",
            "message"  => "Success, Check email untuk reset"
          );
        }else{
          $return = array(
            "status"    => "error_db",
            "message"  => "Email tidak ditemukan!"
          );
        }
      }
      return response()->json($return, 200);
    }
}
