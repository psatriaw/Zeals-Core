<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductRestrictionModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_product_restriction';
	protected $primaryKey   = 'id_product_restriction';
	protected $fillable     = ['id_product_restriction', 'id_purchased','time_created','id_not_allowed','last_update'];

	public function findProduct($keyword){
		$data = ProductRestrictionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("product_name","like","%".$keyword."%");
								$query->orwhere("product_code","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive","available","unavailable"))
						->orderBy("product_name","ASC")
						->get();
		return $data;
	}

	public function getDataRestricted($id){
		return ProductRestrictionModel::where("id_purchased",$id)->join(DB::raw("(SELECT * FROM tb_product WHERE status NOT IN ('removed'))tb_product"),"tb_product.id_product","=",$this->table.".id_not_allowed")->get();
	}

	public function getlistproduct(){
		$data = ProductRestrictionModel::orderBy("product_name","ASC")->get();
		if($data->count()){
			foreach ($data as $key => $value) {
				$option[$value->id_product] = $value->product_name;
			}
		}else{
			$option = array();
		}
		return $option;
	}

  public function getnotallowed(){
    return $this->hasMany(ProductModel::class,"id_product")->where("status","active");
  }


	public function getData($id_product){
		$data = ProductRestrictionModel::getnotallowed()->where("id_purchased",$id_product)->get();
		return $data;
	}

	public function countData($keyword){
		$data = ProductRestrictionModel::where(function($query) use ($keyword){
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
		$data = ProductRestrictionModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = ProductRestrictionModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = ProductRestrictionModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return ProductModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		$result = ProductRestrictionModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
}
