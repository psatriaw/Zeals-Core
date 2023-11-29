<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UserFitMeModel extends Model {
	public $timestamps      = false;
  	const CREATED_AT      = 'time_created';
  	const UPDATED_AT      = 'last_update';

	protected $table 	       = 'user_fit_me';
	protected $primaryKey    = 'id_fit_me';
	protected $fillable      = ['id_fit_me', 'id_user','id_product_category','id_brand','size','time_created','last_update'];

	public function getCategoriesFitMe($publicpath, $id_user){
		$data = UserFitMeModel::where("status","active")->orderBy("category_name","ASC");
		if($data->count()){
			$data = $data->get();
			foreach ($data as $key => $value) {
				$rets = $value;
				$rets->banner = $publicpath."/".$value->banner;
				$retdata[] = $rets;
			}

			return $retdata;
		}else{
			return 0;
		}
	}

	public function removeall($id_user){
		return UserFitMeModel::where("id_user",$id_user)->delete();
	}

  public function getDetail($id) {
		$data = UserFitMeModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = UserFitMeModel::create($data);
		return $result;
  	}

	public function updateData($data){
		$result = UserFitMeModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}
}
