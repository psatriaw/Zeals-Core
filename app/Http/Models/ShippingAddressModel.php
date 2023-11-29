<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ShippingAddressModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'shipping_address';
	protected $primaryKey   = 'id_shipping_address';
	protected $fillable     = ['id_shipping_address', 'id_user','address_1','address_2','provinsi','kota','kecamatan','id_provinsi','id_kota','id_kecamatan','time_created','last_update','status'];

	public function getDefaultShipping($id_user){
		$data = ShippingAddressModel::where("id_user",$id_user)->where("default")->first();
		if($data){
			return $data;
		}else{
			return 0;
		}
	}

	public function getDefaultAddress($id_user){
		$data = ShippingAddressModel::where("id_user",$id_user)->whereIn("status",array("default","active"))->first();
		return $data;
	}

	public function getShippingAddress($id_user){
		return  ShippingAddressModel::where("id_user",$id_user)->whereIn("status",array("default","active"))->get();
	}

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = ShippingAddressModel::where(function($query) use ($keyword){
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
						->select($this->table.".*","user.first_name","user.last_name","template.template_name")
						->join("account","account.id_account","=",$this->table.".id_account")
						->join("user","user.id_user","=","account.id_user")
						->leftjoin("template","template.id_template","=",$this->table.".id_template")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = ShippingAddressModel::where(function($query) use ($keyword){
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
						->join("account","account.id_account","=",$this->table.".id_account")
						->join("user","user.id_user","=","account.id_user")
						->count();
		return $data;
	}

  public function getShippingInfo($login,$id_cart){
    $id_user = $login->id_user;
    if($login->id_department!="1"){
      $data = ShippingAddressModel::where("id_cart",$id_cart)
              ->join(DB::raw("(SELECT id_account, id_shop FROM ".DB::getTablePrefix()."shop WHERE id_account = $id_user) AS ".DB::getTablePrefix()."shop"),"shop.id_shop","=",$this->table.".id_shop")
              ->first();
      return $data;
    }else{
      $data = ShippingAddressModel::where("id_cart",$id_cart)
              ->where("id_shop","0")
              ->first();
      return $data;
    }
  }

  public function setAllNotDefault($id_user){
    $data = array("status" => "active");
    $data = ShippingAddressModel::where("id_user",$id_user)->update($data);
		return $data;
  }

	public function getListOfAddress($id_user) {
		$data = ShippingAddressModel::where("id_user",$id_user)->whereNotIn("status",['deleted','removed'])->get();
		return $data;
	}

	public function getDataShippingOfCart($id_cart){
		$data = ShippingAddressModel::where("id_cart",$id_cart)->get();
		return $data;
	}


	public function getDetail($id) {
		$data = ShippingAddressModel::where("id_shipping_address",$id)->get()->first();
		return $data;
	}

	public function getWebShipping(){
		$data = ShippingAddressModel::where("id_user",0)->first();
		return $data;
	}

	public function insertData($data){
		$result = ShippingAddressModel::create($data);
		return $result;
  }

  public function getShippingData($id_cart){
    return ShippingAddressModel::where("id_cart",$id_cart)->get();
  }

	public function updateData($data){
		$result = ShippingAddressModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return ShippingAddressModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		$result = ShippingAddressModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

	public function getAuth($data){
		return ShippingAddressModel::where("username",$data['username'])->where("password",$data['password'])->where("status","active")->first();
	}

	public function activateAccount($code, $data){
		return ShippingAddressModel::where("activation_code",$code)->update($data);
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

      $checkavailability = ShippingAddressModel::where("activation_code",$code)->count();
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

      $checkavailability = ShippingAddressModel::where("affiliate_code",$code)->count();
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
