<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
  use SoftDeletes;
  use HasFactory;
  
	public $timestamps    = false;
	const CREATED_AT      = 'time_created';
	const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_penerbit';
	protected $primaryKey = 'id_penerbit';
	protected $fillable   = [
            'id_penerbit',
            'nama_penerbit',
            'kode_penerbit',
            'time_created',
            'last_update',
            'status',
            'alamat',
            'no_telp',
            'siup',
            'nib',
            'pic_name',
            'id_user',
            'pic_telp',
            'id_sektor_industri',
            'email',
            'nib_file',
            'pbb_file',
            'neraca_file',
            'pos_file',
            'rab_file',
            'proyeksi_file',
            'photo_video_file',
            'affiliate_id'
          ];

  //Relation

  public function createCode(){
    $no_urut            = Brand::withTrashed()->count();
    return  "BR".str_pad($no_urut+1, 4, '0', STR_PAD_LEFT); 
  }

  public function campaigns(){
    return $this->hasMany(Campaign::class,'id_penerbit','id_penerbit');
  }

  public function industry(){
    return $this->belongsTo(SektorIndustri::class,'id_sektor_industri','id_sektor_industri');
  }

}
