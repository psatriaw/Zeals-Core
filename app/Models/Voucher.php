<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
	public $timestamps    = false;
	const CREATED_AT      = 'date_created';
	const UPDATED_AT      = 'last_update';

	protected $table 	    = 'tb_voucher';
	protected $primaryKey = 'id_voucher';
	protected $fillable   = [
            'id_voucher',
            'status',
            'id_campaign',
            'id_tracker',
            'time_created',
            'status',
            'last_update',
            'id_outlet_usage',
            'time_usage',
            'voucher_code',
            'optin_name',
            'optin_email',
            'optin_phone',
            'optin_address',
            'additional_1',
            'additional_2',
            'disclaimer',
            'optin_source',
            'optin_other_source',
            'optin_dob',
            'optin_institution',
            'optin_institution_name',
            'optin_institution_division'
          ];
  
  //Relation
  public function campaign(){
    $this->belongsTo(Campaign::class,'id_campaign','id_campaign');
  }

  public function outlet(){
    $this->belongsTo(Voucher::class);
  }

  //CRUD
  public function insertData($data)
  {
    $result = Voucher::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = Voucher::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = Voucher::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = Voucher::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
