<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\IGWHelper;
use App\Http\Models\PrevilegeModel;
use App\Http\Models\RekeningDanaModel;
use App\Http\Models\SettingModel;
use App\Http\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class TopUpController extends Controller
{
    //
    //
    private $setting_model;
    private $previlege_model;
    private $helper;
    private $user_model;
    private $rekening_dana_model;

    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $this->user_model       = new UserModel();
        // $this->rekeninddanatransaksi_model = new RekeningDanaTransaksiModel();
        $this->rekening_dana_model = new RekeningDanaModel();

        $dataconfig['main_url'] = "master/topup";
        $dataconfig['view']     = "topup-view";
        $dataconfig['approve']  = "topup-approve";
        $dataconfig['create']   = "topup-create";
        $dataconfig['edit']     = "topup-edit";
        $dataconfig['remove']   = "topup-remove";
        $dataconfig['manage']   = "topup-manage";

        $this->config           = $dataconfig;
    }

    public function index(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {
                $default    = array(
                    "short"     => "trx_code",
                    "shortmode" => "asc"
                );
                $shorter = array(
                    "trx_code"      => "TRX Code",
                    "trx_amount"      => "TRX Amount",
                    "time_created"        => "Tgl Terdaftar",
                    "last_update"         => "Terakhir Diperbarui"
                );
                $page       = $request->input("page");
                $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword    = $request->input("keyword");
                $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
                $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
                $str        = ($page != "") ? (($page - 1) * $limit) : 0;
                $data       = 0;//$this->rekeninddanatransaksi_model->getAllTopUp($str, $limit, $keyword, $short, $shortmode);

                // dd($data);

                //exit();
                $totaldata  = 0;//count($data);
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
                    "total_paid_topup"  => 0,//$this->rekeninddanatransaksi_model->sumAllPaidTopup(),
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );

                $view     = View::make("backend.master.topup.pre", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " |  Manajemen Top Up",
                    "description"   => $webname . " |  Manajemen Top Up",
                    "keywords"      => $webname . " |  Manajemen Top Up"
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

    public function approve($id, Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['approve'])) {
                $detail = $this->user_model->getDetail($id);

                // isi callback manual
                $transaksidetail = $this->rekeninddanatransaksi_model::where('id_transaksi', $id)->first();
                $trx_request = json_decode($transaksidetail->trx_request);

                $trx_callback = [
                    "external_id"         => $trx_request->external_id,
                    "account_number"      => $trx_request->account_number,
                    "bank_code"           => $trx_request->bank_code,
                    "amount"              => $trx_request->expected_amount,
                    "merchant_code"       => $trx_request->merchant_code,
                    "id"                  => $trx_request->id
                ];

                $trx_callback = json_encode($trx_callback);


                $data = array(
                    "id_transaksi"      => $id,
                    "status"            => "paid",
                    "last_update"       => time(),
                    "trx_callback"      => $trx_callback,
                    "trx_action"        => "manual"
                );
                $approve = $this->rekeninddanatransaksi_model->updateData($data);

                //==== update saldo di rekening dana
                $saldo          = $this->rekening_dana_model->getCurrentRekening($transaksidetail->id_user);
                $current_saldo  = $saldo->saldo+$transaksidetail->kredit;

                $dataupdatesaldo  = array(
                  "id_rekening_dana"  => $saldo->id_rekening_dana,
                  "saldo"             => $current_saldo
                );
                $this->rekening_dana_model->updateData($dataupdatesaldo);

                //==== update saldo di rekening dana

                if ($approve) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses approve data transaksi!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat approve data transaksi']);
                }
                return redirect(url('master/topup'));
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

    public function detail($id){
      $login    = Session::get("user");
      $webname  = $this->setting_model->getSettingVal("website_name");
      if($login){
        if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
          $datacontent = array(
            "login"          => $login,
            "helper"         => "",
            "config"         => $this->config,
            "previlege"      => $this->previlege_model,
            "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
            "data"           => $this->rekeninddanatransaksi_model->getDetail($id)
          );

          $view     = View::make("backend.master.topup.detail",$datacontent);
          $content  = $view->render();

          $metadata = array(
            "title"         => $webname." | Detail pengguna",
            "description"   => $webname." | Detail pengguna",
            "keywords"      => $webname." | Detail pengguna"
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
