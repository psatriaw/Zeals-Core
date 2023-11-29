<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TicketCommentModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_ticket_comment';
	protected $primaryKey   = 'id_ticket_comment';
	protected $fillable     = ['id_ticket_comment', 'content','time_created','last_update','id_ticket','author','file_path','type'];

  public function getListBalasan($id_ticket){
    $data = TicketCommentModel::where("id_ticket",$id_ticket)
            ->join("tb_user","tb_user.id_user","=",$this->table.".author")
            ->orderBy("time_created","ASC")
            ->get();
    return $data;
  }

  public function insertData($data){
		$result = TicketCommentModel::create($data);
		if($result){
      return $result->id_ticket_comment;
    }else{
      return $result;
    }
  }

	public function updateData($data){
		$result = TicketCommentModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

}
