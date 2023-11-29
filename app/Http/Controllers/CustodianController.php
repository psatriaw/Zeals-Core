<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\IGWHelper;
use App\Http\Models\CustodianModel;
use App\Http\Models\PrevilegeModel;
use App\Http\Models\SahamModel;
use App\Http\Models\SettingModel;
use App\Http\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CustodianController extends Controller
{
    //
    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();
        $this->custodian_model = new CustodianModel();
        $this->saham_model = new SahamModel();

        $this->user_model = new UserModel();

        $dataconfig['main_url'] = "custodian";
        $dataconfig['view-user']     = "custodian-user-view";
        $dataconfig['create-user'] = "custodian-user-create";

        $dataconfig['view-purchase']     = "custodian-purchase-view";
        $dataconfig['create-purchase'] = "custodian-purchase-create";

        $this->config = $dataconfig;
    }

    public function indexUser(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view-user'])) {
                $default    = array(
                    "short"     => "time_created",
                    "shortmode" => "desc"
                );

                $shorter = array(
                    "file_name" => "Nama File",
                    "time_created" => "Tanggal Dibuat",
                    "last_update" => "Tanggal Diupdate",
                );

                $page       = $request->input("page");
                $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword    = $request->input("keyword");
                $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
                $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
                $str        = ($page != "") ? (($page - 1) * $limit) : 0;
                $data       = $this->custodian_model->getCustodianUser($str, $limit, $keyword, $short, $shortmode);

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
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );


                $view     = View::make("backend.master.custodian.user.index", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " |  Custodian Data",
                    "description"   => $webname . " |  Custodian Data",
                    "keywords"      => $webname . " |  Custodian Data"
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

    public function createUser(Request $request)
    {
        $login    = Session::get("user");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create-user'])) {

                $data = array(
                    "time_created"      => time(),
                    "last_update"       => time(),
                );

                $userdata = $this->user_model->getUserCustodian();

                if (count($userdata) == 0) {
                    Session::flash('info', ['status' => 'warning', 'alert-class' => 'alert-warning', 'message' => 'Data custodian registran sudah diexport semua']);
                    return redirect(url('custodian/user'));
                }
                // dd($userdata);

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A1', 'user custodian ' . date('Y-m-d', time()));
                // make judul sheet
                $sheet->setCellValue('A2', 'No.')->getStyle('A2')->getFont()->setBold(true);
                $sheet->setCellValue('B2', 'Nama Depan')->getStyle('B2')->getFont()->setBold(true);
                $sheet->setCellValue('C2', 'Nama Belakang')->getStyle('C2')->getFont()->setBold(true);
                $sheet->setCellValue('D2', 'Email')->getStyle('D2')->getFont()->setBold(true);
                $sheet->setCellValue('E2', 'Telepon')->getStyle('E2')->getFont()->setBold(true);

                // kyc pribadi
                $sheet->setCellValue('F2', 'TTL')->getStyle('F2')->getFont()->setBold(true);
                $sheet->setCellValue('G2', 'Jenis Kelamin')->getStyle('G2')->getFont()->setBold(true);
                $sheet->setCellValue('H2', 'Pendidikan Terakhir')->getStyle('H2')->getFont()->setBold(true);
                $sheet->setCellValue('I2', 'Kewarganegaraan')->getStyle('I2')->getFont()->setBold(true);
                $sheet->setCellValue('J2', 'Email')->getStyle('J2')->getFont()->setBold(true);
                $sheet->setCellValue('K2', 'NIK')->getStyle('K2')->getFont()->setBold(true);
                $sheet->setCellValue('L2', 'Tgl Kadaluwarsa KTP')->getStyle('L2')->getFont()->setBold(true);
                $sheet->setCellValue('M2', 'Nomor Passport')->getStyle('M2')->getFont()->setBold(true);
                $sheet->setCellValue('N2', 'Tgl. Kadaluwarsa Passport')->getStyle('N2')->getFont()->setBold(true);

                // kyc keluarga
                $sheet->setCellValue('O2', 'Status Pernikahan')->getStyle('O2')->getFont()->setBold(true);
                $sheet->setCellValue('P2', 'Nama Lengkap Pasangan')->getStyle('P2')->getFont()->setBold(true);
                $sheet->setCellValue('Q2', 'Nama Ibu')->getStyle('Q2')->getFont()->setBold(true);
                $sheet->setCellValue('R2', 'Nama Ahli Waris')->getStyle('R2')->getFont()->setBold(true);
                $sheet->setCellValue('S2', 'Hubungan Ahli Waris')->getStyle('S2')->getFont()->setBold(true);
                $sheet->setCellValue('T2', 'Telpon Ahli Waris')->getStyle('T2')->getFont()->setBold(true);

                // kyc alamat
                $sheet->setCellValue('U2', 'Negara')->getStyle('U2')->getFont()->setBold(true);
                $sheet->setCellValue('V2', 'Provinsi')->getStyle('V2')->getFont()->setBold(true);
                $sheet->setCellValue('W2', 'Kabupaten')->getStyle('W2')->getFont()->setBold(true);
                $sheet->setCellValue('X2', 'Kecamtan')->getStyle('X2')->getFont()->setBold(true);
                $sheet->setCellValue('Y2', 'Alamat')->getStyle('Y2')->getFont()->setBold(true);
                $sheet->setCellValue('Z2', 'Kode Pos')->getStyle('Z2')->getFont()->setBold(true);
                $sheet->setCellValue('AA2', 'Domisili')->getStyle('AA2')->getFont()->setBold(true);

                // kyc bank
                $sheet->setCellValue('AB2', 'Nama Pemilik Rekening Utama')->getStyle('AB2')->getFont()->setBold(true);
                $sheet->setCellValue('AC2', 'Nama Bank Rekening Utama')->getStyle('AC2')->getFont()->setBold(true);
                $sheet->setCellValue('AD2', 'Nomor Rekening Utama')->getStyle('AD2')->getFont()->setBold(true);

                $sheet->setCellValue('AE2', 'Nama Pemilik Rekening 2')->getStyle('AE2')->getFont()->setBold(true);
                $sheet->setCellValue('AF2', 'Nama Bank Rekening 2')->getStyle('AF2')->getFont()->setBold(true);
                $sheet->setCellValue('AG2', 'Nomor Rekening 2')->getStyle('AG2')->getFont()->setBold(true);

                $sheet->setCellValue('AH2', 'Nama Pemilik Rekening 3')->getStyle('AH2')->getFont()->setBold(true);
                $sheet->setCellValue('AI2', 'Nama Bank Rekening 3')->getStyle('AI2')->getFont()->setBold(true);
                $sheet->setCellValue('AJ2', 'Nomor Rekening 3')->getStyle('AJ2')->getFont()->setBold(true);


                // kyc pajak
                $sheet->setCellValue('AK2', 'Total Pendapatan')->getStyle('AK2')->getFont()->setBold(true);
                $sheet->setCellValue('AL2', 'Kode Akun Pajak')->getStyle('AL2')->getFont()->setBold(true);
                $sheet->setCellValue('AM2', 'NPWP')->getStyle('AM2')->getFont()->setBold(true);
                $sheet->setCellValue('AN2', 'Tgl Kadaluwarsa NPWP')->getStyle('AN2')->getFont()->setBold(true);
                $sheet->setCellValue('AO2', 'Rekening Efek')->getStyle('AO2')->getFont()->setBold(true);
                $sheet->setCellValue('AP2', 'Nomor SID')->getStyle('AP2')->getFont()->setBold(true);
                $sheet->setCellValue('AQ2', 'Tgl Registrasi Rek. Efek')->getStyle('AQ2')->getFont()->setBold(true);


                // KYC pekerjaan
                $sheet->setCellValue('AR2', 'Pekerjaan')->getStyle('AR2')->getFont()->setBold(true);
                $sheet->setCellValue('AS2', 'Total Aset')->getStyle('AS2')->getFont()->setBold(true);
                $sheet->setCellValue('AT2', 'Sumber Dana')->getStyle('AT2')->getFont()->setBold(true);
                $sheet->setCellValue('AU2', 'Motivasi Investasi')->getStyle('AU2')->getFont()->setBold(true);

                $indexSheet = 3;
                $indexNumber = 1;
                foreach ($userdata as $ud) {
                    $sheet->setCellValue('A' . $indexSheet, $indexNumber);
                    $sheet->setCellValue('B' . $indexSheet, $ud->first_name);
                    $sheet->setCellValue('C' . $indexSheet, $ud->last_name);
                    $sheet->setCellValue('D' . $indexSheet, $ud->email);
                    $sheet->setCellValue('E' . $indexSheet, $ud->phone);

                    // kyc pribadi
                    $sheet->setCellValue('F' . $indexSheet, $ud->kyc_biodata_pribadi->kota_lahir . ' ' . $ud->kyc_biodata_pribadi->tgl_lahir);
                    $sheet->setCellValue('G' . $indexSheet, $ud->kyc_biodata_pribadi->jenis_kelamin);
                    $sheet->setCellValue('H' . $indexSheet, $ud->kyc_biodata_pribadi->pendidikan_terakhir);
                    $sheet->setCellValue('I' . $indexSheet, $ud->kyc_biodata_pribadi->kewarganegaraan);
                    $sheet->setCellValue('J' . $indexSheet, $ud->kyc_biodata_pribadi->email);
                    $sheet->setCellValue('K' . $indexSheet, $ud->kyc_biodata_pribadi->nomor_nik);
                    $sheet->setCellValue('L' . $indexSheet, $ud->kyc_biodata_pribadi->tgl_kadaluwarsa_ktp);
                    $sheet->setCellValue('M' . $indexSheet, $ud->kyc_biodata_pribadi->nomor_passport);
                    $sheet->setCellValue('N' . $indexSheet, $ud->kyc_biodata_pribadi->tgl_kadaluwarsa_passport);

                    // kyc keluarga
                    $sheet->setCellValue('O' . $indexSheet, $ud->kyc_biodata_keluarga->status_pernikahan);
                    $sheet->setCellValue('P' . $indexSheet, $ud->kyc_biodata_keluarga->nama_lengkap_pasangan);
                    $sheet->setCellValue('Q' . $indexSheet, $ud->kyc_biodata_keluarga->nama_ibu);
                    $sheet->setCellValue('R' . $indexSheet, $ud->kyc_biodata_keluarga->nama_ahli_waris);
                    $sheet->setCellValue('S' . $indexSheet, $ud->kyc_biodata_keluarga->hubungan_ahli_waris);
                    $sheet->setCellValue('T' . $indexSheet, $ud->kyc_biodata_keluarga->nomor_telp_ahli_waris);

                    // kyc alamat
                    $sheet->setCellValue('U' . $indexSheet, $ud->kyc_alamat->negara);
                    $sheet->setCellValue('V' . $indexSheet, $ud->kyc_alamat->provinsi);
                    $sheet->setCellValue('W' . $indexSheet, $ud->kyc_alamat->kabupaten);
                    $sheet->setCellValue('X' . $indexSheet, $ud->kyc_alamat->kecamatan);
                    $sheet->setCellValue('Y' . $indexSheet, $ud->kyc_alamat->alamat_lengkap);
                    $sheet->setCellValue('Z' . $indexSheet, $ud->kyc_alamat->kode_pos);
                    $sheet->setCellValue('AA' . $indexSheet, $ud->kyc_alamat->domisili);

                    // kyc bank
                    if (count($ud->kyc_akun_bank) == 1) {
                        $sheet->setCellValue('AB' . $indexSheet, $ud->kyc_akun_bank[0]->nama_pemilik);
                        $sheet->setCellValue('AC' . $indexSheet, $ud->kyc_akun_bank[0]->nama_bank);
                        $sheet->setCellValue('AD' . $indexSheet, $ud->kyc_akun_bank[0]->nomor_rekening);
                    } else if (count($ud->kyc_akun_bank) == 2) {
                        $sheet->setCellValue('AB' . $indexSheet, $ud->kyc_akun_bank[0]->nama_pemilik);
                        $sheet->setCellValue('AC' . $indexSheet, $ud->kyc_akun_bank[0]->nama_bank);
                        $sheet->setCellValue('AD' . $indexSheet, $ud->kyc_akun_bank[0]->nomor_rekening);

                        $sheet->setCellValue('AE' . $indexSheet, $ud->kyc_akun_bank[1]->nama_pemilik);
                        $sheet->setCellValue('AF' . $indexSheet, $ud->kyc_akun_bank[1]->nama_bank);
                        $sheet->setCellValue('AG' . $indexSheet, $ud->kyc_akun_bank[1]->nomor_rekening);
                    } else if (count($ud->kyc_akun_bank) == 3) {
                        $sheet->setCellValue('AB' . $indexSheet, $ud->kyc_akun_bank[0]->nama_pemilik);
                        $sheet->setCellValue('AC' . $indexSheet, $ud->kyc_akun_bank[0]->nama_bank);
                        $sheet->setCellValue('AD' . $indexSheet, $ud->kyc_akun_bank[0]->nomor_rekening);

                        $sheet->setCellValue('AE' . $indexSheet, $ud->kyc_akun_bank[1]->nama_pemilik);
                        $sheet->setCellValue('AF' . $indexSheet, $ud->kyc_akun_bank[1]->nama_bank);
                        $sheet->setCellValue('AG' . $indexSheet, $ud->kyc_akun_bank[1]->nomor_rekening);

                        $sheet->setCellValue('AH' . $indexSheet, $ud->kyc_akun_bank[2]->nama_pemilik);
                        $sheet->setCellValue('AI' . $indexSheet, $ud->kyc_akun_bank[2]->nama_bank);
                        $sheet->setCellValue('AJ' . $indexSheet, $ud->kyc_akun_bank[2]->nomor_rekening);
                    }

                    // kyc pajak
                    $sheet->setCellValue('AK' . $indexSheet, $ud->kyc_pajak->total_pendapatan);
                    $sheet->setCellValue('AL' . $indexSheet, $ud->kyc_pajak->kode_akun_pajak);
                    $sheet->setCellValue('AM' . $indexSheet, $ud->kyc_pajak->npwp);
                    $sheet->setCellValue('AN' . $indexSheet, $ud->kyc_pajak->tgl_kadaluwarsa_npwp);
                    $sheet->setCellValue('AO' . $indexSheet, $ud->kyc_pajak->rekening_efek);
                    $sheet->setCellValue('AP' . $indexSheet, $ud->kyc_pajak->nomor_sid);
                    $sheet->setCellValue('AQ' . $indexSheet, $ud->kyc_pajak->tgl_registrasi_rekening_efek);

                    $sheet->setCellValue('AR' . $indexSheet, $ud->kyc_pekerjaan->pekerjaan);
                    $sheet->setCellValue('AS' . $indexSheet, $ud->kyc_pekerjaan->total_aset);
                    $sheet->setCellValue('AT' . $indexSheet, $ud->kyc_pekerjaan->sumber_dana);
                    $sheet->setCellValue('AU' . $indexSheet, $ud->kyc_pekerjaan->motivasi_investasi);


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
                $sheet->getColumnDimension('P')->setAutoSize(true);
                $sheet->getColumnDimension('Q')->setAutoSize(true);
                $sheet->getColumnDimension('R')->setAutoSize(true);
                $sheet->getColumnDimension('S')->setAutoSize(true);
                $sheet->getColumnDimension('T')->setAutoSize(true);
                $sheet->getColumnDimension('U')->setAutoSize(true);
                $sheet->getColumnDimension('V')->setAutoSize(true);
                $sheet->getColumnDimension('W')->setAutoSize(true);
                $sheet->getColumnDimension('X')->setAutoSize(true);
                $sheet->getColumnDimension('Y')->setAutoSize(true);
                $sheet->getColumnDimension('Z')->setAutoSize(true);
                $sheet->getColumnDimension('AA')->setAutoSize(true);
                $sheet->getColumnDimension('AB')->setAutoSize(true);
                $sheet->getColumnDimension('AC')->setAutoSize(true);
                $sheet->getColumnDimension('AD')->setAutoSize(true);
                $sheet->getColumnDimension('AE')->setAutoSize(true);
                $sheet->getColumnDimension('AF')->setAutoSize(true);
                $sheet->getColumnDimension('AG')->setAutoSize(true);
                $sheet->getColumnDimension('AH')->setAutoSize(true);
                $sheet->getColumnDimension('AI')->setAutoSize(true);
                $sheet->getColumnDimension('AJ')->setAutoSize(true);
                $sheet->getColumnDimension('AK')->setAutoSize(true);
                $sheet->getColumnDimension('AL')->setAutoSize(true);
                $sheet->getColumnDimension('AM')->setAutoSize(true);
                $sheet->getColumnDimension('AN')->setAutoSize(true);
                $sheet->getColumnDimension('AO')->setAutoSize(true);
                $sheet->getColumnDimension('AP')->setAutoSize(true);
                $sheet->getColumnDimension('AQ')->setAutoSize(true);
                $sheet->getColumnDimension('AR')->setAutoSize(true);
                $sheet->getColumnDimension('AS')->setAutoSize(true);
                $sheet->getColumnDimension('AT')->setAutoSize(true);
                $sheet->getColumnDimension('AU')->setAutoSize(true);


                $file_name = 'user_custodian_' . date('Y-m-d', time()) . '-' . time() . '.xlsx';

                $writer = new Xlsx($spreadsheet);
                $writer->save(public_path('custodian/' . $file_name));

                $custodian_model = new CustodianModel();

                $custodian_model::insert([
                    'file_name' => $file_name,
                    'file_path' => 'custodian/' . $file_name,
                    'type' => 'user',
                    'status' => 'active',
                    'time_created' => time(),
                    'last_update' => time()
                ]);

                $rules  = array(
                    "file_name" => "required",
                    "file_path" => "required",
                    "type" => "required",
                    "time_created" => "required",
                    "last_update" => "required"
                );

                // $this->validate($request, $rules);


                if (true) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah custodian registran!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah custodian registran']);
                }
                return redirect(url('custodian/user'));
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

    public function indexSaham(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view-purchase'])) {
                $default    = array(
                    "short"     => "time_created",
                    "shortmode" => "desc"
                );

                $shorter = array(
                    "file_name" => "Nama File",
                    "time_created" => "Tanggal Dibuat",
                    "last_update" => "Tanggal Diupdate",
                );

                $page       = $request->input("page");
                $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
                $keyword    = $request->input("keyword");
                $short      = ($request->input("short") != "") ? $request->input("short") : $default['short'];
                $shortmode  = ($request->input("shortmode") != "") ? $request->input("shortmode") : $default['shortmode'];
                $str        = ($page != "") ? (($page - 1) * $limit) : 0;
                $data       = $this->custodian_model->getCustodianSaham($str, $limit, $keyword, $short, $shortmode);

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
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
                );


                $view     = View::make("backend.master.custodian.saham.index", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " |  Custodian Data",
                    "description"   => $webname . " |  Custodian Data",
                    "keywords"      => $webname . " |  Custodian Data"
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

    public function createSaham(Request $request)
    {
        $login    = Session::get("user");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create-purchase'])) {

                $data = array(
                    "time_created"      => time(),
                    "last_update"       => time(),
                );

                // $userdata = $this->user_model::where([['is_kyc_stored', 1], ['is_exported', 0]])->get();
                $sahamdata = $this->saham_model->getCustodianSaham();

                if(count($sahamdata) == 0) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-warning', 'message' => 'KYC Saham sudah terexport semua']);

                    return redirect('custodian/purchase');
                }

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A1', 'Saham custodian ' . date('Y-m-d', time()));
                // make judul sheet
                $sheet->setCellValue('A2', 'No.')->getStyle('A2')->getFont()->setBold(true);
                $sheet->setCellValue('B2', 'Nama Pemilik')->getStyle('B2')->getFont()->setBold(true);
                $sheet->setCellValue('C2', 'Email')->getStyle('C2')->getFont()->setBold(true);
                $sheet->setCellValue('D2', 'Campaign Saham')->getStyle('D2')->getFont()->setBold(true);
                $sheet->setCellValue('E2', 'Nilai Beli')->getStyle('E2')->getFont()->setBold(true);
                $sheet->setCellValue('F2', 'Banyak Lembar')->getStyle('F2')->getFont()->setBold(true);
                $sheet->setCellValue('G2', 'Waktu Beli')->getStyle('G2')->getFont()->setBold(true);

                $indexSheet = 3;
                $indexNumber = 1;
                foreach ($sahamdata as $sd) {
                    $sheet->setCellValue('A' . $indexSheet, $indexNumber);
                    $sheet->setCellValue('B' . $indexSheet, $sd->first_name);
                    $sheet->setCellValue('C' . $indexSheet, $sd->email);
                    $sheet->setCellValue('D' . $indexSheet, $sd->campaign_title);
                    $sheet->setCellValue('E' . $indexSheet, $sd->nilai_beli);
                    $sheet->setCellValue('F' . $indexSheet, $sd->quantity);
                    $sheet->setCellValue('G' . $indexSheet, date('Y-m-d h:i:s', $sd->time_created));
                    $indexSheet++;
                    $indexNumber++;
                }

                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);


                $file_name = 'saham_custodian' . date('Y-m-d', time()) . '-' . time() . '.xlsx';

                $writer = new Xlsx($spreadsheet);
                $writer->save(public_path('custodian/' . $file_name));

                $custodian_model = new CustodianModel();

                $custodian_model::insert([
                    'file_name' => $file_name,
                    'file_path' => 'custodian/' . $file_name,
                    'type' => 'saham',
                    'status' => 'active',
                    'time_created' => time(),
                    'last_update' => time()
                ]);

                $rules  = array(
                    "file_name" => "required",
                    "file_path" => "required",
                    "type" => "required",
                    "time_created" => "required",
                    "last_update" => "required"
                );

                // $this->validate($request, $rules);


                if (true) {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah custodian saham!']);
                } else {
                    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah custodian saham']);
                }
                return redirect(url('custodian/purchase'));
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
