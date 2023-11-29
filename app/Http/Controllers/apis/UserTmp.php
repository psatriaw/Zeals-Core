<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Http\Models\UserModel;
use App\Http\Models\SettingModel;
use App\Http\Models\UserTempModel;
use App\Http\Models\DepartmentModel;

class UserTmp extends Controller
{
    public function __construct(){
      $this->setting_model      = new SettingModel();
      $this->user_model         = new UserModel();
      $this->department_model   = new DepartmentModel();
    }

    public function checkUserPool(Request $request){

      $data        = $this->user_model->checkCustomField($request->value);

      if(!$data){
        $response = array(
            "status"  => "error",
            "data"    => null
          );
      }else{

        $data['first_name']     = $data->first_name;
        $name                   = explode(' ',$data->first_name);
        $data['last_name']      = $name[sizeof($name)-1];
        $data['phone']          = $data->phone;
        $data['email']          = $data->email;
    
        $response = array(
            "status"  => "success",
            "data"    => $data
          );
      }

      return response()->json($response,200);
    }

    public function checkUserThePool(Request $request){
      $this->tempuser = new UserTempModel();

      $data        = $this->tempuser->checkData($request->value);

      if(!$data){
        $response = array(
            "status"  => "error",
            "data"    => null
          );
      }else{

        $data['first_name']     = $data->name;
        $name                   = explode(' ',$data->name);
        $data['last_name']      = $name[sizeof($name)-1];
        $data['phone']          = $data->phone;
        $data['email']          = $data->email;
    
        $response = array(
            "status"  => "success",
            "data"    => $data
          );
      }

      return response()->json($response,200);
    }
}
