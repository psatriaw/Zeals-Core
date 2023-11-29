<?php 
namespace App\Services\Withdrawal;

use App\Models\ { Withdrawal };
use App\Http\Models\ { RekeningDanaModel };
 
class CreateWithdrawal {

    public function __construct($request){
        $this->rekening_dana_model = new RekeningDanaModel();
        $this->withdrawal_model = new Withdrawal();
        $this->request = $request;
    }

    public function store($user,$login){
        $data = array(
            "id_user"               => $user->id_user,
            "nomor_rekening"        => $user->nomor_rekening,
            "nama_bank"             => $user->nama_bank,
            "nama_pemilik_rekening" => $user->nama_pemilik_rekening,
            "time_created"          => time(),
            "last_update"           => time(),
            "total_pencairan"       => $this->request->total_pencairan,
            "status"                => "pending",
            "withdrawal_code"       => $this->withdrawal_model->createCode()
        );

        $checkanywithdrawal     = $this->withdrawal_model->checkANyPendingWithdrawal($login);
        if(!$checkanywithdrawal){
            $infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);
            if($infosaldo->saldo>$data['total_pencairan']){
                $data['fee']            = $this->setting_model->getSettingVal("pajak_pencairan_value");

                $create                 = $this->withdrawal_model->insertData($data);
                if($create){
                    return ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Withdrawal request on demand'];
                }else{
                    return ['status' => 'error', 'alert-class' => 'alert-danger', 'message' => 'Failed to request a withdrawal'];
                }
            }else{
                return ['status' => 'error', 'alert-class' => 'alert-danger', 'message' => 'Total amount exceeds your current balance! Please make a lower request'];
            }

        }else{
            return ['status' => 'error', 'alert-class' => 'alert-danger', 'message' => 'You have any queue of withdrawal, Please be patient until the previous request responded'];
        }
    }

}

?>