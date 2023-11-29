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

use App\Http\Models\UserModel;
use App\Http\Models\MaterialDistributionModel;
use App\Http\Models\MaterialModel;

class DistribusiController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;
  private $config;

  private $user_model;
  private $distribusi_model;
  private $material_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $config['main_url'] = "master/distribusi";
    $config['create']   = "dist-create";
    $config['view']     = "dist-view";
    $config['remove']   = "dist-remove";

    $this->config       = $config;

    $this->user_model          = new UserModel();
    $this->distribusi_model    = new MaterialDistributionModel();
    $this->material_model      = new MaterialModel();
  }

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $default    = array(
          "short"     => "last_update",
          "shortmode" => "desc"
        );
        $shorter = array(
          "material_name"     => "Nama Bahan Baku",
          "quantity"          => "Quantity",
          "time_created"      => "Tgl Daftar",
          "last_update"       => "Tgl Diperbarui"
        );

        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;
        $data       = $this->distribusi_model->getDataAll($str, $limit, $keyword, $short, $shortmode,$login,"",true);

        $totaldata  = $this->distribusi_model->countData($keyword);
        $pagging    = $this->helper->showPagging($totaldata, url('master/distribusi/?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode), $position = "", $page, $limit , 2);

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
          "limit"          => $limit,
          "config"         => $this->config,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.distribusi.index",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Distribusi Bahan baku",
          "description"   => $webname." | Distribusi Bahan baku",
          "keywords"      => $webname." | Distribusi Bahan baku"
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
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.distribusi.create",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah Loyang",
          "description"   => $webname." | Tambah Loyang",
          "keywords"      => $webname." | Tambah Loyang"
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
          "id_purchase_detail"  => $request->input("id_purchase_detail"),
          "quantity"            => $request->input("quantity"),
          "time_created"        => time(),
          "last_update"         => time(),
          "author"              => $login->id_user,
          "status"              => "active",
          "id_mitra"            => $request->input("id_mitra"),
          "distribution_date"   => $request->input("distribution_date")
        );

        $stock = $this->material_model->getStockItem($data['id_purchase_detail']);
        if($data['quantity']>$stock){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Quantity tidak boleh melebihi stock']);
          return redirect(url($this->config['main_url']."/create"));
        }else{
          $create                 = $this->distribusi_model->insertData($data);
          if($create){
            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Distribusi berhasil dicatat!']);
          }else{
            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mencatat distribusi']);
          }
          return redirect(url($this->config['main_url']));
        }
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

  public function mrp($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['mrp'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->cetakan_model->getDetail($id),
          "list"           => $this->cetakan_model->getlistmrpcetakan($id)
        );
        //dd($data);

        $view     = View::make("backend.master.cetakan.mrp.index",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah produk",
          "description"   => $webname." | Ubah produk",
          "keywords"      => $webname." | Ubah produk"
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
        $categoryproducts = $this->category_model->getList();
        if($categoryproducts){
            foreach ($categoryproducts as $key => $value) {
              $categories[$key] = $value;
            }
        }

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->cetakan_model->getDetail($id),
          "photos"         => $this->cetakan_model->getPhotos($id),
          "categories"     => $categories
        );

        $data = $this->cetakan_model->getPhotos($id);
        //dd($data);

        $view     = View::make("backend.master.cetakan.manage",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah produk",
          "description"   => $webname." | Ubah produk",
          "keywords"      => $webname." | Ubah produk"
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
        $categoryproducts = $this->category_model->getList();
        if($categoryproducts){
            foreach ($categoryproducts as $key => $value) {
              $categories[$key] = $value;
            }
        }

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->cetakan_model->getDetail($id),
          "categories"     => $categories
        );

        $view     = View::make("backend.master.cetakan.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah produk",
          "description"   => $webname." | Ubah produk",
          "keywords"      => $webname." | Ubah produk"
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
          "id_cetakan"          => $id,
          "cetakan_name"        => $request->input("cetakan_name"),
          "cetakan_code"        => $request->input("cetakan_code"),
          "status"              => $request->input("status"),
          "last_update"         => time(),
          "author"              => $login->id_user
        );

        $rules  = array(
          "cetakan_name"        => "required",
          "cetakan_code"        => "required|unique:tb_brownies_cetakan,cetakan_code,".$id.",id_cetakan"
    		);

    		$messages = array(
          "cetakan_name.required"   => "Mohon mengisi nama loyang disini",
          "cetakan_code.required"   => "Mohon mengisi Kode loyang disini",
          "cetakan_code.unique"     => "Kode loyang sudah digunakan oleh produk lain"
        );

    		$this->validate($request, $rules, $messages);

        $update                 = $this->cetakan_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data']);
        }
        return redirect(url($this->config['main_url'].'/edit/'.$id));
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

  public function removemrp(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['remove']) && $this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['mrp'])){
        $id         = $request->input("id");
        $id_parent  = $request->input("parent_id");

        $data = array(
          "id_cetakan_mrp"      => $id,
          "status"              => "removed",
          "last_update"         => time()
        );
        $remove = $this->cetakan_mrp_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data']);
        }
        return redirect(url($this->config['main_url'].'/mrp/'.$id_parent));
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
        $detail = $this->cetakan_model->getDetail($id);
        $data = array(
          "id_cetakan"          => $id,
          "status"              => "removed",
          "last_update"         => time()
        );
        $remove = $this->cetakan_model->updateData($data);

        if($remove){
          $undolink = url('master/product/restore/'.$id);
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data</a>']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data']);
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

  public function restore($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['restore'])){
        $detail = $this->cetakan_model->getDetail($id);
        $data = array(
          "id_product"            => $id,
          "status"                => "available",
          "last_update"           => time()
        );
        $remove = $this->cetakan_model->updateData($data);

        if($remove){
          $undolink = url('admin/service/restore/'.$id);
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengembalikan data <strong>'.$detail->product_name.'</strong>']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengembalikan data ']);
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
        $categoryproducts = $this->category_model->getList();
        if($categoryproducts){
            foreach ($categoryproducts as $key => $value) {
              $categories[$key] = $value;
            }
        }
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->cetakan_model->getDetail($id),
          "categories"     => $categories
        );

        $view     = View::make("backend.master.cetakan.detail",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail Loyang",
          "description"   => $webname." | Detail Loyang",
          "keywords"      => $webname." | Detail Loyang"
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

  public function loadcropper(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
        return view("backend.master.cetakan.photocropper",$request);
      }
    }
  }

  public function doupload(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
        $uploadedFile = $request->file('file');
        $path         = public_path('upload');
        $filename     = time()."_".$uploadedFile->getClientOriginalName();
        $file         = $uploadedFile->move($path, $filename);

        if($file){
          $width    = 200;
          $height   = 200;

          list($iwidth, $iheight) = getimagesize("public/upload/".$filename);
          if($iwidth>$iheight){
            //orientasi landscape
            $cropwidth  = $iheight;
            $cropheight = $iheight;
          }else{
            $cropwidth  = $iwidth;
            $cropheight = $iwidth;
          }

          $x  = ($iwidth - $cropwidth)/2;
          $y  = ($iheight - $cropheight)/2;

          Image::make("public/upload/".$filename)->crop((int)$cropwidth, (int)$cropheight, (int)$x, (int)$y)->resize($width, $height)->save( public_path("upload/thumbnail_".$filename ) );

          $dataphoto = array(
            "thumbnail"     => "public/upload/thumbnail_".$filename,
            "path"          => "public/upload/".$filename,
            "time_created"  => time(),
            "status"        => "active",
            "author"        => $login->id_user,
            "id_reff"       => $request->id_product
          );

          $photo = $this->photo_model->insertData($dataphoto);

          $dataphotoproduct = array(
            "type"          => "photo",
            "value"         => $photo,
            "description"   => "",
            "time_created"  => time(),
            "status"        => "active",
            "id_product"    => $request->id_product
          );

          $detail = $this->productdetail_model->insertData($dataphotoproduct);

          $return = array(
            "status"      => "success",
            "text"        => "Berhasil",
            "path"        => url("public/upload/".$filename),
            "thumbnail"   => url("public/upload/thumbnail_".$filename),
            "id_photo"    => $photo,
            "id_relation" => $detail
          );
        }else{
          $return = array(
            "status"  => "error",
            "text"    => "gagal mengunggah!"
          );
        }
      }else{
        $return = array(
          "status"  => "error_auth",
          "text"    => "Akun anda tidak diperbolehkan mengunggah!"
        );
      }
    }else{
      $return = array(
        "status"  => "error_login",
        "text"    => "Akun anda tidak diperbolehkan mengunggah!"
      );
    }
    return response()->json($return, 200);
  }

  public function removephoto(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
        $id           = $request->input("id");
        $detail = $this->productdetail_model->getDetail($id);

        $id_product   = $detail->id_product;

        $data = array(
          "id_product_detail"   => $id,
          "status"              => "deleted",
          "last_update"         => time()
        );
        $remove = $this->productdetail_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus foto']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus foto']);
        }
        return redirect(url($this->config['main_url'].'/manage/'.$id_product));
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

  public function removephotoall(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
        $id           = $request->input("id");
        $detail       = $this->cetakan_model->getDetail($id);
        $id_product   = $detail->id_product;
        $photos       = $this->cetakan_model->getPhotos($id);

        if($photos){
          foreach ($photos as $key => $value) {
            $data = array(
              "id_product_detail"   => $value->id_product_detail,
              "status"              => "deleted",
              "last_update"         => time()
            );
            $remove = $this->productdetail_model->updateData($data);
          }
        }

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus seluruh foto']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus seluruh foto']);
        }
        return redirect(url($this->config['main_url'].'/manage/'.$id_product));
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
