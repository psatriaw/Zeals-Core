<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentPrevilege extends Model
{
	public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_department_previlege';
	protected $primaryKey = 'id_previlege';
	protected $fillable   = [
            'id_previlege',
            'id_method',
            'description',
            'id_department'
          ];

  //Relation
  public function department(){
    $this->belongsTo(Department::class,'id_department','id_department');
  }
  public function method(){
    $this->hasMany(Method::class,'id_method','id_method');
  }

  //CRUD
  public function insertData($data)
  {
    $result = DepartmentPrevilege::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = DepartmentPrevilege::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = DepartmentPrevilege::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = DepartmentPrevilege::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query


}
