<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignBookmark extends Model
{
    use HasFactory;

	public $timestamps    = false;

    protected $table 	    = 'tb_campaign_bookmark';
	protected $primaryKey = 'id_bookmark';
	protected $fillable   = [
        'id_bookmark',
        'time_created',
        'last_update',
        'id_campaign',
        'id_user',
        'updated_at'
    ];
}
