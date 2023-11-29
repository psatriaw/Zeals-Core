<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
	public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_module';
	protected $primaryKey = 'id_module';
	protected $fillable   = [
            'id_module',
            'module_name',
            'time_created',
            'last_update'
          ];
  
  //Relation
  public function method(){
    $this->hasMany(Method::class,'id_module','id_module');
  }

  //CRUD
  public function insertData($data)
  {
    $result = Module::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = Module::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = Module::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = Module::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
