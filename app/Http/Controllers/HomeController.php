<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

use App\Http\Models\SettingModel;
use App\Http\Models\UserModel;

class HomeController extends Controller{
  private $setting_model;

  public function __construct(){
      $this->setting_model  = new SettingModel();
      $this->user_model  = new UserModel();
  }

  public function index(){

      $view     = View::make("public.frontpage");
      $content  = $view->render();

      $webname  = $this->setting_model->getSettingVal("website_name");
      $metadata = array(
        "title"         => $webname." | ".$this->setting_model->getSettingVal("website_tagline"),
        "description"   => $webname." | ".$this->setting_model->getSettingVal("website_description"),
        "keywords"      => $webname." | ".$this->setting_model->getSettingVal("website_keywords")
      );

      $data     = array(
          "content"   => $content,
          "login"     => "",//$login,
          "page"      => "notification",
          "submenu"   => "notification",
          "meta"      => $metadata,
          "helper"    => "",//$this->frontier_helper,
          "previlege" => ""//$this->previlege
      );

      return view("backend/body",$data);
  }

  public function notfound(){
    $view     = View::make("backend.404");
    $content  = $view->render();

    $webname  = $this->setting_model->getSettingVal("website_name");
    $metadata = array(
      "title"         => $webname." | Not Found!",
      "description"   => $webname." | Not Found!",
      "keywords"      => $webname." | Not Found!"
    );

    $data     = array(
        "content"   => $content,
        "login"     => "",//$login,
        "page"      => "notification",
        "submenu"   => "notification",
        "meta"      => $metadata,
        "helper"    => "",//$this->frontier_helper,
        "previlege" => ""//$this->previlege
    );

    return view("backend/body",$data);
  }

  public function testui(){
    //$data           = $this->user_model->getDetail(3);
    //return view("emails.user_activation",$data);

    $Body = '{
             "encrypted_code": "mcbfgrtyak89ik",
             "aff_id":84737392,
             "unique_random_code": "testing123"
    }';

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://app.zeals.asia/apiv1/AMPcallback",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 120,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_SSLVERSION => 6,// CURL_SSLVERSION_TLSv1_2
      CURLOPT_SSL_VERIFYHOST => 2,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_POSTFIELDS => $Body,
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    echo $response;


  }

  public function policy(){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");

    $view     = View::make("landing.policy");
    $content  = $view->render();

    $data     = array(
        "content"                 => $content,
        "login"                   => $login,
        "page"                    => "admin_dashboard",
        "submenu"                 => "admin_dashboard",
        "title"                   => $webname." | ".$this->setting_model->getSettingVal("website_tagline"),
        "description"             => $webname." | ".$this->setting_model->getSettingVal("website_description"),
        "keywords"                => $webname." | ".$this->setting_model->getSettingVal("website_keywords"),
        "official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
        "official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
        "official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
        "official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
        "official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
        "head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
        "opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
    );

    return view("frontend.body",$data);
  }

  public function services(){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");

    $view     = View::make("landing.services");
    $content  = $view->render();

    $data     = array(
        "content"                 => $content,
        "login"                   => $login,
        "page"                    => "admin_dashboard",
        "submenu"                 => "admin_dashboard",
        "title"                   => $webname." | ".$this->setting_model->getSettingVal("website_tagline"),
        "description"             => $webname." | ".$this->setting_model->getSettingVal("website_description"),
        "keywords"                => $webname." | ".$this->setting_model->getSettingVal("website_keywords"),
        "official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
        "official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
        "official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
        "official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
        "official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
        "head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
        "opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
    );

    return view("frontend.body",$data);
  }

  public function landing(){
        return view("landing.landing");
    }
}
