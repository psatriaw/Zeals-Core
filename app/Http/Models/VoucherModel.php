<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class VoucherModel extends Model {
	public $timestamps      = false;
	const CREATED_AT        = 'date_created';
	const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_voucher';
	protected $primaryKey   = 'id_voucher';
	protected $fillable     = ['id_voucher', 'status','id_campaign','id_tracker','time_created','status','last_update','id_outlet_usage','time_usage','voucher_code','optin_name','optin_email','optin_phone','optin_address','additional_1','additional_2','disclaimer','optin_source','optin_other_source','optin_dob','optin_institution','optin_institution_name','optin_institution_division'];

	public function getAllVoucherUsage($id_campaign,$id_outlet_usage="",$status="all"){
		$data =  VoucherModel::where("id_campaign",$id_campaign)
						->orderBy("time_usage","DESC");

		if($status=="used"){
			$data = $data->where("status","used");
		}else{
			$data = $data->where("status","used")
							->orWhere(function($query){
									$query->where("status","active");
									$query->WhereNotNull("optin_email");
							});
		}

			$data = $data->leftjoin(DB::raw("(SELECT outlet_name, outlet_address,id_outlet FROM tb_outlet)ot"),"ot.id_outlet","=",$this->table.".id_outlet_usage")
						->leftjoin(DB::raw("(SELECT ip, city, id_tracker, first_name as affiliator_name, referrer FROM tb_campaign_tracker, tb_user WHERE tb_campaign_tracker.id_user = tb_user.id_user)tc"),"tc.id_tracker","=",$this->table.".id_tracker");

				if($id_outlet_usage!=""){
					$data = $data->where("id_outlet_usage",$id_outlet_usage);
				}

				$data = $data->get();
		return $data;
	}

	public function checkRecordWithEmail($email,$id_outlet="",$id_campaign=""){
		if($id_outlet!=""){
				return VoucherModel::where("optin_email",$email)->where("id_outlet_usage",$id_outlet)->get()->count();
		}else{
				return VoucherModel::where("optin_email",$email)->where("id_campaign",$id_campaign)->get()->count();
		}
	}

	public function getTotalVoucherByTime($status="",$rangemin="",$rangemax="", $id_campaign="", $login=""){
		$data = VoucherModel::orderBy("time_created","DESC");

		if($rangemin!="" && $rangemax!=""){
			$min  = $rangemin;
			$max 	= $rangemax;

			$data = $data->whereBetween("time_created",[$min,$max]);
		}

		if($status!="" && $status!='active'){
			$data = $data->where("status",$status);
		}

		if($id_campaign!=""){
			$data = $data->where("id_campaign",$id_campaign);
		}

		if($login){
			$data = $data->where("id_user",$login->id_user);
		}

		return $data->get()->count();
	}

	public function getTotalVoucher($status="",$rangemin="",$rangemax="", $id_campaign="", $login=""){
		$now 	= time();

		$data = VoucherModel::orderBy("time_created","DESC");

		if($rangemin!="" && $rangemax!=""){
			$min  = $now - $rangemax;
			$max 	= $now - $rangemin;

			$data = $data->whereBetween("time_created",[$min,$max]);
		}

		if($status!="" && $status!='active'){
			$data = $data->where("status",$status);
		}

		if($id_campaign!=""){
			$data = $data->where("id_campaign",$id_campaign);
		}

		if($login){
			$data = $data->where("id_user",$login->id_user);
		}

		return $data->get()->count();
	}

	public function getTotalVoucherOnOutlet($id_outlet){
		return VoucherModel::where("status","used")->where("id_outlet_usage",$id_outlet)->count();
	}

	public function getTotalVoucherOnOutletTotay($id_outlet){
		$todaymin = strtotime(date("Y-m-d 00:00:00"));
		$todaymax = strtotime(date("Y-m-d 23:59:59"));
		return VoucherModel::where("status","used")->where("id_outlet_usage",$id_outlet)->whereBetween("time_usage",[$todaymin, $todaymax])->count();
	}

  public function checkFriendship($id_author, $id_target){
    $data = VoucherModel::where("id_author",$id_author)
            ->where("id_target",$id_target)
            ->count();
    if($data){
      return 1;
    }else{
      return 0;
    }
  }

  public function insertData($data){
		$result = VoucherModel::create($data)->id_voucher;
		return $result;
  }

	public function updateData($data){
		$result = VoucherModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

  public function deleteData($data){
    $statement  = DB::table($this->table);
    foreach ($data as $key => $value) {
      $statement =  $statement->where($key,$value);
    }
    return $hasil = $statement->delete();
  }

	public function getDetailByCode($code){
		return VoucherModel::where("voucher_code",$code)->where("status","active")->first();
	}

	public function createCode(){
		$ketemu = true;
		$html 	= "";
		do{
			$html 	= "";
			$abjad 	= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$long 	= strlen($abjad);

			for ($i=0; $i < 8; $i++) {
				$html = @$html.substr($abjad,rand(0,$long-1),1);
			}

			$check = VoucherModel::where("voucher_code",$html)->first();
			if($check==1){
				$ketemu = true;
			}
		}while($ketemu==false);

		return $html;
	}
}
