<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_product';
	protected $primaryKey   = 'id_product';
	protected $fillable     = ['id_product', 'product_name','time_created','status','description','price','discount','author','id_vendor','id_product_category','product_type','last_update','product_code','product_unit','price_gojek','price_grab','harga_outlet','type_product'];

	public function getlistrevenueOfTheDayByProduct($id_outlet,$date){
		$data = ProductModel::whereIn($this->table.".status",array("available",'active'))
													->select("product_name",DB::raw("IFNULL(total_item,0) as total_item"),DB::raw("IFNULL(grand_total,0) as grand_total"))
													->join(DB::raw("(SELECT id_product FROM tb_product_outlet WHERE id_outlet = '$id_outlet' AND status = 'active')tbpo"),"tbpo.id_product","=",$this->table.".id_product")
													->leftjoin(DB::raw("(SELECT SUM(quantity) as total_item, SUM(sell_price*quantity) as grand_total, id_product FROM tb_product_outlet_sell_item, tb_product_outlet_sell WHERE tb_product_outlet_sell_item.id_outlet_sell = tb_product_outlet_sell.id_outlet_sell AND tb_product_outlet_sell.id_outlet = '$id_outlet' AND tb_product_outlet_sell_item.status = 'active' AND sell_date = '$date' GROUP BY id_product)ts"),"ts.id_product","=",$this->table.".id_product")
													->orderBy("product_name","ASC")
													->get();
		return $data;
	}

	public function getPhotos($id_product){
		$data = ProductModel::find($id_product);
		return $data->photos;
	}

	public function mrps(){
		return $this->hasMany(MrpModel::class,"id_product")->where("status","active")->join(DB::raw("(SELECT material_name, material_code, material_price, id_material, material_unit FROM tb_fremilt_material WHERE status = 'active')tfm"),"tfm.id_material","=","tb_fremilt_mrp.id_material");
	}

	public function findProduct($keyword, $selected){
		$data = ProductModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("product_name","like","%".$keyword."%");
								$query->orwhere("product_code","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive","available","unavailable"));
						///->outerjoin(DB::raw("(SELECT id_not_allowed, id_purchased FROM tb_product_restriction WHERE id_purchased IN ($selected) )"),"tb_product_restriction.id_not_allowed","=",$this->table.".id_product")
						if($selected!=""){
							//$data		= $data->whereRaw("id_product NOT IN (SELECT id_not_allowed FROM tb_product_restriction WHERE id_purchased IN ($selected))");
							$data		= $data->where(function($query) use ($selected){
								if($selected!=""){
									//$query->whereRaw("id_product NOT IN (SELECT id_not_allowed FROM tb_product_restriction WHERE id_purchased IN ($selected) UNION SELECT id_purchased FROM tb_product_restriction WHERE id_not_allowed IN ($selected))");
									//$query->orwhereRaw("id_product NOT IN (SELECT id_purchased FROM tb_product_restriction WHERE id_not_allowed IN ($selected))");
									$query->orwhereRaw("id_product NOT IN ($selected)");
								}
							});
						}

						$data 	= $data->orderBy("product_name","ASC")->get();
		return $data;
	}

	public function getlistproductfull(){
		$data = ProductModel::orderBy("product_name","ASC")->wherein($this->table.".status",array("active","available"))->get();
		if($data->count()){
			return $data;
		}else{
			return 0;
		}
	}

	public function getlistproduct(){
		$data = ProductModel::orderBy("product_name","ASC")->wherein($this->table.".status",array("active","available"))->get();
		if($data->count()){
			foreach ($data as $key => $value) {
				$option[$value->id_product] = $value->product_name;
			}
		}else{
			$option = array();
		}
		return $option;
	}

	public function getlistmrpproduct($id_product){
		$data = ProductModel::find($id_product);
		return $data->mrps()->get();
	}

	public function photos(){
		return $this->hasMany(ProductDetailModel::class,"id_product")
							->where("tb_product_detail.status","active")
							->join("tb_photo","tb_product_detail.value","=","tb_photo.id_photo");
	}

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = ProductModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("product_name","like","%".$keyword."%");
								$query->orwhere("product_code","like","%".$keyword."%");
								$query->orwhere("price","like","%".$keyword."%");
								$query->orwhere("discount","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive","available","unavailable"))
						->select($this->table.".*","hpp","rules","para_loyang")
						->select($this->table.".*","tb_product_category.category_name","tb_product_category.code")
						->leftjoin("tb_product_category","tb_product_category.id_product_category","=",$this->table.".id_product_category")
						->leftjoin(DB::raw("(SELECT SUM(material_price*qty) as hpp, id_product FROM tb_fremilt_mrp, tb_fremilt_material WHERE tb_fremilt_mrp.id_material = tb_fremilt_material.id_material GROUP BY tb_fremilt_mrp.id_product)mrp"),"mrp.id_product","=",$this->table.".id_product")
						->leftjoin(DB::raw("(SELECT COUNT(id_product_restriction) as rules, id_purchased FROM tb_product_restriction GROUP BY id_purchased)rule"),"rule.id_purchased","=",$this->table.".id_product")
						->leftjoin(DB::raw("(SELECT id_product as id_prod, GROUP_CONCAT(CONCAT('<span class=\"label label-info\">',qty,'x',naikan_rumus_code,'</span>') SEPARATOR ' ') as para_loyang FROM tb_brownies_product_naikan, tb_brownies_naikan_rumus WHERE tb_brownies_naikan_rumus.id_naikan_rumus = tb_brownies_product_naikan.id_naikan_rumus AND tb_brownies_naikan_rumus.status = 'active' AND tb_brownies_product_naikan.status = 'active' GROUP BY id_product)loy"),"loy.id_prod","=","tb_product.id_product")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function getDatanStock($str, $limit, $keyword, $short, $shortmode){
		$data = ProductModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("product_name","like","%".$keyword."%");
								$query->orwhere("product_code","like","%".$keyword."%");
								$query->orwhere("price","like","%".$keyword."%");
								$query->orwhere("discount","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive","available","unavailable"))
						->select($this->table.".*","hpp","production_quantity","akhir_produksi","penggunaan")
						->leftjoin(DB::raw("(SELECT SUM(tb_packing_detail.quantity) as penggunaan, id_product FROM tb_packing_detail, tb_cart_detail WHERE tb_packing_detail.id_cart_detail = tb_cart_detail.id_cart_detail GROUP BY id_product)pckd"),"pckd.id_product","=",$this->table.".".$this->primaryKey)
						->leftjoin(DB::raw("(SELECT time_created as akhir_produksi, id_product as id_plp FROM tb_fremilt_production ORDER BY id_production LIMIT 0,1)lp"),"lp.id_plp","=",$this->table.".".$this->primaryKey)
						->leftjoin(DB::raw("(SELECT SUM(production_quantity) as production_quantity, id_product as idp FROM tb_fremilt_production GROUP BY idp)fmpro"),"fmpro.idp","=",$this->table.".".$this->primaryKey)
						->leftjoin(DB::raw("(SELECT SUM(material_price*qty) as hpp, id_product FROM tb_fremilt_mrp, tb_fremilt_material WHERE tb_fremilt_mrp.id_material = tb_fremilt_material.id_material GROUP BY tb_fremilt_mrp.id_product)mrp"),"mrp.id_product","=",$this->table.".id_product")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = ProductModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("product_name","like","%".$keyword."%");
								$query->orwhere("price","like","%".$keyword."%");
								$query->orwhere("discount","like","%".$keyword."%");
								$query->orwhere("product_code","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive","available","unavailable"))
						->count();
		return $data;
	}

	public function getDetail($id) {
		$data = ProductModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = ProductModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = ProductModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return ProductModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		$result = ProductModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

	public function getAuth($data){
		return ProductModel::where("username",$data['username'])->where("password",$data['password'])->where("status","active")->first();
	}

	public function activateAccount($code, $data){
		return ProductModel::where("activation_code",$code)->update($data);
	}

	public function createActivationCode(){
    $code = "";
    $length = 16;
    $status = true;
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    do{
      $code = "";
      for($i=0; $i < $length; $i++){
        $code = $code.substr($alphabet,rand(0,strlen($alphabet)-1),1);
      }

      $checkavailability = UserModel::where("activation_code",$code)->count();
      if($checkavailability){
        $status = true;
      }else{
        $status = false;
      }
    }while($status);

    return $code;
  }

	public function createaffiliatecode(){
		$code = "";
    $length = 8;
    $status = true;
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    do{
      $code = "";
      for($i=0; $i < $length; $i++){
        $code = $code.substr($alphabet,rand(0,strlen($alphabet)-1),1);
      }

      $checkavailability = ProductModel::where("affiliate_code",$code)->count();
      if($checkavailability){
        $status = true;
      }else{
        $status = false;
      }
    }while($status);

    return $code;
	}

	public function getRandomPassword($length){
		$code 		= "";
    $status 	= true;
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		for($i=0; $i < $length; $i++){
			$code = $code.substr($alphabet,rand(0,strlen($alphabet)-1),1);
		}
		return $code;
	}
}
