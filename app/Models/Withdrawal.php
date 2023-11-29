<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Withdrawal extends Model
{
	public $timestamps    = false;
  const CREATED_AT      = 'date_created';

  //Schema
  protected $table      = "tb_withdrawal";
  protected $primaryKey = "id_withdrawal";
	protected $fillable   = [
            'id_withdrawal',
            'time_created',
            'last_update',
            'total_pencairan',
            'status',
            'nama_bank',
            'nama_pemilik_rekening',
            'nomor_rekening',
            'id_user',
            'callback_response',
            'trx_status',
            'withdrawal_code',
            'fee'
          ];
  protected $prestring        = "WTL";
	protected $digittransaction = 10;
    
  //Relation
  public function user(){
      return $this->belongsTo(User::class,'id_user','id_user');
  }

  //CRUD

  //Custom Query
    public function checkANyPendingWithdrawal($login){
		return Withdrawal::where("id_user",$login->id_user)->where("status","pending")->get()->count();
	}

    public function createCode(){
        $data  = Withdrawal::where("withdrawal_code", "like", "%" . $this->prestring . "%")->orderBy($this->primaryKey, "DESC")->get();

        if ($data->count() > 0) {
            $data             = $data->toArray()[0];
            $transnumb  = explode($this->prestring, $data['withdrawal_code']);
            $number     = intval($transnumb[1]);
            $nextnumber = $number + 1;
            return $this->formattransaction($this->prestring, $nextnumber);
        } else {
            return $this->formattransaction($this->prestring, 1);
        }
	}

	public function formattransaction($prestring, $number)
	{
        $lengthofnumber = strlen(strval($number));
        $lengthrest     = $this->digittransaction - $lengthofnumber;
        if ($lengthrest > 0) {
            $transnumb = strval($number);
            for ($i = 0; $i < $lengthrest; $i++) {
                $transnumb = "0" . $transnumb;
            }
            $transnumb = $prestring . $transnumb;
            return $transnumb;
        } else {
                return $prestring . $number;
        }
    }

}
