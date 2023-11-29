<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class OutletModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	    = 'tb_outlet';
	protected $primaryKey   = 'id_outlet';
	protected $fillable     = ['id_outlet', 'id_campaign','outlet_name','outlet_address','status','outlet_phone','time_created','last_update','max_redemption','max_redemption_per_day','outlet_code','longitude','latitude'];

  public function getList(){
    return OutletModel::orderBy('category_name')->pluck('category_name', 'id_product_category');
  }

	public function getDetailByCode($code){
		$result = OutletModel::where("outlet_code",$code)->first();
		return $result;
	}

  public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = OutletModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("packing_code","like","%".$keyword."%");
                $query->orwhere("time_created","like","%".$keyword."%");
                $query->orwhere("last_update","like","%".$keyword."%");
							}
						})
            ->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = OutletModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("packing_code","like","%".$keyword."%");
                $query->orwhere("time_created","like","%".$keyword."%");
                $query->orwhere("last_update","like","%".$keyword."%");
							}
						})
						->count();
		return $data;
	}

  public function getDetail($id) {
		$data = OutletModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = OutletModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = OutletModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getListOutletByDistance($id_campaign, $longitude, $latitude){
		$distance 	= 15;
		$raw 		= DB::raw("SELECT *, (6371 * acos( cos( radians($latitude) ) * cos( radians( `latitude` ) ) * cos( radians(`longitude` ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians(`latitude`) ) ) ) as distance FROM ".$this->table." WHERE id_campaign = $id_campaign HAVING distance < $distance");
		$data 		= DB::select($raw);
		return 		$data;
	}
	
	public function getListOutlet($id_campaign, $longitude=0, $latitude=0){
		if($longitude!=0 && $latitude!=0){
			$raw 		= DB::raw("SELECT *, (6371 * acos( cos( radians($latitude) ) * cos( radians( `latitude` ) ) * cos( radians(`longitude` ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians(`latitude`) ) ) ) as distance FROM ".$this->table." WHERE id_campaign = $id_campaign");
			$data 		= DB::select($raw);
		}else{
			$data 		= OutletModel::where("id_campaign",$id_campaign)->get();
		}
		
		return 		$data;
	}

	public function createCode(){
		$text 	= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$length = strlen($text);
		$code 	= "";
		$ketemu = true;

		do{
			$code = "";

			for ($i=0; $i < 8; $i++) {
				$code = $code.substr($text,rand(0,$length-1),1);
			}

			$ketemu = OutletModel::where("outlet_code",$code)->first();
		}while($ketemu);

		return $code;
	}
}
