<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ShopModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_shop';
	protected $primaryKey   = 'id_shop';
	protected $fillable     = ['id_shop', 'shop_name','id_account','shortlink','description','balance','time_created','last_balance_update','status','id_template','last_update'];

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = ShopModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("shop_name","like","%".$keyword."%");
								$query->orwhere("shortlink","like","%".$keyword."%");
								$query->orwhere("first_name","like","%".$keyword."%");
								$query->orwhere("last_name","like","%".$keyword."%");
								$query->orwhere("email","like","%".$keyword."%");
								$query->orwhere("username","like","%".$keyword."%");
								$query->orwhere("phone","like","%".$keyword."%");
								$query->orwhere("address","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive"))
						->select($this->table.".*","tb_user.first_name","tb_user.last_name","tb_template.template_name")
						->join("tb_account","tb_account.id_account","=",$this->table.".id_account")
						->join("tb_user","tb_user.id_user","=","tb_account.id_user")
						->leftjoin("tb_template","tb_template.id_template","=",$this->table.".id_template")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = ShopModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("shop_name","like","%".$keyword."%");
								$query->orwhere("shortlink","like","%".$keyword."%");
								$query->orwhere("first_name","like","%".$keyword."%");
								$query->orwhere("last_name","like","%".$keyword."%");
								$query->orwhere("email","like","%".$keyword."%");
								$query->orwhere("username","like","%".$keyword."%");
								$query->orwhere("phone","like","%".$keyword."%");
								$query->orwhere("address","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive"))
						->join("tb_account","tb_account.id_account","=",$this->table.".id_account")
						->join("tb_user","tb_user.id_user","=","tb_account.id_user")
						->count();
		return $data;
	}

	public function getDetail($id) {
		$data = ShopModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = ShopModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = ShopModel::where($this->primaryKey,$data['id_user'])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return ShopModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		$result = ShopModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

	public function getAuth($data){
		return ShopModel::where("username",$data['username'])->where("password",$data['password'])->where("status","active")->first();
	}

	public function activateAccount($code, $data){
		return ShopModel::where("activation_code",$code)->update($data);
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

      $checkavailability = ShopModel::where("activation_code",$code)->count();
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

      $checkavailability = ShopModel::where("affiliate_code",$code)->count();
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
