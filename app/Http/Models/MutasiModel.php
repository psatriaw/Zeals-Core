<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MutasiModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'mutasi';
	protected $primaryKey   = 'id_mutasi';
	protected $fillable     = ['id_mutasi', 'id_reff','time_created','type','total','transaction','author','status','last_update'];

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = TransactionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("kode","like","%".$keyword."%");
								$query->orwhere("author_name","like","%".$keyword."%");
								$query->orwhere("total","like","%".$keyword."%");
								$query->orwhere("transaction","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("valid"))
						->select($this->table.".*","kode","author_name")
						->leftjoin(DB::raw("(SELECT first_name as author_name,id_user FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."user"),"user.id_user","=",$this->table.".id_author")
						->leftjoin(DB::raw("(SELECT cart_code,id_cart FROM ".DB::getTablePrefix()."cart)cart"),"cart.id_cart","=",$this->table.".id_reff")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = TransactionModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("transaction_code","like","%".$keyword."%");
								$query->orwhere("cart_code","like","%".$keyword."%");
								$query->orwhere("first_name","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("accepted","deleted","rejected","pending","pre-production","ready","queue","production","packed","shipted"))
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
						->join(DB::raw("(SELECT cart_code, id_cart,id_mitra,id_user FROM ".DB::getTablePrefix()."cart) AS ".DB::getTablePrefix()."cart"),"cart.id_cart","=",$this->table.".order_id")
						->leftjoin(DB::raw("(SELECT mitra_name, id_mitra, mitra_code FROM ".DB::getTablePrefix()."fremilt_mitra) AS ".DB::getTablePrefix()."fremilt_mitra"),"fremilt_mitra.id_mitra","=","cart.id_mitra")
						->leftjoin(DB::raw("(SELECT first_name, id_user FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."user"),"user.id_user","=","cart.id_user")
						->leftjoin(DB::raw("(SELECT first_name as admin_name, id_user FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."author"),"author.id_user","=",$this->table.".id_author")
						->first();
		return $data;
	}

	public function insertData($data){
		$result = TransactionModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = TransactionModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}


}
