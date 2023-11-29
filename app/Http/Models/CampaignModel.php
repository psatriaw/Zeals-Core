<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CampaignModel extends Model
{
	public $timestamps      = false;
	const CREATED_AT        = 'time_created';
	const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_campaign';
	protected $primaryKey   = 'id_campaign';
	protected $fillable     = ['id_campaign', 'id_penerbit', 'time_created', 'last_update', 'campaign_title', 'campaign_description','campaign_instruction','campaign_do_n_dont', 'status', 'start_date', 'end_date', 'budget', 'max_commission', 'photos','campaign_link','landing_url','campaign_type','tipe_url','campaign_internal','dislcaimer'];
	protected $prestring 				= "CMP";
	protected $digittransaction = 8;

	public function getResumeCampaign($keyword,$id_penerbit=0, $start_date="", $end_date=""){
		//"sum-running","sum-budget","sum-stopped","sum-expired","sum-drafted","sum-paid","sum-revenue","sum-outsanding";
		$data = CampaignModel::where("status","active");

		switch($keyword){
			case "sum-running":
				$today = date("y-m-d");
				$data = $data->where("running_status","open")
							->where(function ($query) use ($today) {
									$query->where("start_date","<=",$today);
									$query->where("end_date",">=",$today);
							});
			break;

			case "sum-stopped":
				$data = $data->where("running_status","closed");
			break;

			case "sum-expired":
				$today = date("y-m-d");
				$data = $data->where("running_status","open")
							->where(function ($query) use ($today) {
									$query->where("end_date","<",$today);
							});
			break;

			case "sum-drafted":
				$data = $data->where("running_status","close");
			break;

			case "sum-revenue";
				if($id_penerbit!=0){
					$data 	=	$data->leftjoin(DB::raw("(SELECT SUM(commission+fee) as running_budget, SUM(fee) as system_rev,id_campaign as idc FROM tb_campaign_tracker GROUP BY id_campaign)rev"),"rev.idc","=",$this->table.".id_campaign");
					$data 	= $data->where("id_penerbit",$id_penerbit);
					return $data->sum("running_budget");
				}else{
					$data 	= DB::select(DB::raw("SELECT SUM(commission+fee) as running_budget, SUM(fee) as system_rev FROM tb_campaign_tracker"));
					return $data[0]->system_rev;
				}
			break;

			case "sum-paid";
				return 0;
			break;

			case "sum-outstanding";
				if($id_penerbit!=0){
					$data 	=	$data->leftjoin(DB::raw("(SELECT SUM(commission+fee) as running_budget, SUM(fee) as system_rev,id_campaign as idc FROM tb_campaign_tracker GROUP BY id_campaign)rev"),"rev.idc","=",$this->table.".id_campaign");
					$data 	= $data->where("id_penerbit",$id_penerbit);
					return $data->sum("running_budget");
				}else{
					$data 	= DB::select(DB::raw("SELECT SUM(commission+fee) as running_budget, SUM(fee) as system_rev FROM tb_campaign_tracker"));
					return $data[0]->running_budget;
				}
			break;
		}

		if($id_penerbit!=0){
			$data = $data->where("id_penerbit",$id_penerbit);
		}

		$data 	= $data->get()->count();

		return $data;

	}

	public function getMightlikeCampaign($id_user,$limit){
		//dd($id_user)l
		return	 CampaignModel::where("status","active")
										->where("end_date",">",date("Y-m-d",time()))
										->join(DB::raw("(SELECT id_campaign as idc,time_created as urutan FROM tb_campaign_unique_link where id_user = '$id_user')tbl"),"tbl.idc","=",$this->table.".id_campaign")
										->join(DB::raw("(SELECT GROUP_CONCAT(CONCAT(nama_sektor_industri,'_',id_sektor_industri) SEPARATOR ':') as data_categories, id_campaign FROM tb_campaign_property, tb_sektor_industri WHERE tb_campaign_property.property_type = 'category' AND tb_campaign_property.value = tb_sektor_industri.id_sektor_industri AND tb_campaign_property.status = 'active' GROUP BY id_campaign)categories"),"categories.id_campaign","=",$this->table.".id_campaign")
										->limit($limit)
										->inRandomOrder()
										->get();
	}

	public function getJoinedCampaignByUser($id_user,$limit){
		//dd($id_user)l
		return	 CampaignModel::where("status","active")
										->join(DB::raw("(SELECT id_campaign as idc,time_created as urutan FROM tb_campaign_unique_link where id_user = '$id_user')tbl"),"tbl.idc","=",$this->table.".id_campaign")
										->join(DB::raw("(SELECT GROUP_CONCAT(CONCAT(nama_sektor_industri,'_',id_sektor_industri) SEPARATOR ':') as data_categories, id_campaign FROM tb_campaign_property, tb_sektor_industri WHERE tb_campaign_property.property_type = 'category' AND tb_campaign_property.value = tb_sektor_industri.id_sektor_industri AND tb_campaign_property.status = 'active' GROUP BY id_campaign)categories"),"categories.id_campaign","=",$this->table.".id_campaign")
										->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach, id_campaign as id_ct FROM tb_campaign_tracker WHERE type_conversion = 'initial' AND id_user = $id_user GROUP BY id_campaign)tracker"),"tracker.id_ct","=",$this->table.".id_campaign")
										->leftjoin(DB::raw("(SELECT SUM(commission) as total_earning, id_campaign as id_ce FROM tb_campaign_tracker WHERE id_user = $id_user GROUP BY id_campaign)earning"),"earning.id_ce","=",$this->table.".id_campaign")
										->limit($limit)
										->orderBy("urutan","DESC")
										->get();
	}

	public function getMost10Earning($login=""){
		$id_user = $login->id_user;
		$data = CampaignModel::where("status","active")
						->join(DB::raw("(SELECT SUM(commission) as earning, id_campaign FROM tb_campaign_tracker WHERE id_user = '$id_user' GROUP BY id_campaign)com"),"com.id_campaign","=",$this->table.".id_campaign")
						->join(DB::raw("(SELECT id_campaign FROM tb_campaign_unique_link WHERE id_user = '$id_user')link"),"link.id_campaign","=",$this->table.".id_campaign")
						->orderBy("earning","DESC")
						->get();
		return $data;
	}

	public function findCampaignByIDPenerbit($keyword, $id_penerbit){
		$data  = CampaignModel::where("id_penerbit",$id_penerbit);

		if($keyword!=""){
			$data = $data->where(function ($query) use ($keyword) {
				if ($keyword != "") {
					$query->where("campaign_title", "like", "%" . $keyword . "%");
				}
			});
		}

		$data = $data->where("status","active")
						->get();

		return $data;
	}

	public function getDetailByCampaignLink($campaign_link,$login=""){
		if($login==""){
			return CampaignModel::where("campaign_link",$campaign_link)->first();
		}else{
			$id_user  = $login->id_user;
			return CampaignModel::where("campaign_link",$campaign_link)
						->leftjoin(DB::raw("(SELECT unique_link as joined, id_campaign as idc FROM tb_campaign_unique_link WHERE id_user = '$id_user')ul"),"ul.idc","=",$this->table.".id_campaign")
						->first();
		}
	}

	public function getTotalCampaign($id_penerbit=""){
		if($id_penerbit!=""){
				return CampaignModel::whereNotIn("status",["deleted"])->where($this->table.".id_penerbit",$id_penerbit)->count();
		}else{
				return CampaignModel::whereNotIn("status",["deleted"])->count();
		}
	}

	public function getCampaignByPenerbitPluck($id_penerbit){
		return CampaignModel::where("id_penerbit",$id_penerbit)->where("status","active")->pluck('campaign_title','id_campaign');
	}

	public function getCampaignByPenerbit($id_penerbit){
		return CampaignModel::where("id_penerbit",$id_penerbit)->where("status","active")->get();
	}

	public function getTotalCampaignStatus($status, $id_penerbit=""){
		$date = date("y-m-d");

		$data = CampaignModel::select("*",DB::raw("(total_terkumpul - target_fund) as lebih"))
						->where("status","active")->whereDate("start_date","<=",$date)->whereDate("end_date",">=",$date)
						->leftjoin(DB::raw("(SELECT SUM(harga_beli * quantity) as total_terkumpul, id_campaign as idc FROM tb_cart_detail GROUP BY idc)cp"),"cp.idc","=",$this->table.".id_campaign");
		if($id_penerbit){
				$data = $data->where("id_penerbit",$id_penerbit);
		}

		if($status=="complete"){
			$data = $data->havingRaw("lebih >= 0")->get();
		}else{
			$data = $data->havingRaw("lebih < 0")->get();
		}
		//$query = "SELECT *, ";
		//print($data->toSql());
		return $data;
		//return 0;
	}

	public function getTotalBudget($range=""){
		$data = CampaignModel::where("status","active")->where("running_status","open");
		if($range!=""){
			$data = $data->where("time_created",">",$range);
		}

		$data = $data->sum("budget");
		return $data;
	}

	public function getTotalRunningCampaign($id_penerbit=""){
		$date = date("y-m-d");

		if($id_penerbit){
			return CampaignModel::where("status","active")->whereDate("start_date","<=",$date)->whereDate("end_date",">=",$date)->where($this->table.".id_penerbit",$id_penerbit);
		}else{
			return CampaignModel::where("status","active")->whereDate("start_date","<=",$date)->whereDate("end_date",">=",$date)->where("running_status","open");
		}
	}

	public function getAvailableCampaign($path, $limit){
		$data = CampaignModel::where("status","active")
						->leftjoin(DB::raw("(SELECT nama_penerbit, kode_penerbit, id_penerbit, id_sektor_industri FROM tb_penerbit)pnb"),"pnb.id_penerbit","=",$this->table.".id_penerbit")
						->leftjoin(DB::raw("(SELECT nama_sektor_industri, id_sektor_industri as isi FROM tb_sektor_industri)tsi"),"tsi.isi","=","pnb.id_sektor_industri")
						->leftjoin(DB::raw("(SELECT SUM(quantity) as saham_terjual,SUM(harga_beli*quantity) as total_terpenuhi, COUNT(tb_cart.id_cart) as slot_terpakai, id_campaign as idc FROM tb_cart_detail,tb_cart WHERE tb_cart.id_cart = tb_cart_detail.id_cart AND tb_cart.status = 'paid' GROUP BY id_campaign)tcd"),"tcd.idc","=",$this->table.".id_campaign")
						->limit($limit)
						->inRandomOrder()
						->get();
		return $data;
	}

	public function getAvailableCampaign2($str, $limit, $keyword, $short, $shortmode, $id_penerbit="", $start_date="", $end_date=""){
		$data = CampaignModel::where(function ($query) use ($keyword) {
			if ($keyword != "") {
				$query->where("campaign_title", "like", "%" . $keyword . "%");
				$query->orwhere("start_date", "like", "%" . $keyword . "%");
				$query->orwhere("end_date", "like", "%" . $keyword . "%");
				$query->orWhere("nama_penerbit", "like", "%".$keyword."%");
			}
		});

		if($id_penerbit){
			$data = $data->where($this->table.".id_penerbit",$id_penerbit);
		}


		if($start_date!=""){
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as joined, id_campaign as idc FROM tb_campaign_unique_link WHERE id_user > 0 GROUP BY id_campaign)ul"),"ul.idc","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT SUM(commission+fee) as running_budget, SUM(fee) as system_rev, id_campaign as idcc FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date GROUP BY idcc)bd"),"idcc","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach, id_campaign as idconv FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'initial' GROUP BY idconv)cdata"),"idconv","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_visit, id_campaign as idconv2 FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'visit' GROUP BY idconv2)cdata2"),"idconv2","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_read, id_campaign as idconv3 FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'read' GROUP BY idconv3)cdata3"),"idconv3","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_action, id_campaign as idconv4 FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'action' GROUP BY idconv4)cdata4"),"idconv4","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_acquisition, id_campaign as idconv5 FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'acquisition' GROUP BY idconv5)cdata5"),"idconv5","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_usage, id_campaign as id_voucher_campaign FROM tb_voucher WHERE time_usage BETWEEN $start_date AND $end_date AND status = 'used' GROUP BY id_voucher_campaign)cdata_voucher"),"id_voucher_campaign","=",$this->table.".id_campaign");
		}else{
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as joined, id_campaign as idc FROM tb_campaign_unique_link WHERE id_user > 0 GROUP BY id_campaign)ul"),"ul.idc","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT SUM(commission+fee) as running_budget, SUM(fee) as system_rev, id_campaign as idcc FROM tb_campaign_tracker GROUP BY idcc)bd"),"idcc","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach, id_campaign as idconv FROM tb_campaign_tracker WHERE type_conversion = 'initial' GROUP BY idconv)cdata"),"idconv","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_visit, id_campaign as idconv2 FROM tb_campaign_tracker WHERE type_conversion = 'visit' GROUP BY idconv2)cdata2"),"idconv2","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_read, id_campaign as idconv3 FROM tb_campaign_tracker WHERE type_conversion = 'read' GROUP BY idconv3)cdata3"),"idconv3","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_action, id_campaign as idconv4 FROM tb_campaign_tracker WHERE type_conversion = 'action' GROUP BY idconv4)cdata4"),"idconv4","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_acquisition, id_campaign as idconv5 FROM tb_campaign_tracker WHERE type_conversion = 'acquisition' GROUP BY idconv5)cdata5"),"idconv5","=",$this->table.".id_campaign");
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_usage, id_campaign as id_voucher_campaign FROM tb_voucher WHERE status = 'used' GROUP BY id_voucher_campaign)cdata_voucher"),"id_voucher_campaign","=",$this->table.".id_campaign");
		}
		

		$data = $data->wherein($this->table . ".status", array("active"))
			->leftJoin('tb_penerbit', 'tb_penerbit.id_penerbit', '=', 'tb_campaign.id_penerbit')
			->select('tb_penerbit.nama_penerbit', 'tb_campaign.*','joined','running_budget','system_rev','total_reach','total_visit','total_read','total_action','total_acquisition','total_usage')
			->orderBy($short, $shortmode)
			->skip($str)
			->limit($limit)
			//->inRandomOrder()
			->get();
		return $data;
	}

	public function getListCampaignAvailable($login="",$id_category="",$keyword="", $limit=0){
		$today = date("Y-m-d");
		$data = CampaignModel::whereNull("campaign_internal")
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
					->select('tb_penerbit.nama_penerbit', 'tb_campaign.*','joined')
					->leftJoin('tb_penerbit', 'tb_penerbit.id_penerbit', '=', 'tb_campaign.id_penerbit');

		if($id_category!=""){
			$data = $data->join(DB::raw("(SELECT id_campaign FROM tb_campaign_property WHERE property_type='category' AND value='".$id_category."' AND tb_campaign_property.status = 'active' GROUP BY id_campaign)cat"),"cat.id_campaign","=",$this->table.".id_campaign");
		}

		if($login!=""){
			$id_user 	= $login->id_user;
			$data 		= $data->leftjoin(DB::raw("(SELECT unique_link as joined, id_campaign as idc FROM tb_campaign_unique_link WHERE id_user = '$id_user')ul"),"ul.idc","=",$this->table.".id_campaign");
		}else{
			$id_user  = 0;
			$data 		= $data->leftjoin(DB::raw("(SELECT unique_link as joined, id_campaign as idc FROM tb_campaign_unique_link WHERE id_user = '$id_user')ul"),"ul.idc","=",$this->table.".id_campaign");
		}

		if($limit!=0){
			$data 		= $data->limit($limit)->skip(0);
		}

		$data = $data->orderBy($this->table.".start_date", "DESC")->get();
		return $data;
	}

	public function getListLast10Campaigns($login=""){
		$data = CampaignModel::wherein($this->table . ".status", array("active"))
			->select('tb_penerbit.nama_penerbit', 'tb_campaign.*','joined',DB::raw("IFNULL(running_budget,0) as running_budget"))
			->leftJoin('tb_penerbit', 'tb_penerbit.id_penerbit', '=', 'tb_campaign.id_penerbit');

		if($login!=""){
			$id_user 	= $login->id_user;
			$data 		= $data->leftjoin(DB::raw("(SELECT unique_link as joined, id_campaign as idc FROM tb_campaign_unique_link WHERE id_user = '$id_user')ul"),"ul.idc","=",$this->table.".id_campaign");
		}else{
			$id_user  = 0;
			$data 		= $data->leftjoin(DB::raw("(SELECT COUNT(*) as joined, id_campaign as idc FROM tb_campaign_unique_link GROUP BY id_campaign)ul"),"ul.idc","=",$this->table.".id_campaign");
		}

		$data = $data->join(DB::raw("(SELECT SUM(commission+fee) as running_budget, id_campaign as idcc FROM tb_campaign_tracker GROUP BY idcc)bd"),"idcc","=",$this->table.".id_campaign");
		$data = $data->orderBy($this->table.".time_created", "DESC")->skip(0)->limit(10)->get();
		return $data;
	}


	public function getDetailCampaign($base,$id)
	{
		$data = CampaignModel::where("status", "active")
			->leftjoin(DB::raw("(SELECT nama_penerbit, kode_penerbit, id_penerbit, id_sektor_industri,longitude, latitude FROM tb_penerbit)pnb"), "pnb.id_penerbit", "=", $this->table . ".id_penerbit")
			->leftjoin(DB::raw("(SELECT nama_sektor_industri, id_sektor_industri as isi FROM tb_sektor_industri)tsi"), "tsi.isi", "=", "pnb.id_sektor_industri")
			->leftjoin(DB::raw("(SELECT SUM(quantity) as saham_terjual, SUM(harga_beli*quantity) as total_terpenuhi, COUNT(tb_cart.id_cart) as slot_terpakai, id_campaign as idc FROM tb_cart_detail,tb_cart WHERE tb_cart.id_cart = tb_cart_detail.id_cart AND tb_cart.status = 'paid' GROUP BY id_campaign)tcd"), "tcd.idc", "=", $this->table . ".id_campaign")
			->where($this->table.".id_campaign", $id)
			->first();
		return $data;
	}

	public function insertData($data)
	{
		$result = CampaignModel::create($data);
		return $result;
	}

	public function getDetail($id)
	{
		$data = CampaignModel::find($id);
		return $data;
	}

	public function updateData($data)
	{
		$result = CampaignModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id)
	{
		DB::select(DB::raw("DELETE FROM tb_department_previlege WHERE id_method = $id"));
		$result = CampaignModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
	public function getAllMethods()
	{
		return CampaignModel::get();
	}

	public function getMethods($id_department)
	{
		return CampaignModel::join(DB::raw("(SELECT * FROM tb_module)tb_module"), "tb_module.id_module", "=", $this->table . ".id_module")
			->leftjoin(DB::raw("(SELECT COUNT(*) as granted, id_method FROM tb_department_previlege WHERE id_department = '" . $id_department . "' GROUP BY id_method)tb_dp"), "tb_dp.id_method", "=", $this->table . ".id_method")
			->join(DB::raw("(SELECT * FROM tb_module_department WHERE id_department = '" . $id_department . "')tb_mdp"), "tb_mdp.id_module", "=", "tb_module.id_module")
			->select($this->table . ".*", "tb_module.module_name", "tb_dp.granted")
			->orderBy("tb_module.module_name", "ASC")
			->get();
	}

	public function getMethodsByUser($id_department, $id_user)
	{
		return CampaignModel::join(DB::raw("(SELECT * FROM tb_module)tb_module"), "tb_module.id_module", "=", $this->table . ".id_module")
			->leftjoin(DB::raw("(SELECT COUNT(*) as granted, id_method FROM tb_department_previlege WHERE id_department = '" . $id_department . "' GROUP BY id_method)tb_dp"), "tb_dp.id_method", "=", $this->table . ".id_method")
			->leftjoin(DB::raw("(SELECT COUNT(*) as superungranted, id_method FROM tb_user_restricted WHERE id_user = '" . $id_user . "' GROUP BY id_method)restricted"), "restricted.id_method", "=", $this->table . ".id_method")
			->join(DB::raw("(SELECT * FROM tb_module_department WHERE id_department = '" . $id_department . "')tb_mdp"), "tb_mdp.id_module", "=", "tb_module.id_module")
			->select($this->table . ".*", "tb_module.module_name", "tb_dp.granted", "restricted.superungranted")
			->orderBy("tb_module.module_name", "ASC")
			->orderBy("tb_previlege_method.method", "ASC")
			->get();
	}

	public function createCode(){
		$data  = CampaignModel::where("campaign_link","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

		if($data->count()>0){
			$data 			= $data->toArray()[0];
			$transnumb  = explode($this->prestring,$data['campaign_link']);
			$number     = intval($transnumb[1]);
			$nextnumber = $number+1;
			return $this->formattransaction($this->prestring,$nextnumber);
		}else{
			return $this->formattransaction($this->prestring,1);
		}
	}

	public function formattransaction($prestring, $number){
		$lengthofnumber = strlen(strval($number));
		$lengthrest     = $this->digittransaction - $lengthofnumber;
		if($lengthrest>0){
			$transnumb = strval($number);
			for($i=0;$i<$lengthrest;$i++){
				$transnumb = "0".$transnumb;
			}
			$transnumb = $prestring.$transnumb;
			return $transnumb;
		}else{
			return $prestring.$number;
		}
	}
}
