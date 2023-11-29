<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model {
	public $timestamps      = false;
	const CREATED_AT        = 'time_created';
	const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_department';
	protected $primaryKey   = 'id_department';
	protected $fillable     = ['id_department', 'name','time_created','last_update','status','default','department_code'];

  public function getDepartmentOpt(){
    return DepartmentModel::where("status","active")->orderBy('name')->pluck('name', 'id_department');
  }

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = DepartmentModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("name","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive"))
						->leftjoin(DB::raw("(SELECT COUNT(*) as total_user, id_department as idd FROM tb_user WHERE status = 'active' GROUP BY id_department)tot"),"tot.idd","=",$this->table.".id_department")
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = DepartmentModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("name","like","%".$keyword."%");
							}
						})
						->wherein($this->table.".status",array("active","inactive"))
						->count();
		return $data;
	}

	public function getByCode($code){
		return DepartmentModel::where("department_code",$code)->first();
	}

	public function getDepartmentByCode($code){
		$data = DepartmentModel::where("department_code",$code)->first();
		return $data->id_department;
	}

	public function getDefaultDepartment(){
		$data = DepartmentModel::where("default","yes")->first();
		return $data->id_department;
	}

	public function userlist(){
		return $this->hasMany(UserModel::class,$this->primaryKey)->where("status","active");
	}

	public function getListofDepartmentUsers($department_code){
		$data = DepartmentModel::where("department_code",$department_code)->first();
		if($data){
			$list =  DepartmentModel::find($data->id_department)->userlist()->get();
			if($list->count()){
				return $list;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	public function getDetail($id) {
		$data = DepartmentModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = DepartmentModel::create($data)->id_department;
		return $result;
  }

	public function updateData($data){
		$result = DepartmentModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return DepartmentModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		$result = DepartmentModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

}
