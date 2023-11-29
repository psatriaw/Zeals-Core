<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductionCompletionModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_fremilt_production';
	protected $primaryKey   = 'id_production';
	protected $fillable     = ['id_production', 'author','time_created','last_update','admin','status','production_quantity','production_code','production_type','id_cart_detail','id_product','production_completion'];
	protected $prestring 				= "PRO";
	protected $digittransaction = 8;

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = ProductionCompletionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("transaction_code","like","%".$keyword."%");
								$query->orwhere("cart_code","like","%".$keyword."%");
								$query->orwhere("admin_name","like","%".$keyword."%");
							}
						})
            ->where("transaction_status","terbayar")
						->wherein($this->table.".status",array("queue","production","packed","shipted"))
						->select($this->table.".*","admin_name","cart_code","id_cart")
						->leftjoin(DB::raw("(SELECT first_name as admin_name,id_user FROM tb_user)user"),"user.id_user","=",$this->table.".id_author")
						->leftjoin(DB::raw("(SELECT cart_code,id_cart FROM tb_cart)cart"),"cart.id_cart","=",$this->table.".id_cart_detail")
						->where("type","purchase")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = ProductionCompletionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("transaction_code","like","%".$keyword."%");
								$query->orwhere("cart_code","like","%".$keyword."%");
								$query->orwhere("first_name","like","%".$keyword."%");
							}
						})
            ->where("transaction_status","terbayar")
						->wherein($this->table.".status",array("pre-production","queue","production","packed","shipted"))
						->count();
		return $data;
	}

	public function createCode(){
		$data  = ProductionCompletionModel::where("production_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

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

	public function getDetail($id) {
		$data = ProductionCompletionModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = ProductionCompletionModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = ProductionCompletionModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}


}
