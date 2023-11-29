<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductOnProductModel extends Model {
	public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

	protected $table 	    = 'tb_product_on_product';
	protected $primaryKey   = 'id_product_on_product';
	protected $fillable     = ['id_product_on_product', 'id_product','time_created','status','last_update','id_sub_product','product_quantity'];


    public function insertData($data){
		$result = ProductOnProductModel::create($data);
		return $result;
    }

	public function updateData($data){
		$result = ProductOnProductModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getListProduct($id){
	    $result = ProductOnProductModel::where("id_product",$id)
								  ->where("status","active")
	                ->join(DB::raw("(SELECT product_name, product_code, id_product as idp FROM tb_product WHERE status = 'available')p"),"p.idp","=",$this->table.".id_sub_product")
	                ->get();

	    return $result;
	}

	public function getDetail($id) {
		$data = ProductOnProductModel::find($id);
		return $data;
	}
}
