<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\IGWHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\Controller;

use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
use App\Http\Models\MicrositesModel;
use App\Http\Models\CampaignModel;
use App\Http\Models\CampaignTrackerModel;
use App\Http\Models\CampaignProgramModel;


class ReportController extends Controller
{
    //
    public function __construct()
    {
        $this->setting_model    = new SettingModel();
        $this->previlege_model  = new PrevilegeModel();
        $this->microsite_model  = new MicrositesModel();
        $this->campaign_model  = new CampaignModel();
        $this->campaign_tracker_model  = new CampaignTrackerModel();
        $this->campaign_program_model  = new CampaignProgramModel();
        $this->helper  = new IGWHelper();

        $dataconfig['main_url'] = "master/microsite";
        $dataconfig['view']     = "microsite-view";
        $dataconfig['create']   = "microsite-create";
        $dataconfig['edit']     = "microsite-edit";
        $dataconfig['report']   = "microsite-report";
        $dataconfig['remove']   = "microsite-remove";
        $dataconfig['manage']   = "microsite-manage";
        $this->config           = $dataconfig;
    }
    public function report(Request $request)
    {
        $login    = Session::get("user");
        $keyword = empty($request->search) ? $request->search : '';
        $webname  = $this->setting_model->getSettingVal("website_name");
        if ($login) {
            if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
                $detail = $this->campaign_model->getDetailByCampaignLink("CMP00000008");
                $datacontent = array(
                    "login"          => $login,
                    "helper"         => "",
                    "previlege"      => $this->previlege_model,
                    "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
                    "detail"         => $detail,
                    "logs"           => $this->campaign_tracker_model->getLast25Logs($detail->id_campaign),
                    "earning"        => array(
                        "estimation"    => $this->campaign_tracker_model->sumTotalEstimation($detail->id_campaign),
                        "total"         => $this->campaign_tracker_model->sumTotalEarning($detail->id_campaign),
                    ),
                    "logs_group"      => $this->campaign_tracker_model->getLast1000LogsGroup(),
                    "logs"            => $this->campaign_tracker_model->getLast1000Logs(),
                    // 'affiliators'    => $this->campaign_join_model->getPerformanceofAff($detail->id_campaign)
                );

                $statistic_visit        = $this->campaign_tracker_model->getTotalTracker('visit', $detail->id_campaign);
                $statistic_read         = $this->campaign_tracker_model->getTotalTracker('read', $detail->id_campaign);
                $statistic_action       = $this->campaign_tracker_model->getTotalTracker('action', $detail->id_campaign);
                $statistic_acquisition  = $this->campaign_tracker_model->getTotalTracker('acquisition', $detail->id_campaign);
                $statistic_reach        = $this->campaign_tracker_model->getTotalTracker('initial', $detail->id_campaign);

                $datacontent['statistic']['reach'] = array(
                    "total"       => $statistic_reach,
                    "percent"     => 0,
                    "items"       => $statistic_reach
                );

                for ($i = 29; $i > 0; $i--) {
                    $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial', ($i * 60 * 60 * 24) + 1, ($i + 1) * 60 * 60 * 24, $detail->id_campaign);
                }
                $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial', (12 * 60 * 60) + 1, 1 * 60 * 60 * 24, $detail->id_campaign);
                $datacontent['chart']['reach'][] = $this->campaign_tracker_model->getTransactionTotalInRange('initial', 0, 12 * 60 * 60, $detail->id_campaign);

                for ($i = 29; $i > 0; $i--) {
                    $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit', ($i * 60 * 60 * 24) + 1, ($i + 1) * 60 * 60 * 24, $detail->id_campaign);
                }
                $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit', (12 * 60 * 60) + 1, 1 * 60 * 60 * 24, $detail->id_campaign);
                $datacontent['chart']['visit'][] = $this->campaign_tracker_model->getTransactionTotalInRange('visit', 0, 12 * 60 * 60, $detail->id_campaign);

                for ($i = 29; $i > 0; $i--) {
                    $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read', ($i * 60 * 60 * 24) + 1, ($i + 1) * 60 * 60 * 24, $detail->id_campaign);
                }
                $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read', (12 * 60 * 60) + 1, 1 * 60 * 60 * 24, $detail->id_campaign);
                $datacontent['chart']['read'][] = $this->campaign_tracker_model->getTransactionTotalInRange('read', 0, 12 * 60 * 60, $detail->id_campaign);

                for ($i = 29; $i > 0; $i--) {
                    $datacontent['chart']['action'][] = $this->campaign_tracker_model->getTransactionTotalInRange('action', ($i * 60 * 60 * 24) + 1, ($i + 1) * 60 * 60 * 24, $detail->id_campaign);
                }
                $datacontent['chart']['action'][] = $this->campaign_tracker_model->getTransactionTotalInRange('action', (12 * 60 * 60) + 1, 1 * 60 * 60 * 24, $detail->id_campaign);
                $datacontent['chart']['action'][] = $this->campaign_tracker_model->getTransactionTotalInRange('action', 0, 12 * 60 * 60, $detail->id_campaign);

                for ($i = 29; $i > 0; $i--) {
                    $datacontent['chart']['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRange('acquisition', ($i * 60 * 60 * 24) + 1, ($i + 1) * 60 * 60 * 24, $detail->id_campaign);
                }
                $datacontent['chart']['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRange('acquisition', (12 * 60 * 60) + 1, 1 * 60 * 60 * 24, $detail->id_campaign);
                $datacontent['chart']['acquisition'][] = $this->campaign_tracker_model->getTransactionTotalInRange('acquisition', 0, 12 * 60 * 60, $detail->id_campaign);

                //if($statistic_visit>0){

                $datacontent['statistic']['visit'] = array(
                    "total"       => $statistic_visit,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('visit', $detail->id_campaign)
                );


                $datacontent['statistic']['read'] = array(
                    "total"       => $statistic_read,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('read', $detail->id_campaign)
                );

                $datacontent['statistic']['action'] = array(
                    "total"       => $statistic_action,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('action', $detail->id_campaign)
                );

                $datacontent['statistic']['acquisition'] = array(
                    "total"       => $statistic_acquisition,
                    "percent"     => 0,
                    "items"       => $this->campaign_program_model->getProgramByCampaign('acquisition', $detail->id_campaign)
                );

                //}

                // dd($datacontent);
                for ($i = 29; $i >= 0; $i--) {
                    $label_chart[] = ($i + 1) . " days ago";
                }
                $label_chart[] = "Under 12 hours";
                $datacontent['label_chart']  = $label_chart;

                // dd($datacontent);

                $view     = View::make("backend.master.microsite.report", $datacontent);
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
}
