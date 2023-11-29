<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignTracker extends Model
{
	public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_campaign_tracker';
	protected $primaryKey = 'id_tracker';
	protected $fillable   = [
            'id_tracker',
            'unique_link',
            'id_campaign',
            'status',
            'type_conversion',
            'time_created',
            'last_update',
            'time_created',
            'id_user',
            'ip',
            'browser',
            'date',
            'commission',
            'fee',
            'id_program',
            'referrer',
            'os',
            'device_info',
            'fee',
            'encrypted_code',
            'callback_url',
            'protocol',
            'domain',
            'info',
            'city',
            'region_code',
            'connection_info',
            'country'
          ];

  //Relation
  public function campaign(){
    $this->belongsTo(Campaign::class,'id_campaign','id_campaign');
  }

  // public function program(){
  //   $this->hasOne(campaignProgram::class);
  // }

  //CRUD
  public function insertData($data)
  {
    $result = CampaignTracker::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = CampaignTracker::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = CampaignTracker::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = CampaignTracker::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }
  
  //Custom Query
	public function getTotalAffiliatorData($id_campaign){
		$data  = CampaignTrackerModel::where("id_campaign",$id_campaign)
						->groupBy("id_user")
						->get();
		return $data;
	}
	public function getDataTrackingByCity(){
		$data = CampaignTrackerModel::select(DB::raw("COUNT(*) as total_data_visit"),"city")
						->where("status","active")
						->groupBy("city")
						->orderBy("total_data_visit","DESC")
						->skip(0)
						->limit(25)
						->get();

		return $data;
	}
}
