<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignComponent extends Model
{
  public $timestamps    = false;

  //Schema
  protected $table      = 'tb_campaign_component';
  protected $primaryKey = 'id_component';
  protected $fillable   = [
            'id_component',
            'id_campaign',
            'input_type',
            'input_source',
            'field_name',
            'rules',
            'status',
            'is_deleted'
          ];

  //Relation
  public function campaign(){
    $this->belongsTo(Campaign::class,'id_campaign','id_campaign');
  }
  
  //CRUD
  public function insertData($data)
	{
		$result = CampaignComponent::create($data);
		return $result;
	}
	public function findById($id)
	{
		$result = CampaignComponent::find($id);
		return $result;
	}
	public function updateData($data)
	{
		$result = CampaignComponent::where($this->primaryKey, $data[$this->primaryKey])->update($data);
		return $result;
	}
  public function removeData($id)
  {
    $result = CampaignComponent::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }
  
  //Custom Query
  public function getListComponent($id_campaign){
    return CampaignComponentModel::select('id_component','input_type','input_source','field_name','rules','status')
    ->where('is_deleted','=',0)
    ->where('id_campaign','=',$id_campaign)
    ->get();
  }
}
