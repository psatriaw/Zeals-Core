<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class FundraisingModel extends Model{
    public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

    protected $table 	    = 'fundraising';
    protected $primaryKey   = 'id_fundraising';
    protected $fillable     = ['id_fundraising', 'id_member','time_created','last_update','id_product','paid_by_author','title','status'];

    public function funds(){
        return $this->hasMany('App\Http\Models\FundraisingFundsModel','id_fundraising','id_fundraising');
    }

    public function member(){
        return $this->hasOne('App\Http\Models\UserModel','id_user','id_member');
    }

    public function getListOfAvailableFundraising($id_user, $limit = ""){
        $data = FundraisingModel::orderBy('time_created', 'DESC')->where('fundraising.status', 'active');

        if( isset($limit) and $limit>0 ){
            $data = $data->skip(0)->take($limit);
        }

        return $data->get();
    }

    public function getDetailOfFundraising($id){
        $data = FundraisingModel::with(['funds','member'])
            ->where($this->primaryKey, $id)->get();
        return $data;
    }

    public function insertData($data){
        $result = FundraisingModel::create($data)->id_fundraising;
        return $result;
    }

    public function updateData($data){
        $result = FundraisingModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
        return $result;
    }

}
