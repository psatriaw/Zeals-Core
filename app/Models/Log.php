<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $table        = 'tb_logs';

    protected $fillable     = ["user_id","action","description","data","subject_id","subject_type"];
    
    public function author(){
        return $this->belongsTo(User::class,"user_id","id_user");
    }
}
