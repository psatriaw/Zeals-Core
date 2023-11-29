<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class SettingModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_setting';
	protected $primaryKey   = 'id_setting';
	protected $fillable     = ['id_setting','code_setting','setting_value', 'last_update','description','id_order'];

	public function getData() {
		$data = SettingModel::orderBy("id_order","ASC")->get();

		return $data;
	}

    public function getFeeType() {
        $fee_type = SettingModel::where('code_setting', 'fee_type')->first();

        // beli saham
        $fee_beli_saham_type = SettingModel::where('code_setting', 'fee_beli_saham_type')->first();
        $fee_beli_saham_persen = SettingModel::where('code_setting', 'fee_beli_saham_persen')->first();
        $fee_beli_saham_value = SettingModel::where('code_setting', 'fee_beli_saham_value')->first();
        $pajak_beli_saham_type = SettingModel::where('code_setting', 'pajak_beli_saham_type')->first();
        $pajak_beli_saham_persen = SettingModel::where('code_setting', 'pajak_beli_saham_persen')->first();
        $pajak_beli_saham_value = SettingModel::where('code_setting', 'pajak_beli_saham_value')->first();

        // release deviden
        $fee_release_deviden_type = SettingModel::where('code_setting', 'fee_release_deviden_type')->first();
        $fee_release_deviden_persen = SettingModel::where('code_setting', 'fee_release_deviden_persen')->first();
        $fee_release_deviden_value = SettingModel::where('code_setting', 'fee_release_deviden_value')->first();
        $pajak_release_deviden_type = SettingModel::where('code_setting', 'pajak_release_deviden_type')->first();
        $pajak_release_deviden_persen = SettingModel::where('code_setting', 'pajak_release_deviden_persen')->first();
        $pajak_release_deviden_value = SettingModel::where('code_setting', 'pajak_release_deviden_value')->first();

        // topup
        $fee_topup_type = SettingModel::where('code_setting', 'fee_topup_type')->first();
        $fee_topup_persen = SettingModel::where('code_setting', 'fee_topup_persen')->first();
        $fee_topup_value = SettingModel::where('code_setting', 'fee_topup_value')->first();
        $pajak_topup_type = SettingModel::where('code_setting', 'pajak_topup_type')->first();
        $pajak_topup_persen = SettingModel::where('code_setting', 'pajak_topup_persen')->first();
        $pajak_topup_value = SettingModel::where('code_setting', 'pajak_topup_value')->first();

        // pencairan
        $fee_pencairan_type = SettingModel::where('code_setting', 'fee_pencairan_type')->first();
        $fee_pencairan_persen = SettingModel::where('code_setting', 'fee_pencairan_persen')->first();
        $fee_pencairan_value = SettingModel::where('code_setting', 'fee_pencairan_value')->first();
        $pajak_pencairan_type = SettingModel::where('code_setting', 'pajak_pencairan_type')->first();
        $pajak_pencairan_persen = SettingModel::where('code_setting', 'pajak_pencairan_persen')->first();
        $pajak_pencairan_value = SettingModel::where('code_setting', 'pajak_pencairan_value')->first();

        // pendanaan
        $fee_release_pendanaan_type = SettingModel::where('code_setting', 'fee_release_pendanaan_type')->first();
        $fee_release_pendanaan_persen = SettingModel::where('code_setting', 'fee_release_pendanaan_persen')->first();
        $fee_release_pendanaan_value = SettingModel::where('code_setting', 'fee_release_pendanaan_value')->first();
        $pajak_release_pendanaan_type = SettingModel::where('code_setting', 'pajak_release_pendanaan_type')->first();
        $pajak_release_pendanaan_persen = SettingModel::where('code_setting', 'pajak_release_pendanaan_persen')->first();
        $pajak_release_pendanaan_value = SettingModel::where('code_setting', 'pajak_release_pendanaan_value')->first();



        $data = new stdClass;
        $data->fee_type = $fee_type;


        // beli saham
        $data->fee_beli_saham_type = $fee_beli_saham_type;
        $data->fee_beli_saham_persen = $fee_beli_saham_persen;
        $data->fee_beli_saham_value = $fee_beli_saham_value;

        $data->pajak_beli_saham_type = $pajak_beli_saham_type;
        $data->pajak_beli_saham_persen = $pajak_beli_saham_persen;
        $data->pajak_beli_saham_value = $pajak_beli_saham_value;

        // release deviden
        $data->fee_release_deviden_type = $fee_release_deviden_type;
        $data->fee_release_deviden_persen = $fee_release_deviden_persen;
        $data->fee_release_deviden_value = $fee_release_deviden_value;

        $data->pajak_release_deviden_type = $pajak_release_deviden_type;
        $data->pajak_release_deviden_persen = $pajak_release_deviden_persen;
        $data->pajak_release_deviden_value = $pajak_release_deviden_value;

        // topup
        $data->fee_topup_type = $fee_topup_type;
        $data->fee_topup_persen = $fee_topup_persen;
        $data->fee_topup_value = $fee_topup_value;

        $data->pajak_topup_type = $pajak_topup_type;
        $data->pajak_topup_persen = $pajak_topup_persen;
        $data->pajak_topup_value = $pajak_topup_value;

        // topup
        $data->fee_pencairan_type = $fee_pencairan_type;
        $data->fee_pencairan_persen = $fee_pencairan_persen;
        $data->fee_pencairan_value = $fee_pencairan_value;

        $data->pajak_pencairan_type = $pajak_pencairan_type;
        $data->pajak_pencairan_persen = $pajak_pencairan_persen;
        $data->pajak_pencairan_value = $pajak_pencairan_value;

        // topup
        $data->fee_release_pendanaan_type = $fee_release_pendanaan_type;
        $data->fee_release_pendanaan_persen = $fee_release_pendanaan_persen;
        $data->fee_release_pendanaan_value = $fee_release_pendanaan_value;

        $data->pajak_release_pendanaan_type = $pajak_release_pendanaan_type;
        $data->pajak_release_pendanaan_persen = $pajak_release_pendanaan_persen;
        $data->pajak_release_pendanaan_value = $pajak_release_pendanaan_value;

        // dd($data);

        return $data;
    }

	public function countDataSetting($keyword) {
		$data = DB::table($this->table)
						->where('setting_value', 'LIKE', '%'.$keyword.'%')
						->orWhere('description', 'LIKE', '%'.$keyword.'%')
						->orderBy($this->primaryKey, 'desc')
						->count();

		return $data;
	}

	public function getDetail($id) {
		$data = SettingModel::where('code_setting',$id);
		return $data;
	}

	public function insertData($data){
  		$result = DB::table($this->table)->insert($data);
  		return $result;
  	}

	public function updateData($data){
  		$result = SettingModel::where("code_setting",$data['code_setting'])->update($data);
  		return $result;
  	}

  	public function deleteData($id){
  		$result = DB::table($this->table)->where($this->primaryKey, '=', $id)->delete();
  		return $result;
  	}

		public function getSettingVal($id_setting){
      $data = SettingModel::where('code_setting',$id_setting)->first();

			if($data){
				return $data->setting_value;
			}else{
				return "";
			}
		}
}
