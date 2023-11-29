<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ReportModel extends Model {
    public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

    protected $table 	      = 'tb_penerbit_report';
    protected $primaryKey   = 'id_report';
    protected $fillable     = ['id_report', 'id_campaign','id_penerbit','report_date','status','time_created','last_update','report_code','file_path','catatan','report_month','report_year','id_user'];
    protected $prestring 				= "RP";
  	protected $digittransaction = 8;

    public function getReports($id_campaign,$url){
        return ReportModel::select("*",DB::raw("CONCAT('$url','/',file_path) as download_url"))->wherein("status",array("approved"))->where("id_campaign",$id_campaign)->orderBy("id_report","DESC")->get();
    }

    public function getData($str, $limit, $keyword, $short, $shortmode){
        $data = ReportModel::where(function($query) use ($keyword){
            if($keyword!=""){
                $query->where("brand_name","like","%".$keyword."%");
                $query->orwhere("brand_code","like","%".$keyword."%");
                $query->orwhere("brand_permalink","like","%".$keyword."%");
            }
        })
            ->wherein("status", array("active","inactive"))
            ->orderBy($short, $shortmode)
            ->skip($str)
            ->limit($limit)
            ->get();
        return $data;
    }

    public function countData($keyword){
        $data = ReportModel::where(function($query) use ($keyword){
            if($keyword!=""){
                $query->where("brand_name","like","%".$keyword."%");
                $query->orwhere("brand_code","like","%".$keyword."%");
                $query->orwhere("brand_permalink","like","%".$keyword."%");
            }
        })
            ->wherein("status", array("active","inactive"))
            ->count();
        return $data;
    }

    public function getDetailBrand($id_brand, $id_user){
        $data = ReportModel::where($this->primaryKey,$id_brand)
            ->leftjoin(DB::raw("(SELECT COUNT(id_product) as total_post, id_brand as idb FROM ".DB::getTablePrefix()."product GROUP BY idb) AS ".DB::getTablePrefix()."tbp"),"tbp.idb","=",$this->table.".".$this->primaryKey)
            ->leftjoin(DB::raw("(SELECT COUNT(id_friendship) as total_follower, id_target FROM ".DB::getTablePrefix()."friendship_brand WHERE status = 'accepted' GROUP BY id_target) AS ".DB::getTablePrefix()."tffo"),"tffo.id_target","=",$this->table.".".$this->primaryKey)
            ->leftjoin(DB::raw("(SELECT COUNT(id_friendship) as total_following, id_author FROM ".DB::getTablePrefix()."friendship_brand GROUP BY id_author) AS ".DB::getTablePrefix()."tffb"),"tffb.id_author","=",$this->table.".".$this->primaryKey)
            ->leftjoin(DB::raw("(SELECT id_friendship as follow_current_brand, status as status_following,id_target as idt FROM ".DB::getTablePrefix()."friendship WHERE id_author = '$id_user') AS ".DB::getTablePrefix()."chekfollow"),"chekfollow.idt","=",$this->table.".".$this->primaryKey)
            ->get();
        return $data;
    }

    public function getDetail($id) {
        $data = ReportModel::find($id);
        return $data;
    }

    public function dataproducts($limit, $id_product, $base,$id_user,$moderand=""){
        $data =  $this->hasMany(ReportModel::class,"id_brand");

        if($id_product!=0){
            $data = $data->where("id_product",">",$id_product);
        }else{
            $tambahan = "";
        }

        $data = $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_like, id_product as idptbl FROM ".DB::getTablePrefix()."like_product GROUP BY id_product) AS ".DB::getTablePrefix()."tbl"),"tbl.idptbl","=","product.id_product")
            ->leftjoin(DB::raw("(SELECT id_like as loved, id_product as idpliked FROM ".DB::getTablePrefix()."like_product WHERE author = '$id_user') AS ".DB::getTablePrefix()."tblike"),"tblike.idpliked","=","product.id_product")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_comment, id_product as idptbc FROM ".DB::getTablePrefix()."comment_product GROUP BY id_product) AS ".DB::getTablePrefix()."tbc"),"tbc.idptbc","=","product.id_product")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_share, id_product as ids FROM ".DB::getTablePrefix()."share_product GROUP BY id_product) AS ".DB::getTablePrefix()."tbs"),"tbs.ids","=","product.id_product");

        $data = $data->join(DB::raw("(SELECT brand_name, id_brand, brand_avatar FROM ".DB::getTablePrefix()."brand) AS ".DB::getTablePrefix()."brd"),"brd.id_brand","=","product.id_brand");
        $data = $data->leftjoin(DB::raw("(SELECT GROUP_CONCAT(CONCAT('".$base."/',thumbnail) SEPARATOR '#') AS paths, id_product as idpp FROM ".DB::getTablePrefix()."photo,".DB::getTablePrefix()."product_detail WHERE ".DB::getTablePrefix()."photo.id_photo = ".DB::getTablePrefix()."product_detail.value AND ".DB::getTablePrefix()."product_detail.status = 'active' GROUP BY idpp) AS ".DB::getTablePrefix()."tbph"),"tbph.idpp","=","product.id_product");
        $data = $data->limit($limit);

		if($moderand=="random"){
			$data = $data->orderByRaw('RAND()');
		}else{
			$data = $data->orderBy("id_product","DESC");
		}

		return $data;
	}

	public function getBrandsMobile($id_user, $brand = "", $keyword = ""){
		$data = ReportModel::orderBy("brand_name","ASC")
				->leftjoin(DB::raw("(SELECT COUNT(id_product) as total_post, id_brand as idb FROM ".DB::getTablePrefix()."product GROUP BY idb) AS ".DB::getTablePrefix()."tbp"),"tbp.idb","=",$this->table.".".$this->primaryKey)
				->leftjoin(DB::raw("(SELECT COUNT(id_friendship) as total_follower, id_target FROM ".DB::getTablePrefix()."friendship_brand WHERE status = 'accepted' GROUP BY id_target) AS ".DB::getTablePrefix()."tffo"),"tffo.id_target","=",$this->table.".".$this->primaryKey)
				->leftjoin(DB::raw("(SELECT COUNT(id_friendship) as total_following, id_author FROM ".DB::getTablePrefix()."friendship_brand GROUP BY id_author) AS ".DB::getTablePrefix()."tffb"),"tffb.id_author","=",$this->table.".".$this->primaryKey)
				->leftjoin(DB::raw("(SELECT id_friendship as follow_current_brand, status as status_following,id_target as idt FROM ".DB::getTablePrefix()."friendship WHERE id_author = '$id_user') AS ".DB::getTablePrefix()."chekfollow"),"chekfollow.idt","=",$this->table.".".$this->primaryKey);

		if($brand!=""){
				$brands = explode("#",$brand);
				$data = $data->whereNotIn("id_brand",$brands);
		}

		if($keyword!=""){
			$data = $data->where("brand_name","like","%".$keyword."%");
		}
		$data = $data->where("brand.status","active");
		$data = $data->get();

		return $data;
	}

	public function getOtherBrandProducts($id_brand,$limit, $id_product, $base,$id_user){
		$data = ReportModel::find($id_brand)->dataproducts($limit, $id_product, $base, $id_user,"random")->get();
		return $data;
	}

	public function getBrandProducts($id_brand,$limit, $id_product, $base,$id_user){
		$data = ReportModel::find($id_brand)->dataproducts($limit, $id_product, $base, $id_user)->get();
		return $data;
	}

	public function insertData($data){
		$result = ReportModel::create($data)->id_report;
		return $result;
    }

    public function updateData($data){
        $result = ReportModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
        return $result;
    }

    public function createCode(){
  		$data  = ReportModel::where("report_code","like","%".$this->prestring."%")->orderBy($this->primaryKey,"DESC")->get();

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
