<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use Validator;
use App\Models\SektorIndustri;

use Session;


class CategoryController extends Controller
{

    protected $user_model;
    protected $department_model;
    // protected $setting_model;

    public function __construct(){
        //$this->setting_model = new SettingModel();
    }

    public function list(Request $request){
        $categories = SektorIndustri::where("status",'active')->get();
        
        if($categories){

            $return = array(
                "status"    => "success",
                "data"      => $categories,
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
