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

use App\Http\Models\BrandModel;

class BrandController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $brand_model;
  private $config;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->brand_model      = new BrandModel();
    $this->config           = array(
      "main_url"    => "admin/brand",
      "view"        => "brand-view",
      "edit"        => "brand-edit",
      "create"      => "brand-create",
      "remove"      => "brand-remove",
      "detail"      => "brand-view",
      "restore"     => "brand-restore",
      );
  }

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $default    = array(
          "short"     => "brand_name",
          "shortmode" => "asc"
        );
        $shorter = array(
          "brand_name"        => "Brand name",
          "brand_code"        => "Brand code",
          "time_created"      => "Time created",
          "last_update"       => "Last update"
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;
        $data       = $this->brand_model->getData($str, $limit, $keyword, $short, $shortmode);

        $totaldata  = $this->brand_model->countData($keyword);
        $pagging    = $this->helper->showPagging($totaldata, url($this->config['main_url'].'/?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode), $position = "", $page, $limit , 2);

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
          "config"         => $this->config,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.brand.index",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Daftar Kategori",
          "description"   => $webname." | Daftar Kategori",
          "keywords"      => $webname." | Daftar Kategori"
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
        $parents = array("0" => "Tidak menggunakan parent");

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "config"         => $this->config
        );

        $view     = View::make("backend.master.brand.create",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah kategori",
          "description"   => $webname." | Tambah kategori",
          "keywords"      => $webname." | Tambah kategori"
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
          "brand_name"        => $request->input("brand_name"),
          "brand_code"        => $request->input("brand_code"),
          "brand_permalink"   => $request->input("brand_permalink"),
          "status"            => $request->input("status"),
          "brand_avatar"      => $request->input("brand_avatar"),
          "banner"            => "",
          "time_created"      => time(),
          "last_update"       => time(),
        );

        $rules  = array(
          "brand_name"        => "required",
          "brand_avatar"      => "required",
          "brand_code"        => "required|unique:product_category,code"
    		);

    		$messages = array(
          "brand_name.required"       => "Please fill brand name here",
          "brand_code.required"       => "Please fill brand code here",
          "brand_code.unique"         => "Brand code already used by another category, please try different one"
        );

        $this->validate($request, $rules, $messages);
        
        if($request->hasFile('brand_avatar')){
            $uploadedFile = $request->file('brand_avatar');
            $path         = public_path('brands');
            $filename     = time()."_".$uploadedFile->getClientOriginalName();
            $file         = $uploadedFile->move($path, $filename);
            $data['brand_avatar'] = url("public/brands/".$filename);
        }

        $create                 = $this->brand_model->insertData($data);
        if($create){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully created!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to create data']);
        }
        return redirect(url($this->config['main_url'].'/create'));
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
        $parents = array("0" => "Tidak menggunakan parent");

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->brand_model->getDetail($id),
          "config"         => $this->config
        );

        $view     = View::make("backend.master.brand.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah kategori",
          "description"   => $webname." | Ubah kategori",
          "keywords"      => $webname." | Ubah kategori"
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
        $detail = $this->brand_model->getDetail($id);
        $data = array(
          "id_brand"          => $id,
          "brand_name"        => $request->input("brand_name"),
          "brand_code"        => $request->input("brand_code"),
          "brand_permalink"   => $request->input("brand_permalink"),
          "brand_avatar"      => $request->input("brand_avatar"),
          "status"            => $request->input("status"),
          "banner"            => "",
          "last_update"       => time(),
        );

        $rules  = array(
          "brand_name"        => "required",
          "brand_code"        => "required|unique:product_category,code"
        );

        $messages = array(
          "brand_name.required"       => "Please fill brand name here",
          "brand_code.required"       => "Please fill brand code here",
          "brand_code.unique"         => "Brand code already used by another category, please try different one"
        );

    		$this->validate($request, $rules, $messages);

        if($request->file('brand_avatar')){
          $uploadedFile = $request->file('brand_avatar');
          $path         = public_path('brands');
          $filename     = time()."_".$uploadedFile->getClientOriginalName();
          $file         = $uploadedFile->move($path, $filename);
          $data['brand_avatar'] = url("public/brands/".$filename);
        }

        $update                 = $this->brand_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully updated!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to update data']);
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

  public function remove(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['remove'])){
        $id = $request->input("id");
        $detail = $this->brand_model->getDetail($id);
        $data = array(
          "id_brand"              => $id,
          "status"                => "deleted",
          "last_update"           => time()
        );
        $remove = $this->brand_model->updateData($data);

        if($remove){
          $undolink = url($this->config['main_url'].'/restore/'.$id);
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data <strong>'.$detail->category_name.'</strong> successfully removed! click <a href="'.$undolink.'" class="text-danger"><strong>here</strong></a> to restore data']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to remove data']);
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
        $detail = $this->brand_model->getDetail($id);
        $data = array(
          "id_brand"              => $id,
          "status"                => "active",
          "last_update"           => time()
        );
        $remove = $this->brand_model->updateData($data);

        if($remove){
          $undolink = url('admin/service/restore/'.$id);
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data <strong>'.$detail->category_name.'</strong> successfully restored!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to restore data']);
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
        $parents = array("0" => "Tidak menggunakan parent");

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->brand_model->getDetail($id),
          "config"         => $this->config
        );

        $view     = View::make("backend.master.brand.detail",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail kategori",
          "description"   => $webname." | Detail kategori",
          "keywords"      => $webname." | Detail kategori"
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
