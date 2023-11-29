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

use App\Http\Models\WilayahModel;
use App\Http\Models\DemographModel;

use App\Http\Models\dashboard_bcsModel;
use App\Http\Models\UserModel;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Session;
use stdClass;

//===============================

class DemographController extends Controller
{
    private $setting_model;
    private $previlege_model;
    private $helper;
    private $wilayah_model;
    private $demograph_model;

    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->helper           = new IGWHelper();

        $this->wilayah_model               = new WilayahModel();
        $this->demograph_model             = new DemographModel();
        
        // $this->data_bulan = array(
        //   "1"   => "January",
        //   "2"   => "February",
        //   "3"   => "March",
        //   "4"   => "April",
        //   "5"   => "May",
        //   "6"   => "June",
        //   "7"   => "July",
        //   "8"   => "August",
        //   "9"   => "September",
        //   "10"   => "October",
        //   "11"   => "November",
        //   "12"   => "December",
        // );
        $dataconfig['main_url'] = "admin/demograph";
        $dataconfig['view']     = "demograph-view";
        $this->config           = $dataconfig;
    }


    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    public function index(Request $request)
    {
        $login    = Session::get("user");
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {
                // $checkis_penerbit = $this->penerbit_model->checkUserIsPenerbit($login->id_user);
                // if($checkis_penerbit){
                //   $id_penerbit = $checkis_penerbit->id_penerbit;
                // }

                // $data       = $this->campaign_model->getAvailableCampaign2($str, $limit, $keyword, $short, $shortmode, @$login->id_brand);
                // $userdata = $this->user_model::get();
                // dd($data);

                //exit();
                // $totaldata  = count($data);
                // $total      = $this->campaign_model->getTotalCampaign(@$id_penerbit);
                // $pagging    = $this->helper->showPagging($total, url($this->config['main_url'] . '?keyword=' . $keyword . "&short=" . $short . "&shortmode=" . $shortmode), $position = "", $page, $limit, 2);

                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    // "data"           => $data,
                    // "pagging"        => $pagging,
                    "input"          => $request->all(),
                    // "default"        => $default,
                    "config"         => $this->config,
                    // "shorter"        => $shorter,
                    // "page"           => $page,
                    // "limit"          => $limit,
                    // "total_data"     => @$total,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    "data_map"       => $this->demograph_model->getWilayahAndDistribution(),
                    "data_affiliator"=> $this->demograph_model->demographAffiliator(),
                    // "userdata" => $userdata
                );


                $view     = View::make("backend.admin.demograph", $datacontent);
                $content  = $view->render();

                $metadata = array(
                    "title"         => $webname . " |  Demographic Affiliator",
                    "description"   => $webname . " |  Demographic Affiliator",
                    "keywords"      => $webname . " |  Demographic Affiliators"
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
}
