<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\IGWHelper;
use App\Http\Models\BannerModel;
use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
use App\Http\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

// use Illuminate\View\View;

class BannerController extends Controller
{
    //
    private $setting_model;
    private $previlege_model;
    private $helper;
    private $user_model;

    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $this->user_model       = new UserModel();
        $this->banner_model = new BannerModel();

        $dataconfig['main_url'] = "master/banner";
        $dataconfig['view']     = "banner-view";
        $dataconfig['create']   = "banner-create";
        $dataconfig['edit']     = "banner-edit";
        $dataconfig['remove']   = "banner-remove";
        $dataconfig['manage']   = "banner-manage";

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
                    "title"      => "Title",
                    "time_created"        => "Tgl Terdaftar",
                    "last_update"         => "Terakhir Diperbarui"
                );
                $page       = $request->input("page");
                $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword    = $request->input("keyword");
                $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
                $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
                $str        = ($page != "") ? (($page - 1) * $limit) : 0;
                $data       = $this->banner_model->getAllBannersActive($str, $limit, $keyword, $short, $shortmode);

                // dd($data);

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
                    "page"           => $page,
                    "limit"          => $limit,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );

                $view     = View::make("backend.master.banner.index", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " |  Manajemen Banner",
                    "description"   => $webname . " |  Manajemen Banner",
                    "keywords"      => $webname . " |  Manajemen Banner"
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
                    "config"         => $this->config,
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    "data"           => $this->banner_model->getDetail($id)
                );

                // dd($datacontent);

                $view     = View::make("backend.master.banner.detail", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Detail Data Banner",
                    "description"   => $webname . " | Detail Data Banner",
                    "keywords"      => $webname . " | Detail Data Banner"
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
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );

                $view     = View::make("backend.master.banner.create", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Tambah Data Banner",
                    "description"   => $webname . " | Tambah Data Banner",
                    "keywords"      => $webname . " | Tambah Data Banner"
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

                // catch file
                if ($request->hasFile('file_banner')) {
                  $fileInput = $request->file('file_banner');
                  $banner_path = "uploads/" . md5($fileInput->getClientOriginalName()) . '-' . time() . '.' . $fileInput->getClientOriginalExtension();
                  $fileInput->move(public_path('uploads'), $banner_path);
                  // dd($banner_path);

                  $data = array(
                      "time_created"      => time(),
                      "last_update"       => time(),
                      // "file_banner" => $fileInput,
                      'title' => $request->title,
                      'banner_path' => $banner_path,
                      'description' => $request->description,
                      'status' => $request->status,
                      'link' => $request->link,
                      'link_type' => $request->link_type,
                      'status' => $request->status
                  );


                  $rules  = array(
                      "title"    => "required",
                      // "file_banner" => "required",
                      "description" => "required",
                      "status" => "required",
                      "link" => "required",
                      "link_type" => "required",
                      "status" => "required"
                  );

                  $messages = array(
                      "title.required" => "Mohon mengisi Title disini",
                      // "file_banner.required" => "Mohon mengisi File disini",
                      "description.required" => "Mohon mengisi Deskripsi disini",
                      "status.required" => "Mohon mengisi Status disini",
                      "link.required" => "Mohon mengisi Link disini",
                      "link_type.required" => "Mohon mengisi Link Type disini",
                      "status.required" => "Mohon mengisi Status disini",
                  );

                  $this->validate($request, $rules, $messages);

                  $create_banner                 = $this->banner_model::insert($data);

                  if ($create_banner) {
                      Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah banner!']);
                  } else {
                      Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah banner']);
                  }
                  return redirect(url('master/banner'));
                }else{
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Mohon memilih file banner!']);

                  return redirect(url('master/banner/create'));
                }

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

        // $data = [
        //     'title' => $request->title,
        //     'file' => $request->file,
        //     'description' => $request->description,
        //     'status' => $request->status,
        //     'link' => $request->link,
        //     'link_type' => $request->link_type,
        //     'status' => $request->status
        // ];

        // dd($data);
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
                    "data"           => $this->banner_model->getDetail($id)
                );

                $view     = View::make("backend.master.banner.edit", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Ubah Data Banner",
                    "description"   => $webname . " | Ubah Data Banner",
                    "keywords"      => $webname . " | Ubah Data Banner"
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
                $detail = $this->banner_model->getDetail($id);

                if ($request->hasFile('file_banner_update')) {
                    // catch file
                    $fileInput = $request->file('file_banner_update');
                    $banner_path = "uploads/" . md5($fileInput->getClientOriginalName()) . '-' . time() . '.' . $fileInput->getClientOriginalExtension();
                    $fileInput->move(public_path('uploads'), $banner_path);

                    $data = array(
                        "id_banner" => $id,
                        "title" => $request->title,
                        "description" => $request->description,
                        "last_update" => time(),
                        "status" => $request->status,
                        "banner_path" => $banner_path,
                        "link" => $request->link,
                        "link_type" => $request->link_type
                    );
                } else {
                    $data = array(
                        "id_banner" => $id,
                        "title" => $request->title,
                        "description" => $request->description,
                        "last_update" => time(),
                        "status" => $request->status,
                        "link" => $request->link,
                        "link_type" => $request->link_type
                    );
                }


                $rules  = array(
                    "title" => "required",
                    "status" => "required",
                    "link" => "required",
                    "link_type" => "required"
                );

                $messages = array(
                    "title" => "Mohon maaf, Anda harus mengisi bagian ini",
                    "status" => "Mohon maaf, Anda harus mengisi bagian ini",
                    "link" => "Mohon maaf, Anda harus mengisi bagian ini",
                    "link_type" => "Mohon maaf, Anda harus mengisi bagian ini"
                );

                $this->validate($request, $rules, $messages);

                $update = $this->banner_model->updateData($data);
                if ($update) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data banner!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data banner']);
                }
                return redirect(url('master/banner/edit/' . $id));
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

    public function remove($id, Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['remove'])) {
                $id = $request->input("id");
                $detail = $this->user_model->getDetail($id);
                $data = array(
                    "id_banner"      => $id,
                    "status"            => "deleted",
                    "last_update"       => time()
                );
                $remove = $this->banner_model->updateData($data);

                if ($remove) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data banner!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data banner']);
                }
                return redirect(url('master/banner'));
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
