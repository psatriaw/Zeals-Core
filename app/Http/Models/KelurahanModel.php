<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class KelurahanModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'dukcapil_kelurahan';
	protected $primaryKey   = 'id_kelurahan';
	protected $fillable     = ['id_kelurahan', 'nama_kelurahan','id_kecamatan','time_created','status','author','last_update','id_reff'];

	public function getData($id_kecamatan){
		$data = KelurahanModel::where("id_kecamatan",$id_kecamatan)
						->wherein($this->table.".status",array("active","inactive","available","unavailable"))
						->get();
		return $data;
	}

	public function getDataKelurahan($str, $limit, $keyword, $short, $shortmode){

		$data = KelurahanModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("nama_kecamatan","like","%".$keyword."%");
								$query->orwhere("nama_kelurahan","like","%".$keyword."%");
								$query->orwhere("id_reff","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive","available","unavailable"))
						->leftjoin(DB::raw("(SELECT nama_kecamatan, id_kecamatan as idk FROM tb_dukcapil_kecamatan )tb_kec"),"kec.idk","=",$this->table.".id_kecamatan")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countDataKelurahan($keyword){
		$data = KelurahanModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("nama_kecamatan","like","%".$keyword."%");
								$query->orwhere("nama_kelurahan","like","%".$keyword."%");
								$query->orwhere("id_reff","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive","available","unavailable"))
						->leftjoin(DB::raw("(SELECT nama_kecamatan, id_kecamatan as idk FROM tb_dukcapil_kecamatan )tb_kec"),"kec.idk","=",$this->table.".id_kecamatan")
						->count();
		return $data;
	}

	public function checkData($keyword,$id_kecamatan,$create){
		print "ditanya kelurahan $keyword ";
		$data = KelurahanModel::where("nama_kelurahan",$keyword);
		if($data->count()){
			$data = $data->first();
			return $data->id_kelurahan;
		}else{
			$ret = DB::table($this->table)->insertGetId($create);
		//	$query = DB::raw("INSERT INTO tb_perkim_kelurahan (`nama_kelurahan`,`id_kecamatan`,`status`,`time_created`,`last_update`) VALUES ('$keyword','$id_kecamatan','active','".time()."','".time()."');");
		//	print $query;
		//	$ret = DB::select($query);
			print_r(DB::getQueryLog());
			return $ret;
		}
	}

	public function getlistkelurahanbykec($id_kecamatan){
		$data = KelurahanModel::where("id_kecamatan",$id_kecamatan)->orderBy("nama_kelurahan","asc")->get();
		return $data;
	}

	public function getlist(){
		return KelurahanModel::orderBy("nama_kelurahan","asc")->pluck("nama_kelurahan","id_kelurahan");
	}

	public function getDetail($id) {
		$data = KelurahanModel::where("id_kelurahan",$id)->join(DB::raw("(SELECT nama_kecamatan,id_kecamatan FROM tb_dukcapil_kecamatan)tb_kecamatan"),"kecamatan.id_kecamatan","=",$this->table.".id_kecamatan");
		return $data->first();
	}

	public function insertData($data){
		$result = KelurahanModel::create($data)->id_kelurahan;
		return $result;
  }

	public function updateData($data){
		$result = KelurahanModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return KelurahanModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		$result = KelurahanModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
}
