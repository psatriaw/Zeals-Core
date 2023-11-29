<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class NamecardsModel extends Model
{

  public $timestamps      = false;
  const CREATED_AT        = 'created_at';
  const UPDATED_AT        = 'update_at';

  protected $table        = 'namecards';
  protected $primaryKey   = 'id';
  protected $fillable     = ['id','full_name','job_desk','email','phone','address','created_at','update_at','slug'];

  
  public function insertData($data)
  {
      $result = NamecardsModel::create($data)->id;
      return $result;
  }

  public function getDataAll(){
    return NamecardsModel::get();
  }
  public function findById($id)
  {
      $data = NamecardsModel::where($this->primaryKey, $id)->first();
      return $data;
  }

  public function updateData($data){
      $result = NamecardsModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
      return $result;
  }
  public function removeData($id)
  {
      $result = NamecardsModel::where($this->primaryKey, '=', $id)->delete();
      return $result;
  }

}
