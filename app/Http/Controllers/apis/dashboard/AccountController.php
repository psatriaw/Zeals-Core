<?php

namespace App\Http\Controllers\apis\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\DB;

use App\Models\{User, Department};
use Auth;

class AccountController extends Controller
{
    public function list(Request $request){
        $user       = auth('sanctum')->user();
        $limit      = $request->perPage;
        $skip       = ($request->currentPage-1)*$limit;
        $keyword    = $request->keyword;

        $subjects   = (new User())->with('department')->withCount('total_campaign','total_reach','total_visit','total_interest','total_action','total_sales');

        $data       = $subjects->orwhere(function($query) use ($keyword){
                            $query->where("first_name",'LIKE', "%{$keyword}%");
                            $query->orwhere("last_name",'LIKE', "%{$keyword}%");
                            $query->orwhere("email",'LIKE', "%{$keyword}%");
                            $query->orwhere("phone",'LIKE', "%{$keyword}%");
                        })
                        ->orWhereHas('department', function($query) use ($keyword){
                            $query->where("name",'LIKE',"%{$keyword}%");
                        });
        $total      = $data->count();

        $data       = $data->skip($skip)->orderBy("date_created","DESC")->limit($limit)->get();

        if($data){
            $return = [
                "status"    => "success",
                "data"      => $data,
                "total"     => $total,
                "base_path" => url('')
            ];
        }else{
            $return = [
                "status"    => "error",
                "message"   => "No data found",
            ];
        }

        return response()->json($return, 200);
    }

    public function detail(Request $request){
        $user       = auth('sanctum')->user();

        $detail     = User::where("id_user",$request->id)->first();

        if($detail){
            $return = [
                "status"    => "success",
                "data"      => $detail,
                "base_path" => url('')
            ];
        }else{
            $return = [
                "status"    => "error",
                "message"   => "No data found",
            ];
        }

        return response()->json($return, 200);
    }

    public function create(Request $request){
        $object = new User();
        $department = new Department();

        $data = array(
            "first_name"        => $request->first_name,
            "last_name"         => $request->last_name,
            "email"             => $request->email,
            "phone"             => $request->phone,
            "dob"               => $request->dob,
            "gender"            => $request->gender,
            "id_job"            => $request->id_job,
            "id_wilayah"        => $request->id_wilayah,
            "address"           => $request->address,
            "longitude"         => $request->longitude,
            "latitude"          => $request->latitude,
            "bank_id"           => $request->bank_id,
            "nama_bank"         => $request->nama_bank,
            "nama_pemilik_rekening" => $request->nama_pemilik_rekening,
            "nomor_rekening"    => $request->nomor_rekening,
            "password"          => md5($request->password),
            "c_password"        => md5($request->c_password),
            "date_created"      => time(),
            "last_update"       => time(),
            "activation_code"   => $object->createActivationCode(),
            "id_department"     => $department->getDefaultDepartment(),
        );

        $rules  = array(
            "first_name"        => "required",
            "email"             => "required|email|unique:tb_user,email",
            "phone"             => "required|unique:tb_user,phone",
            "dob"               => "required",
            "gender"            => "required",
            "address"           => "required",
            "password"          => "required",
            "c_password"        => "required|same:password"
        );

        $messages = array(
            "first_name.required"       => "First name required!",
            "email.required"            => "Email required!",
            "email.email"               => "Please fill email field with valid email format!",
            "email.unique"              => "Email already used! Please use another email.",
            "phone.required"            => "Phone required!",
            "phone.unique"              => "Phone number already used! Please use another phone number",
            "dob.required"              => "Date of birth required!",
            "gender.required"           => "Gender required!",
            "address.required"          => "Address required!",
            "password.required"         => "Password required!",
            "c_password.required"       => "Password confirmation required!",
            "c_password.same"           => "Password & Confirmation different!",
        );

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $error = array(
              "status"      => "error_validation",
              "data"        => $validator->messages()
            );
      
            return response()->json($error, 200);
            exit();
        }

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

        $create                 = $object->create($data);

        if($create){

            $response =  [
                "status"    => "success",
                "messages"  => "Data successfully registered!"
            ];
        }else{
            $response =  [
                "status"    => "error",
                "messages"  => "Failed to register data!"
            ];
        }
       
        return response()->json($response,200);
    }

    public function update(Request $request){
        $user       = (new User())->where("id_user",$request->id_user)->first();

        $data = array(
            "first_name"        => $request->first_name,
            "last_name"         => $request->last_name,
            "email"             => $request->email,
            "phone"             => $request->phone,
            "dob"               => $request->dob,
            "gender"            => $request->gender,
            "id_job"            => $request->id_job,
            "id_wilayah"        => $request->id_wilayah,
            "address"           => $request->address,
            "longitude"         => $request->longitude,
            "latitude"          => $request->latitude,
            "bank_id"           => $request->bank_id,
            "nama_bank"         => $request->nama_bank,
            "nama_pemilik_rekening" => $request->nama_pemilik_rekening,
            "nomor_rekening"    => $request->nomor_rekening,
            "last_update"       => time(),
            "id_department"     => $request->id_department,
        );

        $rules  = array(
            "first_name"        => "required",
            "email"             => "required|email|unique:tb_user,email,".$user->id_user.",id_user",
            "phone"             => "required|unique:tb_user,phone,".$user->id_user.",id_user",
            "dob"               => "required",
            "gender"            => "required",
            "address"           => "required",
        );

        if($request->password){
            $data["password"]       = $request->password;
            $data["c_password"]     = $request->c_password;
            $rules["password"]       = "required";
            $rules["c_password"]     = "required|same:password";
        }

        $messages = array(
            "first_name.required"       => "First name required!",
            "email.required"            => "Email required!",
            "email.email"               => "Please fill email field with valid email format!",
            "email.unique"              => "Email already used! Please use another email.",
            "phone.required"            => "Phone required!",
            "phone.unique"              => "Phone number already used! Please use another phone number",
            "dob.required"              => "Date of birth required!",
            "gender.required"           => "Gender required!",
            "address.required"          => "Address required!",
            "password.required"         => "Password required!",
            "c_password.required"       => "Password confirmation required!",
            "c_password.same"           => "Password & Confirmation different!",
        );

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $error = array(
              "status"      => "error_validation",
              "data"        => $validator->messages()
            );
      
            return response()->json($error, 200);
            exit();
        }

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

        $update                 = (new User())->where("id_user",$request->id_user)->update($data);

        if($update){
            $response =  [
                "status"    => "success",
                "messages"  => "Data successfully updated!",
            ];
        }else{
            $response =  [
                "status"    => "error",
                "messages"  => "Failed to update data!"
            ];
        }
        return response()->json($response,200);
    }

    public function  delete(Request $request){
        $deletion =  (new  User())::where("id_user",$request->id)->delete();
        if($deletion){
            $response =  [
                "status"    => "success",
                "messages"  => "Data successfully deleted!"
            ];
        }else{
            $response =  [
                "status"    => "error",
                "messages"  => "Failed to delete data!"
            ];
        }

        return response()->json($response,200);
    }
    
    public function  cleanup(Request $request){
        $sets   =  (new  User())->where("status","inactive")->groupBy("email")->having("total",">",1)->select(DB::raw("COUNT(*) as total"),"email",DB::raw("max(id_user) as id_user"))->get();
        $done   = true;
        foreach($sets as $index=>$set){
            $deletion = User::where("email",$set->email)->where("status","inactive")->whereNotIn("id_user",[$set->id_user])->delete();
            if(!$deletion){
                $response =  [
                    "status"    => "error",
                    "messages"  => "Failed to delete data ".$set->email."!"
                ];
                $done = false;
                break;
            }
        }

        if($done){
            $response =  [
                "status"    => "success",
                "messages"  => "Clean Up success!"
            ];
        }

        return response()->json($response,200);
    }
}
