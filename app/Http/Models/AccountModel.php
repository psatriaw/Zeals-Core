<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AccountModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'date_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_account';
	protected $primaryKey   = 'id_account';
	protected $fillable     = ['id_account','last_update','status','id_service','id_user','account_start_time','account_end_time','time_apply'];

  public function user(){
    return $this->hasOne(UserModel::class,"id_user");
  }

  public function service(){
    return $this->hasOne(ServiceModel::class,"id_service");
  }

  public function transaction(){
    return $this->belongsTo(TransactionModel::class,"id_account","id_ref")
            ->leftjoin(DB::raw("(SELECT name as mc_bank_name,code FROM tb_bank)tb_bank"),"tb_bank.code","=","tb_transaction.mc_bank_target")
            ->leftjoin(DB::raw("(SELECT CONCAT(first_name,' ', last_name) as mc_moderator_name,id_user FROM tb_user)tb_user"),"tb_user.id_user","=","tb_transaction.mc_moderator_id")
            ->where("type","account")
            ->orderBy("id_transaction");
  }

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = AccountModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("tb_user.first_name","like","%".$keyword."%");
								$query->orwhere("tb_user.last_name","like","%".$keyword."%");
								$query->orwhere("tb_user.email","like","%".$keyword."%");
								$query->orwhere("tb_user.username","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive"))
						->select($this->table.".*","tb_user.first_name","tb_user.last_name","tb_user.email","tb_user.username","tb_service.service_name")
            ->join("tb_user","tb_user.id_user","=",$this->table.".id_user")
            ->join("tb_service","tb_service.id_service","=",$this->table.".id_service")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = AccountModel::where(function($query) use ($keyword){
              if($keyword!=""){
                $query->where("tb_user.first_name","like","%".$keyword."%");
                $query->orwhere("tb_user.last_name","like","%".$keyword."%");
                $query->orwhere("tb_user.email","like","%".$keyword."%");
                $query->orwhere("tb_user.username","like","%".$keyword."%");
              }
						})
						->wherein($this->table.".status",array("active","inactive"))
            ->join("tb_user","tb_user.id_user","=",$this->table.".id_user")
            ->join("tb_service","tb_service.id_service","=",$this->table.".id_service")
						->count();
		return $data;
	}

	public function getDetail($id) {
		$data = AccountModel::find($id);
    $return = array(
      "account"     => $data->toArray(),
      "service"     => $data->service()->first()->toArray(),
      "user"        => $data->user()->first()->toArray(),
      "transaction" => ($data->transaction()->first())?$data->transaction()->first()->toArray():""
    );
		return $return;
	}

	public function insertData($data){
		$result = AccountModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = AccountModel::where($this->primaryKey,$data['id_account'])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return AccountModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		$result = AccountModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

	public function getAuth($data){
		return AccountModel::where("username",$data['username'])->where("password",$data['password'])->where("status","active")->first();
	}

	public function activateAccount($code, $data){
		return AccountModel::where("activation_code",$code)->update($data);
	}

	public function createActivationCode(){
    $code = "";
    $length = 16;
    $status = true;
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    do{
      $code = "";
      for($i=0; $i < $length; $i++){
        $code = $code.substr($alphabet,rand(0,strlen($alphabet)-1),1);
      }

      $checkavailability = UserModel::where("activation_code",$code)->count();
      if($checkavailability){
        $status = true;
      }else{
        $status = false;
      }
    }while($status);

    return $code;
  }

	public function createaffiliatecode(){
		$code = "";
    $length = 8;
    $status = true;
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    do{
      $code = "";
      for($i=0; $i < $length; $i++){
        $code = $code.substr($alphabet,rand(0,strlen($alphabet)-1),1);
      }

      $checkavailability = AccountModel::where("affiliate_code",$code)->count();
      if($checkavailability){
        $status = true;
      }else{
        $status = false;
      }
    }while($status);

    return $code;
	}

	public function getRandomPassword($length){
		$code 		= "";
    $status 	= true;
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		for($i=0; $i < $length; $i++){
			$code = $code.substr($alphabet,rand(0,strlen($alphabet)-1),1);
		}
		return $code;
	}
}
