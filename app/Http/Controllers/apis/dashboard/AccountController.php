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
        $order_by   = ($request->order_by)?$request->order_by:"date_created";
        $order_type = ($request->order_type)?$request->order_type:"desc";
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        // $special_fields = ['total_campaign','total_reach','total_visit','total_interest','total_action','total_sales'];
        // if(in_array($order_by,$special_fields)){
        //     $special_fields = [$order_by];
        //     $order_by = $order_by."_count";
        // }

        // $subjects   = (new User())->with('department')->withCount($special_fields);

        // $data       = $subjects->orwhere(function($query) use ($keyword){
        //                     $query->where("first_name",'LIKE', "%{$keyword}%");
        //                     $query->orwhere("last_name",'LIKE', "%{$keyword}%");
        //                     $query->orwhere("email",'LIKE', "%{$keyword}%");
        //                     $query->orwhere("phone",'LIKE', "%{$keyword}%");
        //                 })
        //                 ->orWhereHas('department', function($query) use ($keyword){
        //                     $query->where("name",'LIKE',"%{$keyword}%");
        //                 });
                        
        // $total      = $data->count();

        // $data       = $data->skip($skip)->orderBy($order_by,$order_type)->limit($limit)->get();
        $table  = "tb_user";

        $data = User::where(function ($query) use ($keyword) {
            if ($keyword != "") {
                $query->where("first_name", "like", "%" . $keyword . "%");
                $query->orwhere("last_name", "like", "%" . $keyword . "%");
                $query->orwhere("email", "like", "%" . $keyword . "%");
                $query->orwhere("username", "like", "%" . $keyword . "%");
                $query->orwhere("phone", "like", "%" . $keyword . "%");
                $query->orwhere("address", "like", "%" . $keyword . "%");
                $query->orWhereHas("department",function($query) use ($keyword){
                    $query->where("name",'LIKE',"%{$keyword}%");
                });
            }
        })
        ->wherein($table . ".status", array("active", "inactive"))
        ->select($table . ".*","total_reach","total_visit","total_action","total_read","total_acquisition","total_created","total_redemption","total_campaign","total_downline")
        ->with('department');

        if($start_date!=""){
            $start_date = strtotime($start_date." 00:00:00");
		    $end_date 	= strtotime($end_date." 23:59:59");

            $data = $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_campaign, id_user as idu FROM tb_campaign_unique_link GROUP BY id_user)thej"),"thej.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_downline, referral_code FROM tb_user WHERE status = 'active' GROUP BY referral_code)reff"),"reff.referral_code","=",$table.".activation_code")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'initial'  GROUP BY id_user)init"),"init.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_visit,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'visit'  GROUP BY id_user)init2"),"init2.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_read,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'read'  GROUP BY id_user)init3"),"init3.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_action,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'action'  GROUP BY id_user)init4"),"init4.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_acquisition,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'acquisition'  GROUP BY id_user)init5"),"init5.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_created,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker  GROUP BY id_user)init7"),"init7.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND tb_voucher.status = 'used'  GROUP BY id_user)init6"),"init6.idu","=",$table.".id_user");
        }else{

            $data = $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_campaign, id_user as idu FROM tb_campaign_unique_link GROUP BY id_user)thej"),"thej.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_downline, referral_code FROM tb_user WHERE status = 'active' GROUP BY referral_code)reff"),"reff.referral_code","=",$table.".activation_code")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach,id_user as idu FROM tb_campaign_tracker WHERE  type_conversion = 'initial'  GROUP BY id_user)init"),"init.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_visit,id_user as idu FROM tb_campaign_tracker WHERE  type_conversion = 'visit'  GROUP BY id_user)init2"),"init2.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_read,id_user as idu FROM tb_campaign_tracker WHERE  type_conversion = 'read'  GROUP BY id_user)init3"),"init3.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_action,id_user as idu FROM tb_campaign_tracker WHERE  type_conversion = 'action'  GROUP BY id_user)init4"),"init4.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_acquisition,id_user as idu FROM tb_campaign_tracker WHERE  type_conversion = 'acquisition'  GROUP BY id_user)init5"),"init5.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_created,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker  GROUP BY id_user)init7"),"init7.idu","=",$table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND tb_voucher.status = 'used'  GROUP BY id_user)init6"),"init6.idu","=",$table.".id_user");
        }

        $total = $data->count();

        $data = $data->orderBy($order_by, $order_type)
                ->skip($skip)
                ->limit($limit);

        // if($id_department!=""){
        //   $data = $data->where($table.".id_department",$id_department);
        // }

        $data = $data->get();

        if($data){
            $return = [
                "status"    => "success",
                "form"      => [
                    "order_by"     => $order_by,
                    "order_type"   => $order_type
                ],
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
