<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class QRModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_qr';
	protected $primaryKey   = 'id_qr';
	protected $fillable     = ['id_qr', 'code','time_created','last_update','status','usage'];
  protected $prestring 				= "QR";
	protected $digittransaction = 6;

	public function getDetail($id) {
		$data = QRModel::where("id_cart_detail",$id)->join(DB::raw("(SELECT SUM(qty) as total_dapat FROM tb_brownies_cart_pemenuhan WHERE status = 'active')penuhan"),"pemenuhan.id_cart_detail","=",$this->table.".id_cart_detail");
		return $data;
	}

	public function getItems($id, $path){
		return QRModel::where("id_cart",$id)
						->join(DB::raw("(SELECT product_name, id_product FROM tb_product WHERE status IN ('active','available'))cd"),"cd.id_product","=",$this->table.".id_product")
						->leftjoin(DB::raw("(SELECT path , CONCAT('$path','/',thumbnail) as product_photo, tb_product_detail.id_product as idp FROM tb_photo, tb_product_detail WHERE tb_photo.id_photo = tb_product_detail.value AND tb_product_detail.status = 'active' AND tb_product_detail.type = 'photo')ph"),"ph.idp","=",$this->table.".id_product")
						->get();
	}

	public function insertData($data){
		$result = QRModel::create($data);
		return $result;
  }

	public function updateData($data){
		$result = QRModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
		return $result;
	}

  public function createCode(){
    $string = "";
    $abjad  = "ABCDEFGHIJKLMNOPQRSTUVWZYZ0123456789";
    
    do{
      for($i=0;$i<10;$i++){
        $string = $string.substr($abjad,rand(0,strlen($abjad)-1),1);
      }

      $count  = QRModel::where("code",$string)->where("status","used")->orderBy($this->primaryKey,"DESC")->count();
    }while($count>0);

    $dataqr = array(
      "code"              => $string,
      "time_created"      => time(),
      "last_update"       => time(),
      "status"            => 'unused'
    );

    $this->insertData($dataqr);
		return $string;
	}
}
