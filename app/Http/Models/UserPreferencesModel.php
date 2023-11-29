<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UserPreferencesModel extends Model
{
    public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

    protected $table           = 'tb_user_preferences';
    protected $primaryKey   = 'id_preferences';
    protected $fillable     = ['id_preferences', 'time_created', 'last_update', 'id_user', 'id_sektor_industri', 'status'];

    public function getUserPreferences($id)
    {
        $data = UserPreferencesModel::where("id_user", $id)->where("status","active")->pluck("id_sektor_industri");
        return $data;
    }

    public function getUserLabelPreferences($id){
        $data = UserPreferencesModel::where("id_user", $id)->where($this->table.".status","active")->join("tb_sektor_industri","tb_sektor_industri.id_sektor_industri","=",$this->table.".id_sektor_industri")->get();
        return $data;
    }

    public function removePreferences($login){
      $data = UserPreferencesModel::where("id_user",$login->id_user)->update(array("status" => "deleted"));
      return $data;
    }

    public function getParaPembeliSaham($id_campaign)
    {
        $data = UserPreferencesModel::select(DB::raw("SUM(quantity) as quantity"),"first_name","last_name",$this->table.".id_user")
                ->where("id_campaign", $id_campaign)
                ->join(DB::raw("(SELECT first_name, last_name, id_user FROM tb_user)tb_user"), "tb_user.id_user", "=", $this->table . ".id_user")
                ->groupBy("id_user");
        return $data->get();
    }

    public function getTotalSahamTerjual($id){
      $data = UserPreferencesModel::where("status","active")->where("id_campaign",$id)->select(DB::raw("SUM(quantity) as total_saham"))->groupBy("id_campaign")->first();
      if($data){
        return $data->total_saham;
      }else{
        return 0;
      }
    }

    public function getMyInvestmentDetail($id_user, $id_campaign)
    {
        $data = UserPreferencesModel::where("id_user", $id_user)
            ->where($this->table . ".id_campaign", $id_campaign)
            ->select(DB::raw("IFNULL(nilai,0) as nilai"), DB::raw("SUM(quantity*nilai_beli) as total_asset"), DB::raw("SUM(quantity) as total_saham"), "campaign_title", $this->table . ".id_campaign", "photos", "tipe_produk", "periode_deviden")
            ->leftjoin(DB::raw("(SELECT campaign_title,photos,tipe_produk,periode_deviden, id_campaign FROM tb_campaign)campaign"), "campaign.id_campaign", "=", $this->table . ".id_campaign")
            ->leftjoin(DB::raw("(SELECT nilai, id_campaign as id_camp FROM tb_campaign_review WHERE status = 'active')penilaian"), "penilaian.id_camp", "=", $this->table . ".id_campaign")
            ->groupBy("id_campaign")
            ->first();
        return $data;
    }

    public function getMyInvestment($id_user)
    {
        $data = UserPreferencesModel::where("id_user", $id_user)
            ->select(DB::raw("IFNULL(nilai,0) as nilai"), DB::raw("SUM(quantity*nilai_beli) as total_asset"), DB::raw("SUM(quantity) as total_saham"), "campaign_title", $this->table . ".id_campaign", "photos")
            ->leftjoin(DB::raw("(SELECT campaign_title,photos, id_campaign FROM tb_campaign)campaign"), "campaign.id_campaign", "=", $this->table . ".id_campaign")
            ->leftjoin(DB::raw("(SELECT nilai, id_campaign as id_camp FROM tb_campaign_review WHERE status = 'active')penilaian"), "penilaian.id_camp", "=", $this->table . ".id_campaign")
            ->groupBy("id_campaign")
            ->get();
        return $data;
    }

    public function getItems($id, $path)
    {
        return UserPreferencesModel::where("id_cart", $id)
            ->where("status", "active")
            ->join(DB::raw("(SELECT product_name, id_product FROM tb_product WHERE status IN ('active','available'))cd"), "cd.id_product", "=", $this->table . ".id_product")
            ->leftjoin(DB::raw("(SELECT path , CONCAT('$path','/',thumbnail) as product_photo, tb_product_detail.id_product as idp FROM tb_photo, tb_product_detail WHERE tb_photo.id_photo = tb_product_detail.value AND tb_product_detail.status = 'active' AND tb_product_detail.type = 'photo')ph"), "ph.idp", "=", $this->table . ".id_product")
            ->get();
    }

    public function insertData($data)
    {
        $result = UserPreferencesModel::create($data)->id_preferences;
        return $result;
    }

    public function updateData($data)
    {
        $result = UserPreferencesModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
        return $result;
    }

    public function getCustodianSaham()
    {
        $result = UserPreferencesModel::where([['tb_saham.status', 'active'], ['tb_saham.is_exported', 0]])
            ->join('tb_user', 'tb_user.id_user', 'tb_saham.id_user')
            ->join('tb_campaign', 'tb_campaign.id_campaign', 'tb_saham.id_campaign')
            ->select('tb_saham.*', 'tb_user.first_name', 'tb_user.email', 'tb_campaign.campaign_title')
            ->get();

        if ($result != null) {
            // update is_exported
            foreach ($result as $dt) {
                $this->updateData([
                    'id_saham' => $dt->id_saham,
                    'is_exported' => 1
                ]);
            }
        }

        return $result;
    }
}
