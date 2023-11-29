<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\IGWHelper;
use App\Http\Models\CampaignModel;
use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
use App\Http\Models\UserModel;
use App\Http\Models\SektorIndustriModel;
use App\Http\Models\LaporanCampaignModel;
use App\Http\Models\CampaignProgramModel;

use App\Http\Models\OutletModel;
use App\Http\Models\BankModel;
use App\Http\Models\PenerbitModel;
use App\Http\Models\XenditPayoutModel;
use App\Http\Models\WilayahModel;
use App\Http\Models\CampaignPropertyModel;
use App\Http\Models\CampaignTrackerModel;
use App\Http\Models\CampaignJoinModel;
use App\Http\Models\CampaignComponentModel;
use App\Http\Models\VoucherModel;
use App\Http\Controllers\apis\Registration;

use App\Http\Controllers\LaporanCampaignController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CampaignController extends Controller
{
    //
    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $this->user_model       = new UserModel();
        $this->campaign_model         = new CampaignModel();
        $this->laporancampaign_model  = new LaporanCampaignModel();

        $this->outlet_model           = new OutletModel();
        $this->bank_model             = new BankModel();
        $this->payout_model           = new XenditPayoutModel();
        $this->penerbit_model         = new PenerbitModel();
        $this->sektor_industri_model  = new SektorIndustriModel();
        $this->wilayah_model                = new WilayahModel();
        $this->campaign_property_model      = new CampaignPropertyModel();
        $this->campaign_program_model       = new CampaignProgramModel();
        $this->campaign_tracker_model       = new CampaignTrackerModel();
        $this->campaign_join_model          = new CampaignJoinModel();
        $this->voucher_model          = new VoucherModel();
        $this->custom_field_model     = new CampaignComponentModel();

        $dataconfig['main_url'] = "master/campaign";
        $dataconfig['view']     = "campaign-view";
        $dataconfig['create']   = "campaign-create";
        $dataconfig['edit']     = "campaign-edit";
        $dataconfig['remove']   = "campaign-remove";
        $dataconfig['manage']   = "campaign-manage";
        $dataconfig['approve']   = "campaign-approve";
        $dataconfig['deactivate']   = "campaign-deactivate";
        $dataconfig['boost']    = "campaign-boost";

        $this->config           = $dataconfig;

        $laporanc               = new LaporanCampaignController();
        $this->months           = $laporanc->months;

    }

    public function masterQR(Request $request, $permalink = ""){
      $session = Session::get("masterqr");
      if($session==""){
          //tembak
          $url 	= url('api/regis3rdparty');//'https://app.zeals.asia/api/regis3rdparty';

          $code = "SUPERQRAFF";
          if($request->aff!=""){
            $code = $request->aff;
          }

          $data 	= array(
          	"first_name"	 => $this->user_model->getRandom('name'),
          	"username"		 => $this->user_model->getRandom('username'),
          	"email"			   => $this->user_model->getRandom('email')."@zeals.asia",
          	"phone"			   => "08",
          	"password"		 => "ZA12345678",
          	"google_id"		 => "",
          	"department_code" => $code,
            "verification"    => "no"
          );
          //dd($url);
          $postdata = $request->merge($data);
          $register = (new Registration())->regist3rdparty($postdata);

          if($register['status']=="success"){
            Session::put("masterqr",$this->user_model->getDetail($register['credential']));
            $session = Session::get("masterqr");
            Session::put("aff",$session);
          }

          if($session){
            if($permalink!=""){
              return redirect(url('campaign/detail/'.$permalink));
            }else{
              return redirect(url('campaign'));
            }
          }
	        //tembak
      }else{
        Session::put("aff",$session);
        if($permalink!=""){
          return redirect(url('campaign/detail/'.$permalink));
        }else{
          return redirect(url('campaign'));
        }
      }
    }

    public function searchcampaign(Request $request){

      $data = $this->campaign_model->findCampaignByIDPenerbit($request->search,$request->id_penerbit);
      if($data){
        foreach ($data as $key => $value) {
          $dataret[] = array(
            "id"    => $value->id_campaign,
            "text"  => $value->campaign_title
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

    public function index(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {
                $default    = array(
                    "short"     => "time_created",
                    "shortmode" => "desc"
                );
                $shorter = array(
                    "campaign_title"  => "Campaign Title",
                    "start_date"      => "Start Campaign",
                    "end_date"        => "End Campaign",
                    "campaign_type"   => "Campaign Type",
                    "time_created"    => "Campaign Created",
                    "joined"          => "Total Joined Affiliator",
                    "running_budget"  => "Running Budget",
                    "system_rev"      => "Revenue",
                    "total_reach"     => "Total Reach",
                    "total_visit"     => "Total Visit",
                    "total_acquisition"     => "Total Acquisition",
                );

                $page       = $request->input("page");
                $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword    = $request->input("keyword");
                $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
                $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
                $str        = ($page != "") ? (($page - 1) * $limit) : 0;

                $checkis_penerbit = $this->penerbit_model->checkUserIsPenerbit($login->id_user);
                if($checkis_penerbit){
                  $id_penerbit = $checkis_penerbit->id_penerbit;
                }

                $dates = $request->dates;
                if($dates){
                  $dates = explode(" - ",$dates);
                  $start_date = $dates[0];
                  $end_date   = $dates[1];
                }else{
                  $start_date = date("d-m-Y", time()-(7*24*60*0));
                  $end_date   = date("d-m-Y");
                }

                if($dates!=""){
                  $data       = $this->campaign_model->getAvailableCampaign2($str, $limit, $keyword, $short, $shortmode, @$login->id_brand, strtotime($start_date." 00:00:00"), strtotime($end_date." 23:59:59"));
                }else{
                  $data       = $this->campaign_model->getAvailableCampaign2($str, $limit, $keyword, $short, $shortmode, @$login->id_brand);
                }

                
                
                $totaldata  = count($data);
                $total      = $this->campaign_model->getTotalCampaign(@$login->id_brand);
                $pagging    = $this->helper->showPagging($total, url($this->config['main_url'] . '?keyword=' . $keyword . "&short=" . $short . "&shortmode=" . $shortmode."&dates=".$start_date." - ".$end_date), $position = "", $page, $limit, 2);

                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "data"           => $data,
                    "start_date"     => $start_date,
                    "end_date"       => $end_date,
                    "dates"          => $request->dates,
                    "pagging"        => $pagging,
                    "input"          => $request->all(),
                    "default"        => $default,
                    "config"         => $this->config,
                    "shorter"        => $shorter,
                    "page"           => $page,
                    "limit"          => $limit,
                    "total_data"     => @$total,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                );


                $view     = View::make("backend.master.campaign.index", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " |  Manajemen Campaign",
                    "description"   => $webname . " |  Manajemen Campaign",
                    "keywords"      => $webname . " |  Manajemen Campaign"
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
                    "data"           => $this->campaign_model->getDetailCampaign($id)
                );

                // dd($datacontent['data']);

                $view     = View::make("backend.master.campaign.detail", $datacontent);
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
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['manage'])) {

              $data = $this->campaign_model->getDetailCampaign(url(''),$id);

              $default    = array(
                  "short"     => "time_created",
                  "shortmode" => "DESC"
              );

              $shorter = array(
                  "id_report"       => "Report Number",
                  "campaign_title"  => "Judul Campaign",
                  "nama_penerbit"   => "Nama Penerbit",
                  "report_code"     => "Kode Report",
                  "status"          => "Status",
                  "time_created"    => "Tanggal Upload"
              );

              $page       = $request->input("page");
              $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
              $keyword    = $request->input("keyword");
              $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
              $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
              $str        = ($page != "") ? (($page - 1) * $limit) : 0;
              $laporan       = $this->laporancampaign_model->getDataReport($str, $limit, $keyword, $short, $shortmode,$data->id_penerbit, $data->id_campaign);
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
                  "status"          => "Status",
              );

              $page_dev       = $request->input("page_dev");
              $limit_dev      = $this->setting_model->getSettingVal("limit_data_perpage");
              $keyword_dev    = $request->input("keyword_dev");
              $short_dev      = ($request->input("short_dev") != "") ? $request->input("short_dev") : $default_dev['short_dev'];
              $shortmode_dev  = ($request->input("shortmode_dev") != "") ? $request->input("shortmode_dev") : $default_dev['shortmode_dev'];
              $str_dev        = ($page_dev != "") ? (($page_dev - 1) * $limit_dev) : 0;
              $deviden        = $this->deviden_model->getDataDeviden($str_dev, $limit_dev, $keyword_dev, $short_dev, $shortmode_dev,$data->id_penerbit);
              $totaldata      = $this->deviden_model->countData($keyword_dev,$data->id_penerbit);
              $pagging_dev    = $this->helper->showPagging($totaldata, url($this->config['main_url']."/manage/".$id . '?keyword_dev=' . $keyword_dev . "&short_dev=" . $short_dev . "&shortmode_dev=" . $shortmode_dev), $position = "", $page_dev, $limit_dev, 2);

              $rupss[]        = "Pilih";
              $datarups       = $this->rups_model->getRUPSByIDPenerbit($data->id_penerbit);
              //print $data->id_penerbit;
              //print_r($datarups);
              //exit();
              if($datarups){
                foreach ($datarups as $key => $value) {
                  $rupss[$key] = $value;
                }
              }

              $datacontent = array(
                  "login"          => $login,
                  "helper_dev"     => "",
                  "helper"         => "",
                  "rupss"          => $rupss,
                  "limit"          => $limit,
                  "limit_dev"      => $limit,
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
                  "data"           => $data,
                  "pemodals"       => $this->saham_model->getParaPembeliSaham($data->id_campaign)
              );

                // dd($datacontent['data']);

                $view     = View::make("backend.master.campaign.manage", $datacontent);
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

    public function setCampaignTarget($campaign_link){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

              $datacontent = array(
                  "login"          => $login,
                  "helper"         => "",
                  "previlege"      => $this->previlege_model,
                  "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                  "detail"         => $this->campaign_model->getDetailByCampaignLink($campaign_link),
                  "categories"     => $this->sektor_industri_model->getPluckPerusahaan(),
                  "domisili"       => $this->wilayah_model->getListKota()
              );

              // dd($datacontent);

              $view     = View::make("backend.master.campaign.set_target", $datacontent);
              $content  = $view->render();

              $metadata = array(
                  "title"         => $webname . " | Tambah Data Campaign",
                  "description"   => $webname . " | Tambah Data Campaign",
                  "keywords"      => $webname . " | Tambah Data Campaign"
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

    public function setCampaignOutlet($campaign_link){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
              $detail = $this->campaign_model->getDetailByCampaignLink($campaign_link);

              $datacontent = array(
                  "login"          => $login,
                  "helper"         => "",
                  "previlege"      => $this->previlege_model,
                  "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                  "detail"         => $detail,
                  "categories"     => $this->sektor_industri_model->getPluckPerusahaan(),
                  "domisili"       => $this->wilayah_model->getListKota(),
                  "outlets"        => $this->outlet_model->getListOutlet($detail->id_campaign)
              );

              // dd($datacontent);

              $view     = View::make("backend.master.campaign.set_outlet", $datacontent);
              $content  = $view->render();

              $metadata = array(
                  "title"         => $webname . " | Tambah Data Campaign",
                  "description"   => $webname . " | Tambah Data Campaign",
                  "keywords"      => $webname . " | Tambah Data Campaign"
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

    public function editoutlet($campaign_link,$id_outlet){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
              $detail = $this->campaign_model->getDetailByCampaignLink($campaign_link);

              $datacontent = array(
                  "login"          => $login,
                  "helper"         => "",
                  "previlege"      => $this->previlege_model,
                  "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                  "detail"         => $detail,
                  "outlet"         => $this->outlet_model->getDetail($id_outlet)
              );

              // dd($datacontent);

              $view     = View::make("backend.master.campaign.edit_outlet", $datacontent);
              $content  = $view->render();

              $metadata = array(
                  "title"         => $webname . " | Tambah Data Campaign",
                  "description"   => $webname . " | Tambah Data Campaign",
                  "keywords"      => $webname . " | Tambah Data Campaign"
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

    public function setprogram($campaign_link){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

              $datacontent = array(
                  "login"          => $login,
                  "helper"         => "",
                  "previlege"      => $this->previlege_model,
                  "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                  "detail"         => $this->campaign_model->getDetailByCampaignLink($campaign_link),
                  "categories"     => $this->sektor_industri_model->getPluckPerusahaan(),
                  "domisili"       => $this->wilayah_model->getListKota()
              );

              // dd($datacontent);

              $view     = View::make("backend.master.campaign.set_program", $datacontent);
              $content  = $view->render();

              $metadata = array(
                  "title"         => $webname . " | Tambah Data Campaign",
                  "description"   => $webname . " | Tambah Data Campaign",
                  "keywords"      => $webname . " | Tambah Data Campaign"
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
    public function setcomponent($campaign_link){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

              $datacontent = array(
                  "login"          => $login,
                  "helper"         => "",
                  "previlege"      => $this->previlege_model,
                  "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                  "detail"         => $this->campaign_model->getDetailByCampaignLink($campaign_link),
                  "categories"     => $this->sektor_industri_model->getPluckPerusahaan(),
                  "domisili"       => $this->wilayah_model->getListKota()
              );

              // dd($datacontent);

              $view     = View::make("backend.master.campaign.set_component", $datacontent);
              $content  = $view->render();

              $metadata = array(
                  "title"         => $webname . " | Tambah Data Campaign",
                  "description"   => $webname . " | Tambah Data Campaign",
                  "keywords"      => $webname . " | Tambah Data Campaign"
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

    public function resume($campaign_link){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create']) || $this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['manage'])) {
              $detail = $this->campaign_model->getDetailByCampaignLink($campaign_link);

              $datacontent = array(
                  "login"          => $login,
                  "helper"         => "",
                  "previlege"      => $this->previlege_model,
                  "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                  "detail"         => $detail,
                  "penerbits"      => $this->penerbit_model->getListPenerbit(),
                  "tracker_test"   => $this->campaign_tracker_model->getTestData($detail->id_campaign),
                  "callback_test"  => $this->campaign_tracker_model->getTotalTracker('acquisition',$detail->id_campaign),
                  "last_callback"  => $this->campaign_tracker_model->getLastTracker('acquisition',$detail->id_campaign),
                  "categories"     => $this->campaign_property_model->getPropertyBy('category',$detail->id_campaign),
                  "domisili"       => $this->campaign_property_model->getPropertyBy('location',$detail->id_campaign),
                  "test_link"      => $this->campaign_join_model->getLinkTest($detail->id_campaign),
                  "penerbit"       => $this->penerbit_model->getDetail($detail->id_penerbit),
                  "program"        => array(
                    "visit"         => $this->campaign_program_model->getProgramByCampaign('visit',$detail->id_campaign),
                    "read"          => $this->campaign_program_model->getProgramByCampaign('read',$detail->id_campaign),
                    "action"        => $this->campaign_program_model->getProgramByCampaign('action',$detail->id_campaign),
                    "acquisition"   => $this->campaign_program_model->getProgramByCampaign('acquisition',$detail->id_campaign),
                    "voucher"       => $this->campaign_program_model->getProgramByCampaign('voucher',$detail->id_campaign),
                    "cashback"      => $this->campaign_program_model->getProgramByCampaign('cashback',$detail->id_campaign),
                  )
              );

              //if($detail->campaign_type=="o2o" || $detail->campaign_type=="event"){
                $datacontent['outlets'] = $this->outlet_model->getListOutlet($detail->id_campaign);
              //}

              // dd($datacontent);

              $view     = View::make("backend.master.campaign.resume", $datacontent);
              $content  = $view->render();

              $metadata = array(
                  "title"         => $webname . " | Tambah Data Campaign",
                  "description"   => $webname . " | Tambah Data Campaign",
                  "keywords"      => $webname . " | Tambah Data Campaign"
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

    public function reportoutlet($campaign_link, $merchant_code){
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
                $detail = $this->campaign_model->getDetailByCampaignLink($campaign_link);

                  $voucherinfo = $this->campaign_program_model->getProgramByCampaign('voucher',$detail->id_campaign);
                  $allfee      = $voucherinfo->commission + $voucherinfo->fee;
                    //print $detail->id_campaign." AND ".$merchant_code;
                    //exit();
                    $datacontent = array(
                      "login"          => $login,
                      "helper"         => "",
                      "previlege"      => $this->previlege_model,
                      "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                      "detail"         => $detail,
                      "voucher_logs"   => $this->voucher_model->getAllVoucherUsage($detail->id_campaign,$merchant_code),
                    );
                  // dd($datacontent);

                  $view     = View::make("backend.master.campaign.report_o2o_outlet", $datacontent);
                  $content  = $view->render();



                $metadata = array(
                    "title"         => $webname . " | Tambah Data Campaign",
                    "description"   => $webname . " | Tambah Data Campaign",
                    "keywords"      => $webname . " | Tambah Data Campaign"
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

    public function export($campaign_link){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
              $detail = $this->campaign_model->getDetailByCampaignLink($campaign_link);
              $voucherinfo = $this->campaign_program_model->getProgramByCampaign('voucher',$detail->id_campaign);
              $allfee      = $voucherinfo->commission + $voucherinfo->fee;

              $logs        = $this->voucher_model->getAllVoucherUsage($detail->id_campaign,"","used");
              $affiliator  = $this->campaign_join_model->getPerformanceofAff($detail->id_campaign);

              $statistic_visit        = $this->campaign_tracker_model->getTotalTracker('visit',$detail->id_campaign);
              $statistic_read         = $this->campaign_tracker_model->getTotalTracker('read',$detail->id_campaign);

              $statistic_voucher      = $this->voucher_model->getTotalVoucher('active',"","",$detail->id_campaign);
              $statistic_used         = $this->voucher_model->getTotalVoucher('used',"","",$detail->id_campaign);
              $statistic_reach        = $this->campaign_tracker_model->getTotalTracker('initial',$detail->id_campaign);



              $datacontent['statistic']['reach'] = array(
                "total"       => $statistic_reach,
                "percent"     => 0,
                "items"       => $statistic_reach
              );


              for($i=29;$i>0;$i--) {
                $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
              }
              $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
              $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',0, 12*60*60,$detail->id_campaign);

              for($i=29;$i>0;$i--) {
                $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
              }
              $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
              $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',0, 12*60*60,$detail->id_campaign);

              for($i=29;$i>0;$i--) {
                $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
              }
              $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
              $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',0, 12*60*60,$detail->id_campaign);

              for($i=29;$i>0;$i--) {
                $datacontent['chart']['voucher'][] = $this->voucher_model->getTotalVoucher('active',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
              }
              $datacontent['chart']['voucher'][] = $this->voucher_model->getTotalVoucher('active',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
              $datacontent['chart']['voucher'][] = $this->voucher_model->getTotalVoucher('active',0, 12*60*60,$detail->id_campaign);

              for($i=29;$i>0;$i--) {
                $datacontent['chart']['usage'][] = $this->voucher_model->getTotalVoucher('used',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
              }
              $datacontent['chart']['usage'][] = $this->voucher_model->getTotalVoucher('used',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
              $datacontent['chart']['usage'][] = $this->voucher_model->getTotalVoucher('used',0, 12*60*60,$detail->id_campaign);

              //if($statistic_visit>0){
                $datacontent['statistic']['visit'] = array(
                  "total"       => $statistic_visit,
                  "percent"     => 0,
                  "items"       => $this->campaign_program_model->getProgramByCampaign('visit',$detail->id_campaign)
                );

                $datacontent['statistic']['read'] = array(
                  "total"       => $statistic_read,
                  "percent"     => 0,
                  "items"       => $this->campaign_program_model->getProgramByCampaign('read',$detail->id_campaign)
                );

                $datacontent['statistic']['voucher'] = array(
                  "total"       => $statistic_voucher,
                  "percent"     => 0,
                  "items"       => $this->campaign_program_model->getProgramByCampaign('voucher',$detail->id_campaign)
                );

                if($statistic_voucher>0){
                  $statistic_used_percent = ($statistic_used*100)/$statistic_voucher;
                }else{
                  $statistic_used_percent = 0;
                }

                $datacontent['statistic']['usage'] = array(
                  "total"       => $statistic_used,
                  "percent"     => 0,
                  "items"       => $this->campaign_program_model->getProgramByCampaign('voucher',$detail->id_campaign)
                );

              //}

              // dd($datacontent);
              for($i=29;$i>=0;$i--) {
                $label_chart[] = ($i+1)." days ago";
              }
              $label_chart[] = "Under 12 hours";
              $datacontent['label_chart']  = $label_chart;


              $data = array(
                  "time_created"      => time(),
                  "last_update"       => time(),
              );


              $spreadsheet = new Spreadsheet();
              $sheet = $spreadsheet->getActiveSheet();

              $sheet->setCellValue('A1', 'Export Data : ' . date('Y-m-d', time()));
              // make judul sheet
              $sheet->setCellValue('A2', 'No.')->getStyle('A2')->getFont()->setBold(true);
              $sheet->setCellValue('B2', 'Time')->getStyle('B2')->getFont()->setBold(true);
              $sheet->setCellValue('C2', 'Outlet')->getStyle('C2')->getFont()->setBold(true);
              $sheet->setCellValue('D2', 'Voucher Code')->getStyle('D2')->getFont()->setBold(true);
              //$sheet->setCellValue('E2', 'City')->getStyle('E2')->getFont()->setBold(true);
              $sheet->setCellValue('E2', 'IP')->getStyle('F2')->getFont()->setBold(true);
              $sheet->setCellValue('F2', 'Name')->getStyle('F2')->getFont()->setBold(true);
              $sheet->setCellValue('G2', 'Email')->getStyle('G2')->getFont()->setBold(true);
              $sheet->setCellValue('H2', 'Phone')->getStyle('H2')->getFont()->setBold(true);
              $sheet->setCellValue('I2', 'Address')->getStyle('I2')->getFont()->setBold(true);
              $sheet->setCellValue('J2', 'Role/Company')->getStyle('J2')->getFont()->setBold(true);
              $sheet->setCellValue('K2', 'Affiliator/Source')->getStyle('J2')->getFont()->setBold(true);
              $sheet->setCellValue('L2', 'Disclaimer')->getStyle('J2')->getFont()->setBold(true);
              $sheet->setCellValue('M2', 'Source')->getStyle('M2')->getFont()->setBold(true);
              $sheet->setCellValue('N2', 'Other Source')->getStyle('N2')->getFont()->setBold(true);
              $sheet->setCellValue('O2', 'Date of Birth')->getStyle('O2')->getFont()->setBold(true);
              $sheet->setCellValue('P2', 'Institution')->getStyle('P2')->getFont()->setBold(true);
              $sheet->setCellValue('Q2', 'Name of Institution')->getStyle('Q2')->getFont()->setBold(true);
              $sheet->setCellValue('R2', 'Division/Class')->getStyle('R2')->getFont()->setBold(true);

              $indexSheet = 3;
              $indexNumber = 1;
              foreach ($logs as $ud) {
                  $sheet->setCellValue('A' . $indexSheet, $indexNumber);
                  $sheet->setCellValue('B' . $indexSheet, date("d/m/Y H:i:s A",@$ud->time_usage));
                  $sheet->setCellValue('C' . $indexSheet, $ud->outlet_name);
                  $sheet->setCellValue('D' . $indexSheet, $ud->voucher_code);

                  $sheet->setCellValue('E' . $indexSheet, $ud->ip);
                  $sheet->setCellValue('F' . $indexSheet, $ud->optin_name);
                  $sheet->setCellValue('G' . $indexSheet, $ud->optin_email);
                  $sheet->setCellValue('H' . $indexSheet, $ud->optin_phone);
                  $sheet->setCellValue('I' . $indexSheet, $ud->optin_address);
                  $sheet->setCellValue('J' . $indexSheet, $ud->additional_1);
                  $sheet->setCellValue('K' . $indexSheet, $ud->affiliator_name);
                  $sheet->setCellValue('L' . $indexSheet, $ud->disclaimer);
                  $sheet->setCellValue('M' . $indexSheet, $ud->optin_source);
                  $sheet->setCellValue('N' . $indexSheet, $ud->optin_other_source);
                  $sheet->setCellValue('O' . $indexSheet, $ud->optin_dob);
                  $sheet->setCellValue('P' . $indexSheet, $ud->optin_institution);
                  $sheet->setCellValue('Q' . $indexSheet, $ud->optin_institution_name);
                  $sheet->setCellValue('R' . $indexSheet, $ud->optin_institution_division);

                  $indexSheet++;
                  $indexNumber++;
              }

              $sheet->getColumnDimension('B')->setAutoSize(true);
              $sheet->getColumnDimension('C')->setAutoSize(true);
              $sheet->getColumnDimension('D')->setAutoSize(true);
              $sheet->getColumnDimension('E')->setAutoSize(true);
              $sheet->getColumnDimension('F')->setAutoSize(true);
              $sheet->getColumnDimension('G')->setAutoSize(true);
              $sheet->getColumnDimension('H')->setAutoSize(true);
              $sheet->getColumnDimension('I')->setAutoSize(true);
              $sheet->getColumnDimension('J')->setAutoSize(true);
              $sheet->getColumnDimension('M')->setAutoSize(true);
              $sheet->getColumnDimension('N')->setAutoSize(true);
              $sheet->getColumnDimension('O')->setAutoSize(true);
              $sheet->getColumnDimension('P')->setAutoSize(true);
              $sheet->getColumnDimension('Q')->setAutoSize(true);
              $sheet->getColumnDimension('R')->setAutoSize(true);


              $file_name = 'export_'.$campaign_link. '_'. date('Y-m-d_H:i:s', time()) .'.xlsx';

              $writer = new Xlsx($spreadsheet);
              $writer->save(public_path('report/' . $file_name));

              Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'File export ready please download from here <a href="'.url('report/' . $file_name).'">[Download]</a>']);
              return redirect(url('master/campaign/report/'.$campaign_link));
        }
      }
    }

    public function exportredemption($campaign_link){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
              $detail = $this->campaign_model->getDetailByCampaignLink($campaign_link);

              $allfee      = $voucherinfo->commission + $voucherinfo->fee;

              $logs        = $this->voucher_model->getAllVoucherUsage($detail->id_campaign,"","used");

              $data = array(
                  "time_created"      => time(),
                  "last_update"       => time(),
              );


              $spreadsheet = new Spreadsheet();
              $sheet = $spreadsheet->getActiveSheet();

              $sheet->setCellValue('A1', 'Export Redemptions: ' . date('Y-m-d', time()));
              // make judul sheet
              $sheet->setCellValue('A2', 'No.')->getStyle('A2')->getFont()->setBold(true);
              $sheet->setCellValue('B2', 'Time')->getStyle('B2')->getFont()->setBold(true);
              $sheet->setCellValue('C2', 'Outlet')->getStyle('C2')->getFont()->setBold(true);
              $sheet->setCellValue('D2', 'Voucher Code')->getStyle('D2')->getFont()->setBold(true);
              //$sheet->setCellValue('E2', 'City')->getStyle('E2')->getFont()->setBold(true);
              $sheet->setCellValue('E2', 'IP')->getStyle('F2')->getFont()->setBold(true);
              $sheet->setCellValue('F2', 'Name')->getStyle('F2')->getFont()->setBold(true);
              $sheet->setCellValue('G2', 'Email')->getStyle('G2')->getFont()->setBold(true);
              $sheet->setCellValue('H2', 'Phone')->getStyle('H2')->getFont()->setBold(true);
              $sheet->setCellValue('I2', 'Address')->getStyle('I2')->getFont()->setBold(true);
              $sheet->setCellValue('J2', 'Role/Company')->getStyle('J2')->getFont()->setBold(true);
              $sheet->setCellValue('K2', 'Affiliator/Source')->getStyle('K2')->getFont()->setBold(true);
              $sheet->setCellValue('L2', 'Disclaimer')->getStyle('L2')->getFont()->setBold(true);
              $sheet->setCellValue('M2', 'Source')->getStyle('M2')->getFont()->setBold(true);
              $sheet->setCellValue('N2', 'Other Source')->getStyle('N2')->getFont()->setBold(true);
              $sheet->setCellValue('O2', 'Date of Birth')->getStyle('O2')->getFont()->setBold(true);

              $indexSheet = 3;
              $indexNumber = 1;
              foreach ($logs as $ud) {
                  $sheet->setCellValue('A' . $indexSheet, $indexNumber);
                  $sheet->setCellValue('B' . $indexSheet, date("d/m/Y H:i:s A",@$ud->time_usage));
                  $sheet->setCellValue('C' . $indexSheet, $ud->outlet_name);
                  $sheet->setCellValue('D' . $indexSheet, $ud->voucher_code);

                  $sheet->setCellValue('E' . $indexSheet, $ud->ip);
                  $sheet->setCellValue('F' . $indexSheet, $ud->optin_name);
                  $sheet->setCellValue('G' . $indexSheet, $ud->optin_email);
                  $sheet->setCellValue('H' . $indexSheet, $ud->optin_phone);
                  $sheet->setCellValue('I' . $indexSheet, $ud->optin_address);
                  $sheet->setCellValue('J' . $indexSheet, $ud->additional_1);
                  $sheet->setCellValue('K' . $indexSheet, $ud->affiliator_name);
                  $sheet->setCellValue('L' . $indexSheet, $ud->disclaimer);
                  $sheet->setCellValue('M' . $indexSheet, $ud->optin_source);
                  $sheet->setCellValue('N' . $indexSheet, $ud->optin_other_source);
                  $sheet->setCellValue('O' . $indexSheet, $ud->optin_dob);

                  $indexSheet++;
                  $indexNumber++;
              }

              $sheet->getColumnDimension('B')->setAutoSize(true);
              $sheet->getColumnDimension('C')->setAutoSize(true);
              $sheet->getColumnDimension('D')->setAutoSize(true);
              $sheet->getColumnDimension('E')->setAutoSize(true);
              $sheet->getColumnDimension('F')->setAutoSize(true);
              $sheet->getColumnDimension('G')->setAutoSize(true);
              $sheet->getColumnDimension('H')->setAutoSize(true);
              $sheet->getColumnDimension('I')->setAutoSize(true);
              $sheet->getColumnDimension('J')->setAutoSize(true);
              $sheet->getColumnDimension('K')->setAutoSize(true);
              $sheet->getColumnDimension('L')->setAutoSize(true);
              $sheet->getColumnDimension('M')->setAutoSize(true);
              $sheet->getColumnDimension('N')->setAutoSize(true);
              $sheet->getColumnDimension('O')->setAutoSize(true);


              $file_name = 'export_'.$campaign_link. '_'. date('Y-m-d_H:i:s', time()) .'.xlsx';

              $writer = new Xlsx($spreadsheet);
              $writer->save(public_path('report/' . $file_name));

              Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'File export ready please download from here <a href="'.url('report/' . $file_name).'">[Download]</a>']);
              return redirect(url('master/campaign/report/'.$campaign_link));
        }
      }
    }

    public function report($campaign_link){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
              $detail = $this->campaign_model->getDetailByCampaignLink($campaign_link);

              $dates      = (@$_GET['dates'] == "") ? date("Y/m/d") . "-" . date("Y/m/d") : @$_GET['dates'];
              //print $dates;

              $thedates      = explode("-", $dates);
              $start      = @$thedates[0];
              $end        = @$thedates[1];

              if($detail->campaign_type=="banner"){
                $datacontent = array(
                    "login"          => $login,
                    "dates"          => $dates,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    "detail"         => $detail,
                    "logs"           => $this->campaign_tracker_model->getLast25Logs($detail->id_campaign),
                    "earning"        => array(
                      "estimation"    => $this->campaign_tracker_model->sumTotalEstimation($detail->id_campaign,"", $start, $end),
                      "total"         => $this->campaign_tracker_model->sumTotalEarning($detail->id_campaign,"", $start, $end),
                    ),
                    'affiliators'    => $this->campaign_join_model->getPerformanceofAff($detail->id_campaign, $start, $end)
                );

                $statistic_visit        = $this->campaign_tracker_model->getTotalTracker('visit',$detail->id_campaign);
                $statistic_read         = $this->campaign_tracker_model->getTotalTracker('read',$detail->id_campaign);
                $statistic_action       = $this->campaign_tracker_model->getTotalTracker('action',$detail->id_campaign);
                $statistic_acquisition  = $this->campaign_tracker_model->getTotalTracker('acquisition',$detail->id_campaign);
                $statistic_reach        = $this->campaign_tracker_model->getTotalTracker('initial',$detail->id_campaign);

                $datacontent['statistic']['reach'] = array(
                  "total"       => $statistic_reach,
                  "percent"     => 0,
                  "items"       => $statistic_reach
                );

                // $begin  = new DateTime($start);
                // $end    = new DateTime($end);
                // $end    = $end->modify( '+1 day' ); 

                // $interval   = new DateInterval('P1D');
                // $daterange  = new DatePeriod($begin, $interval ,$end);

                $startTime  = strtotime( $start );
                $endTime    = strtotime( $end );

                for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
                    $thisDate = date( 'Y-m-d', $i );

                    $cdate  = strtotime(date("Y-m-d",$i)." 00:00:00");
                    $cpdate = strtotime(date("Y-m-d",$i)." 23:59:59");

                    $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',$cdate, $cpdate,$detail->id_campaign);
                    $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',$cdate, $cpdate,$detail->id_campaign);
                    $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',$cdate, $cpdate,$detail->id_campaign);
                    $datacontent['chart']['action'][] = $this->campaign_tracker_model->getTransactionTotalInRange('action',$cdate, $cpdate,$detail->id_campaign);
                    $datacontent['chart']['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRange('acquisition',$cdate, $cpdate,$detail->id_campaign);

                    $label_chart[] = date("Y-m-d",$i);
                }

                // print "<pre>";
                // print_r($label_chart);
                // print_r($datacontent['chart']);
                // exit();

                // foreach($daterange as $date){
                //     $cdate  = strtotime($date->format("Y-m-d")." 00:00:00");
                //     $cpdate = strtotime($date->format("Y-m-d")." 23:59:59");

                //     $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',$cdate, $cpdate,$detail->id_campaign);
                //     $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',$cdate, $cpdate,$detail->id_campaign);
                //     $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',$cdate, $cpdate,$detail->id_campaign);
                //     $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('action',$cdate, $cpdate,$detail->id_campaign);
                //     $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('acquisition',$cdate, $cpdate,$detail->id_campaign);

                //     $label_chart[] = $cdate;
                // }

                // for($i=29;$i>0;$i--) {
                //   $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
                // }
                // $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
                // $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',0, 12*60*60,$detail->id_campaign);

                // for($i=29;$i>0;$i--) {
                //   $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
                // }
                // $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
                // $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',0, 12*60*60,$detail->id_campaign);

                // for($i=29;$i>0;$i--) {
                //   $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
                // }
                // $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
                // $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',0, 12*60*60,$detail->id_campaign);

                // for($i=29;$i>0;$i--) {
                //   $datacontent['chart']['action'][] = $this->campaign_tracker_model->getTransactionTotalInRange('action',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
                // }
                // $datacontent['chart']['action'][] = $this->campaign_tracker_model->getTransactionTotalInRange('action',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
                // $datacontent['chart']['action'][] = $this->campaign_tracker_model->getTransactionTotalInRange('action',0, 12*60*60,$detail->id_campaign);

                // for($i=29;$i>0;$i--) {
                //   $datacontent['chart']['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRange('acquisition',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
                // }
                // $datacontent['chart']['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRange('acquisition',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
                // $datacontent['chart']['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRange('acquisition',0, 12*60*60,$detail->id_campaign);

                //if($statistic_visit>0){

                  $datacontent['statistic']['visit'] = array(
                    "total"       => $statistic_visit,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('visit',$detail->id_campaign)
                  );


                  $datacontent['statistic']['read'] = array(
                    "total"       => $statistic_read,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('read',$detail->id_campaign)
                  );

                  $datacontent['statistic']['action'] = array(
                    "total"       => $statistic_action,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('action',$detail->id_campaign)
                  );

                  $datacontent['statistic']['acquisition'] = array(
                    "total"       => $statistic_acquisition,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('acquisition',$detail->id_campaign)
                  );

                //}

                // dd($datacontent);
                // for($i=29;$i>=0;$i--) {
                //   $label_chart[] = ($i+1)." days ago";
                // }
                // $label_chart[] = "Under 12 hours";
                $datacontent['label_chart']  = $label_chart;

                // dd($datacontent);

                $view     = View::make("backend.master.campaign.report", $datacontent);
                $content  = $view->render();

              }elseif($detail->campaign_type=="o2o"  || $detail->campaign_type=="event"){

                $voucherinfo = $this->campaign_program_model->getProgramByCampaign('voucher',$detail->id_campaign);
                $allfee      = $voucherinfo->commission + $voucherinfo->fee;

                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    "detail"         => $detail,
                    "logs"           => $this->campaign_tracker_model->getLast25Logs($detail->id_campaign),
                    "voucher_logs"   => $this->voucher_model->getAllVoucherUsage($detail->id_campaign),
                    "earning"        => array(
                      "estimation"    => $allfee*($this->voucher_model->getTotalVoucher("used",0,time()-(strtotime("01-".date("m-Y")." 00:00:00")),$detail->id_campaign)),
                      "total"         => $allfee*($this->voucher_model->getTotalVoucher("used","","",$detail->id_campaign)),
                    ),
                    'affiliators'    => $this->campaign_join_model->getPerformanceofAff($detail->id_campaign),
                    'helper'         => $this->helper
                );

                $statistic_visit        = $this->campaign_tracker_model->getTotalTracker('visit',$detail->id_campaign);
                $statistic_read         = $this->campaign_tracker_model->getTotalTracker('read',$detail->id_campaign);

                $statistic_voucher      = $this->voucher_model->getTotalVoucher('active',"","",$detail->id_campaign);
                $statistic_used         = $this->voucher_model->getTotalVoucher('used',"","",$detail->id_campaign);
                $statistic_reach        = $this->campaign_tracker_model->getTotalTracker('initial',$detail->id_campaign);



                $datacontent['statistic']['reach'] = array(
                  "total"       => $statistic_reach,
                  "percent"     => 0,
                  "items"       => $statistic_reach
                );


                for($i=29;$i>0;$i--) {
                  $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
                }
                $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
                $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial',0, 12*60*60,$detail->id_campaign);

                for($i=29;$i>0;$i--) {
                  $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
                }
                $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
                $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit',0, 12*60*60,$detail->id_campaign);

                for($i=29;$i>0;$i--) {
                  $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
                }
                $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
                $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read',0, 12*60*60,$detail->id_campaign);

                for($i=29;$i>0;$i--) {
                  $datacontent['chart']['voucher'][] = $this->voucher_model->getTotalVoucher('active',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
                }
                $datacontent['chart']['voucher'][] = $this->voucher_model->getTotalVoucher('active',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
                $datacontent['chart']['voucher'][] = $this->voucher_model->getTotalVoucher('active',0, 12*60*60,$detail->id_campaign);

                for($i=29;$i>0;$i--) {
                  $datacontent['chart']['usage'][] = $this->voucher_model->getTotalVoucher('used',($i*60*60*24)+1, ($i+1)*60*60*24,$detail->id_campaign);
                }
                $datacontent['chart']['usage'][] = $this->voucher_model->getTotalVoucher('used',(12*60*60)+1, 1*60*60*24,$detail->id_campaign);
                $datacontent['chart']['usage'][] = $this->voucher_model->getTotalVoucher('used',0, 12*60*60,$detail->id_campaign);

                //if($statistic_visit>0){
                  $datacontent['statistic']['visit'] = array(
                    "total"       => $statistic_visit,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('visit',$detail->id_campaign)
                  );

                  $datacontent['statistic']['read'] = array(
                    "total"       => $statistic_read,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('read',$detail->id_campaign)
                  );

                  $datacontent['statistic']['voucher'] = array(
                    "total"       => $statistic_voucher,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('voucher',$detail->id_campaign)
                  );

                  if($statistic_voucher>0){
                    $statistic_used_percent = ($statistic_used*100)/$statistic_voucher;
                  }else{
                    $statistic_used_percent = 0;
                  }

                  $datacontent['statistic']['usage'] = array(
                    "total"       => $statistic_used,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('voucher',$detail->id_campaign)
                  );

                //}

                // dd($datacontent);
                for($i=29;$i>=0;$i--) {
                  $label_chart[] = ($i+1)." days ago";
                }
                $label_chart[] = "Under 12 hours";
                $datacontent['label_chart']  = $label_chart;

                $view     = View::make("backend.master.campaign.report_o2o", $datacontent);
                $content  = $view->render();
              }


              $metadata = array(
                  "title"         => $webname . " | Tambah Data Campaign",
                  "description"   => $webname . " | Tambah Data Campaign",
                  "keywords"      => $webname . " | Tambah Data Campaign"
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

    public function create($kind)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                switch($kind){
                  case "banner":
                      $datacontent = array(
                          "login"          => $login,
                          "helper"         => "",
                          "previlege"      => $this->previlege_model,
                          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                          "penerbits"      => $this->penerbit_model->getListPenerbit(),
                          "categories"     => $this->sektor_industri_model->getPluckPerusahaan()
                      );

                      $view     = View::make("backend.master.campaign.create", $datacontent);
                  break;

                  case "o2o":
                      $datacontent = array(
                          "login"          => $login,
                          "helper"         => "",
                          "previlege"      => $this->previlege_model,
                          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                          "penerbits"      => $this->penerbit_model->getListPenerbit(),
                          "categories"     => $this->sektor_industri_model->getPluckPerusahaan()
                      );

                      $view     = View::make("backend.master.campaign.create", $datacontent);
                  break;

                  case "shopee":
                      $datacontent = array(
                          "login"          => $login,
                          "helper"         => "",
                          "previlege"      => $this->previlege_model,
                          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                          "penerbits"      => $this->penerbit_model->getListPenerbit(),
                          "categories"     => $this->sektor_industri_model->getPluckPerusahaan()
                      );

                      $view     = View::make("backend.master.campaign.create", $datacontent);
                  break;

                  case "event":
                    $datacontent = array(
                        "login"          => $login,
                        "helper"         => "",
                        "previlege"      => $this->previlege_model,
                        "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                        "penerbits"      => $this->penerbit_model->getListPenerbit(),
                        "categories"     => $this->sektor_industri_model->getPluckPerusahaan()
                    );

                    $view     = View::make("backend.master.campaign.create", $datacontent);
                break;

                }

                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Tambah Data Campaign",
                    "description"   => $webname . " | Tambah Data Campaign",
                    "keywords"      => $webname . " | Tambah Data Campaign"
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

    public function choose()
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );

                // dd($datacontent);

                $view     = View::make("backend.master.campaign.create_choose", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Tambah Data Campaign",
                    "description"   => $webname . " | Tambah Data Campaign",
                    "keywords"      => $webname . " | Tambah Data Campaign"
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
                if($request->hasFile('photos')){
                  $filePhotosInput = $request->file('photos');
                  $photos_path = "uploads/campaign/" . md5($filePhotosInput->getClientOriginalName()) . '-' . time() . '.' . $filePhotosInput->getClientOriginalExtension();
                  $filePhotosInput->move(public_path('uploads/campaign/'), $photos_path);
                }else{
                  $photos_path = "";
                }

                $campaign_link  = $this->campaign_model->createCode();

                $data = [
                    'campaign_title'        => $request->campaign_title,
                    'id_penerbit'           => $request->id_penerbit,
                    'photos'                => $photos_path,
                    'campaign_description'  => $request->campaign_description,
                    'campaign_instruction'  => $request->campaign_instruction,
                    'campaign_do_n_dont'    => $request->campaign_do_n_dont,
                    'start_date'            => $request->start_date,
                    'end_date'              => $request->end_date,
                    'budget'                => $request->budget,
                    'max_commission'        => $request->max_commission,
                    'campaign_type'         => $request->campaign_type,
                    'campaign_internal'     => $request->campaign_internal,
                    'time_created'          => time(),
                    'last_update'           => time(),
                    'campaign_link'         => $campaign_link
                ];

                $penerbit   = PenerbitModel::find($data['id_penerbit']);
                $data['affiliate_id']   = $penerbit->kode_penerbit;


                $rules  = array(
                    'campaign_title'        => 'required',
                    'id_penerbit'           => 'required',
                    'campaign_description'  => 'required',
                    'start_date'            => 'required',
                    'end_date'              => 'required',
                    'budget'                => 'required|numeric',
                    'max_commission'        => 'required|numeric'
                );

                $messages = array(
                    'campaign_title'        => 'Mohon isi bagian berikut!',
                    'id_penerbit'           => 'Mohon isi bagian berikut',
                    'campaign_description'  => 'Mohon isi bagian berikut!',
                    'start_date'            => 'Mohon isi bagian berikut!',
                    'end_date'              => 'Mohon isi bagian berikut!',
                    'budget.required'            => 'Mohon isi bagian berikut!',
                    'budget.numeric'             => 'Mohon isi bagian berikut dengan angka!',
                    'max_commission.required'    => 'Mohon isi bagian berikut!',
                    'max_commission.numeric'     => 'Mohon isi bagian berikut dengan angka!',
                );

                $this->validate($request, $rules, $messages);

                $create_campaign                 = $this->campaign_model::insert($data);

                if ($create_campaign) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Yes! Your campaign detail created! Please define the program for this campaign.']);
                    if($request->campaign_type=='event'){
                      return redirect(url('master/campaign/setcomponent/'.$campaign_link));
                    }else{
                      return redirect(url('master/campaign/setprogram/'.$campaign_link));
                    }
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to create campaign detail']);
                    return redirect(url('master/campaign/create/'.$data['campaign_type']));
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
    }
    public function storecomponent(Request $request)
    {
        $login    = Session::get("user");


        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                $id_campaign      = $request->id_campaign;
                $campaign_link    = $request->campaign_link;
                $detail_campaign  = $this->campaign_model->getDetail($id_campaign);

                $rules  = array(
                    'input_type'        => 'required',
                    'field_name'     => 'required',
                    'status'       => 'required',
                );

                $messages = array(
                    // 'id_categories'        => 'Mohon isi bagian berikut!',
                    // 'landing_url'          => 'Mohon isi bagian berikut!',
                );

                $this->validate($request, $rules, $messages);

                $data = array(
                  'id_campaign'   => $id_campaign,
                  'input_type'    => $request->input_type,
                  'field_name'    => $request->field_name,
                  'input_source'  => $request->input_source,
                  'rules'         => $request->rules,
                  'status'        => $request->status,
                );

                $this->custom_field_model->insertData($data);

                if($request->type_submit=="more"){
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Field created, Please add more input.']);
                  return redirect(url('master/campaign/setcomponent/'.$campaign_link));
                }else{
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Field created, set your campaign program!']);
                  return redirect(url('master/campaign/setprogram/'.$campaign_link));
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
    }
    public function storetarget(Request $request)
    {
        $login    = Session::get("user");


        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                $id_campaign      = $request->id_campaign;
                $campaign_link    = $request->campaign_link;
                $detail_campaign  = $this->campaign_model->getDetail($id_campaign);

                $rules  = array(
                    'id_categories'        => 'required',
                );

                if($detail_campaign->campaign_type=="banner"){
                  $rules['landing_url'] = 'required';
                }

                $messages = array(
                    'id_categories'        => 'Mohon isi bagian berikut!',
                    'landing_url'          => 'Mohon isi bagian berikut!',
                );

                $this->validate($request, $rules, $messages);

                $this->campaign_property_model->removeAllData($id_campaign);

                $updatecampaign = array(
                  "id_campaign"   => $id_campaign,
                  "tipe_url"      => $request->tipe_url,
                  "landing_url"   => $request->landing_url
                );

                $this->campaign_model->updateData($updatecampaign);

                if($request->id_domisili){
                  foreach ($request->id_domisili as $key => $value) {
                    $data_domisili = array(
                      "value"                 => $value,
                      'time_created'          => time(),
                      'last_update'           => time(),
                      "id_campaign"           => $id_campaign,
                      "property_type"         => 'location',
                      "status"                => "active"
                    );

                    $this->campaign_property_model->insertData($data_domisili);
                  }
                }

                if($request->id_categories){
                  foreach ($request->id_categories as $key => $value) {
                    $data_categories = array(
                      "value"                 => $value,
                      'time_created'          => time(),
                      'last_update'           => time(),
                      "id_campaign"           => $id_campaign,
                      "property_type"         => 'category',
                      "status"                => "active"
                    );

                    $this->campaign_property_model->insertData($data_categories);
                  }
                }

                //test link
                $datajoin = array(
                  "id_campaign"   => $id_campaign,
                  "time_created"  => time(),
                  "last_update"   => time(),
                  "id_user"       => 0,
                  "unique_link"   => $this->campaign_join_model->createLink()
                );

                $this->campaign_join_model->insertData($datajoin);
                //test link

                if($detail_campaign->campaign_type=="o2o" || $detail_campaign->campaign_type=="event"){
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Target successfully paired to the campaign. Please set the outlet for voucher redemption']);
                  return redirect(url('master/campaign/setoutlet/'.$campaign_link));
                }elseif($detail_campaign->campaign_type=="banner"){
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Target successfully paired to the campaign.']);
                  return redirect(url('master/campaign/resume/'.$campaign_link));
                }elseif($detail_campaign->campaign_type=="shopee"){
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Target successfully paired to the campaign.']);
                  return redirect(url('master/campaign/resume/'.$campaign_link));
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
    }

    public function storeoutlet(Request $request)
    {
        $login    = Session::get("user");


        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                $id_campaign      = $request->id_campaign;
                $campaign_link    = $request->campaign_link;
                $detail_campaign  = $this->campaign_model->getDetail($id_campaign);

                $rules  = array(
                    'outlet_name'        => 'required',
                    'outlet_address'     => 'required',
                    'outlet_phone'       => 'required',
                );

                $messages = array(
                    'id_categories'        => 'Mohon isi bagian berikut!',
                    'landing_url'          => 'Mohon isi bagian berikut!',
                );

                $this->validate($request, $rules, $messages);

                $data = array(
                  "id_campaign"             => $id_campaign,
                  "outlet_code"             => $this->outlet_model->createCode(),
                  "outlet_name"             => $request->outlet_name,
                  "outlet_address"          => $request->outlet_address,
                  "outlet_phone"            => $request->outlet_phone,
                  "max_redemption"          => $request->max_redemption,
                  "max_redemption_per_day"  => $request->max_redemption_per_day,
                  "longitude"               => $request->longitude,
                  "latitude"                => $request->latitude,
                  "time_created"            => time(),
                  "last_update"             => time()
                );

                $this->outlet_model->insertData($data);

                if($request->type_submit=="more"){
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Outlet created, Please add more outlet.']);
                  return redirect(url('master/campaign/setoutlet/'.$campaign_link));
                }else{
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Outlet created, your campaign is ready to run!']);
                  return redirect(url('master/campaign/resume/'.$campaign_link));
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
    }

    public function updateoutlet(Request $request)
    {
        $login    = Session::get("user");


        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

                $campaign_link    = $request->campaign_link;

                $rules  = array(
                    'outlet_name'        => 'required',
                    'outlet_address'     => 'required',
                    'outlet_phone'       => 'required',
                );

                $messages = array(
                    'id_categories'        => 'Mohon isi bagian berikut!',
                    'landing_url'          => 'Mohon isi bagian berikut!',
                );

                $this->validate($request, $rules, $messages);

                $data = array(
                  "id_outlet"               => $request->id_outlet,
                  "outlet_name"             => $request->outlet_name,
                  "outlet_address"          => $request->outlet_address,
                  "outlet_phone"            => $request->outlet_phone,
                  "max_redemption"          => $request->max_redemption,
                  "max_redemption_per_day"  => $request->max_redemption_per_day,
                  "longitude"               => $request->longitude,
                  "latitude"                => $request->latitude,
                  "last_update"             => time()
                );

                $this->outlet_model->updateData($data);

                Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Outlet updated']);
                return redirect(url('master/campaign/resume/'.$campaign_link));

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

    public function removeoutlet(Request $request, $campaign_link, $id_outlet)
    {
        $login    = Session::get("user");


        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['remove'])) {

                $campaign_link    = $campaign_link;

                $data = array(
                  "id_outlet"               => $request->id_outlet,
                  "status"                  => "deleted",
                  "last_update"             => time()
                );

                $this->outlet_model->updateData($data);

                Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Outlet removed']);
                return redirect(url('master/campaign/resume/'.$campaign_link));

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

    public function storeprogram(Request $request)
    {
        $login    = Session::get("user");


        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

                $id_campaign      = $request->id_campaign;
                $campaign_link    = $request->campaign_link;
                $detail_campaign  = $this->campaign_model->getDetail($id_campaign);

                if($detail_campaign->campaign_type=="banner"){
                  if($request->visit_total=="" && $request->reader_total=="" && $request->action_total=="" && $request->acquisition_total==""){
                    $rules  = array(
                        'visit_total'         => 'required',
                        'reader_total'        => 'required',
                        'action_total'        => 'required',
                        'acquisition_total'   => 'required',
                    );

                    $messages = array(
                        'visit_total.required'         => 'Please fill at least one of this programs',
                        'reader_total.required'        => 'Please fill at least one of this programs',
                        'action_total.required'        => 'Please fill at least one of this programs',
                        'acquisition_total.required'   => 'Please fill at least one of this programs'
                    );

                    $this->validate($request, $rules, $messages);
                  }

                  $this->campaign_program_model->removeAllData($id_campaign);

                  if($request->visit_total!=""){
                    $dataprogram = array(
                      "total_item"            => $request->visit_total,
                      'time_created'          => time(),
                      'last_update'           => time(),
                      "id_campaign"           => $id_campaign,
                      "type_program"          => 'visit',
                      "status"                => "active",
                      "commission"            => $request->visit_commission,
                      "fee"                   => $request->visit_fee,
                    );

                    $this->campaign_program_model->insertData($dataprogram);
                  }

                  if($request->reader_total!=""){
                    $dataprogram = array(
                      "total_item"            => $request->reader_total,
                      'time_created'          => time(),
                      'last_update'           => time(),
                      "id_campaign"           => $id_campaign,
                      "type_program"          => 'read',
                      "status"                => "active",
                      "commission"            => $request->reader_commission,
                      "fee"                   => $request->reader_fee,
                    );

                    $this->campaign_program_model->insertData($dataprogram);
                  }

                  if($request->action_total!=""){
                    $dataprogram = array(
                      "total_item"            => $request->action_total,
                      'time_created'          => time(),
                      'last_update'           => time(),
                      "id_campaign"           => $id_campaign,
                      "type_program"          => 'action',
                      "status"                => "active",
                      "commission"            => $request->action_commission,
                      "fee"                   => $request->action_fee,
                    );

                    $this->campaign_program_model->insertData($dataprogram);
                  }

                  if($request->acquisition_total!=""){
                    $dataprogram = array(
                      "total_item"            => $request->acquisition_total,
                      'time_created'          => time(),
                      'last_update'           => time(),
                      "id_campaign"           => $id_campaign,
                      "type_program"          => 'acquisition',
                      "status"                => "active",
                      "commission"            => $request->acquisition_commission,
                      "fee"                   => $request->acquisition_fee,
                    );

                    $this->campaign_program_model->insertData($dataprogram);
                  }
                }elseif($detail_campaign->campaign_type=="o2o" || $detail_campaign->campaign_type=="event"){
                  $this->campaign_program_model->removeAllData($id_campaign);

                  if($request->voucher_total!=""){

                    $dataprogram = array(
                      "total_item"            => $request->voucher_total,
                      'time_created'          => time(),
                      'last_update'           => time(),
                      "id_campaign"           => $id_campaign,
                      "type_program"          => 'voucher',
                      "status"                => "active",
                      "commission"            => $request->voucher_commission,
                      "fee"                   => $request->voucher_fee,
                    );

                    $this->campaign_program_model->insertData($dataprogram);
                  }else{

                    $rules  = array(
                        'voucher_total'         => 'required'
                    );

                    $messages = array(
                        'voucher_total.required'         => 'Please fill this field!'
                    );

                    $this->validate($request, $rules, $messages);
                  }
                }elseif($detail_campaign->campaign_type=="shopee"){
                  $this->campaign_program_model->removeAllData($id_campaign);

                  if($request->voucher_total!=""){

                    $dataprogram = array(
                      "total_item"            => $request->voucher_total,
                      'time_created'          => time(),
                      'last_update'           => time(),
                      "id_campaign"           => $id_campaign,
                      "type_program"          => 'cashback',
                      "status"                => "active",
                      "commission"            => $request->voucher_commission,
                      "fee"                   => $request->voucher_fee,
                      "custom_link"           => $request->custom_link
                    );

                    $this->campaign_program_model->insertData($dataprogram);
                  }else{

                    $rules  = array(
                        'voucher_total'         => 'required',
                        "custom_link"           => 'required'
                    );

                    $messages = array(
                        'voucher_total.required'         => 'Please fill this field!',
                        'custom_link.required'           => 'Please fill this field!',
                    );

                    $this->validate($request, $rules, $messages);
                  }
                }

                Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Programs paired to the campaign! Please setup the affiliator as target of this campaign.']);
                return redirect(url('master/campaign/settarget/'.$campaign_link));

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
                    "id_campaign"      => $id,
                    "status"            => "deleted",
                    "last_update"       => time(),
                );

                $deleteCampaign = $this->campaign_model->updateData($data);

                if ($deleteCampaign) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses remove data campaign!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat remove data campaign']);
                }
                return redirect(url('master/campaign'));
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

    public function setrun(Request $request)
    {
        $id       = $request->id;
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['manage'])) {
                $detail = $this->campaign_model->getDetail($id);
                $backlink = @$_GET['backlink'];

                $data = array(
                    "id_campaign"       => $id,
                    "running_status"    => "open",
                    "last_update"       => time(),
                );

                $deleteCampaign = $this->campaign_model->updateData($data);

                if ($deleteCampaign) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Campaign '.$detail->campaign_title.' Is Run!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => "Failed to run the campaign"]);
                }
                return redirect(url("master/campaign"));
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

    public function setclose(Request $request)
    {
      $id       = $request->id;
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['manage'])) {
              $detail = $this->campaign_model->getDetail($id);
              $backlink = @$_GET['backlink'];

              $data = array(
                  "id_campaign"       => $id,
                  "running_status"    => "closed",
                  "last_update"       => time(),
              );

              $deleteCampaign = $this->campaign_model->updateData($data);

              if ($deleteCampaign) {
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Campaign '.$detail->campaign_title.' Is Closed!']);
              } else {
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => "Failed to close the campaign"]);
              }
              return redirect("master/campaign");
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

    public function closecampaign(Request $request)
    {
        $id       = $request->id;
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['manage'])) {
                $detail = $this->user_model->getDetail($id);
                $backlink = @$_GET['backlink'];

                $data = array(
                    "id_campaign"       => $id,
                    "running_status"    => "close",
                    "last_update"       => time(),
                );

                $deleteCampaign = $this->campaign_model->updateData($data);

                if ($deleteCampaign) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghentikan campaign!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghentikan campaign']);
                }
                return redirect(url($backlink));
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

    public function outletscanner($campaign_link, $merchant_code){
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
                $detail = $this->campaign_model->getDetailByCampaignLink($campaign_link);

                  $voucherinfo = $this->campaign_program_model->getProgramByCampaign('voucher',$detail->id_campaign);
                  $allfee      = $voucherinfo->commission + $voucherinfo->fee;
                    //print $detail->id_campaign." AND ".$merchant_code;
                    //exit();
                    $datacontent = array(
                      "login"          => $login,
                      "helper"         => "",
                      "previlege"      => $this->previlege_model,
                      "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                      "detail"         => $detail,
                      "outlet_id"      => $merchant_code,
                      "voucher_logs"   => $this->voucher_model->getAllVoucherUsage($detail->id_campaign,$merchant_code),
                    );
                  // dd($datacontent);

                  $view     = View::make("backend.master.campaign.scanner_event", $datacontent);
                  $content  = $view->render();



                $metadata = array(
                    "title"         => $webname . " | Tambah Data Campaign",
                    "description"   => $webname . " | Tambah Data Campaign",
                    "keywords"      => $webname . " | Tambah Data Campaign"
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


    public function previewreleasedana($id)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

                $detail = $this->campaign_model->getDetailCampaign(url(''),$id);
                $detail->invoice_code = $this->payout_model->createCode();

                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    "data"           => $detail,
                    "fee_type"       => $this->setting_model->getSettingVal("fee_release_pendanaan_type"),
                    "fee_value"      => $this->setting_model->getSettingVal("fee_release_pendanaan_value"),
                    "fee_persen"     => $this->setting_model->getSettingVal("fee_release_pendanaan_persen"),
                    "pajak_type"     => $this->setting_model->getSettingVal("pajak_release_pendanaan_type"),
                    "pajak_value"    => $this->setting_model->getSettingVal("pajak_release_pendanaan_value"),
                    "pajak_persen"   => $this->setting_model->getSettingVal("pajak_release_pendanaan_persen"),
                    "banks"          => $this->bank_model->getBankList(),
                    "berita"         => "Release Dana Investasi Dari Urun Mandiri Ke ".$detail->nama_penerbit,
                    'payout_data'    => $this->payout_model->getDetail($detail->id_payout)
                );

                // dd($datacontent['data']);

                $view     = View::make("backend.master.campaign.preview_release_dana", $datacontent);
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

    public function edit($campaign_link){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
              $detail = $this->campaign_model->getDetailByCampaignLink($campaign_link);

              $selected_cat = $this->campaign_property_model->getPropertyBy('category',$detail->id_campaign);
                if($selected_cat){
                foreach (@$selected_cat as $key => $value) {
                  $selectedcat[] = $value->value;
                }
              }else{
                $selectedcat = array();
              }

              $selected_dom = $this->campaign_property_model->getPropertyBy('location',$detail->id_campaign);
              if($selected_dom){
                foreach (@$selected_dom as $key => $value) {
                  $selecteddom[] = $value->value;
                }
              }else{
                $selecteddom = array();
              }

              $datacontent = array(
                  "login"          => $login,
                  "helper"         => "",
                  "previlege"      => $this->previlege_model,
                  "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                  "detail"         => $detail,
                  "penerbits"      => $this->penerbit_model->getListPenerbit(),
                  "tracker_test"   => $this->campaign_tracker_model->getTestData($detail->id_campaign),
                  "callback_test"  => $this->campaign_tracker_model->getTotalTracker('acquisition',$detail->id_campaign),
                  "last_callback"  => $this->campaign_tracker_model->getLastTracker('acquisition',$detail->id_campaign),
                  "categories"     => $selectedcat,
                  "categoriess"    => $this->sektor_industri_model->getPluckPerusahaan(),
                  "domisilis"      => $this->wilayah_model->getListKota(),
                  "domisili"       => $selecteddom,
                  "test_link"      => $this->campaign_join_model->getLinkTest($detail->id_campaign),
                  "program"        => array(
                    "visit"         => $this->campaign_program_model->getProgramByCampaign('visit',$detail->id_campaign),
                    "read"          => $this->campaign_program_model->getProgramByCampaign('read',$detail->id_campaign),
                    "action"        => $this->campaign_program_model->getProgramByCampaign('action',$detail->id_campaign),
                    "acquisition"   => $this->campaign_program_model->getProgramByCampaign('acquisition',$detail->id_campaign),
                    "voucher"       => $this->campaign_program_model->getProgramByCampaign('voucher',$detail->id_campaign),
                    "cashback"      => $this->campaign_program_model->getProgramByCampaign('cashback',$detail->id_campaign),
                  )
              );

              //if($detail->campaign_type=="o2o"){
                $datacontent['outlets'] = $this->outlet_model->getListOutlet($detail->id_campaign);
              //}

              // dd($datacontent);

              $view     = View::make("backend.master.campaign.edit", $datacontent);
              $content  = $view->render();

              $metadata = array(
                  "title"         => $webname . " | Tambah Data Campaign",
                  "description"   => $webname . " | Tambah Data Campaign",
                  "keywords"      => $webname . " | Tambah Data Campaign"
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

    public function boost($campaign_link){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
              $detail = $this->campaign_model->getDetailByCampaignLink($campaign_link);

              $selected_cat = $this->campaign_property_model->getPropertyBy('category',$detail->id_campaign);
                if($selected_cat){
                foreach (@$selected_cat as $key => $value) {
                  $selectedcat[] = $value->value;
                }
              }else{
                $selectedcat = array();
              }

              $selected_dom = $this->campaign_property_model->getPropertyBy('location',$detail->id_campaign);
              if($selected_dom){
                foreach (@$selected_dom as $key => $value) {
                  $selecteddom[] = $value->value;
                }
              }else{
                $selecteddom = array();
              }

              $datacontent = array(
                  "login"          => $login,
                  "helper"         => "",
                  "previlege"      => $this->previlege_model,
                  "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                  "detail"         => $detail,
                  "penerbits"      => $this->penerbit_model->getListPenerbit(),
                  "tracker_test"   => $this->campaign_tracker_model->getTestData($detail->id_campaign),
                  "callback_test"  => $this->campaign_tracker_model->getTotalTracker('acquisition',$detail->id_campaign),
                  "last_callback"  => $this->campaign_tracker_model->getLastTracker('acquisition',$detail->id_campaign),
                  "categories"     => $selectedcat,
                  "categoriess"    => $this->sektor_industri_model->getPluckPerusahaan(),
                  "domisilis"      => $this->wilayah_model->getListKota(),
                  "domisili"       => $selecteddom,
                  "test_link"      => $this->campaign_join_model->getLinkTest($detail->id_campaign),
                  "program"        => array(
                    "visit"         => $this->campaign_program_model->getProgramByCampaign('visit',$detail->id_campaign),
                    "read"          => $this->campaign_program_model->getProgramByCampaign('read',$detail->id_campaign),
                    "action"        => $this->campaign_program_model->getProgramByCampaign('action',$detail->id_campaign),
                    "acquisition"   => $this->campaign_program_model->getProgramByCampaign('acquisition',$detail->id_campaign),
                    "voucher"       => $this->campaign_program_model->getProgramByCampaign('voucher',$detail->id_campaign),
                    "cashback"      => $this->campaign_program_model->getProgramByCampaign('cashback',$detail->id_campaign),
                  )
              );

              //if($detail->campaign_type=="o2o"){
                $datacontent['outlets'] = $this->outlet_model->getListOutlet($detail->id_campaign);
              //}

              // dd($datacontent);

              $view     = View::make("backend.master.campaign.boost", $datacontent);
              $content  = $view->render();

              $metadata = array(
                  "title"         => $webname . " | Tambah Data Campaign",
                  "description"   => $webname . " | Tambah Data Campaign",
                  "keywords"      => $webname . " | Tambah Data Campaign"
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

    public function updatemanage($id, Request $request)
    {
        $login    = Session::get("user");


        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

                $data = [
                    'id_campaign'              => $id,
                    'last_update'              => time()
                ];

                if($request->id_rups!=""){
                  $data['id_rups']  = $request->id_rups;
                }

                if($request->tanggal_release_dana!=""){
                  $data['tanggal_release_dana']  = $request->tanggal_release_dana;
                }

                $update_campaign = $this->campaign_model->updateData($data);

                if ($update_campaign) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses melakukan update!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat melakukan update']);
                }

                //if($request->backlink!=""){
                  return redirect(url($request->backlink));
                //}

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
                if($request->update_type==""){
                  $data = [
                      'id_campaign'           => $id,
                      'campaign_title'        => $request->campaign_title,
                      'campaign_description'  => $request->campaign_description,
                      'start_date'            => $request->start_date,
                      'end_date'              => $request->end_date,
                      'budget'                => $request->budget,
                      'campaign_internal'     => $request->campaign_internal,
                      'max_commission'        => $request->max_commission,
                      'campaign_instruction'  => $request->campaign_instruction,
                      'campaign_do_n_dont'    => $request->campaign_do_n_dont,
                      'campaign_do_n_dont'    => $request->campaign_do_n_dont,
                      'last_update' => time()
                  ];

                  if ($request->hasFile('photos')) {
                      // catch file
                      $filePhotosInput = $request->file('photos');
                      $photos_path = "uploads/master/campaign/photos/" . md5($filePhotosInput->getClientOriginalName()) . '-' . time() . '.' . $filePhotosInput->getClientOriginalExtension();
                      $filePhotosInput->move(public_path('uploads/master/campaign/photos/'), $photos_path);
                      $data['photos'] = $photos_path;
                  }


                  $rules  = array(
                      'campaign_title'        => 'required',
                      'campaign_description'  => 'required',
                      'start_date'            => 'required',
                      'end_date'              => 'required',
                      'budget'                => 'required|numeric',
                      'max_commission'        => 'required|numeric',
                  );

                  $messages = array(
                      'campaign_title'        => 'Mohon isi bagian berikut!',
                      'periode_deviden'       => 'Mohon isi bagian berikut!',
                      'campaign_description'  => 'Mohon isi bagian berikut!',
                      'start_date'            => 'Mohon isi bagian berikut!',
                      'end_date'              => 'Mohon isi bagian berikut!',
                      'budget.required'       => 'Mohon isi bagian berikut!',
                      'budget.numeric'        => 'Mohon isi dengan angka!',
                  );

                  $this->validate($request, $rules, $messages);

                  $update_campaign = $this->campaign_model->updateData($data);

                  if ($update_campaign) {
                      Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah campaign!']);
                  } else {
                      Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah campaign']);
                  }

                }elseif($request->update_type=="target"){
                  $detail_campaign  = $this->campaign_model->getDetail($id);

                  $rules  = array(
                      'id_categories'        => 'required',
                  );

                  if($detail_campaign->campaign_type=="banner"){
                    $rules['landing_url'] = 'required';
                  }

                  $messages = array(
                      'id_categories'        => 'Mohon isi bagian berikut!',
                      'landing_url'          => 'Mohon isi bagian berikut!',
                  );

                  $this->validate($request, $rules, $messages);

                  $this->campaign_property_model->removeAllData($id);

                  $updatecampaign = array(
                    "id_campaign"   => $id,
                    "tipe_url"      => $request->tipe_url,
                    "landing_url"   => $request->landing_url
                  );

                  $this->campaign_model->updateData($updatecampaign);

                  if($request->id_domisili){
                    foreach ($request->id_domisili as $key => $value) {
                      $data_domisili = array(
                        "value"                 => $value,
                        'time_created'          => time(),
                        'last_update'           => time(),
                        "id_campaign"           => $id,
                        "property_type"         => 'location',
                        "status"                => "active"
                      );

                      $this->campaign_property_model->insertData($data_domisili);
                    }
                  }

                  if($request->id_categories){
                    foreach ($request->id_categories as $key => $value) {
                      $data_categories = array(
                        "value"                 => $value,
                        'time_created'          => time(),
                        'last_update'           => time(),
                        "id_campaign"           => $id,
                        "property_type"         => 'category',
                        "status"                => "active"
                      );

                      $this->campaign_property_model->insertData($data_categories);
                    }
                  }

                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menyimpan target!']);

                }elseif($request->update_type=="program"){
                  $detail_campaign  = $this->campaign_model->getDetail($id);

                  if($detail_campaign->campaign_type=="banner"){
                    if($request->visit_total=="" && $request->reader_total=="" && $request->action_total=="" && $request->acquisition_total==""){
                      $rules  = array(
                          'visit_total'         => 'required',
                          'reader_total'        => 'required',
                          'action_total'        => 'required',
                          'acquisition_total'   => 'required',
                      );

                      $messages = array(
                          'visit_total.required'         => 'Please fill at least one of this programs',
                          'reader_total.required'        => 'Please fill at least one of this programs',
                          'action_total.required'        => 'Please fill at least one of this programs',
                          'acquisition_total.required'   => 'Please fill at least one of this programs'
                      );

                      $this->validate($request, $rules, $messages);
                    }

                    $this->campaign_program_model->removeAllData($id);

                    if($request->visit_total!=""){
                      $dataprogram = array(
                        "total_item"            => $request->visit_total,
                        'time_created'          => time(),
                        'last_update'           => time(),
                        "id_campaign"           => $id,
                        "type_program"          => 'visit',
                        "status"                => "active",
                        "commission"            => $request->visit_commission,
                        "fee"                   => $request->visit_fee,
                      );

                      $this->campaign_program_model->insertData($dataprogram);
                    }

                    if($request->reader_total!=""){
                      $dataprogram = array(
                        "total_item"            => $request->reader_total,
                        'time_created'          => time(),
                        'last_update'           => time(),
                        "id_campaign"           => $id,
                        "type_program"          => 'read',
                        "status"                => "active",
                        "commission"            => $request->reader_commission,
                        "fee"                   => $request->reader_fee,
                      );

                      $this->campaign_program_model->insertData($dataprogram);
                    }

                    if($request->action_total!=""){
                      $dataprogram = array(
                        "total_item"            => $request->action_total,
                        'time_created'          => time(),
                        'last_update'           => time(),
                        "id_campaign"           => $id,
                        "type_program"          => 'action',
                        "status"                => "active",
                        "commission"            => $request->action_commission,
                        "fee"                   => $request->action_fee,
                      );

                      $this->campaign_program_model->insertData($dataprogram);
                    }

                    if($request->acquisition_total!=""){
                      $dataprogram = array(
                        "total_item"            => $request->acquisition_total,
                        'time_created'          => time(),
                        'last_update'           => time(),
                        "id_campaign"           => $id,
                        "type_program"          => 'acquisition',
                        "status"                => "active",
                        "commission"            => $request->acquisition_commission,
                        "fee"                   => $request->acquisition_fee,
                        "type"                  => $request->acquisition_type
                      );

                      $this->campaign_program_model->insertData($dataprogram);
                    }
                  }elseif($detail_campaign->campaign_type=="o2o"){
                    $this->campaign_program_model->removeAllData($id);

                    if($request->voucher_total!=""){

                      $dataprogram = array(
                        "total_item"            => $request->voucher_total,
                        'time_created'          => time(),
                        'last_update'           => time(),
                        "id_campaign"           => $id,
                        "type_program"          => 'voucher',
                        "status"                => "active",
                        "commission"            => $request->voucher_commission,
                        "fee"                   => $request->voucher_fee,
                      );

                      $this->campaign_program_model->insertData($dataprogram);
                    }else{

                      $rules  = array(
                          'voucher_total'         => 'required'
                      );

                      $messages = array(
                          'voucher_total.required'         => 'Please fill this field!'
                      );

                      $this->validate($request, $rules, $messages);
                    }
                  }elseif($detail_campaign->campaign_type=="shopee"){
                    $this->campaign_program_model->removeAllData($id);

                    if($request->voucher_total!=""){

                      $dataprogram = array(
                        "total_item"            => $request->voucher_total,
                        'time_created'          => time(),
                        'last_update'           => time(),
                        "id_campaign"           => $id,
                        "type_program"          => 'cashback',
                        "status"                => "active",
                        "commission"            => $request->voucher_commission,
                        "fee"                   => $request->voucher_fee,
                        "custom_link"           => $request->custom_link
                      );

                      $this->campaign_program_model->insertData($dataprogram);
                    }else{

                      $rules  = array(
                          'voucher_total'         => 'required',
                          "custom_link"           => 'required'
                      );

                      $messages = array(
                          'voucher_total.required'         => 'Please fill this field!',
                          'custom_link.required'           => 'Please fill this field!',
                      );

                      $this->validate($request, $rules, $messages);
                    }
                  }

                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menyimpan program!']);
                }


                if($request->backlink!=""){
                  return redirect(url($request->backlink));
                }else{
                  return redirect(url('master/campaign'));
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
    }
}
