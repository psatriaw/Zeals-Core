<?php

namespace App\http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PaymentCallbackModel extends Model
{
  public $timestamps      = false;
  const CREATED_AT        = 'created_at';
  const UPDATED_AT        = 'updated_at';

	protected $table 	      = 'tb_payment_callback';
	protected $primaryKey   = 'id';
	protected $fillable     = ['json_callback','provider','created_at','updated_at'];

  public function insertData($data){
    return PaymentCallbackModel::create($data)->id;
  }

  public function updateData($item){
    return PaymentCallbackModel::find($item['id'])->update($item);
  }
}
