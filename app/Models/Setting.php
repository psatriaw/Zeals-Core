<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_setting';
	protected $primaryKey = 'id_setting';
	protected $fillable   = [
            'id_setting',
            'code_setting',
            'setting_value',
            'last_update',
            'description',
            'id_order'
          ];

  //Relation

  //CRUD
  public function insertData($data)
  {
    $result = Setting::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = Setting::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = Setting::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = Setting::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
