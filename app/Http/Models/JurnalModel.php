<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class JurnalModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_jurnal';
	protected $primaryKey   = 'id_jurnal';
	protected $fillable     = ['id_jurnal', 'id_reff','time_created','type','total','transaction','author','status','last_update','id_category_jurnal','reff_code'];

	public function getData($str, $limit, $keyword, $short, $shortmode, $tahun, $bulan){
		$data = JurnalModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("jurnal_code","like","%".$keyword."%");
								$query->orwhere("author_name","like","%".$keyword."%");
								$query->orwhere("total","like","%".$keyword."%");
								$query->orwhere("transaction","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("valid"))
						->select($this->table.".*","author_name","tb_category_jurnal.*")
						->leftjoin(DB::raw("(SELECT first_name as author_name,id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
						->leftjoin(DB::raw("(SELECT cart_code,id_cart FROM tb_cart)cart"),"cart.id_cart","=",$this->table.".id_reff")
						->leftjoin(DB::raw("(SELECT jurnal_name,id_category_jurnal,jurnal_code, description as jurnal_description FROM tb_category_jurnal)tb_category_jurnal"),"tb_category_jurnal.id_category_jurnal","=",$this->table.".id_category_jurnal")
						->orderBy($short, $shortmode)
						->whereBetween('time_created',[strtotime($tahun."-".$bulan."-00 00:00:00"), strtotime($tahun.'-'.$bulan."-31 23:59:59")])
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword, $tahun, $bulan){
		$data = JurnalModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("jurnal_code","like","%".$keyword."%");
								$query->orwhere("author_name","like","%".$keyword."%");
								$query->orwhere("total","like","%".$keyword."%");
								$query->orwhere("transaction","like","%".$keyword."%");
							}
						})
						->whereBetween('time_created',[strtotime($tahun."-".$bulan."-00 00:00:00"), strtotime($tahun.'-'.$bulan."-31 23:59:59")])
						->wherein($this->table.".status",array("valid"))
						->select($this->table.".*","author_name","tb_category_jurnal.*")
						->leftjoin(DB::raw("(SELECT first_name as author_name,id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
						->leftjoin(DB::raw("(SELECT cart_code,id_cart FROM tb_cart)cart"),"cart.id_cart","=",$this->table.".id_reff")
						->leftjoin(DB::raw("(SELECT jurnal_name,id_category_jurnal,jurnal_code, description as jurnal_description FROM tb_category_jurnal)tb_category_jurnal"),"tb_category_jurnal.id_category_jurnal","=",$this->table.".id_category_jurnal")
						->count();
		return $data;
	}

	public function createCode(){
		$data  = JurnalModel::where("transaction_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

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
		$data = JurnalModel::where($this->primaryKey,$id)
						->join(DB::raw("(SELECT cart_code, id_cart,id_mitra,id_user FROM tb_cart)tb_cart"),"tb_cart.id_cart","=",$this->table.".order_id")
						->leftjoin(DB::raw("(SELECT mitra_name, id_mitra, mitra_code FROM tb_fremilt_mitra)tb_fremilt_mitra"),"tb_fremilt_mitra.id_mitra","=","tb_cart.id_mitra")
						->leftjoin(DB::raw("(SELECT first_name, id_user FROM tb_user)tb_user"),"tb_user.id_user","=","tb_cart.id_user")
						->leftjoin(DB::raw("(SELECT first_name as admin_name, id_user FROM tb_user)author"),"author.id_user","=",$this->table.".id_author")
						->first();
		return $data;
	}

	public function insertData($data){
		$result = JurnalModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = JurnalModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}


}
