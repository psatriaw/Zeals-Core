<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
use App\Http\Models\BankModel;

class BankController extends Controller
{
    private $setting_model;
    private $previlege_model;
    private $helper;
    private $bank_model;

    public function __construct(){
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->bank_model = new BankModel();

        $dataconfig['main_url'] = "admin/bank";
        $dataconfig['view']     = "bank-show";
        $dataconfig['create']   = "bank-create";
        $dataconfig['edit']     = "bank-edit";
        $dataconfig['remove']   = "bank-remove";
        $this->config           = $dataconfig;
    }
    public function index(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {
                $data=$this->bank_model->getDataAll();
                $datacontent = array(
                    "login"          => $login,
                    "previlege"      => $this->previlege_model,
                    "data"           => $data,
                    "input"          => $request->all(),
                    "config"         => $this->config,
                );
                
                $view     = View::make("backend.master.bank.index",$datacontent);
                $content  = $view->render();

                $metadata = array(
                "title"         => $webname." | Bank",
                "description"   => $webname." | Bank",
                "keywords"      => $webname." | Bank"
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
                // "optdepartment"  => $this->department_model->getDepartmentOpt(),
                // "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                // "brands"         => $this->penerbit_model->getPenerbitActive()
                );
    
                $view     = View::make("backend.master.bank.create",$datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname." | Tambah data bank",
                    "description"   => $webname." | Tambah data bank",
                    "keywords"      => $webname." | Tambah data bank"
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
    public function save(Request $request){
      $login    = Session::get("user");
      if($login){
        if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
          $data = array(
            "kode"          => $request->input("kode"),
            "nama"          => $request->input("nama"),
            "bank_code"     => $request->input("bank_code"),
          );
  
          $rules  = array(
            "nama"     => "required",
            );
  
            $messages = array(
            "nama.required"    => "Please fill this field",
          );

              $this->validate($request, $rules, $messages);
  
          $createdata                 = $this->bank_model->insertData($data);
          if($createdata){
            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully added']);
          }else{
            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to add data!']);
          }
          return redirect(url('admin/bank'));
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
              "data"           => $this->bank_model->findById($id)
            );
    
            //print_r($datacontent['data']->toArray());
            //exit();
    
            $view     = View::make("backend.master.bank.edit",$datacontent);
            $content  = $view->render();
    
            $metadata = array(
              "title"         => $webname." | Ubah data bank",
              "description"   => $webname." | Ubah data bank",
              "keywords"      => $webname." | Ubah data bank"
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
                $detail = $this->bank_model->findById($id);
        
                $data = array(
                  "kode"           => $id,
                  "nama"          => $request->input("nama"),
                  "bank_code"     => $request->input("bank_code"),
                );
        
                $rules  = array(
                  "nama"     => "required",
                  );
        
                  $messages = array(
                  "nama.required"    => "Please fill this field",
                );
      
                    $this->validate($request, $rules, $messages);
        
                $update                 = $this->bank_model->updateData($data);
                if($update){
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully updated!']);
                }else{
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Update data failed!']);
                }
                return redirect(url('admin/bank/edit/'.$id));
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
                $detail = $this->bank_model->findById($id);
                $remove = $this->bank_model->removeData($id);

                if($remove){
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data bank '.$detail->nama.'!']);
                }else{
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data bank']);
                }
                return redirect(url('admin/bank'));
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
