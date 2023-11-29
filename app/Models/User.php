<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $timestamps    = false;
    const CREATED_AT      = 'date_created';
    const UPDATED_AT      = 'last_update';

    protected $table      = 'tb_user';
    protected $primaryKey = 'id_user';
    protected $fillable   = [
                'id_user',
                'first_name',
                'last_name',
                'username',
                'password',
                'email',
                'date_created',
                'affiliate_code',
                'status',
                'phone',
                'address',
                'activation_code',
                'last_update',
                'id_department',
                'id_job',
                'id_wilayah',
                'avatar',
                'google_id',
                'referral_code',
                'id_brand',
                'custom_field_1',
                'custom_field_2',
                'gender',
                'dob',
                'longitude',
                'latitude',
                'bank_id',
                'nama_bank',
                'nama_pemilik_rekening',
                'nomor_rekening',
                'passcode',
                'otp_code'
            ];
  protected $prestring  = "";

  //Relation
    public function brand(){
        return $this->belongsTo(Penerbit::class,'id_brand','id_penerbit');
    }

    public function department(){
        return $this->belongsTo(Department::class,'id_department','id_department');
    }

    public function job(){
        return $this->belongsTo(Job::class,'id_job','id_job');
    }

    public function restrict(){
        return $this->hasMany(UserRestriction::class,'id_user','id_user');
    }

    public function prefer(){
        return $this->hasMany(UserPreferrences::class,'id_user','id_user');
    }

    public function uniqueLink(){
        return $this->hasMany(CampaignUniqueLink::class,'id_user','id_user');
    }

    public function total_campaign(){
        return $this->hasMany(CampaignUniqueLink::class,'id_user','id_user');
    }

    public function total_reach(){
        return $this->hasMany(CampaignTracker::class,'id_user','id_user')->where("type_conversion","initial");
    }

    public function total_visit(){
        return $this->hasMany(CampaignTracker::class,'id_user','id_user')->where("type_conversion","visit");
    }

    public function total_interest(){
        return $this->hasMany(CampaignTracker::class,'id_user','id_user')->where("type_conversion","read");
    }

    public function total_action(){
        return $this->hasMany(CampaignTracker::class,'id_user','id_user')->where("type_conversion","action");
    }
    
    public function total_sales(){
        return $this->hasMany(CampaignTracker::class,'id_user','id_user')->where("type_conversion","acquisition");
    }

    public function wilayah(){
        return $this->belongsTo(Wilayah::class,'id_wilayah','id_wilayah');
    }

    public function saldo(){
        return $this->hasOne(RekeningDana::class,"id_user","id_user");
    }

    //CRUD
    public function insertData($data){
        $result = User::create($data);
        return $result;
    }

    public function findById($id){
        $result = User::find($id);
        return $result;
    }

    public function updateData($data){
        $result = User::where($this->primaryKey, $data[$this->primaryKey])->update($data);
        return $result;
    }

    public function removeData($id){
        $result = User::where($this->primaryKey, '=', $id)->delete();
        return $result;
    }

    public function createActivationCode()
    {
        $code = "";
        $length = 16;
        $status = true;
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        do {
            $code = "";
            for ($i = 0; $i < $length; $i++) {
                $code = $code . substr($alphabet, rand(0, strlen($alphabet) - 1), 1);
            }

            $checkavailability = User::where("activation_code", $code)->count();
            if ($checkavailability) {
                $status = true;
            } else {
                $status = false;
            }
        } while ($status);

        return $code;
    }

  //Custom Query
    public function getDetailByGoogleID($google_id)
    {
        return User::where("google_id", $google_id)
            ->where("status","active")
            ->leftjoin(DB::raw("(SELECT name as department_name, department_code, id_department FROM tb_department)dp"),"dp.id_department","=",$this->table.".id_department")
            ->first();
    }
}
