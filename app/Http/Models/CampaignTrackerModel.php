<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CampaignTrackerModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_campaign_tracker';
	protected $primaryKey   = 'id_tracker';
	protected $fillable     = ['id_tracker', 'unique_link','id_campaign','status','type_conversion','time_created','last_update','time_created','id_user','ip','browser','date','commission','fee','id_program','referrer','os','device_info','fee','encrypted_code','callback_url','protocol','domain','info','city','region_code','connection_info','country'];

	public function getTotalAffiliatorData($id_campaign){
		$data  = CampaignTrackerModel::where("id_campaign",$id_campaign)
						->groupBy("id_user")
						->get();
		return $data;
	}

	public function getTrackerInfo($encrypted_code){
		$data = CampaignTrackerModel::where("encrypted_code",$encrypted_code)->first();
		return $data;
	}

	public function getDataTrackingByCity(){
		$data = CampaignTrackerModel::select(DB::raw("COUNT(*) as total_data_visit"),"city")
						->where("status","active")
						->groupBy("city")
						->orderBy("total_data_visit","DESC")
						->skip(0)
						->limit(25)
						->get();

		return $data;
	}

	public function getTestData($id_campaign){
		$data = CampaignTrackerModel::where("type_conversion","visit")->where("id_user","0")->where("id_campaign",$id_campaign)->where("status","active")->orderBy("time_created","desc")->skip(0)->limit(1)->first();
		if($data){
			return date("d M Y H:i:s",$data->time_created);
		}else{
			return 0;
		}
	}

	public function getLastTracker($type,$id_campaign){
		$data = CampaignTrackerModel::where("status","active")->where("type_conversion",$type)->where("id_campaign",$id_campaign)->orderBy('time_created','desc')->skip(0)->limit(1)->first();
		if($data){
			return date("d M Y H:i:s",$data->time_created);
		}else{
			return "";
		}
	}

	public function getTotalTracker($type,$id_campaign="",$login=""){
		if($type=="initial"){
			$data = CampaignTrackerModel::where("status","initial");
		}else{
			$data = CampaignTrackerModel::where("status","active");
		}


		if($type!=""){
			$data = $data->where("type_conversion",$type);
		}

		if($id_campaign!=""){
			$data = $data->where("id_campaign",$id_campaign);
		}

		if($login){
			$data = $data->where("id_user",$login->id_user);
		}

		$data = $data->whereNotIn("id_user",array("0"));
		$data = $data->get()
						->count();
		return $data;
	}

	public function getTransactionTotalInRangeReal($type,$rangemin = "",$rangemax = "", $id_campaign="",$login=""){
		$now 	= time();
		$min  = $rangemin;
		$max 	= $rangemax;

		$data = CampaignTrackerModel::whereIn("status",["active",'initial'])
						->where("type_conversion",$type);

		if($rangemin!="" && $rangemax!=""){
						$data->whereBetween("time_created",[$min,$max]);
		}

		if($id_campaign!=""){
			$data = $data->where("id_campaign",$id_campaign);
		}

		if($login){
			$data = $data->where("id_user",$login->id_user);
		}
		$data = $data->join(DB::raw("(SELECT first_name, last_name, id_user as idu FROM tb_user)user"),"user.idu","=",$this->table.".id_user");
		$data = $data->join(DB::raw("(SELECT campaign_title, campaign_type, id_campaign as idc FROM tb_campaign)campaign"),"campaign.idc","=",$this->table.".id_campaign");
		$data = $data->get()
						->count();
		return $data;
	}

	public function getTransactionTotalInRange($type,$rangemin,$rangemax, $id_campaign="",$login=""){
		$now 	= time();
		$min  = $now - $rangemax;
		$max 	= $now - $rangemin;

		if($type=="initial"){
			$data = CampaignTrackerModel::where("status","initial")
							->where("type_conversion",$type)
							->whereBetween("time_created",[$min,$max]);
		}else{
			$data = CampaignTrackerModel::where("status","active")
							->where("type_conversion",$type)
							->whereBetween("time_created",[$min,$max]);
		}

		if($id_campaign!=""){
			$data = $data->where("id_campaign",$id_campaign);
		}

		if($login){
			$data = $data->where("id_user",$login->id_user);
		}

		$data = $data->whereNotIn("id_user",array("0"));
		$data = $data->get()
						->count();
		return $data;
	}

	public function getTotalTrackingData($year,$status="active"){
		$data = CampaignTrackerModel::where("status",$status)->where("time_created",">",strtotime($year))->get()->count();
		return $data;
	}

	public function getTotalIncome(){
		$data = CampaignTrackerModel::where("status","active")->sum("fee");
		return $data;
	}

	public function sumTotalEarning($id_campaign="",$login="", $start="", $end=""){
		$startdate 	= (($start=="")?"01-".date("m-Y"):$start)." 00:00:00";
		$enddate 	= (($end=="")?"31-".date("m-Y"):$end)." 23:59:59";

		$between		= [strtotime($startdate),strtotime($enddate)];

		$data = CampaignTrackerModel::where("status","active")->whereBetween("time_created",$between);

		if($id_campaign!=""){
			$data = $data->where("id_campaign",$id_campaign);
		}

		if($login){
			$data = $data->where("id_user",$login->id_user);
		}

		$data = $data->sum("commission");

		return $data;
	}

	public function sumTotalEstimation($id_campaign="",$login="", $start="", $end=""){
		$startdate 	= (($start=="")?"01-".date("m-Y"):$start)." 00:00:00";
		$enddate 	= (($end=="")?"31-".date("m-Y"):$end)." 23:59:59";

		$between	= [strtotime($startdate),strtotime($enddate)];

		$data = CampaignTrackerModel::where("status","active")->whereBetween("time_created",$between);

		if($id_campaign!=""){
			$data = $data->where("id_campaign",$id_campaign);
		}

		if($login){
			$data = $data->where("id_user",$login->id_user);
			$data = $data->sum("commission");
		}else{
			$data = $data->sum(DB::raw("(commission+fee)"));
		}

		return $data;
	}

	public function checkTrackerRecord($type,$encrypted_code){
		return CampaignTrackerModel::where("encrypted_code",$encrypted_code)->where("type_conversion",$type)->get()->count();
	}

	public function getDetailTrackerFromUniquecode($type,$encrypted_code){
		return CampaignTrackerModel::where("encrypted_code",$encrypted_code)->where("type_conversion",$type)->first();
	}

	public function getLast15Logs($id_campaign,$login=""){
		$data = CampaignTrackerModel::where("status","active");

		if($id_campaign!=""){
			$data = $data->where("id_campaign",$id_campaign);
		}

		if($login){
			$data = $data->where("id_user",$login->id_user);
		}

		$data = $data->leftjoin(DB::raw("(SELECT campaign_title, id_campaign as idc FROM tb_campaign)tbc"),"tbc.idc","=",$this->table.".id_campaign");
		$data = $data->orderBy("time_created","DESC")->skip(0)->limit(15)->get();
		return $data;
	}

	public function getLast25Logs($id_campaign="",$login=""){
		$data = CampaignTrackerModel::where("status","active");

		if($id_campaign!=""){
			$data = $data->where("id_campaign",$id_campaign);
		}

		if($login){
			$data = $data->where("id_user",$login->id_user);
		}

		$data = $data->join(DB::raw("(SELECT first_name, last_name, id_user as idu FROM tb_user)user"),"user.idu","=",$this->table.".id_user");
		$data = $data->join(DB::raw("(SELECT campaign_title, campaign_type, id_campaign as idc FROM tb_campaign)campaign"),"campaign.idc","=",$this->table.".id_campaign");

		$data = $data->orderBy("time_created","DESC")->skip(0)->limit(25)->get();
		return $data;
	}

	public function getLast1000LogsGroup($id_campaign="",$login=""){
		$data = CampaignTrackerModel::select(DB::raw("COUNT(*) as total_data"),"type_conversion")
					->where("status","active");

		if($id_campaign!=""){
			$data = $data->where("id_campaign",$id_campaign);
		}

		if($login){
			$data = $data->where("id_user",$login->id_user);
		}

		$data = $data->join(DB::raw("(SELECT first_name, last_name, id_user as idu FROM tb_user)user"),"user.idu","=",$this->table.".id_user");
		$data = $data->join(DB::raw("(SELECT campaign_title, campaign_type, id_campaign as idc FROM tb_campaign)campaign"),"campaign.idc","=",$this->table.".id_campaign");

		$data = $data->groupBy("type_conversion")->skip(0)->limit(1000)->get();
		return $data;
	}

	public function getLast1000Logs($id_campaign="",$login=""){
		$data = CampaignTrackerModel::where("status","active");

		if($id_campaign!=""){
			$data = $data->where("id_campaign",$id_campaign);
		}

		if($login){
			$data = $data->where("id_user",$login->id_user);
		}

		$data = $data->join(DB::raw("(SELECT first_name, last_name, id_user as idu FROM tb_user)user"),"user.idu","=",$this->table.".id_user");
		$data = $data->join(DB::raw("(SELECT campaign_title, campaign_type, id_campaign as idc FROM tb_campaign)campaign"),"campaign.idc","=",$this->table.".id_campaign");

		$data = $data->orderBy("time_created","DESC")->skip(0)->limit(1000)->get();
		return $data;
	}

	public function getDataStock($str, $limit, $keyword, $short, $shortmode){
		$data = CampaignTrackerModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("naikan_rumus_code","like","%".$keyword."%");
								$query->orwhere("naikan_rumus_code","like","%".$keyword."%");
							}
						})
						->whereNotIn($this->table.".status",["removed"])
            ->join(DB::raw("(SELECT first_name as author_name, id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_available, tb_brownies_naikan_implementasi.id_naikan_rumus as id_nr FROM tb_brownies_stock_unit, tb_brownies_naikan_implementasi WHERE tb_brownies_stock_unit.id_implementasi = tb_brownies_naikan_implementasi.id_implementasi AND tb_brownies_stock_unit.status = 'active' AND tb_brownies_naikan_implementasi.status = 'active' AND tb_brownies_stock_unit.id_naikan IS NULL GROUP BY tb_brownies_naikan_implementasi.id_naikan_rumus)stk"),"stk.id_nr","=",$this->table.".id_naikan_rumus")
						->leftjoin(DB::raw("(SELECT AVG(hpp) as hpp_prediksi, tb_brownies_naikan_implementasi.id_naikan_rumus as id_nr2 FROM tb_brownies_stock_unit, tb_brownies_naikan_implementasi WHERE tb_brownies_stock_unit.id_implementasi = tb_brownies_naikan_implementasi.id_implementasi AND tb_brownies_stock_unit.status = 'active' AND tb_brownies_naikan_implementasi.status = 'active'  GROUP BY tb_brownies_naikan_implementasi.id_naikan_rumus)stkhpp"),"stkhpp.id_nr2","=",$this->table.".id_naikan_rumus")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function findNaikanRumus($keyword, $selected){
		$data = CampaignTrackerModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("naikan_rumus_name","like","%".$keyword."%");
								$query->orwhere("naikan_rumus_code","like","%".$keyword."%");
							}
						})
						->whereNotIn($this->table.".status",["removed"]);

						if($selected!=""){
							$data		= $data->where(function($query) use ($selected){
								if($selected!=""){
									$query->orwhereRaw("id_naikan_rumus NOT IN ($selected)");
								}
							});
						}

						$data 	= $data->orderBy("naikan_rumus_name","ASC")->get();
		return $data;
	}

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = CampaignTrackerModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("naikan_rumus_code","like","%".$keyword."%");
								$query->orwhere("naikan_rumus_code","like","%".$keyword."%");
                $query->orwhere("author_name","like","%".$keyword."%");
							}
						})
						->whereNotIn($this->table.".status",["removed"])
            ->join(DB::raw("(SELECT first_name as author_name, id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
            ->leftjoin(DB::raw("(SELECT GROUP_CONCAT(CONCAT('<label class=\"label label-info\">',cetakan_quantity,'x ',cetakan_name,'</label>') SEPARATOR ' ') AS products, id_naikan_rumus as id_nr FROM tb_brownies_cetakan,tb_brownies_naikan_rumus_item WHERE tb_brownies_cetakan.id_cetakan = tb_brownies_naikan_rumus_item.id_cetakan AND tb_brownies_cetakan.status NOT IN ('removed') AND tb_brownies_naikan_rumus_item.status = 'active' GROUP BY id_naikan_rumus)loyang"),"loyang.id_nr","=",$this->table.".id_naikan_rumus")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function updateStock($total, $id_material){
		$query = "UPDATE tb_fremilt_material SET quantity = quantity + $total WHERE id_material = '$id_material'";
		return $this->executeQuery($query);
	}

	public function countDataStock($keyword){
		$data = CampaignTrackerModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("naikan_rumus_code","like","%".$keyword."%");
								$query->orwhere("naikan_rumus_code","like","%".$keyword."%");
							}
						})
						->whereNotIn($this->table.".status",["removed"])
            ->join(DB::raw("(SELECT first_name as author_name, id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
						->count();
		return $data;
	}

	public function countData($keyword){
		$data = CampaignTrackerModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("naikan_rumus_code","like","%".$keyword."%");
								$query->orwhere("naikan_rumus_code","like","%".$keyword."%");
                $query->orwhere("author_name","like","%".$keyword."%");
							}
						})
						->whereNotIn($this->table.".status",["removed"])
            ->join(DB::raw("(SELECT first_name as author_name, id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
						->count();
		return $data;
	}

  public function getDataRumus(){
    $data = CampaignTrackerModel::whereIn($this->table.".status",["active"])
            ->join(DB::raw("(SELECT first_name as author_name, id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
            ->leftjoin(DB::raw("(SELECT GROUP_CONCAT(CONCAT('<label class=\"label label-info\">',cetakan_quantity,'x ',cetakan_name,'</label>') SEPARATOR ' ') AS products, id_naikan_rumus as id_nr FROM tb_brownies_cetakan,tb_brownies_naikan_rumus_item WHERE tb_brownies_cetakan.id_cetakan = tb_brownies_naikan_rumus_item.id_cetakan AND tb_brownies_cetakan.status NOT IN ('removed') GROUP BY id_naikan_rumus)loyang"),"loyang.id_nr","=",$this->table.".id_naikan_rumus")
						->orderBy("naikan_rumus_name", "ASC")
						->get();
		return $data;
  }

	public function getListLokasiSekitar($latitude, $longitude, $id, $distance){
		//$additionalquery = "AND ";
		$raw 			= DB::raw("SELECT *, (6371 * acos( cos( radians(" . $latitude . ") ) * cos( radians( `latitude` ) ) * cos( radians(`longitude` ) - radians(" . $longitude . ") ) + sin( radians(" .$latitude. ") ) * sin( radians(`latitude`) ) ) ) as distance FROM tb_fremilt_mitra WHERE status = 'diterima' AND id_mitra NOT IN ($id) AND (6371 * acos( cos( radians(" . $latitude . ") ) * cos( radians( `latitude` ) ) * cos( radians(`longitude` ) - radians(" . $longitude . ") ) + sin( radians(" .$latitude. ") ) * sin( radians(`latitude`) ) ) ) < $distance");
		$data 		= DB::select($raw);
		return $data;
	}

	public function getlist(){
		//return MaterialModel::where("status","active")->orderBy("material_name","asc")->pluck("material_name","id_material");
		$data = CampaignTrackerModel::where("status","active")->orderBy("naikan_rumus_name","asc")->get();
		if($data->count()>0){
			foreach ($data as $key => $value) {
				$ret[$value->id_naikan_rumus] = $value->naikan_rumus_name." (".$value->naikan_rumus_code.")";
			}
			return $ret;
		}else{
			return 0;
		}
	}

	public function executeQuery($query){
		return DB::select(DB::raw($query));
	}

	public function getAllData(){
		return CampaignTrackerModel::where("status","diterima")->get();
	}

	public function getDetail($id) {
		$data = CampaignTrackerModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = CampaignTrackerModel::create($data)->id_naikan_rumus;
		return $result;
  }

	public function updateData($data){
		$result = CampaignTrackerModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id){
		$result = CampaignTrackerModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

	public function checkMinimumQuantity($id_material){
		$data = $this->executeQuery(DB::raw("SELECT * FROM ".$this->table." WHERE id_material = $id_material AND quantity <= minimum_stock"));
		if(sizeof($data)){
			return $data[0];
		}else{
			return 0;
		}
	}

	public function createEncryptedCode($affiliate_code,$ip){
    return md5(date("Y-m-d")."_".$ip."_".$affiliate_code);
  }
  public function getLogsByCampaign($id){
	$data=CampaignTrackerModel::select("campaign_title", "time_created", "type_conversion", "commission", "first_name", "last_name", "name")
	->join(DB::raw("(SELECT id_campaign, campaign_title FROM tb_campaign) campaign"),"campaign.id_campaign","=",$this->table.".id_campaign")
	->join(DB::raw("(SELECT id_user, first_name, last_name, id_department FROM tb_user) user"),"user.id_user","=",$this->table.".id_user")
	->join(DB::raw("(SELECT id_department, name FROM tb_department) department"), "department.id_department","=","user.id_department")
	->where("campaign.id_campaign", "=", $id)
	->get();
	return $data;
  }
}
