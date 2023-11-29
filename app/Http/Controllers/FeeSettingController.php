<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\IGWHelper;
use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class FeeSettingController extends Controller
{
    //
    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $this->settin_model = new SettingModel();

        $dataconfig['main_url'] = "master/feesetting";
        $dataconfig['view']     = "feesetting-view";
        $dataconfig['create']   = "feesetting-create";
        $dataconfig['edit']     = "feesetting-edit";
        $dataconfig['detail']   = "feesetting-detail";
        $dataconfig['remove']   = "feesetting-remove";
        $dataconfig['manage']   = "feesetting-manage";
        $dataconfig['approve']   = "feesetting-approve";

        $this->config = $dataconfig;
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
                );

                $page       = $request->input("page");
                $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword    = $request->input("keyword");
                $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
                $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
                $str        = ($page != "") ? (($page - 1) * $limit) : 0;
                $data       = $this->setting_model->getFeeType();

                //exit();
                // $totaldata  = count($data);
                // $pagging    = $this->helper->showPagging($totaldata, url($this->config['main_url'] . '?keyword=' . $keyword . "&short=" . $short . "&shortmode=" . $shortmode), $position = "", $page, $limit, 2);

                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "data"           => $data,
                    // "pagging"        => $pagging,
                    "input"          => $request->all(),
                    "default"        => $default,
                    "config"         => $this->config,
                    "shorter"        => $shorter,
                    "page"           => $page,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );


                $view     = View::make("backend.master.feesetting.index", $datacontent);
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

    public function update(Request $request)
    {
        $login    = Session::get("user");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

                $fee_type = $request->fee_type;

                if($fee_type == 'persen') {
                    // dd($request->fee_beli_saham_persen);
                    $fee_beli_saham_persen = $request->fee_beli_saham_persen;
                    $fee_release_deviden_persen = $request->fee_release_deviden_persen;
                    $fee_pencairan_persen = $request->fee_pencairan_persen;
                    $fee_topup_persen = $request->fee_topup_persen;
                    $fee_release_pendanaan_persen = $request->fee_release_pendanaan_persen;

                    $data = [
                        'fee_beli_saham_persen' => $fee_beli_saham_persen,
                        'fee_release_deviden_persen' => $fee_release_deviden_persen,
                        'fee_pencairan_persen' => $fee_pencairan_persen,
                        'fee_topup_persen' => $fee_topup_persen,
                        'fee_release_pendanaan_persen' => $fee_release_pendanaan_persen,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_type')->first();
                    $update_fee_type->setting_value = $fee_type;
                    $update_fee_type->save();

                    $update_fee_beli_saham_persen = $this->setting_model::where('code_setting', 'fee_beli_saham_persen')->first();
                    $update_fee_beli_saham_persen->setting_value = $fee_beli_saham_persen;
                    $update_fee_beli_saham_persen->save();

                    $update_fee_release_deviden_persen = $this->setting_model::where('code_setting', 'fee_release_deviden_persen')->first();
                    $update_fee_release_deviden_persen->setting_value = $fee_release_deviden_persen;
                    $update_fee_release_deviden_persen->save();

                    $update_fee_pencairan_persen = $this->setting_model::where('code_setting', 'fee_pencairan_persen')->first();
                    $update_fee_pencairan_persen->setting_value = $fee_pencairan_persen;
                    $update_fee_pencairan_persen->save();

                    $update_fee_topup_persen = $this->setting_model::where('code_setting', 'fee_topup_persen')->first();
                    $update_fee_topup_persen->setting_value = $fee_topup_persen;
                    $update_fee_topup_persen->save();

                    $update_fee_release_pendanaan_persen = $this->setting_model::where('code_setting', 'fee_release_pendanaan_persen')->first();
                    $update_fee_release_pendanaan_persen->setting_value = $fee_release_pendanaan_persen;
                    $update_fee_release_pendanaan_persen->save();

                    $update = true;


                    // dd('sukses');

                    // dd($data);
                } else if($fee_type == 'value') {
                    $fee_beli_saham_value = $request->fee_beli_saham_value;
                    $fee_release_deviden_value = $request->fee_release_deviden_value;
                    $fee_pencairan_value = $request->fee_pencairan_value;
                    $fee_topup_value = $request->fee_topup_value;
                    $fee_release_pendanaan_value = $request->fee_release_pendanaan_value;

                    $data = [
                        'fee_beli_saham_value' => $fee_beli_saham_value,
                        'fee_release_deviden_value' => $fee_release_deviden_value,
                        'fee_pencairan_value' => $fee_pencairan_value,
                        'fee_topup_value' => $fee_topup_value,
                        'fee_release_pendanaan_value' => $fee_release_pendanaan_value,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_type')->first();
                    $update_fee_type->setting_value = $fee_type;
                    $update_fee_type->save();

                    $update_fee_beli_saham_value = $this->setting_model::where('code_setting', 'fee_beli_saham_value')->first();
                    $update_fee_beli_saham_value->setting_value = $fee_beli_saham_value;
                    $update_fee_beli_saham_value->save();

                    $update_fee_release_deviden_value = $this->setting_model::where('code_setting', 'fee_release_deviden_value')->first();
                    $update_fee_release_deviden_value->setting_value = $fee_release_deviden_value;
                    $update_fee_release_deviden_value->save();

                    $update_fee_pencairan_value = $this->setting_model::where('code_setting', 'fee_pencairan_value')->first();
                    $update_fee_pencairan_value->setting_value = $fee_pencairan_value;
                    $update_fee_pencairan_value->save();

                    $update_fee_topup_value = $this->setting_model::where('code_setting', 'fee_topup_value')->first();
                    $update_fee_topup_value->setting_value = $fee_topup_value;
                    $update_fee_topup_value->save();

                    $update_fee_release_pendanaan_value = $this->setting_model::where('code_setting', 'fee_release_pendanaan_value')->first();
                    $update_fee_release_pendanaan_value->setting_value = $fee_release_pendanaan_value;
                    $update_fee_release_pendanaan_value->save();

                    // dd('sukses value');
                    // dd($data);
                    $update = true;
                }


                // $rules  = array(
                //     "title" => "required",
                //     "status" => "required",
                //     "link" => "required",
                //     "link_type" => "required"
                // );

                // $messages = array(
                //     "title" => "Mohon maaf, Anda harus mengisi bagian ini",
                //     "status" => "Mohon maaf, Anda harus mengisi bagian ini",
                //     "link" => "Mohon maaf, Anda harus mengisi bagian ini",
                //     "link_type" => "Mohon maaf, Anda harus mengisi bagian ini"
                // );

                // $this->validate($request, $rules, $messages);

                // $update = $this->setting_model->where('code_setting', 'fee_type')->first();
                // dd($update);

                if ($update) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data fee setting!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data fee setting']);
                }
                return redirect(url('master/feesetting'));

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

    public function updateBeliSaham(Request $request)
    {
        $login    = Session::get("user");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

                $fee_beli_saham_type = $request->fee_beli_saham_type;
                $pajak_beli_saham_type = $request->pajak_beli_saham_type;

                if($fee_beli_saham_type == 'persen') {
                    $fee_beli_saham_persen = $request->fee_beli_saham_persen;

                    $data = [
                        'fee_beli_saham_persen' => $fee_beli_saham_persen,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_beli_saham_type')->first();
                    $update_fee_type->setting_value = $fee_beli_saham_type;
                    $update_fee_type->save();

                    $update_fee_beli_saham_persen = $this->setting_model::where('code_setting', 'fee_beli_saham_persen')->first();
                    $update_fee_beli_saham_persen->setting_value = $fee_beli_saham_persen;
                    $update_fee_beli_saham_persen->save();

                    $update = true;


                    // dd('sukses');

                    // dd($data);
                } else if($fee_beli_saham_type == 'value') {
                    $fee_beli_saham_value = $request->fee_beli_saham_value;

                    $data = [
                        'fee_beli_saham_value' => $fee_beli_saham_value,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_beli_saham_type')->first();
                    $update_fee_type->setting_value = $fee_beli_saham_type;
                    $update_fee_type->save();

                    $update_fee_beli_saham_value = $this->setting_model::where('code_setting', 'fee_beli_saham_value')->first();
                    $update_fee_beli_saham_value->setting_value = $fee_beli_saham_value;
                    $update_fee_beli_saham_value->save();

                    $update = true;
                }

                if($pajak_beli_saham_type == 'persen') {
                    $pajak_beli_saham_persen = $request->pajak_beli_saham_persen;

                    $data = [
                        'pajak_beli_saham_persen' => $pajak_beli_saham_persen,
                    ];

                    $update_pajak_type = $this->setting_model::where('code_setting', 'pajak_beli_saham_type')->first();
                    $update_pajak_type->setting_value = $pajak_beli_saham_type;
                    $update_pajak_type->save();

                    $update_pajak_beli_saham_persen = $this->setting_model::where('code_setting', 'pajak_beli_saham_persen')->first();
                    $update_pajak_beli_saham_persen->setting_value = $pajak_beli_saham_persen;
                    $update_pajak_beli_saham_persen->save();

                    $update = true;


                    // dd('sukses');

                    // dd($data);
                } else if($pajak_beli_saham_type == 'value') {
                    $pajak_beli_saham_value = $request->pajak_beli_saham_value;

                    $data = [
                        'pajak_beli_saham_value' => $pajak_beli_saham_value,
                    ];

                    $update_pajak_type = $this->setting_model::where('code_setting', 'pajak_beli_saham_type')->first();
                    $update_pajak_type->setting_value = $pajak_beli_saham_type;
                    $update_pajak_type->save();

                    $update_pajak_beli_saham_value = $this->setting_model::where('code_setting', 'pajak_beli_saham_value')->first();
                    $update_pajak_beli_saham_value->setting_value = $pajak_beli_saham_value;
                    $update_pajak_beli_saham_value->save();

                    $update = true;
                }



                // $fee_type = $request->fee_type;

                // if($fee_type == 'persen') {
                //     // dd($request->fee_beli_saham_persen);
                //     $fee_beli_saham_persen = $request->fee_beli_saham_persen;
                //     $fee_release_deviden_persen = $request->fee_release_deviden_persen;
                //     $fee_pencairan_persen = $request->fee_pencairan_persen;
                //     $fee_topup_persen = $request->fee_topup_persen;
                //     $fee_release_pendanaan_persen = $request->fee_release_pendanaan_persen;

                //     $data = [
                //         'fee_beli_saham_persen' => $fee_beli_saham_persen,
                //         'fee_release_deviden_persen' => $fee_release_deviden_persen,
                //         'fee_pencairan_persen' => $fee_pencairan_persen,
                //         'fee_topup_persen' => $fee_topup_persen,
                //         'fee_release_pendanaan_persen' => $fee_release_pendanaan_persen,
                //     ];

                //     $update_fee_type = $this->setting_model::where('code_setting', 'fee_type')->first();
                //     $update_fee_type->setting_value = $fee_type;
                //     $update_fee_type->save();

                //     $update_fee_beli_saham_persen = $this->setting_model::where('code_setting', 'fee_beli_saham_persen')->first();
                //     $update_fee_beli_saham_persen->setting_value = $fee_beli_saham_persen;
                //     $update_fee_beli_saham_persen->save();

                //     $update_fee_release_deviden_persen = $this->setting_model::where('code_setting', 'fee_release_deviden_persen')->first();
                //     $update_fee_release_deviden_persen->setting_value = $fee_release_deviden_persen;
                //     $update_fee_release_deviden_persen->save();

                //     $update_fee_pencairan_persen = $this->setting_model::where('code_setting', 'fee_pencairan_persen')->first();
                //     $update_fee_pencairan_persen->setting_value = $fee_pencairan_persen;
                //     $update_fee_pencairan_persen->save();

                //     $update_fee_topup_persen = $this->setting_model::where('code_setting', 'fee_topup_persen')->first();
                //     $update_fee_topup_persen->setting_value = $fee_topup_persen;
                //     $update_fee_topup_persen->save();

                //     $update_fee_release_pendanaan_persen = $this->setting_model::where('code_setting', 'fee_release_pendanaan_persen')->first();
                //     $update_fee_release_pendanaan_persen->setting_value = $fee_release_pendanaan_persen;
                //     $update_fee_release_pendanaan_persen->save();

                //     $update = true;


                //     // dd('sukses');

                //     // dd($data);
                // } else if($fee_type == 'value') {
                //     $fee_beli_saham_value = $request->fee_beli_saham_value;
                //     $fee_release_deviden_value = $request->fee_release_deviden_value;
                //     $fee_pencairan_value = $request->fee_pencairan_value;
                //     $fee_topup_value = $request->fee_topup_value;
                //     $fee_release_pendanaan_value = $request->fee_release_pendanaan_value;

                //     $data = [
                //         'fee_beli_saham_value' => $fee_beli_saham_value,
                //         'fee_release_deviden_value' => $fee_release_deviden_value,
                //         'fee_pencairan_value' => $fee_pencairan_value,
                //         'fee_topup_value' => $fee_topup_value,
                //         'fee_release_pendanaan_value' => $fee_release_pendanaan_value,
                //     ];

                //     $update_fee_type = $this->setting_model::where('code_setting', 'fee_type')->first();
                //     $update_fee_type->setting_value = $fee_type;
                //     $update_fee_type->save();

                //     $update_fee_beli_saham_value = $this->setting_model::where('code_setting', 'fee_beli_saham_value')->first();
                //     $update_fee_beli_saham_value->setting_value = $fee_beli_saham_value;
                //     $update_fee_beli_saham_value->save();

                //     $update_fee_release_deviden_value = $this->setting_model::where('code_setting', 'fee_release_deviden_value')->first();
                //     $update_fee_release_deviden_value->setting_value = $fee_release_deviden_value;
                //     $update_fee_release_deviden_value->save();

                //     $update_fee_pencairan_value = $this->setting_model::where('code_setting', 'fee_pencairan_value')->first();
                //     $update_fee_pencairan_value->setting_value = $fee_pencairan_value;
                //     $update_fee_pencairan_value->save();

                //     $update_fee_topup_value = $this->setting_model::where('code_setting', 'fee_topup_value')->first();
                //     $update_fee_topup_value->setting_value = $fee_topup_value;
                //     $update_fee_topup_value->save();

                //     $update_fee_release_pendanaan_value = $this->setting_model::where('code_setting', 'fee_release_pendanaan_value')->first();
                //     $update_fee_release_pendanaan_value->setting_value = $fee_release_pendanaan_value;
                //     $update_fee_release_pendanaan_value->save();

                //     // dd('sukses value');
                //     // dd($data);
                //     $update = true;
                // }


                // $rules  = array(
                //     "title" => "required",
                //     "status" => "required",
                //     "link" => "required",
                //     "link_type" => "required"
                // );

                // $messages = array(
                //     "title" => "Mohon maaf, Anda harus mengisi bagian ini",
                //     "status" => "Mohon maaf, Anda harus mengisi bagian ini",
                //     "link" => "Mohon maaf, Anda harus mengisi bagian ini",
                //     "link_type" => "Mohon maaf, Anda harus mengisi bagian ini"
                // );

                // $this->validate($request, $rules, $messages);

                // $update = $this->setting_model->where('code_setting', 'fee_type')->first();
                // dd($update);

                if ($update) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data fee beli saham setting!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data fee beli saham setting']);
                }
                return redirect(url('master/feesetting'));

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

    public function updateReleaseDeviden(Request $request)
    {
        $login    = Session::get("user");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

                $fee_release_deviden_type = $request->fee_release_deviden_type;
                $pajak_release_deviden_type = $request->pajak_release_deviden_type;

                if($fee_release_deviden_type == 'persen') {
                    $fee_release_deviden_persen = $request->fee_release_deviden_persen;

                    $data = [
                        'fee_release_deviden_persen' => $fee_release_deviden_persen,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_release_deviden_type')->first();
                    $update_fee_type->setting_value = $fee_release_deviden_type;
                    $update_fee_type->save();

                    $update_fee_release_deviden_persen = $this->setting_model::where('code_setting', 'fee_release_deviden_persen')->first();
                    $update_fee_release_deviden_persen->setting_value = $fee_release_deviden_persen;
                    $update_fee_release_deviden_persen->save();

                    $update = true;


                    // dd('sukses');

                    // dd($data);
                } else if($fee_release_deviden_type == 'value') {
                    $fee_release_deviden_value = $request->fee_release_deviden_value;

                    $data = [
                        'fee_release_deviden_value' => $fee_release_deviden_value,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_release_deviden_type')->first();
                    $update_fee_type->setting_value = $fee_release_deviden_type;
                    $update_fee_type->save();

                    $update_fee_release_deviden_value = $this->setting_model::where('code_setting', 'fee_release_deviden_value')->first();
                    $update_fee_release_deviden_value->setting_value = $fee_release_deviden_value;
                    $update_fee_release_deviden_value->save();

                    $update = true;
                }

                if($pajak_release_deviden_type == 'persen') {
                    $pajak_release_deviden_persen = $request->pajak_release_deviden_persen;

                    $data = [
                        'pajak_release_deviden_persen' => $pajak_release_deviden_persen,
                    ];

                    $update_pajak_type = $this->setting_model::where('code_setting', 'pajak_release_deviden_type')->first();
                    $update_pajak_type->setting_value = $pajak_release_deviden_type;
                    $update_pajak_type->save();

                    $update_pajak_release_deviden_persen = $this->setting_model::where('code_setting', 'pajak_release_deviden_persen')->first();
                    $update_pajak_release_deviden_persen->setting_value = $pajak_release_deviden_persen;
                    $update_pajak_release_deviden_persen->save();

                    $update = true;


                    // dd('sukses');

                    // dd($data);
                } else if($pajak_release_deviden_type == 'value') {
                    $pajak_release_deviden_value = $request->pajak_release_deviden_value;

                    $data = [
                        'pajak_release_deviden_value' => $pajak_release_deviden_value,
                    ];

                    $update_pajak_type = $this->setting_model::where('code_setting', 'pajak_release_deviden_type')->first();
                    $update_pajak_type->setting_value = $pajak_release_deviden_type;
                    $update_pajak_type->save();

                    $update_pajak_release_deviden_value = $this->setting_model::where('code_setting', 'pajak_release_deviden_value')->first();
                    $update_pajak_release_deviden_value->setting_value = $pajak_release_deviden_value;
                    $update_pajak_release_deviden_value->save();

                    $update = true;
                }

                if ($update) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data fee release deviden setting!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data fee release deviden setting']);
                }
                return redirect(url('master/feesetting'));

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

    public function updateTopup(Request $request)
    {
        $login    = Session::get("user");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

                $fee_topup_type = $request->fee_topup_type;
                $pajak_topup_type = $request->pajak_topup_type;

                if($fee_topup_type == 'persen') {
                    $fee_topup_persen = $request->fee_topup_persen;

                    $data = [
                        'fee_topup_persen' => $fee_topup_persen,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_topup_type')->first();
                    $update_fee_type->setting_value = $fee_topup_type;
                    $update_fee_type->save();

                    $update_fee_topup_persen = $this->setting_model::where('code_setting', 'fee_topup_persen')->first();
                    $update_fee_topup_persen->setting_value = $fee_topup_persen;
                    $update_fee_topup_persen->save();

                    $update = true;


                    // dd('sukses');

                    // dd($data);
                } else if($fee_topup_type == 'value') {
                    $fee_topup_value = $request->fee_topup_value;

                    $data = [
                        'fee_topup_value' => $fee_topup_value,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_topup_type')->first();
                    $update_fee_type->setting_value = $fee_topup_type;
                    $update_fee_type->save();

                    $update_fee_topup_value = $this->setting_model::where('code_setting', 'fee_topup_value')->first();
                    $update_fee_topup_value->setting_value = $fee_topup_value;
                    $update_fee_topup_value->save();

                    $update = true;
                }

                if($pajak_topup_type == 'persen') {
                    $pajak_topup_persen = $request->pajak_topup_persen;

                    $data = [
                        'pajak_topup_persen' => $pajak_topup_persen,
                    ];

                    $update_pajak_type = $this->setting_model::where('code_setting', 'pajak_topup_type')->first();
                    $update_pajak_type->setting_value = $pajak_topup_type;
                    $update_pajak_type->save();

                    $update_pajak_topup_persen = $this->setting_model::where('code_setting', 'pajak_topup_persen')->first();
                    $update_pajak_topup_persen->setting_value = $pajak_topup_persen;
                    $update_pajak_topup_persen->save();

                    $update = true;


                    // dd('sukses');

                    // dd($data);
                } else if($pajak_topup_type == 'value') {
                    $pajak_topup_value = $request->pajak_topup_value;

                    $data = [
                        'pajak_topup_value' => $pajak_topup_value,
                    ];

                    $update_pajak_type = $this->setting_model::where('code_setting', 'pajak_topup_type')->first();
                    $update_pajak_type->setting_value = $pajak_topup_type;
                    $update_pajak_type->save();

                    $update_pajak_topup_value = $this->setting_model::where('code_setting', 'pajak_topup_value')->first();
                    $update_pajak_topup_value->setting_value = $pajak_topup_value;
                    $update_pajak_topup_value->save();

                    $update = true;
                }

                if ($update) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data fee beli saham setting!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data fee beli saham setting']);
                }
                return redirect(url('master/feesetting'));

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

    public function updatePencairan(Request $request)
    {
        $login    = Session::get("user");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

                $fee_pencairan_type = $request->fee_pencairan_type;
                $pajak_pencairan_type = $request->pajak_pencairan_type;

                if($fee_pencairan_type == 'persen') {
                    $fee_pencairan_persen = $request->fee_pencairan_persen;

                    $data = [
                        'fee_pencairan_persen' => $fee_pencairan_persen,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_pencairan_type')->first();
                    $update_fee_type->setting_value = $fee_pencairan_type;
                    $update_fee_type->save();

                    $update_fee_pencairan_persen = $this->setting_model::where('code_setting', 'fee_pencairan_persen')->first();
                    $update_fee_pencairan_persen->setting_value = $fee_pencairan_persen;
                    $update_fee_pencairan_persen->save();

                    $update = true;


                    // dd('sukses');

                    // dd($data);
                } else if($fee_pencairan_type == 'value') {
                    $fee_pencairan_value = $request->fee_pencairan_value;

                    $data = [
                        'fee_pencairan_value' => $fee_pencairan_value,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_pencairan_type')->first();
                    $update_fee_type->setting_value = $fee_pencairan_type;
                    $update_fee_type->save();

                    $update_fee_pencairan_value = $this->setting_model::where('code_setting', 'fee_pencairan_value')->first();
                    $update_fee_pencairan_value->setting_value = $fee_pencairan_value;
                    $update_fee_pencairan_value->save();

                    $update = true;
                }

                if($pajak_pencairan_type == 'persen') {
                    $pajak_pencairan_persen = $request->pajak_pencairan_persen;

                    $data = [
                        'pajak_pencairan_persen' => $pajak_pencairan_persen,
                    ];

                    $update_pajak_type = $this->setting_model::where('code_setting', 'pajak_pencairan_type')->first();
                    $update_pajak_type->setting_value = $pajak_pencairan_type;
                    $update_pajak_type->save();

                    $update_pajak_pencairan_persen = $this->setting_model::where('code_setting', 'pajak_pencairan_persen')->first();
                    $update_pajak_pencairan_persen->setting_value = $pajak_pencairan_persen;
                    $update_pajak_pencairan_persen->save();

                    $update = true;


                    // dd('sukses');

                    // dd($data);
                } else if($pajak_pencairan_type == 'value') {
                    $pajak_pencairan_value = $request->pajak_pencairan_value;

                    $data = [
                        'pajak_pencairan_value' => $pajak_pencairan_value,
                    ];

                    $update_pajak_type = $this->setting_model::where('code_setting', 'pajak_pencairan_type')->first();
                    $update_pajak_type->setting_value = $pajak_pencairan_type;
                    $update_pajak_type->save();

                    $update_pajak_pencairan_value = $this->setting_model::where('code_setting', 'pajak_pencairan_value')->first();
                    $update_pajak_pencairan_value->setting_value = $pajak_pencairan_value;
                    $update_pajak_pencairan_value->save();

                    $update = true;
                }

                if ($update) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data update pencairan saham setting!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data update pencairan saham setting']);
                }
                return redirect(url('master/feesetting'));

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

    public function updateReleasePendanaan(Request $request)
    {
        $login    = Session::get("user");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

                $fee_release_pendanaan_type = $request->fee_release_pendanaan_type;
                $pajak_release_pendanaan_type = $request->pajak_release_pendanaan_type;

                if($fee_release_pendanaan_type == 'persen') {
                    $fee_release_pendanaan_persen = $request->fee_release_pendanaan_persen;

                    $data = [
                        'fee_release_pendanaan_persen' => $fee_release_pendanaan_persen,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_release_pendanaan_type')->first();
                    $update_fee_type->setting_value = $fee_release_pendanaan_type;
                    $update_fee_type->save();

                    $update_fee_release_pendanaan_persen = $this->setting_model::where('code_setting', 'fee_release_pendanaan_persen')->first();
                    $update_fee_release_pendanaan_persen->setting_value = $fee_release_pendanaan_persen;
                    $update_fee_release_pendanaan_persen->save();

                    $update = true;


                    // dd('sukses');

                    // dd($data);
                } else if($fee_release_pendanaan_type == 'value') {
                    $fee_release_pendanaan_value = $request->fee_release_pendanaan_value;

                    $data = [
                        'fee_release_pendanaan_value' => $fee_release_pendanaan_value,
                    ];

                    $update_fee_type = $this->setting_model::where('code_setting', 'fee_release_pendanaan_type')->first();
                    $update_fee_type->setting_value = $fee_release_pendanaan_type;
                    $update_fee_type->save();

                    $update_fee_release_pendanaan_value = $this->setting_model::where('code_setting', 'fee_release_pendanaan_value')->first();
                    $update_fee_release_pendanaan_value->setting_value = $fee_release_pendanaan_value;
                    $update_fee_release_pendanaan_value->save();

                    $update = true;
                }

                if($pajak_release_pendanaan_type == 'persen') {
                    $pajak_release_pendanaan_persen = $request->pajak_release_pendanaan_persen;

                    $data = [
                        'pajak_release_pendanaan_persen' => $pajak_release_pendanaan_persen,
                    ];

                    $update_pajak_type = $this->setting_model::where('code_setting', 'pajak_release_pendanaan_type')->first();
                    $update_pajak_type->setting_value = $pajak_release_pendanaan_type;
                    $update_pajak_type->save();

                    $update_pajak_release_pendanaan_persen = $this->setting_model::where('code_setting', 'pajak_release_pendanaan_persen')->first();
                    $update_pajak_release_pendanaan_persen->setting_value = $pajak_release_pendanaan_persen;
                    $update_pajak_release_pendanaan_persen->save();

                    $update = true;


                    // dd('sukses');

                    // dd($data);
                } else if($pajak_release_pendanaan_type == 'value') {
                    $pajak_release_pendanaan_value = $request->pajak_release_pendanaan_value;

                    $data = [
                        'pajak_release_pendanaan_value' => $pajak_release_pendanaan_value,
                    ];

                    $update_pajak_type = $this->setting_model::where('code_setting', 'pajak_release_pendanaan_type')->first();
                    $update_pajak_type->setting_value = $pajak_release_pendanaan_type;
                    $update_pajak_type->save();

                    $update_pajak_release_pendanaan_value = $this->setting_model::where('code_setting', 'pajak_release_pendanaan_value')->first();
                    $update_pajak_release_pendanaan_value->setting_value = $pajak_release_pendanaan_value;
                    $update_pajak_release_pendanaan_value->save();

                    $update = true;
                }

                if ($update) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data release pendanaan saham setting!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data release pendanaan saham setting']);
                }
                return redirect(url('master/feesetting'));

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
