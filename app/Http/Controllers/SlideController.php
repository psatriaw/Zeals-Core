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

use Intervention\Image\ImageManagerStatic as Image;
//===============================

use App\Http\Models\SlideModel;

class SlideController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $slide_model;
  private $config;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->slide_model      = new SlideModel();
    $this->config           = array(
      "main_url"    => "master-slide",
      "view"        => "slide-view",
      "edit"        => "slide-edit",
      "create"      => "slide-create",
      "remove"      => "slide-remove",
      "detail"      => "slide-view",
      );
  }

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $default    = array(
          "short"     => "time_created",
          "shortmode" => "desc"
        );
        $shorter = array(
          "time_created"      => "Time created",
          "last_update"       => "Last update"
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;
        $data       = $this->slide_model->getData($str, $limit, $keyword, $short, $shortmode);

        $totaldata  = $this->slide_model->countData($keyword);
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

        $view     = View::make("backend.master.slide.index",$datacontent);
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
        
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model), 
          "config"         => $this->config
        );

        $view     = View::make("backend.master.slide.create",$datacontent);
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
          "status"      => $request->input("status"),
          "time_created"      => time(),
          "last_update"       => time(),
        );

        $rules  = array(
          "status"        => "required",
        );
		
    	$messages = array(
          "status.required"       => "Please fill brand name here"
        );

    	$this->validate($request, $rules, $messages);
		
		if($request->hasFile('path')){
			$uploadedFile = $request->file('path');
			$path         = public_path('upload');
			$filename     = time()."_".$uploadedFile->getClientOriginalName();
			$file         = $uploadedFile->move($path, $filename);
			$data['path'] = url("public/upload/".$filename);

			if($file){
			  $width    = 400;
			  $height   = 400;

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
		   }
		}
		
        $create                 = $this->slide_model->insertData($data);
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
        
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->slide_model->getDetail($id),
          "config"         => $this->config
        );

        $view     = View::make("backend.master.slide.edit",$datacontent);
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
        $data = array(
          "id_slide"      		=> $id,
          "status"      		=> $request->input("status"),
          "last_update"       	=> time(),
        );

        $rules  = array(
          "status"        => "required",
        );
		
    	$messages = array(
          "status.required"       => "Please fill brand name here"
        );

    	$this->validate($request, $rules, $messages);
		
		if($request->hasFile('path')){
			$uploadedFile = $request->file('path');
			$path         = public_path('upload');
			$filename     = time()."_".$uploadedFile->getClientOriginalName();
			$file         = $uploadedFile->move($path, $filename);
			$data['path'] = url("public/upload/".$filename);

			if($file){
			  $width    = 400;
			  $height   = 400;

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
		   }
		}

        $update                 = $this->slide_model->updateData($data);
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
        $detail = $this->slide_model->getDetail($id);
        $data = array(
          "id_slide"              => $id,
          "status"                => "deleted",
          "last_update"           => time()
        );
        $remove = $this->slide_model->updateData($data);

        if($remove){
          $undolink = url($this->config['main_url'].'/restore/'.$id);
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully removed!']);
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
        $detail = $this->slide_model->getDetail($id);
        $data = array(
          "id_brand"              => $id,
          "status"                => "active",
          "last_update"           => time()
        );
        $remove = $this->slide_model->updateData($data);

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
          "data"           => $this->slide_model->getDetail($id),
          "config"         => $this->config
        );

        $view     = View::make("backend.master.slide.detail",$datacontent);
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
