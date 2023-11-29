<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PackingModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_packing';
	protected $primaryKey   = 'id_packing';
	protected $fillable     = ['id_packing', 'packing_code','packing_date','admin_packing','status','delivery_courier','time_created','last_update','id_transaction'];

  public function getList(){
    return PackingModel::orderBy('category_name')->pluck('category_name', 'id_product_category');
  }

  public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = PackingModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("packing_code","like","%".$keyword."%");
                $query->orwhere("time_created","like","%".$keyword."%");
                $query->orwhere("last_update","like","%".$keyword."%");
							}
						})
            ->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = PackingModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("packing_code","like","%".$keyword."%");
                $query->orwhere("time_created","like","%".$keyword."%");
                $query->orwhere("last_update","like","%".$keyword."%");
							}
						})
						->count();
		return $data;
	}

  public function getDetail($id) {
		$data = PackingModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = PackingModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = PackingModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}
}
