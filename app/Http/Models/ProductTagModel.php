<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductTagModel extends Model{
    public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

    protected $table 	    = 'product_tag';
    protected $primaryKey   = 'id_product_tag';
    protected $fillable     = ['id_product_tag', 'id_product','id_post','id_member','time_created','last_update','x_coordinate','y_coordinate'];

}
