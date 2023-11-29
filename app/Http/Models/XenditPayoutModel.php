<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class XenditPayoutModel extends Model
{
	public $timestamps      = false;
	const CREATED_AT        = 'time_created';
	const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_payment';
	protected $primaryKey   = 'id_payment';
	protected $fillable     = ['id_payment','time_created', 'payment_time', 'last_update', 'total_amount', 'total_pajak', 'total_fee', 'fee_type', 'pajak_type','bank_account','bank_account_number','bank_account_name','trx_callback','trx_status','invoice_code','description','trx_sign','trx_type','trx_response','va_number'];
  protected $prestring 				= "UMPAY";
	protected $digittransaction = 10;

	public function getAllVideo($str, $limit, $keyword, $short, $shortmode)
	{
		$data = XenditPayoutModel::where(function ($query) use ($keyword) {
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

	public function getDetailByInvoiceCode($invoice_code)
	{
		$data = XenditPayoutModel::where("invoice_code", $invoice_code)->first();
		return $data;
	}

	public function getDetail($id)
	{
		$data = XenditPayoutModel::find($id);
		return $data;
	}

	public function getItems($id)
	{
	}

	public function insertData($data){
		$result = XenditPayoutModel::create($data)->id_payment;
		return $result;
	}

	public function updateData($data){
		$result = XenditPayoutModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
		return $result;
	}

  public function createCode(){
		$data  = XenditPayoutModel::where("invoice_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

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
