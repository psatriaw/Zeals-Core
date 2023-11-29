<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CartDetailModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_cart_detail';
	protected $primaryKey   = 'id_cart_detail';
	protected $fillable     = ['id_cart_detail', 'id_cart','time_created','last_update','id_campaign','quantity','status','harga_beli','id_campaign_product','id_user'];

	public function getDetail($id) {
		$data = CartDetailModel::where("id_cart_detail",$id)->join(DB::raw("(SELECT SUM(qty) as total_dapat FROM tb_brownies_cart_pemenuhan WHERE status = 'active')penuhan"),"pemenuhan.id_cart_detail","=",$this->table.".id_cart_detail");
		return $data;
	}

	public function getDetailByIDCart($id_cart){
		$data = CartDetailModel::where("id_cart",$id_cart)->first();
		return $data;
	}

	public function getItems($id, $path){
		return CartDetailModel::where("id_cart",$id)
						->where("status","active")
						->join(DB::raw("(SELECT product_name, id_product FROM tb_product WHERE status IN ('active','available'))cd"),"cd.id_product","=",$this->table.".id_product")
						->leftjoin(DB::raw("(SELECT path , CONCAT('$path','/',thumbnail) as product_photo, tb_product_detail.id_product as idp FROM tb_photo, tb_product_detail WHERE tb_photo.id_photo = tb_product_detail.value AND tb_product_detail.status = 'active' AND tb_product_detail.type = 'photo')ph"),"ph.idp","=",$this->table.".id_product")
						->get();
	}

	public function insertData($data){
		$result = CartDetailModel::create($data)->id_cart_detail;
		return $result;
  }

	public function masukData($data){
		$result = CartDetailModel::create($data)->id_cart_detail;
		return $result;
	}

	public function updateData($data){
		$result = CartDetailModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id_cart_detail){
		$data = array(
			"id_cart_detail"	=> $id_cart_detail,
			"last_update"			=> time(),
			"status"					=> "deleted"
		);

		$result = CartDetailModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}
}
