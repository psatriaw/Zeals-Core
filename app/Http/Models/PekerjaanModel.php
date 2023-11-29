<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PekerjaanModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'dukcapil_pekerjaan';
	protected $primaryKey   = 'id_pekerjaan';
	protected $fillable     = ['id_pekerjaan', 'pekerjaan','id_reff','status','time_created','last_update'];

	public function getData($str, $limit, $keyword, $short, $shortmode,$status){
		$data = PekerjaanModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("pekerjaan","like","%".$keyword."%");
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
		$data = PekerjaanModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("pekerjaan","like","%".$keyword."%");
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
		return PekerjaanModel::where("status","diterima")->get();
	}

	public function getDetail($id) {
		$data = PekerjaanModel::where($this->primaryKey,$id)
						->first();
		return $data;
	}

	public function insertData($data){
		$result = PekerjaanModel::create($data)->id_pekerjaan;
		return $result;
  }

	public function updateData($data){
		$result = PekerjaanModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id){
		$result = PekerjaanModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

	public function createMitraCode(){
    $code = "";
    $length = 8;
    $status = true;
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    do{
      $code = "";
      for($i=0; $i < $length; $i++){
        $code = $code.substr($alphabet,rand(0,strlen($alphabet)-1),1);
      }

      $checkavailability = PekerjaanModel::where("mitra_code",$code)->count();
      if($checkavailability){
        $status = true;
      }else{
        $status = false;
      }
    }while($status);

    return $code;
  }
}
