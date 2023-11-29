<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PenerbitModel extends Model
{
	public $timestamps      = false;
	const CREATED_AT        = 'time_created';
	const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_penerbit';
	protected $primaryKey   = 'id_penerbit';
	protected $fillable     = ['id_penerbit', 'nama_penerbit', 'kode_penerbit', 'time_created', 'last_update', 'status', 'alamat', 'no_telp', 'siup', 'nib', 'pic_name','id_user', 'pic_telp', 'id_sektor_industri','email','nib_file','pbb_file','neraca_file','pos_file','rab_file','proyeksi_file','photo_video_file','affiliate_id'];

	public function getResume($keyword, $id_penerbit=""){
		$data = PenerbitModel::wherein("status", array("active"));

		switch($keyword){
			case "sum-company":

			break;

			case "sum-average":
				$data = $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_campaign, id_penerbit FROM tb_campaign WHERE status='active' GROUP BY id_penerbit)tot"),"tot.id_penerbit","=",$this->table.".id_penerbit")->avg("total_campaign");
				return number_format($data,2,",",".");
			break;
		}

		$data 	= $data->get()->count();

		return number_format($data,0,",",".");
	}


	public function getListPenerbit($id_penerbit=""){
		if($id_penerbit!=""){
			return PenerbitModel::where("status","active")->where("id_penerbit",$id_penerbit)->pluck("nama_penerbit","id_penerbit");
		}else{
			return PenerbitModel::where("status","active")->pluck("nama_penerbit","id_penerbit");
		}
	}

	public function getDetailByUser($id_user){
		$data = PenerbitModel::where("id_user",$id_user)->where("status","active")->first();
		if($data){
			return $data;
		}else{
			return "";
		}
	}

	public function getPenerbitActive(){
		return PenerbitModel::where("status","active")->pluck("nama_penerbit","id_penerbit");
	}

	public function getActiveUser(){
		return PenerbitModel::where("status","active");
	}

	public function checkUserIsPenerbit($id_user){
		return PenerbitModel::where("id_user",$id_user)->first();
	}

	public function getInActiveUser(){
		return PenerbitModel::where("status","inactive");
	}

	public function countDataLikes(){
		$query = DB::raw("SELECT COUNT(*) as total FROM tb_pra_like");
		$data  = DB::select($query);
		return $data[0]->total;
	}

	public function countDataComments(){
		$query = DB::raw("SELECT  COUNT(*) as total  FROM tb_pra_comment");
		$data  = DB::select($query);
		return $data[0]->total;
	}

	public function getPenerbitPraPemasaran($base, $limit)
	{
		$data = PenerbitModel::where("status", "pending")
			->where("photos","<>","")
			->select($this->table . ".*", DB::raw("IFNULL(total_like,0) as total_like"), DB::raw("IFNULL(total_comment,0) as total_comment"),DB::raw("CONCAT('$base','/',photos) as photos"))
			->leftjoin(DB::raw("(SELECT COUNT(*) AS total_like, id_penerbit as idpp FROM tb_pra_like GROUP BY id_penerbit)tbpl"), "tbpl.idpp", "=", $this->table . ".id_penerbit")
			->leftjoin(DB::raw("(SELECT COUNT(*) AS total_comment,id_penerbit as idppp FROM tb_pra_comment GROUP By id_penerbit)tbpc"), "tbpc.idppp", "=", $this->table . ".id_penerbit")
			->limit($limit)
			->orderBy("id_penerbit", "ASC")
			->get();
		return $data;
	}

	public function getDetailPenerbitAtPraPemasaran($base, $id)
	{
		$data = PenerbitModel::where("id_penerbit", $id)
			->select($this->table . ".*", DB::raw("IFNULL(total_like,0) as total_like"), DB::raw("IFNULL(total_comment,0) as total_comment"),DB::raw("CONCAT('$base','/',photos) as photos"))
			->leftjoin(DB::raw("(SELECT COUNT(*) AS total_like, id_penerbit as idpp FROM tb_pra_like GROUP BY id_penerbit)tbpl"), "tbpl.idpp", "=", $this->table . ".id_penerbit")
			->leftjoin(DB::raw("(SELECT COUNT(*) AS total_comment,id_penerbit as idppp FROM tb_pra_comment GROUP By id_penerbit)tbpc"), "tbpc.idppp", "=", $this->table . ".id_penerbit")
			->first();

		return $data;
	}

	public function getDetail($id)
	{
		$data = PenerbitModel::find($id);
		return $data;
	}

	public function insertData($data)
	{
		$result = PenerbitModel::create($data)->id_photo;
		return $result;
	}

	public function updateData($data)
	{
		$result = PenerbitModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id)
	{
		$result = PenerbitModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

	public function getDataAll(){
		$result= PenerbitModel::where("status","active")->get();
		return $result;
	}

	public function getAllPenerbit($str, $limit, $keyword, $short, $shortmode)
	{
		$data = PenerbitModel::where(function ($query) use ($keyword) {
			if ($keyword != "") {
				$query->where("nama_penerbit", "like", "%" . $keyword . "%");
				$query->orwhere("kode_penerbit", "like", "%" . $keyword . "%");
				$query->orwhere("nama_sektor_industri", "like", "%" . $keyword . "%");
				$query->orwhere("siup", "like", "%" . $keyword . "%");
			}
		})
			->wherein($this->table . ".status", array("active", "inactive"))
			->leftJoin('tb_sektor_industri', 'tb_penerbit.id_sektor_industri', '=', 'tb_sektor_industri.id_sektor_industri')
			->leftjoin(DB::raw("(SELECT COUNT(*) as total_campaign, id_penerbit FROM tb_campaign WHERE status = 'active' GROUP BY id_penerbit)tot"),"tot.id_penerbit","=",$this->table.".id_penerbit")
			->select('tb_penerbit.*', 'tb_sektor_industri.nama_sektor_industri','total_campaign')
			->orderBy($short, $shortmode)
			->skip($str)
			->limit($limit)
			->get();
		return $data;
	}

	public function getAllPenerbitPending($str, $limit, $keyword, $short, $shortmode)
	{
		$data = PenerbitModel::where(function ($query) use ($keyword) {
			if ($keyword != "") {
				$query->where("nama_penerbit", "like", "%" . $keyword . "%");
				$query->orwhere("kode_penerbit", "like", "%" . $keyword . "%");
				$query->orwhere("nama_sektor_industri", "like", "%" . $keyword . "%");
				$query->orwhere("siup", "like", "%" . $keyword . "%");
			}
		})
			->wherein($this->table . ".status", array("pending"))
			->leftJoin('tb_user', 'tb_user.id_user', '=', $this->table.'.id_user')
			->leftJoin('tb_sektor_industri', 'tb_penerbit.id_sektor_industri', '=', 'tb_sektor_industri.id_sektor_industri')
			->leftjoin(DB::raw("(SELECT COUNT(*) as total_like, id_penerbit as id_p FROM tb_pra_like GROUP BY id_p)ll"),"ll.id_p","=",$this->table.".id_penerbit")
			->leftjoin(DB::raw("(SELECT COUNT(*) as total_comment, id_penerbit as id_pc FROM tb_pra_comment GROUP BY id_pc)lc"),"lc.id_pc","=",$this->table.".id_penerbit")
			->select('tb_penerbit.*', 'tb_sektor_industri.nama_sektor_industri',DB::raw("IFNULL(total_like,0) as total_like"),DB::raw("IFNULL(total_comment,0) as total_comment"),"first_name","last_name")
			->orderBy($short, $shortmode)
			->skip($str)
			->limit($limit)
			->get();
		return $data;
	}

	public function getPenawaranPenerbitDetail($id)
	{
		$data = PenerbitModel::where('id_penerbit', $id)
			->wherein($this->table . ".status", array("pending"))
			->leftJoin('tb_sektor_industri', 'tb_penerbit.id_sektor_industri', '=', 'tb_sektor_industri.id_sektor_industri')
			->select('tb_penerbit.*', 'tb_sektor_industri.nama_sektor_industri')
			->get();
		return $data;
	}

	public function getDetailPenerbit($id)
	{
		$data = PenerbitModel::where('id_penerbit', $id)
			->leftJoin('tb_sektor_industri', 'tb_penerbit.id_sektor_industri', '=', 'tb_sektor_industri.id_sektor_industri')
			->select('tb_penerbit.*', 'tb_sektor_industri.nama_sektor_industri')
			->first();
		return $data;
	}

	public function findPenerbit($keyword)
	{
		$data = PenerbitModel::where(function ($query) use ($keyword) {
			if ($keyword != "") {
				$query->where("nama_penerbit", "like", "%" . $keyword . "%");
				$query->orwhere("kode_penerbit", "like", "%" . $keyword . "%");
			}
		})
			->orderBy("nama_penerbit", "asc")
			->orderBy("nama_penerbit", "asc")
			->wherein($this->table . ".status", array("active", "inactive"))
			->limit(10)
			->get();
		return $data;
	}

	public function getPenerbitCode(){
    $string = "";
    $lengthofcode   = 10;
    $initialstring  = "0123456789";
    do{
			$string = "";
      for($i=0;$i<$lengthofcode;$i++){
        $string = @$string.(substr($initialstring,rand(0,strlen($initialstring)),1));
      }
			$hasilcek = PenerbitModel::where("kode_penerbit",$string)->get()->count();
    }while($hasilcek!=0);
    return $string;
  }
}
