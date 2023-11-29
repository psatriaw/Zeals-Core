<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class FundraisingFundsModel extends Model{
    public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

    protected $table 	    = 'fundraising_funds';
    protected $primaryKey   = 'id_fundraising_funds';
    protected $fillable     = ['id_fundraising_funds', 'id_fundraising','id_member','total_fund','time_created','last_update','status','payment_code','payment_time'];

    public function fundraising(){
        $this->belongsTo('App\Http\Models\FundraisingModel','id_fundraising','id_fundraising');
    }

    public function member(){
        return $this->hasOne('App\Http\Models\UserModel','id_user','id_member');
    }

    public function createPayment($data){
      $result = FundraisingFundsModel::create($data)->id_fundraising_funds;
      return $result;
    }

    public function getTotalFunds($id_fundraising){
      $result = FundraisingFundsModel::select(DB::raw("SELECT SUM(total_fund) as total_f"))->where("id_fundraising",$id_fundraising)->group_by("id_fundraising")->first();
      return $result->total_f;
    }

    public function getListOfDonatur($id_fundraising){
      $result = FundraisingFundsModel::where("id_fundraising",$id_fundraising)->get();
      if($result->count()){
        return $result;
      }else{
        return 0;
      }
    }

}
