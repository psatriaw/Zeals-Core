<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class GroupPrevilegeModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_department_previlege';
	protected $primaryKey   = 'id_previlege';
	protected $fillable     = ['id_previlege', 'id_method','description','id_department'];


  public function deletePrevileges($id_department){
    return GroupPrevilegeModel::where("id_department",$id_department)->delete();
  }

}
