<?php

namespace App\Http\Controllers\apis\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Xendit\Xendit;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

use App\Models\{Setting, MasterTrx};

class XenditController extends Controller
{
    public function balance(Request $request){
        $xendit_key     = Setting::where("code_setting","xendit_api_key")->first()->setting_value;
        Xendit::setApiKey($xendit_key);

        $infobalance = \Xendit\Balance::getBalance('CASH');
        
        $response =  [
            "status"    => "success",
            "messages"  => $infobalance
        ];

        return response()->json($response,200);
    }

    public function history(Request $request){
        $history    = MasterTrx::where("type","topup")->with('user')->orderBy("id","DESC")->get();
        
        $response   =  [
            "status"    => "success",
            "data"      => $history
        ];

        return response()->json($response,200);
    }

    public function topup(Request $request){
        $data = array(
            "amount"      => $request->amount,
        );

        $rules  = array(
            "amount"         => "required",
        );

        $messages = array(
            "amount.required"        => "Please fill total amount field!",
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

        $user           = auth('sanctum')->user();
        $xendit_key     = Setting::where("code_setting","xendit_api_key")->first()->setting_value;
        Xendit::setApiKey($xendit_key);

        $mastertrx      = (new MasterTrx());
        $trx_code       = $mastertrx->createCode();
        
        $params = [
            "external_id"     => $trx_code,
            "bank_code"       => $request->bank,   //accepted bank code MANDIRI, BNI, BNI_SYARIAH, BRI, PERMATA, BCA, SAHABAT_SAMPOERNA
            "name"            => $user->first_name." ".$user->last_name,
            "is_closed"       =>  true,
            "expected_amount" => (int)$request->amount,
        ];

        $createVA = \Xendit\VirtualAccounts::create($params);

        $datatrx        = [
            "user_id"       => $user->id_user,
            "amount"        => $request->amount,
            "trx_code"      => $trx_code,
            "va_number"     => $createVA['account_number'],
            "data_request"  => json_encode($createVA),
            "status"        => "pending",
            "data_callback" => "",
            "vendor"        => "xendit",
            "type"          => "topup",
            "sign"          => 1
        ];

        $createrequest  = $mastertrx->create($datatrx);
        // $createrequest  = true;

        if($createrequest){
            $response =  [
                "status"    => "success",
                "messages"  => "Topup successfully requested!",
                "data"      => $datatrx,
                "request"   => $request->all()
            ];
        }else{
            $response =  [
                "status"    => "error",
                "messages"  => "Failed to request topup!"
            ];
        }
       
        return response()->json($response,200);

    }
}
