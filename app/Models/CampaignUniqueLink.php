<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignUniqueLink extends Model
{
	public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_campaign_unique_link';
	protected $primaryKey = 'id_unique_link';
	protected $fillable   = [
            'id_unique_link',
            'id_campaign',
            'id_user',
            'time_created',
            'last_update',
            'unique_link'
          ];

  //Relation
  public function campaign(){
    $this->belongsTo(Campaign::class,'id_campaign','id_campaign');
  }
  public function user(){
    $this->belongsTo(User::class,'id_user','id_user');
  }

  public function reach(){
		return $this->hasMany(CampaignTracker::class, 'unique_link', 'unique_link')->where("type_conversion","initial");
	}

	public function visit(){
		return $this->hasMany(CampaignTracker::class, 'unique_link', 'unique_link')->where("type_conversion","visit");
	}

	public function interest(){
		return $this->hasMany(CampaignTracker::class, 'unique_link', 'unique_link')->where("type_conversion","read");
	}

	public function action(){
		return $this->hasMany(CampaignTracker::class, 'unique_link', 'unique_link')->where("type_conversion","action");
	}

	public function acquisition(){
		return $this->hasMany(CampaignTracker::class, 'unique_link', 'unique_link')->where("type_conversion","acquisition");
	}

	public function last15logs(){
		return $this->hasMany(CampaignTracker::class, 'unique_link', 'unique_link')->orderBy("time_created","DESC")->limit(8);
	}

	public function estimation(){
		return $this->hasMany(CampaignTracker::class, 'unique_link', 'unique_link')->where("status","active");
	}

	public function earning(){
		return $this->hasMany(CampaignTracker::class, 'unique_link', 'unique_link')->where("status","paid");
	}

  //CRUD
  public function insertData($data)
  {
    $result = CampaignUniqueLink::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = CampaignUniqueLink::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = CampaignUniqueLink::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = CampaignUniqueLink::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }
  
  //Custom Query
	public function getLinkTest($id_campaign){
		$data =  CampaignJoinModel::where("id_user",0)->where("id_campaign",$id_campaign)->first();
		if($data){
			return $data->unique_link;
		}else{
			//create unique link test
			$campaigntestlink = array(
				"time_created"	=> time(),
				"last_update"		=> time(),
				"id_user"				=> 0,
				"id_campaign"		=> $id_campaign,
				"unique_link"		=> $this->createLink()
			);
			$this->insertData($campaigntestlink);
			return $this->getLinkTest($id_campaign);
			//create unique link test
		}
	}
	public function createLink(){
		$ketemu = true;
		$html 	= "";
		do{
			$html 	= "";
			$abjad 	= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$long 	= strlen($abjad);

			for ($i=0; $i < 8; $i++) {
				$html = @$html.substr($abjad,rand(0,$long-1),1);
			}

			$check = CampaignUniqueLink::where("unique_link",$html)->first();
			if($check==1){
				$ketemu = true;
			}
		}while($ketemu==false);

		return $html;
	}
}
