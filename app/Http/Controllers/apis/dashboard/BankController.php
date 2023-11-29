<?php

namespace App\Http\Controllers\apis\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

use App\Models\{BankList};
use Auth;

class BankController extends Controller
{
    public function list(Request $request){
        $user       = auth('sanctum')->user();
        $limit      = $request->perPage;
        $skip       = ($request->currentPage-1)*$limit;
        $keyword    = $request->keyword;

        $subjects   = new BankList();
        $total      = $subjects->count();

        $data       = $subjects->where(function($query) use ($keyword){
                            $query->where("nama",'LIKE', "%{$keyword}%");
                        })->skip($skip)->orderBy("nama","ASC")->limit($limit)->get();

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
        $user   = auth('sanctum')->user();

        $detail    = BankList::where("id",$request->id)->first();

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
        $bank = new BankList();

        $data = array(
            "kode"          => $request->kode,
            "nama"          => $request->nama,
            "bank_code"     => $request->bank_code,
        );

        $rules  = array(
            "kode"         => "required",
            "nama"         => "required",
            "bank_code"    => "required"
        );

        $messages = array(
            "kode.required"         => "Please fill universal bank code field!",
            "nama.required"         => "Please fill bank name field!",
            "bank_code.required"    => "Please fill PG bank code field!",
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

        $create                 = $bank->create($data);

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
            "kode"          => $request->kode,
            "nama"          => $request->nama,
            "bank_code"     => $request->bank_code,
        );

        $rules  = array(
            "kode"         => "required",
            "nama"         => "required",
            "bank_code"    => "required"
        );

        $messages = array(
            "kode.required"         => "Please fill universal bank code field!",
            "nama.required"         => "Please fill bank name field!",
            "bank_code.required"    => "Please fill PG bank code field!",
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

        $update                 = (new BankList())->where("id",$request->id)->update($data);

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
        $deletion =  (new  BankList())::where("id",$request->id)->delete();
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
