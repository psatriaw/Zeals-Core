<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductionModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_production';
	protected $primaryKey   = 'id_production';
	protected $fillable     = ['id_production', 'production_code','time_created','author','status','production_date','last_update'];
	protected $prestring 				= "PRO";
	protected $digittransaction = 8;
	
	public function getHPPProductionByDate($date1, $date2){
	    $data = "SELECT IFNULL(SUM(hpp),0) as total FROM tb_brownies_stock_unit  WHERE id_production IN (SELECT id_production FROM tb_production WHERE production_date BETWEEN '$date1' AND '$date2') AND status = 'active'";
		//print $data;
		$data = DB::select(DB::raw($data));
		if($data){
			return $data[0]->total;
		}else{
			return 0;
		}
	}	
	
	
	public function getTotalProductionByDate($date1, $date2){
	    $data = "SELECT COUNT(*) as total FROM tb_brownies_stock_unit  WHERE id_production IN (SELECT id_production FROM tb_production WHERE production_date BETWEEN '$date1' AND '$date2') AND status = 'active'";
		//print $data;
		$data = DB::select(DB::raw($data));
		if($data){
			return $data[0]->total;
		}else{
			return 0;
		}
	}

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = ProductionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("production_code","like","%".$keyword."%");
								$query->orwhere("admin_name","like","%".$keyword."%");
							}
						})
            ->wherein($this->table.".status",array("active","cancelled","done"))
						->leftjoin(DB::raw("(SELECT first_name as admin_name,id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
						->leftjoin(DB::raw("(SELECT SUM(total_implementasi) as total_item, id_production as idp FROM tb_brownies_naikan_implementasi WHERE status ='active' GROUP BY id_production)bni"),"bni.idp","=",$this->table.".id_production")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = ProductionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("production_code","like","%".$keyword."%");
								$query->orwhere("admin_name","like","%".$keyword."%");
							}
						})
            ->wherein($this->table.".status",array("active","cancelled","done"))
						->count();
		return $data;
	}

	public function createCode(){
		$data  = ProductionModel::where("production_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

		if($data->count()>0){
			$data 			= $data->toArray()[0];
			$transnumb  = explode($this->prestring,$data['production_code']);
			$number     = intval($transnumb[1]);
			$nextnumber = $number+1;
			return $this->formattransaction($this->prestring,$nextnumber);
		}else{
			return $this->formattransaction($this->prestring,1);
		}
	}

	public function formattransaction($prestring, $number){
		$lengthofnumber = strlen(strval($number));
		$lengthrest     = $this->digittransaction - $lengthofnumber;
		if($lengthrest>0){
			$transnumb = strval($number);
			for($i=0;$i<$lengthrest;$i++){
				$transnumb = "0".$transnumb;
			}
			$transnumb = $prestring.$transnumb;
			return $transnumb;
		}else{
			return $prestring.$number;
		}
	}

	public function getDataProduction($id_product, $str, $limit){
		$data = ProductionModel::where($this->table.".id_product",$id_product)
						->leftjoin(DB::raw("(SELECT first_name as admin_name,id_user FROM tb_user)user"),"user.id_user","=",$this->table.".admin")
						->leftjoin(DB::raw("(SELECT id_product, product_name, product_code FROM tb_product)tp"),"tp.id_product","=",$this->table.".id_product")
						->leftjoin(DB::raw("(SELECT SUM(quantity) as penggunaan, id_production as idp FROM tb_packing_detail GROUP BY id_production)tfp"),"tfp.idp","=",$this->table.".id_production")
						->orderBy("id_production","DESC")
						->limit($limit)
						->skip($str)
						->get();
		return $data;
	}

	public function getDetail($id) {
		$data = ProductionModel::where($this->primaryKey,$id)
						->leftjoin(DB::raw("(SELECT first_name as admin_name,id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
						->leftjoin(DB::raw("(SELECT IFNULL(SUM(total_implementasi),0) as total_item, id_production as idp FROM tb_brownies_naikan_implementasi GROUP BY id_production)tfp"),"tfp.idp","=",$this->table.".id_production")
						->leftjoin(DB::raw("(SELECT id_cart as id_crt, cart_code FROM tb_cart)tbc"),"tbc.id_crt","=",$this->table.".id_source_bahan")
						->first();
		return $data;
	}

	public function itemusage(){
		return $this->hasMany(ProductionUsageModel::class,"id_production")
						->join(DB::raw("(SELECT cart_code,id_cart_detail FROM tb_cart_detail,tb_cart WHERE tb_cart.id_cart = tb_cart_detail.id_cart)pr"),"pr.id_cart_detail","=","tb_packing_detail.id_cart_detail")
						->join(DB::raw("(SELECT first_name as admin_name,id_user FROM tb_user)tb_user"),"tb_user.id_user","=","tb_packing_detail.admin_packing")
						->join(DB::raw("(SELECT status as packing_status, id_packing,delivery_courier,packing_code,last_update as packing_time FROM tb_packing)tb_packing"),"tb_packing.id_packing","=","tb_packing_detail.id_packing")
						->leftjoin(DB::raw("(SELECT first_name as courier_name,id_user FROM tb_user)courier"),"courier.id_user","=","tb_packing.delivery_courier");
	}

	public function getItemUsageOfProduct($id_product){
		$data = ProductionModel::where("id_product",$id_product)
						->join(DB::raw("(SELECT * FROM tb_packing_detail)tb_packing_detail"),"tb_packing_detail.id_production","=",$this->table.".".$this->primaryKey)
						->join(DB::raw("(SELECT cart_code,id_cart_detail FROM tb_cart_detail,tb_cart WHERE tb_cart.id_cart = tb_cart_detail.id_cart)pr"),"pr.id_cart_detail","=","tb_packing_detail.id_cart_detail")
						->join(DB::raw("(SELECT first_name as admin_name,id_user FROM tb_user)tb_user"),"tb_user.id_user","=","tb_packing_detail.admin_packing")
						->join(DB::raw("(SELECT status as packing_status, id_packing,delivery_courier,packing_code,last_update as packing_time FROM tb_packing)tb_packing"),"tb_packing.id_packing","=","tb_packing_detail.id_packing")
						->leftjoin(DB::raw("(SELECT first_name as courier_name,id_user FROM tb_user)courier"),"courier.id_user","=","tb_packing.delivery_courier")
						->get();
		return $data;
	}

	public function materialusage(){
		return $this->hasMany(MaterialUsageModel::class,"id_production")->join(DB::raw("(SELECT material_name, material_code, material_price, id_material, material_unit FROM tb_fremilt_material WHERE status = 'active')tfm"),"tfm.id_material","=","tb_fremilt_material_usage.id_material");
	}

	public function getProductUsage($id_production){
		return ProductionModel::find($id_production)->itemusage()->get();

	}

	public function getMaterials($id_production){
		return ProductionModel::find($id_production)->materialusage()->get();
	}

	public function insertData($data){
		$result = ProductionModel::create($data)->id_production;
		return $result;
  }

	public function updateData($data){
		$result = ProductionModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}


}
