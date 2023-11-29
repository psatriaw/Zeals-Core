<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RekeningDanaModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_rekening_dana';
	protected $primaryKey   = 'id_rekening_dana';
	protected $fillable     = ['id_rekening_dana','time_created','last_update','id_user','saldo','status'];

	public function getDetail($id) {
		$data = RekeningDanaModel::where("id_cart_detail",$id)->join(DB::raw("(SELECT SUM(qty) as total_dapat FROM tb_brownies_cart_pemenuhan WHERE status = 'active')penuhan"),"pemenuhan.id_cart_detail","=",$this->table.".id_cart_detail");
		return $data;
	}

	public function updateSaldo($id_user, $usage){
        $query = "UPDATE tb_rekening_dana SET saldo = saldo - $usage WHERE id_user = $id_user";
		return DB::statement(DB::raw($query));
    }

	public function getItems($id, $path){
		return RekeningDanaModel::where("id_cart",$id)
						->where("status","active")
						->join(DB::raw("(SELECT product_name, id_product FROM tb_product WHERE status IN ('active','available'))cd"),"cd.id_product","=",$this->table.".id_product")
						->leftjoin(DB::raw("(SELECT path , CONCAT('$path','/',thumbnail) as product_photo, tb_product_detail.id_product as idp FROM tb_photo, tb_product_detail WHERE tb_photo.id_photo = tb_product_detail.value AND tb_product_detail.status = 'active' AND tb_product_detail.type = 'photo')ph"),"ph.idp","=",$this->table.".id_product")
						->get();
	}

  public function getCurrentRekening($id_user){
    $check = RekeningDanaModel::where("id_user",$id_user)->skip(0)->take(1)->orderBy("id_rekening_dana","DESC")->first();
    if($check){
      return $check;
    }else{
      $datacreate = array(
        "id_user" 			=> $id_user,
        "status"  			=> "active",
				"time_created"	=> time(),
				"last_update"		=> time(),
        "saldo"   			=> 0
      );

      $this->insertData($datacreate);

      $data = $this->getCurrentRekening($id_user);
      return $data;
    }
  }

	public function insertData($data){
		$result = RekeningDanaModel::create($data)->id_rekening_dana;
		return $result;
  }

	public function updateData($data){
		$result = RekeningDanaModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id_cart_detail){
		$data = array(
			"id_cart_detail"	=> $id_cart_detail,
			"last_update"			=> time(),
			"status"					=> "deleted"
		);

		$result = RekeningDanaModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}


}
