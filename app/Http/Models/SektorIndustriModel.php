<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SektorIndustriModel extends Model
{
    public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

    protected $table         = 'tb_sektor_industri';
    protected $primaryKey   = 'id_sektor_industri';
    protected $fillable     = ['id_sektor_industri', 'nama_sektor_industri', 'time_created', 'last_update', 'status'];
    //

    public function getAllDataActive(){
      return SektorIndustriModel::where("status","active")->orderBy("nama_sektor_industri","ASC")->get();
    }

    public function getPluckPerusahaan(){
      return SektorIndustriModel::where('status', 'active')
      ->pluck('nama_sektor_industri','id_sektor_industri');
    }

    public function getSektorIndustri() {
        return SektorIndustriModel::where('status', 'active')
        ->select('id_sektor_industri', 'nama_sektor_industri')
        ->get();
    }

    public function getSektorIndustriAll($str, $limit, $keyword, $short, $shortmode){
  		$data = SektorIndustriModel::where(function ($query) use ($keyword) {
  			if ($keyword != "") {
  				$query->where("nama_sektor_industri", "like", "%" . $keyword . "%");
  				$query->orwhere("time_created", "like", "%" . $keyword . "%");
  				$query->orwhere("last_update", "like", "%" . $keyword . "%");
  			}
  		})
      ->leftjoin(DB::raw("(SELECT COUNT(*) as total_campaign, value FROM tb_campaign_property WHERE property_type = 'category' GROUP BY value)tot"),"tot.value","=",$this->table.".id_sektor_industri")
			->where('status', 'active')
			->orderBy($short, $shortmode)
			->skip($str)
			->limit($limit)
			->get();
		  return $data;
	}

  public function countData($keyword){
    $data = SektorIndustriModel::where(function ($query) use ($keyword) {
      if ($keyword != "") {
        $query->where("nama_sektor_industri", "like", "%" . $keyword . "%");
        $query->orwhere("time_created", "like", "%" . $keyword . "%");
        $query->orwhere("last_update", "like", "%" . $keyword . "%");
      }
    })
    ->where('status', 'active')
    ->get()
    ->count();
    return $data;
  }

    public function insertData($data)
	{
		$result = SektorIndustriModel::create($data)->id_photo;
		return $result;
	}

	public function updateData($data)
	{
		$result = SektorIndustriModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id)
	{
		$result = SektorIndustriModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
}
