<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
	public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_job';
	protected $primaryKey = 'id_job';
	protected $fillable   = [
            'id_job',
            'job_name',
            'status',
            'time_created',
            'last_update'
          ];

  //Relation
  public function user(){
    return $this->hasMany(User::class,'id_job','id_job');
  }

  //CRUD
  public function insertData($data)
  {
    $result = Job::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = Job::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = Job::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = Job::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
