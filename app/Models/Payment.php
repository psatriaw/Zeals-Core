<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	public $timestamps    = false;
	const CREATED_AT      = 'time_created';
	const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_payment';
	protected $primaryKey = 'id_payment';
	protected $fillable   = [
            'id_payment',
            'time_created',
            'payment_time',
            'last_update',
            'total_amount',
            'total_pajak',
            'total_fee',
            'fee_type',
            'pajak_type',
            'bank_account',
            'bank_account_number',
            'bank_account_name',
            'trx_callback',
            'trx_status',
            'invoice_code',
            'description',
            'trx_sign',
            'trx_type',
            'trx_response',
            'va_number'
          ];
  protected $prestring 				= "UMPAY";
	protected $digittransaction = 10;

  //Relation

  //CRUD
  public function insertData($data)
  {
    $result = Payment::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = Payment::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = Payment::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = Payment::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
