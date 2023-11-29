<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleDepartment extends Model
{
	public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_module_department';
	protected $primaryKey = 'id_module_department';
	protected $fillable   = [
            'id_module_department',
            'id_module',
            'id_department'
          ];
  
  //Relation
  public function module(){
    $this->belongsTo(Module::class,'id_module','id_module');
  }
  public function previlege(){
    $this->belongsTo(Department::class,'id_department','id_department');
  }

  //CRUD
  public function insertData($data)
  {
    $result = ModuleDepartment::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = ModuleDepartment::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = ModuleDepartment::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = ModuleDepartment::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
