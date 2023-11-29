<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreferrences extends Model
{
  public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
  protected $table      = 'tb_user_preferences';
  protected $primaryKey = 'id_preferences';
  protected $fillable   = [
            'id_preferences',
            'time_created',
            'last_update',
            'id_user',
            'id_sektor_industri',
            'status'
          ];

  //Relation
  public function user(){
    $this->belongsTo(UserRestriction::class,'id_user','id_user');
  }

  //CRUD
  public function insertData($data)
  {
    $result = UserPreferrences::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = UserPreferrences::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = UserPreferrences::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = UserPreferrences::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
