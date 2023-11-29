<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use Validator;
use App\Models\{Banner, Campaign};


use App\Services\apis\{ BannerService };

use Session;


class BannerController extends Controller
{

    protected $user_model;
    protected $department_model;
    // protected $setting_model;

    public function __construct(){
        //$this->setting_model = new SettingModel();
    }

    public function list(Request $request){
        $service = new BannerService($request);
        $banners = $service->get();
        
        if($banners){
            $return = array(
                "status"    => "success",
                "data"      => $banners,
                "path"      => url('/')
            );
        }else{
            $return = array(
                "status"    => "error",
                "messages"  => "No banner found!"
            );
        }

        return response()->json($return, 200);
    }
}
