<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BDBACompany extends Model
{
    use HasFactory;

    public $timestamps    = false;

    protected $table      = 'tb_bdba_company';

    protected $fillable   = [
        'company_name',
        'award_nomination'
    ];
}
