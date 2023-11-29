<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Method extends Model
{
	public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_previlege_method';
	protected $primaryKey = 'id_method';
	protected $fillable   = [
            'id_method',
            'method',
            'description',
            'id_module'
          ];

  //Relation
  public function module(){
    $this->belongsTo(Module::class,'id_department','id_department');
  }
  public function previlege(){
    $this->hasMany(DepartmentPrevilege::class,'id_method','id_method');
  }

  //CRUD
  public function insertData($data)
  {
    $result = Method::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = Method::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = Method::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = Method::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}