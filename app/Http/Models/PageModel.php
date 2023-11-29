<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PageModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_page';
	protected $primaryKey   = 'id_page';
	protected $page_code 		= "";
	protected $fillable     = ['id_page','page_code','content', 'last_update','time_created','title','keyword','status'];

	public function setCode($code){
		$this->page_code = $code;
	}

	public function getTitle(){
		$data = PageModel::where("page_code",$this->page_code)->first();
		if($data){
			return $data->title;
		}else{
			return "";
		}
	}

	public function getKeyword(){
		$data = PageModel::where("page_code",$this->page_code)->first();
		if($data){
			return $data->keyword;
		}else{
			return "";
		}
	}

	public function getContent(){
		$data = PageModel::where("page_code",$this->page_code)->first();
		if($data){
			return $data->content;
		}else{
			return "";
		}
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
		$data = PageModel::where($this->primaryKey,$id)->get()->first();
		return $data;
	}

	public function insertData($data){
  		$result = PageModel::create($data)->id_page;
  		return $result;
  	}

	public function updateData($data){
  		$result = PageModel::where("id_page",$data['id_page'])->update($data);
  		return $result;
  	}

  	public function deleteData($id){
  		$result = DB::table($this->table)->where($this->primaryKey, '=', $id)->delete();
  		return $result;
  	}

	public function getSettingVal($id_setting){
      return PageModel::where('code_setting',$id_setting)->first()->setting_value;
	}

	public function getData($str, $limit, $keyword, $short, $shortmode){
        $data = PageModel::where(function($query) use ($keyword){
            if($keyword!=""){
                $query->where("title","like","%".$keyword."%");
                $query->orwhere("content","like","%".$keyword."%");
                $query->orwhere("keyword","like","%".$keyword."%");
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
        $data = PageModel::where(function($query) use ($keyword){
            if($keyword!=""){
                $query->where("title","like","%".$keyword."%");
				$query->orwhere("content","like","%".$keyword."%");
                $query->orwhere("keyword","like","%".$keyword."%");
            }
        })
            ->wherein("status", array("active","inactive"))
            ->count();
        return $data;
    }
}
