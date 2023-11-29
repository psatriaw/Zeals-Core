<?php

namespace App\Http\Models\backend\member\distributor;

use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShopModel extends Model
{
    public $timestamps = false;
	const CREATED_AT ='date_created';
	
	protected $table 	= 'shop';
	protected $primaryKey = 'id_shop';
	protected $fillable = ['shop_name', 'description', 'id_owner', 'status', 'permalink', 'last_update', 'shop_level'];
	
	public function checkShop($id_member){
		$shop = DB::table($this->table)
					->where('id_owner', $id_member)
					//->where('status', 'aktif')
					->count();
		if($shop > 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function getDetailShop($id){
		$shop = DB::table($this->table)
					->where('id_shop', $id)
					->first();
		return $shop;
	}
	
	public function getDetailShopByOwner($id_member){
		$shop = DB::table($this->table)
					->where('id_owner', $id_member)
					->first();
		return $shop;
	}
	
	public function insertData($data){
		$result = DB::table($this->table)->insert($data);
		return $result;
	}
	
	public function updateData($data){
		$result = DB::table($this->table)
						->where('id_owner', $data['id_owner'])
						->update($data);
		return $result;
	}
	
	public function getDetail($id) {
		$data = ShopModel::where($this->primaryKey, $id)
					->first();
					
		return $data;
	}
	
	public function getByIdOwner($id_owner) {
		$data = ShopModel::select('*')->where('id_owner', '=', $id_owner)->get();
						
		return $data;
	}
}
