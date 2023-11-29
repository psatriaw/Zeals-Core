<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XenditLog extends Model
{
	public $timestamps    = false;
	const CREATED_AT      = 'time_created';
	const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_xendit_log';
	protected $primaryKey = 'id_log';
	protected $fillable   = [
            'id_log',
            'time_created',
            'content'
          ];

  protected $prestring 				= "PAYOUT";
	protected $digittransaction = 10;
  
  //Relation

  //CRUD
  public function insertData($data)
  {
    $result = XenditLog::create($data);
    return $result;
  }

  public function findById($id)
  {
    $result = XenditLog::find($id);
    return $result;
  }

  public function updateData($data)
  {
    $result = XenditLog::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  
  public function removeData($id)
  {
    $result = XenditLog::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
