<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRestriction extends Model
{
  public $timestamps    = false;

  //Schema
	protected $table 	    = 'tb_user_restricted';
	protected $primaryKey = 'id_restriction';
	protected $fillable   = [
            'id_restriction',
            'id_method',
            'type_user',
            'id_user'
          ];

  //Relation
  public function user(){
    $this->belongsTo(UserRestriction::class,'id_user','id_user');
  }

  //CRUD
  public function insertData($data)
  {
    $result = UserRestriction::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = UserRestriction::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = UserRestriction::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = UserRestriction::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
