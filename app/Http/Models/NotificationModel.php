<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_notification';
	protected $primaryKey   = 'id_notification';
	protected $fillable     = ['id_notification', 'id_user','last_update','time_created','status','content','title'];

	public function getDetail($id) {
		$data = NotificationModel::find($id);
		return $data;
	}

	public function insertData($data){
		$result = NotificationModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = NotificationModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

	public function removeData($id){
		$result = NotificationModel::where($this->primaryKey, '=', $id)->delete();
		return $result;
	}

  public function getListNotifikasi($id_to){
    return NotificationModel::where("id_user",$id_to)->whereIn("status",array('unread','read'))->orderBy("id_notification","desc")->get();
  }

  public function getMoreNotifications($id_to,$last_id){
    if($last_id!=0){
      $data =  NotificationModel::where("id_to",$id_to)
            ->where("id_notification","<",$last_id)
            ->orderBy("id_notification","desc")
            ->skip(0)->take(10)
            ->get();
    }else{
      $data =  NotificationModel::where("id_to",$id_to)
            ->orderBy("id_notification","desc")
            ->skip(0)->take(10)
            ->get();
    }

    return $data;
  }
}
