<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class XenditLogModel extends Model
{
	public $timestamps      = false;
	const CREATED_AT        = 'time_created';
	const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_xendit_log';
	protected $primaryKey   = 'id_payment';
	protected $fillable     = ['id_log','time_created','content'];
  protected $prestring 				= "PAYOUT";
	protected $digittransaction = 10;

	public function getAllVideo($str, $limit, $keyword, $short, $shortmode)
	{
		$data = XenditLogModel::where(function ($query) use ($keyword) {
			if ($keyword != "") {
				$query->where("title", "like", "%" . $keyword . "%");
			}
		})
			->wherein($this->table . ".status", array("active"))
			// ->select($this->table . ".*", "first_name", "cart_code")
			// ->where("status", "active")
			->select('*')
			->orderBy($short, $shortmode)
			->skip($str)
			->limit($limit)
			->get();
		return $data;
	}

	public function getAllDataProduksi()
	{
		$data = "SELECT IFNULL(SUM(qty),0) as total FROM tb_brownies_cart_pemenuhan WHERE status = 'active'";
		$data = DB::select(DB::raw($data));

		$data_reject = "SELECT IFNULL(SUM(qty),0) as total FROM tb_brownies_product_reject WHERE status = 'active'";
		$data_reject = DB::select(DB::raw($data_reject));
		if ($data_reject) {
			$datareject = $data_reject[0]->total;
		} else {
			$datareject = 0;
		}

		if ($data) {
			return $data[0]->total - $datareject;
		} else {
			return 0;
		}
	}

	public function getDataProductionAtThatTime($date, $date2)
	{
		$data = "SELECT IFNULL(SUM(qty),0) as total FROM tb_cart, tb_cart_detail, tb_brownies_cart_pemenuhan WHERE tb_cart.id_cart = tb_cart_detail.id_cart AND tb_cart_detail.id_cart_detail = tb_brownies_cart_pemenuhan.id_cart_detail AND tb_cart.cart_date BETWEEN '$date' AND '$date2'";
		//print $data;
		$data = DB::select(DB::raw($data));
		if ($data) {
			return $data[0]->total;
		} else {
			return 0;
		}
	}

	public function getDataPermintaanAtThatTime($date, $date2)
	{
		$data = "SELECT IFNULL(SUM(quantity),0) as total FROM tb_cart_detail,tb_cart WHERE tb_cart.id_cart = tb_cart_detail.id_cart AND tb_cart_detail.status = 'active' AND cart_date BETWEEN '$date' AND '$date2'";
		//print $data;
		$data = DB::select(DB::raw($data));
		if ($data) {
			return $data[0]->total;
		} else {
			return 0;
		}
	}

	public function getVideoData()
	{
		$data = XenditLogModel::where("status", "active")->orderBy("time_created", "DESC");
		return $data;
	}

	public function getDetail($id)
	{
		$data = XenditLogModel::find($id);
		return $data;
	}

	public function getItems($id)
	{
	}

	public function insertData($data){
		$result = XenditLogModel::create($data)->id_log;
		return $result;
	}

	public function updateData($data){
		$result = XenditLogModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
		return $result;
	}

  public function createCode(){
		$data  = XenditLogModel::where("invoice_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

		if($data->count()>0){
			$data 			= $data->toArray()[0];
			$transnumb  = explode($this->prestring,$data['invoice_code']);
			$number     = intval($transnumb[1]);
			$nextnumber = $number+1;
			return $this->formattransaction($this->prestring,$nextnumber);
		}else{
			return $this->formattransaction($this->prestring,1);
		}
	}

	public function formattransaction($prestring, $number){
		$lengthofnumber = strlen(strval($number));
		$lengthrest     = $this->digittransaction - $lengthofnumber;
		if($lengthrest>0){
			$transnumb = strval($number);
			for($i=0;$i<$lengthrest;$i++){
				$transnumb = "0".$transnumb;
			}
			$transnumb = $prestring.$transnumb;
			return $transnumb;
		}else{
			return $prestring.$number;
		}
	}
}
