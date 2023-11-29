<?php
namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductRejectModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_brownies_product_reject';
	protected $primaryKey   = 'id_product_reject';
	protected $fillable     = ['id_product_reject', 'id_cart','time_created','last_update','author','qty','status','id_product','date_rejection','alasan'];

	public function getDataReject($date, $date2){
		$data = "SELECT IFNULL(SUM(qty),0) as total FROM tb_brownies_product_reject WHERE status = 'active' AND date_rejection BETWEEN '$date' AND '$date2'";
		//print $data."<br><br>";
		$data = DB::select(DB::raw($data));
		if($data){
			return $data[0]->total;
		}else{
			return 0;
		}
	}

  public function getDataProductionAtThatTime($date){
    $data = "SELECT IFNULL(SUM(qty),0) as total FROM tb_cart, tb_cart_detail, tb_brownies_cart_pemenuhan WHERE tb_cart.id_cart = tb_cart_detail.id_cart AND tb_cart_detail.id_cart_detail = tb_brownies_cart_pemenuhan.id_cart_detail AND tb_cart.cart_date = '$date' GROUP BY cart_date";
		$data = DB::select(DB::raw($data));
		if($data){
			return $data[0]->total;
		}else{
			return 0;
		}
  }

	public function getDataPermintaanAtThatTime($date){
		$data = "SELECT IFNULL(SUM(quantity),0) as total FROM tb_cart_detail,tb_cart WHERE tb_cart.id_cart = tb_cart_detail.id_cart AND tb_cart_detail.status = 'active' AND cart_date = '$date'";
		$data = DB::select(DB::raw($data));
		if($data){
			return $data[0]->total;
		}else{
			return 0;
		}
	}

	public function getDataReject2($date1, $date2){
		$data = ProductRejectModel::where(DB::raw("date_rejection BETWEEN '$date1' AND '$date2'"))->sum("qty");
		return $data;
	}

	public function countProductReject($id_product,$id_cart) {
		$data = ProductRejectModel::where("id_product",$id_product)->where("id_cart",$id_cart)->sum("qty");
		return $data;
	}

	public function getDetail($id) {
		$data = ProductRejectModel::find($id);
		return $data;
	}

	public function getItems($id){

	}

	public function insertData($data){
		$result = ProductRejectModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = ProductRejectModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}


}
