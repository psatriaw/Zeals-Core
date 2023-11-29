<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AddressModel extends Model {
	public $timestamps      = false;
	const CREATED_AT        = 'time_created';
	const UPDATED_AT        = 'last_update';

	protected $table 	    = 'shipping_address';
	protected $primaryKey   = 'id_shipping_address';
	protected $fillable     = ['id_shipping_address', 'address_1','address_2','town','country','post_code','id_user','time_created','last_update'];

	public function insertData($data){
		$result = AddressModel::create($data)->id_shipping_address;
		return $result;
    }

	public function updateData($data){
		$result = AddressModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getDetail($id) {
		$data = AddressModel::find($id);
		return $data;
	}

	public function getDetailAddressByUser($id_user){
		$data = AddressModel::where("id_user",$id_user)->limit(1)->orderBy("id_shipping_address","DESC")->first();
		return $data;
	}

	public function getdata($id_user){
		$data = AddressModel::where("id_user",$id_user)->orderBy("id_shipping_address","DESC")->get();
		return $data;
	}

	public function removeData($id_shipping_address){
		$data = AddressModel::where("id_shipping_address",$id_shipping_address)->delete();
		return $data;
	}
}
