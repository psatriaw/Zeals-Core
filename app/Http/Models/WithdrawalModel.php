<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WithdrawalModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'date_created';
  //const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_withdrawal';
	protected $primaryKey   = 'id_withdrawal';
	protected $fillable     = ['id_withdrawal', 'time_created','last_update','total_pencairan','status','nama_bank','nama_pemilik_rekening','nomor_rekening','id_user','callback_response','trx_status','withdrawal_code'];
	protected $prestring        = "WTL";
	protected $digittransaction = 10;

	public function getResume($keyword, $id_penerbit=""){
		$data = WithdrawalModel::whereNotin("status", array("deleted"));

		switch($keyword){
			case "sum-request":

			break;

			case "sum-outstanding":
				$data = $data->where("status","pending")->sum("total_pencairan");
				return number_format($data,0,",",".");
			break;

			case "sum-new-request":
				$data = $data->where("status","pending");
			break;
		}

		$data 	= $data->get()->count();

		return number_format($data,0,",",".");
	}

	public function getDetailByCode($withdrawal_code){
		return WithdrawalModel::where("withdrawal_code",$withdrawal_code)->first();
	}

	public function getWithdrawalRequest($login){
		return WithdrawalModel::where("id_user",$login->id_user)->orderBy("time_created","DESC")->get();
	}

	public function checkANyPendingWithdrawal($login){
		return WithdrawalModel::where("id_user",$login->id_user)->where("status","pending")->get()->count();
	}

	public function getDetail($id){
		return WithdrawalModel::where("id_withdrawal",$id)->first();
	}

	public function countTotal($status=""){
		if($status==""){
			return WithdrawalModel::orderBy("id_withdrawal","ASC");
		}else{
			return WithdrawalModel::where("status",$status);
		}
	}

  public function getData($str, $limit, $keyword, $short, $shortmode, $login){
		$data = WithdrawalModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("nama_pemilik_rekening","like","%".$keyword."%");
								$query->orwhere("nama_bank","like","%".$keyword."%");
								$query->orwhere("total_pencairan","like","%".$keyword."%");
								// $query->orwhere("first_name","like","%".$keyword."%");
							}
						})
            ->join('tb_user', 'tb_user.id_user', $this->table.'.id_user')
						->select($this->table.".*", 'tb_user.first_name')
						// ->leftjoin(DB::raw("(SELECT first_name,id_user FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."user"),"user.id_user","=",$this->table.".id_user")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit);

    if($login->id_department!=1){
      $data = $data->where($this->table.".id_user",$login->id_user);
    }

		$data = $data->get();
		return $data;
	}

  public function getTotalWithdrawal($id_shop=""){
    if($id_shop!=""){
      $data = WithdrawalModel::where("id_ref",$id_shop)
              ->select(DB::raw("SUM(total_amount) as total"))
              ->where("status","approved")
              ->groupBy("id_ref");

      if($data->count()){
        $data = $data->first();
        return $data->total;
      }else{
        return 0;
      }
    }else{
      $data = WithdrawalModel::select(DB::raw("SUM(total_amount) as total"))
              ->where("status","approved");

      if($data->count()){
        $data = $data->first();
        return $data->total;
      }else{
        return 0;
      }
    }
  }

	public function countData($keyword, $login){
		$data = WithdrawalModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("first_name","like","%".$keyword."%");
                $query->where("total_pencairan","like","%".$keyword."%");
								$query->orwhere("nama_pemilik_rekening","like","%".$keyword."%");
								$query->orwhere("nama_bank","like","%".$keyword."%");
							}
						})
            ->leftjoin(DB::raw("(SELECT first_name,id_user FROM ".DB::getTablePrefix()."tb_user) AS ".DB::getTablePrefix()."tb_user"),"tb_user.id_user","=",$this->table.".id_user");

    if($login->id_department!=1){
      $data = $data->where($this->table.".id_user",$login->id_user);
    }

		$data = $data->count();
		return $data;
	}

  public function removeData($id){
		$result = WithdrawalModel::find($id)->delete();
		return $result;
  }

  public function insertData($data){
		$result = WithdrawalModel::create($data)->id_withdrawal;
		return $result;
  }

	public function updateData($data){
		$result = WithdrawalModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function createCode()
	{
			$data  = WithdrawalModel::where("withdrawal_code", "like", "%" . $this->prestring . "%")->orderBy($this->primaryKey, "DESC")->get();

			if ($data->count() > 0) {
					$data             = $data->toArray()[0];
					$transnumb  = explode($this->prestring, $data['withdrawal_code']);
					$number     = intval($transnumb[1]);
					$nextnumber = $number + 1;
					return $this->formattransaction($this->prestring, $nextnumber);
			} else {
					return $this->formattransaction($this->prestring, 1);
			}
	}

	public function formattransaction($prestring, $number)
	{
			$lengthofnumber = strlen(strval($number));
			$lengthrest     = $this->digittransaction - $lengthofnumber;
			if ($lengthrest > 0) {
					$transnumb = strval($number);
					for ($i = 0; $i < $lengthrest; $i++) {
							$transnumb = "0" . $transnumb;
					}
					$transnumb = $prestring . $transnumb;
					return $transnumb;
			} else {
					return $prestring . $number;
			}
	}

}
