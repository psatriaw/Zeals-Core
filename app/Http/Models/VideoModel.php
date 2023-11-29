<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class VideoModel extends Model
{
	public $timestamps      = false;
	const CREATED_AT        = 'time_created';
	const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_tutorial_videos';
	protected $primaryKey   = 'id_video';
	protected $fillable     = ['id_video', 'url_video', 'time_created', 'last_update', 'id_user', 'video_code', 'status', 'title'];


	public function getAllVideo($str, $limit, $keyword, $short, $shortmode)
	{
		$data = VideoModel::where(function ($query) use ($keyword) {
			if ($keyword != "") {
				$query->where("title", "like", "%" . $keyword . "%");
			}
		})
			->wherein($this->table . ".status", array("active"))
			// ->select($this->table . ".*", "first_name", "cart_code")
			// ->where("status", "active")
			->select('*')
			->orderBy($short, $shortmode)
			->skip($str)
			->limit($limit)
			->get();
		return $data;
	}

	public function getTutorials(){
		return VideoModel::where("status","active")->orderBy("time_created","DESC")->get();
	}

	public function getAllDataProduksi()
	{
		$data = "SELECT IFNULL(SUM(qty),0) as total FROM tb_brownies_cart_pemenuhan WHERE status = 'active'";
		$data = DB::select(DB::raw($data));

		$data_reject = "SELECT IFNULL(SUM(qty),0) as total FROM tb_brownies_product_reject WHERE status = 'active'";
		$data_reject = DB::select(DB::raw($data_reject));
		if ($data_reject) {
			$datareject = $data_reject[0]->total;
		} else {
			$datareject = 0;
		}

		if ($data) {
			return $data[0]->total - $datareject;
		} else {
			return 0;
		}
	}

	public function getDataProductionAtThatTime($date, $date2)
	{
		$data = "SELECT IFNULL(SUM(qty),0) as total FROM tb_cart, tb_cart_detail, tb_brownies_cart_pemenuhan WHERE tb_cart.id_cart = tb_cart_detail.id_cart AND tb_cart_detail.id_cart_detail = tb_brownies_cart_pemenuhan.id_cart_detail AND tb_cart.cart_date BETWEEN '$date' AND '$date2'";
		//print $data;
		$data = DB::select(DB::raw($data));
		if ($data) {
			return $data[0]->total;
		} else {
			return 0;
		}
	}

	public function getDataPermintaanAtThatTime($date, $date2)
	{
		$data = "SELECT IFNULL(SUM(quantity),0) as total FROM tb_cart_detail,tb_cart WHERE tb_cart.id_cart = tb_cart_detail.id_cart AND tb_cart_detail.status = 'active' AND cart_date BETWEEN '$date' AND '$date2'";
		//print $data;
		$data = DB::select(DB::raw($data));
		if ($data) {
			return $data[0]->total;
		} else {
			return 0;
		}
	}

	public function getVideoData()
	{
		$data = VideoModel::where("status", "active")->orderBy("time_created", "DESC");
		return $data;
	}

	public function getDetail($id)
	{
		$data = VideoModel::find($id);
		return $data;
	}

	public function getItems($id)
	{
	}

	public function insertData($data)
	{
		$result = VideoModel::create($data);
		return $result;
	}

	public function updateData($data)
	{
		$result = VideoModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
		return $result;
	}
}
