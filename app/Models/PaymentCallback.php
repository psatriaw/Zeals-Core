<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCallback extends Model
{
  public $timestamps    = false;
  const CREATED_AT      = 'created_at';
  const UPDATED_AT      = 'updated_at';

	protected $table 	    = 'tb_payment_callback';
	protected $primaryKey = 'id';
	protected $fillable   = [
            'json_callback',
            'provider',
            'created_at',
            'updated_at'
          ];

  //Relation

  //CRUD
  public function insertData($data)
  {
    $result = PaymentCallback::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = PaymentCallback::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = PaymentCallback::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = PaymentCallback::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
