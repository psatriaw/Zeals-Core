<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\IGWHelper;
use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
use App\Http\Models\UserModel;
use App\Http\Models\VideoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class TutorialVideoController extends Controller
{
    //
    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $this->user_model       = new UserModel();
        $this->video_model = new VideoModel();

        $dataconfig['main_url'] = "master/tutorial";
        $dataconfig['view']     = "tutorial-view";
        $dataconfig['create']   = "tutorial-create";
        $dataconfig['edit']     = "tutorial-edit";
        $dataconfig['remove']   = "tutorial-remove";

        $this->config           = $dataconfig;
    }

    public function index(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {
                $default    = array(
                    "short"     => "title",
                    "shortmode" => "asc"
                );
                $shorter = array(
                    "title"      => "Judul Video",
                    "time_created"        => "Tgl Terdaftar",
                    "last_update"         => "Terakhir Diperbarui"
                );
                $page       = $request->input("page");
                $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword    = $request->input("keyword");
                $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
                $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
                $str        = ($page != "") ? (($page - 1) * $limit) : 0;
                $data       = $this->video_model->getAllVideo($str, $limit, $keyword, $short, $shortmode);


                //exit();
                $totaldata  = count($data);
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
                    "limit"          => $limit,
                    "page"           => $page,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );


                $view     = View::make("backend.master.tutorial.index", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " |  Manajemen Video Tutorial",
                    "description"   => $webname . " |  Manajemen Video Tutorial",
                    "keywords"      => $webname . " |  Manajemen Video Tutorial"
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
                    "data"           => $this->video_model::where('id_video', $id)->first()
                );


                $view     = View::make("backend.master.tutorial.detail", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Detail Data Video Tutorial",
                    "description"   => $webname . " | Detail Data Video Tutorial",
                    "keywords"      => $webname . " | Detail Data Video Tutorial"
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

    public function create()
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "config"         => $this->config,
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                );

                // dd($datacontent);

                $view     = View::make("backend.master.tutorial.create", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Tambah Data Tutorial",
                    "description"   => $webname . " | Tambah Data Tutorial",
                    "keywords"      => $webname . " | Tambah Data Tutorial"
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

                $data = [
                    'url_video' => $request->url_video,
                    'time_created' => time(),
                    'last_update' => time(),
                    'status' => 'active',
                    'video_code' => $request->video_code,
                    'title' => $request->title
                ];


                $rules  = array(
                    'url_video' => 'required',
                    'video_code' => 'required',
                    'title' => 'required'
                );

                $messages = array(
                    'url_video' => 'Mohon mengisi bagian berikut!',
                    'video_code' => 'Mohon mengisi bagian berikut!',
                    'title' => 'Mohon mengisi bagian berikut!'
                );

                $this->validate($request, $rules, $messages);

                // dd($data);

                $create_penerbit  = $this->video_model::insert($data);

                if ($create_penerbit) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah video tutorial!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah video tutorial']);
                }
                return redirect(url('master/tutorial'));
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

    public function edit($id)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {
                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "config"         => $this->config,
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    "data"           => $this->video_model::where('id_video', $id)->first()
                );

                // dd($datacontent['data']);

                $view     = View::make("backend.master.tutorial.edit", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Edit Data Video Tutorial",
                    "description"   => $webname . " | Edit Data Video Tutorial",
                    "keywords"      => $webname . " | Edit Data Video Tutorial"
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
                    'id_video' => $id,
                    'url_video' => $request->url_video,
                    'title' => $request->title,
                    'video_code' => $request->video_code,
                    'last_update' => time(),
                ];

                // dd($data);

                $rules  = array(
                    'url_video' => "required",
                    'title' => "required",
                    'video_code' => "required",
                );

                $messages = array(
                    'id_video' => "Mohon isi bagian berikut",
                    'url_video' => "Mohon isi bagian berikut",
                    'title' => "Mohon isi bagian berikut",
                    'video_code' => "Mohon isi bagian berikut",
                );

                $this->validate($request, $rules, $messages);

                $update_penerbit = $this->video_model->updateData($data);

                if ($update_penerbit) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah video tutorial!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah video tutorial']);
                }
                return redirect(url('master/tutorial'));
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
                    "id_video"      => $id,
                    "status"            => "deleted",
                    "last_update"       => time(),
                );
                $deleted = $this->video_model->updateData($data);

                if ($deleted) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses remove data penerbit!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat remove data penerbit']);
                }
                return redirect(url('master/tutorial'));
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
