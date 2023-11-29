<?php 
namespace App\Services\apis;

use App\Models\ { Campaign };

use Illuminate\Support\Facades\DB;
 
class CampaignService {

    public function __construct($request){
        $this->request = $request;
    }

    public function get($type=""){
        $keyword    = $this->request->keyword;
        $today      = date("Y-m-d");

        $data = Campaign::with('program')->whereNull("campaign_internal")
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

        if($type){
            switch($type){
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
                    $order      = 'start_date';
                    $sort       = 'DESC';
                break;
            }
        }else{
          $order    = 'start_date';
          $sort     = 'DESC';
        }

        if($type!='random'){
          $data       = $data->orderBy($order,$sort);
        }

        if($this->request->categories){
            $categories = implode("','",$this->request->categories);
            $data = $data->join(DB::raw("(SELECT id_campaign as idcc FROM tb_campaign_property WHERE property_type = 'category' AND status ='active' AND value IN ('$categories'))catpro"),"catpro.idcc","=","tb_campaign.id_campaign");
        }

        $user = auth('sanctum')->user();

        if($this->request->filter=="joined"){
            $id_user 	= $user->id_user;
            $data 		= $data->join(DB::raw("(SELECT unique_link as joined, id_campaign as idc FROM tb_campaign_unique_link WHERE id_user = '$id_user')ul"),"ul.idc","=","tb_campaign.id_campaign");
        }

        if($this->request->limit!=0){
            $skip       = ($this->request->page - 1)*$this->request->limit;
            $data 		= $data->limit($this->request->limit)->skip($skip);
        }
        $data = $data->orderBy("tb_campaign.start_date", "DESC")->get();

        return $data;
    }

}

?>