<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UserTempModel extends Model
{

  public $timestamps      = false;
  const CREATED_AT        = 'date_created';
  const UPDATED_AT        = 'last_update';

  protected $table        = 'tb_user_tmp';
  protected $primaryKey   = 'id_member';
  protected $fillable     = ['id_member','name','email','no_rek','account_bank_name','bank_code','password','address','status','member_key','facebook_account_id','twitter_account_id','google_account_id','no_telp','birth_date','id_location','marital_status','job','salary','have_children','is_hapus','join_date','last_login','last_update','avatar','notif_email','notif_push','skip_intro_referral','affiliate_code','instagram_username','last_request','referral_code','id_job','domisili','npwp','invited'];

  public function getData($time,$limit){
    return UserTempModel::where("invited","0")->orderBy("id_member","asc")->limit($limit)->skip(0)->get();
  }

  public function updateData($data){
      $result = UserTempModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
      return $result;
  }

  public function checkData($npwp){
    $result = UserTempModel::where("npwp",$npwp)->first();
    return $result;
  }
}
