<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ModuleModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_module';
	protected $primaryKey   = 'id_module';
	protected $fillable     = ['id_module', 'module_name','id_department','time_created','last_update'];

  public function previleges(){
    return $this->hasMany(MethodModel::class,"id_module");
  }

  public function getDataSubModule($id){
    return ModuleModel::find($id)->previleges()->get();
  }

	public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = ModuleModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("module_name","like","%".$keyword."%");
								$query->orwhere("tb_module_department.name","like","%".$keyword."%");
                $query->orwhere("tb_module.last_update","like","%".$keyword."%");
							}
						})
						->select($this->table.".*","tb_module_department.name","total_method")
						->leftjoin(DB::raw("(SELECT GROUP_CONCAT(tb_department.name SEPARATOR ', ') as name, id_module FROM tb_department, tb_module_department WHERE tb_module_department.id_department = tb_department.id_department GROUP BY id_module)tb_module_department"),"tb_module_department.id_module","=",$this->table.".id_module")
            ->leftjoin(DB::raw("(SELECT COUNT(id_method) as total_method, id_module FROM tb_previlege_method GROUP BY id_module)tb_method"),"tb_method.id_module","=",$this->table.".".$this->primaryKey)
						->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = ModuleModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("module_name","like","%".$keyword."%");
								$query->orwhere("tb_module_department.name","like","%".$keyword."%");
                $query->orwhere("tb_module.last_update","like","%".$keyword."%");
							}
						})
						->leftjoin(DB::raw("(SELECT GROUP_CONCAT(tb_department.name SEPARATOR ', ') as name, id_module FROM tb_department, tb_module_department WHERE tb_module_department.id_department = tb_department.id_department GROUP BY id_module)tb_module_department"),"tb_module_department.id_module","=",$this->table.".id_module")
            ->leftjoin(DB::raw("(SELECT COUNT(id_method) as total_method, id_module FROM tb_previlege_method GROUP BY id_module)tb_method"),"tb_method.id_module","=",$this->table.".".$this->primaryKey)
						->count();
		return $data;
	}

	public function getDetail($id) {
		$data = ModuleModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = ModuleModel::create($data)->id_module;
		return $result;
  }

	public function updateData($data){
		$result = ModuleModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function getDetailByEmail($data){
		return ModuleModel::where("email",$data['email'])->where("status","active")->first();
	}

	public function removeData($id){
		DB::select(DB::raw("DELETE FROM tb_department_previlege WHERE id_method IN (SELECT id_method FROM tb_previlege_method WHERE id_module = $id)"));
		DB::table("tb_previlege_method")->where("id_module",$id)->delete();
		DB::table("tb_module_department")->where("id_module",$id)->delete();
		$result = ModuleModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
}
