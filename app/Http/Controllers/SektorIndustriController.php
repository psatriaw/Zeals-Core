<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\IGWHelper;
use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
use App\Http\Models\UserModel;
use App\Http\Models\SektorIndustriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class SektorIndustriController extends Controller
{
    //
    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $this->user_model       = new UserModel();
        $this->sektorindustri_model = new SektorIndustriModel();

        $dataconfig['main_url'] = "master/category";
        $dataconfig['view']     = "category-view";
        $dataconfig['create']   = "category-create";
        $dataconfig['edit']     = "category-edit";
        $dataconfig['remove']   = "category-remove";
        $dataconfig['manage']   = "category-manage";

        $this->config           = $dataconfig;
    }

    public function index(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {
                $default    = array(
                    "short"     => "nama_sektor_industri",
                    "shortmode" => "asc"
                );
                $shorter = array(
                    "nama_sektor_industri" => "Sektor Industri",
                    "time_created"        => "Tgl Terdaftar",
                    "last_update"         => "Terakhir Diperbarui"
                );

                $page       = $request->input("page");
                $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword    = $request->input("keyword");
                $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
                $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
                $str        = ($page != "") ? (($page - 1) * $limit) : 0;
                $data       = $this->sektorindustri_model->getSektorIndustriAll($str, $limit, $keyword, $short, $shortmode);

                // dd($data);

                //exit();
                $totaldata  = $this->sektorindustri_model->countData($keyword);
                $active     = $this->sektorindustri_model::where('status', 'active')->get();
                $pagging    = $this->helper->showPagging($totaldata, url($this->config['main_url'] . '?keyword=' . $keyword . "&short=" . $short . "&shortmode=" . $shortmode), $position = "", $page, $limit, 2);

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
                    "total_data"     => $data->count(),
                    "total_active"         => $active->count(),
                    "limit"          => $limit,
                    // "total_non_active"     => $inactive->count(),
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );


                $view     = View::make("backend.master.category.index", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " |  Manajemen Sektor Industri",
                    "description"   => $webname . " |  Manajemen Sektor Industri",
                    "keywords"      => $webname . " |  Manajemen Sektor Industri"
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
            return view($body, $data);
        } else {
            return redirect(url('login'));
        }
    }

    public function detail($id)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {
                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    "data"           => $this->sektorindustri_model::where('id_sektor_industri', $id)->first()
                );

                // dd($datacontent['data']);

                $view     = View::make("backend.master.category.detail", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Detail Data Sektor Industri",
                    "description"   => $webname . " | Detail Data Sektor Industri",
                    "keywords"      => $webname . " | Detail Data Sektor Industri"
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
            return view($body, $data);
        } else {
            return redirect(url('login'));
        }
    }

    public function edit($id)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {
                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    "data"           => $this->sektorindustri_model::where('id_sektor_industri', $id)->first()
                );

                // dd($datacontent['data']);

                $view     = View::make("backend.master.category.edit", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Detail Data Sektor Industri",
                    "description"   => $webname . " | Detail Data Sektor Industri",
                    "keywords"      => $webname . " | Detail Data Sektor Industri"
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
            return view($body, $data);
        } else {
            return redirect(url('login'));
        }
    }

    public function update($id, Request $request)
    {
        $login    = Session::get("user");


        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

                $data = [
                    'nama_sektor_industri' => $request->nama_sektor_industri,
                    'last_update' => time(),
                    'id_sektor_industri' => $id
                ];
                if ($request->hasFile('icon')) {
                  $filePhotosInput = $request->file('icon');
                  $icon_path = "templates/category/icon/" .md5($filePhotosInput->getClientOriginalName()) . '.' . $filePhotosInput->getClientOriginalExtension();
                  $filePhotosInput->move(public_path('templates/category/icon/'), $icon_path);
                  $data['icon']=$icon_path;
                }

                $rules  = array(
                    'nama_sektor_industri' => 'required'
                );

                $messages = array(
                    'nama_sektor_industri' => 'Mohon isi bagian berikut'
                );

                $this->validate($request, $rules, $messages);

                $update_sektor_industri = $this->sektorindustri_model->updateData($data);

                if ($update_sektor_industri) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'data updated!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to update data!']);
                }
                return redirect(url('master/category'));
            } else {
                $webname  = $this->setting_model->getSettingVal("website_name");
                $view     = View::make("backend.403");
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Halaman tidak diperbolehkan",
                    "description"   => $webname . " | Halaman tidak diperbolehkan",
                    "keywords"      => $webname . " | Halaman tidak diperbolehkan"
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
                return view($body, $data);
            }
        } else {
            return redirect(url('login'));
        }
    }

    public function create()
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                );

                // dd($datacontent);

                $view     = View::make("backend.master.category.create", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Tambah Data Sektor Industri",
                    "description"   => $webname . " | Tambah Data Sektor Industri",
                    "keywords"      => $webname . " | Tambah Data Sektor Industri"
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
            return view($body, $data);
        } else {
            return redirect(url('login'));
        }
    }

    public function store(Request $request)
    {
        $login    = Session::get("user");


        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

              if($request->hasFile('icon')){
                $filePhotosInput = $request->file('icon');
                $icon_path = "templates/category/icon/" .md5($filePhotosInput->getClientOriginalName()) . '.' . $filePhotosInput->getClientOriginalExtension();
                $filePhotosInput->move(public_path('templates/category/icon/'), $icon_path);
              }else{
                $icon_path = "";
              }
                $data = [
                    'nama_sektor_industri' => $request->nama_sektor_industri,
                    'time_created' => time(),
                    'last_update' => time(),
                    'icon'=>$icon_path
                ];


                $rules  = array(
                    'nama_sektor_industri' => 'required'
                );

                $messages = array(
                    'nama_sektor_industri' => 'Mohon isi bagian ini!'
                );

                $this->validate($request, $rules, $messages);

                $create_sektor_industri                 = $this->sektorindustri_model::insert($data);

                if ($create_sektor_industri) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data created!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to create data!']);
                }
                return redirect(url('master/category'));
            } else {
                $webname  = $this->setting_model->getSettingVal("website_name");
                $view     = View::make("backend.403");
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Halaman tidak diperbolehkan",
                    "description"   => $webname . " | Halaman tidak diperbolehkan",
                    "keywords"      => $webname . " | Halaman tidak diperbolehkan"
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
                return view($body, $data);
            }
        } else {
            return redirect(url('login'));
        }
    }

    public function remove($id)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['remove'])) {
                $detail = $this->user_model->getDetail($id);

                $data = array(
                    "id_sektor_industri"      => $id,
                    "status"            => "deleted",
                    "last_update"       => time(),
                );
                $approve = $this->sektorindustri_model->updateData($data);

                if ($approve) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data sektor industri!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data sektor industri']);
                }
                return redirect(url('master/category'));
            } else {
                $webname  = $this->setting_model->getSettingVal("website_name");
                $view     = View::make("backend.403");
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Halaman tidak diperbolehkan",
                    "description"   => $webname . " | Halaman tidak diperbolehkan",
                    "keywords"      => $webname . " | Halaman tidak diperbolehkan"
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
                return view($body, $data);
            }
        } else {
            return redirect(url('login'));
        }
    }

}
