<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;

use App\Http\Models\CampaignModel;
use Illuminate\Http\Request;

class Campaign extends Controller
{
    protected $campaign_model;

    function getcampaignresume(Request $request){
      $id_penerbit = $request->id_penerbit;
      $this->campaign_model = new CampaignModel();

      $response = array(
        "status"  => "success",
        "total"   => number_format($this->campaign_model->getResumeCampaign($request->keyword,$id_penerbit),0,",","."),
        "percent" => "",
        "unit"    => $request->unit
      );
      return response()->json($response,200);
    }
}
