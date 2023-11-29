<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductionOutletModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_product_outlet_production';
	protected $primaryKey   = 'id_outlet_production';
	protected $fillable     = ['id_outlet_production', 'production_code','time_created','last_update','id_outlet','status','author','production_date'];
  protected $prestring 				= "PROD";
	protected $digittransaction = 10;

	public function getListOfProductWasted($id_outlet,$date){
		$data = ProductionOutletModel::where("id_outlet",$id_outlet)
						->select(DB::raw("(IFNULL(SUM(total_produksi),0) - IFNULL(total_jual,0)) as total_item"),DB::raw("production_date as tanggal"),DB::raw("total_jual as total_lost"),"production_date")
						->join(DB::raw("(SELECT SUM(quantity) as total_produksi, id_outlet_production FROM tb_product_outlet_production_items WHERE status = 'active' GROUP BY id_outlet_production)prod"),"prod.id_outlet_production","=",$this->table.".id_outlet_production")
						->leftjoin(DB::raw("(SELECT SUM(quantity) as total_jual, sell_date FROM tb_product_outlet_sell_item,tb_product_outlet_sell WHERE tb_product_outlet_sell_item.id_outlet_sell = tb_product_outlet_sell.id_outlet_sell AND tb_product_outlet_sell_item.status = 'active' AND tb_product_outlet_sell.status = 'paid' AND id_outlet = '$id_outlet' GROUP BY sell_date)jual"),"jual.sell_date","=","production_date")
						->groupBy("production_date")
						->get();
		//print $data;
		//exit();
		return $data;
	}

	public function getListofProduction($id_outlet){
		$data = ProductionOutletModel::where("id_outlet",$id_outlet)->where("status","done")
						->select("*",DB::raw("IFNULL(production_qty,0) as production_qty"))
						->leftjoin(DB::raw("(SELECT SUM(quantity) as production_qty, id_outlet_production as iop FROM tb_product_outlet_production_items WHERE status = 'active' GROUP BY id_outlet_production)prod"),"prod.iop","=",$this->table.".id_outlet_production")
						->leftjoin(DB::raw("(SELECT first_name as author, id_user FROM tb_user WHERE status='active')us"),"us.id_user","=",$this->table.".author")
						->orderBy("production_code","DESC")
						->get();

		return $data;
	}

	public function getTodayProduction($id_shop,$date){
		$data = ProductionOutletModel::where("id_outlet",$id_shop)->where("status","done")->where("production_date",$date)
						->leftjoin(DB::raw("(SELECT SUM(quantity) as total_produksi, id_outlet_production as iop FROM tb_product_outlet_production_items WHERE status = 'active' GROUP BY id_outlet_production)prod"),"prod.iop","=",$this->table.".id_outlet_production")
						->get();

		if($data){
			$qty = 0;
			foreach ($data as $key => $value) {
				$qty = $qty + $value->total_produksi;
			}
			return $qty." Potong Ayam";
		}else{
			return 0;
		}
	}

	public function getDetail($id) {
		$data = ProductionOutletModel::where($this->table.".id_outlet_production",$id)->leftjoin(DB::raw("(SELECT SUM(quantity) as total_production,id_outlet_production as iop FROM tb_product_outlet_production_items WHERE tb_product_outlet_production_items.status = 'active')outlet_production"),"outlet_production.iop","=",$this->table.".id_outlet_production")->first();
		return $data;
	}

  public function getData($str, $limit, $keyword, $short, $shortmode,$outlet){
    $id_outlet = $outlet->id_mitra;
		$data = ProductionOutletModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("production_code","like","%".$keyword."%");
								$query->orwhere("production_date","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","pending","done"))
            ->where($this->table.".id_outlet",$id_outlet)
						->select($this->table.".id_outlet_production","total_production","production_code","production_date","time_created",$this->table.".status")
            ->leftjoin(DB::raw("(SELECT SUM(quantity) as total_production,id_outlet_production FROM tb_product_outlet_production_items WHERE tb_product_outlet_production_items.status = 'active' GROUP BY id_outlet_production)outlet_production"),"outlet_production.id_outlet_production","=",$this->table.".id_outlet_production")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

  public function countData($keyword){
		$data = ProductionOutletModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("production_code","like","%".$keyword."%");
								$query->orwhere("production_date","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","pending","done"))
            ->leftjoin(DB::raw("(SELECT SUM(quantity) as total_production,id_outlet_production FROM tb_product_outlet_production_items WHERE tb_product_outlet_production_items.status = 'active')outlet_production"),"outlet_production.id_outlet_production","=",$this->table.".id_outlet_production")
						->count();
		return $data;
	}

	public function getItems($id_outlet){
		return ProductionOutletModel::where("id_outlet",$id_outlet)->wherein($this->table.".status",array("active"))->get();
	}

	public function insertData($data){
		$result = ProductionOutletModel::create($data)->id_outlet_production;
		return $result;
  }

	public function updateData($data){
		$result = ProductionOutletModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

  public function createCode(){
		$data  = ProductionOutletModel::where("production_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

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
