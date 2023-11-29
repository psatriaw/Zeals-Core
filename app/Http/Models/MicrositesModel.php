<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MicrositesModel extends Model
{

  public $timestamps      = false;
  const CREATED_AT        = 'created_at';
  const UPDATED_AT        = 'update_at';

  protected $table        = 'tb_microsite';
  protected $primaryKey   = 'id_microsite';
  protected $fillable     = ['id_microsite','nama_microsite','id_penerbit','start_date','end_date','status','notes','banner','keyword','css_source','is_deleted','created_at','updated_at'];

  
  public function insertData($data){
      $result = MicrositesModel::create($data)->id_microsite;
      return $result;
  }
  public function getDataAll(){
    return MicrositesModel::get();
  }
  public function getListSite(){
    return MicrositesModel::select('id_microsite','nama_microsite','nama_penerbit','start_date','end_date',$this->table.'.status')
    ->join('tb_penerbit','tb_penerbit.id_penerbit','=',$this->table . '.id_penerbit')
    ->where('is_deleted','=',0)
    ->get();
  }
  public function findById($id){
      $result = MicrositesModel::where($this->primaryKey, $id)->first();
      return $result;
  }
  public function updateData($data){
      $result = MicrositesModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
      return $result;
  }
  public function removeData($id){
      $result = MicrositesModel::where($this->primaryKey, '=', $id)->delete();
      return $result;
  }
  // public function getTotalStatus($status){
  //   return MicrositesModel::select('count(1) as total')
  //   ->where('is_deleted','=',0)
  //   ->where('status','=',$status)
  //   ->get();
  // }
  public function getResume($keyword,$id_penerbit=0){

		$data = MicrositesModel::where("is_deleted","0");

		switch($keyword){
			case "sum-drafted":
				$data = $data->where("status","drafted");
			break;

			case "sum-review":
				$data = $data->where("status","review");
			break;

			case "sum-pending":
				$today = date("y-m-d");
				$data = $data->where("status","running")
              ->where(function ($query) use ($today) {
                  $query->where("start_date",">",$today);
              });
			break;

			case "sum-running":
				$today = date("y-m-d");
				$data = $data->where("status","running")
							->where(function ($query) use ($today) {
									$query->where("start_date","<=",$today);
									$query->where("end_date",">=",$today);
							});
			break;

			case "sum-stopped":
				$data = $data->where("status","stopped");
			break;

			case "sum-expired":
				$today = date("y-m-d");
				$data = $data->where("status","running")
							->where(function ($query) use ($today) {
									$query->where("end_date","<",$today);
							});
			break;
		}

		if($id_penerbit!=0){
			$data = $data->where("id_penerbit",$id_penerbit);
		}

		$data 	= $data->get()->count();

		return $data;

	}
}
