<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BDBAScoring extends Model
{
    use HasFactory;
    public $timestamps    = false;

    protected $table      = 'tb_bdba_scoring';

    protected $fillable   = [
        'judger_id',
        'company_id',
        'score'
    ];
}
