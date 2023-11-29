<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcmNotification extends Model
{
    use HasFactory;

    protected $table 	    = 'fcm_notifications';
    protected $primaryKey   = 'id';
    protected $fillable     = ["title","message","status","link"];
}
