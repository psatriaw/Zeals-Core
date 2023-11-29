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
use App\Http\Models\DepartmentModel;
use App\Http\Models\ModuleModel;
use App\Http\Models\MethodModel;
use App\Http\Models\ModuleDepartmentModel;

class ModuleController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $user_model;
  private $department_model;
  private $module_model;
  private $method_model;
  private $moduledepartment_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->user_model       = new UserModel();
    $this->department_model = new DepartmentModel();
    $this->module_model     = new ModuleModel();
    $this->method_model     = new MethodModel();
    $this->moduledepartment_model = new ModuleDepartmentModel();

    $dataconfig['main_url'] = "admin/module";
    $dataconfig['view']     = "admin-master-module-show";
    $dataconfig['approve']  = "admin-master-module-approve";
    $dataconfig['create']   = "admin-master-module-create";
    $dataconfig['edit']     = "admin-master-module-edit";
    $dataconfig['remove']   = "admin-master-module-remove";
    $dataconfig['manage']   = "admin-master-module-manage";

    $this->config           = $dataconfig;
  }

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $default    = array(
          "short"     => "tb_module.last_update",
          "shortmode" => "desc"
        );
        $shorter = array(
          "module_name"             => "Module Name",
          "tb_department.name"      => "Group User",
          "tb_module.time_created"  => "Registered",
          "tb_module.last_update"   => "Last Update"
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;
        $data       = $this->module_model->getData($str, $limit, $keyword, $short, $shortmode);
        //dd($data);
        //exit;
        $totaldata  = $this->module_model->countData($keyword);
        $pagging    = $this->helper->showPagging($totaldata, url('admin/module/?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode), $position = "", $page, $limit , 2);

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

        $view     = View::make("backend.master.module.index",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Daftar Module",
          "description"   => $webname." | Daftar Module",
          "keywords"      => $webname." | Daftar Module"
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
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.module.create",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah Module",
          "description"   => $webname." | Tambah Module",
          "keywords"      => $webname." | Tambah Module"
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
          "module_name"      => $request->input("module_name"),
          "time_created"     => time(),
          "last_update"      => time(),
        );

        $rules  = array(
          "module_name"      => "required"
    		);

    		$messages = array(
          "module_name.required" => "Please fill this field!",
        );

    		$this->validate($request, $rules, $messages);

        $addmodule          = $this->module_model->insertData($data);
        if($addmodule){
          if($request->input("id_department")!=""){
            foreach ($request->input("id_department") as $key => $value) {
              $datamoduledepart = array(
                "id_module"       => $addmodule,
                "id_department"   => $value
              );
              $this->moduledepartment_model->insertData($datamoduledepart);
            }
          }
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully added!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to add data!']);
        }
        return redirect(url('admin/module/manage/'.$addmodule));
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
        $data = $this->module_model->getDetail($id);
        $data['id_department']  = $this->moduledepartment_model->getdepartments($id);

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "optdepartment"  => $this->department_model->getDepartmentOpt(),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $data
        );

        $view     = View::make("backend.master.module.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah module",
          "description"   => $webname." | Ubah module",
          "keywords"      => $webname." | Ubah module"
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
        $detail = $this->module_model->getDetail($id);

        $data = array(
          "id_module"        => $id,
          "module_name"      => $request->input("module_name"),
          "last_update"      => time(),
        );

        $rules  = array(
          "module_name"      => "required"
    		);

    		$messages = array(
          "module_name.required" => "Please fill this field!",
        );

    		$this->validate($request, $rules, $messages);

        $update                 = $this->module_model->updateData($data);
        if($update){
          if($request->input("id_department")!=""){
            $this->moduledepartment_model->removeDepartmentRecord($id);
            foreach ($request->input("id_department") as $key => $value) {
              $datamoduledepart = array(
                "id_module"       => $id,
                "id_department"   => $value
              );
              $this->moduledepartment_model->insertData($datamoduledepart);
            }
          }
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully updated']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to update data!']);
        }
        return redirect(url('admin/module/edit/'.$id));
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
          "optdepartment"  => $this->department_model->getDepartmentOpt(),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->module_model->getDataSubModule($id),
          "parent_id"      => $id,
          "detail"         => $this->module_model->getDetail($id)
        );

        $view     = View::make("backend.master.module.method.index",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Manage module",
          "description"   => $webname." | Manage module",
          "keywords"      => $webname." | Manage module"
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

  public function createmethod($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "parent_id"      => $id
        );

        $view     = View::make("backend.master.module.method.create",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah Method",
          "description"   => $webname." | Tambah Method",
          "keywords"      => $webname." | Tambah Method"
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

  public function storemethod($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $data = array(
          "method"          => $request->input("method"),
          "description"     => $request->input("description"),
          "id_module"       => $id
        );

        $rules  = array(
          "method"          => "required",
          "description"     => "required"
    		);

    		$messages = array(
          "method.required"       => "Mohon mengisi nama method disini",
          "description.required"  => "Mohon mengisi deskripsi disini"
        );

    		$this->validate($request, $rules, $messages);

        $createuser          = $this->method_model->insertData($data);
        if($createuser){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully added!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to add data!']);
        }
        return redirect(url('admin/module/manage/'.$id));
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

  public function editmethod($id,$id_method){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "parent_id"      => $id,
          "data"           => $this->method_model->getDetail($id_method)
        );

        $view     = View::make("backend.master.module.method.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah Method",
          "description"   => $webname." | Tambah Method",
          "keywords"      => $webname." | Tambah Method"
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

  public function updatemethod($id, $id_method, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){
        $detail = $this->module_model->getDetail($id);

        $data = array(
          "id_method"       => $id_method,
          "method"          => $request->input("method"),
          "description"     => $request->input("description"),
        );

        $rules  = array(
          "method"          => "required",
          "description"     => "required"
    		);

    		$messages = array(
          "method.required"       => "Mohon mengisi nama method disini",
          "description.required"  => "Mohon mengisi deskripsi disini"
        );

    		$this->validate($request, $rules, $messages);

        $update                 = $this->method_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully updated!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to update data!']);
        }
        return redirect(url('admin/module/method/edit/'.$id."/".$id_method));
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
        $detail = $this->module_model->getDetail($id);
        $remove = $this->module_model->removeData($id);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data '.$detail->module_name.' successfully removed!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to remove data!']);
        }
        return redirect(url('admin/module'));
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

  public function removemethod($id_module, Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['remove'])){
        $id = $request->input("id");
        $detail = $this->method_model->getDetail($id);
        $remove = $this->method_model->removeData($id);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data '.$detail->method.' successfully removed!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to remove data!']);
        }
        return redirect(url('admin/module/manage/'.$id_module));
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
          "data"           => $this->module_model->getDetail($id)
        );

        $view     = View::make("backend.master.module.detail",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail module",
          "description"   => $webname." | Detail module",
          "keywords"      => $webname." | Detail module"
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
