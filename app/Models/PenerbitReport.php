<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerbitReport extends Model
{
  public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  protected $table 	    = 'tb_penerbit_report';
  protected $primaryKey = 'id_report';
  protected $fillable   = [
            'id_report',
            'id_campaign',
            'id_penerbit',
            'report_date',
            'status',
            'time_created',
            'last_update',
            'report_code',
            'file_path',
            'catatan',
            'report_month',
            'report_year',
            'id_user'
          ];
  protected $prestring 	    	= "RP";
  protected $digittransaction = 8;

  //Relation
  public function campaign(){
    $this->belongsTo(Campaign::class,'id_campaign','id_campaign');
  }
  public function penerbit(){
    $this->belongsTo(Penerbit::class,'id_penerbit','id_penerbit');
  }

  //CRUD
  public function insertData($data)
  {
    $result = PenerbitReport::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = PenerbitReport::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = PenerbitReport::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = PenerbitReport::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
