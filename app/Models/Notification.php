<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

  //Schema
	protected $table 	    = 'tb_notification';
	protected $primaryKey   = 'id_notification';
	protected $fillable     = [
            'id_notification',
            'status',
            'time_created',
            'last_update',
            'title',
            'content',
            'id_user'
          ];

  //Relation
  public function user(){
    $this->belongsTo(User::class,'id_user','id_user');
  }

}
