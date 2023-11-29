<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SlideModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'slides';
	protected $primaryKey   = 'id_slide';
	protected $fillable     = ['id_slide','path','status','time_created','last_update'];

  public function getSlidesActive() {
		$data = SlideModel::where("status","active")->get();
		return $data;
	}

  public function getDetail($id) {
		$data = SlideModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = SlideModel::create($data)->id_slide;
		return $result;
  }

	public function updateData($data){
		$result = SlideModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id){
		$result = SlideModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
	
	public function getData($str, $limit, $keyword, $short, $shortmode){
        $data = SlideModel::where(function($query) use ($keyword){
            if($keyword!=""){
                $query->where("path","like","%".$keyword."%");
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
        $data = SlideModel::where(function($query) use ($keyword){
            if($keyword!=""){
                $query->where("path","like","%".$keyword."%");
            }
        })
            ->wherein("status", array("active","inactive"))
            ->count();
        return $data;
    }
}
