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
use App\Http\Models\NotificationModel;
//===============================

class NotificationController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $notification_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->notification_model = new NotificationModel();
  }

  public function index(){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      $datacontent = array(
        "login"          => $login,
        "helper"         => "",
        "previlege"      => $this->previlege_model,
        "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
      );

      $view     = View::make("backend.notification.index",$datacontent);
      $content  = $view->render();

      $metadata = array(
        "title"         => $webname." | Notifikasi",
        "description"   => $webname." | Notifikasi",
        "keywords"      => $webname." | Notifikasi"
      );

      $body = "backend.body_backend_with_sidebar";

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

  public function getunread(){
    $login    = Session::get("user");
    $id_to    = $login->id_user;

    $listunread = $this->notification_model->getListUnreadNotification($id_to);
    $total      = $listunread->count();
    $html       = array();
    if($total){
      foreach ($listunread as $key => $value) {
        $html[] = '<li>
                      <a href="#" onclick="readnotification('.$value->id_notification.',\''.$value->link.'\')">
                          <div>
                              '.$value->content.'
                          </div>
                      </a>
                  </li>';
        $html[] = '<li class="divider"></li>';
      }
      $html = implode("",$html);
    }

    $result = array(
      "total" 	=> $total,
      "html" 		=> $html
    );

    return response()->json($result,200);
  }

  public function loadmore(Request $request){
    $login    = Session::get("user");
    $id_to    = $login->id_user;
    $last_id  = $request->input("last_id");

    $notifications = $this->notification_model->getMoreNotifications($id_to,$last_id);
    //print_r($notifications);
    $total      = $notifications->count();
    $html       = array();
    if($total){
      foreach ($notifications as $key => $value) {
        $html[] = '<li>
                      <a class="'.(($value->is_read)?"read":"unread").'" onclick="readnotification('.$value->id_notification.',\''.$value->link.'\')">
                          <div>
                              '.$value->content.'
                          </div>
                      </a>
                  </li>';
        $last_id = $value->id_notification;
      }
      $html = implode("",$html);
    }

    $result = array(
      "total" 	=> $total,
      "html" 		=> $html,
      "last_id" => $last_id
    );

    return response()->json($result,200);
  }

  public function readnotif(Request $request){
    $id_notification = $request->input("id_notification");
    $data = array(
      "id_notification" => $id_notification,
	  "last_update"		=> time(),
      "is_read"         => 1
    );

    $result = $this->notification_model->updateData($data);
    if($result){
      $return['result'] = 200;
    }else{
      $return['result'] = 400;
    }

    return response()->json($return,200);
  }
}
