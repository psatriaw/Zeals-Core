<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BDBAJudger extends Model
{
    use HasFactory;
    public $timestamps    = false;

    protected $table      = 'tb_bdba_judger';

    protected $fillable   = [
        'full_name',
        'institution',
        'email',
        'title',
        'phone_number',
        'password',
        'role',
        'token',
        'status'
    ];
}
