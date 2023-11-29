<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductionOutletItemModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_product_outlet_production_items';
	protected $primaryKey   = 'id_outlet_production_item';
	protected $fillable     = ['id_outlet_production_item', 'id_product','time_created','last_update','id_outlet_production','status','author','quantity'];
  protected $prestring 				= "PROD";
	protected $digittransaction = 10;

	public function getCostOfWasted($date,$id_outlet){
		$query = "SELECT SUM(tb_product_outlet_production_items.quantity) as total_produksi, production_code, product_name, production_date, IFNULL(total_jual,0) as total_jual, price, (IFNULL(SUM(tb_product_outlet_production_items.quantity),0) - IFNULL(total_jual,0))*price as total_lost, id_outlet
							FROM `tb_product_outlet_production_items`
							INNER JOIN (SELECT id_outlet_production, production_code, production_date, id_outlet FROM tb_product_outlet_production WHERE id_outlet = '$id_outlet' AND production_date = '$date')tbp ON tbp.id_outlet_production = tb_product_outlet_production_items.`id_outlet_production`
							LEFT JOIN (SELECT id_product as idp, product_name, price FROM tb_product)tp ON idp = tb_product_outlet_production_items.id_product
							LEFT JOIN (SELECT IFNULL(SUM(quantity),0) as total_jual, id_product as idps  FROM tb_product_outlet_sell, tb_product_outlet_sell_item WHERE tb_product_outlet_sell.id_outlet_sell = tb_product_outlet_sell_item.id_outlet_sell AND tb_product_outlet_sell.status = 'paid' AND `tb_product_outlet_sell_item`.status = 'active' AND tb_product_outlet_sell.trx_date = '$date' AND tb_product_outlet_sell.id_outlet = '$id_outlet' GROUP BY tb_product_outlet_sell_item.id_product)jual ON idps = tb_product_outlet_production_items.id_product
							GROUP BY id_product
							";
		//print_r($query);
		//print "<br><br><br>";
		$data   = DB::raw($query);
		$data 	= DB::select($data);
		if(sizeof($data)){
			$total = 0;
			foreach ($data as $key => $value) {
				//print_r($value);
				$total = $total + $value->total_lost;
			}
			if($total<0){
				return 0;
			}
			return $total;
		}else{
			return 0;
		}
	}

	public function getListOfProductWasted($id_outlet,$date){
		$data = ProductionOutletItemModel::select(DB::raw("(SUM(total_produksi) - total_jual) as total_item"),DB::raw("production_date as tanggal"),DB::raw("total_jual as total_lost"))
						->join(DB::raw("(SELECT SUM(quantity) as total_produksi, id_outlet_production FROM tb_product_outlet_production WHERE status = 'active' GROUP BY id_outlet_production)prod"),"prod.id_outlet_production","=",$this->table.".id_outlet_production")
						->leftjoin(DB::raw("(SELECT SUM(quantity) as total_jual, sell_date FROM tb_product_outlet_sell_item,tb_product_outlet_sell WHERE tb_product_outlet_sell_item.id_outlet_sell = tb_product_outlet_sell.id_outlet_sell AND tb_product_outlet_sell_item.status = 'active' AND tb_product_outlet_sell.status = 'paid' AND id_outlet = '$id_outlet' GROUP BY sell_date)jual"),"jual.sell_date","=","production_date")
						->groupBy($this->table.".id_product")
						->get();

		$query = "SELECT SUM(tb_product_outlet_production_items.quantity) as total_produksi,total_jual, product_name
							FROM 		tb_product_outlet_production_items, tb_product_outlet_production
							LEFT JOIN (SELECT SUM(quantity) as total_jual,id_product as idpj FROM tb_product_outlet_sell,tb_product_outlet_sell_item WHERE tb_product_outlet_sell.id_outlet_sell = tb_product_outlet_sell_item.id_outlet_sell AND id_outlet = '$id_outlet' GROUP BY idpj)jual ON jual.idpj = id_product
							JOIN (SELECT product_name, id_product as idp FROM tb_product)pd ON pd.idp = id_product
							WHERE 	tb_product_outlet_production_items.id_outlet_production = tb_product_outlet_production.id_outlet_production
							AND 		tb_product_outlet_production.id_outlet = '$id_outlet'
							AND 		tb_product_outlet_production.status = 'done'
							AND 		tb_product_outlet_production_items.status	= 'active'
							GROUP BY id_product
							";

		$data   = DB::raw($query);
		//print $data;
		//exit();
		return $data;
	}

	public function getDetail($id) {
		$data = ProductionOutletItemModel::where($this->table.".id_outlet_production_item",$id)->first();
		return $data;
	}

  public function getData($str, $limit, $keyword, $short, $shortmode,$outlet){
    $id_outlet = $outlet->id_mitra;
		$data = ProductionOutletItemModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("production_code","like","%".$keyword."%");
								$query->orwhere("production_date","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active"))
            ->where($this->table.".id_outlet",$id_outlet)
						->select($this->table.".id_outlet_production","total_production","production_code","production_date","time_created","tbp.product_name")
            ->leftjoin(DB::raw("(SELECT product_name, id_product FROM tb_product WHERE status IN ('active','available'))"),"tbp.id_product","=",$this->table.".id_product")
            ->leftjoin(DB::raw("(SELECT SUM(quantity) as total_production,id_outlet_production FROM tb_product_outlet_production_items WHERE tb_product_outlet_production_items.status = 'active')outlet_production"),"outlet_production.id_outlet_production","=",$this->table.".id_outlet_production")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

  public function countData($keyword){
		$data = ProductionOutletItemModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("production_code","like","%".$keyword."%");
								$query->orwhere("production_date","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active"))
            ->leftjoin(DB::raw("(SELECT SUM(quantity) as total_production,id_outlet_production FROM tb_product_outlet_production_items WHERE tb_product_outlet_production_items.status = 'active')outlet_production"),"outlet_production.id_outlet_production","=",$this->table.".id_outlet_production")
						->count();
		return $data;
	}

  public function getListProducts($id_outlet_production){
    return ProductionOutletItemModel::where("id_outlet_production",$id_outlet_production)->where("status","active")->leftjoin(DB::raw("(SELECT product_name, id_product FROM tb_product WHERE status IN ('active','available'))tbp"),"tbp.id_product","=",$this->table.".id_product")->get();
  }

  public function getPluckProducts($id_outlet_production){
    $data =  ProductionOutletItemModel::where("id_outlet_production",$id_outlet_production)->leftjoin(DB::raw("(SELECT product_name, id_product FROM tb_product WHERE status IN ('active','available'))tbp"),"tbp.id_product","=",$this->table.".id_product")->get();
    if($data){
      foreach ($data as $key => $value) {
        $ret[$value->id_product] = $value->product_name;
      }
    }

    return $ret;
  }

	public function getItems($id_outlet){
		return ProductionOutletItemModel::where("id_outlet",$id_outlet)->wherein($this->table.".status",array("active"))->get();
	}

	public function insertData($data){
		$result = ProductionOutletItemModel::create($data)->id_outlet_production_item;
		return $result;
  }

	public function updateData($data){
		$result = ProductionOutletItemModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

  public function createCode(){
		$data  = ProductionOutletItemModel::where("production_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

		if($data->count()>0){
			$data 			= $data->toArray()[0];
			$transnumb  = explode($this->prestring,$data['production_code']);
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
