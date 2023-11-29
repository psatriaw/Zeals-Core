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
use App\Http\Models\DevidenModel;
use App\Http\Models\PenerbitModel;
use App\Http\Models\BankModel;
use App\Http\Models\XenditPayoutModel;
use App\Http\Controllers\LaporanCampaignController;


class TagihanController extends Controller{
  private $config;
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $merchant_model;
  private $user_model;
  private $naikanrumus_model;
  private $naikanrumus_items_model;
  private $product_model;
  private $cetakan_model;

  public function __construct(){
    $config['main_url'] = "master/tagihan";
    $config['create']   = "tagihan-create";
    $config['edit']     = "tagihan-edit";
    $config['view']     = "tagihan-view";
    $config['manage']   = "tagihan-manage";
    $config['remove']   = "tagihan-remove";
    $config['restore']  = "tagihan-restore";
    $config['control']  = "tagihan-control";

    $this->config       = $config;

    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->user_model       = new UserModel();
    $this->deviden_model    = new DevidenModel();
    $this->penerbit_model   = new PenerbitModel();
    $this->bank_model       = new BankModel();
    $this->payout_model     = new XenditPayoutModel();

    $laporancontroller      = new LaporanCampaignController();
    $this->months           = $laporancontroller->months;
    $this->years            = $laporancontroller->years;
  }

  public function index(Request $request){
    $login    = Session::get("user");
    // dd($login);
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $default    = array(
            "short"     => "invoice_code",
            "shortmode" => "desc"
        );
        $shorter = array(
            "invoice_code"        => "Kode Deviden",
            "campaign_title"      => "Saham",
            "first_name"          => "Akun",
            "nama_penerbit"       => "Penerbit",
            "time_created"        => "Tgl Terdaftar",
            "last_update"         => "Terakhir Diperbarui"
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
        $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
        $str        = ($page != "") ? (($page - 1) * $limit) : 0;

        $id_penerbit  = @$_GET['id_penerbit'];

        $checkis_penerbit = $this->penerbit_model->checkUserIsPenerbit($login->id_user);
        if($checkis_penerbit){
          $id_penerbit = $checkis_penerbit->id_penerbit;
        }

        $data         = $this->deviden_model->getData($str, $limit, $keyword, $short, $shortmode,$id_penerbit);

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
            'months'         => $this->months,
            'penerbit'       => $this->penerbit_model->getPenerbitActive(),
            "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.tagihan.index", $datacontent);
        $content  = $view->render();

        $metadata = array(
            "title"         => $webname . " |  Manajemen Deviden",
            "description"   => $webname . " |  Manajemen Deviden",
            "keywords"      => $webname . " |  Manajemen Deviden"
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

  public function payment($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $reports = $this->deviden_model->getLaporansOnDeviden($id);
        $detail  = $this->deviden_model->getDetail($id);
        //print_r((object)$reports);
        //exit();
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $detail,
          "config"         => $this->config,
          "months"         => $this->months,
          "years"          => $this->years,
          "laporans"       => $reports,
          "bank"           => $this->bank_model->getBankList(),
          "payment"        => $this->payout_model->getDetail($detail->id_payment)
        );

        $view     = View::make("backend.master.tagihan.payment",$datacontent);
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
}
