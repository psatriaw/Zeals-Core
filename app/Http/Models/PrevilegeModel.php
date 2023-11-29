<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use App\Http\Models\RestrictionModel;

class PrevilegeModel extends Model{

  public $timestamps      = false;
  //const CREATED_AT        = 'time_created';
  //const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_department_previlege';
	protected $primaryKey   = 'id_previlege';
	protected $fillable     = ['id_previlege', 'id_method','description','id_department'];

  public function isAllow($id_user,$id_department,$method){
    $sesiuser = Session::get("user");
    if($sesiuser==""){
      $sesiuser = Session::get("member");
      $typeuser = "member";
    }else{
      $typeuser = "user";
    }

    if($id_department==""){
      print "tidak ada ".$id_department;
      exit;
      return false; //forbidden
    }else{
      $granted = PrevilegeModel::where("id_department",$id_department)
                  ->where("tb_previlege_method.method",$method)
                  ->join("tb_previlege_method","tb_previlege_method.id_method","=","tb_department_previlege.id_method")
                  ->first();

      if($granted==""){
        return false; //forbidden
      }else{
        $datagranted = $granted;
        //$query  = "SELECT * FROM user_restricted WHERE id_method = ".($datagranted->id_method)." AND id_user = ".$id_user." AND type_user = '$typeuser'";
        //$granted = DB::select($query);
        /*
        $granted = DB::table("tb_user_restricted")
                    ->where("id_method",$datagranted->id_method)
                    ->where("id_user",$id_user)
                    ->first();
        if($granted==""){
          return true;  //allowed
        }else{
          return false;//forbidden
        }
        */
        $restriction = new RestrictionModel();
        return $restriction->isAllow($id_user,$datagranted->id_method);
      }
    }
  }

  public function getDepartmentByCode($code){
		$data = DepartmentModel::where("department_code",$code)->first();
		return $data->id_department;
	}

  public function getIDPenerbit($id_user){
    $data = PenerbitModel::where("id_user",$id_user)->first();
    return $data->id_penerbit;
  }

  public function deletePrevileges($id_department){
    return PrevilegeModel::where("id_department",$id_department)->delete();
  }

  public function deleteRestriction($id_user){
    return PrevilegeModel::where("id_department",$id_department)->delete();
  }

  public function createData($data){
    return PrevilegeModel::create($data);
  }
}
