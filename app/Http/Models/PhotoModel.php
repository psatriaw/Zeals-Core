<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PhotoModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_photo';
	protected $primaryKey   = 'id_photo';
	protected $fillable     = ['id_photo', 'path','id_reff','time_created','author','status','thumbnail'];

	public function getDetail($id) {
		$data = PhotoModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = PhotoModel::create($data)->id_photo;
		return $result;
  }

	public function updateData($data){
		$result = PhotoModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id){
		$result = PhotoModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
}
