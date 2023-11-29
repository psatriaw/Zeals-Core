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

class ShopController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $user_model;
  private $shop_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->shop_model       = new ShopModel();
    $this->user_model       = new UserModel();

  }

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-shop-show")){
        $default    = array(
          "short"     => "shop_name",
          "shortmode" => "asc"
        );
        $shorter = array(
          "shop_name"             => "Nama Toko",
          "first_name"            => "Nama Depan Pemilik",
          "shortlink"             => "URL Toko",
          "balance"               => "Saldo",
          "tb_shop.time_created"  => "Tgl Terdaftar"
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;
        $data       = $this->shop_model->getData($str, $limit, $keyword, $short, $shortmode);

        $totaldata  = $this->shop_model->countData($keyword);
        $pagging    = $this->helper->showPagging($totaldata, url('admin/shop/?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode), $position = "", $page, $limit , 2);

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

        $view     = View::make("backend.master.shop.index",$datacontent);
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-user-edit")){
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

  public function update($id, Request $request){
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
          "last_update"       => time(),
        );

        $rules  = array(
          "first_name"      => "required",
          "username"        => "required|unique:tb_user,username,".$id.",id_user",
          "email"           => "required|email|unique:tb_user,email,".$id.",id_user"
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

        if($request->input("password")!=""){
          $data['password']   = md5($request->input("password"));
        }

    		$this->validate($request, $rules, $messages);

        $update                 = $this->user_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data pengguna!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data pengguna']);
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-user-remove")){
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-user-show")){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "optdepartment"  => $this->department_model->getDepartmentOpt(),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->user_model->getDetail($id)
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
}
