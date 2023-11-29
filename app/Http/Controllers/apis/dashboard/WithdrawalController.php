<?php

namespace App\Http\Controllers\apis\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

use App\Models\{Withdrawal, Setting, Log};
use Xendit\Xendit;

class WithdrawalController extends Controller
{
    public function approve(Request $request){
        $withdrawal     = Withdrawal::where("id_withdrawal",$request->id)->first();
        $logs           = Log::where("subject_id",$request->id)->where("subject_type","withdrawal")->count();
        if($logs>0){
            $newWTH     = $withdrawal->withdrawal_code."R";
            $withdrawal->update(["withdrawal_code" => $newWTH,"last_update" => time()]);
            $withdrawal->withdrawal_code = $newWTH;
        }

        $xendit_key     = Setting::where("code_setting","xendit_api_key")->first()->setting_value;
        $user           = auth('sanctum')->user();

        try{
            Xendit::setApiKey($xendit_key);

            $params = [
                'external_id'           => $withdrawal->withdrawal_code,
                'amount'                => $withdrawal->total_pencairan - $withdrawal->fee,
                'bank_code'             => $withdrawal->nama_bank,
                'account_holder_name'   => $withdrawal->nama_pemilik_rekening,
                'account_number'        => $withdrawal->nomor_rekening,
                'description'           => 'Pencairan dana dengan kode #'.$withdrawal->withdrawal_code,
                'X-IDEMPOTENCY-KEY'     => $withdrawal->withdrawal_code
            ];

            $disbursementRequest = \Xendit\Disbursements::create($params);

            if($disbursementRequest['status']=="PENDING"){
                $data       = [
                    "id_withdrawal"     => $request->id,
                    "last_update"       => time(),
                    "status"            => "approved",
                    "trx_status"        => $disbursementRequest['status'],
                    "callback_response" => json_encode($disbursementRequest)
                ]; 
        
                $approval   =  (new  Withdrawal())::where("id_withdrawal",$request->id)->update($data);
                if($approval){
                    $log = [
                        "user_id"       => $user->id_user,
                        "action"        => "Approve",
                        "description"   => "Success withdrawal #".$withdrawal->withdrawal_code,
                        "data"          => json_encode($disbursementRequest),
                        "subject_id"    => $request->id,
                        "subject_type"    => "withdrawal"
                    ];

                    $response =  [
                        "status"    => "success",
                        "messages"  => "Transfer request successfully created!"
                    ];
                }else{
                    $log = [
                        "user_id"       => $user->id_user,
                        "action"        => "Approve",
                        "description"   => "Failed withdrawal #".$withdrawal->withdrawal_code,
                        "data"          => json_encode($disbursementRequest),
                        "subject_id"    => $request->id,
                        "subject_type"    => "withdrawal"
                    ];

                    $response =  [
                        "status"    => "error",
                        "messages"  => "Failed to create transfer request!"
                    ];
                }

                Log::create($log);

            }else{
                $response =  [
                    "status"    => "error",
                    "messages"  => "Failed to delete data!",
                    "response"  => $disbursementRequest
                ];
            }
        }catch(Exception $message){
            $response =  [
                "status"    => "error",
                "messages"  => $message
            ];
        }


        return response()->json($response,200);
    }

    public function list(Request $request){
        $user       = auth('sanctum')->user();
        $limit      = $request->perPage;
        $skip       = ($request->currentPage-1)*$limit;
        $keyword    = $request->keyword;

        $withdrawal = (new Withdrawal())->with('user');
        $data       = (new Withdrawal());
        $total      = $data->count();
        $requested  = $data->whereNotIn("status",['completed'])->sum("total_pencairan") - $data->whereNotIn("status",['completed'])->sum("fee");

        $data       = $withdrawal->where(function($query) use ($keyword){
                            $query->where("withdrawal_code",'LIKE', "%{$keyword}%");
                            $query->orwhere("nama_pemilik_rekening",'LIKE', "%{$keyword}%");
                            $query->orwhere("nama_bank",'LIKE', "%{$keyword}%");
                            $query->orwhere("nomor_rekening",'LIKE', "%{$keyword}%");
                            $query->orwhere("total_pencairan",'LIKE', "%{$keyword}%");
                        })->skip($skip)->orderBy("withdrawal_code","ASC")->limit($limit)->get();

        if($data){
            $return = [
                "status"    => "success",
                "data"      => $data,
                "total"     => $total,
                "requested" => $requested,
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
        $user   = auth('sanctum')->user();

        $detail    = Withdrawal::with('user')->where("id_withdrawal",$request->id)->first();

        if($detail){
            $return = [
                "status"    => "success",
                "data"      => $detail,
                "base_path" => url(''),
                "logs"      => Log::with("author")->where("subject_type","withdrawal")->where("subject_id",$request->id)->orderBy("id","DESC")->get()
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
        $industry = new SektorIndustri();

        $data = array(
            "nama_sektor_industri"      => $request->nama_sektor_industri,
            "status"                    => "active",
            "time_created"              => time(),
            "last_update"               => time(),
        );

        $rules  = array(
            "nama_sektor_industri"         => "required",
        );

        $messages = array(
            "nama_sektor_industri.required"        => "Please fill name field!",
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

        if($request->hasFile('icon')){
            $file           = $request->file('icon'); 
            $upload_path    = public_path('templates/category/icon/');
  
            File::isDirectory($upload_path) or File::makeDirectory($upload_path, 0777, true, true);
  
            $file_name          = $file->getClientOriginalName();
            $size               = $file->getSize();
  
            $generated_new_name = time().'-'.rand(1111,9999).$file_name;
            $file->move($upload_path, $generated_new_name);
  
            $photo    = "templates/category/icon/".$generated_new_name;
  
            $data['icon']     = $photo;
        }

        $create                 = $industry->create($data);

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
        $data = array(
            "id_sektor_industri"        => $request->id_sektor_industri,
            "nama_sektor_industri"      => $request->nama_sektor_industri,
            "status"                    => $request->status,
            "last_update"               => time(),
        );

        $rules  = array(
            "nama_sektor_industri"         => "required",
        );

        $messages = array(
            "nama_sektor_industri.required"        => "Please fill name field!",
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

        if($request->hasFile('icon')){
            $file           = $request->file('icon'); 
            $upload_path    = public_path('templates/category/icon/');
  
            File::isDirectory($upload_path) or File::makeDirectory($upload_path, 0777, true, true);
  
            $file_name          = $file->getClientOriginalName();
            $size               = $file->getSize();
  
            $generated_new_name = time().'-'.rand(1111,9999).$file_name;
            $file->move($upload_path, $generated_new_name);
  
            $photo    = "templates/category/icon/".$generated_new_name;
  
            $data['icon']     = $photo;
        }

        $update                 = (new SektorIndustri())->where("id_sektor_industri",$request->id_sektor_industri)->update($data);

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
        $deletion =  (new  Withdrawal())::where("id_withdrawal",$request->id)->delete();
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
}
