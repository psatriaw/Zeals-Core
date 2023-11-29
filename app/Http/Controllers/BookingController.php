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

class BookingController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $user_model;
  private $department_model;
  private $account_model;
  private $bank_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

  }

  public function index(){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");

    $webname  = $this->setting_model->getSettingVal("website_name");
    $datacontent = array(
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

    $view     = View::make("frontend.booking_step_1",$datacontent);
    $content  = $view->render();

    $data     = array(
        "content"   => $content,
        "login"     => $login,
        "page"      => "admin_dashboard",
        "submenu"   => "admin_dashboard",
        "helper"    => $this->helper,
        "previlege" => $this->previlege_model,
        "title"     => "Boking",
        "description" => "tes",
        "keywords" => "tes"
    );

    return view("frontend.body",$data);
  }

}
