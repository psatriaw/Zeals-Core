<?php

namespace App\Http\Controllers;

//===============================
use Xendit\Xendit;


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

use App\Http\Models\SahamModel;
use App\Http\Models\DevidenDetailModel;
use App\Http\Models\ProductModel;
use App\Http\Models\UserModel;
use App\Http\Models\CampaignModel;
use App\Http\Models\DevidenModel;
use App\Http\Models\XenditPayoutModel;
use App\Http\Models\RekeningDanaTransaksiModel;
use App\Http\Models\RekeningDanaModel;
use App\Http\Models\XenditLogModel;
use App\Http\Models\NotificationModel;
use Illuminate\Support\Facades\DB;

class XenditController extends Controller
{
    private $setting_model;
    private $previlege_model;
    private $helper;
    private $config;

    private $user_model;
    private $stock_recipe_model;
    private $naikan_rumus_model;
    private $naikan_model;
    private $emailcontrol;

    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $config['main_url'] = "payment/xendit";
        $config['view']     = "payment-xendit-view";
        $config['charge']   = "payment-xendit-charge";
        $config['release']   = "payment-xendit-release";


        $this->config       = $config;

        $this->user_model             = new UserModel();
        $this->payout_model           = new XenditPayoutModel();
        $this->campaign_model         = new CampaignModel();
        $this->deviden_model          = new DevidenModel();
        $this->xendi_log_model        = new XenditLogModel();
        $this->saham_model            = new SahamModel();
        $this->rekening_deviden_model = new DevidenDetailModel();
        $this->rekening_dana_model    = new RekeningDanaTransaksiModel();
        $this->rekening_model         = new RekeningDanaModel();
        $this->notification_model     = new NotificationModel();
        $this->emailcontrol           = new EmailController();

        $payment_mode = $this->setting_model->getSettingVal("xendit_status");

        switch ($payment_mode) {
            case 'sandbox':
                $apikey   = $this->setting_model->getSettingVal("xendit_secret_key_sandbox");
                break;

            case 'live':
                $apikey   = $this->setting_model->getSettingVal("xendit_secret_key_live");
                break;
        }
        // print $apikey;
        //exit();
        Xendit::setApiKey($apikey);
    }

    public function chargedeviden(Request $request)
    {

        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['charge'])) {
                $id_deviden     = $request->id_deviden;

                $amount     = $request->total_tagihan;
                $trx_code   = $this->payout_model->createCode();
                $bank_code  = $request->bank_account;

                $payoutdata = array(
                    'payment_time'            => time(),
                    'time_created'            => time(),
                    'last_update'             => time(),
                    'trx_response'            => '',
                    'trx_callback'            => '',
                    'total_amount'            => $amount,
                    'total_fee'               => $request->total_fee,
                    'total_pajak'             => $request->total_pajak,
                    'fee_type'                => $request->pajak_type,
                    'pajak_type'              => $request->fee_type,
                    'bank_account'            => $bank_code,
                    'bank_account_name'       => '',
                    'bank_account_number'     => '',
                    'description'             => "Pembayaran Invoice Deviden Oleh Penerbit",
                    'invoice_code'            => $trx_code,
                    'trx_status'              => '',
                    'trx_sign'                => 'kredit',
                    'trx_type'                => 'deviden'
                );

                // dd($trx_code);


                $id_payment = $this->payout_model->insertData($payoutdata);


                $params = [
                    "external_id"     => $trx_code,
                    "bank_code"       => $bank_code,   //accepted bank code MANDIRI, BNI, BNI_SYARIAH, BRI, PERMATA, BCA, SAHABAT_SAMPOERNA
                    "name"            => $request->pic_name,
                    "is_closed"       =>  true,
                    "expected_amount" => (int)$amount,
                ];

                //print "<pre>";
                //print_r($params);
                $createVA = \Xendit\VirtualAccounts::create($params);

                $updatedatapayout = array(
                    "trx_callback"  => json_encode($createVA),
                    "id_payment"    => $id_payment,
                    "va_number"     => $createVA['account_number']
                );

                //print_r($updatedatapayout);
                $this->payout_model->updateData($updatedatapayout);


                $updatedeviden = array(
                    "id_payment"  => $id_payment,
                    "id_deviden"  => $id_deviden
                );

                // $datauser = DB::table('tb_payment')->where('tb_payment.invoice_code', $trx_code)
                //     ->join('tb_deviden', 'tb_deviden.id_payment', 'tb_payment.id_payment')
                //     ->join('tb_penerbit', 'tb_deviden.id_penerbit', 'tb_penerbit.id_penerbit')
                //     ->join('tb_user', 'tb_user.id_user', 'tb_penerbit.id_user')
                //     ->select('tb_penerbit.email', 'tb_payment.invoice_code', 'tb_user.first_name', 'tb_deviden.total_tagihan', 'tb_payment.bank_account', 'tb_payment.va_number')
                //     ->first();

                // dd($datauser);


                //print_r($updatedeviden);
                $this->deviden_model->updateData($updatedeviden);

                $datauser = DB::table('tb_payment')->where('tb_payment.invoice_code', $trx_code)
                        ->join("tb_deviden", "tb_deviden.id_payment", "=", "tb_payment.id_payment")
                        ->join('tb_penerbit', 'tb_deviden.id_penerbit', 'tb_penerbit.id_penerbit')
                        ->join('tb_user', 'tb_user.id_user', 'tb_penerbit.id_user')
                        ->select('tb_penerbit.email', 'tb_payment.invoice_code', 'tb_user.first_name', 'tb_deviden.total_tagihan', 'tb_payment.bank_account', 'tb_payment.va_number')
                        ->first();
                // dd($datauser);

                $this->emailcontrol->sendInvoiceInformation($datauser->email, $trx_code, $params["name"], 'Transfer', $params['expected_amount'], 'pending', $params['bank_code'], $updatedatapayout['va_number']);

                if ($createVA['status'] == "PENDING") {


                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Berhasil membuat Nomor VA, Silahkan lakukan pembayaran melalui internet/mobile banking anda']);
                } else {
                    Session::flash('info', ['status' => 'danger', 'alert-class' => 'alert-danger', 'message' => 'Gagal membuat Nomor VA!']);
                }

                return redirect(url('master/tagihan/payment/' . $id_deviden));
            } else {
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

    public function release(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['release'])) {
                $id_campaign = $request->input('id_campaign');
                $payload = array(
                    "external_id"           => $id_campaign,
                    "account_holder_name"   => $request->input('bank_account_name'),
                    "account_number"        => $request->input('bank_account_number'),
                    "bank_code"             => $request->input('bank_account'),
                    "amount"                => $request->input('sub_total'),
                    "description"           => $request->input('description'),
                    'X-IDEMPOTENCY-KEY'     => $request->input('invoice_code')
                );

                try {
                    $response = \Xendit\Disbursements::create($payload);

                    $payoutdata = array(
                        'payment_time'            => time(),
                        'time_created'            => time(),
                        'last_update'             => time(),
                        'trx_response'            => '',
                        'trx_callback'            => json_encode($response),
                        'total_amount'            => $request->sub_total,
                        'total_fee'               => $request->biaya_admin_value,
                        'total_pajak'             => $request->biaya_pajak_value,
                        'fee_type'                => $this->setting_model->getSettingVal('fee_release_pendanaan_type'),
                        'pajak_type'              => $this->setting_model->getSettingVal('pajak_release_pendanaan_type'),
                        'bank_account'        => $request->bank_account,
                        'bank_account_name'   => $request->bank_account_name,
                        'bank_account_number' => $request->bank_account_number,
                        'description'         => $request->input('description'),
                        'invoice_code'        => $request->input('invoice_code'),
                        'trx_status'          => $response['status'],
                        'trx_sign'            => 'debit',
                        'trx_type'            => 'release'
                    );

                    $id_payout = $this->payout_model->insertData($payoutdata);

                    $releaseinfo = array(
                        'id_campaign'             => $request->id_campaign,
                        'tanggal_release_dana'    => date("Y-m-d"),
                        'id_payout'               => $id_payout
                    );

                    if ($response) {
                        if ($response['status'] == 'PENDING') {
                            $releaseinfo['release_status']  = "released";
                            $this->campaign_model->updateData($releaseinfo);

                            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Berhasil melakukan pengiriman dana ke Rekening Tujuan! Mohon menunggu hingga informasi callback dari provider Xendit untuk informasi keberhasilan transaksi']);
                            return redirect(url('master/campaign/releasedana/' . $id_campaign . '?backlink=master/campaign/manage/' . $id_campaign));
                        } elseif ($response['status'] == 'SWITCHING_NETWORK_ERROR') {
                            $releaseinfo['release_status']  = "failed";
                            $this->campaign_model->updateData($releaseinfo);

                            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal Melakukan Pengiriman Dana, jaringan perbankan provider sedang tidak stabil!']);
                            return redirect(url('master/campaign/releasedana/' . $id_campaign . '?backlink=master/campaign/manage/' . $id_campaign));
                        } elseif ($response['status'] == 'UNKNOWN_BANK_NETWORK_ERROR') {
                            $releaseinfo['release_status']  = "failed";
                            $this->campaign_model->updateData($releaseinfo);

                            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal Melakukan Pengiriman Dana, jaringan perbankan Tujuan sedang tidak stabil!']);
                            return redirect(url('master/campaign/releasedana/' . $id_campaign . '?backlink=master/campaign/manage/' . $id_campaign));
                        } elseif ($response['status'] == 'INVALID_DESTINATION') {
                            $releaseinfo['release_status']  = "failed";
                            $this->campaign_model->updateData($releaseinfo);

                            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal Melakukan Pengiriman Dana, rekening bank tujuan tidak ditemukan!']);
                            return redirect(url('master/campaign/releasedana/' . $id_campaign . '?backlink=master/campaign/manage/' . $id_campaign));
                        } elseif ($response['status'] == 'REJECTED_BY_BANK') {
                            $releaseinfo['release_status']  = "failed";
                            $this->campaign_model->updateData($releaseinfo);

                            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal Melakukan Pengiriman Dana, ditolak oleh bank tujuan!']);
                            return redirect(url('master/campaign/releasedana/' . $id_campaign . '?backlink=master/campaign/manage/' . $id_campaign));
                        } elseif ($response['status'] == 'TRANSFER_ERROR') {
                            $releaseinfo['release_status']  = "failed";
                            $this->campaign_model->updateData($releaseinfo);

                            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal Melakukan Pengiriman Dana, terjadi kesalahan provider!']);
                            return redirect(url('master/campaign/releasedana/' . $id_campaign . '?backlink=master/campaign/manage/' . $id_campaign));
                        } elseif ($response['status'] == 'TEMPORARY_TRANSFER_ERROR') {
                            $releaseinfo['release_status']  = "failed";
                            $this->campaign_model->updateData($releaseinfo);

                            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal Melakukan Pengiriman Dana, tidak dapat mengirim uang untuk sementara waktu!']);
                            return redirect(url('master/campaign/releasedana/' . $id_campaign . '?backlink=master/campaign/manage/' . $id_campaign));
                        } elseif ($response['status'] == 'INSUFFICIENT_BALANCE') {
                            $releaseinfo['release_status']  = "failed";
                            $this->campaign_model->updateData($releaseinfo);

                            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal Melakukan Pengiriman Dana, balance pada platform Xendit tidak cukup!']);
                            return redirect(url('master/campaign/releasedana/' . $id_campaign . '?backlink=master/campaign/manage/' . $id_campaign));
                        }
                    } else {
                        Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal Melakukan Pengiriman Dana, Platform Xendit tidak meresponse!']);
                        return redirect(url('master/campaign/releasedana/' . $id_campaign . '?backlink=master/campaign/manage/' . $id_campaign));
                    }
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
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

    public function pay(Request $request)
    {
        $data =  file_get_contents('php://input');
        $data = json_decode($data, true);

        print "<pre>";
        print_r($data);


        $trx_code       = $data['external_id'];
        $amount         = $data['amount'];
        $trx_callback   = json_encode($data);
        $va_code        = $data['account_number'];

        $datalog = array(
            "time_created"  => time(),
            "content"       => json_encode($data)
        );

        $this->xendi_log_model->insertData($datalog);

        $checkpayment   = $this->payout_model->getDetailByInvoiceCode($trx_code);
        print_r($checkpayment);
        //exit();
        if ($checkpayment && !($checkpayment->trx_status=="PENDING" || $checkpayment->trx_status=="COMPLETED")) {
            //JIKA ADA PADA PAYMENT
            $paymentupdate = array(
                "trx_response"  => json_encode($data),
                "last_update"   => time(),
                "trx_status"    => 'COMPLETED',
                "id_payment"    => $checkpayment->id_payment
            );

            $this->payout_model->updateData($paymentupdate);


            switch ($checkpayment->trx_type) {
                case 'deviden':
                    $checkdevidenwithpaymentid = $this->deviden_model->getDetailByPaymentID($checkpayment->id_payment);
                    $updatedeviden = array(
                        "status"        => "paid",
                        "id_deviden"    => $checkdevidenwithpaymentid->id_deviden
                    );

                    $this->deviden_model->updateData($updatedeviden);

                    $dataemail = [
                        'invoice_number' => $trx_code,
                        //   'tipe_pembayaran' =>

                    ];

                    $datauser = DB::table('tb_payment')->where('tb_payment.invoice_code', $trx_code)
                        ->join('tb_deviden', 'tb_deviden.id_payment', 'tb_payment.id_payment')
                        ->join('tb_penerbit', 'tb_deviden.id_penerbit', 'tb_penerbit.id_penerbit')
                        ->join('tb_user', 'tb_user.id_user', 'tb_penerbit.id_user')
                        ->select('tb_penerbit.email', 'tb_payment.invoice_code', 'tb_user.first_name', 'tb_deviden.total_tagihan', 'tb_payment.bank_account', 'tb_payment.va_number')
                        ->first();

                    //   $email, $invoice_number, $nama_user, $tipe_pembayaran, $nominal, $status, $bank, $va_number

                    //print "<pre>";

                    $this->emailcontrol->sendInvoiceInformation($datauser->email, $datauser->invoice_code, $datauser->first_name, 'Bank Transfer', $datauser->total_tagihan, 'success', $datauser->bank_account, $datauser->va_number);

                    $pemodals = $this->saham_model->getParaPembeliSaham($checkdevidenwithpaymentid->id_campaign);
                    if($pemodals){
                      $infosaham    = $this->campaign_model->getDetail($checkdevidenwithpaymentid->id_campaign);
                      $total_saham  = $infosaham->total_lembar;

                      //print_r($pemodals->toArray());

                      foreach ($pemodals as $key => $value) {
                        //memasukkan deviden ke rekening
                        $percentage = $value->quantity/$total_saham;
                        $amount     = $percentage * ($checkdevidenwithpaymentid->total_tagihan - $checkdevidenwithpaymentid->fee_total);

                        //print_r($amount);


                        $datarekeningdeviden = array(
                          "time_created"  => time(),
                          "last_update"   => time(),
                          "id_campaign"   => $checkdevidenwithpaymentid->id_campaign,
                          "id_user"       => $value->id_user,
                          "deviden_date"  => date("Y-m-d"),
                          "amount"        => $amount,
                          "deviden_code"  => $checkdevidenwithpaymentid->invoice_code,
                          "id_penerbit"   => $infosaham->id_penerbit,
                          "id_deviden"    => $checkdevidenwithpaymentid->id_deviden,
                          "id_rups"       => 0,
                        );

                        //print_r($datarekeningdeviden);

                        $this->rekening_deviden_model->insertData($datarekeningdeviden);
                        $rekening_dana = $this->rekening_model->getCurrentRekening($value->id_user);

                        $datarekeningdana = array(
                          "time_created"  => time(),
                          "last_update"   => time(),
                          "description"   => "Deviden dari penerbit dengan kode #".($checkdevidenwithpaymentid->invoice_code),
                          "debit"         => 0,
                          "kredit"        => $amount,
                          "status"        => "paid",
                          "id_rekening_dana"  => $rekening_dana->id_rekening_dana,
                          "id_user"       => $value->id_user,
                          "id_author"     => $value->id_user,
                          "trx_code"      => $this->rekening_dana_model->createCode(),
                          "trx_callback"  => "",
                          "trx_request"   => "",
                          "va_code"       => "",
                          "trx_type"      => "deviden",
                          "trx_amount"    => $amount,
                          "reff_id"       => 0,
                          "trx_action"    => "system"
                        );
                        //print_r($datarekeningdana);

                        //exit();
                        $this->rekening_dana_model->insertData($datarekeningdana);

                        $updatesaldo = array(
                          "last_update" => time(),
                          "saldo"       => $rekening_dana->saldo + $amount,
                          "id_rekening_dana"  => $rekening_dana->id_rekening_dana
                        );

                        $this->rekening_model->updateData($updatesaldo);
                        //memasukkan deviden ke rekening

                        //notifikasi ke pemodal kalau ada dana deviden cair
                        $datanotifikasi = array(
                          "status"        => "unread",
                          "time_created"  => time(),
                          "last_update"   => time(),
                          "title"         => "Deviden Telah Cair",
                          "content"       => "Deviden dari penerbit dengan kode #".($checkdevidenwithpaymentid->invoice_code)." telah cair sebesar Rp.".number_format($amount,2),
                          "id_user"       => $value->id_user
                        );

                        $this->notification_model->insertData($datanotifikasi);
                        //notifikasi ke pemodal kalau ada dana deviden cair
                      }
                    }


                    return response([
                        'data'      => $dataemail,
                        'datajoin'  => $datauser
                    ]);
                    break;
            }
            //JIKA ADA PADA PAYMENT
        }else{
          echo "PAYMENT PAID";
        }
    }
}
