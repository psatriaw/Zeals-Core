<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Models\PenerbitModel;

class Brand extends Controller
{
  public function __construct(){
    $this->brand_model = new PenerbitModel();
  }

  public function getresume(Request $request){
    $id_penerbit = $request->id_penerbit;

    $response = array(
      "status"  => "success",
      "total"   => $this->brand_model->getResume($request->keyword,$id_penerbit),
      "percent" => "",
      "unit"    => $request->unit
    );
    return response()->json($response,200);
  }
  public function getDataBrand(Request $request){
    $response = array(
      "status"  => "success",
      "data"   => $this->brand_model->getDataAll(),
    );
    return response()->json($response,200);
  }
}
