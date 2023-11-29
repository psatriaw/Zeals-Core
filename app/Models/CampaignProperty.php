<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignProperty extends Model
{
	public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_campaign_property';
	protected $primaryKey = 'id_property';
	protected $fillable   = [
            'id_property',
            'id_campaign',
            'property_type',
            'time_created',
            'last_update',
            'status',
            'value'
          ];

  //Relation
  public function runningcampaign(){
    return $this->belongsTo(Campaign::class,'id_campaign','id_campaign');
  }

  public function campaign(){
    return $this->belongsTo(Campaign::class,'id_campaign','id_campaign');
  }

  //CRUD
  public function insertData($data)
	{
		$result = CampaignProperty::create($data);
		return $result;
	}
	public function findById($id)
	{
		$result = CampaignProperty::find($id);
		return $result;
	}
	public function updateData($data)
	{
		$result = CampaignProperty::where($this->primaryKey, $data[$this->primaryKey])->update($data);
		return $result;
	}
  public function removeData($id)
  {
    $result = CampaignProperty::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }
  
  //Custom Query

}
