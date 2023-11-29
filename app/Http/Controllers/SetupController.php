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
use App\Http\Models\MethodModel;

class SetupController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $user_model;
  private $method_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->user_model       = new UserModel();
    $this->method_model     = new MethodModel();
  }

  public function grantall($id){
    $methods = $this->method_model->getAllMethods();
    if($methods){
      $id_department = $id;
      $this->previlege_model->deletePrevileges($id_department);
      foreach ($methods as $key => $value) {
        $data = array(
          "id_method"       => $value->id_method,
          "id_department"   => $id_department
        );
        $this->previlege_model->createData($data);
      }
    }
  }
}
