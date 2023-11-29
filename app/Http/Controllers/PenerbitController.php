<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\IGWHelper;
use App\Http\Models\BannerModel;
use App\Http\Models\PenerbitModel;
use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
use App\Http\Models\UserModel;
use App\Http\Models\LaporanCampaignModel;
use App\Http\Models\SektorIndustriModel;
use App\Http\Controllers\LaporanCampaignController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class PenerbitController extends Controller
{
    //
    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $this->user_model       = new UserModel();
        $this->penerbit_model = new PenerbitModel();
        $this->sektorindutri_model = new SektorIndustriModel();
        $this->laporancampaign_model = new LaporanCampaignModel();

        $dataconfig['main_url'] = "master/penerbit";
        $dataconfig['view']     = "penerbit-view";
        $dataconfig['create']   = "penerbit-create";
        $dataconfig['edit']     = "penerbit-edit";
        $dataconfig['remove']   = "penerbit-remove";
        $dataconfig['manage']   = "penerbit-manage";
        $dataconfig['approve']   = "penerbit-approve";
        $dataconfig['deactivate']   = "penerbit-deactivate";

        $this->config           = $dataconfig;

        $laporanc               = new LaporanCampaignController();
        $this->months           = $laporanc->months;
    }

    public function index(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {
                $default    = array(
                    "short"     => "nama_penerbit",
                    "shortmode" => "asc"
                );
                $shorter = array(
                    "nama_penerbit"         => "Brand Name",
                    "kode_penerbit"         => "Brand Code",
                    "total_campaign"        => "Total Campaign",
                    "time_created"          => "Registered",
                    "last_update"           => "Last Update"
                );
                $page       = $request->input("page");
                $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword    = $request->input("keyword");
                $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
                $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
                $str        = ($page != "") ? (($page - 1) * $limit) : 0;
                $data       = $this->penerbit_model->getAllPenerbit($str, $limit, $keyword, $short, $shortmode);

                // dd($data);

                //exit();
                $totaldata  = count($data);
                $active     = $this->penerbit_model->getActiveUser();
                $inactive   = $this->penerbit_model->getInActiveUser();
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
                    "total_non_active"     => $inactive->count(),
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );


                $view     = View::make("backend.master.penerbit.index", $datacontent);
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
                    "data"           => $this->penerbit_model->getDetailPenerbit($id)
                );

                // dd($datacontent['data']);

                $view     = View::make("backend.master.penerbit.detail", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Detail Data Penerbit",
                    "description"   => $webname . " | Detail Data Penerbit",
                    "keywords"      => $webname . " | Detail Data Penerbit"
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

    public function manage($id, Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {

                $default    = array(
                    "short"     => "id_report",
                    "shortmode" => "asc"
                );

                $shorter = array(
                    "id_report"       => "Report Number",
                    "campaign_title"  => "Judul Campaign",
                    "nama_penerbit"   => "Nama Penerbit",
                    "report_code"     => "Kode Report",
                    "status"          => "Status"
                );

                $page       = $request->input("page");
                $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword    = $request->input("keyword");
                $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
                $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
                $str        = ($page != "") ? (($page - 1) * $limit) : 0;
                $laporan       = $this->laporancampaign_model->getDataReport($str, $limit, $keyword, $short, $shortmode,$id);
                // dd($data);

                //exit();
                $totaldata  = count($laporan);
                $pagging    = $this->helper->showPagging($totaldata, url($this->config['main_url']."/manage/".$id . '?keyword=' . $keyword . "&short=" . $short . "&shortmode=" . $shortmode), $position = "", $page, $limit, 2);


                $default_dev    = array(
                    "short_dev"     => "invoice_code",
                    "shortmode_dev" => "desc"
                );

                $shorter_dev = array(
                    "campaign_title"  => "Judul Campaign",
                    "nama_penerbit"   => "Nama Penerbit",
                    "invoice_code"    => "Kode Deviden",
                    "status"          => "Status"
                );

                $page_dev       = $request->input("page_dev");
                $limit_dev      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword_dev    = $request->input("keyword_dev");
                $short_dev      = ($request->input("short_dev") != "") ? $request->input("short_dev") : $default_dev['short_dev'];
                $shortmode_dev  = ($request->input("shortmode_dev") != "") ? $request->input("shortmode_dev") : $default_dev['shortmode_dev'];
                $str_dev        = ($page_dev != "") ? (($page_dev - 1) * $limit_dev) : 0;
                $deviden        = $this->deviden_model->getDataDeviden($str_dev, $limit_dev, $keyword_dev, $short_dev, $shortmode_dev,$id);
                $totaldata      = $this->deviden_model->countData($keyword_dev,$id);
                $pagging_dev    = $this->helper->showPagging($totaldata, url($this->config['main_url']."/manage/".$id . '?keyword_dev=' . $keyword_dev . "&short_dev=" . $short_dev . "&shortmode_dev=" . $shortmode_dev), $position = "", $page_dev, $limit_dev, 2);

                $datacontent = array(
                    "login"          => $login,
                    "helper_dev"     => "",
                    "helper"         => "",
                    "limit"          => $limit,
                    "limit_dev"          => $limit,
                    "laporan"        => $laporan,
                    "deviden"        => $deviden,
                    "pagging"        => $pagging,
                    "pagging_dev"    => $pagging_dev,
                    "input"          => $request->all(),
                    "default"        => $default,
                    "default_dev"    => $default_dev,
                    "shorter"        => $shorter,
                    "shorter_dev"    => $shorter_dev,
                    "page"           => $page,
                    "page_dev"       => $page_dev,
                    "months"         => $this->months,
                    "config"         => $this->config,
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    "data"           => $this->penerbit_model->getDetailPenerbit($id)
                );

                // dd($datacontent['data']);

                $view     = View::make("backend.master.penerbit.manage", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Detail Data Penerbit",
                    "description"   => $webname . " | Detail Data Penerbit",
                    "keywords"      => $webname . " | Detail Data Penerbit"
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
                    "sektor_industri" => $this->sektorindutri_model->getSektorIndustri()->pluck("nama_sektor_industri",'id_sektor_industri'),
                    "kode_penerbit"  => $this->penerbit_model->getPenerbitCode()
                );

                // dd($datacontent);

                $view     = View::make("backend.master.penerbit.create", $datacontent);
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

                $data = [
                    'nama_penerbit' => $request->nama_penerbit,
                    'kode_penerbit' => $request->kode_penerbit,
                    'alamat'        => $request->alamat,
                    'no_telp'       => $request->no_telp,
                    'time_created'  => time(),
                    'last_update'   => time(),
                    'status'        => $request->status,
                    'siup'          => @$request->siup,
                    'nib'           => @$nib_path,
                    'pic_name'      => $request->pic_name,
                    'pic_telp'      => $request->pic_telp,
                    'id_sektor_industri' => $request->id_sektor_industri,
                    'longitude'     => $request->longitude,
                    'latitude'      => $request->latitude
                ];


                $rules  = array(
                    'nama_penerbit' => "required",
                    'kode_penerbit' => "required|unique:tb_penerbit",
                    'pic_name'      => "required",
                    'pic_telp'      => "required",
                    'id_sektor_industri' => "required",
                );

                $messages = array(
                    'nama_penerbit.required'     => "Please fill this field!",
                    'kode_penerbit.required'     => "Please fill this field!",
                    'alamat.required'            => "Please fill this field!",
                    'no_telp.required'           => "Please fill this field!",
                    'status.required'            => "Please fill this field!",
                    'pic_name.required'          => "Please fill this field!",
                    'pic_telp.required'          => "Please fill this field!",
                    'id_sektor_industri.required' => "Please fill this field!",
                );

                if($request->hasFile('brand_avatar')){
                    $uploadedFile = $request->file('brand_avatar');
                    $path         = public_path('brands');
                    $filename     = time()."_".$uploadedFile->getClientOriginalName();
                    $file         = $uploadedFile->move($path, $filename);
                    $data['photos'] = url("public/brands/".$filename);
                }

                $this->validate($request, $rules, $messages);

                $create_penerbit                 = $this->penerbit_model::insert($data);

                if ($create_penerbit) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully added!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to add data!']);
                }
                return redirect(url('master/penerbit'));
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

    public function update($id, Request $request)
    {
        $login    = Session::get("user");


        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

               $data = [
                    'id_penerbit'     => $id,
                    'nama_penerbit'   => $request->nama_penerbit,
                    'kode_penerbit'   => $request->kode_penerbit,
                    'alamat'          => $request->alamat,
                    'no_telp'         => $request->no_telp,
                    'time_created'    => time(),
                    'last_update'     => time(),
                    'status'          => $request->status,
                    'pic_name'        => $request->pic_name,
                    'pic_telp'        => $request->pic_telp,
                    'id_sektor_industri' => $request->id_sektor_industri
                ];

                if ($request->hasFile('brand_avatar')) {
                    // catch file
                    $filePhotosInput = $request->file('brand_avatar');
                    $photos_path = md5($filePhotosInput->getClientOriginalName()) . '-' . time() . '.' . $filePhotosInput->getClientOriginalExtension();
                    $filePhotosInput->move(public_path('brands'), $photos_path);

                    $data['photos'] = $photos_path;
                }

                $rules  = array(
                    'nama_penerbit'     => "required",
                    "kode_penerbit"     => ["required",Rule::unique('tb_penerbit')->where("status","active")->ignore($id, 'id_penerbit')],
                    'no_telp'           => "required",
                    'pic_name'          => "required",
                    'pic_telp'          => "required",
                    'id_sektor_industri' => "required",
                );

                $messages = array(
                    'nama_penerbit.required'     => "Please fill this field!",
                    'kode_penerbit.required'     => "Please fill this field!",
                    'alamat.required'            => "Please fill this field!",
                    'no_telp.required'           => "Please fill this field!",
                    'status.required'            => "Please fill this field!",
                    'pic_name.required'          => "Please fill this field!",
                    'pic_telp.required'          => "Please fill this field!",
                    'id_sektor_industri.required' => "Please fill this field!",
                );

                $this->validate($request, $rules, $messages);

                $update_penerbit = $this->penerbit_model->updateData($data);

                if ($update_penerbit) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully updated!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to update data!']);
                }
                return redirect(url('master/penerbit/edit/'.$id));
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
                    "data"           => $this->penerbit_model->getDetailPenerbit($id),
                    "sektor_industri" => $this->sektorindutri_model->getSektorIndustri()
                );

                // dd($datacontent['data']);

                $view     = View::make("backend.master.penerbit.edit", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Detail Data Penerbit",
                    "description"   => $webname . " | Detail Data Penerbit",
                    "keywords"      => $webname . " | Detail Data Penerbit"
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

    public function approve($id)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['approve'])) {
                $detail = $this->user_model->getDetail($id);


                $data = array(
                    "id_penerbit"      => $id,
                    "status"            => "active",
                    "last_update"       => time(),
                );
                $approve = $this->penerbit_model->updateData($data);

                if ($approve) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses approve data penerbit!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat approve data penerbit']);
                }
                return redirect(url('master/penerbit'));
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

    public function deactivate($id)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['deactivate'])) {
                $detail = $this->user_model->getDetail($id);


                $data = array(
                    "id_penerbit"      => $id,
                    "status"            => "inactive",
                    "last_update"       => time(),
                );
                $approve = $this->penerbit_model->updateData($data);

                if ($approve) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses non-aktifkan data penerbit!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat non-aktifkan data penerbit']);
                }
                return redirect(url('master/penerbit'));
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
                    "id_penerbit"      => $id,
                    "status"            => "deleted",
                    "last_update"       => time(),
                );
                $approve = $this->penerbit_model->updateData($data);

                if ($approve) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses remove data penerbit!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat remove data penerbit']);
                }
                return redirect(url('master/penerbit'));
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

    public function findPenerbit(Request $request)
    {
        $data = $this->penerbit_model->findPenerbit($request->search);
        if ($data) {
            foreach ($data as $key => $value) {
                $dataret[] = array(
                    "id"    => $value->id_penerbit,
                    "text"  => $value->nama_penerbit . " - " . $value->kode_penerbit
                );
            }

            $return = array(
                "results"     => $dataret,
                "pagination"  => array(
                    "more"  => false
                )
            );

            return response()->json($return, 200);
        }
    }
}
