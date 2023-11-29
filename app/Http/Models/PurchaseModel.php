<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PurchaseModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_purchase';
	protected $primaryKey   = 'id_purchase';
	protected $fillable     = ['id_purchase', 'purchase_code','status','receiver','time_receive','purchase_type','purchase_author','purchase_title','time_created','last_update','purchase_date','tipe_pembayaran'];

	protected $prestring 				= "PUR";
	protected $digittransaction = 10;

	public function getPurchaseResume($start,$end){
		//$data = PurchaseModel::where(DB::raw("purchase_date BETWEEN '$start' AND '$end'"))
		//				->join(DB::raw("(SELECT SUM(item_price * item_quantity) AS total, id_purchase FROM tb_purchase_detail GROUP BY id_purchase)pd"),"pd.id_purchase","=",$this->table.".id_purchase")
		//				->select(DB::raw("SUM(total) as totalan"))
		$query = "SELECT SUM(total) as totalan FROM tb_purchase JOIN (SELECT SUM(item_price * item_quantity) AS total, id_purchase FROM tb_purchase_detail WHERE status = 'ok' GROUP BY id_purchase)pd ON pd.id_purchase = tb_purchase.id_purchase WHERE purchase_date BETWEEN '$start' AND '$end' ";
		$data 	= DB::select(DB::raw($query));

		return $data[0]->totalan;
	}

  public function previleges(){
    return $this->hasMany(MethodModel::class,"id_module");
  }

  public function getDataSubModule($id){
    return PurchaseModel::find($id)->previleges()->get();
  }

	public function getItemPurchase($id_material){
		$data 	= "SELECT";
	}

	public function getData($str, $limit, $keyword, $short, $shortmode, $status){
		$data = PurchaseModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("purchase_code","like","%".$keyword."%");
								$query->orwhere("purchase_author","like","%".$keyword."%");
                $query->orwhere("purchase_title","like","%".$keyword."%");
							}
						})
						->where(function($query) use ($status){
							if($status!="semua"){
								$query->orwhere("status",$status);
							}
						})
						->leftjoin(DB::raw("(SELECT SUM(item_price*item_quantity) as estimation, id_purchase as idp FROM tb_purchase_detail WHERE status NOT IN ('deleted') GROUP BY id_purchase)pd"),"pd.idp","=",$this->table.".".$this->primaryKey)
						->leftjoin(DB::raw("(SELECT first_name as author, id_user FROM tb_user)tb_user"),"tb_user.id_user","=",$this->table.".purchase_author")
						->leftjoin(DB::raw("(SELECT first_name as receiver_name, id_user FROM tb_user)tb_receiver"),"tb_receiver.id_user","=",$this->table.".receiver")
						->orderBy($short, $shortmode)
						->whereNotIn($this->table.".status",array("deleted"))
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword, $status){
		$data = PurchaseModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("purchase_code","like","%".$keyword."%");
								$query->orwhere("purchase_author","like","%".$keyword."%");
                $query->orwhere("purchase_title","like","%".$keyword."%");
							}
						})
						->where(function($query) use ($status){
							if($status!="semua"){
								$query->orwhere("status",$status);
							}
						})
						->whereNotIn($this->table.".status",array("deleted"))
						->count();
		return $data;
	}

  public function getListPendingPurchase(){
    $data = PurchaseModel::where("status","pending")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_item, id_purchase as idp FROM tb_purchase_detail GROUP BY id_purchase)tbd"),"tbd.idp","=",$this->table.".".$this->primaryKey)
            ->get();
		return $data;
  }

	public function getDetail($id) {
		$data = PurchaseModel::where($this->primaryKey,$id)->leftjoin(DB::raw("(SELECT first_name as receiver_name, id_user FROM tb_user)tb_receiver"),"tb_receiver.id_user","=",$this->table.".receiver")->first();
		return $data;
	}

	public function insertData($data){
		$result = PurchaseModel::create($data)->id_purchase;
		return $result;
  }

	public function itemoncart($tipe){
	    if($tipe=="material"){
		    return $this->hasMany(PurchaseDetailModel::class,"id_purchase")->where("status","ok")->where("item_type",$tipe)->join(DB::raw("(SELECT material_name, material_unit, id_material, material_code FROM tb_fremilt_material WHERE status='active')material"),"material.id_material","=","tb_purchase_detail.id_item");
	    }else{
	        return $this->hasMany(PurchaseDetailModel::class,"id_purchase")->where("status","ok")->where("item_type",$tipe)->join(DB::raw("(SELECT package_name, package_unit, id_material_package, package_code FROM tb_material_package WHERE status='active')package"),"package.id_material_package","=","tb_purchase_detail.id_item");
	    }
	}

	public function getListItem($id, $tipe=""){
		$data =  PurchaseModel::where("id_purchase",$id)->get();
		if($data->count()>0){
			return PurchaseModel::find($id)->itemoncart($tipe)->get();
		}else{
			return 0;
		}
	}
	
	

	public function check(){
		$data  = PurchaseModel::where("purchase_codes","like",$this->prestring)->orderBy($this->primaryKey,"DESC")->get();
		return $data;
	}

	public function createCode(){
		$data  = PurchaseModel::where("purchase_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

		if($data->count()>0){
			$data 			= $data->toArray()[0];
			$transnumb  = explode($this->prestring,$data['purchase_code']);
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

	public function updateData($data){
		$result = PurchaseModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return PurchaseModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		DB::select(DB::raw("DELETE FROM tb_department_previlege WHERE id_method IN (SELECT id_method FROM tb_previlege_method WHERE id_module = $id)"));
		DB::table("tb_previlege_method")->where("id_module",$id)->delete();
		DB::table("tb_module_department")->where("id_module",$id)->delete();
		$result = PurchaseModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
}
