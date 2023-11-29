<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
  use HasFactory, SoftDeletes;

  public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
  protected $table      = 'tb_banner';
  protected $primaryKey = 'id_banner';
  protected $fillable   = [
          'id_banner',
          'title',
          'description',
          'time_created',
          'last_update',
          'status',
          'banner_path',
          'link'
        ];

  //Relation

  //CRUD
  public function findById($id)
  {
    $result = Banner::where("id_banner", $id)->first();
    return $result;
  }
  public function updateData($data)
  {
    $result = Banner::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }

  //Custom Query
  public function getAllBannersActive($base)
  {
    return Banner::select("*", DB::raw("CONCAT('$base','/',banner_path) as path "))->orderBy('id_banner', "DESC")->where("status","active")->get();
  }
  public function getAllBanners($str, $limit, $keyword, $short, $shortmode)
  {
    $result = Banner::where(function ($query) use ($keyword) {
      if ($keyword != "") {
        $query->where("title", "like", "%" . $keyword . "%");
      }
    })
      ->wherein($this->table . ".status", array("active", "inactive"))
      ->select('*')
      ->orderBy($short, $shortmode)
      ->skip($str)
      ->limit($limit)
      ->get();
    return $result;
  }
}
