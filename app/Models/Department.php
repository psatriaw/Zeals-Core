<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
	public $timestamps    = false;
	const CREATED_AT      = 'time_created';
	const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_department';
	protected $primaryKey = 'id_department';
	protected $fillable   = [
            'id_department',
            'name',
            'time_created',
            'last_update',
            'status',
            'default',
            'department_code'
          ];

  //Relation
  public function previlege(){
    return $this->hasMany(DepartmentPrevilege::class,'id_department','id_department');
  }
  public function user(){
    return $this->hasMany(User::class,'id_department','id_department');
  }

  //CRUD
  public function insertData($data)
  {
    $result = Department::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = Department::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = Department::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = Department::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

  public function getDefaultDepartment(){
		$data = Department::where("default","yes")->first();
		return $data->id_department;
	}

}
