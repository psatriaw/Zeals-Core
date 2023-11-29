<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
  public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
  protected $table 	    = 'tb_campaign';
  protected $primaryKey = 'id_campaign';
  protected $fillable   = [
            'id_campaign',
            'id_penerbit',
            'time_created',
            'last_update',
            'campaign_title',
            'campaign_description',
            'campaign_instruction',
            'campaign_do_n_dont',
            'status',
            'start_date',
            'end_date',
            'budget',
            'max_commission',
            'photos',
            'campaign_link',
            'landing_url',
            'campaign_type',
            'tipe_url',
            'campaign_internal',
            'dislcaimer'
          ];
  protected $prestring  = "CMP";
  protected $digittransaction = 8;

  //Relation
  public function penerbit(){
    $this->belongsTo(Penerbit::class,'id_penerbit','id_penerbit');
  }
	// public function penerbitReport(){
	// 	$this->hasMany(PenerbitReport::class, 'id_campaign', 'id_campaign');
	// }

  public function component(){
		return $this->hasMany(CampaingComponent::class, 'id_campaign', 'id_campaign');
	}
	// public function product(){
	// 	$this->hasMany(BELUM_ADA_::class, 'id_campaign', 'id_campaign');
	// }
	public function outlet(){  //outlets
		return $this->hasMany(Outlet::class, 'id_campaign', 'id_campaign');
	}

	public function program(){ //programs
		return $this->hasMany(CampaignProgram::class, 'id_campaign', 'id_campaign')->where("status","active");
	}

	public function property(){
		return $this->hasMany(CampaignProperty::class, 'id_campaign', 'id_campaign');
	}

	public function trackers(){
		return $this->hasMany(CampaignTracker::class, 'id_campaign', 'id_campaign');
	}

	public function joincampaign(){
		$user    = auth('sanctum')->user();
		return $this->hasOne(CampaignUniqueLink::class, 'id_campaign', 'id_campaign')->where("id_user",$user->id_user);
	}

	public function reach(){
		return $this->hasMany(CampaignTracker::class, 'id_campaign', 'id_campaign')->where("type_conversion","initial");
	}

	public function visit(){
		return $this->hasMany(CampaignTracker::class, 'id_campaign', 'id_campaign')->where("type_conversion","visit");
	}

	public function interest(){
		return $this->hasMany(CampaignTracker::class, 'id_campaign', 'id_campaign')->where("type_conversion","read");
	}

	public function action(){
		return $this->hasMany(CampaignTracker::class, 'id_campaign', 'id_campaign')->where("type_conversion","action");
	}

	public function acquisition(){
		return $this->hasMany(CampaignTracker::class, 'id_campaign', 'id_campaign')->where("type_conversion","acquisition");
	}

	public function last15logs(){
		return $this->hasMany(CampaignTracker::class, 'id_campaign', 'id_campaign')->orderBy("time_created","DESC")->limit(8);
	}

	public function estimation(){
		return $this->hasMany(CampaignTracker::class, 'id_campaign', 'id_campaign')->where("status","active");
	}

	public function earning(){
		return $this->hasMany(CampaignTracker::class, 'id_campaign', 'id_campaign')->where("status","paid");
	}

  	public function uniqueLink(){
		return $this->hasMany(CampaignUniqueLink::class, 'id_campaign', 'id_campaign');
	}
	
  	public function voucher(){
		return $this->hasMany(Voucher::class, 'id_campaign', 'id_campaign');
	}

  //CRUD
  public function insertData($data)
	{
		$result = Campaign::create($data);
		return $result;
	}
	public function findById($id)
	{
		$result = Campaign::find($id);
		return $result;
	}
	public function updateData($data)
	{
		$result = Campaign::where($this->primaryKey, $data[$this->primaryKey])->update($data);
		return $result;
	}
  public function removeData($id)
  {
    $result = Campaign::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query
	public function createCode(){
		$data  = Campaign::where("campaign_link","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

		if($data->count()>0){
			$data 			= $data->toArray()[0];
			$transnumb  = explode($this->prestring,$data['campaign_link']);
			$number     = intval($transnumb[1]);
			$nextnumber = $number+1;
			return $this->formattransaction($this->prestring,$nextnumber);
		}else{
			return $this->formattransaction($this->prestring,1);
		}
	}

	public function formattransaction($prestring, $number){
		$lengthofnumber = strlen(strval($number));
		$lengthrest     = $this->digittransaction - $lengthofnumber;
		if($lengthrest>0){
			$transnumb = strval($number);
			for($i=0;$i<$lengthrest;$i++){
				$transnumb = "0".$transnumb;
			}
			$transnumb = $prestring.$transnumb;
			return $transnumb;
		}else{
			return $prestring.$number;
		}
	}
}
