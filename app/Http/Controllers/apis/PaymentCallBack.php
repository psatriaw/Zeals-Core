<?php
namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Http\Models\UserModel;
use App\Http\Models\WithdrawalModel;
use App\Http\Models\TopupModel;
use App\Http\Models\PaymentCallbackModel;

class PaymentCallBack extends Controller
{

    public function __construct(){
      $this->user_model       = new UserModel();
      $this->topup_model      = new TopupModel();

    }

    public function xenditdisbursementcallback(Request $request){
        /*
        $validator = Validator::make($request->all(),[
            'first_name'      => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users',
            'password'        => 'required|string|min:8'
        ]);


        if($validator->fails()){
            return response()->json($validator->errors());
        };
        */

        $validator = Validator::make($request->all(),[
            'status'          => 'required',
            'amount'          => 'required|numeric',
            'bank_code'       => 'required'
        ]);


        if($validator->fails()){
            return response()->json($validator->errors());
        };

        if($request->status=="COMPLETED"){
          $status = "completed";
        }elseif($request->status=="FAILED"){
          $status = "invalid";
        }

        $external_id      = $request->external_id;
        $transaction_type = substr($external_id,0,3);

        $payment_callback_model = new PaymentCallbackModel();
        $payment_callback_model->insertData(['json_callback'=> json_encode($request->all()),'provider' => 'xendit','created_at' => now(), 'updated_at' => now()]);

        print_r($transaction_type);

        switch($transaction_type){
          case "WTL":
            //withdrawal
            print "WITHDRAWAL";

            $withdrawal_model = new WithdrawalModel();
            $detail           = $withdrawal_model->getDetailByCode($external_id);
            $detail->status   = $status;
            $detail->trx_status = $status;
            $detail->callback_response = json_encode($request->all());
            $withdrawal_model->updateData($detail->toArray());
          break;

          case "":

          break;
        }



        //return response()->json(['data' => $data, 'token_type' => 'Bearer', ]);
    }

}
