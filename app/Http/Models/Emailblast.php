<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Emailblast extends Model {
  use SoftDeletes;
  use HasFactory;

  public function logs(){
    return $this->hasMany(Emaillog::class);
  }
  
}
