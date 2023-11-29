<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_service';
	protected $primaryKey   = 'id_service';
	protected $fillable     = ['id_service', 'service_name','description','status','time_created','expiration','price','code','type','last_update'];

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = ServiceModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("service_name","like","%".$keyword."%");
								$query->orwhere("description","like","%".$keyword."%");
                $query->orwhere("price","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive"))
						//->select($this->table.".*","tb_department.name")
						//->join("tb_department","tb_department.id_department","=",$this->table.".id_department")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = ServiceModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("service_name","like","%".$keyword."%");
								$query->orwhere("description","like","%".$keyword."%");
                $query->orwhere("price","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive"))
						->count();
		return $data;
	}

	public function getDetail($id) {
		$data = ServiceModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = ServiceModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = ServiceModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return ServiceModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		$result = ServiceModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
}
