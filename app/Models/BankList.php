<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankList extends Model
{
	public $timestamps    = false;

  use SoftDeletes;
  use HasFactory;

	//Schema
  protected $table 	    = 'tb_bank_list';
	protected $primaryKey = 'id';
	protected $fillable   = [
            'kode',
            'nama',
            'bank_code'
          ];
  
  //Relation
  public function user(){
    $this->hasMany(User::class,'nama_bank','bank_code');
  }

  //CRUD
  public function insertData($data)
  {
    $result = BankList::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = BankList::where($this->primaryKey, $id)->first();
    return $result;
  }
  public function updateData($data){
    $result = BankList::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = BankList::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query
}
