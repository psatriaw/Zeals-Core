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

use App\Http\Requests\Withdrawal\{ CreateWithdrawalRequest };

use App\Http\Models\OrderModel;
use App\Http\Models\ShopModel;
use App\Http\Models\WithdrawalModel;
use App\Http\Models\UserModel;
use App\Http\Models\RekeningDanaModel;

use Intervention\Image\ImageManagerStatic as Image;
use Xendit\Xendit;

class WithdrawalController extends Controller
{
    private $setting_model;
    private $previlege_model;
    private $helper;
    private $config;

    private $user_model;
    private $wishlist_model;
    private $shop_model;
    private $order_model;

    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $config['main_url']   = "master/withdrawal";
        $config['view']       = "withdrawal-view";
        $config['create']     = "withdrawal-create";
        $config['approve']    = "withdrawal-approve";
        $config['reject']     = "withdrawal-reject";

        $this->config       = $config;

        $this->user_model           = new UserModel();
        $this->withdrawal_model     = new WithdrawalModel();
        $this->shop_model           = new ShopModel();
        $this->rekening_dana_model  = new RekeningDanaModel();
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
                    "first_name"                    => "Nama",
                    "total_pencairan"               => "Total Pencairan",
                    "nama_pemilik_rekening"         => "Nama Pemilik Rekening",
                    "time_created"                  => "Waktu Permintaan"
                );

                $page       = $request->input("page");
                $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword    = $request->input("keyword");
                $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
                $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
                $str        = ($page != "") ? (($page - 1) * $limit) : 0;
                $data       = $this->withdrawal_model->getData($str, $limit, $keyword, $short, $shortmode, $login);

                // dd($data);

                $total          = $this->withdrawal_model->countTotal();
                $pending        = $this->withdrawal_model->countTotal('pending');
                $terlaksana     = $this->withdrawal_model->countTotal('berhasil');
                $rejected       = $this->withdrawal_model->countTotal('gagal');

                $totaldata  = $this->withdrawal_model->countData($keyword, $login);
                $pagging    = $this->helper->showPagging($totaldata, url($this->config['main_url'] . '/?keyword=' . $keyword . "&short=" . $short . "&shortmode=" . $shortmode), $position = "", $page, $limit, 2);

                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "data"           => $data,
                    "pagging"        => $pagging,
                    "input"          => $request->all(),
                    "default"        => $default,
                    "shorter"        => $shorter,
                    "page"           => $page,
                    "limit"          => $limit,
                    "config"         => $this->config,
                    "total_data"     => $total->count(),
                    "total_pending"  => $pending->count(),
                    "total_terlaksana"  => $terlaksana->count(),
                    "total_rejected"  => $rejected->count(),
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );

                if ($login->id_department != 1) {
                    $detail = $this->shop_model->getDetailShopByOwner($login->id_user);
                    $data   = $this->shop_model->getDetail($detail->id_shop);

                    $transactions   = $this->order_model->getTotalBalance($data->id_shop);
                    $datawithdrawal = $this->withdrawal_model->getTotalWithdrawal($data->id_shop);
                    $outstanding    = $this->order_model->getTotalOutstandingBalance($data->id_shop);

                    $balance        = $transactions - $datawithdrawal;

                    $datacontent['balance']      = $balance;
                    $datacontent['withdrawn']    = $datawithdrawal;
                    $datacontent['outstanding']  = $outstanding;
                }

                $view     = View::make("backend.withdrawal.index", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Withdrawal",
                    "description"   => $webname . " | Withdrawal",
                    "keywords"      => $webname . " | Withdrawal"
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


    public function create(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
                $detail = $this->shop_model->getDetailShopByOwner($login->id_user);
                $data   = $this->shop_model->getDetail($detail->id_shop);

                $transactions   = $this->order_model->getTotalBalance($detail->id_shop);
                $datawithdrawal = $this->withdrawal_model->getTotalWithdrawal($detail->id_shop);
                $balance        = $transactions - $datawithdrawal;

                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "config"         => $this->config,
                    "data"           => $data,
                    "balance"        => $balance,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );

                $view     = View::make("backend.withdrawal.create", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Withdrawal",
                    "description"   => $webname . " | Withdrawal",
                    "keywords"      => $webname . " | Withdrawal"
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

    public function store(CreateWithdrawalRequest $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
                $detail = $this->shop_model->getDetailShopByOwner($login->id_user);
                $data   = $this->shop_model->getDetail($detail->id_shop);

                $transactions   = $this->order_model->getTotalBalance($detail->id_shop);
                $datawithdrawal = $this->withdrawal_model->getTotalWithdrawal($detail->id_shop);
                $balance        = $transactions - $datawithdrawal;

                $total_amount = $request->input("total_amount");

                if ($balance >= $total_amount) {
                    $datawithdrawal = array(
                        "time_created"          => time(),
                        "total_amount"          => $total_amount,
                        "status"                => "pending",
                        "bank_account"          => $detail->account_name,
                        "bank_account_number"   => $detail->account_number,
                        "bank_name"             => $detail->bank_name,
                        "id_ref"                => $detail->id_shop,
                        "id_user"               => $login->id_user
                    );

                    $hasil = $this->withdrawal_model->insertData($datawithdrawal);
                    if ($hasil) {
                        Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => "Your request has been created, please wait. Our administrator will process your request in 3 - 4 business days"]);
                        return redirect(url($this->config['main_url']));
                    } else {
                        Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => "Can't create your request."]);
                        return redirect(url($this->config['main_url']));
                    }
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => "Total amount of your request can't higher than your balance!"]);
                    return redirect(url($this->config['main_url']));
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

    public function approve($id, Request $request)
    {
        $admin_fee  = 5500;

        $login      = Session::get("user");
        $webname    = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['approve'])) {
                // $id = $request->input("id");

                $datawithdrawal = array(
                    "id_withdrawal" => $id,
                    "last_update"       => time(),
                    "status"              => "approved"
                );


                $hasil = $this->withdrawal_model->updateData($datawithdrawal);

                if ($hasil) {
                    $detail = $this->withdrawal_model->getDetail($id);

                    Xendit::setApiKey("xnd_production_UIWl2HG8FsIAUQ5GRpiJcyN9xKjJKXsjRcIS1lE6wv6tQIvRYxcfBIcGbpfNbO");

                    $params = [
                        'external_id'           => $detail->withdrawal_code,
                        'amount'                => $detail->total_pencairan - $detail->fee,
                        'bank_code'             => $detail->nama_bank,
                        'account_holder_name'   => $detail->nama_pemilik_rekening,
                        'account_number'        => $detail->nomor_rekening,
                        'description'           => 'Pencairan dana dengan kode #'.$detail->withdrawal_code,
                        'X-IDEMPOTENCY-KEY'     => $detail->withdrawal_code
                    ];

                    $createDisbursements = \Xendit\Disbursements::create($params);
                    print_r($createDisbursements);

                    if($createDisbursements['status']=="PENDING"){
                        $updateSaldo = $this->rekening_dana_model->updateSaldo($detail->id_user,$detail->total_pencairan);

                        Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => "Withdrawal approved"]);
                    }else{
                        Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => "Can't approve withdrawal "]);
                    }
                    return redirect(url($this->config['main_url']));
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => "Can't approve withdrawal"]);
                    return redirect(url($this->config['main_url']));
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

    public function reject(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['approve'])) {
                $id = $request->input("id");

                $datawithdrawal = array(
                    "id_withdrawal"       => $id,
                    "status"              => "rejected"
                );

                $hasil = $this->withdrawal_model->updateData($datawithdrawal);
                if ($hasil) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => "Withdrawal rejected"]);
                    return redirect(url($this->config['main_url']));
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => "Can't reject withdrawal"]);
                    return redirect(url($this->config['main_url']));
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
