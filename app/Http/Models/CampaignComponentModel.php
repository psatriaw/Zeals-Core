<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CampaignComponentModel extends Model
{

  public $timestamps      = false;
  // const CREATED_AT        = 'created_at';
  // const UPDATED_AT        = 'update_at';

  protected $table        = 'tb_campaign_component';
  protected $primaryKey   = 'id_component';
  protected $fillable     = ['id_component','id_campaign','input_type','input_source','field_name','rules','status','is_deleted'];

  
  public function insertData($data){
      $result = CampaignComponentModel::create($data)->id_component;
      return $result;
  }
  public function getDataAll(){
    return CampaignComponentModel::get();
  }
  public function getListComponent($id_campaign){
    return CampaignComponentModel::select('id_component','input_type','input_source','field_name','rules','status')
    ->where('is_deleted','=',0)
    ->where('id_campaign','=',$id_campaign)
    ->get();
  }
  public function findById($id){
      $result = CampaignComponentModel::where($this->primaryKey, $id)->first();
      return $result;
  }
  public function updateData($data){
      $result = CampaignComponentModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
      return $result;
  }
  public function removeData($id){
      $result = CampaignComponentModel::where($this->primaryKey, '=', $id)->delete();
      return $result;
  }
  // public function getTotalStatus($status){
  //   return CampaignComponentModel::
  //   ->where('is_deleted','=',0)
  //   ->where('status','=',$status)
  //   ->get()->count();
  // }
  public function getResume($keyword,$id_penerbit=0){

		$data = CampaignComponentModel::where("is_deleted","0");

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
