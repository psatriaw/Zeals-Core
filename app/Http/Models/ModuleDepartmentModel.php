<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ModuleDepartmentModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_module_department';
	protected $primaryKey   = 'id_module_department';
	protected $fillable     = ['id_module_department', 'id_module','id_department'];

  public function insertData($data){
		$result = ModuleDepartmentModel::create($data);
		return $result;
  }

  public function getDetail($id) {
		$data = ModuleDepartmentModel::find($id);
		return $data;
	}

  public function updateData($data){
		$result = ModuleDepartmentModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

  public function removeData($id){
		$result = ModuleDepartmentModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

	public function getdepartments($id_module){
		return  ModuleDepartmentModel::where("id_module",$id_module)->pluck('id_department');
	}

	public function removeDepartmentRecord($id_module){
		return ModuleDepartmentModel::where("id_module",$id_module)->delete();
	}

  public function getMethods($id_department){
		return ModuleDepartmentModel::join(DB::raw("(SELECT * FROM tb_module)tb_module"),"tb_module.id_module","=",$this->table.".id_module")
						->leftjoin(DB::raw("(SELECT COUNT(*) as granted, id_method FROM tb_department_previlege WHERE id_department = '".$id_department."' GROUP BY id_method)tb_dp"),"tb_dp.id_method","=",$this->table.".id_method")
						->select($this->table.".*","tb_module.module_name","tb_dp.granted")
						->orderBy("tb_module.module_name","ASC")
						->get();
	}

	public function getListModulesByGroupId($id_department){
		return ModuleDepartmentModel::where("id_department",$id_department)
		->join(DB::raw("(SELECT * FROM tb_module)tb_module"),"tb_module.id_module","=",$this->table.".id_module")
		->leftjoin(DB::raw("(SELECT COUNT(*) as total_method, id_module as idm FROM tb_previlege_method GROUP BY id_module)tb_method"),"tb_method.idm","=",$this->table.".id_module")
		->leftjoin(DB::raw("(SELECT COUNT(*) as total_granted, id_module as idmm FROM tb_previlege_method, tb_department_previlege WHERE tb_previlege_method.id_method = tb_department_previlege.id_method AND tb_department_previlege.id_department = $id_department GROUP BY id_module)tb_method_grant"),"tb_method_grant.idmm","=",$this->table.".id_module")
		->orderBy("tb_module.module_name","ASC")
		->get();
	}

}
