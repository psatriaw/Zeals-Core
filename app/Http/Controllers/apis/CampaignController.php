<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use Validator;
use App\Models\{Campaign, CampaignUniqueLink};
use Illuminate\Support\Facades\DB;

use Session;


class CampaignController extends Controller
{
    public function __construct(){
        //$this->setting_model = new SettingModel();
    }

    public function list(Request $request){
        $keyword    = $request->keyword;
        $today      = date("Y-m-d");

        $data = Campaign::with('program')->whereNull("campaign_internal")
			->whereNotIn("campaign_type",["saham","surat hutang"])
			->where(function ($query) use ($today) {
					$query->where("tb_campaign.status","active");
					$query->where("start_date","<=",$today);
					$query->where("end_date",">=",$today);
					$query->where("running_status","open");
			})
			->where(function ($query) use ($keyword) {
				if ($keyword != "") {
					$query->where("campaign_title", "like", "%" . $keyword . "%");
					$query->orWhere("nama_penerbit", "like", "%".$keyword."%");
					$query->orWhere("campaign_description", "like", "%".$keyword."%");
				}
			})
			->select('tb_penerbit.nama_penerbit', 'tb_campaign.*')
			->leftJoin('tb_penerbit', 'tb_penerbit.id_penerbit', '=', 'tb_campaign.id_penerbit');

        if($request->id_category!=""){
            $data = $data->join(DB::raw("(SELECT id_campaign FROM tb_campaign_property WHERE property_type='category' AND value='".$request->id_category."' AND tb_campaign_property.status = 'active' GROUP BY id_campaign)cat"),"cat.id_campaign","=","tb_campaign.id_campaign");
        }

        if($request->order_by){
            switch($request->order_by){
                case 'hot':
                    $data       = $data->join(DB::raw("(SELECT count(id_user) as joining, id_campaign as idcs FROM tb_campaign_unique_link WHERE tb_campaign_unique_link.time_created >= ".strtotime('-3 month')." GROUP BY id_campaign)comm"),"comm.idcs","=","tb_campaign.id_campaign");
                    $order      = 'joining';
                    $sort       = 'DESC';
                break;
                case 'random':
                    $data       = $data->orderByRaw("RAND()");
                break;
                
                case 'highest':
                    $data       = $data->join(DB::raw("(SELECT SUM(commission) as total_commission, id_campaign as idcs FROM tb_campaign_program GROUP BY id_campaign)comm"),"comm.idcs","=","tb_campaign.id_campaign");
                    $order      = 'total_commission';
                    $sort       = 'DESC';
                break;

                default:
                    $order = $request->order_by;
                    $sort = $request->sort;
                break;
            }
        }else{
          $order = 'start_date';
          $sort = 'DESC';
        }
        if($request->order_by!='random'){
          $data       = $data->orderBy($order,$sort);
        }

        if($request->categories){
            $categories = implode("','",$request->categories);
            $data = $data->join(DB::raw("(SELECT id_campaign as idcc FROM tb_campaign_property WHERE property_type = 'category' AND status = 'active' AND value IN ('$categories'))catpro"),"catpro.idcc","=","tb_campaign.id_campaign");
        }

        $user = auth('sanctum')->user();

        if($request->filter=="joined"){
            $id_user 	= $user->id_user;
            $data 		= $data->join(DB::raw("(SELECT unique_link as joined, id_campaign as idc FROM tb_campaign_unique_link WHERE id_user = '$id_user')ul"),"ul.idc","=","tb_campaign.id_campaign");
        }else{
            $id_user  = 0;
            $data 		= $data->leftjoin(DB::raw("(SELECT unique_link as joined, id_campaign as idc FROM tb_campaign_unique_link WHERE id_user = '$request->id_user')ul"),"ul.idc","=","tb_campaign.id_campaign");
        }

        if($request->limit!=0){
            $data 		= $data->limit($request->limit)->skip(0);
        }
        $data = $data->orderBy("tb_campaign.start_date", "DESC")->get();

        if($data){

            $return = array(
                "status"    => "success",
                "data"      => $data,
                "path"      => url('/'),
                "user"      => $user
            );
        }else{
            $return = array(
                "status"    => "error",
                "messages"  => "No banner found!"
            );
        }

        return response()->json($return, 200);
    }

    public function detail(Request $request){
        $user = auth('sanctum')->user();

        $campaign = Campaign::where("tb_campaign.id_campaign",$request->id_campaign)->with('program','joincampaign','joincampaign.reach','joincampaign.visit','joincampaign.interest','joincampaign.action','joincampaign.acquisition','joincampaign.last15logs','joincampaign.estimation','joincampaign.earning');
        if($user){
            $joined = DB::raw("(SELECT unique_link, time_created, id_campaign as idcj FROM tb_campaign_unique_link WHERE id_user = '$user->id_user')joincampaign");
            $campaign = $campaign->leftjoin($joined,"joincampaign.idcj","=","tb_campaign.id_campaign");
        }

        $campaign   = $campaign->first();


        if($campaign){
            $campaign['campaign_description']   = nl2br($campaign->campaign_description); 
            $campaign['campaign_instruction']   = nl2br($campaign->campaign_instruction); 
            $campaign['campaign_do_n_dont']     = nl2br($campaign->campaign_do_n_dont); 
             
            $return = array(
                "status"    => "success",
                "data"      => $campaign,
                "path"      => url('/')
            );
        }else{
            $return = array(
                "status"    => "error",
                "messages"  => "No campaign found!"
            );
        }

        return response()->json($return, 200);
    }

    public function join(Request $request){
        $user        = auth('sanctum')->user();
        $id_campaign = $request->id_campaign;

        $data   = [
            "time_created"  => time(),
            "last_update"   => time(),
            "id_user"       => $user->id_user,
            "id_campaign"   => $id_campaign,
            "unique_link"   => (new CampaignUniqueLink())->createLink()
        ];

        $join = CampaignUniqueLink::updateOrCreate($data,[
            "id_user"       => $user->id_user,
            "id_campaign"   => $id_campaign,
        ]);

        if($join){
            $return = array(
                "status"    => "success",
                "messages"  => "Join campaign success!",
                "unique_link"   => $data['unique_link']
            );
        }else{
            $return = array(
                "status"    => "error",
                "messages"  => "Failed to join campaign"
            );
        }

        return response()->json($return, 200);
    }
}
