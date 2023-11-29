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

use App\Http\Models\ShopModel;
use App\Http\Models\UserModel;
use App\Http\Models\OrderModel;

class TrackingController extends Controller{
    private $setting_model;
    private $previlege_model;
    private $helper;

    private $user_model;
    private $order_model;

    private $config;

    public function __construct(){
        $this->setting_model     = new SettingModel();
        $this->previlege_model   = new PrevilegeModel();
        $this->helper            = new IGWHelper();

        $this->user_model        = new UserModel();
        $this->order_model       = new OrderModel();

        $dataconfig['main_url']   = "admin/user";
        $dataconfig['view']       = "tracking-view";
    }
	
	public function gettracking(Request $request){
		$email_resi   		= $this->setting_model->getSettingVal("email_resi");
		$password_resi   	= $this->setting_model->getSettingVal("password_resi");

		$curl = curl_init();

		$url 	= "https://resi.id/api/auth/login";
		$query  = "&email=psatriaw@gmail.com&password=27272727pandu";
		$query2  = array(
			"email"			=> $email_resi,
			"password"		=> $password_resi
		);

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $query
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		
		print_r($response);
	}
}