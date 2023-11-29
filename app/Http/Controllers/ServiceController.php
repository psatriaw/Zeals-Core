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

use App\Http\Models\ServiceModel;
use App\Http\Models\UserModel;

class ServiceController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $user_model;
  private $service_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->service_model    = new ServiceModel();
    $this->user_model       = new UserModel();

  }

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-service-show")){
        $default    = array(
          "short"     => "service_name",
          "shortmode" => "asc"
        );
        $shorter = array(
          "service_name"    => "Nama Layanan",
          "description"     => "Deskripsi",
          "price"           => "Harga",
          "code"            => "Kode Layanan",
          "time_created"    => "Tgl Daftar",
          "last_update"     => "Tgl Diperbarui"
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;
        $data       = $this->service_model->getData($str, $limit, $keyword, $short, $shortmode);

        $totaldata  = $this->service_model->countData($keyword);
        $pagging    = $this->helper->showPagging($totaldata, url('admin/service/?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode), $position = "", $page, $limit , 2);

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

        $view     = View::make("backend.master.service.index",$datacontent);
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
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.service.create",$datacontent);
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
          "service_name"      => $request->input("service_name"),
          "description"       => $request->input("description"),
          "status"            => $request->input("status"),
          "price"             => $request->input("price"),
          "code"              => $request->input("code"),
          "expiration"        => $request->input("expiration"),
          "time_created"      => time(),
          "last_update"       => time(),
          "type"              => $request->input("type"),
        );

        $rules  = array(
          "service_name"      => "required",
          "price"             => "required|numeric",
          "expiration"        => "required|numeric",
          "code"              => "required|unique:tb_service,code"
    		);

    		$messages = array(
          "service_name.required" => "Mohon mengisi nama layanan disini",
          "price.required"        => "Mohon mengisi harga layanan disini",
          "price.numeric"         => "Harga layanan harus berupa angka",
          "expiration.required"   => "Mohon mengisi masa layanan disini",
          "expiration.numeric"    => "Masa layanan harus berupa angka",
          "code.required"         => "Mohon mengisi kode layanan",
          "code.unique"           => "Kode sudah pernah digunakan, mohon menggunakan kode yang lain",
        );

    		$this->validate($request, $rules, $messages);

        $create                 = $this->service_model->insertData($data);
        if($create){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah layanan!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah layanan']);
        }
        return redirect(url('admin/service/create'));
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-service-edit")){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->service_model->getDetail($id)
        );

        $view     = View::make("backend.master.service.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah layanan",
          "description"   => $webname." | Ubah layanan",
          "keywords"      => $webname." | Ubah layanan"
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
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-service-edit")){
        $detail = $this->user_model->getDetail($id);

        $data = array(
          "id_service"        => $id,
          "service_name"      => $request->input("service_name"),
          "description"       => $request->input("description"),
          "status"            => $request->input("status"),
          "price"             => $request->input("price"),
          "code"              => $request->input("code"),
          "expiration"        => $request->input("expiration"),
          "last_update"       => time(),
          "type"              => $request->input("type"),
        );

        $rules  = array(
          "service_name"      => "required",
          "price"             => "required|numeric",
          "expiration"        => "required|numeric",
          "code"              => "required|unique:tb_service,code,".$id.",id_service"
    		);

    		$messages = array(
          "service_name.required" => "Mohon mengisi nama layanan disini",
          "price.required"        => "Mohon mengisi harga layanan disini",
          "price.numeric"         => "Harga layanan harus berupa angka",
          "expiration.required"   => "Mohon mengisi masa layanan disini",
          "expiration.numeric"    => "Masa layanan harus berupa angka",
          "code.required"         => "Mohon mengisi kode layanan",
          "code.unique"           => "Kode sudah pernah digunakan, mohon menggunakan kode yang lain",
        );

    		$this->validate($request, $rules, $messages);

        $update                 = $this->service_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data layanan!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data layanan']);
        }
        return redirect(url('admin/service/edit/'.$id));
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-service-remove")){
        $id = $request->input("id");
        $detail = $this->service_model->getDetail($id);
        $data = array(
          "id_service"  => $id,
          "status"      => "deleted",
          "last_update" => time()
        );
        $remove = $this->service_model->updateData($data);

        if($remove){
          $undolink = url('admin/service/restore/'.$id);
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data layanan <strong>'.$detail->service_name.'</strong>! klik disini untuk <a href="'.$undolink.'" class="text-danger"><strong>kembalikan</strong></a>']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data layanan']);
        }
        return redirect(url('admin/service'));
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

  public function restore($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-service-restore")){
        $detail = $this->service_model->getDetail($id);
        $data = array(
          "id_service"  => $id,
          "status"      => "active",
          "last_update" => time()
        );
        $remove = $this->service_model->updateData($data);

        if($remove){
          $undolink = url('admin/service/restore/'.$id);
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengembalikan data layanan <strong>'.$detail->service_name.'</strong>!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengembalikan data layanan']);
        }
        return redirect(url('admin/service'));
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-service-show")){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->service_model->getDetail($id)
        );

        $view     = View::make("backend.master.service.detail",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail layanan",
          "description"   => $webname." | Detail layanan",
          "keywords"      => $webname." | Detail layanan"
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
