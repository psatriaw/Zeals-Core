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
use App\Http\Models\MicrositesModel;
use App\Http\Models\MicrositeComponentModel;
use App\Http\Models\PenerbitModel;


class MicrositeController extends Controller
{
    //
    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->microsite_model  = new MicrositesModel();
        $this->component_model  = new MicrositeComponentModel();
        $this->penerbit_model   = new PenerbitModel();

        $dataconfig['main_url'] = "master/microsite";
        $dataconfig['view']     = "microsite-view";
        $dataconfig['create']   = "microsite-create";
        $dataconfig['edit']     = "microsite-edit";
        $dataconfig['report']   = "microsite-report";
        $dataconfig['remove']   = "microsite-remove";
        $dataconfig['manage']   = "microsite-manage";
        $this->config           = $dataconfig;
    }
    public function index(Request $request)
    {
        $login    = Session::get("user");
        $keyword = empty($request->search) ? $request->search : '';
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {
                // $count['running']= $this->microsite_model->getTotalStatus('running');
                // $count['stopped']= $this->microsite_model->getTotalStatus('stopped');
                // $count['pending']= $this->microsite_model->getTotalStatus('pending');
                // $count['expired']= $this->microsite_model->getTotalStatus('expired');
                // $count['review']= $this->microsite_model->getTotalStatus('review');
                // $count['drafted']= $this->microsite_model->getTotalStatus('drafted');
                $datacontent = array(
                    "login"     => $login,
                    "config"    => $this->config,
                    "previlege" => $this->previlege_model,
                    "list"      => $this->microsite_model->getListSite(),
                    // "count"     => $count
                );
                $view     = View::make('backend.master.microsite.index', $datacontent);
                $content  = $view->render();
                $data     = array(
                    "content"   => $content,
                    "login"     => $login,
                    "page"      => "admin_dashboard",
                    "submenu"   => "admin_dashboard",
                    "previlege" => $this->previlege_model
                );
                $metadata = array(
                    "title"         => $webname . " | Microsite",
                    "description"   => $webname . " | Microsite",
                    "keywords"      => $webname . " | Microsite"
                );
                $body = "backend.body_backend_with_sidebar";
            } else {
                $view     = View::make("backend.403");
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Halaman tidak diperbolehkan",
                    "description"   => $webname . " | Halaman tidak diperbolehkan",
                    "keywords"      => $webname . " | Halaman tidak diperbolehkan"
                );
                $body = "backend.body";
            }

            $data     = array(
                "content"   => $content,
                "login"     => $login,
                "page"      => "admin_dashboard",
                "submenu"   => "admin_dashboard",
                "meta"      => $metadata,
                "previlege" => $this->previlege_model
            );
            return view($body, $data);
        } else {
            return redirect(url('login'));
        }
    }
    public function createMicrosite(Request $request)
    {
        $login    = Session::get("user");
        $keyword = empty($request->search) ? $request->search : '';
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                $datacontent = array(
                    "login"     => $login,
                    "config"    => $this->config,
                    "previlege" => $this->previlege_model,
                    "penerbits" => $this->penerbit_model->getListPenerbit(),
                );
                $view     = View::make('backend.master.microsite.create', $datacontent);
                $content  = $view->render();
                $data     = array(
                    "content"   => $content,
                    "login"     => $login,
                    "page"      => "admin_dashboard",
                    "submenu"   => "admin_dashboard",
                    "previlege" => $this->previlege_model
                );
                $metadata = array(
                    "title"         => $webname . " | Microsite",
                    "description"   => $webname . " | Microsite",
                    "keywords"      => $webname . " | Microsite"
                );
                $body = "backend.body_backend_with_sidebar";
            } else {
                $view     = View::make("backend.403");
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Halaman tidak diperbolehkan",
                    "description"   => $webname . " | Halaman tidak diperbolehkan",
                    "keywords"      => $webname . " | Halaman tidak diperbolehkan"
                );
                $body = "backend.body";
            }

            $data     = array(
                "content"   => $content,
                "login"     => $login,
                "page"      => "admin_dashboard",
                "submenu"   => "admin_dashboard",
                "meta"      => $metadata,
                "previlege" => $this->previlege_model
            );
            return view($body, $data);
        } else {
            return redirect(url('login'));
        }
    }
    public function saveMicrosite(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                // catch file
                if ($request->hasFile('photos')) {
                    $filePhotosInput = $request->file('photos');
                    $photos_path = "uploads/microsite/" . md5($filePhotosInput->getClientOriginalName()) . '-' . time() . '.' . $filePhotosInput->getClientOriginalExtension();
                    $filePhotosInput->move(public_path('uploads/campaign/'), $photos_path);
                } else {
                    $photos_path = "";
                }

                $data = [
                    // 'id_website'    => $request->id_website,
                    'nama_microsite'      => $request->web_name,
                    'id_penerbit'   => $request->id_penerbit,
                    'banner'        => $photos_path,
                    'notes'         => $request->notes,
                    'start_date'    => $request->start_date,
                    'end_date'      => $request->end_date,
                    'status'        => 'drafted',
                    'created_at'    => time(),
                    'updated_at'    => time(),
                ];
                // $id_microsite=$request->id_microsite;

                $rules  = array(
                    'web_name'      => 'required',
                    'id_penerbit'   => 'required',
                    'notes'         => 'required',
                    'start_date'    => 'required',
                    'end_date'      => 'required',
                );

                $messages = array(
                    'web_name'      => 'Mohon isi bagian berikut!',
                    'id_penerbit'   => 'Mohon isi bagian berikut',
                    'notes'         => 'Mohon isi bagian berikut!',
                    'start_date'    => 'Mohon isi bagian berikut!',
                    'end_date'      => 'Mohon isi bagian berikut!',
                );

                $this->validate($request, $rules, $messages);

                $create_microsite   = $this->microsite_model->insertData($data);
                if ($create_microsite) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Yes! Your microsite detail created! Please define the component for this website.']);
                    return redirect(url('master/microsite/setcomponent/' . $create_microsite));
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to create microsite detail']);
                    return redirect(url('master/microsite/create/'));
                }

                // $update_microsite   = $this->microsite_model::updateData($data);
                // if ($update_microsite) {
                //     Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Yes! Your campaign detail created! Please define the program for this campaign.']);
                //     return redirect(url('master/microsite/setcomponent/'.$id_microsite));
                // } else {
                //     Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to create campaign detail']);
                //     return redirect(url('master/microsite/create/'.$id_microsite));
                // }
            } else {
                $view     = View::make("backend.403");
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Halaman tidak diperbolehkan",
                    "description"   => $webname . " | Halaman tidak diperbolehkan",
                    "keywords"      => $webname . " | Halaman tidak diperbolehkan"
                );
                $body = "backend.body";
            }

            $data     = array(
                "content"   => $content,
                "login"     => $login,
                "page"      => "admin_dashboard",
                "submenu"   => "admin_dashboard",
                "meta"      => $metadata,
                "previlege" => $this->previlege_model
            );
            return view($body, $data);
        } else {
            return redirect(url('login'));
        }
    }
    public function setComponent(Request $request, $id_microsite = '')
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                $datacontent = array(
                    "login"     => $login,
                    "config"    => $this->config,
                    "previlege" => $this->previlege_model,
                    "id_microsite" => $id_microsite,
                );
                $view     = View::make('backend.master.microsite.set_component', $datacontent);
                $content  = $view->render();
                $data     = array(
                    "content"   => $content,
                    "login"     => $login,
                    "page"      => "admin_dashboard",
                    "submenu"   => "admin_dashboard",
                    "previlege" => $this->previlege_model
                );
                $metadata = array(
                    "title"         => $webname . " | Microsite",
                    "description"   => $webname . " | Microsite",
                    "keywords"      => $webname . " | Microsite"
                );
                $body = "backend.body_backend_with_sidebar";
            } else {
                $view     = View::make("backend.403");
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Halaman tidak diperbolehkan",
                    "description"   => $webname . " | Halaman tidak diperbolehkan",
                    "keywords"      => $webname . " | Halaman tidak diperbolehkan"
                );
                $body = "backend.body";
            }

            $data     = array(
                "content"   => $content,
                "login"     => $login,
                "page"      => "admin_dashboard",
                "submenu"   => "admin_dashboard",
                "meta"      => $metadata,
                "previlege" => $this->previlege_model
            );
            return view($body, $data);
        } else {
            return redirect(url('login'));
        }
    }
    public function saveComponent(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                //proses saving to database here!!!

                $data = [
                    'id_microsite'  => $request->id_microsite,
                    'input_type'    => $request->input_type,
                    'field_name'    => $request->field_name,
                    'input_source'  => $request->input_source,
                    'rules'         => $request->rules,
                    'status'        => $request->status,
                ];
                // $id_microsite=$request->id_microsite;

                $rules  = array(
                    'input_type'    => 'required',
                    'field_name'    => 'required',
                    'status'        => 'required',
                );

                $messages = array(
                    'input_type'      => 'Mohon isi bagian berikut!',
                    'field_name'   => 'Mohon isi bagian berikut',
                    'status'         => 'Mohon isi bagian berikut!',
                );

                $this->validate($request, $rules, $messages);

                $create_component = $this->component_model->insertData($data);
                if ($create_component) {
                    if ($request->type_submit == "more") {
                        Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Component created, Please add more component.']);
                        return redirect(url('master/microsite/setcomponent/' . $request->id_microsite));
                    } else {
                        Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Component created, your Microsite is ready!']);
                        return redirect(url('master/microsite/resume/' . $request->id_microsite));
                    }
                    // Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Yes! Your microsite detail created! Please define the component for this website.']);
                    // return redirect(url('master/microsite/resume/'.$create_component));
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to create microsite detail']);
                    return redirect(url('master/microsite/setcomponent/' . $request->id_microsite));
                }
            } else {
                $view     = View::make("backend.403");
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Halaman tidak diperbolehkan",
                    "description"   => $webname . " | Halaman tidak diperbolehkan",
                    "keywords"      => $webname . " | Halaman tidak diperbolehkan"
                );
                $body = "backend.body";
            }

            $data     = array(
                "content"   => $content,
                "login"     => $login,
                "page"      => "admin_dashboard",
                "submenu"   => "admin_dashboard",
                "meta"      => $metadata,
                "previlege" => $this->previlege_model
            );
            return view($body, $data);
        } else {
            return redirect(url('login'));
        }
    }
    public function resume(Request $request, $id_microsite)
    {
        $login    = Session::get("user");
        $keyword = empty($request->search) ? $request->search : '';
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                $datacontent = array(
                    "login"     => $login,
                    "config"    => $this->config,
                    "previlege" => $this->previlege_model,
                    "list_component" => $this->component_model->getListComponent($id_microsite),
                );
                $view     = View::make('backend.master.microsite.resume', $datacontent);
                $content  = $view->render();
                $data     = array(
                    "content"   => $content,
                    "login"     => $login,
                    "page"      => "admin_dashboard",
                    "submenu"   => "admin_dashboard",
                    "previlege" => $this->previlege_model
                );
                $metadata = array(
                    "title"         => $webname . " | Microsite",
                    "description"   => $webname . " | Microsite",
                    "keywords"      => $webname . " | Microsite"
                );
                $body = "backend.body_backend_with_sidebar";
            } else {
                $view     = View::make("backend.403");
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Halaman tidak diperbolehkan",
                    "description"   => $webname . " | Halaman tidak diperbolehkan",
                    "keywords"      => $webname . " | Halaman tidak diperbolehkan"
                );
                $body = "backend.body";
            }

            $data     = array(
                "content"   => $content,
                "login"     => $login,
                "page"      => "admin_dashboard",
                "submenu"   => "admin_dashboard",
                "meta"      => $metadata,
                "previlege" => $this->previlege_model
            );
            return view($body, $data);
        } else {
            return redirect(url('login'));
        }
    }
    public function report(Request $request)
    {
        $login    = Session::get("user");
        $keyword = empty($request->search) ? $request->search : '';
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                $datacontent = array(
                    "login"     => $login,
                    "config"    => $this->config,
                    "previlege" => $this->previlege_model,
                    // "list_component"=> $this->component_model->getListComponent($id_microsite),
                );
                $view     = View::make('backend.master.microsite.report', $datacontent);
                $content  = $view->render();
                $data     = array(
                    "content"   => $content,
                    "login"     => $login,
                    "page"      => "admin_dashboard",
                    "submenu"   => "admin_dashboard",
                    "previlege" => $this->previlege_model
                );
                $metadata = array(
                    "title"         => $webname . " | Microsite",
                    "description"   => $webname . " | Microsite",
                    "keywords"      => $webname . " | Microsite"
                );
                $body = "backend.body_backend_with_sidebar";
            } else {
                $view     = View::make("backend.403");
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Halaman tidak diperbolehkan",
                    "description"   => $webname . " | Halaman tidak diperbolehkan",
                    "keywords"      => $webname . " | Halaman tidak diperbolehkan"
                );
                $body = "backend.body";
            }

            $data     = array(
                "content"   => $content,
                "login"     => $login,
                "page"      => "admin_dashboard",
                "submenu"   => "admin_dashboard",
                "meta"      => $metadata,
                "previlege" => $this->previlege_model
            );
            return view($body, $data);
        } else {
            return redirect(url('login'));
        }
    }
}
