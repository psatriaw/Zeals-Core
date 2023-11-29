<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CardModel extends Model {
    public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

    protected $table 	      = 'card';
    protected $primaryKey   = 'id_card';
    protected $fillable     = ['id_card', 'id_user','time_created','last_update','card_name','card_number','card_month','card_year'];

    public function getDetail($id) {
        $data = CardModel::find($id);
        return $data;
    }

    public function getallcards($id_user){
        $data = CardModel::where("id_user",$id_user)->get();
        return $data;
    }

    public function insertData($data){
        $result = CardModel::create($data)->id_card;
        return $result;
    }

    public function updateData($data){
        $result = CardModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
        return $result;
    }

    public function deletecard($id_card){
        return CardModel::where("id_card",$id_card)->delete();
    }
}
