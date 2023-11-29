<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RestrictionModel extends Model{

  public $timestamps      = false;
  //const CREATED_AT        = 'time_created';
  //const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_user_restricted';
	protected $primaryKey   = 'id_restriction';
	protected $fillable     = ['id_restriction', 'id_method','type_user','id_user'];

  public function isAllow($id_user,$id_method){
    $granted = RestrictionModel::where("id_method",$id_method)
                ->where("id_user",$id_user)
                ->first();

    if($granted==""){
      return true;  //allowed
    }else{
      return false;//forbidden
    }

  }

  public function deleteRestriction($id_user){
    return RestrictionModel::where("id_user",$id_user)->delete();
  }

  public function createData($data){
    return RestrictionModel::create($data);
  }
}
