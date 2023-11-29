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

use App\Http\Models\PekerjaanModel;
use App\Http\Models\UserModel;
use App\Http\Models\MitraMatriksModel;

class PekerjaanController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $pekerjaan_model;
  private $user_model;
  protected $config;
  protected $shorter;
  protected $statuses;
  protected $default;
  protected $labels;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->user_model       = new UserModel();
    $this->pekerjaan_model  = new PekerjaanModel();

    $config['main_url'] = "admin/perkawinan";
    $config['edit']     = "perkawinan-edit";
    $config['view']     = "perkawinan-view";
    $config['remove']   = "perkawinan-remove";
    $config['restore']  = "perkawinan-restore";
    $config['create']   = "perkawinan-create";
    $this->config       = $config;

    $shorter = array(
      "jumlah_pakerja"  => "Jumlah Penduduk",
      "pekerjaan"       => "Status Perkawinan",
      "time_created"    => "Tgl Daftar",
      "last_update"     => "Terakhir Diperbarui"
    );
    $this->shorter    = $shorter;

    $statuses = array(
      "semua"     => "Semua",
      "active"    => "Aktif",
      "inactive"  => "Tidak Aktif",
      "ditolak"   => "Ditolak"
    );
    $this->statuses = $statuses;

    $default    = array(
      "short"     => "time_created",
      "shortmode" => "desc",
      "status"    => "semua"
    );
    $this->default = $default;

    $labels = array(
      "ID"   => array(
        "index"    => array(
          "title"  => "Pernikahan"
        )
      );
    );

    $this->language   = "ID";
    $this->labels     = $labels;
  }

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){

        $status     = ($request->input("status")=="")?$default['status']:$request->input("status");
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;

        $data       = $this->pekerjaan_model->getData($str, $limit, $keyword, $short, $shortmode,$status);

        $totaldata  = $this->pekerjaan_model->countData($keyword,$status);

        $pagging    = $this->helper->showPagging($totaldata, url($this->config['main_url'].'?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode."&status=".$status), $position = "", $page, $limit , 2);

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "data"           => $data,
          "pagging"        => $pagging,
          "config"         => $this->config,
          "input"          => $request->all(),
          "default"        => $this->default,
          "shorter"        => $this->shorter,
          "statuses"       => $this->statuses,
          "page"           => $page,
          "labels"         => $this->labels[$this->language],
          "limit"          => $limit,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.pekerjaan.index",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Manajemen Pekerjaan",
          "description"   => $webname." | Manajemen Pekerjaan",
          "keywords"      => $webname." | Manajemen Pekerjaan"
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
          "previlege"      => $this->previlege_model,
          "config"         => $this->config,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
        );

        $view     = View::make("backend.master.pekerjaan.create",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah Data Pekerjaan",
          "description"   => $webname." | Tambah Data Pekerjaan",
          "keywords"      => $webname." | Tambah Data Pekerjaan"
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
          "pekerjaan"      => $request->input("pekerjaan"),
          "id_reff"        => $request->input("id_reff"),
          "status"          => "active",
          "time_created"    => time(),
          "last_update"     => time()
        );

        $rules  = array(
          "pekerjaan"      => "required"
    		);
    		$messages = array(
          "pekerjaan.required" => "Mohon mengisi pekerjaan anda disini",
        );

    		$this->validate($request, $rules, $messages);

        $create                 = $this->pekerjaan_model->insertData($data);
        if($create){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah data!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal menambah data!']);
        }

        return redirect(url($this->config['main_url']));
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
          "previlege"      => $this->previlege_model,
          "config"         => $this->config,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"            => $this->pekerjaan_model->getDetail($id)
        );

        $view     = View::make("backend.master.pekerjaan.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah Data Pekerjaan",
          "description"   => $webname." | Ubah Data Pekerjaan",
          "keywords"      => $webname." | Ubah Data Pekerjaan"
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){
        $data = array(
          "id_pekerjaan"    => $id,
          "pekerjaan"       => $request->input("pekerjaan"),
          "id_reff"         => $request->input("id_reff"),
          "status"          => $request->input("status"),
          "last_update"     => time()
        );

        $rules  = array(
          "pekerjaan"      => "required",
    		);
    		$messages = array(
          "pekerjaan.required" => "Mohon mengisi pekerjaan anda disini"
        );

    		$this->validate($request, $rules, $messages);

        $updatedata                 = $this->pekerjaan_model->updateData($data);
        if($updatedata){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal mengubah data!']);
        }

        return redirect(url($this->config['main_url']."/edit/".$id));
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
    $id       = $request->input("id");
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['remove'])){
        $detail   = $this->pekerjaan_model->getDetail($id);
        $detail   = $detail->toArray();

        $data['id_pekerjaan']     = $id;
        $data['status']           = "deleted";
        $data['last_update']      = time();

        $delete                 = $this->pekerjaan_model->updateData($data);
        if($delete){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal menghapus data!']);
        }

        return redirect(url($this->config['main_url']));
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
        $detail   = $this->pekerjaan_model->getDetail($id);
        //exit;
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"            => $detail,
          "config"          => $this->config
        );

        $view     = View::make("backend.master.pekerjaan.detail",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail data Pekerjaan",
          "description"   => $webname." | Detail data Pekerjaan",
          "keywords"      => $webname." | Detail data Pekerjaan"
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
