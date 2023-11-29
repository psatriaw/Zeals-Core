<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\IGWHelper;
use App\Http\Models\BannerModel;
use App\Http\Models\PenerbitModel;
use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
use App\Http\Models\UserModel;
use App\Http\Models\LaporanCampaignModel;
use App\Http\Models\CampaignModel;
use App\Http\Models\SektorIndustriModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class LaporanCampaignController extends Controller
{
    //
    public $years = array();

    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $this->laporancampaign_model = new LaporanCampaignModel();
        $this->penerbit_model = new PenerbitModel();
        $this->sektorindutri_model = new SektorIndustriModel();
        $this->user_model = new UserModel();
        $this->campaign_model = new CampaignModel();

        $dataconfig['main_url'] = "master/laporan-campaign";
        $dataconfig['view']     = "laporan-view";
        $dataconfig['create']   = "laporan-create";
        $dataconfig['edit']     = "laporan-edit";
        $dataconfig['detail']   = "laporan-detail";
        $dataconfig['remove']   = "laporan-remove";
        $dataconfig['manage']   = "laporan-manage";
        $dataconfig['approve']   = "laporan-approve";

        $this->config = $dataconfig;

        $this->months      = array(
          "1"    => "Januari",
          "2"    => "Februari",
          "3"    => "Maret",
          "4"    => "April",
          "5"    => "Mei",
          "6"    => "Juni",
          "7"    => "Juli",
          "8"    => "Agustus",
          "9"    => "September",
          "10"    => "Oktober",
          "11"    => "November",
          "12"    => "Desember"
        );

        for($i=date("Y");$i>(date("Y")-20);$i--){
          $this->years[$i] = $i;
        }
    }

    public function getlaporanscampaign(Request $request){
      $id_campaign  = $request->id_campaign;
      $laporans     = $this->laporancampaign_model->getLaporanKeuanganCampaign($id_campaign);
      $htmls        = "";
      if($laporans){
        foreach ($laporans as $key => $value) {
          $htmls = $htmls.'<div class="col-sm-4 col-xs-6">
            <div class="box-opsi-keuangan" id="laporan_'.$value->id_report.'" onclick="pilihLaporan('.$value->id_report.',\''.$value->profit.'\')">
                <i class="fa fa-file"></i>
                <br>
                <span>'.$this->months[$value->report_month].' - '.$value->report_year.'</span><br>
                <span>Rp.'.number_format($value->profit,0).'</span>
            </div>
          </div>';
        }
      }

      return response()->json(array("html"=> $htmls), 200);
    }

    public function index(Request $request)
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
                    "id_report"      => "Report Number",
                    "campaign_title" => "Judul Campaign",
                    "nama_penerbit" => "Nama Penerbit",
                    "report_code" => "Kode Report",
                    "status" => "Status"
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

                $data       = $this->laporancampaign_model->getDataReport($str, $limit, $keyword, $short, $shortmode, @$id_penerbit);

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
                    "id_penerbit"    => @$id_penerbit,
                    "months"         => $this->months,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );


                $view     = View::make("backend.master.campaignreport.index", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " |  Manajemen Campaign Report",
                    "description"   => $webname . " |  Manajemen Campaign Report",
                    "keywords"      => $webname . " |  Manajemen Campaign Report"
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

    public function changestatus($id, Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['approve'])) {
                $detail = $this->user_model->getDetail($id);

                $data = array(
                    "id_report"         => $id,
                    "status"            => $request->status,
                    "catatan"           => $request->catatan,
                    "last_update"       => time(),
                );
                $backlink  = $request->backlink;
                $tanggapan = $this->laporancampaign_model->updateData($data);

                if ($tanggapan) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah status data report!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah status data report']);
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

    public function reject($id)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['reject'])) {
                $detail = $this->user_model->getDetail($id);


                $data = array(
                    "id_report"      => $id,
                    "status"            => "rejected",
                    "last_update"       => time(),
                );
                $approve = $this->laporancampaign_model->updateData($data);

                if ($approve) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses reject data report!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat reject data report']);
                }
                return redirect(url('master/laporan-campaign'));
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
                    "id_report"      => $id,
                    "status"            => "deleted",
                    "last_update"       => time(),
                );
                $approve = $this->laporancampaign_model->updateData($data);

                if ($approve) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses hapus data report!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat hapus data report']);
                }
                return redirect(url('master/laporan-campaign'));
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
                    "data"           => $this->laporancampaign_model->getDetailCampaignReport($id)
                );

                // dd($datacontent['data']);

                $view     = View::make("backend.master.campaignreport.detail", $datacontent);
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


    public function create($id)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
                $penerbit    = $this->penerbit_model->getDetail($id);
                $author      = $this->user_model->getDetail($penerbit->id_user);


                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "config"         => $this->config,
                    "previlege"      => $this->previlege_model,
                    "perusahaan"     => $penerbit,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    "campaigns"      => $this->campaign_model->getCampaignByPenerbitPluck($id),
                    "months"         => $this->months,
                    "years"          => $this->years,
                    "id_penerbit"    => $id,
                    "data"           => array(
                      "report_code"   => $this->laporancampaign_model->createCode(),
                      "report_date"   => date("Y-m-d H:i"),
                      "first_name"    => $author->first_name,
                      "report_month"  => date("m")
                    )
                );

                // dd($datacontent['data']);
                //print_r($datacontent['data']);
                //exit();
                $view     = View::make("backend.master.campaignreport.create", $datacontent);
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

    public function create_laporan($id, Request $request){
      $login    = Session::get("user");
      if ($login) {
          if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {

              $rules  = array(
                  'file_path'     => "required",
                  "profit"        => "required|numeric"
              );

              $messages = array(
                  'file_path.required' => "Mohon memilih file untuk laporan!",
                  'file_path.mimes' => "Hanya PDF file yang diperbolehkan!",
                  'profit.required' => "Mohon mengisi profit disini!",
                  'profit.numeric' => "Hanya Angka yang diperbolehkan!",
              );

              $this->validate($request, $rules, $messages);

              // catch file
              $filePhotosInput  = $request->file('file_path');
              $laporan_path     = "uploads/laporan/" . md5($filePhotosInput->getClientOriginalName()) . '-' . time() . '.' . $filePhotosInput->getClientOriginalExtension();
              $filePhotosInput->move(public_path('uploads/laporan/'), $laporan_path);

              $backlink         = $request->backlink;

              $data = [
                  'id_campaign'   => $request->id_campaign,
                  'report_date'   => date("Y-m-d"),
                  'report_month'  => $request->report_month,
                  'report_year'   => $request->report_year,
                  'time_created'  => time(),
                  'last_update'   => time(),
                  'status'        => "pending",
                  'id_user'       => $login->id_user,
                  'id_penerbit'   => $id,
                  'report_code'   => $this->laporancampaign_model->createCode(),
                  'file_path'     => $laporan_path,
                  "profit"        => $request->profit,
              ];
              //print_r($data);
              //exit();
              $create_penerbit                 = $this->laporancampaign_model->insertData($data);

              if ($create_penerbit) {
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Berhasil mengupload data laporan!']);
              } else {
                  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal mengupload data laporan!']);
              }
              return redirect(url($this->config['main_url']."/create/".$id."?backlink=".$backlink));
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
