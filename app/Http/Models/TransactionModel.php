<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_transaction';
	protected $primaryKey   = 'id_transaction';
	protected $fillable     = ['id_transaction', 'total_amount','time_created','id_author','id_ref','type','trx_code','transaction_status','order_id','bank','status','mc_bank_name','mc_bank_account','mc_bank_account_number','mc_time_request','mc_status','mc_bank_target','discount','id_discount','last_update','transaction_code','mc_moderator','mc_moderator_id','mc_moderator_time','mc_total_amount','mc_date'];
	protected $prestring 				= "TR";
	protected $digittransaction = 12;

	public function getTransactionwithstatus($status, $type){
		$data = TransactionModel::where("status",$status)
						->join(DB::raw("(SELECT id_cart FROM tb_cart WHERE type_order = '$type')cart"),"cart.id_cart","=","order_id")
						->count();
		return $data;
	}

	public function getPermintaanRecipe(){
		$data = TransactionModel::where("status","pre-production");
		$data = $data->join(DB::raw("(SELECT cart_code,id_cart, type_order FROM tb_cart WHERE status_joined = 'false' AND type_order='recipe')cart"),"cart.id_cart","=",$this->table.".order_id");
		$data = $data->leftjoin(DB::raw("(SELECT id_source_bahan, production_code FROM tb_production WHERE status = 'done' group by id_source_bahan)prod"),"prod.id_source_bahan","=","cart.id_cart")
						//->where("id_source_bahan",">","0")
						->orderBy("id_cart","ASC");
		return $data->get();
	}

	public function getTransactionCost($id){
		//bahan HPP produk
		$query 	= "SELECT SUM(hpp_produk * tb_brownies_cart_pemenuhan.qty) as total_hpp_produk FROM tb_brownies_cart_pemenuhan,tb_cart_detail WHERE tb_brownies_cart_pemenuhan.id_cart_detail = tb_cart_detail.id_cart_detail AND tb_brownies_cart_pemenuhan.status = 'active' AND tb_cart_detail.status = 'active' AND id_cart = $id GROUP BY id_cart";
		$hpp_produk = DB::select(DB::raw($query));
		if($hpp_produk){
			$hpp_produk = $hpp_produk[0]->total_hpp_produk;
		}else{
			$hpp_produk = 0;
		}

		//bahan HPP loyang/naikan
		$query 	= "SELECT SUM(hpp) as total_hpp_loyang FROM tb_brownies_naikan,tb_brownies_stock_unit WHERE tb_brownies_naikan.id_naikan = tb_brownies_stock_unit.id_naikan AND production_status = 'used' AND id_cart = '$id' GROUP BY id_cart";
		$hpp_naikan = DB::select(DB::raw($query));
		if($hpp_naikan){
			$hpp_naikan = $hpp_naikan[0]->total_hpp_loyang;
		}else{
			$hpp_naikan = 0;
		}
		//bahan lain2 diluar MRP
		$query  = "SELECT SUM(tb_brownies_bahan_penyerta_produksi.quantity * tb_purchase_detail.item_price) as total_hpp_produksi FROM tb_brownies_bahan_penyerta_produksi, tb_purchase_detail WHERE id_cart = $id AND tb_brownies_bahan_penyerta_produksi.id_purchase_detail = tb_purchase_detail.id_purchase_detail GROUP BY id_cart";
		$hpp_produksi = DB::select(DB::raw($query));
		if($hpp_produksi){
			$hpp_produksi = $hpp_produksi[0]->total_hpp_produksi;
		}else{
			$hpp_produksi = 0;
		}

		$total  = $hpp_produk + $hpp_naikan + $hpp_produksi;
		return $total;
	}

	public function getTransactionCostByDate($date,$date2){
		//bahan HPP produk
		$query 	= "SELECT IFNULL(SUM(hpp_produk * tb_brownies_cart_pemenuhan.qty),0) as total_hpp_produk FROM tb_brownies_cart_pemenuhan,tb_cart_detail, tb_cart WHERE tb_cart.id_cart = tb_cart_detail.id_cart AND tb_brownies_cart_pemenuhan.id_cart_detail = tb_cart_detail.id_cart_detail AND tb_brownies_cart_pemenuhan.status = 'active' AND tb_cart_detail.status = 'active' AND cart_date BETWEEN '$date' AND '$date2'";
		$hpp_produk = DB::select(DB::raw($query));
		if($hpp_produk){
			$hpp_produk = $hpp_produk[0]->total_hpp_produk;
		}else{
			$hpp_produk = 0;
		}

		//print $hpp_produk."<br>";

		//bahan HPP loyang/naikan
		$query 	= "SELECT IFNULL(SUM(hpp),0) as total_hpp_loyang FROM tb_brownies_naikan,tb_brownies_stock_unit,tb_cart WHERE tb_cart.id_cart = tb_brownies_naikan.id_cart AND tb_brownies_naikan.id_naikan = tb_brownies_stock_unit.id_naikan AND production_status = 'used' AND cart_date BETWEEN '$date' AND '$date2'";
		$hpp_naikan = DB::select(DB::raw($query));
		if($hpp_naikan){
			$hpp_naikan = $hpp_naikan[0]->total_hpp_loyang;
		}else{
			$hpp_naikan = 0;
		}
		//print $hpp_naikan."<br>";

		//bahan lain2 diluar MRP
		$query  = "SELECT IFNULL(SUM(tb_brownies_bahan_penyerta_produksi.quantity * tb_purchase_detail.item_price),0) as total_hpp_produksi FROM tb_brownies_bahan_penyerta_produksi, tb_purchase_detail, tb_cart WHERE tb_cart.id_cart = tb_brownies_bahan_penyerta_produksi.id_cart AND tb_brownies_bahan_penyerta_produksi.id_purchase_detail = tb_purchase_detail.id_purchase_detail AND cart_date BETWEEN '$date' AND '$date2'";
		$hpp_produksi = DB::select(DB::raw($query));
		if($hpp_produksi){
			$hpp_produksi = $hpp_produksi[0]->total_hpp_produksi;
		}else{
			$hpp_produksi = 0;
		}
		//print $hpp_produksi."<br>";
		//exit();
		$total  = $hpp_produk + $hpp_naikan + $hpp_produksi;
		return $total;
	}

	public function getData($str, $limit, $keyword, $short, $shortmode, $type_order="", $step=""){
		$data = TransactionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("transaction_code","like","%".$keyword."%");
								$query->orwhere("cart_code","like","%".$keyword."%");
								$query->orwhere("first_name","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("accepted","deleted","rejected","pending","pre-production","pre-inisiation","queue","production","ready","packed","shipted","done","confirmed","accepted"))
						->select($this->table.".*","first_name","cart_date","cart_code","id_cart","total_permintaan","type_order","total_produksi")
						->leftjoin(DB::raw("(SELECT first_name,id_user FROM tb_user)user"),"user.id_user","=",$this->table.".id_author")
						->leftjoin(DB::raw("(SELECT SUM(qty) as total_produksi, id_cart as id_cp FROM tb_brownies_cart_pemenuhan, tb_cart_detail WHERE tb_brownies_cart_pemenuhan.id_cart_detail = tb_cart_detail.id_cart_detail GROUP BY id_cp)prod"),"prod.id_cp","=",$this->table.".order_id")
						->leftjoin(DB::raw("(SELECT SUM(quantity) as total_permintaan, id_cart AS idcc FROM tb_cart_detail WHERE status = 'active'  GROUP BY idcc)cartd"),"cartd.idcc","=",$this->table.".order_id")
						->where("type","purchase");

						if($type_order!=""){
							$data = $data->join(DB::raw("(SELECT cart_code,id_cart, type_order, cart_date FROM tb_cart WHERE type_order IN ($type_order))cart"),"cart.id_cart","=",$this->table.".order_id");
						}else{
							$data = $data->leftjoin(DB::raw("(SELECT cart_code,id_cart, type_order, cart_date FROM tb_cart)cart"),"cart.id_cart","=",$this->table.".order_id");
						}

						if($step!=""){
							$data = $data->wherein("status",$step);
						}

						$data = $data->orderBy($short, $shortmode)
										->skip($str)
				            ->limit($limit)
										->get();
		return $data;
	}

	public function countData($keyword, $type_order=""){
		$data = TransactionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("transaction_code","like","%".$keyword."%");
								$query->orwhere("cart_code","like","%".$keyword."%");
								$query->orwhere("first_name","like","%".$keyword."%");
							}
						});

						if($type_order!=""){
							$data = $data->join(DB::raw("(SELECT cart_code,id_cart, type_order FROM tb_cart WHERE type_order = '$type_order')cart"),"cart.id_cart","=",$this->table.".order_id");
						}else{
							$data = $data->leftjoin(DB::raw("(SELECT cart_code,id_cart, type_order FROM tb_cart)cart"),"cart.id_cart","=",$this->table.".order_id");
						}

						$data = $data->wherein($this->table.".status",array("accepted","deleted","rejected","pending","pre-production","pre-inisiation","ready","queue","production","packed","shipted","done"))
						->count();
		return $data;
	}

	public function createCode(){
		$data  = TransactionModel::where("transaction_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

		if($data->count()>0){
			$data 			= $data->toArray()[0];
			$transnumb  = explode($this->prestring,$data['transaction_code']);
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

	public function getDetail($id) {
		$data = TransactionModel::where($this->primaryKey,$id)
						->join(DB::raw("(SELECT cart_code, id_cart,id_mitra,id_user,type_order,cart_date FROM tb_cart)tb_cart"),"tb_cart.id_cart","=",$this->table.".order_id")
						->leftjoin(DB::raw("(SELECT mitra_name, id_mitra, mitra_code FROM tb_fremilt_mitra)tb_fremilt_mitra"),"tb_fremilt_mitra.id_mitra","=","tb_cart.id_mitra")
						->leftjoin(DB::raw("(SELECT first_name, id_user FROM tb_user)tb_user"),"tb_user.id_user","=","tb_cart.id_user")
						->leftjoin(DB::raw("(SELECT first_name as admin_name, id_user FROM tb_user)author"),"author.id_user","=",$this->table.".id_author")
						->first();
		return $data;
	}

	public function insertData($data){
		$result = TransactionModel::create($data)->id_transaction;
		return $result;
  }

	public function updateData($data){
		$result = TransactionModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}


}
