<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class SektorIndustri extends Model
{
  use SoftDeletes;

  public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
  protected $table      = 'tb_sektor_industri';
  protected $primaryKey = 'id_sektor_industri';
  protected $fillable   = [
            'id_sektor_industri',
            'nama_sektor_industri',
            'time_created',
            'last_update',
            'icon',
            'status'
          ];

  //Relation
  public function campaigns(){
    return $this->hasMany(CampaignProperty::class,'value','id_sektor_industri')->where("property_type","category");
  }
  
  public function brands(){
    return $this->hasMany(Brand::class,'id_sektor_industri','id_sektor_industri');
  }

  public function penerbit(){
    return $this->hasMany(Penerbit::class);
  }

  //CRUD
  public function insertData($data)
  {
    $result = SektorIndustri::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = SektorIndustri::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = SektorIndustri::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = SektorIndustri::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query
  public function getAllActive(){
    return SektorIndustriModel::where("status","active")->orderBy("nama_sektor_industri","ASC")->get();
  }
}
