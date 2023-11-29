<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterTrx extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ["user_id","amount","trx_code","va_number","data_request","status","data_callback","vendor","type","sign"];
    protected $table    = "tb_master_trx";


    public function createCode(){
        $no_urut            = MasterTrx::withTrashed()->count();
        return  "TR".str_pad($no_urut+1, 8, '0', STR_PAD_LEFT); 
    }

    public function user(){
        return $this->belongsTo(User::class,"user_id","id_user");
    }
}
