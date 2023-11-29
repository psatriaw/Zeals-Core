<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AccountApplicationModel extends Model {
	public $timestamps      = false;
  const CREATED_AT        = 'time_created';
  const UPDATED_AT        = 'last_update';

	protected $table 	      = 'tb_account_application';
	protected $primaryKey   = 'id_account_application';
	protected $fillable     = ['id_account_application', 'id_user','last_update','status','id_service','time_created','expiration_time','quantity'];



}
