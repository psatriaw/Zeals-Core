<?php

namespace App\Http\Controllers;

//===============================
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\IGWHelper;

use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
//===============================

class LocationController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();
  }

  public function searchaddress(Request $request){
    $address 	= $request->input("address");

    //$initial = str_replace(" ", "+", $address);
    $initial = urlencode($address);
    $APIKEY = $this->setting_model->getSettingVal("google_api_key");

    $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$initial&sensor=false&components=country:ID&key=" . $APIKEY);
    $json = json_decode($json);

    $latitude   = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $longitude  = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

    foreach($json->{'results'}[0]->{'address_components'} as $key=>$val){
      $isada = "";
      foreach($val->types as $k=>$v){
        if($v == "administrative_area_level_4"){
          $kelurahan = $val->short_name;
        }

        if($v == "administrative_area_level_3"){
          $kecamatan = $val->short_name;
        }

        if($v == "administrative_area_level_2"){
          $kota = $val->short_name;
        }
      }
    }

    $result = array(
      "longitude" 	=> $longitude,
      "latitude" 		=> $latitude,
      "kelurahan"		=> @$kelurahan,
      "kecamatan"		=> @$kecamatan,
      "kota"			  => @$kota,
    );

    return response()->json($result,200);
  }
}
