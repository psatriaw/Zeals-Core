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

use App\Http\Models\UserModel;

class StripeController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $user_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->user_model       = new UserModel();
  }

  public function index(){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"stripe-test")){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "api_key"        => $this->setting_model->getSettingVal("stripe_publishable_key"),
          "stripe_secret_key"        => $this->setting_model->getSettingVal("stripe_secret_key"),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.stripe.test",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Daftar Account",
          "description"   => $webname." | Daftar Account",
          "keywords"      => $webname." | Daftar Account"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Forbidden Page",
          "description"   => $webname." | Forbidden Page",
          "keywords"      => $webname." | Forbidden Page"
        );
        $body = "backend/body";
      }

      $data     = array(
          "content"   => $content,
          "login"     => $login,
          "page"      => "admin_dashboard",
          "submenu"   => "admin_dashboard",
          "meta"      => $metadata,
          "helper"    => $this->helper,
          "previlege" => $this->previlege_model
      );
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function update(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"master-pengaturan")){

        foreach($request->all() as $key=>$val){
          if($key!="_token"){
            if($val!=""){
              $data = array(
                "code_setting"    => $key,
                "setting_value"   => $val,
                "last_update"     => time()
              );

              $update  = $this->setting_model->updateData($data);
            }
          }
        }

        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Setting successfully saved!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => "Failed to save setting!"]);
        }
        return redirect(url('master/stripe-test'));

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Forbidden Page",
          "description"   => $webname." | Forbidden Page",
          "keywords"      => $webname." | Forbidden Page"
        );
        $body = "backend/body";
      }

      $data     = array(
          "content"   => $content,
          "login"     => $login,
          "page"      => "admin_dashboard",
          "submenu"   => "admin_dashboard",
          "meta"      => $metadata,
          "helper"    => $this->helper,
          "previlege" => $this->previlege_model
      );
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function charge(Request $request){
    print "<pre>";
    print_r($request->all());
  }
}
