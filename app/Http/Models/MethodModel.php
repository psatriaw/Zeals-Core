<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MethodModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_previlege_method';
	protected $primaryKey   = 'id_method';
	protected $fillable     = ['id_method', 'method','description','id_module'];



  public function insertData($data){
		$result = MethodModel::create($data);
		return $result;
  }

  public function getDetail($id) {
		$data = MethodModel::find($id);
		return $data;
	}

  public function updateData($data){
		$result = MethodModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

  public function removeData($id){
		DB::select(DB::raw("DELETE FROM tb_department_previlege WHERE id_method = $id"));
		$result = MethodModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}
	public function getAllMethods(){
		return MethodModel::get();
	}

	public function getMethods($id_department){
		return MethodModel::join(DB::raw("(SELECT * FROM tb_module)tb_module"),"tb_module.id_module","=",$this->table.".id_module")
						->leftjoin(DB::raw("(SELECT COUNT(*) as granted, id_method FROM tb_department_previlege WHERE id_department = '".$id_department."' GROUP BY id_method)tb_dp"),"tb_dp.id_method","=",$this->table.".id_method")
						->join(DB::raw("(SELECT * FROM tb_module_department WHERE id_department = '".$id_department."')tb_mdp"),"tb_mdp.id_module","=","tb_module.id_module")
						->select($this->table.".*","tb_module.module_name","tb_dp.granted")
						->orderBy("tb_module.module_name","ASC")
						->get();
	}

	public function getMethodsByUser($id_department, $id_user){
		return MethodModel::join(DB::raw("(SELECT * FROM tb_module)tb_module"),"tb_module.id_module","=",$this->table.".id_module")
						->leftjoin(DB::raw("(SELECT COUNT(*) as granted, id_method FROM tb_department_previlege WHERE id_department = '".$id_department."' GROUP BY id_method)tb_dp"),"tb_dp.id_method","=",$this->table.".id_method")
						->leftjoin(DB::raw("(SELECT COUNT(*) as superungranted, id_method FROM tb_user_restricted WHERE id_user = '".$id_user."' GROUP BY id_method)restricted"),"restricted.id_method","=",$this->table.".id_method")
						->join(DB::raw("(SELECT * FROM tb_module_department WHERE id_department = '".$id_department."')tb_mdp"),"tb_mdp.id_module","=","tb_module.id_module")
						->select($this->table.".*","tb_module.module_name","tb_dp.granted","restricted.superungranted")
						->orderBy("tb_module.module_name","ASC")
						->orderBy("tb_previlege_method.method","ASC")
						->get();
	}

}
