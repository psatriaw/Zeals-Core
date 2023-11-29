<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class KYC_Pribadi_Model extends Model
{
    public $timestamps      = false;
    const CREATED_AT        = 'date_created';
    const UPDATED_AT        = 'last_update';

    protected $table           = 'tb_kyc_biodata_pribadi';
    protected $primaryKey   = 'id_kyc_pribadi';
    protected $fillable     = ['id_kyc_pribadi', 'id_user', 'nama_lengkap', 'kota_lahir', 'tgl_lahir', 'jenis_kelamin', 'pendidikan_terakhir', 'kewarganegaraan', 'email', 'phone', 'nomor_nik', 'tgl_kadaluwarsa_ktp', 'nomor_passport', 'tgl_kadaluwarsa_passport', 'ktp_photo_path', 'selfie_photo_path', 'img_profile_path', 'date_created', 'last_update'];

    public function getKYCPribadi() {

    }
}
