<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{Withdrawal, User};

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;

class WithdrawalController extends Controller
{
    public function __construct(){
        
    }

    public function create(Request $request){
        $user        = $user = auth('sanctum')->user();
        $user        = User::where("id_user",$user->id_user)->with('saldo')->first();
        $admin       = 10000;
        $min_trx     = 0;

        $rules  = array(
            "total_pencairan"       => "required|numeric|min:$min_trx",
            "nama_bank"             => "required",
            "nama_pemilik_rekening" => "required",
            "nomor_rekening"        => "required",
        );

        $messages = array(
            "total_pencairan.required"       => "Please fill total amount!",
            "total_pencairan.numeric"        => "Please fill total amount with only numeric!",
            "nama_bank"             => "Please fill your bank account through your zeals's account",
            "nomor_rekening"        => "Please fill your account numer through your zeals's account",
            "nama_pemilik_rekening" => "Please fill your account name through your zeals's account"
        );


        $data        = [
            "id_user"           => $user->id_user,
            "nomor_rekening"    => $user->nomor_rekening,
            "nama_bank"         => $user->nama_bank,
            "nama_pemilik_rekening"   => $user->nama_pemilik_rekening,
            "status"            => "pending",
            "total_pencairan"   => $request->amount,
            "withdrawal_code"   => (new Withdrawal())->createCode(),
            "time_created"      => time(),
            "last_update"       => time(),
            "fee"               => $admin
        ];

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

        if(Withdrawal::where("id_user",$user->id_user)->where("status","pending")->count()){
            $error = array(
                "status"    => "error_validation",
                "data"      => $data,
                "messages"  => [
                    "pending" => ["Your previous request still in pending status. Please wait, we are about to review your request."]
                ],
            );

            return response()->json($error, 200);
            exit();
        }
    
        if($request->amount>$user->saldo['saldo']){
            $error = array(
                "status"    => "error_validation",
                "data"      => $data,
                "messages"  => [
                    "saldo" => ["You are about to withdraw more than your balance. Your balance is IDR ".$user->saldo['saldo']]
                ],
            );

            return response()->json($error, 200);
            exit();
        }

        $update     = Withdrawal::create($data);
  
        if($update){
          $response = array(
              "status"    => "success",
              "message"   => "Withdrawal request successfully created!"
          );
        }else{
          $response = array(
              "status"    => "error",
              "message"   => "Failed to create withdrawal request" 
            );
        }
  
        return response()->json($response,200);
    }

    public function list(Request $request){
        $user       = $user = auth('sanctum')->user();

        $list       = Withdrawal::where("id_user",$user->id_user)->orderBy("id_withdrawal","DESC")->get();

        if($list){
            $response = array(
                "status"    => "success",
                "data"      => $list
            );
          }else{
            $response = array(
                "status"    => "error",
                "message"   => "No withdrawal request found"
              );
          }
    
          return response()->json($response,200);
    }
}
