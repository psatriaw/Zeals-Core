<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Models\WithdrawalModel;

class Withdrawal extends Controller
{
  public function __construct(){
    $this->withdrawal_model = new WithdrawalModel();
  }

  public function getresume(Request $request){
    $id_penerbit = $request->id_penerbit;

    $response = array(
      "status"  => "success",
      "total"   => $this->withdrawal_model->getResume($request->keyword,$id_penerbit),
      "percent" => "",
      "unit"    => $request->unit
    );
    return response()->json($response,200);
  }
}
