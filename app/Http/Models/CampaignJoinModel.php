<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CampaignJoinModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_campaign_unique_link';
	protected $primaryKey   = 'id_unique_link';
	protected $fillable     = ['id_unique_link','id_campaign', 'id_user','time_created','last_update','unique_link'];

	public function getPerformanceofAff($id_campaign, $start_date="", $end_date=""){
		if($start_date!=""){
			$start_date = strtotime($start_date." 00:00:00");
			$end_date 	= strtotime($end_date." 23:59:59");

			$data = CampaignJoinModel::where("id_campaign",$id_campaign)
				->leftjoin("tb_user","tb_user.id_user","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'initial'  AND id_campaign = $id_campaign GROUP BY id_user)init"),"init.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_visit,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'visit'  AND id_campaign = $id_campaign GROUP BY id_user)init2"),"init2.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_read,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'read'  AND id_campaign = $id_campaign GROUP BY id_user)init3"),"init3.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_action,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'action'  AND id_campaign = $id_campaign GROUP BY id_user)init4"),"init4.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_acquisition,  SUM(commission) as total_commission, SUM(fee) as total_fee, id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'acquisition'  AND id_campaign = $id_campaign GROUP BY id_user)init5"),"init5.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_created,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)init7"),"init7.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND tb_voucher.status = 'used' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)init6"),"init6.idu","=",$this->table.".id_user")

				->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach_facebook,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%facebook%' AND type_conversion = 'visit'  AND id_campaign = $id_campaign GROUP BY id_user)reachfb"),"reachfb.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach_instagram,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%instagram%' AND type_conversion = 'visit'  AND id_campaign = $id_campaign GROUP BY id_user)reachig"),"reachig.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach_twitter,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%twitter%' AND type_conversion = 'visit'  AND id_campaign = $id_campaign GROUP BY id_user)reachtwitter"),"reachtwitter.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach_google,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%google%' AND type_conversion = 'visit'  AND id_campaign = $id_campaign GROUP BY id_user)reachgoogle"),"reachgoogle.idu","=",$this->table.".id_user")

				->leftjoin(DB::raw("(SELECT COUNT(*) as total_read_facebook,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%facebook%' AND type_conversion = 'read'  AND id_campaign = $id_campaign GROUP BY id_user)readfb"),"readfb.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_read_instagram,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%instagram%' AND type_conversion = 'read'  AND id_campaign = $id_campaign GROUP BY id_user)readig"),"readig.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_read_twitter,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%twitter%' AND type_conversion = 'read'  AND id_campaign = $id_campaign GROUP BY id_user)readtwitter"),"readtwitter.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_read_google,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%google%' AND type_conversion = 'read'  AND id_campaign = $id_campaign GROUP BY id_user)readgoogle"),"readgoogle.idu","=",$this->table.".id_user")

				->leftjoin(DB::raw("(SELECT COUNT(*) as total_action_facebook,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%facebook%' AND type_conversion = 'action'  AND id_campaign = $id_campaign GROUP BY id_user)actfb"),"actfb.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_action_instagram,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%instagram%' AND type_conversion = 'action'  AND id_campaign = $id_campaign GROUP BY id_user)actig"),"actig.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_action_twitter,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%twitter%' AND type_conversion = 'action'  AND id_campaign = $id_campaign GROUP BY id_user)acttwitter"),"acttwitter.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_action_google,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%google%' AND type_conversion = 'action'  AND id_campaign = $id_campaign GROUP BY id_user)actgoogle"),"actgoogle.idu","=",$this->table.".id_user")

				->leftjoin(DB::raw("(SELECT COUNT(*) as total_request_facebook,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%facebook%' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)reqfb"),"reqfb.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_request_instagram,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%instagram%' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)reqig"),"reqig.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_request_twitter,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%twitter%' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)reqtwitt"),"reqtwitt.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_request_google,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%google%' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)reqgoogle"),"reqgoogle.idu","=",$this->table.".id_user")

				->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption_facebook,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%facebook%' AND tb_voucher.status = 'used' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)redfb"),"redfb.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption_instagram,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%instagram%' AND tb_voucher.status = 'used' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)redig"),"redig.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption_twitter,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%twitter%' AND tb_voucher.status = 'used' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)redtwitt"),"redtwitt.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption_google,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%google%' AND tb_voucher.status = 'used' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)redgoogle"),"redgoogle.idu","=",$this->table.".id_user")

				->select("*")
				->orderBy("total_acquisition","DESC")
				->get();	

		}else{
			$data = CampaignJoinModel::where("id_campaign",$id_campaign)
				->leftjoin("tb_user","tb_user.id_user","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach,id_user as idu FROM tb_campaign_tracker WHERE type_conversion = 'initial'  AND id_campaign = $id_campaign GROUP BY id_user)init"),"init.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_visit,id_user as idu FROM tb_campaign_tracker WHERE type_conversion = 'visit'  AND id_campaign = $id_campaign GROUP BY id_user)init2"),"init2.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_read,id_user as idu FROM tb_campaign_tracker WHERE type_conversion = 'read'  AND id_campaign = $id_campaign GROUP BY id_user)init3"),"init3.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_action,id_user as idu FROM tb_campaign_tracker WHERE type_conversion = 'action'  AND id_campaign = $id_campaign GROUP BY id_user)init4"),"init4.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_acquisition, SUM(commission) as total_commission, SUM(fee) as total_fee, id_user as idu FROM tb_campaign_tracker WHERE type_conversion = 'acquisition'  AND id_campaign = $id_campaign GROUP BY id_user)init5"),"init5.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_created,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)init7"),"init7.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND tb_voucher.status = 'used' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)init6"),"init6.idu","=",$this->table.".id_user")

				->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach_facebook,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%facebook%' AND type_conversion = 'visit'  AND id_campaign = $id_campaign GROUP BY id_user)reachfb"),"reachfb.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach_instagram,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%instagram%' AND type_conversion = 'visit'  AND id_campaign = $id_campaign GROUP BY id_user)reachig"),"reachig.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach_twitter,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%twitter%' AND type_conversion = 'visit'  AND id_campaign = $id_campaign GROUP BY id_user)reachtwitter"),"reachtwitter.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach_google,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%google%' AND type_conversion = 'visit'  AND id_campaign = $id_campaign GROUP BY id_user)reachgoogle"),"reachgoogle.idu","=",$this->table.".id_user")

				->leftjoin(DB::raw("(SELECT COUNT(*) as total_read_facebook,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%facebook%' AND type_conversion = 'read'  AND id_campaign = $id_campaign GROUP BY id_user)readfb"),"readfb.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_read_instagram,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%instagram%' AND type_conversion = 'read'  AND id_campaign = $id_campaign GROUP BY id_user)readig"),"readig.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_read_twitter,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%twitter%' AND type_conversion = 'read'  AND id_campaign = $id_campaign GROUP BY id_user)readtwitter"),"readtwitter.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_read_google,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%google%' AND type_conversion = 'read'  AND id_campaign = $id_campaign GROUP BY id_user)readgoogle"),"readgoogle.idu","=",$this->table.".id_user")

				->leftjoin(DB::raw("(SELECT COUNT(*) as total_action_facebook,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%facebook%' AND type_conversion = 'action'  AND id_campaign = $id_campaign GROUP BY id_user)actfb"),"actfb.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_action_instagram,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%instagram%' AND type_conversion = 'action'  AND id_campaign = $id_campaign GROUP BY id_user)actig"),"actig.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_action_twitter,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%twitter%' AND type_conversion = 'action'  AND id_campaign = $id_campaign GROUP BY id_user)acttwitter"),"acttwitter.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_action_google,id_user as idu FROM tb_campaign_tracker WHERE referrer LIKE '%google%' AND type_conversion = 'action'  AND id_campaign = $id_campaign GROUP BY id_user)actgoogle"),"actgoogle.idu","=",$this->table.".id_user")

				->leftjoin(DB::raw("(SELECT COUNT(*) as total_request_facebook,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%facebook%' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)reqfb"),"reqfb.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_request_instagram,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%instagram%' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)reqig"),"reqig.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_request_twitter,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%twitter%' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)reqtwitt"),"reqtwitt.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_request_google,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%google%' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)reqgoogle"),"reqgoogle.idu","=",$this->table.".id_user")

				->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption_facebook,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%facebook%' AND tb_voucher.status = 'used' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)redfb"),"redfb.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption_instagram,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%instagram%' AND tb_voucher.status = 'used' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)redig"),"redig.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption_twitter,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%twitter%' AND tb_voucher.status = 'used' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)redtwitt"),"redtwitt.idu","=",$this->table.".id_user")
				->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption_google,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND referrer LIKE '%google%' AND tb_voucher.status = 'used' AND tb_campaign_tracker.id_campaign = $id_campaign GROUP BY id_user)redgoogle"),"redgoogle.idu","=",$this->table.".id_user")

				->select("*")
				->orderBy("total_acquisition","DESC")
				->get();
		}

		return $data;
	}

	public function getLinkTest($id_campaign){
		$data =  CampaignJoinModel::where("id_user",0)->where("id_campaign",$id_campaign)->first();
		if($data){
			return $data->unique_link;
		}else{
			//create unique link test
			$campaigntestlink = array(
				"time_created"	=> time(),
				"last_update"		=> time(),
				"id_user"				=> 0,
				"id_campaign"		=> $id_campaign,
				"unique_link"		=> $this->createLink()
			);
			$this->insertData($campaigntestlink);
			return $this->getLinkTest($id_campaign);
			//create unique link test
		}
	}

	public function getAVGJoinedProject(){
		$query =  "SELECT AVG(total_data) as nilai_avg FROM (SELECT COUNT(*) as total_data FROM tb_campaign_unique_link GROUP BY id_campaign) tbul";
		$data	 = DB::select($query);
		return $data[0]->nilai_avg;
	}

	public function getPhotos($id_product){
		$data = CampaignJoinModel::find($id_product);
		return $data->photos;
	}

	public function penggunaan(){
		return $this->hasMany(MrpModel::class,"id_product")->where("status","active")->join(DB::raw("(SELECT material_name, material_code, material_price, id_material, material_unit FROM tb_fremilt_material WHERE status = 'active')tfm"),"tfm.id_material","=","tb_fremilt_mrp.id_material");
	}

	public function findProduct($keyword){
		$data = ProductModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("product_name","like","%".$keyword."%");
								$query->orwhere("product_code","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive","available","unavailable"))
						->orderBy("product_name","ASC")
						->get();
		return $data;
	}

	public function getlistproduct(){
		$data = ProductModel::orderBy("product_name","ASC")->get();
		if($data->count()){
			foreach ($data as $key => $value) {
				$option[$value->id_product] = $value->product_name;
			}
		}else{
			$option = array();
		}
		return $option;
	}

	public function getlistmrpproduct($id_product){
		$data = ProductModel::find($id_product);
		return $data->mrps()->get();
	}

	public function photos(){
		return $this->hasMany(ProductDetailModel::class,"id_product")
							->where("tb_product_detail.status","active")
							->join("tb_photo","tb_product_detail.value","=","tb_photo.id_photo");
	}

	public function checkCode($unique_link){

		return CampaignJoinModel::where("unique_link",$unique_link)->first();
	}

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = CampaignJoinModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("product_name","like","%".$keyword."%");
								$query->orwhere("product_code","like","%".$keyword."%");
								$query->orwhere("price","like","%".$keyword."%");
								$query->orwhere("discount","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive","available","unavailable"))
						->select($this->table.".*","hpp")
						//->select($this->table.".*","tb_product_category.category_name")
						->leftjoin(DB::raw("(SELECT SUM(material_price*qty) as hpp, id_product FROM tb_fremilt_mrp, tb_fremilt_material WHERE tb_fremilt_mrp.id_material = tb_fremilt_material.id_material GROUP BY tb_fremilt_mrp.id_product)mrp"),"mrp.id_product","=",$this->table.".id_product")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function createLink(){
		$ketemu = true;
		$html 	= "";
		do{
			$html 	= "";
			$abjad 	= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$long 	= strlen($abjad);

			for ($i=0; $i < 8; $i++) {
				$html = @$html.substr($abjad,rand(0,$long-1),1);
			}

			$check = CampaignJoinModel::where("unique_link",$html)->first();
			if($check==1){
				$ketemu = true;
			}
		}while($ketemu==false);

		return $html;
	}

	public function countData($keyword){
		$data = CampaignJoinModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("product_name","like","%".$keyword."%");
								$query->orwhere("price","like","%".$keyword."%");
								$query->orwhere("discount","like","%".$keyword."%");
								$query->orwhere("product_code","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive","available","unavailable"))
						->count();
		return $data;
	}

	public function isproductJoined($id_user,$id_campaign){
		$result = CampaignJoinModel::where("id_user",$id_user)->where("id_campaign",$id_campaign)->first();
		return $result;
	}

	public function getDetail($id) {
		$data = CampaignJoinModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = CampaignJoinModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = CampaignJoinModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}
}
