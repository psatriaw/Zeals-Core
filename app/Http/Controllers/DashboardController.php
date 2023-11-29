<?php

namespace App\Http\Controllers;

//===============================
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\IGWHelper;
use Carbon\carbon;

use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;

use App\Http\Models\TransactionModel;
use App\Http\Models\CampaignTrackerModel;
use App\Http\Models\CampaignModel;
use App\Http\Models\WilayahModel;
use App\Http\Models\CampaignJoinModel;

use App\Http\Models\OrderModel;
use App\Http\Models\PurchaseModel;
use App\Http\Models\MaterialMovementModel;
use App\Http\Models\MaterialModel;
use App\Http\Models\ProductionModel;
use App\Http\Models\dashboard_bcsModel;
use App\Http\Models\LikeModel;
use App\Http\Models\RekeningDanaTransaksiModel;
use App\Http\Models\UserModel;
use App\Http\Models\VoucherModel;
use App\Http\Models\DepartmentModel;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Session;
use stdClass;

//===============================

class DashboardController extends Controller
{
    private $setting_model;
    private $previlege_model;
    private $helper;
    private $transaction_model;
    private $cart_pemenuhan_model;
    private $product_reject_model;
    private $cart_model;
    private $purchse_model;
    private $material_movement_model;
    private $material_model;
    private $wilayah_model;

    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $this->transaction_model           = new TransactionModel();
        $this->campaign_tracker_model      = new CampaignTrackerModel();
        $this->user_model                  = new UserModel();
        $this->campaign_model              = new CampaignModel();
        $this->wilayah_model               = new WilayahModel();
        $this->campaign_join_model         = new CampaignJoinModel();
        $this->voucher_model               = new VoucherModel();
        $this->department_model            = new DepartmentModel();

        $this->data_bulan = array(
          "1"   => "January",
          "2"   => "February",
          "3"   => "March",
          "4"   => "April",
          "5"   => "May",
          "6"   => "June",
          "7"   => "July",
          "8"   => "August",
          "9"   => "September",
          "10"   => "October",
          "11"   => "November",
          "12"   => "December",
        );
    }


    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }


    public function dashboardIndex(Request $request)
    {

        if ($request->all() == null) {
            $totalTransaksi = $this->rekeningdanatransaksi_model->where('status', 'paid')->count();
            $totalPengguna = $this->user_model->where('status', 'active')->count();
            $totalPencairan = $this->rekeningdanatransaksi_model->where([['status', 'paid'], ['trx_type', 'withdrawal']])->count();
            $totalCampaign = $this->campaign_model->where('status', 'active')->count();
            $totalDukungan = $this->dukungan_model->where('status', 'active')->count();
            $totalComment = $this->comment_model->where('status', 'active')->count();

            $dateStart = strtotime(date('Y-m-d', strtotime("-1 months")));

            $dataChart = $this->rekeningdanatransaksi_model->getDashboardChart($dateStart, null);

            return response([
                'status' => 'success',
                'response' => [
                    'total_transaksi' => $totalTransaksi,
                    'total_pengguna' => $totalPengguna,
                    'pencairan' => $totalPencairan,
                    'campaign' => $totalCampaign,
                    'dukungan' => $totalDukungan,
                    'komentar' => $totalComment,
                    'chart' => $dataChart
                ],
            ]);
        } else {


            $typeSearch = $request->type;
            $chartSearch = $request->chart;
            $datesSearch = $request->dates;

            if ($typeSearch == 'weekly') {
                $dates      = (@$_GET['dates'] == "") ? date("Y/m/d") . "-" . date("Y/m/d") : @$_GET['dates'];
                $dates      = explode("-", $dates);

                $dataIntDates = [
                    'time1' => strtotime($dates[0]),
                    'time2' => strtotime($dates[1]) + 86399
                ];

                $amountWeek = (int)ceil((($dataIntDates['time2'] - $dataIntDates['time1']) / 86400) / 7);

                $dateLabel = [];
                $dateTemp = $dataIntDates['time2'];

                for ($i = 0; $i < $amountWeek; $i++) {
                    $stringdate = date('Y-m-d', $dateTemp - (86400 * 6)) . '-' . date('Y-m-d', $dateTemp);
                    $dateTemp -= (86400 * 7);
                    array_push($dateLabel, $stringdate);
                }


                $dataTranskasi = $this->rekeningdanatransaksi_model::where('status', 'paid')
                    ->where('time_created', '>=', $dataIntDates['time1'])
                    ->where('time_created', '<=', $dataIntDates['time2'])
                    ->get();

                $dataQuery = $this->rekeningdanatransaksi_model->getDashboardChartWeekly($dataIntDates);


                $totalTransaksi = $this->rekeningdanatransaksi_model->where([['status', 'paid'], ['time_created', '>=', $dateTemp], ['time_created', '<=', $dataIntDates['time2']]])->count();
                $totalPengguna = $this->user_model->where([['status', 'active'], ['date_created', '>=', $dateTemp], ['date_created', '<=', $dataIntDates['time2']]])->count();
                $totalPencairan = $this->rekeningdanatransaksi_model->where([['status', 'paid'], ['trx_type', 'withdrawal'], ['time_created', '>=', $dateTemp], ['time_created', '<=', $dataIntDates['time2']]])->count();
                $totalCampaign = $this->campaign_model->where([['status', 'active'], ['time_created', '>=', $dateTemp], ['time_created', '<=', $dataIntDates['time2']]])->count();
                $totalDukungan = $this->dukungan_model->where([['status', 'active'], ['time_created', '>=', $dateTemp], ['time_created', '<=', $dataIntDates['time2']]])->count();
                $totalComment = $this->comment_model->where([['status', 'active'], ['time_created', '>=', $dateTemp], ['time_created', '<=', $dataIntDates['time2']]])->count();


                return response([
                    'status' => 'success',
                    'response' => [
                        'total_transaksi' => $totalTransaksi,
                        'total_pengguna' => $totalPengguna,
                        'pencairan' => $totalPencairan,
                        'campaign' => $totalCampaign,
                        'dukungan' => $totalDukungan,
                        'komentar' => $totalComment,
                        'chart' => $dataQuery
                    ],
                ]);
            } else if ($typeSearch == 'daily') {
                $datesSplit = explode("-", $datesSearch);
                $dateTime = array();

                foreach ($datesSplit as $ds) {
                    $temp = strtotime($ds);
                    array_push($dateTime, $temp);
                }

                $dateTime[1] = $dateTime[1] + 86399;

                // data chart
                $dataChart = $this->rekeningdanatransaksi_model->getDashboardChart($dateTime[0], $dateTime[1]);

                $totalTransaksi = $this->rekeningdanatransaksi_model->where([['status', 'paid'], ['time_created', '>=', $dateTime[0]], ['time_created', '<=', $dateTime[1]]])->count();
                $totalPengguna = $this->user_model->where([['status', 'active'], ['date_created', '>=', $dateTime[0]], ['date_created', '<=', $dateTime[1]]])->count();
                $totalPencairan = $this->rekeningdanatransaksi_model->where([['status', 'paid'], ['trx_type', 'withdrawal'], ['time_created', '>=', $dateTime[0]], ['time_created', '<=', $dateTime[1]]])->count();
                $totalCampaign = $this->campaign_model->where([['status', 'active'], ['time_created', '>=', $dateTime[0]], ['time_created', '<=', $dateTime[1]]])->count();
                $totalDukungan = $this->dukungan_model->where([['status', 'active'], ['time_created', '>=', $dateTime[0]], ['time_created', '<=', $dateTime[1]]])->count();
                $totalComment = $this->comment_model->where([['status', 'active'], ['time_created', '>=', $dateTime[0]], ['time_created', '<=', $dateTime[1]]])->count();

                return response([
                    'status' => 'success',
                    'response' => [
                        'total_transaksi' => $totalTransaksi,
                        'total_pengguna' => $totalPengguna,
                        'pencairan' => $totalPencairan,
                        'campaign' => $totalCampaign,
                        'dukungan' => $totalDukungan,
                        'komentar' => $totalComment,
                        'chart' => $dataChart
                    ],
                    'dates' => $dateTime,
                    'params' => [
                        'type' => $typeSearch,
                        'chart' => $chartSearch,
                        'dates' => $datesSplit
                    ]
                ]);
            } else if ($typeSearch == 'monthly') {
                $dates      = (@$_GET['dates'] == "") ? date("Y/m/d") . "-" . date("Y/m/d") : @$_GET['dates'];
                $dates      = explode("-", $dates);

                $dataIntDates = [
                    'time1' => strtotime($dates[0]),
                    'time2' => strtotime($dates[1]) + 86399
                ];


                $dataTranskasi = $this->rekeningdanatransaksi_model::where('status', 'paid')
                    ->where('time_created', '>=', $dataIntDates['time1'])
                    ->where('time_created', '<=', $dataIntDates['time2'])
                    ->get();

                $dataQuery = $this->rekeningdanatransaksi_model->getDashboardChartMonthly($dataIntDates);


                $totalTransaksi = $this->rekeningdanatransaksi_model->where([['status', 'paid'], ['time_created', '>=', $dataIntDates['time1']], ['time_created', '<=', $dataIntDates['time2']]])->count();
                $totalPengguna = $this->user_model->where([['status', 'active'], ['date_created', '>=', $dataIntDates['time1']], ['date_created', '<=', $dataIntDates['time2']]])->count();
                $totalPencairan = $this->rekeningdanatransaksi_model->where([['status', 'paid'], ['trx_type', 'withdrawal'], ['time_created', '>=', $dataIntDates['time1']], ['time_created', '<=', $dataIntDates['time2']]])->count();
                $totalCampaign = $this->campaign_model->where([['status', 'active'], ['time_created', '>=', $dataIntDates['time1']], ['time_created', '<=', $dataIntDates['time2']]])->count();
                $totalDukungan = $this->dukungan_model->where([['status', 'active'], ['time_created', '>=', $dataIntDates['time1']], ['time_created', '<=', $dataIntDates['time2']]])->count();
                $totalComment = $this->comment_model->where([['status', 'active'], ['time_created', '>=', $dataIntDates['time1']], ['time_created', '<=', $dataIntDates['time2']]])->count();


                return response([
                    'status' => 'success',
                    'response' => [
                        'total_transaksi' => $totalTransaksi,
                        'total_pengguna' => $totalPengguna,
                        'pencairan' => $totalPencairan,
                        'campaign' => $totalCampaign,
                        'dukungan' => $totalDukungan,
                        'komentar' => $totalComment,
                        'chart' => $dataQuery
                    ],
                ]);
            }
        }

        $typeSearch = $request->type;
        $chartSearch = $request->chart;
        $datesSearch = $request->dates;

        $totalTransaksi = $this->rekeningdanatransaksi_model->where('status', 'paid')->count();
        $totalPengguna = $this->user_model->where('status', 'active')->count();
        $totalPencairan = $this->rekeningdanatransaksi_model->where([['status', 'paid'], ['trx_type', 'withdrawal']])->count();
        $totalCampaign = $this->campaign_model->where('status', 'active')->count();
        $totalDukungan = $this->dukungan_model->where('status', 'active')->count();
        $totalComment = $this->comment_model->where('status', 'active')->count();

        $totalDates = (@$_GET['dates'] == "") ? date("Y/m/d") . "-" . date("Y/m/d") : @$_GET['dates'];
        $datesArray = explode("-", $totalDates);


        // return response([
        //     'status' => 'success',
        //     'response' => [
        //         'total_transaksi' => $totalTransaksi,
        //         'total_pengguna' => $totalPengguna,
        //         'pencairan' => $totalPencairan,
        //         'campaign' => $totalCampaign,
        //         'dukungan' => $totalDukungan,
        //         'komentar' => $totalComment,
        //         'dates' => $totalDates
        //     ],
        //     'time' => $dateNow
        // ]);
    }


    public function index(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if($login->id_brand!=""){
              return redirect(url("master/campaign"));
            }
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, "dashboard-view")) {
                $data       = $this->setting_model->getData();
                $total_produksi_tertinggi = 0;

                $dates      = (@$_GET['dates'] == "") ? date("Y/m/d") . "-" . date("Y/m/d") : @$_GET['dates'];


                $dates      = explode("-", $dates);
                $start      = @$dates[0];
                $end        = @$dates[1];

                redirect(url("master/campaign"));

                $statistic_visit        = 0;//$this->campaign_tracker_model->getTotalTracker('visit',"","");
                $statistic_read         = 0;//$this->campaign_tracker_model->getTotalTracker('read',"","");
                $statistic_action       = 0;//$this->campaign_tracker_model->getTotalTracker('action',"","");
                $statistic_acquisition  = 0;//$this->campaign_tracker_model->getTotalTracker('acquisition',"","");

                $tahundata = date("Y");
                for($bulan=1;$bulan<=(int)date("m");$bulan++){
                  $bulans[]             = $this->data_bulan[$bulan];
                  $rangemin             = strtotime("01-".$bulan."-".$tahundata." 00:00:00");
                  $rangemax             = strtotime("31-".$bulan."-".$tahundata." 23:59:59");

                  $databulan['visit'][]       = 0;//$this->campaign_tracker_model->getTransactionTotalInRangeReal("visit",$rangemin,$rangemax);
                  $databulan['read'][]        = 0;//$this->campaign_tracker_model->getTransactionTotalInRangeReal("read",$rangemin,$rangemax);
                  $databulan['action'][]      = 0;//$this->campaign_tracker_model->getTransactionTotalInRangeReal("action",$rangemin,$rangemax);
                  $databulan['acquisition'][] = 0;//$this->campaign_tracker_model->getTransactionTotalInRangeReal("acquisition",$rangemin,$rangemax);
                  $databulan['reach'][]       = 0;//$this->campaign_tracker_model->getTransactionTotalInRangeReal("initial",$rangemin,$rangemax);
                }

                $datacontent = array(
                    "config"        => array("main_url" => "dashboard/view"),
                    "dates"          => @$_GET['dates'],
                    "chart"          => (@$_GET['chart'] == "") ? "bar" : @$_GET['chart'],
                    "login"          => $login,
                    "previlege"      => $this->previlege_model,
                    "data"           => $data,
                    //data baru
                    "logs_group"      => [],//$this->campaign_tracker_model->getLast1000LogsGroup(),
                    "logs"            => [],//$this->campaign_tracker_model->getLast1000Logs(),

                    "campaigns"       => $this->campaign_model->getListLast10Campaigns(),
                    "running_project" => $this->campaign_model->getTotalRunningCampaign()->get()->count(),
                    "new_comer"       => $this->user_model->countNewComer(strtotime(date("Y-m-01"))),
                    "estimated_budget"  => $this->campaign_tracker_model->sumTotalEstimation(),
                    "in_budget_range"   => $this->campaign_model->getTotalBudget(strtotime(date("Y-m-01"))),
                    "running_budget"    => 1,//$this->campaign_tracker_model->sumTotalEarning(),
                    "in_budget"         => 1,//($this->campaign_model->getTotalBudget()==0)?1:$this->campaign_model->getTotalBudget(),
                    "total_income"      => 1,//$this->campaign_tracker_model->getTotalIncome(),
                    "all_data_tracker"  => 1,//$this->campaign_tracker_model->getTotalTrackingData(date("01-01-Y")),
                    "all_reach"         => 1,//$this->campaign_tracker_model->getTransactionTotalInRangeReal("initial"),
                    "tahun_data"        => date("Y"),
                    "data_map"          => $this->wilayah_model->getWilayahAndDataTracking(),
                    "data_kota"         => $this->campaign_tracker_model->getDataTrackingByCity(),
                    "avg_joined"        => 1,//$this->campaign_join_model->getAVGJoinedProject(),
                    "bulan_on_year"       => $bulans,
                    "data_bulan_on_year"  => $databulan
                );

                if($statistic_visit>0){

                  $datacontent['effectiveness']['read'] = array(
                    "total"       => $statistic_read,
                    "percent"     => ($statistic_read*100)/$statistic_visit
                  );

                  $datacontent['effectiveness']['action'] = array(
                    "total"       => $statistic_action,
                    "percent"     => ($statistic_action*100)/$statistic_visit
                  );

                  $datacontent['effectiveness']['acquisition'] = array(
                    "total"       => $statistic_acquisition,
                    "percent"     => ($statistic_acquisition*100)/$statistic_visit
                  );
                }else{
                    $datacontent['effectiveness']['read']['total']      = 0;
                    $datacontent['effectiveness']['read']['percent']    = 0;

                    $datacontent['effectiveness']['action']['total']      = 0;
                    $datacontent['effectiveness']['action']['percent']    = 0;

                    $datacontent['effectiveness']['acquisition']['total']      = 0;
                    $datacontent['effectiveness']['acquisition']['percent']    = 0;
                }


                $view     = View::make("backend.admin.dashboard.main", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Dashboard Administrator",
                    "description"   => $webname . " | Dashboard Administrator",
                    "keywords"      => $webname . " | Dashboard Administrator"
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
                $body = "backend.body_backend";
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

    public function transaction(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, "dashboard-transaction")) {
                $data       = $this->setting_model->getData();
                $total_produksi_tertinggi = 0;

                $dates      = (@$_GET['dates'] == "") ? date("Y/m/d") . "-" . date("Y/m/d") : @$_GET['dates'];
                //print $dates;

                $dates      = explode("-", $dates);
                $start      = @$dates[0];
                $end        = @$dates[1];
                //print_r($dates);
                //exit();
                $type       = @$_GET['type'];
                $label      = "";
                $data_date_14_hari = array();
                $data_produksi_14_hari = array();
                $data_permintaan_14_hari = array();

                $label    = "dari " . $dates[0] . " - " . $dates[1];
                $start    = str_replace(array("/", " "), array("-", ""), $start);
                $end      = str_replace(array("/", " "), array("-", ""), $end);

                switch ($type) {
                    default:
                        $ranges   = strtotime($end)  - strtotime($start);
                        $range_inc = (int)($ranges / 86400);

                        for ($i = 0; $i <= $range_inc; $i++) {

                          $datatime[$i]         = date("Y-m-d", strtotime($end) - (86400 * ($range_inc - $i)));
                          $rangemin             = strtotime($datatime[$i]." 00:00:00");//strtotime("01-".$bulan."-".$tahundata." 00:00:00");
                          $rangemax             = strtotime($datatime[$i]." 23:59:59");//strtotime("31-".$bulan."-".$tahundata." 23:59:59");

                          $datatrack['visit'][]       = $this->campaign_tracker_model->getTransactionTotalInRangeReal("visit",$rangemin,$rangemax);
                          $datatrack['read'][]        = $this->campaign_tracker_model->getTransactionTotalInRangeReal("read",$rangemin,$rangemax);
                          $datatrack['action'][]      = $this->campaign_tracker_model->getTransactionTotalInRangeReal("action",$rangemin,$rangemax) + $this->voucher_model->getTotalVoucherByTime('active',$rangemin,$rangemax);
                          $datatrack['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRangeReal("acquisition",$rangemin,$rangemax) + $this->voucher_model->getTotalVoucherByTime('used',$rangemin,$rangemax);
                          $datatrack['reach'][]       = $this->campaign_tracker_model->getTransactionTotalInRangeReal("initial",$rangemin,$rangemax);

                        }

                        break;

                    case "all":
                        $start = "";
                        $end   = "";
                        $datatime=[];
                        $datatrack['visit'][]=0;
                        $datatrack['read'][]=0;
                        $datatrack['action'][]=0;
                        $datatrack['acquisition'][]=0;
                        $datatrack['reach'][]=0;
                        break;

                    case "weekly":
                        $ranges   = strtotime($end)+1  - strtotime($start);
                        $range_inc = (int)($ranges / (86400*7));  //weekly range


                        for ($i = 0; $i < $range_inc; $i++) {
                            $data_date_14_hari[$i]        = date("Y-m-d", strtotime($end) - (86400 * ($range_inc - $i)));

                            $datatime[$i]         = date("Y-m-d", strtotime($start) + ((86400*7) * $i))." - ".date("Y-m-d", strtotime($start) + ((86400*7) * $i)+(86400*6));
                            $rangemin             = strtotime(date("Y-m-d", strtotime($start) + ((86400*7) * $i))." 00:00:00");
                            $rangemax             = strtotime(date("Y-m-d", strtotime($start) + ((86400*7) * $i)+(86400*6))." 23:59:59");

                            $datatrack['visit'][]       = $this->campaign_tracker_model->getTransactionTotalInRangeReal("visit",$rangemin,$rangemax);
                            $datatrack['read'][]        = $this->campaign_tracker_model->getTransactionTotalInRangeReal("read",$rangemin,$rangemax);
                            $datatrack['action'][]      = $this->campaign_tracker_model->getTransactionTotalInRangeReal("action",$rangemin,$rangemax)  + $this->voucher_model->getTotalVoucherByTime('active',$rangemin,$rangemax);
                            $datatrack['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRangeReal("acquisition",$rangemin,$rangemax)  + $this->voucher_model->getTotalVoucherByTime('used',$rangemin,$rangemax);
                            $datatrack['reach'][]       = $this->campaign_tracker_model->getTransactionTotalInRangeReal("initial",$rangemin,$rangemax);
                        }

                        if($ranges%(86400*7)!=0){
                            $datatime[$range_inc]       = date("Y-m-d", strtotime($start) + ((86400*7) * $range_inc))." - ".date("Y-m-d", strtotime($end));
                            $rangemin                   = strtotime(date("Y-m-d", strtotime($start) + ((86400*7) * $range_inc))." 00:00:00");
                            $rangemax                   = strtotime(date("Y-m-d", strtotime($end))." 23:59:59");

                            $datatrack['visit'][]       = $this->campaign_tracker_model->getTransactionTotalInRangeReal("visit",$rangemin,$rangemax);
                            $datatrack['read'][]        = $this->campaign_tracker_model->getTransactionTotalInRangeReal("read",$rangemin,$rangemax);
                            $datatrack['action'][]      = $this->campaign_tracker_model->getTransactionTotalInRangeReal("action",$rangemin,$rangemax)  + $this->voucher_model->getTotalVoucherByTime('active',$rangemin,$rangemax);
                            $datatrack['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRangeReal("acquisition",$rangemin,$rangemax)  + $this->voucher_model->getTotalVoucherByTime('used',$rangemin,$rangemax);
                            $datatrack['reach'][]       = $this->campaign_tracker_model->getTransactionTotalInRangeReal("initial",$rangemin,$rangemax);
                        }
                        break;

                    case "monthly":
                        $label    = "dari " . $dates[0] . " Hingga " . $dates[1];
                        $start    = str_replace(array("/", " "), array("-", ""), $start);
                        $end      = str_replace(array("/", " "), array("-", ""), $end);

                        $start_date = date("Y-m-d", strtotime($start));
                        $end_date   = date("Y-m-d", strtotime($end));

                        $ranges   = strtotime($end)  - strtotime($start);
                        $range_inc = (int)($ranges / (86400));
                        $c_month  = "";
                        $month    = array();
                        for ($i = 0; $i <= $range_inc; $i++) {
                            if ($i == 0) {
                                $c_month = date("m", strtotime($start) + ($i * 86400));
                            }

                            $ada = FALSE;
                            foreach ($month as $key => $value) {
                                //print $value." ".date("M",strtotime($start) + ($i*86400))."<br>";
                                if ($value == date("m", strtotime($start) + ($i * 86400))) {
                                    $ada = TRUE;
                                    break;
                                }
                            }
                            if (!$ada) {
                                $month[] = date("m", strtotime($start) + ($i * 86400));
                            }
                            $c_months = date("m", strtotime($start) + ($i * 86400));
                        }

                        $i = 0;
                        foreach ($month as $key => $value) {
                            if ($key == 0) {
                                $date1 = date("Y-" . $value . "-" . $start_date);
                            } else {
                                $date1 = date("Y-" . $value . "-01");
                            }

                            if (sizeof($month) > $key) {
                                $date2 = date("Y-" . $value . "-t");
                            } else {
                                $date2 = date("Y-" . $value . "-" . $end_date);
                            }

                            //print  $date1." ".$date2;

                            $data_date_14_hari[$i]        = date("d M Y", strtotime($date1)) . " - " . date("d M Y", strtotime($date2));
                            $data_produksi_14_hari[$i]    = 0; //$this->cart_pemenuhan_model->getDataProductionAtThatTime($date1, $date2);
                            $data_permintaan_14_hari[$i]  = 0; //$this->cart_pemenuhan_model->getDataPermintaanAtThatTime($date1, $date2);
                            $data_reject_14_hari[$i]      = 0; //$this->product_reject_model->getDataReject($date1, $date2);
                            $data_hpp_14_hari[$i]         = 0; //$this->transaction_model->getTransactionCostByDate($date1, $date2);

                            if ($total_produksi_tertinggi < $data_produksi_14_hari[$i]) {
                                $total_produksi_tertinggi = $data_produksi_14_hari[$i];
                                $tgl_produksi_tertinggi   = $data_date_14_hari[$i];
                            }
                            $i++;
                        }

                        $dataIntDates = [
                            'time1' => strtotime($dates[0]),
                            'time2' => strtotime($dates[1]) + 86399
                        ];

                        $dataTranskasi = $this->rekeningdanatransaksi_model::where('status', 'paid')
                            ->where('time_created', '>=', $dataIntDates['time1'])
                            ->where('time_created', '<=', $dataIntDates['time2'])
                            ->get();

                        $jumlah_tanggal            = sizeof($month) . " bulan ";
                        $total_produksi            = 0; //$this->cart_pemenuhan_model->getAllDataProduksi();
                        $total_produksi_hari_ini   = 0; //$this->cart_pemenuhan_model->getDataProductionAtThatTime($start, $end);
                        $total_reject_hari_ini     = 0; //$this->product_reject_model->getDataReject($start, $end);
                        $hpp_hari_ini              = 0; //$this->transaction_model->getTransactionCostByDate($start, $end);
                        $total_permintaan_hari_ini = 0; //$this->cart_pemenuhan_model->getDataPermintaanAtThatTime($start, $end);
                        break;
                }

                $statistic['visit']       = $this->campaign_tracker_model->getTransactionTotalInRangeReal("visit",strtotime($start),strtotime($end." 23:59:59"));
                $statistic['read']        = $this->campaign_tracker_model->getTransactionTotalInRangeReal("read",strtotime($start),strtotime($end." 23:59:59"));
                $statistic['action']      = $this->campaign_tracker_model->getTransactionTotalInRangeReal("action",strtotime($start),strtotime($end." 23:59:59"));
                $statistic['voucher_request'] = $this->voucher_model->getTotalVoucherByTime('active',strtotime($start),strtotime($end." 23:59:59"));
                $statistic['voucher_usage'] = $this->voucher_model->getTotalVoucherByTime('used',strtotime($start),strtotime($end." 23:59:59"));
                $statistic['acquisition'] = $this->campaign_tracker_model->getTransactionTotalInRangeReal("acquisition",strtotime($start),strtotime($end." 23:59:59"));
                $statistic['reach']       = $this->campaign_tracker_model->getTransactionTotalInRangeReal("initial",strtotime($start),strtotime($end." 23:59:59"));

                $datacontent = array(
                    "config"        => array("main_url" => "dashboard/view"),
                    "label_type"     => $label,
                    "dates"          => @$_GET['dates'],
                    "type"           => @$_GET['type'],
                    "chart"          => (@$_GET['chart'] == "") ? "line" : @$_GET['chart'],
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "data"           => $data,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    //data baru
                    "logs_group"      => $this->campaign_tracker_model->getLast1000LogsGroup(),
                    "logs"            => $this->campaign_tracker_model->getLast1000Logs(),

                    "campaigns"       => $this->campaign_model->getListLast10Campaigns(),
                    "running_project" => $this->campaign_model->getTotalRunningCampaign()->get()->count(),
                    "new_comer"       => $this->user_model->countNewComer(strtotime(date("Y-m-01"))),
                    "estimated_budget"  => $this->campaign_tracker_model->sumTotalEstimation(),
                    "in_budget_range"   => $this->campaign_model->getTotalBudget(strtotime(date("Y-m-01"))),
                    "running_budget"    => $this->campaign_tracker_model->sumTotalEarning(),
                    "in_budget"         => ($this->campaign_model->getTotalBudget()==0)?1:$this->campaign_model->getTotalBudget(),
                    "total_income"      => $this->campaign_tracker_model->getTotalIncome(),
                    "all_data_tracker"  => $this->campaign_tracker_model->getTotalTrackingData(date("01-01-Y")),
                    "tahun_data"        => date("Y"),
                    "data_map"          => $this->wilayah_model->getWilayahAndDataTracking(),
                    "data_kota"         => $this->campaign_tracker_model->getDataTrackingByCity(),
                    "avg_joined"        => $this->campaign_join_model->getAVGJoinedProject(),
                    "bulan_on_year"       => $datatime,
                    "data_bulan_on_year"  => $datatrack,
                    "effectiveness"     => $statistic
                );

                $view     = View::make("backend.admin.dashboard.transaction", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Dashboard Administrator",
                    "description"   => $webname . " | Dashboard Administrator",
                    "keywords"      => $webname . " | Dashboard Administrator"
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
                $body = "backend.body_backend";
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

    public function affiliator(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, "dashboard-affiliator")) {
                $data       = $this->setting_model->getData();
                $total_produksi_tertinggi = 0;

                $dates      = (@$_GET['dates'] == "") ? date("Y/m/d") . "-" . date("Y/m/d") : @$_GET['dates'];

                $dates      = explode("-", $dates);
                $start      = @$dates[0];
                $end        = @$dates[1];
                
                $type       = @$_GET['type'];
                $label      = "";

                $data_date_14_hari        = array();
                $data_produksi_14_hari    = array();
                $data_permintaan_14_hari  = array();

                $label    = "dari " . $dates[0] . " - " . $dates[1];
                $start    = str_replace(array("/", " "), array("-", ""), $start);
                $end      = str_replace(array("/", " "), array("-", ""), $end);

                $super_qr = $this->department_model->getDepartmentByCode("SUPERQRAFF");
                $organic  = $this->department_model->getDepartmentByCode("affiliator");

                switch ($type) {
                    default:
                        $ranges   = strtotime($end)  - strtotime($start);
                        $range_inc = (int)($ranges / 86400);

                        for ($i = 0; $i <= $range_inc; $i++) {

                          $datatime[$i]         = date("Y-m-d", strtotime($end) - (86400 * ($range_inc - $i)));
                          $rangemin             = $datatime[$i]." 00:00:00";//strtotime("01-".$bulan."-".$tahundata." 00:00:00");
                          $rangemax             = $datatime[$i]." 23:59:59";//strtotime("31-".$bulan."-".$tahundata." 23:59:59");

                          $datatrack['super_qr'][]       = $this->user_model->getUserByCodeAndStatus("active",$rangemin,$rangemax,$super_qr,"");
                          $datatrack['new_reg'][]        = $this->user_model->getUserByCodeAndStatus("",$rangemin,$rangemax);
                          $datatrack['activated'][]      = $this->user_model->getUserByCodeAndStatus("active",$rangemin,$rangemax);
                          $datatrack['organic'][]        = $this->user_model->getUserByCodeAndStatus('active',$rangemin,$rangemax,$organic,"");
                          $datatrack['referral'][]       = $this->user_model->getUserByCodeAndStatus('active',$rangemin,$rangemax,"",$organic);

                        }

                    break;

                    case "weekly":
                        $ranges   = strtotime($end)+1  - strtotime($start);
                        $range_inc = (int)($ranges / (86400*7));  //weekly range


                        for ($i = 0; $i < $range_inc; $i++) {
                            $data_date_14_hari[$i]        = date("Y-m-d", strtotime($end) - (86400 * ($range_inc - $i)));

                            $datatime[$i]         = date("Y-m-d", strtotime($start) + ((86400*7) * $i))." - ".date("Y-m-d", strtotime($start) + ((86400*7) * ($i))+(86400*6));
                            $rangemin             = date("Y-m-d", strtotime($start) + ((86400*7) * $i))." 00:00:00";
                            $rangemax             = date("Y-m-d", strtotime($start) + ((86400*7) * ($i))+(86400*6))." 23:59:59";

                            $datatrack['super_qr'][]       = $this->user_model->getUserByCodeAndStatus("active",$rangemin,$rangemax,$super_qr,"");
                            $datatrack['new_reg'][]        = $this->user_model->getUserByCodeAndStatus("",$rangemin,$rangemax);
                            $datatrack['activated'][]      = $this->user_model->getUserByCodeAndStatus("active",$rangemin,$rangemax);
                            $datatrack['organic'][]        = $this->user_model->getUserByCodeAndStatus('active',$rangemin,$rangemax,$organic,"");
                            $datatrack['referral'][]       = $this->user_model->getUserByCodeAndStatus('active',$rangemin,$rangemax,"",$organic);
                        }

                        if($ranges%(86400*7)!=0){
                            $data_date_14_hari[$i]        = date("Y-m-d", strtotime($end) - (86400 * ($range_inc - $i)));

                            $datatime[$i]         = date("Y-m-d", strtotime($start) + ((86400*7) * $i))." - ".date("Y-m-d", strtotime($end));
                            $rangemin             = date("Y-m-d", strtotime($start) + ((86400*7) * $i))." 00:00:00";
                            $rangemax             = date("Y-m-d", strtotime($end))." 23:59:59";

                            $datatrack['super_qr'][]       = $this->user_model->getUserByCodeAndStatus("active",$rangemin,$rangemax,$super_qr,"");
                            $datatrack['new_reg'][]        = $this->user_model->getUserByCodeAndStatus("",$rangemin,$rangemax);
                            $datatrack['activated'][]      = $this->user_model->getUserByCodeAndStatus("active",$rangemin,$rangemax);
                            $datatrack['organic'][]        = $this->user_model->getUserByCodeAndStatus('active',$rangemin,$rangemax,$organic,"");
                            $datatrack['referral'][]       = $this->user_model->getUserByCodeAndStatus('active',$rangemin,$rangemax,"",$organic);
                        }
                        break;

                    case "monthly":

                        $b_awal   = date("m",strtotime($start));
                        $b_akhir  = date("m",strtotime($end));



                        $ranges   = strtotime($end)  - strtotime($start);
                        $range_inc = (int)($ranges / (86400));
                        $c_month  = "";
                        $month    = array();

                        for ($i = 0; $i <= $range_inc; $i++) {
                            if ($i == 0) {
                                $c_month = date("m", strtotime($start) + ($i * 86400));
                            }

                            $ada = FALSE;
                            foreach ($month as $key => $value) {
                                //print $value." ".date("M",strtotime($start) + ($i*86400))."<br>";
                                if ($value == date("m", strtotime($start) + ($i * 86400))) {
                                    $ada = TRUE;
                                    break;
                                }
                            }

                            if (!$ada) {
                                $month[] = date("m", strtotime($start) + ($i * 86400));
                            }
                            $c_months = date("m", strtotime($start) + ($i * 86400));
                        }

                        $i = 0;
                        foreach ($month as $key => $value) {
                            if ($key == 0) {
                                $date1 = date("Y-" . $value . "-" . $start_date);
                            } else {
                                $date1 = date("Y-" . $value . "-01");
                            }

                            if (sizeof($month) > $key) {
                                $date2 = date("Y-" . $value . "-t");
                            } else {
                                $date2 = date("Y-" . $value . "-" . $end_date);
                            }

                            //print  $date1." ".$date2;

                            $data_date_14_hari[$i]        = date("d M Y", strtotime($date1)) . " - " . date("d M Y", strtotime($date2));
                            $data_produksi_14_hari[$i]    = 0; //$this->cart_pemenuhan_model->getDataProductionAtThatTime($date1, $date2);
                            $data_permintaan_14_hari[$i]  = 0; //$this->cart_pemenuhan_model->getDataPermintaanAtThatTime($date1, $date2);
                            $data_reject_14_hari[$i]      = 0; //$this->product_reject_model->getDataReject($date1, $date2);
                            $data_hpp_14_hari[$i]         = 0; //$this->transaction_model->getTransactionCostByDate($date1, $date2);

                            if ($total_produksi_tertinggi < $data_produksi_14_hari[$i]) {
                                $total_produksi_tertinggi = $data_produksi_14_hari[$i];
                                $tgl_produksi_tertinggi   = $data_date_14_hari[$i];
                            }
                            $i++;
                        }

                        $dataIntDates = [
                            'time1' => strtotime($dates[0]),
                            'time2' => strtotime($dates[1]) + 86399
                        ];

                        $dataTranskasi = $this->rekeningdanatransaksi_model::where('status', 'paid')
                            ->where('time_created', '>=', $dataIntDates['time1'])
                            ->where('time_created', '<=', $dataIntDates['time2'])
                            ->get();

                        $jumlah_tanggal            = sizeof($month) . " bulan ";
                        $total_produksi            = 0; //$this->cart_pemenuhan_model->getAllDataProduksi();
                        $total_produksi_hari_ini   = 0; //$this->cart_pemenuhan_model->getDataProductionAtThatTime($start, $end);
                        $total_reject_hari_ini     = 0; //$this->product_reject_model->getDataReject($start, $end);
                        $hpp_hari_ini              = 0; //$this->transaction_model->getTransactionCostByDate($start, $end);
                        $total_permintaan_hari_ini = 0; //$this->cart_pemenuhan_model->getDataPermintaanAtThatTime($start, $end);
                    break;

                    case "all":
                        $datatime[] = array();
                        $datatrack['super_qr'][]       = 0;//$this->user_model->getUserByCodeAndStatus("active",$rangemin,$rangemax,$super_qr,"");
                        $datatrack['new_reg'][]        = 0;//$this->user_model->getUserByCodeAndStatus("",$rangemin,$rangemax);
                        $datatrack['activated'][]      = 0;//$this->user_model->getUserByCodeAndStatus("active",$rangemin,$rangemax);
                        $datatrack['organic'][]        = 0;//$this->user_model->getUserByCodeAndStatus('active',$rangemin,$rangemax,$organic,"");
                        $datatrack['referral'][]       = 0;//$this->user_model->getUserByCodeAndStatus('active',$rangemin,$rangemax,"",$organic);
                        $start                         = "";
                        $end                           = "";
                    break;
                }

                $statistic['super_qr']        = $this->user_model->getUserByCodeAndStatus("active",$start,$end,$super_qr,"");
                $statistic['new_reg']         = $this->user_model->getUserByCodeAndStatus("",$start,$end);
                $statistic['activated']       = $this->user_model->getUserByCodeAndStatus("active",$start,$end);
                $statistic['organic']         = $this->user_model->getUserByCodeAndStatus('active',$start,$end,$organic,"");
                $statistic['referral']        = $this->user_model->getUserByCodeAndStatus('active',$start,$end,"",$organic);

                $datacontent = array(
                    "config"            => array("main_url" => "dashboard/view"),
                    "label_type"        => $label,
                    "dates"             => @$_GET['dates'],
                    "type"              => @$_GET['type'],
                    "chart"             => (@$_GET['chart'] == "") ? "line" : @$_GET['chart'],
                    "login"             => $login,
                    "helper"            => "",
                    "previlege"         => $this->previlege_model,
                    "data"              => $data,
                    "head_menu"         => $this->helper->getMenuContent($login, $this->previlege_model),
                    "tahun_data"        => date("Y"),
                    "bulan_on_year"     => $datatime,
                    "data_bulan_on_year" => $datatrack,
                    "effectiveness"      => $statistic
                );

                $view     = View::make("backend.admin.dashboard.growth", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " | Dashboard Administrator",
                    "description"   => $webname . " | Dashboard Administrator",
                    "keywords"      => $webname . " | Dashboard Administrator"
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
                $body = "backend.body_backend";
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
}
