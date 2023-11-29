<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TicketModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_ticket';
	protected $primaryKey   = 'id_ticket';
	protected $fillable     = ['id_ticket', 'subject','time_created','last_update','status','target','sender','ticket_code'];

  protected $digittransaction = 12;

  public function createNewCode($prestring){
      $data   = TicketModel::where("ticket_code","like","%".$prestring."%")->orderBy("id_ticket","ASC")->get()->toArray();
      if($data){
        $transnumb  = explode($prestring,$data[0]['ticket_code']);
        $number     = intval($transnumb[1]);
        $nextnumber = $number+1;
        return $this->formatnumbertransaction($prestring,$nextnumber);
      }else{
        return $this->formatnumbertransaction($prestring,1);
      }

    }

    public function formatnumbertransaction($prestring, $number){
      print $prestring." ".$number;
      $lengthofnumber = strlen(strval($number));
      $lengthrest     = $this->digittransaction - $lengthofnumber;
      if($lengthrest>0){
        $transnumb = strval($number);
        for($i=0;$i<$lengthrest;$i++){
          $transnumb = "0".$transnumb;
        }
        $transnumb = $prestring.$transnumb;
        return $transnumb;
      }else{
        return $prestring.$number;
      }
    }

  public function getData($str, $limit, $keyword, $short, $shortmode){
		$data = TicketModel::where(function($query) use ($keyword){
							if($keyword!=""){
								$query->where("subject","like","%".$keyword."%");
                $query->where("thesender.name_sender","like","%".$keyword."%");
                $query->where("thetarget.name_target","like","%".$keyword."%");
							}
						})
            ->select($this->table.".*","thesender.name_sender","thetarget.name_target")
            ->leftjoin(DB::raw("(SELECT id_user,CONCAT(first_name,' ',last_name) as name_target FROM tb_user)thetarget"),"thetarget.id_user","=",$this->table.".target")
            ->leftjoin(DB::raw("(SELECT id_user,CONCAT(first_name,' ',last_name) as name_sender FROM tb_user)thesender"),"thesender.id_user","=",$this->table.".sender")
            ->wherein("status", array("open","close"))
            ->orderBy($short, $shortmode)
						->skip($str)
            ->limit($limit)
						->get();
		return $data;
	}

	public function countData($keyword){
		$data = TicketModel::where(function($query) use ($keyword){
							if($keyword!=""){
                $query->where("subject","like","%".$keyword."%");
                $query->where("thesender.name_sender","like","%".$keyword."%");
                $query->where("thetarget.name_target","like","%".$keyword."%");
							}
						})
            ->wherein("status", array("open","close"))
						->count();
		return $data;
	}

  public function getDetail($id) {
		$data = TicketModel::where($this->primaryKey,$id)
            ->select($this->table.".*","thesender.name_sender","thetarget.name_target")
            ->leftjoin(DB::raw("(SELECT id_user,CONCAT(first_name,' ',last_name) as name_target FROM tb_user)thetarget"),"thetarget.id_user","=",$this->table.".target")
            ->leftjoin(DB::raw("(SELECT id_user,CONCAT(first_name,' ',last_name) as name_sender FROM tb_user)thesender"),"thesender.id_user","=",$this->table.".sender")
            ->get()->first();
		return $data;
	}

	public function insertData($data){
		$result = TicketModel::create($data);
		if($result){
      return $result->id_ticket;
    }else{
      return $result;
    }
  }

	public function updateData($data){
		$result = TicketModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}
}
