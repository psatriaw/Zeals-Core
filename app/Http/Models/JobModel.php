<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class JobModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_job';
	protected $primaryKey   = 'id_job';
	protected $fillable     = ['id_job', 'job_name','status','time_created','last_update'];

	public function getListPekerjaan(){
		return JobModel::where("status","active")->orderBy("job_name","ASC")->pluck("job_name","id_job");
	}

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = JobModel::where(function($query) use ($keyword){
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
		$data = JobModel::where(function($query) use ($keyword){
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
      $data = JobModel::where("id_cart",$id_cart)
              ->join(DB::raw("(SELECT id_account, id_shop FROM ".DB::getTablePrefix()."shop WHERE id_account = $id_user) AS ".DB::getTablePrefix()."shop"),"shop.id_shop","=",$this->table.".id_shop")
              ->first();
      return $data;
    }else{
      $data = JobModel::where("id_cart",$id_cart)
              ->where("id_shop","0")
              ->first();
      return $data;
    }
  }

	public function getDataShop($id_user) {
		$data = JobModel::where("id_account",$id_user)->first();
		return $data;
	}

	public function getDataShippingOfCart($id_cart){
		$data = JobModel::where("id_cart",$id_cart)->get();
		return $data;
	}


	public function getDetail($id) {
		$data = JobModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = JobModel::create($data);
		return $result;
  }

  public function getShippingData($id_cart){
    return JobModel::where("id_cart",$id_cart)->get();
  }

	public function updateData($data){
		$result = JobModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return JobModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		$result = JobModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

	public function getAuth($data){
		return JobModel::where("username",$data['username'])->where("password",$data['password'])->where("status","active")->first();
	}

	public function activateAccount($code, $data){
		return JobModel::where("activation_code",$code)->update($data);
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

      $checkavailability = JobModel::where("activation_code",$code)->count();
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

      $checkavailability = JobModel::where("affiliate_code",$code)->count();
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
