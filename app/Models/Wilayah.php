<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
  public $timestamps    = false;
  const CREATED_AT      = 'date_created';
  const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_wilayah';
	protected $primaryKey = 'id_wilayah';
	protected $fillable   = [
            'id_wilayah',
            'kodeprov',
            'namaprov',
            'kodekab',
            'namakab',
            'map_code'
          ];

  //Relation
  public function user(){
    $this->hasMany(User::class,'id_wilayah','id_wilayah');
  }

  //CRUD
  public function insertData($data)
  {
    $result = Wilayah::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = Wilayah::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = Wilayah::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = Wilayah::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}

