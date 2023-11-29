<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetailModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_purchase_detail';
	protected $primaryKey   = 'id_purchase_detail';
	protected $fillable     = ['id_purchase_detail', 'id_purchase','added_by','id_item','item_price','item_quantity','item_price_custom','discount','time_created','status','last_update','item_type'];

	public function getItemPurchase($id_material){
		$data 	= PurchaseDetailModel::select("item_price as purchase_price","purchase_code","purchase_title","purchase_time","item_quantity",$this->table.".id_purchase_detail","item_used")
							->where("id_item",$id_material)
							->where("tb_purchase_detail.status","ok")
							->join(DB::raw("(SELECT purchase_code, purchase_title, time_receive AS purchase_time, id_purchase as idp FROM tb_purchase WHERE status ='received')pp"),"pp.idp","=",$this->table.".id_purchase")
							->leftjoin(DB::raw("(SELECT IFNULL(SUM(movement_qty),0) as item_used, id_purchase_detail FROM tb_brownies_material_movement WHERE status IN ('active','moved') GROUP BY id_purchase_detail)mv"),"mv.id_purchase_detail","=","tb_purchase_detail.id_purchase_detail")
							->having("item_quantity",">","item_used")
							->get();
		return $data;
	}

	public function getDataPurchase($id_material){
		$data = PurchaseDetailModel::select($this->table.".*","purchase_code","item_quantity","item_price","purchase_date")
						->leftjoin("tb_purchase","tb_purchase.id_purchase","=",$this->table.".id_purchase")
						->whereNotIn($this->table.".status",["deleted"])
						->where("id_item",$id_material)
						->get();
		return $data;
	}

	public function getData($str, $limit, $keyword, $short, $shortmode,$status="", $id=""){
		$data = PurchaseDetailModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("purchase_code","like","%".$keyword."%");
							}
						})
						->select($this->table.".*","purchase_code","item_quantity","item_price","purchase_date",DB::raw("(IFNULL(distribusi,0) + IFNULL(mvd,0)) as distibuted"),"mvd")
						->leftjoin(DB::raw("(SELECT SUM(movement_qty) as mvd, id_purchase_detail FROM tb_brownies_material_movement WHERE status IN ('active','moved') GROUP BY id_purchase_detail)movemt"),"movemt.id_purchase_detail","=",$this->table.".id_purchase_detail")
						->leftjoin(DB::raw("(SELECT SUM(quantity) as distribusi, id_purchase_detail FROM tb_brownies_material_distribution WHERE status = 'active' GROUP BY id_purchase_detail)dist"),"dist.id_purchase_detail","=",$this->table.".id_purchase_detail")
						->leftjoin("tb_purchase","tb_purchase.id_purchase","=",$this->table.".id_purchase")
						->whereNotIn($this->table.".status",["deleted"])
						->where("id_item",$id)
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword,$id=""){
		$data = PurchaseDetailModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("purchase_code","like","%".$keyword."%");
							}
						})
						->select($this->table.".*","purchase_code","item_quantity","item_price")
						->whereNotIn($this->table.".status",["deleted"])
						->where("id_item",$id)
						->count();
		return $data;
	}

	public function getListLokasiSekitar($latitude, $longitude, $id, $distance){
		//$additionalquery = "AND ";
		$raw 			= DB::raw("SELECT *, (6371 * acos( cos( radians(" . $latitude . ") ) * cos( radians( `latitude` ) ) * cos( radians(`longitude` ) - radians(" . $longitude . ") ) + sin( radians(" .$latitude. ") ) * sin( radians(`latitude`) ) ) ) as distance FROM tb_fremilt_mitra WHERE status = 'diterima' AND id_mitra NOT IN ($id) AND (6371 * acos( cos( radians(" . $latitude . ") ) * cos( radians( `latitude` ) ) * cos( radians(`longitude` ) - radians(" . $longitude . ") ) + sin( radians(" .$latitude. ") ) * sin( radians(`latitude`) ) ) ) < $distance");
		$data 		= DB::select($raw);
		return $data;
	}

	public function getTotalPurchase($id_purchase){
		$data = PurchaseDetailModel::where("id_purchase",$id_purchase)->where("status","ok")->get();
		if($data->count()){
			$subtotal = 0;
			foreach ($data as $key => $value) {
				$subtotal = $subtotal + ($value->item_quantity * $value->item_price);
			}
			return $subtotal;
		}else{
			return 0;
		}
	}

	public function getAllData(){
		return PurchaseDetailModel::where("status","diterima")->get();
	}

	public function getDetail($id) {
		$data = PurchaseDetailModel::where($this->primaryKey,$id)
						->first();
		return $data;
	}

	public function insertData($data){
		$result = PurchaseDetailModel::create($data)->id_purchase_detail;
		return $result;
  }

	public function updateData($data){
		$result = PurchaseDetailModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id){
		$result = PurchaseDetailModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
}
