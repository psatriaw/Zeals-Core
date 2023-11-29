<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class BankModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_bank_list';
	protected $primaryKey   = 'kode';
	protected $fillable     = ['kode', 'nama','bank_code'];

  public function getBankList(){
    return BankModel::orderBy('nama')->where("bank_code","<>","")->pluck('nama', 'bank_code');
  }
  public function insertData($data)
  {
      $result = BankModel::create($data);
      return $result;
  }
  public function getDataAll(){
    // return BankModel::where("bank_code","<>","")->get();
    $data = DB::select(DB::raw("SELECT * FROM tb_bank_list WHERE bank_code <> ''"));
    return $data;
  }
  public function findById($id)
  {
      $data = BankModel::where($this->primaryKey, $id)->first();
      return $data;
  }

  public function updateData($data){
      $result = BankModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
      return $result;
  }
  public function removeData($id)
  {
      $result = BankModel::where($this->primaryKey, '=', $id)->delete();
      return $result;
  }

}
