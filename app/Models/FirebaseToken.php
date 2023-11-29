<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class FirebaseToken extends Model
{
    use HasFactory, SoftDeletes;

    protected $table 	    = 'tb_firebase_tokens';
	protected $primaryKey   = 'id';
    protected $fillable     = ["user_id","token","status"];

    public function user(){
        return $this->belongsTo(User::class,"id_user","user_id");
    }
}
