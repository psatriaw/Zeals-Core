<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Emaillog extends Model
{
  use SoftDeletes;
  use HasFactory;

  public function batch(){
    return $this->hasOne(Emailblast::class);
  }
}
