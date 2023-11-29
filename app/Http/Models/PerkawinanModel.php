<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PerkawinanModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'dukcapil_status_pernikahan';
	protected $primaryKey   = 'id_status_pernikahan';
	protected $fillable     = ['id_status_pernikahan', 'status_pernikahan','id_reff','status','time_created','last_update'];

	public function getData($str, $limit, $keyword, $short, $shortmode,$status){
		$data = PerkawinanModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("status_pernikahan","like","%".$keyword."%");
							}
						})
						->whereNotIn($this->table.".status",["deleted"])
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword,$status){
		$data = PerkawinanModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("status_pernikahan","like","%".$keyword."%");
							}
						})
						->whereNotIn($this->table.".status",["deleted"])
						->count();
		return $data;
	}

	public function getListLokasiSekitar($latitude, $longitude, $id, $distance){
		//$additionalquery = "AND ";
		$raw 			= DB::raw("SELECT *, (6371 * acos( cos( radians(" . $latitude . ") ) * cos( radians( `latitude` ) ) * cos( radians(`longitude` ) - radians(" . $longitude . ") ) + sin( radians(" .$latitude. ") ) * sin( radians(`latitude`) ) ) ) as distance FROM ".DB::getTablePrefix()."fremilt_mitra WHERE status = 'diterima' AND id_mitra NOT IN ($id) AND (6371 * acos( cos( radians(" . $latitude . ") ) * cos( radians( `latitude` ) ) * cos( radians(`longitude` ) - radians(" . $longitude . ") ) + sin( radians(" .$latitude. ") ) * sin( radians(`latitude`) ) ) ) < $distance");
		$data 		= DB::select($raw);
		return $data;
	}

	public function getAllData(){
		return PerkawinanModel::where("status","diterima")->get();
	}

	public function getDetail($id) {
		$data = PerkawinanModel::where($this->primaryKey,$id)
						->first();
		return $data;
	}

	public function insertData($data){
		$result = PerkawinanModel::create($data)->id_pekerjaan;
		return $result;
  }

	public function updateData($data){
		$result = PerkawinanModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id){
		$result = PerkawinanModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
}
