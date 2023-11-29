<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class BahanPenyertaModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_brownies_bahan_penyerta_produksi';
	protected $primaryKey   = 'id_bahan_penyerta_produksi';
	protected $fillable     = ['id_bahan_penyerta_produksi', 'id_purchase_detail','quantity','status','id_cart','author','time_created','last_update'];

  public function getData($id_cart){
    return BahanPenyertaModel::where('id_cart',$id_cart)
          ->join(DB::raw("(SELECT id_purchase_detail as id_cd, id_item, item_price, purchase_code FROM tb_purchase_detail, tb_purchase WHERE tb_purchase.id_purchase = tb_purchase_detail.id_purchase AND tb_purchase_detail.status = 'ok')pd"),"pd.id_cd","=",$this->table.".id_purchase_detail")
          ->join(DB::raw("(SELECT material_code, material_name, material_unit, id_material as id_mat FROM tb_fremilt_material WHERE status = 'active')mat"),"mat.id_mat","=","pd.id_item")
          ->where("status","active")
          ->get();
  }

  public function insertData($data){
    return BahanPenyertaModel::create($data)->id_bahan_penyerta_produksi;
  }

  public function updateData($data){
    $result = BahanPenyertaModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
  }
}
