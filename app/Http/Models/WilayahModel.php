<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WilayahModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'date_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_wilayah';
	protected $primaryKey   = 'id_wilayah';
	protected $fillable     = ['id_wilayah','kodeprov','namaprov','kodekab','namakab','map_code'];

	public function getDataTrackingByCity(){
		$data = WilayahModel::select(DB::raw("IFNULL(total_data,0) as total_data"),"namaprov","map_code")
						->leftjoin(DB::raw("(SELECT COUNT(*) as total_data, region_code FROM tb_campaign_tracker WHERE status='active' AND type_conversion = 'visit' GROUP BY region_code)reg"),"reg.region_code","=",$this->table.".map_code")
						->groupBy("map_code","namaprov")
						->orderBy("total_data","DESC")
						->skip(0)
						->limit(25)
						->get();

		return $data;
	}

	public function getWilayahAndDataTracking(){
		$data = WilayahModel::select(DB::raw("IFNULL(total_data,0) as total_data"),"map_code",$this->table.".id_wilayah")
						->leftjoin(DB::raw("(SELECT COUNT(*) as total_data, region_code FROM tb_campaign_tracker WHERE status='active' GROUP BY region_code)reg"),"reg.region_code","=",$this->table.".map_code")
						->orderBy("total_data","DESC")
						->get();

		if($data){
			foreach ($data as $key => $value) {
				if($value->map_code!=""){
					$collection[$value->map_code] = $value->total_data;
				}
			}
		}else{
			$collection = array();
		}
		return $collection;
	}

	public function getRegistrantByMap(){
		$data = WilayahModel::select(DB::raw("IFNULL(total_registrant,0) as total_registrant"),"map_code",$this->table.".id_wilayah")->join(DB::raw("(SELECT COUNT(*) as total_registrant, id_wilayah FROM tb_registran GROUP BY id_wilayah)reg"),"reg.id_wilayah","=",$this->table.".id_wilayah")->get();
		if($data){
			foreach ($data as $key => $value) {
				if($value->map_code!=""){
					$collection[$value->map_code] = @$collection[$value->map_code] + $value->total_registrant;
				}
			}
		}else{
			$collection = array();
		}
		return $collection;
	}

	public function getRegistrantByCity(){
		$data = WilayahModel::select(DB::raw("IFNULL(total_registrant,0) as total_registrant"),"map_code",$this->table.".id_wilayah","namakab")->leftjoin(DB::raw("(SELECT COUNT(*) as total_registrant, id_wilayah FROM tb_registran GROUP BY id_wilayah)reg"),"reg.id_wilayah","=",$this->table.".id_wilayah")->where("status","active")->orderBy("total_registrant","DESC")->get();
		return $data;
	}

  public function getListProvinsi(){
    $data = DB::select(DB::raw("SELECT kodeprov, namaprov FROM tb_wilayah GROUP BY kodeprov, namaprov ORDER BY namaprov ASC"));
    if($data){
      $prov[0]  = "Pilih Provinsi";
      foreach ($data as $key => $value) {
        $prov[$value->kodeprov] = $value->namaprov;
      }
      return $prov;
    }else{
      return 0;
    }
  }

  public function getListKota(){
    return WilayahModel::orderBy("namakab","asc")->select(DB::raw("CONCAT(namakab,' (',IF(SUBSTRING(kodekab,4)<50,'KABUPATEN','KOTA'),')') as namakab"),'id_wilayah')->pluck("namakab","id_wilayah");
  }

  public function user(){
    return $this->hasOne(UserModel::class,"id_user");
  }

	public function getNamaProv($kodeprov){
		$data = WilayahModel::where("kodeprov",$kodeprov)->first();
		return $data->namaprov;
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
		$data = WilayahModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("kodekab","like","%".$keyword."%");
								$query->orwhere("namakab","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive"))
						->select($this->table.".*",DB::raw("IFNULL(total_registrant,0) as total_registrant"))
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_registrant, id_wilayah FROM tb_registran GROUP BY id_wilayah)reg"),"reg.id_wilayah","=",$this->table.".id_wilayah")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = WilayahModel::where(function($query) use ($keyword){
              if($keyword!=""){
								$query->where("kodekab","like","%".$keyword."%");
								$query->orwhere("namakab","like","%".$keyword."%");
              }
						})
						->wherein($this->table.".status",array("active","inactive"))
						->count();
		return $data;
	}

	public function getDetail($id) {
		$data = WilayahModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = WilayahModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = WilayahModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return WilayahModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		$result = WilayahModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

	public function getAuth($data){
		return WilayahModel::where("username",$data['username'])->where("password",$data['password'])->where("status","active")->first();
	}

	public function activateAccount($code, $data){
		return WilayahModel::where("activation_code",$code)->update($data);
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

      $checkavailability = WilayahModel::where("affiliate_code",$code)->count();
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
