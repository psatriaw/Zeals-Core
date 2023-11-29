<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\Controller;

use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
use App\Http\Models\NamecardsModel;


class NamecardsController extends Controller
{
    private $setting_model;
    private $previlege_model;
    private $helper;

    private $namecard_model;

    public function __construct(){
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->namecard_model = new NamecardsModel();

        $dataconfig['main_url'] = "admin/namecards";
        $dataconfig['view']     = "namecard-show";
        $dataconfig['create']   = "namecard-create";
        $dataconfig['edit']     = "namecard-edit";
        $dataconfig['remove']   = "namecard-remove";
        $dataconfig['card']     = "namecard-view";
        $this->config           = $dataconfig;
    }
    public function index(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {
                $data=$this->namecard_model->getDataAll();
                $datacontent = array(
                    "login"          => $login,
                    "previlege"      => $this->previlege_model,
                    "data"           => $data,
                    "input"          => $request->all(),
                    "config"         => $this->config,
                );
                
                $view     = View::make("backend.master.namecard.index",$datacontent);
                $content  = $view->render();

                $metadata = array(
                "title"         => $webname." | Namecard",
                "description"   => $webname." | Namecard",
                "keywords"      => $webname." | Namecard"
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
    
                $view     = View::make("backend.master.namecard.create",$datacontent);
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
    public function save(Request $request){
      $login    = Session::get("user");
      if($login){
        if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
          // catch file
          if($request->hasFile('qrcode')){
            $filePhotosInput = $request->file('qrcode');
            $qrcode_path = "templates/namecard/qr/" . $request->input("slug") . '.' . $filePhotosInput->getClientOriginalExtension();
            $filePhotosInput->move(public_path('templates/namecard/qr/'), $qrcode_path);
          }else{
            $qrcode_path = "";
          }
          $data = array(
            "full_name"         => $request->input("full_name"),
            "job_desk"          => $request->input("job_desk"),
            "email"             => $request->input("email"),
            "phone"             => $request->input("phone"),
            "address"           => $request->input("address"),
            "slug"              => $request->input("slug"),
            "created_at"        => date("Y-m-d H:i:s"),
            "updated_at"        => date("Y-m-d H:i:s"),
          );
  
          $rules  = array(
            "full_name"     => "required",
            "job_desk"      => "required",
            "email"         => "required|email",
            );
  
            $messages = array(
            "full_name.required"    => "Please fill this field",
            "job_desk.required"     => "Please fill this field",
            "email.required"        => "Please fill this field",
            "email.email"           => "Please use valid email format: contoh@domain"
          );

              $this->validate($request, $rules, $messages);
  
          $createdata                 = $this->namecard_model->insertData($data);
          if($createdata){
            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully added']);
          }else{
            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to add data!']);
          }
          return redirect(url('admin/namecards'));
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
              "data"           => $this->namecard_model->findById($id)
            );
    
            //print_r($datacontent['data']->toArray());
            //exit();
    
            $view     = View::make("backend.master.namecard.edit",$datacontent);
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
                $detail = $this->namecard_model->findById($id);
        
                $data = array(
                "id"           => $id,
                "full_name"         => $request->input("full_name"),
                "job_desk"          => $request->input("job_desk"),
                "email"             => $request->input("email"),
                "phone"             => $request->input("phone"),
                "address"           => $request->input("address"),
                "slug"              => $request->input("slug"),
                "updated_at"        => date("Y-m-d H:i:s"),
                );
    
                $rules  = array(
                "full_name"         => "required",
                "job_desk"          => "required",
                "email"             => "required|email"
                    );
    
                $messages = array(
                    "full_name.required"    => "Please fill this field",
                    "job_desk.required"     => "Please fill this field",
                    "email.required"        => "Please fill this field",
                    "email.email"           => "Please use valid email format: contoh@domain",
                );
        
                $this->validate($request, $rules, $messages);
        
                $update                 = $this->namecard_model->updateData($data);
                if($update){
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully updated!']);
                }else{
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Update data failed!']);
                }
                return redirect(url('admin/namecards/edit/'.$id));
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
                $detail = $this->namecard_model->findById($id);
                $remove = $this->namecard_model->removeData($id);
                unlink(public_path('/templates/namecard/qr/'.$detail->slug.'.png'));
                if($remove){
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data pengguna '.$detail->full_name.'!']);
                }else{
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data pengguna']);
                }
                return redirect(url('admin/namecards'));
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

    public function card($slug){
        $slug = $this->namecard_model::where("slug",$slug);
        if($slug->count()){
          $data     = $slug->first();
          $datacard = $data;
  
          $view     = View::make("frontend.namecard.namecard", $datacard);
          $content  = $view->render();
  
          $data     = array(
              "page"         => "Zeals Digital Name Card",
              "submenu"      => "Zeals Digital Name Card",
              "title"        => $data->full_name." | Zeals Digital Name Card",
              "description"  => $data->full_name." | Zeals Digital Name Card",
              "keywords"     => $data->full_name." | Zeals Digital Name Card",
              "content"      => $content,
              "data"         => $data
          );
  
          return view("frontend.namecard.body",$data);
        }else{
          $view     = View::make("frontend.404");
          $content  = $view->render();
  
          $data     = array(
              "page"         => "Zeals Digital Name Card",
              "submenu"      => "Zeals Digital Name Card",
              "title"        => "Zeals Digital Name Card",
              "description"  => "Zeals Digital Name Card",
              "keywords"     => "Zeals Digital Name Card",
              "content"      => $content
          );
  
          return view("frontend.namecard.body",$data);
        }
      }
}
