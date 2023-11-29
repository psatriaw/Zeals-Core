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

use App\Http\Models\MitraModel;
use App\Http\Models\UserModel;
use App\Http\Models\DepartmentModel;
use App\Http\Models\MethodModel;
use App\Http\Models\RestrictionModel;
use App\Http\Models\PageModel;
use App\Http\Models\PenerbitModel;
use App\Http\Models\CampaignTrackerModel;
use App\Http\Models\CampaignModel;
use App\Http\Models\RekeningDanaModel;
use Socialite;
use Exception;


class UserController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $user_model;
  private $department_model;
  private $method_model;
  private $restriction_model;
  private $mitra_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->user_model         = new UserModel();
    $this->department_model   = new DepartmentModel();
    $this->method_model       = new MethodModel();
    $this->restriction_model  = new RestrictionModel();
    $this->page_model         = new PageModel();
    $this->penerbit_model     = new PenerbitModel();
    $this->rekening_dana_model     = new RekeningDanaModel();

    $dataconfig['main_url'] = "admin/user";
    $dataconfig['view']     = "admin-master-user-show";
    $dataconfig['approve']  = "admin-master-user-approve";
    $dataconfig['create']   = "admin-master-user-create";
    $dataconfig['edit']     = "admin-master-user-edit";
    $dataconfig['remove']   = "admin-master-user-remove";
    $dataconfig['manage']   = "admin-master-user-manage";
    $dataconfig['performance']   = "admin-master-user-performance";
    $dataconfig['resume']   = "admin-master-user-resume";

    $this->config           = $dataconfig;
  }

  public function findUserPegawai(Request $request){
    $data = $this->user_model->findUsersPegawai($request->search);
    if($data){
      foreach ($data as $key => $value) {
        $dataret[] = array(
          "id"    => $value->id_user,
          "text"  => $value->first_name." ".$value->last_name." (".$value->email.") - ".strtoupper($value->name)
        );
      }

      $return = array(
        "results"     => $dataret,
        "pagination"  => array(
          "more"  => false
        )
      );

      return response()->json($return, 200);
    }
  }

  public function findUser(Request $request){
    $data = $this->user_model->findUsers($request->search);
    if($data){
      foreach ($data as $key => $value) {
        $dataret[] = array(
          "id"    => $value->id_user,
          "text"  => $value->first_name." ".$value->last_name." (".$value->email.")"
        );
      }

      $return = array(
        "results"     => $dataret,
        "pagination"  => array(
          "more"  => false
        )
      );

      return response()->json($return, 200);
    }
  }

  public function register(){
      $this->page_model->setCode("registration-page");

      $datacontent = array(
        "google_api_key"    => $this->setting_model->getSettingVal("google_api_key"),
        "base_city_coor"    => $this->setting_model->getSettingVal("base_city_lang_lat"),
        "content"		        => $this->page_model->getContent(),
        "title"			        => $this->page_model->getTitle(),
        "is_google_registration"  => Session::get("google_register"),
        "google_email"      => Session::get("google_email")
      );
      $view     = View::make("backend.user.register",$datacontent);
      $content  = $view->render();

      $webname  = $this->setting_model->getSettingVal("website_name");
      $metadata = array(
        "title"         => $webname." | Registrasi akun kamu disini",
        "description"   => $webname." | Registrasi akun uronshop kamu disini untuk melakukan transaksi pada jaringan uronshop",
        "keywords"      => $webname." | Regsitrasi akun, Registrasi Uronshop"
      );

      $login    = Session::get("user");
      $data     = array(
          "content"                 => $content,
          "login"                   => $login,
          "page"                    => "admin_dashboard",
          "submenu"                 => "admin_dashboard",
          "helper"                  => $this->helper,
          "previlege"               => $this->previlege_model,
          "title"                   => $webname." | ".$this->page_model->getKeyword(),
          "description"             => $webname." | ".$this->page_model->getKeyword(),
          "keywords"                => $webname." | ".$this->page_model->getKeyword(),
          "official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
          "official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
          "official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
          "official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
          "official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
          "head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
          "opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
      );

      return view("frontend/body",$data);
  }

  public function registersubmit(Request $request){
    $department_code = @$request->department_code;

    $data = array(
      "first_name"      => $request->input("first_name"),
      "last_name"       => $request->input("last_name"),
      "username"        => $request->input("username"),
      "email"           => $request->input("email"),
      "password"        => $request->input("password"),
      "phone"           => $request->input("phone"),
      "id_department"   => $this->department_model->getDefaultDepartment(),
      "date_created"    => time(),
      "last_update"     => time(),
      "google_id"       => $request->google_id,
      "custom_field_1"  => $request->custom_field_1,
      "custom_field_2"  => $request->custom_field_2,
      "gender"          => $request->gender,
      "dob"             => $request->dob,
    );

    if(@$request->google_id!=""){
      $data['status'] = 'active';
    }else{
      $data['status'] = 'inactive';
    }

    $rules  = array(
      "first_name"      => "required",
      "email"           => ["required",Rule::unique('tb_user')->where("status","active")],
			"password"        => 'required',
			"phone"           => 'required|numeric',
			"dob"             => 'required',
		);

		$messages = array(
      "first_name.required" => "Please fill this field!",
      "email.required"      => "Please fill this field!",
      "email.email"         => "Please fill this field with valid email format, example: name@domain.com",
      "email.unique"        => "Email already used by another user! Please select another valid email",
      "password.required"   => "Please fill this field!",
      "phone.required"      => "Please fill this field!",
      "phone.numeric"       => "Please fill this field only with numeric!",
      "dob.required"        => "Please fill this field!",
    );

    if($department_code){
      $department = $this->department_model->getByCode($department_code);

      $data['id_department']  = $department->id_department;
      if($department->custom_user_fields!=""){
        $customfields = json_decode($department->custom_user_fields,TRUE);
        $fields       = $customfields['fields'];

        foreach($fields as $indexrule=>$field){
          $rules[$field['name']] = $field['rule'];
          $messages[$field['name'].'.'.$field['rule']] = "Please fill this field!";
        }
      }
    }

		$validator = Validator::make($data, $rules, $messages);
    if ($validator->fails()) {
      $error = array(
        "status"    => "error_validation",
        "data"      => $data,
        "response"  => $validator->messages(),
      );

      return response()->json($error, 200);
    }else{

      //check email
      $email  = $request->email;
      $key    = $this->setting_model->getSettingVal("email_check_api");

      $curl = curl_init();

      curl_setopt_array($curl, [
      	CURLOPT_URL => "https://ajith-verify-email-address-v1.p.rapidapi.com/varifyEmail?email=".$email,
      	CURLOPT_RETURNTRANSFER => true,
      	CURLOPT_FOLLOWLOCATION => true,
      	CURLOPT_ENCODING => "",
      	CURLOPT_MAXREDIRS => 10,
      	CURLOPT_TIMEOUT => 30,
      	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      	CURLOPT_CUSTOMREQUEST => "GET",
      	CURLOPT_HTTPHEADER => [
      		"x-rapidapi-host: ajith-Verify-email-address-v1.p.rapidapi.com",
      		"x-rapidapi-key: ".$key
      	],
      ]);

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if (@$err) {
      	$hasil['exist'] = "false";
      } else {
      	$hasil = json_decode($response,true);
      }

      if(@$hasil['exist']=="false"){
        $error = array(
          "status"    => "error_validation",
          "response"  => array(
            "email"   => array("Email does not exist! Please try with different valid email address")
          )
        );

        return response()->json($error, 200);
        exit();
      }

      $activation_code            = $this->user_model->createActivationCode();
      unset($data['agree']);
      $data['activation_code']    = $activation_code;
      $data['password']           = md5($data['password']);
      $data['date_created']       = time();
      $data['last_update']        = time();
      $createuser                 = $this->user_model->insertData($data);

      //$mitra_code                 = $this->mitra_model->createMitraCode();

      //$datamitra['id_user']       = $createuser;
      //$datamitra['mitra_code']    = $mitra_code;

      $dataemail                    = $data;
      $dataemail['activation_link'] = url('activate-account/'.$activation_code);

      if(@$request->google_id==""){

        Mail::send('emails.user_activation', $dataemail, function ($mail) use ($dataemail) {
          $this->settingmodel = new SettingModel();

          $sender             = $this->setting_model->getSettingVal("email_sender_name");
          $senderaddress      = $this->setting_model->getSettingVal("email_sender_address");

          $mail->from($senderaddress, $sender);
          $mail->to($dataemail['email'], $dataemail['first_name']);
          $mail->subject('Account Activation');
        });

        $return = array(
          "status"    => "success",
          "response"  => "<div class='alert alert-success alert-dismissable'>
                              <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                                Registration almost complete! Please activate your account through your email
                              <a class='alert-link' href='#'>".$data['email']."</a>.
                          </div>"
        );
      }else{
        Session::forget("google_register");
        Session::forget("google_email");
        Session::flush();

        $return = array(
          "status"    => "success",
          "response"  => "<div class='alert alert-success alert-dismissable'>
                              <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                                Registration completed! Please sign in from <a href='".url('signin')."'>here</a>.
                          </div>"
        );
      }
      return response()->json($return,200);
    }
  }

  public function loggedRedirect($login){
    return "logout";
  }

  public function login(){
      $webname  = $this->setting_model->getSettingVal("website_name");

      $dataview = array(
        "webname"   => $webname
      );
      $view     = View::make("backend/user/login",$dataview);
      $content  = $view->render();

      //===================
      $login    = Session::get("user");
      if($login){
        return redirect($this->loggedRedirect($login));
      }
      //===================

      $metadata = array(
        "title"         => $webname." | Login system ".$webname,
        "description"   => $webname." | login jaringan ".$webname,
        "keywords"      => $webname." | Login ".$webname.", Login System"
      );

      $data     = array(
          "content"   => $content,
          "login"     => "",//$login,
          "meta"      => $metadata,
          "page"      => "login",
          "submenu"   => "login",
          "helper"    => $this->helper,
          "previlege" => $this->previlege_model
      );

      return view("backend/body",$data);
  }

  public function auth(Request $request){
    $data = array(
      "username"        => $request->input("username"),
      "password"        => $request->input("password"),
    );

    $rules  = array(
      "username"        => "required",
      "password"        => 'required',
		);
		$messages = array(
      "username.required"   => "Please fill this field",
      "password.required"   => "Please fill this field"
    );

		$validator = Validator::make($data, $rules, $messages);
    if ($validator->fails()) {
      $return = array(
        "status"    => "error_validation",
        "response"  => $validator->messages()
      );
    }else{
      $data['password']   = md5($data['password']);
      $auth    = $this->user_model->getAuth($data);
      // dd($auth);
      //$toko   = $this->toko_model->getToko($auth);
      // dd($toko);
      if($auth){
        Session::put("user",$auth);
        //Session::put("toko",$toko);
        $return = array(
          "status"    => "success",
          "response"  => "<div class='alert alert-success alert-dismissable'>
                              <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                              Hi <strong>".$auth->first_name.".</strong>!<br> Please wait.. signin inisiation.
                          </div>"
        );
      }else{
        $return = array(
          "status"    => "error",
          "response"  => "<div class='alert alert-danger alert-dismissable'>
                              <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                              Username and password do not match.
                          </div>",
          "query"     => $data
        );
      }
    }
    return response()->json($return, 200);
  }

  public function authpublic(Request $request){
      $data = array(
          "email"           => $request->input("email"),
          "password"        => $request->input("password"),
      );

      $rules  = array(
          "email"           => "required",
          "password"        => 'required',
      );
      $messages = array(
          "email.required"      => "Please fill this field",
          "password.required"   => "Please fill this field",
      );

      $validator = Validator::make($data, $rules, $messages);
      if ($validator->fails()) {
          $return = array(
              "status"    => "error_validation",
              "response"  => $validator->messages()
          );
      }else{
          if(substr($data['email'],0,2)=="08" && $data['email']==$data['password']) {
            $data['email'] = "62".substr($data['email'],1);
          }

          if(substr($data['password'],0,2)=="08" && $data['email']==$data['password']) {
            $data['password'] = "62".substr($data['password'],1);
          }

          $data['password']   = md5($data['password']);
          $auth    = $this->user_model->getAuthPublic($data);
          if($auth){
              Session::put("user",$auth);
              $return = array(
                  "status"    => "success",
                  "response"  => "<div class='alert alert-success alert-dismissable'>
                                      <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                                      Hi <strong>".$auth->first_name.".</strong>!<br> Please wait.. signin inisiation.
                                  </div>"
              );
          }else{
              $return = array(
                  "status"    => "error",
                  "response"  => "<div class='alert alert-danger alert-dismissable'>
                                      <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                                      Email and password do not match.
                                  </div>",
                  "query"     => $data
              );
          }
      }
      return response()->json($return, 200);
  }

  public function authpublicaff(Request $request){
      $data = array(
          "email"           => $request->input("email"),
          "password"        => $request->input("password"),
      );

      $rules  = array(
          "email"           => "required",
          "password"        => 'required',
      );
      $messages = array(
          "email.required"      => "Please fill this field",
          "password.required"   => "Please fill this field",
      );

      $validator = Validator::make($data, $rules, $messages);
      if ($validator->fails()) {
          $return = array(
              "status"    => "error_validation",
              "response"  => $validator->messages()
          );
      }else{
          if(substr($data['email'],0,2)=="08" && $data['email']==$data['password']) {
            $data['email'] = "62".substr($data['email'],1);
          }

          if(substr($data['password'],0,2)=="08" && $data['email']==$data['password']) {
            $data['password'] = "62".substr($data['password'],1);
          }

          $data['password']   = md5($data['password']);
          $auth    = $this->user_model->getAuthPublic($data);
          if($auth){
              Session::put("aff",$auth);
              $return = array(
                  "status"    => "success",
                  "response"  => "<div class='alert alert-success alert-dismissable'>
                                      <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                                      Hi <strong>".$auth->first_name.".</strong>!<br> Please wait.. signin inisiation.
                                  </div>"
              );
          }else{
              $return = array(
                  "status"    => "error",
                  "response"  => "<div class='alert alert-danger alert-dismissable'>
                                      <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                                      Email and password do not match.
                                  </div>",
                  "query"     => $data
              );
          }
      }
      return response()->json($return, 200);
  }

  public function forgetpassword(){
    $view     = View::make("backend.user.reset_password");
    $content  = $view->render();

    $webname  = $this->setting_model->getSettingVal("website_name");
    $metadata = array(
      "title"         => $webname." | Reset password",
      "description"   => $webname." | Reset password uronshop",
      "keywords"      => $webname." | Reset password uronshop"
    );

    $data     = array(
        "content"   => $content,
        "login"     => "",//$login,
        "page"      => "registration",
        "submenu"   => "registration",
        "meta"      => $metadata,
        "helper"    => $this->helper,
        "previlege" => $this->previlege_model
    );

    return view("backend/body",$data);
  }

  public function forgetpasswordsubmit(Request $request){
    $data = array(
      "email"        => $request->input("email")
    );

    $rules  = array(
      "email"        => "required"
		);
		$messages = array(
      "email.required"   => "Mohon mengisi email anda disini"
    );

		$validator = Validator::make($data, $rules, $messages);

    if ($validator->fails()) {
      $return = array(
        "status"    => "error_validation",
        "response"  => $validator->messages()
      );
    }else{
      $auth    = $this->user_model->getDetailByEmail($data);
      if($auth){
        $password  = $this->user_model->getRandomPassword(6);
        $newdata   = array(
          "id_user"  => $auth->id_user,
          "password" => md5($password)
        );

        $this->user_model->updateData($newdata);

        $dataemail = $auth->toArray();
        $dataemail['password'] = $password;

        Mail::send('emails.user_reset', $dataemail, function ($mail) use ($dataemail) {
          $this->settingmodel = new SettingModel();

          $sender             = $this->setting_model->getSettingVal("email_sender_name");
          $senderaddress      = $this->setting_model->getSettingVal("email_sender_address");

          $mail->from($senderaddress, $sender);
          $mail->to($dataemail['email'], $dataemail['first_name']);
          $mail->subject('Reset password');
        });

        $return = array(
          "status"    => "success",
          "response"  => "<div class='alert alert-success alert-dismissable'>
                              <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                              Hai ".$auth->first_name.". Kami mengirimkan password baru ke alamat email ".$auth->email.".
                          </div>"
        );
      }else{
        $return = array(
          "status"    => "error",
          "response"  => "<div class='alert alert-danger alert-dismissable'>
                              <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>x</button>
                              Email tidak ditemukan!
                          </div>"
        );
      }
    }
    return response()->json($return, 200);
  }

  public function activateaccount(Request $request){
    $activation_code  = $request->segment(2);
    $activation       = array(
      "status"        => "active",
      "last_update"   => time()
    );
    $activate         = $this->user_model->activateAccount($activation_code,$activation);
    if($activate){
      $return    = array(
        "status"    => "success",
        "response"  => "Account activation completed! Please <a href='".url('signin')."'>Sign in here</a>"
      );
    }else{
      $return    = array(
        "status"    => "error",
        "response"  => "Activation failed!"
      );
    }

    $view     = View::make("frontend.activation",$return);

    $content  = $view->render();

    $webname  = $this->setting_model->getSettingVal("website_name");
    $metadata = array(
      "title"         => $webname." | Aktivasi Akun",
      "description"   => $webname." | Aktivasi Akun",
      "keywords"      => $webname." | Aktivasi Akun"
    );

    $data     = array(
        "content"                 => $content,
        "login"                   => @$login,
        "page"                    => "admin_dashboard",
        "submenu"                 => "admin_dashboard",
        "helper"                  => $this->helper,
        "previlege"               => $this->previlege_model,
        "title"                   => @$webname." | ".$this->setting_model->getSettingVal("website_tagline"),
        "description"             => @$webname." | ".$this->setting_model->getSettingVal("website_description"),
        "keywords"                => @$webname." | ".$this->setting_model->getSettingVal("website_keywords"),
        "official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
        "official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
        "official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
        "official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
        "official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
        "head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
        "opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
    );

    return view("frontend/body",$data);
  }

  public function activateaccountviahp(Request $request){
    $no_hp            = $request->segment(2);
    $activation       = array(
      "status"        => "active",
      "last_update"   => time()
    );
    $activate         = $this->user_model->activateviaHP($no_hp,$activation);
    if($activate){
      $login = $activate;
      Session::put("aff",$login);

      $return    = array(
        "status"    => "success",
        "response"  => "Account activation completed! Please wait. You are about to earn your money as easy as move your thumb!",
        "login"     => $login
      );

    }else{
      $return    = array(
        "status"    => "error",
        "response"  => "Activation failed!",
        "login"     => null
      );
    }

    $view     = View::make("frontend.activation",$return);

    $content  = $view->render();

    $data     = array(
        "content"                 => $content,
        "login"                   => @$login,
        "page"                    => "admin_dashboard",
        "submenu"                 => "admin_dashboard",
        "helper"                  => $this->helper,
        "previlege"               => $this->previlege_model,
        "title"                   => @$webname." | ".$this->setting_model->getSettingVal("website_tagline"),
        "description"             => @$webname." | ".$this->setting_model->getSettingVal("website_description"),
        "keywords"                => @$webname." | ".$this->setting_model->getSettingVal("website_keywords"),
        "official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
        "official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
        "official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
        "official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
        "official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
        "head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
        "opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
    );

    return view("frontend.body_clear",$data);
  }

  public function logout(){
    Session::forget("user");
    Session::flush();
    return redirect("login");
  }

  public function signout(){
    Session::forget("user");
    Session::flush();
    return redirect("signin");
  }

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $default    = array(
          "short"     => "date_created",
          "shortmode" => "desc"
        );
        $shorter = array(
          "first_name"    => "First Name",
          "last_name"     => "Last Name",
          "username"      => "Username",
          "email"         => "Email",
          "date_created"  => "Registered"
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;

        if($login->department_code!="spr"){
          if($login->department_code=="AGENCY"){
            $referral_code = $login->referral_code;
            $id_department = $this->department_model->getDepartmentByCode($referral_code);
          }elseif($login->department_code=="BRAND"){
            $referral_code = $login->referral_code;
            $id_department = $this->department_model->getDepartmentByCode($referral_code);
          }else{
            $id_department = $this->department_model->getDepartmentByCode($login->department_code);
          }
        }else{
          $id_department = "";
        }

        $data       = $this->user_model->getData($str, $limit, $keyword, $short, $shortmode, $id_department);
        //dd($data);
        //exit;
        $totaldata  = $this->user_model->countData($keyword, $id_department);
        $pagging    = $this->helper->showPagging($totaldata, url('admin/user/?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode), $position = "", $page, $limit , 2);

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "data"           => $data,
          "pagging"        => $pagging,
          "input"          => $request->all(),
          "default"        => $default,
          "limit"          => $limit,
          "shorter"        => $shorter,
          "page"           => $page,
          "config"         => $this->config,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.user.index",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Dashboard",
          "description"   => $webname." | Dashboard",
          "keywords"      => $webname." | Dashboard"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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

  public function create(){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "optdepartment"  => $this->department_model->getDepartmentOpt(),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "brands"         => $this->penerbit_model->getPenerbitActive()
        );

        $view     = View::make("backend.master.user.create",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah pengguna",
          "description"   => $webname." | Tambah pengguna",
          "keywords"      => $webname." | Tambah pengguna"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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

  public function store(Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $data = array(
          "first_name"        => $request->input("first_name"),
          "last_name"         => $request->input("last_name"),
          "email"             => $request->input("email"),
          "username"          => $request->input("username"),
          "password"          => md5($request->input("password")),
          "phone"             => $request->input("phone"),
          "id_department"     => $request->input("id_department"),
          "address"           => $request->input("address"),
          "date_created"      => time(),
          "last_update"       => time(),
          "affiliate_code"    => $this->user_model->createaffiliatecode(),
        );

        $rules  = array(
          "first_name"      => "required",
          "username"        => "required|unique:tb_user,username",
          "email"           => "required|email|unique:tb_user,email",
    			"password"        => 'required',
    		);

    		$messages = array(
          "first_name.required" => "Please fill this field",
          "username.required"   => "Please fill this field",
          "username.unique"     => "This value has been taken by another account",
          "email.required"      => "Please fill this field",
          "email.email"         => "Please use valid email format: contoh@domain",
          "email.unique"        => "This value has been taken by another account",
          "password.required"   => "Please fill this field"
        );

        //if department  == brand
        $department = $this->department_model->getDetail($data['id_department']);
        if($department->department_code=="BRAND"){
          $rules['id_brand']              = "required";
          $messages['id_brand.required']  = "Please fill this field";
        }
        //if department == brand

    		$this->validate($request, $rules, $messages);

        $activation_code            = $this->user_model->createActivationCode();
        $data['activation_code']    = $activation_code;

        $createuser                 = $this->user_model->insertData($data);
        if($createuser){

          // $dataemail                  = $data;
          // $dataemail['activation_link'] = url('activate-account/'.$activation_code);

          // Mail::send('emails.user_activation', $dataemail, function ($mail) use ($dataemail) {
          //   $this->settingmodel = new SettingModel();

          //   $sender             = $this->setting_model->getSettingVal("email_sender_name");
          //   $senderaddress      = $this->setting_model->getSettingVal("email_sender_address");

          //   $mail->from($senderaddress, $sender);
          //   $mail->to($dataemail['email'], $dataemail['first_name']);
          //   $mail->subject('Aktivasi Akun Uronshop Anda');
          // });

          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully added']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to add data!']);
        }
        return redirect(url('admin/user/create'));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
        );
        $body = "backend/body";

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
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function edit($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "optdepartment"  => $this->department_model->getDepartmentOpt(),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->user_model->getDetail($id),
          "brands"         => $this->penerbit_model->getPenerbitActive()
        );

        //print_r($datacontent['data']->toArray());
        //exit();

        $view     = View::make("backend.master.user.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah pengguna",
          "description"   => $webname." | Ubah pengguna",
          "keywords"      => $webname." | Ubah pengguna"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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


  public function profile($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,'profile-view')){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "optdepartment"  => $this->department_model->getDepartmentOpt(),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->user_model->getDetail($id),
          "brand"          => $this->penerbit_model->getDetail($login->id_brand)
        );

        $view     = View::make("backend.master.user.profile",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah pengguna",
          "description"   => $webname." | Ubah pengguna",
          "keywords"      => $webname." | Ubah pengguna"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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

  public function startAuthGoogle(){
    return Socialite::driver('google')->redirect();
  }

  public function authGooglePlus(Request $request){
    try{

      $user           = Socialite::driver('google')->user();
      $google_id      = $user->id;
      $google_email   = $user->email;
      $auth           = $this->user_model->getDetailByGoogleID($google_id);

      if($auth){
        Session::put("aff",$auth);
        return redirect(url('campaign'));
      }else{
        Session::put("google_register",$google_id);
        Session::put("google_email",$google_email);
        return redirect(url('register'));
      }

    }catch (Exception $e) {
      Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Login error due to: '.$e->getMessage()]);
      return redirect(url('signin'));
    }
  }

  public function update($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-user-edit")){
        $detail = $this->user_model->getDetail($id);

        $data = array(
          "id_user"           => $id,
          "first_name"        => $request->input("first_name"),
          "last_name"         => $request->input("last_name"),
          "email"             => $request->input("email"),
          "username"          => $request->input("username"),
          "phone"             => $request->input("phone"),
          "id_department"     => $request->input("id_department"),
          "address"           => $request->input("address"),
          "status"            => $request->input("status"),
          "last_update"       => time(),
          //"tipe_user"         => $request->input("tipe_user"),
          //"gaji"              => ($request->input("gaji")!="")? str_replace(".","",$request->input("gaji")):0,
          //"insentif"          => ($request->input("insentif")!="")?str_replace(".","",$request->input("insentif")):0,
          //"uang_makan"        => ($request->input("insentif")!="")?str_replace(".","",$request->input("uang_makan")):0,
          "id_brand"          => @$request->input("id_brand"),
        );

        $rules  = array(
          "first_name"      => "required",
          "username"        => "required|unique:tb_user,username,".$id.",id_user",
          "email"           => "required|email|unique:tb_user,email,".$id.",id_user"
    		);

    		$messages = array(
          "first_name.required" => "Please fill this field",
          "username.required"   => "Please fill this field",
          "username.unique"     => "This value has been taken by another account",
          "email.required"      => "Please fill this field",
          "email.email"         => "Please use valid email format: contoh@domain",
          "email.unique"        => "This value has been taken by another account",
          "password.required"   => "Please fill this field"
        );

        if($request->input("password")!=""){
          $data['password']   = md5($request->input("password"));
        }


        //if department  == brand
        $department = $this->department_model->getDetail($data['id_department']);
        if($department->department_code=="BRAND"){
          $rules['id_brand']              = "required";
          $messages['id_brand.required']  = "Please fill this field";
        }
        //if department == brand

    		$this->validate($request, $rules, $messages);

        $update                 = $this->user_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully updated!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Update data failed!']);
        }
        return redirect(url('admin/user/edit/'.$id));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
        );
        $body = "backend/body";

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
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function remove(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['remove'])){
        $id = $request->input("id");
        $detail = $this->user_model->getDetail($id);
        $data = array(
          "id_user"     => $id,
          "status"      => "deleted",
          "last_update" => time()
        );
        $remove = $this->user_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data pengguna '.$detail->first_name.'!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data pengguna']);
        }
        return redirect(url('admin/user'));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
        );
        $body = "backend/body";

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
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function detail($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "optdepartment"  => $this->department_model->getDepartmentOpt(),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->user_model->getDetail($id),
          "brands"         => $this->penerbit_model->getPenerbitActive()
        );

        $view     = View::make("backend.master.user.detail",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail pengguna",
          "description"   => $webname." | Detail pengguna",
          "keywords"      => $webname." | Detail pengguna"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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

  public function performance($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['performance'])){
        $this->campaign_tracker_model = new CampaignTrackerModel();
        $this->campaign_model         = new CampaignModel();

        $userlogin              = $this->user_model->getDetail($id);
        $statistic_visit        = $this->campaign_tracker_model->getTotalTracker('visit',"",$userlogin);
        $statistic_read         = $this->campaign_tracker_model->getTotalTracker('read',"",$userlogin);
        $statistic_action       = $this->campaign_tracker_model->getTotalTracker('action',"",$userlogin);
        $statistic_acquisition  = $this->campaign_tracker_model->getTotalTracker('acquisition',"",$userlogin);
        $infosaldo              = $this->rekening_dana_model->getCurrentRekening($userlogin->id_user);

        $datacontent = array(
          "login"          => $login,
          "saldo"          => $infosaldo->saldo,
          "data"           => $userlogin,
          "campaigns"      => $this->campaign_model->getListCampaignAvailable($userlogin),
          "logs"           => $this->campaign_tracker_model->getLast15Logs("",$userlogin),
          "earning"         => array(
            "estimation"    => $this->campaign_tracker_model->sumTotalEstimation("",$userlogin),
            "total"         => $this->campaign_tracker_model->sumTotalEarning("",$userlogin),
            "top10"         => $this->campaign_model->getMost10Earning($userlogin)
          )
        );

        if($statistic_visit>0){
          $datacontent['statistic']['visit'] = array(
            "total"       => $statistic_visit,
            "percent"     => ($statistic_read*100)/$statistic_visit
          );

          for($i=29;$i>0;$i--) {
            $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',($i*60*60*24)+1, ($i+1)*60*60*24,"",$userlogin);
          }
          $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',(12*60*60)+1, 1*60*60*24,"",$userlogin);
          $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',0, 12*60*60,"",$userlogin);


          for($i=29;$i>0;$i--) {
            $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',($i*60*60*24)+1, ($i+1)*60*60*24,"",$userlogin);
          }
          $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',(12*60*60)+1, 1*60*60*24,"",$userlogin);
          $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',0, 12*60*60,"",$userlogin);


          $datacontent['statistic']['read'] = array(
            "total"       => $statistic_read,
            "percent"     => ($statistic_read*100)/$statistic_visit
          );

          for($i=29;$i>0;$i--) {
            $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',($i*60*60*24)+1, ($i+1)*60*60*24,"",$userlogin);
          }
          $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',(12*60*60)+1, 1*60*60*24,"",$userlogin);
          $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',0, 12*60*60,"",$userlogin);

          $datacontent['statistic']['action'] = array(
            "total"       => $statistic_action,
            "percent"     => ($statistic_action*100)/$statistic_visit
          );

          for($i=29;$i>0;$i--) {
            $datacontent['chart']['action'][] = $this->campaign_tracker_model->getTransactionTotalInRange('action',($i*60*60*24)+1, ($i+1)*60*60*24,"",$userlogin);
          }
          $datacontent['chart']['action'][] = $this->campaign_tracker_model->getTransactionTotalInRange('action',(12*60*60)+1, 1*60*60*24,"",$userlogin);
          $datacontent['chart']['action'][] = $this->campaign_tracker_model->getTransactionTotalInRange('action',0, 12*60*60,"",$userlogin);

          $datacontent['statistic']['acquisition'] = array(
            "total"       => $statistic_acquisition,
            "percent"     => ($statistic_acquisition*100)/$statistic_visit
          );

          for($i=29;$i>0;$i--) {
            $datacontent['chart']['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRange('acquisition',($i*60*60*24)+1, ($i+1)*60*60*24,"",$userlogin);
          }
          $datacontent['chart']['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRange('acquisition',(12*60*60)+1, 1*60*60*24,"",$userlogin);
          $datacontent['chart']['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRange('acquisition',0, 12*60*60,"",$userlogin);
        }

        for($i=29;$i>=0;$i--) {
          $label_chart[] = ($i+1)." days ago";
        }
        $label_chart[] = "Under 12 hours";

        $datacontent['config']      = $this->config;
        $datacontent['previlege']   = $this->previlege_model;
        $datacontent['label_chart'] = $label_chart;

        $view     = View::make("backend.master.user.performance",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail pengguna",
          "description"   => $webname." | Detail pengguna",
          "keywords"      => $webname." | Detail pengguna"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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

  public function manage($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
        $detail = $this->user_model->getDetail($id);
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $detail,
          "datamethods"    => $this->method_model->getMethodsByUser($detail->id_department,$detail->id_user)
        );

        $view     = View::make("backend.master.user.manage",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | manage user restriction",
          "description"   => $webname." | manage user restriction",
          "keywords"      => $webname." | manage user restriction"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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

  public function updatemanage($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-user-manage")){
        $input = $request->all();

          $this->restriction_model->deleteRestriction($id);
          foreach($input as $key=>$val){
            if(substr($key,0,3)=="cb_"){
              $data = array(
                "id_method"     => $val,
                "id_user"       => $id
              );
              $this->restriction_model->createData($data);
            }
          }

          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menyimpan!']);
          return redirect(url('admin/user/manage/'.$id));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
        );
        $body = "backend/body";

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
      }
    }else{
      return redirect(url('login'));
    }
  }
}
