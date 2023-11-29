<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductDetailModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_product_detail';
	protected $primaryKey   = 'id_product_detail';
	protected $fillable     = ['id_product_detail', 'type','value','description','time_created','status','id_product','last_update'];

	public function getDetail($id) {
		$data = ProductDetailModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = ProductDetailModel::create($data)->id_product_detail;
		return $result;
  }

	public function updateData($data){
		$result = ProductDetailModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id){
		$result = ProductDetailModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
}
