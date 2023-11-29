<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

  //Schema
	protected $table 	      = 'tb_outlet';
	protected $primaryKey   = 'id_outlet';
	protected $fillable     = [
            'id_outlet',
            'id_campaign',
            'outlet_name',
            'outlet_address',
            'status',
            'outlet_phone',
            'time_created',
            'last_update',
            'max_redemption',
            'max_redemption_per_day',
            'outlet_code'
          ];

  //Relation
  public function campaign(){
    $this->belongsTo(Campaign::class,'id_campaign','id_campaign');
  }

  //CRUD
  public function insertData($data)
  {
    $result = Outlet::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = Outlet::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = Outlet::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = Outlet::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
