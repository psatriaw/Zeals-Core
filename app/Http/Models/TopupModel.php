<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TopupModel extends Model
{
    public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

    protected $table         = 'topup';
    protected $primaryKey   = 'id_topup';
    protected $fillable     = ['id_topup', 'id_member', 'time_created', 'last_update', 'payment_code'];

    
}
