<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Namecards extends Model
{
  public $timestamps    = false;
  const CREATED_AT      = 'created_at';
  const UPDATED_AT      = 'update_at';

  //Schema
  protected $table      = 'namecards';
  protected $primaryKey = 'id';
  protected $fillable   = [
            'id',
            'full_name',
            'job_desk',
            'email',
            'phone',
            'address',
            'created_at',
            'update_at',
            'slug'
          ];

  //Relation

  //CRUD
  public function insertData($data)
  {
    $result = Namecards::create($data)->id;
    return $result;
  }
  public function findById($id)
  {
    $result = NamecardsModel::where($this->primaryKey, $id)->first();
    return $result;
  }
  public function updateData($data){
    $result = Namecards::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = Namecards::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query
}
