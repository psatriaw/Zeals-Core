<?php

namespace App\Http\Controllers\apis;

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

use App\Http\Models\KelurahanModel;
use App\Http\Models\WilayahModel;
use App\Http\Models\UserModel;

class WilayahController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $user_model;
  private $wilayah_model;
  private $kelurahan_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->wilayah_model    = new WilayahModel();
    $this->user_model       = new UserModel();
    $this->kelurahan_model  = new KelurahanModel();

    $dataconfig['main_url'] = "master/wilayah";
    $dataconfig['view']     = "wilayah-view";
    $dataconfig['create']   = "wilayah-create";
    $dataconfig['edit']     = "wilayah-edit";
    $dataconfig['remove']   = "wilayah-remove";
    $dataconfig['manage']   = "wilayah-manage";

    $this->config           = $dataconfig;
  }

  public function get(){
    $data = WilayahModel::orderBy("namaprov","asc")->orderBy("namakab","asc")->get();
    if($data){
        return response()->json($data, 200);
    }
  }

  public function findKecamatan(Request $request){
    $data = $this->wilayah_model->findKecamatan($request->search);
    if($data){
        foreach ($data as $key => $value) {
            $dataret[] = array(
                "id"    => $value->id_kecamatan,
                "text"  => $value->nama_kecamatan
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

  public function getlistkelurahanbykecamatan(Request $request){
    $id_kecamatan = $request->input("kecamatan");
    $data     = $this->kelurahan_model->getlistkelurahanbykec($id_kecamatan);
    if($data->count()){
      $html = "";
      foreach ($data as $key => $value) {
        $html = $html."<option value='".$value->id_kelurahan."'>".$value->nama_kelurahan."</option>";
      }

      $return = array(
        "status"  => "success",
        "html"    => $html
      );
    }else{
      $html = "<option value='*'>Tidak ada data kelurahan. Mohon ditambahkan terlebih dahulu pada datar kelurahan</option>";

      $return = array(
        "status"  => "error",
        "html"    => $html
      );
    }

    return response()->json($return,200);
  }

  public function indexkelurahan(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $default    = array(
          "short"     => "nama_kecamatan",
          "shortmode" => "asc"
        );
        $shorter = array(
          "nama_kecamatan"      => "Nama Kecamatan",
          "id_reff"             => "Kode Kelurahan",
          "nama_kelurahan"      => "Nama Kelurahan",
          "time_created"        => "Tgl Terdaftar",
          "last_update"         => "Terakhir Diperbarui"
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;
        $data       = $this->kelurahan_model->getDataKelurahan($str, $limit, $keyword, $short, $shortmode);

        //exit();
        $totaldata  = $this->kelurahan_model->countDataKelurahan($keyword);
        $pagging    = $this->helper->showPagging($totaldata, url('master/kelurahan/?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode), $position = "", $page, $limit , 2);

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "data"           => $data,
          "pagging"        => $pagging,
          "config"         => $this->config,
          "input"          => $request->all(),
          "default"        => $default,
          "shorter"        => $shorter,
          "page"           => $page,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.wilayah.kelurahan.index",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Manajemen Kelurahan",
          "description"   => $webname." | Manajemen Kelurahan",
          "keywords"      => $webname." | Manajemen Kelurahan"
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

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $default    = array(
          "short"     => "nama_kecamatan",
          "shortmode" => "asc"
        );
        $shorter = array(
          "nama_kecamatan"      => "Nama Kecamatan",
          "total_kelurahan"     => "Banyak Kelurahan",
          "time_created"        => "Tgl Terdaftar",
          "last_update"         => "Terakhir Diperbarui"
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;
        $data       = $this->wilayah_model->getData($str, $limit, $keyword, $short, $shortmode);

        //exit();
        $totaldata  = $this->wilayah_model->countData($keyword);
        $pagging    = $this->helper->showPagging($totaldata, url($this->config['main_url'].'?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode), $position = "", $page, $limit , 2);

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "data"           => $data,
          "pagging"        => $pagging,
          "input"          => $request->all(),
          "default"        => $default,
          "config"         => $this->config,
          "shorter"        => $shorter,
          "page"           => $page,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.wilayah.index",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." |  Manajemen Kecamatan",
          "description"   => $webname." |  Manajemen Kecamatan",
          "keywords"      => $webname." |  Manajemen Kecamatan"
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

  public function createkelurahan($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "data_parent"    => $this->wilayah_model->getDetail($id),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "id"             => $id
        );

        $view     = View::make("backend.master.wilayah.kelurahan.create",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah Data Kelurahan",
          "description"   => $webname." | Tambah Data Kelurahan",
          "keywords"      => $webname." | Tambah Data Kelurahan"
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

  public function createkelurahanitself(){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
        );

        $view     = View::make("backend.master.wilayah.kelurahan.create_self",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah Data Kelurahan",
          "description"   => $webname." | Tambah Data Kelurahan",
          "keywords"      => $webname." | Tambah Data Kelurahan"
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
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.wilayah.create",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah Data Kecamatan",
          "description"   => $webname." | Tambah Data Kecamatan",
          "keywords"      => $webname." | Tambah Data Kecamatan"
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

  public function storekelurahan($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $data = array(
          "nama_kelurahan"    => $request->input("nama_kelurahan"),
          "id_kecamatan"      => $id,
          "time_created"      => time(),
          "last_update"       => time(),
          "author"            => $login->id_user,
          "status"            => $request->input("status"),
        );

        $rules  = array(
          "nama_kelurahan"    => "required",
    		);

    		$messages = array(
          "nama_kelurahan.required" => "Mohon mengisi nama kelurahan disini",
        );

    		$this->validate($request, $rules, $messages);

        $createuser                 = $this->kelurahan_model->insertData($data);
        if($createuser){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah kelurahan!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah kelurahan']);
        }
        return redirect(url('master/wilayah/manage/'.$id));
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

  public function storekelurahanitself(Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $data = array(
          "nama_kelurahan"    => $request->input("nama_kelurahan"),
          "id_kecamatan"      => $request->input("id_kecamatan"),
          "id_reff"           => $request->input("id_reff"),
          "time_created"      => time(),
          "last_update"       => time(),
          "author"            => $login->id_user,
          "status"            => $request->input("status"),
        );

        $rules  = array(
          "nama_kelurahan"    => "required",
          "id_kecamatan"      => "required",
          "id_reff"           => "required",
    		);

    		$messages = array(
          "nama_kelurahan.required" => "Mohon mengisi nama kelurahan disini",
          "id_kecamatan.required"   => "Mohon mengisi kecamatan disini",
          "id_reff.required"        => "Mohon mengisi kode kelurahan disini",
        );

    		$this->validate($request, $rules, $messages);

        $createuser                 = $this->kelurahan_model->insertData($data);
        if($createuser){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah data!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah data']);
        }
        return redirect(url('master/kelurahan/create'));
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

  public function store(Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $data = array(
          "nama_kecamatan"    => $request->input("nama_kecamatan"),
          "time_created"      => time(),
          "last_update"       => time(),
          "author"            => $login->id_user,
          "status"            => $request->input("status"),
        );

        $rules  = array(
          "nama_kecamatan"    => "required",
    		);

    		$messages = array(
          "nama_kecamatan.required" => "Mohon mengisi nama kecamatan disini",
        );

    		$this->validate($request, $rules, $messages);

        $createuser                 = $this->wilayah_model->insertData($data);
        if($createuser){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah kecamatan!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah kecamatan']);
        }
        return redirect(url('master/wilayah/create'));
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

  public function manage($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->wilayah_model->getDetail($id),
          "list"           => $this->kelurahan_model->getData($id)
        );

        $view     = View::make("backend.master.wilayah.manage",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Manajemen Data Kelurahan",
          "description"   => $webname." | Manajemen Data Kelurahan",
          "keywords"      => $webname." | Manajemen Data Kelurahan"
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

  public function editkelurahan($id, $id_parent){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "id"             => $id_parent,
          "data_parent"    => $this->wilayah_model->getDetail($id_parent),
          "data"           => $this->kelurahan_model->getDetail($id)
        );

        $view     = View::make("backend.master.wilayah.kelurahan.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah Data Kelurahan",
          "description"   => $webname." | Ubah Data Kelurahan",
          "keywords"      => $webname." | Ubah Data Kelurahan"
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

  public function editkelurahanselft($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->kelurahan_model->getDetail($id)
        );

        $view     = View::make("backend.master.wilayah.kelurahan.edit_self",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah Data Kelurahan",
          "description"   => $webname." | Ubah Data Kelurahan",
          "keywords"      => $webname." | Ubah Data Kelurahan"
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

  public function edit($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->wilayah_model->getDetail($id)
        );

        $view     = View::make("backend.master.wilayah.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah Data Kecamatan",
          "description"   => $webname." | Ubah Data Kecamatan",
          "keywords"      => $webname." | Ubah Data Kecamatan"
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

  public function updatekelurahan($id, $id_parent, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){
        $detail = $this->wilayah_model->getDetail($id);

        $data = array(
          "id_kelurahan"      => $id,
          "nama_kelurahan"    => $request->input("nama_kelurahan"),
          "last_update"       => time(),
          "author"            => $login->id_user,
          "status"            => $request->input("status"),
        );

        $rules  = array(
          "nama_kelurahan"    => "required",
    		);

    		$messages = array(
          "nama_kelurahan.required" => "Mohon mengisi nama kelurahan disini",
        );

    		$this->validate($request, $rules, $messages);

        $update                 = $this->kelurahan_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data kelurahan!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data kelurahan']);
        }
        return redirect(url('master/wilayah/kelurahan/edit/'.$id.'/'.$id_parent));
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

  public function updatekelurahanitself($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){
        $detail = $this->wilayah_model->getDetail($id);

        $data = array(
          "id_kecamatan"      => $request->input("id_kecamatan"),
          "id_kelurahan"      => $id,
          "nama_kelurahan"    => $request->input("nama_kelurahan"),
          "id_reff"           => $request->input("id_reff"),
          "last_update"       => time(),
          "author"            => $login->id_user,
          "status"            => $request->input("status"),
        );

        $rules  = array(
          "nama_kelurahan"    => "required",
          "id_kecamatan"      => "required",
    		);

    		$messages = array(
          "nama_kelurahan.required" => "Mohon mengisi nama kelurahan disini",
          "id_kecamatan.required"   => "Mohon mengisi kecamatan disini",
        );

    		$this->validate($request, $rules, $messages);

        $update                 = $this->kelurahan_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data!']);
        }
        return redirect(url('master/kelurahan/edit/'.$id));
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

  public function update($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){
        $detail = $this->wilayah_model->getDetail($id);

        $data = array(
          "id_kecamatan"      => $id,
          "nama_kecamatan"    => $request->input("nama_kecamatan"),
          "last_update"       => time(),
          "author"            => $login->id_user,
          "status"            => $request->input("status"),
        );

        $rules  = array(
          "nama_kecamatan"    => "required",
    		);

    		$messages = array(
          "nama_kecamatan.required" => "Mohon mengisi nama kecamatan disini",
        );

    		$this->validate($request, $rules, $messages);

        $update                 = $this->wilayah_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data kecamatan!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data kecamatan']);
        }
        return redirect(url('master/wilayah/edit/'.$id));
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

  public function removekelurahan(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['remove'])){
        $id         = $request->input("id");
        $id_parent  = $request->input("id_parent");

        $detail = $this->user_model->getDetail($id);
        $data = array(
          "id_kelurahan"      => $id,
          "status"            => "deleted",
          "last_update"       => time()
        );
        $remove = $this->kelurahan_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data kelurahan!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data kelurahan']);
        }
        return redirect(url('master/wilayah/manage/'.$id_parent));
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
          "id_kecamatan"      => $id,
          "status"            => "deleted",
          "last_update"       => time()
        );
        $remove = $this->wilayah_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data kecamatan!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data kecamatan']);
        }
        return redirect(url('master/wilayah'));
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
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->wilayah_model->getDetail($id)
        );

        $view     = View::make("backend.master.wilayah.detail",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail Data Kecamatan",
          "description"   => $webname." | Detail Data Kecamatan",
          "keywords"      => $webname." | Detail Data Kecamatan"
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
