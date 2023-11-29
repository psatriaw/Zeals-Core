<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MaterialDistributionModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_brownies_material_distribution';
	protected $primaryKey   = 'id_material_distribution';
	protected $fillable     = ['id_material_distribution', 'id_purchase_detail','quantity','id_mitra','status','time_created','last_update','author','distribution_date'];

	public function MaterialDistributionModel($id_material){
		$querypurchase = "SELECT SUM(item_quantity) as total_purchase
											FROM tb_purchase_detail
											INNER JOIN (SELECT id_purchase AS idp FROM tb_purchase WHERE status = 'received')pc ON pc.idp = tb_purchase_detail.id_purchase
											WHERE status = 'ok' AND id_item = $id_material
											";

		$purchase 		 = DB::select(DB::raw($querypurchase));
		$total_purchase = $purchase[0]->total_purchase;
		//print_r($purchase);

		$usage 			   = "SELECT SUM(movement_qty) as total_usage
											FROM tb_brownies_material_movement,tb_purchase_detail
											WHERE tb_brownies_material_movement.id_purchase_detail = tb_purchase_detail.id_purchase_detail
											AND tb_brownies_material_movement.status IN ('moved','active')
											AND tb_purchase_detail.id_item = '$id_material'
											";
		$usage 				 = DB::select(DB::raw($usage));
		$total_usage 	 = $usage[0]->total_usage;
		//print_r($usage);
		$stock = $total_purchase - $total_usage;
		return @$stock;
	}

	public function getData($str, $limit, $keyword, $short, $shortmode, $login="", $id="", $allowall){
		$data = MaterialDistributionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("purchase_code","like","%".$keyword."%");
								$query->orwhere("material_name","like","%".$keyword."%");
							}
						});


						if(!$allowall){
							$id_user = $login->id_user;
							$data = $data->select($this->table.".*","purchase_code","item_price","penggunaan")
							->whereNotIn($this->table.".status",["deleted"])
							->leftjoin(DB::raw("(SELECT SUM(quantity) as penggunaan, id_purchase_detail FROM tb_brownies_bahan_penyerta_produksi WHERE status = 'active' GROUP BY id_purchase_detail)guna"),"guna.id_purchase_detail","=",$this->table.".id_purchase_detail")
              ->join(DB::raw("(SELECT purchase_code, id_purchase_detail, item_price FROM tb_purchase_detail, tb_purchase WHERE tb_purchase.id_purchase = tb_purchase_detail.id_purchase AND id_item = '$id')tp"),"tp.id_purchase_detail","=",$this->table.".id_purchase_detail")
              ->join(DB::raw("(SELECT id_mitra FROM tb_fremilt_mitra WHERE id_user = $id_user)cabang"),"cabang.id_mitra","=",$this->table.".id_mitra")
							->orderBy($short, $shortmode)
							->skip($str)
	            ->limit($limit)
							->get();

						}else{
              $id_user = $login->id_user;
							$data = $data->select($this->table.".*","purchase_code","item_price",DB::raw("IFNULL(penggunaan,0) as penggunaan"))
							->whereNotIn($this->table.".status",["deleted"])
							->leftjoin(DB::raw("(SELECT SUM(quantity) as penggunaan,id_purchase_detail FROM tb_brownies_bahan_penyerta_produksi WHERE status = 'active' GROUP BY id_purchase_detail)guna"),"guna.id_purchase_detail","=",$this->table.".id_purchase_detail")
              ->join(DB::raw("(SELECT purchase_code, id_purchase_detail, item_price FROM tb_purchase_detail, tb_purchase WHERE tb_purchase.id_purchase = tb_purchase_detail.id_purchase AND id_item = '$id')tp"),"tp.id_purchase_detail","=",$this->table.".id_purchase_detail")
							->orderBy($short, $shortmode)
							->skip($str)
	            ->limit($limit)
							->get();
						}
					  //print $data;
						//exit();
		return $data;
	}

	public function getDataAll($str, $limit, $keyword, $short, $shortmode){
		$data = MaterialDistributionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("purchase_code","like","%".$keyword."%");
								$query->orwhere("material_name","like","%".$keyword."%");
								$query->orwhere("distribution_date","like","%".$keyword."%");
							}
						});




						$data = $data->select($this->table.".*","purchase_code","item_price","material_name","material_code","mitra_name")
						->whereNotIn($this->table.".status",["removed"])
						->leftjoin(DB::raw("(SELECT material_name, material_code, id_purchase_detail FROM tb_fremilt_material,tb_purchase_detail WHERE tb_fremilt_material.id_material = tb_purchase_detail.id_item AND tb_purchase_detail.status = 'ok' AND tb_fremilt_material.status = 'active')mtd"),"mtd.id_purchase_detail","=",$this->table.".id_purchase_detail")
            ->join(DB::raw("(SELECT purchase_code, id_purchase_detail, item_price FROM tb_purchase_detail, tb_purchase WHERE tb_purchase.id_purchase = tb_purchase_detail.id_purchase)tp"),"tp.id_purchase_detail","=",$this->table.".id_purchase_detail")
            ->join(DB::raw("(SELECT id_mitra, mitra_name FROM tb_fremilt_mitra)cabang"),"cabang.id_mitra","=",$this->table.".id_mitra")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();


					  //print $data;
						//exit();
		return $data;
	}

	public function updateStock($total, $id_material){
		$query = "UPDATE tb_fremilt_material SET quantity = quantity + $total WHERE id_material = '$id_material'";
		return $this->executeQuery($query);
	}

	public function countData($keyword){
		$data = MaterialDistributionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("purchase_code","like","%".$keyword."%");
								$query->orwhere("material_name","like","%".$keyword."%");
								$query->orwhere("distribution_date","like","%".$keyword."%");
							}
						})
						->leftjoin(DB::raw("(SELECT material_name, material_code, id_purchase_detail FROM tb_fremilt_material,tb_purchase_detail WHERE tb_fremilt_material.id_material = tb_purchase_detail.id_item AND tb_purchase_detail.status = 'ok' AND tb_fremilt_material.status = 'active')mtd"),"mtd.id_purchase_detail","=",$this->table.".id_purchase_detail")
            ->join(DB::raw("(SELECT purchase_code, id_purchase_detail, item_price FROM tb_purchase_detail, tb_purchase WHERE tb_purchase.id_purchase = tb_purchase_detail.id_purchase)tp"),"tp.id_purchase_detail","=",$this->table.".id_purchase_detail")
            ->join(DB::raw("(SELECT id_mitra, mitra_name FROM tb_fremilt_mitra)cabang"),"cabang.id_mitra","=",$this->table.".id_mitra")
						->whereNotIn($this->table.".status",["removed"])
						->count();
		return $data;
	}

  public function getlistFull($id_user){
		//return MaterialModel::where("status","active")->orderBy("material_name","asc")->pluck("material_name","id_material");
		//$data = MaterialModel::where("status","active")->orderBy("material_name","asc")->get();
		$query = "SELECT material_name, material_code, material_unit, purchase_code, (IFNULL(distributed_quantity,0) - IFNULL(total_usage,0)) as stock, tb_purchase_detail.id_purchase_detail,id_material
							FROM tb_purchase_detail
							JOIN tb_purchase on tb_purchase.id_purchase = tb_purchase_detail.id_purchase
              JOIN (SELECT id_purchase_detail,id_mitra,IFNULL(SUM(quantity),0) as distributed_quantity FROM tb_brownies_material_distribution GROUP BY id_purchase_detail)scabang ON scabang.id_purchase_detail = tb_purchase_detail.id_purchase_detail
              JOIN (SELECT id_mitra FROM tb_fremilt_mitra WHERE id_user = $id_user)cabang ON cabang.id_mitra = scabang.id_mitra
							JOIN (SELECT id_material, material_name, material_code, material_unit FROM tb_fremilt_material WHERE tb_fremilt_material.status = 'active' AND type_material = 'nonmrp')mm on mm.id_material = tb_purchase_detail.id_item
							LEFT JOIN (SELECT SUM(quantity) as total_usage, id_purchase_detail as id_pd FROM tb_brownies_bahan_penyerta_produksi WHERE status IN ('active') GROUP BY id_purchase_detail) uss ON uss.id_pd = tb_purchase_detail.id_purchase_detail
							WHERE tb_purchase_detail.status = 'ok'
							HAVING stock > 0
							ORDER BY material_name, purchase_code ASC
							";
		$data = DB::select(DB::raw($query));
		return $data;
	}

	public function getListLokasiSekitar($latitude, $longitude, $id, $distance){
		//$additionalquery = "AND ";
		$raw 			= DB::raw("SELECT *, (6371 * acos( cos( radians(" . $latitude . ") ) * cos( radians( `latitude` ) ) * cos( radians(`longitude` ) - radians(" . $longitude . ") ) + sin( radians(" .$latitude. ") ) * sin( radians(`latitude`) ) ) ) as distance FROM tb_fremilt_mitra WHERE status = 'diterima' AND id_mitra NOT IN ($id) AND (6371 * acos( cos( radians(" . $latitude . ") ) * cos( radians( `latitude` ) ) * cos( radians(`longitude` ) - radians(" . $longitude . ") ) + sin( radians(" .$latitude. ") ) * sin( radians(`latitude`) ) ) ) < $distance");
		$data 		= DB::select($raw);
		return $data;
	}

	public function getlist(){
		//return MaterialModel::where("status","active")->orderBy("material_name","asc")->pluck("material_name","id_material");
		$data = MaterialDistributionModel::where("status","active")->orderBy("material_name","asc")->get();
		if($data->count()>0){
			foreach ($data as $key => $value) {
				$ret[$value->id_material] = $value->material_name." ( satuan: ".$value->material_unit.")";
			}
			return $ret;
		}else{
			return 0;
		}
	}

	public function executeQuery($query){
		return DB::select(DB::raw($query));
	}

	public function getAllData(){
		return MaterialDistributionModel::where("status","diterima")->get();
	}

	public function getDetail($id) {
		$data = MaterialDistributionModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = MaterialDistributionModel::create($data)->id_material_distribution;
		return $result;
  }

	public function updateData($data){
		$result = MaterialDistributionModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id){
		$result = MaterialModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

	public function checkMinimumQuantity($id_material){
		$data = $this->executeQuery(DB::raw("SELECT * FROM ".$this->table." WHERE id_material = $id_material AND quantity <= minimum_stock"));
		if(sizeof($data)){
			return $data[0];
		}else{
			return 0;
		}
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

      $checkavailability = MaterialModel::where("mitra_code",$code)->count();
      if($checkavailability){
        $status = true;
      }else{
        $status = false;
      }
    }while($status);

    return $code;
  }
}
