<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CampaignPropertyModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_campaign_property';
	protected $primaryKey   = 'id_property';
	protected $fillable     = ['id_property', 'id_campaign','property_type','time_created','last_update','status','value'];

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = CampaignPropertyModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("penggajian_code","like","%".$keyword."%");
								$query->orwhere("bulan","like","%".$keyword."%");
								$query->orwhere("tahun","like","%".$keyword."%");
                $query->orwhere("first_name","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("pending","released"))
						->select($this->table.".*","first_name")
						->leftjoin(DB::raw("(SELECT first_name,id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = CampaignPropertyModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("penggajian_code","like","%".$keyword."%");
								$query->orwhere("bulan","like","%".$keyword."%");
								$query->orwhere("tahun","like","%".$keyword."%");
                $query->orwhere("first_name","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("pending","released"))
            ->leftjoin(DB::raw("(SELECT first_name,id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
						->count();
		return $data;
	}

	public function getListGajiPegawai($id_penggajian,$pegawai){
		$data = CampaignPropertyModel::find($id_penggajian)->wherein($pegawai);
	}

	public function checkRecord($id_penggajian,$id_user,$tgl_present){
		$data = CampaignPropertyModel::where("id_penggajian",$id_penggajian)->where("id_user",$id_user)->where("tgl_present",$tgl_present);
		if($data->count()){
			$data = $data->first();
			return $data->id_penggajian_record;
		}else{
			return 0;
		}
	}

	public function getDetail($id) {
		$data = CampaignPropertyModel::where($this->primaryKey,$id)
						->leftjoin(DB::raw("(SELECT first_name,id_user FROM tb_user)user"),"user.id_user","=",$this->table.".author")
						->get()->first();
		return $data;
	}

	public function getTotalTransaction($id_cart){
		$data = CampaignPropertyModel::where($this->table.".".$this->primaryKey,$id_cart)
						->join(DB::raw("(SELECT SUM((quantity*item_price)-item_discount) as total,id_cart FROM tb_cart_detail GROUP BY id_cart)tcd"),"tcd.id_cart","=",$this->table.".id_cart")
						->get();
		if($data->count()){
			$data = $data->first();
			return $data->total;
		}else{
			return 0;
		}
	}

	public function getRecordOfUser($id_user, $id_penggajian){
		$data = CampaignPropertyModel::where("id_user",$id_user)
						->where("id_penggajian",$id_penggajian)
						->orderBy("tgl_present","ASC")
						->get();
		if($data->count()){
			return $data;
		}else{
			return 0;
		}
	}

  public function getTotalRecord($id_reff,$id_penggajian){
    return CampaignPropertyModel::where("id_user",$id_reff)->where("id_penggajian",$id_penggajian)->count();
  }

  public function getTotalRecordLemburJam($id_reff,$id_penggajian, $jam_masuk, $jam_keluar){
    $data = CampaignPropertyModel::where("id_user",$id_reff)
            ->where("id_penggajian",$id_penggajian)
            ->get();
    if($data->count()){
      $lembur = 0;
      foreach ($data as $key => $value) {
        if($value->time_out>strtotime($value->tgl_present." ".$jam_keluar)){
          $lembur = $lembur+ ($value->time_out - (strtotime($value->tgl_present." ".$jam_keluar)));
        }
      }
    }else{
      $lembur = 0;
    }

    return $lembur;
  }

  public function getTotalRecordLembur($id_reff,$id_penggajian, $jam_masuk, $jam_keluar){
    $data = CampaignPropertyModel::where("id_user",$id_reff)
            ->where("id_penggajian",$id_penggajian)
            ->get();
    if($data->count()){
      $lembur = 0;
      foreach ($data as $key => $value) {
        if($value->time_out>strtotime($value->tgl_present." ".$jam_keluar)){
          $lembur++;
        }
      }
    }else{
      $lembur = 0;
    }

    return $lembur;
  }

	public function getTotalRecordTelat($id_reff,$id_penggajian, $jam_masuk, $jam_keluar){
    $data = CampaignPropertyModel::where("id_user",$id_reff)
            ->where("id_penggajian",$id_penggajian)
            ->get();
    if($data->count()){
      $telat = 0;
      foreach ($data as $key => $value) {
        if($value->time_in>strtotime($value->tgl_present." ".$jam_masuk)){
          $telat = $telat + ($value->time_in - (strtotime($value->tgl_present." ".$jam_masuk)));
        }
      }
    }else{
      $telat = 0;
    }

    return $telat;
  }

  public function getTotalRecordTerlambatJam($id_reff,$id_penggajian, $jam_masuk, $jam_keluar){
    $data = CampaignPropertyModel::where("id_user",$id_reff)
            ->where("id_penggajian",$id_penggajian)
            ->get();
    if($data->count()){
      $telat = 0;
      foreach ($data as $key => $value) {
        if($value->time_in>strtotime($value->tgl_present." ".$jam_masuk)){
          $telat = $telat + ($value->time_in - strtotime($value->tgl_present." ".$jam_masuk));
        }
      }
    }else{
      $telat = 0;
    }

    return $telat;
  }

	public function getTotalRecordTerlambat($id_reff,$id_penggajian, $jam_masuk, $jam_keluar){
    $data = CampaignPropertyModel::where("id_user",$id_reff)
            ->where("id_penggajian",$id_penggajian)
            ->get();
    if($data->count()){
      $telat = 0;
      foreach ($data as $key => $value) {
        if($value->time_in>strtotime($value->tgl_present." ".$jam_masuk)){
          $telat++;
        }
      }
    }else{
      $telat = 0;
    }

    return $telat;
  }

	public function items(){
		return $this->hasMany(CartDetailModel::class,$this->primaryKey)
						->join("tb_product","tb_product.id_product","=","tb_cart_detail.id_product")
						->where("tb_cart_detail.status","active");
	}

	public function getItems($id){
		$data = CampaignPropertyModel::find($id)->items()->get();
		if($data->count()){
			return $data;
		}else{
			return 0;
		}
	}

	public function itemsmrp(){
		return $this->hasMany(CartDetailModel::class,$this->primaryKey)
						->join("tb_product","tb_product.id_product","=","tb_cart_detail.id_product")
						->leftjoin(DB::raw("(SELECT GROUP_CONCAT(CONCAT(qty,'_',material_price,'_',quantity,'_', material_code, '_', material_name,'_',tb_fremilt_mrp.id_material,'_',material_unit) SEPARATOR '-') as mrp_item, tb_fremilt_mrp.id_product FROM tb_fremilt_mrp, tb_fremilt_material WHERE tb_fremilt_mrp.status = 'active' AND tb_fremilt_mrp.id_material = tb_fremilt_material.id_material AND tb_fremilt_material.status = 'active' GROUP BY id_product)mrp"),"mrp.id_product","=","tb_cart_detail.id_product")
						->leftjoin(DB::raw("(SELECT production_completion, last_update as completion_terakhir, id_cart_detail as icd, id_production FROM tb_fremilt_production) tfp"),"tfp.icd","=","tb_cart_detail.id_cart_detail")
						->where("tb_cart_detail.status","active")
						->orderBy("tb_cart_detail.id_cart_detail","ASC");
	}

	public function getPropertyBy($property_type, $id_campaign){
		$data = CampaignPropertyModel::where("property_type",$property_type)->where("id_campaign",$id_campaign)->where("status","active");

		switch ($property_type) {
			case 'location':
				$data = $data->join(DB::raw("(SELECT CONCAT(namakab,' (',IF(SUBSTRING(kodekab,4)<50,'KABUPATEN','KOTA'),')') as namakab, id_wilayah FROM tb_wilayah)wil"),"wil.id_wilayah","=",$this->table.".value");
			break;

			case 'category':
				$data = $data->join(DB::raw("(SELECT nama_sektor_industri, id_sektor_industri FROM tb_sektor_industri)sek"),"sek.id_sektor_industri","=",$this->table.".value");
			break;
		}

		$data = $data->get();

		if($data->count()){
			return $data;
		}else{
			return 0;
		}
	}

	public function removeAllData($id_campaign){
		$result = CampaignPropertyModel::where("id_campaign",$id_campaign)->update(array("last_update" => time(),"status" => 'deleted'));
		return $result;
	}

	public function insertData($data){
		$result = CampaignPropertyModel::create($data)->id_property;
		return $result;
  }

	public function updateData($data){
		$result = CampaignPropertyModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}


}
