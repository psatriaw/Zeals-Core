<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignProgram extends Model
{
  public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
  protected $table      = 'tb_campaign_program';
  protected $primaryKey = 'id_program';
  protected $fillable   = [
            'id_program',
            'id_campaign',
            'type_program',
            'status',
            'time_created',
            'last_update',
            'commission',
            'fee',
            'total_item',
            'reff_code',
            'custom_link',
            'type'
          ];

  //Relation
  public function campaign(){
    return $this->belongsTo(Campaign::class,'id_campaign','id_campaign');
  }

  //CRUD
  public function insertData($data)
	{
		$result = CampaignProgram::create($data);
		return $result;
	}
	public function findById($id)
	{
		$result = CampaignProgram::find($id);
		return $result;
	}
	public function updateData($data)
	{
		$result = CampaignProgram::where($this->primaryKey, $data[$this->primaryKey])->update($data);
		return $result;
	}
  public function removeData($id)
  {
    $result = CampaignProgram::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }
  
  //Custom Query

}
