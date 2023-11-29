<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class DiscountModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_discount';
	protected $primaryKey   = 'id_discount';
	protected $fillable     = ['id_discount', 'discount_code','description','status','time_expired','time_created','last_update'];

  public function getList(){
    return DiscountModel::orderBy('category_name')->pluck('category_name', 'id_product_category');
  }

  public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = DiscountModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("discount_code","like","%".$keyword."%");
                $query->orwhere("description","like","%".$keyword."%");
							}
						})
            ->wherein("status", array("active","inactive"))
            ->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = DiscountModel::where(function($query) use ($keyword){
              if($keyword!=""){
                $query->where("discount_code","like","%".$keyword."%");
                $query->orwhere("description","like","%".$keyword."%");
              }
						})
            ->wherein("status", array("active","inactive"))
						->count();
		return $data;
	}

  public function getDetail($id) {
		$data = DiscountModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = DiscountModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = DiscountModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}
}
