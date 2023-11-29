<?php

namespace App\Http\Controllers\apis\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\File;

use App\Models\{User};

class UserController extends Controller
{
    public function profile(Request $request){

        $user        = $user = auth('sanctum')->user();
  
        if($user){
          $response = array(
              "status"      => "success",
              "data"        => User::where("id_user",$user->id_user)->with('saldo')->first(),
              "base_path"   => url('')
          );
        }else{
          $response = array(
              "status"    => "error",
              "message"   => "Need login" 
            );
        }
  
        return response()->json($response,200);
    }

    public function updateprofile(Request $request){
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
            "gender"        => $request->gender,
            "dob"           => $request->dob,
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

        if($request->hasFile('avatar')){
            $file           = $request->file('avatar'); 
            $upload_path    = public_path('uploads/');
  
            File::isDirectory($upload_path) or File::makeDirectory($upload_path, 0777, true, true);
  
            $file_name          = $file->getClientOriginalName();
            $size               = $file->getSize();
  
            $generated_new_name = time().'-'.rand(1111,9999).$file_name;
            $file->move($upload_path, $generated_new_name);
  
            $photo    = "uploads/".$generated_new_name;
  
            $data['avatar']     = $photo;
        }

        $user       = User::where("id_user",$user->id_user);
        $update     = $user->update($data);
        $user       = $user->with('department')->first();
        $user['base_path']  = url('');

        if($update){
          $response = array(
              "status"      => "success",
              "messages"    => "Profile updated!",
              "user"        => $user
          );
        }else{
          $response = array(
              "status"      => "error",
              "messages"   => "No data update" 
            );
        }
  
        return response()->json($response,200); 
    }
}
