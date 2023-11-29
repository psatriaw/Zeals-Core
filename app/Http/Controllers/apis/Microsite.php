<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;

use App\Http\Models\MicrositesModel;
use Illuminate\Http\Request;

class Microsite extends Controller
{
    protected $microsite_model;

    function getResume(Request $request){
      $id_penerbit = $request->id_penerbit;
      $this->microsite_model = new MicrositesModel();

      $response = array(
        "status"  => "success",
        "total"   => number_format($this->microsite_model->getResume($request->keyword,$id_penerbit),0,",","."),
        "percent" => "",
        "unit"    => $request->unit
      );
      return response()->json($response,200);
    }
}
