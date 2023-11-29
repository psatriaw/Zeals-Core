<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Models\UserModel;
use App\Http\Models\SettingModel;
use App\Http\Models\DepartmentModel;
use App\Http\Models\CampaignTrackerModel;

class Tracker extends Controller
{
    public function __construct(){
      $this->setting_model = new SettingModel();
      $this->user_model = new UserModel();
      $this->department_model = new DepartmentModel();
      $this->campaign_tracker= new CampaignTrackerModel();
    }
    public function getTrackList($id_campaign){
      // $id_campaign = $request->id_campaign;
      $data = $this->campaign_tracker->getLogsByCampaign($id_campaign);

      $response = array(
        "status"  => "success",
        "id_campaign"=>$id_campaign,
        "data"   => $data
      );
      return response()->json($response,200);
    }
}
