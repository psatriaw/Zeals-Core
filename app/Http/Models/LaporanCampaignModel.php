<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanCampaignModel extends Model
{
    //
    public $timestamps      = false;
    const CREATED_AT        = 'date_created';
    const UPDATED_AT        = 'last_update';

    protected $table            = 'tb_penerbit_report';
    protected $primaryKey       = 'id_report';
    protected $fillable         = ['id_report', 'id_campaign', 'time_created', 'last_update', 'report_date', 'id_user', 'status', 'id_penerbit', 'report_month', 'report_year', 'catatan', 'file_path','report_code','profit'];
    protected $prestring 				= "REP";
    protected $digittransaction = 8;


    public function getDataReport($str, $limit, $keyword, $short, $shortmode,$id_penerbit="", $id_campaign="")
    {
        $data = LaporanCampaignModel::where(function ($query) use ($keyword) {
            if ($keyword != "") {
                $query->where("campaign_title", "like", "%" . $keyword . "%");
                $query->orwhere("nama_penerbit", "like", "%" . $keyword . "%");
                $query->orwhere("report_code", "like", "%" . $keyword . "%");
                $query->orwhere($this->table.".status", "like", "%" . $keyword . "%");
            }
        });

        if($id_penerbit!=""){
            $data = $data->where($this->table.".id_penerbit",$id_penerbit);
        }

        if($id_campaign!=""){
          $data = $data->where($this->table.".id_campaign",$id_campaign);
        }

        $data = $data->wherein($this->table . ".status", array("active", "pending", "approved", "rejected"))
            ->leftJoin('tb_user', 'tb_user.id_user', '=', 'tb_penerbit_report.id_user')
            ->leftJoin('tb_campaign', 'tb_campaign.id_campaign', '=', 'tb_penerbit_report.id_campaign')
            ->leftJoin('tb_penerbit', 'tb_penerbit.id_penerbit', '=', 'tb_penerbit_report.id_penerbit')
            // ->select('tb_penerbit.nama_penerbit', 'tb_campaign.*')
            ->select('tb_penerbit_report.*', 'tb_user.first_name', 'tb_campaign.campaign_title', 'tb_penerbit.nama_penerbit')
            ->orderBy($short, $shortmode)
            ->skip($str)
            ->limit($limit)
            ->inRandomOrder()
            ->get();
        return $data;
    }

    public function getLaporanKeuanganCampaign($id_campaign){
      return LaporanCampaignModel::where("id_campaign",$id_campaign)->where("calculated","0")->orderBy("id_report","DESC")->get();
    }

    public function insertData($data){
      return LaporanCampaignModel::create($data)->id_report;
    }

    public function updateData($data)
    {
        $result = LaporanCampaignModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
        return $result;
    }

    public function getDetailCampaignReport($id)
    {
        $data = LaporanCampaignModel::where('id_report', $id)
            ->leftJoin('tb_user', 'tb_user.id_user', '=', 'tb_penerbit_report.id_user')
            ->leftJoin('tb_campaign', 'tb_campaign.id_campaign', '=', 'tb_penerbit_report.id_campaign')
            ->leftJoin('tb_penerbit', 'tb_penerbit.id_penerbit', '=', 'tb_penerbit_report.id_penerbit')
            // ->select('tb_penerbit.nama_penerbit', 'tb_campaign.*')
            ->select('tb_penerbit_report.*', 'tb_user.first_name', 'tb_campaign.campaign_title', 'tb_penerbit.nama_penerbit')
            ->first();
        return $data;
    }

    public function createCode(){
  		$data  = LaporanCampaignModel::where("report_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

  		if($data->count()>0){
  			$data 			= $data->toArray()[0];
  			$transnumb  = explode($this->prestring,$data['report_code']);
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
