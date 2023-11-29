<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_product_category';
	protected $primaryKey   = 'id_product_category';
	protected $fillable     = ['id_product_category', 'category_name','code','icon','banner','id_parent','time_created','last_update','status'];

	public function getListOfCategories(){
		return CategoryModel::where('status','active')->orderBy('category_name', 'ASC')->get();
	}

  public function getList(){
    return CategoryModel::orderBy('category_name')->pluck('category_name', 'id_product_category');
  }

  public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = CategoryModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("category_name","like","%".$keyword."%");
                $query->orwhere("time_created","like","%".$keyword."%");
                $query->orwhere("last_update","like","%".$keyword."%");
							}
						})
						->leftjoin(DB::raw("(SELECT COUNT(*) as total_campaign, value FROM tb_campaign_property WHERE property_type = 'category' GROUP BY value)tot"),"tot.value","=",$this->table.".id_product_category")
            ->leftjoin(DB::raw("(SELECT CONCAT(category_name,' [',code,']') as parent_name, id_product_category as idp FROM tb_product_category WHERE status IN ('active','inactive'))parent"),"parent.idp","=",$this->table.".id_parent")
            ->wherein("status", array("active","inactive"))
            ->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = CategoryModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("category_name","like","%".$keyword."%");
                $query->orwhere("time_created","like","%".$keyword."%");
                $query->orwhere("last_update","like","%".$keyword."%");
							}
						})
            ->wherein("status", array("active","inactive"))
						->count();
		return $data;
	}

  public function getDetail($id) {
		$data = CategoryModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = CategoryModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = CategoryModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}
}
