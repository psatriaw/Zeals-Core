<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class LikeModel extends Model {
	public $timestamps      = false;
	const CREATED_AT        = 'time_created';
	const UPDATED_AT        = 'last_update';

	protected $table 	      	= 'tb_pra_like';
	protected $primaryKey   	= 'id_like';
	protected $fillable     	= ['id_like', 'id_user','time_created','last_update','id_penerbit','status'];
	protected $prestring 		= "ORD";
	protected $digittransaction = 10;

	public function getTotalLike(){
		$data = LikeModel::count();
		return $data;
	}

	public function getTotalLikePermonth($year, $month){
		$open 	= strtotime($year."-".$month."-01 00:00:00");
		$close 	= strtotime($year."-".$month."-31 23:59:59");
		$data 	= LikeModel::whereBetween('time_created', [$open, $close])->count();
		return $data;
	}

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = LikeModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("caption","like","%".$keyword."%");
								$query->orwhere("first_name","like","%".$keyword."%");
							}
						})
						->select($this->table.".*","caption","photo","first_name")
						->leftjoin(DB::raw("(SELECT caption, photo, id_post FROM ".DB::getTablePrefix()."post) AS ".DB::getTablePrefix()."tbp"),"tbp.id_post","=",$this->table.".id_post")
						->leftjoin(DB::raw("(SELECT first_name,id_user FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."user"),"user.id_user","=",$this->table.".author")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = LikeModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("caption","like","%".$keyword."%");
								$query->orwhere("first_name","like","%".$keyword."%");
							}
						})
            ->leftjoin(DB::raw("(SELECT caption, photo, id_post FROM ".DB::getTablePrefix()."post) AS ".DB::getTablePrefix()."tbp"),"tbp.id_post","=",$this->table.".id_post")
						->leftjoin(DB::raw("(SELECT first_name,id_user FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."user"),"user.id_user","=",$this->table.".author")
						->count();
		return $data;
	}



	public function getDetailPurchase($id_cart){
		$order['detail']			= $this->getDetail($id_cart);
		$order['items']				= $this->getItems($id_cart);
		$order['time_created']		= date("F jS, Y h:i A",$order['detail']->time_created);
		return $order;
	}

	public function getdatapurchase($id_user,$base){
		$data = LikeModel::where("id_user",$id_user)->orderBy("id_cart","DESC")->get();
		if($data->count()){
			foreach ($data as $key => $value) {
				$order['data']	= $value;
				$order['items']	= $this->getItems($value->id_cart);
				$order['time_created']	= date("F jS, Y h:i A",$value->time_created);
				$orders[] = $order;
			}

			return $orders;
		}else{
			return 0;
		}
	}

	public function createCode(){
		$data  = LikeModel::where("cart_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

		if($data->count()>0){
			$data 			= $data->toArray()[0];
			$transnumb  = explode($this->prestring,$data['cart_code']);
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
		$data = LikeModel::find($id);
		return $data;
	}

	public function getTotalTransaction($id_cart){
		$data = LikeModel::where($this->table.".".$this->primaryKey,$id_cart)
						->join(DB::raw("(SELECT SUM((quantity*item_price)-item_discount) as total,id_cart FROM ".DB::getTablePrefix()."cart_detail GROUP BY id_cart) AS ".DB::getTablePrefix()."tcd"),"tcd.id_cart","=",$this->table.".id_cart")
						->get();
		if($data->count()){
			$data = $data->first();
			return $data->total;
		}else{
			return 0;
		}
	}

	public function items(){
		return $this->hasMany(CartDetailModel::class,$this->primaryKey)
						->join("product","product.id_product","=","cart_detail.id_product")
						->join("brand","brand.id_brand","=","product.id_brand")
						->where("cart_detail.status","active");
	}

	public function getItems($id){
		$data = LikeModel::find($id)->items()->get();
		if($data->count()){
			return $data;
		}else{
			return 0;
		}
	}

	public function itemsmrp(){
		return $this->hasMany(CartDetailModel::class,$this->primaryKey)
						->join("product","product.id_product","=","cart_detail.id_product")
						->leftjoin(DB::raw("(SELECT GROUP_CONCAT(CONCAT(qty,'_',material_price,'_',quantity,'_', material_code, '_', material_name,'_',".DB::getTablePrefix()."fremilt_mrp.id_material,'_',material_unit) SEPARATOR '-') as mrp_item, ".DB::getTablePrefix()."fremilt_mrp.id_product FROM ".DB::getTablePrefix()."fremilt_mrp, ".DB::getTablePrefix()."fremilt_material WHERE ".DB::getTablePrefix()."fremilt_mrp.status = 'active' AND ".DB::getTablePrefix()."fremilt_mrp.id_material = ".DB::getTablePrefix()."fremilt_material.id_material AND ".DB::getTablePrefix()."fremilt_material.status = 'active' GROUP BY id_product) AS ".DB::getTablePrefix()."mrp"),"mrp.id_product","=","cart_detail.id_product")
						->leftjoin(DB::raw("(SELECT production_completion, last_update as completion_terakhir, id_cart_detail as icd, id_production FROM ".DB::getTablePrefix()."fremilt_production) AS ".DB::getTablePrefix()."tfp"),"tfp.icd","=","cart_detail.id_cart_detail")
						->where("cart_detail.status","active")
						->orderBy("cart_detail.id_cart_detail","ASC");
	}

	public function getItemsMRP($id){
		$data = LikeModel::find($id)->itemsmrp()->get();
		if($data->count()){
			return $data;
		}else{
			return 0;
		}
	}

	public function getPeopleWholikes($id_post){
		$data = LikeModel::where("id_post",$id_post)
						->leftjoin(DB::raw("(SELECT first_name,id_user,avatar,email FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."user"),"user.id_user","=",$this->table.".author")
						->get();
		return $data;
	}

	public function checkRecord($data){
		$result = LikeModel::where("id_user",$data['id_user'])->where("id_penerbit",$data['id_penerbit'])->count();
		return $result;
	}

	public function removeData($id){
		$result = LikeModel::find($id)->delete();
		return $result;
  }

  public function insertData($data){
		$result = LikeModel::create($data)->id_like;
		return $result;
  }

	public function updateData($data){
		$result = LikeModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}


}
