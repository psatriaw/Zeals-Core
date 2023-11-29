<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class BannerModel extends Model
{
  public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

  protected $table         = 'tb_banner';
  protected $primaryKey   = 'id_banner';
  protected $fillable     = ['id_banner', 'title', 'description', 'time_created', 'last_update', 'status', 'banner_path', 'link'];

  public function getAllBanners($base)
  {
    return BannerModel::select("*", DB::raw("CONCAT('$base','/',banner_path) as path "))->orderBy('id_banner', "DESC")->where("status","active")->get();
  }

  public function getAllBannersActive($str, $limit, $keyword, $short, $shortmode)
  {
    $data = BannerModel::where(function ($query) use ($keyword) {
      if ($keyword != "") {
        $query->where("title", "like", "%" . $keyword . "%");
      }
    })
      ->wherein($this->table . ".status", array("active", "inactive"))
      // ->select($this->table . ".*", "first_name", "cart_code")
      // ->where("status", "active")
      ->select('*')
      ->orderBy($short, $shortmode)
      ->skip($str)
      ->limit($limit)
      ->get();
    return $data;
  }

  public function getDetail($id)
  {
    $data = BannerModel::where("id_banner", $id)->first();

    return $data;
  }

  public function updateData($data)
  {
    $result = BannerModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
}
