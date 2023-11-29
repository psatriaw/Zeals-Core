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

use App\Http\Models\AccountModel;
use App\Http\Models\UserModel;
use App\Http\Models\DepartmentModel;
use App\Http\Models\BankModel;

class AccountController extends Controller{
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

    $this->user_model       = new UserModel();
    $this->department_model = new DepartmentModel();
    $this->account_model    = new AccountModel();
    $this->bank_model       = new BankModel();
  }

  public function test(){
    $data = $this->account_model->getApplicantlist();
    dd($data);
  }

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-account-show")){
        $default    = array(
          "short"     => "account_start_time",
          "shortmode" => "asc"
        );
        $shorter = array(
          "account_start_time"      => "Tgl Aktif",
          "account_end_time"    => "Tgl Berakhir",
          "first_name"            => "Nama Depan",
          "last_name"             => "Nama Belakang",
          "username"              => "Username",
          "email"                 => "Email",
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;
        $data       = $this->account_model->getData($str, $limit, $keyword, $short, $shortmode);
        //dd($data);
        //exit;
        $totaldata  = $this->account_model->countData($keyword);
        $pagging    = $this->helper->showPagging($totaldata, url('admin/account/?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode), $position = "", $page, $limit , 2);

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "data"           => $data,
          "pagging"        => $pagging,
          "input"          => $request->all(),
          "default"        => $default,
          "shorter"        => $shorter,
          "page"           => $page,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.account.index",$datacontent);
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-user-create")){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "optdepartment"  => $this->department_model->getDepartmentOpt(),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-user-create")){
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
          "first_name.required" => "Mohon mengisi nama anda disini",
          "username.required"   => "Mohon mengisi username anda disini",
          "username.unique"     => "Usernam ini sudah pernah digunakan oleh akun lain, mohon menggunakan usernam lainnya",
          "email.required"      => "Mohon mengisi email anda yang aktif disini",
          "email.email"         => "Mohon memasukkan alamat email yang sesuai, contoh: contoh@domain",
          "email.unique"        => "Alamat email ini sudah pernah digunakan oleh akun lain, mohon menggunakan alamat email lainnya",
          "password.required"   => "Mohon mengisi password disini"
        );

    		$this->validate($request, $rules, $messages);

        $activation_code            = $this->user_model->createActivationCode();
        $data['activation_code']    = $activation_code;

        $createuser                 = $this->user_model->insertData($data);
        if($createuser){

          $dataemail                  = $data;
          $dataemail['activation_link'] = url('activate-account/'.$activation_code);

          Mail::send('emails.user_activation', $dataemail, function ($mail) use ($dataemail) {
            $this->settingmodel = new SettingModel();

            $sender             = $this->setting_model->getSettingVal("email_sender_name");
            $senderaddress      = $this->setting_model->getSettingVal("email_sender_address");

            $mail->from($senderaddress, $sender);
            $mail->to($dataemail['email'], $dataemail['first_name']);
            $mail->subject('Aktivasi Akun Uronshop Anda');
          });

          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah pengguna!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah pengguna']);
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-account-edit")){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "optdepartment"  => $this->department_model->getDepartmentOpt(),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->user_model->getDetail($id)
        );

        $view     = View::make("backend.master.user.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah akun layanan",
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

  public function setactive(Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-account-approve")){
        $id = $request->input("id");
        $detail = $this->account_model->getDetail($id);

        $data = array(
          "id_account"  => $id,
          "status"      => "active",
          "last_update" => time()
        );

        $update                 = $this->account_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses membuat akun menjadi aktif!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengaktivasi akun']);
        }
        return redirect(url('admin/account'));
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

  public function setinactive(Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-account-approve")){
        $id = $request->input("id");
        $detail = $this->account_model->getDetail($id);

        $data = array(
          "id_account"  => $id,
          "status"      => "inactive",
          "last_update" => time()
        );

        $update                 = $this->account_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses membuat akun menjadi tidak aktif!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat membuat akun menjadi tidak aktif']);
        }
        return redirect(url('admin/account'));
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-account-remove")){
        $id = $request->input("id");
        $data = array(
          "id_account"  => $id,
          "status"      => "deleted",
          "last_update" => time()
        );
        $remove = $this->user_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus akun']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus akun']);
        }
        return redirect(url('admin/account'));
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-user-show")){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "optdepartment"  => $this->department_model->getDepartmentOpt(),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->account_model->getDetail($id),
          "databank"       => $this->bank_model->getBankList()
        );

        $view     = View::make("backend.master.account.detail",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail akun",
          "description"   => $webname." | Detail akun",
          "keywords"      => $webname." | Detail akun"
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
}
