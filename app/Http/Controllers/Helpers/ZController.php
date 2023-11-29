<?php

namespace App\Http\Controllers\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Helpers\IGWHelper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;

use App\Http\Models\PrevilegeModel;

class ZController extends Controller{
    public $pv_model;
    public $help_class;

    public function __construct(){
        $this->pv_model         = new PrevilegeModel();
        $this->help_class       = new IGWHelper();
    }

    public function checkPermission($login,$method){
        $this->pv_model         = new PrevilegeModel();
        $this->help_class       = new IGWHelper();
        
        $result = $this->pv_model->isAllow($login->id_user,$login->id_department,$method);

        if($result){
            return true;
        }else{
            $view     = View::make("frontend.403");
            $content  = $view->render();

            $data     = array(
                "content"                 => $content,
                "login"                   => "",
                "page"                    => "admin_dashboard",
                "submenu"                 => "admin_dashboard",
                "helper"                  => $this->help_class,
                "previlege"               => $this->pv_model,
                "title"                   => "403",
                "description"             => "Forbidden Page",
                "keywords"                => "Forbidden area",
                "official_phone_number"   => "",
                "official_email_address"  => "",
                "official_facebook"       => "",
                "official_twitter"        => "",
                "official_instagram"      => "",
                "head_quarters"           => "",
                "opening_hour"            => "",
            );

            print view("frontend.body",$data);
            exit();
        }
    }
}