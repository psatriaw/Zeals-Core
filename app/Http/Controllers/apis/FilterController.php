<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{ Penerbit };

class FilterController extends Controller
{
    public function setfilter(Request $request){
        $sets = [
            "brands"    => Penerbit::where("featured","yes")->orderBy("nama_penerbit","ASC")->get()->pluck("nama_penerbit","id_penerbit")
        ];

        $response = array(
            "status"    => "success",
            "data"      => $sets
        );

        return response()->json($response,200);

    }
}
