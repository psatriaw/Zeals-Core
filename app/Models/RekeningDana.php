<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningDana extends Model
{
  public $timestamps    = false;
  const CREATED_AT      = 'time_created';
  const UPDATED_AT      = 'last_update';

  //Schema
  protected $table      = 'tb_rekening_dana';
  protected $primaryKey = 'id_rekening_dana';
  protected $fillable   = [
            'id_user',
            'status',
            'saldo',
          ];

  
          
}
