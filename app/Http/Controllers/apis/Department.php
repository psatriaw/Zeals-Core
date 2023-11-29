<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Http\Models\DepartmentModel;


class Department extends Controller
{
    public function __construct(){
      $this->department_model = new DepartmentModel();
    }

    public function getdepartmentinfo(Request $request){
      $id         = $request->id_dep;
      $department = $this->department_model->getDetail($id);

      if($department){
        $response = array(
          "status"      => 200,
          "response"    => $department
        );

        return response()->json($response,200);
      }else{
        $response = array(
          "status"      => 400,
          "response"    => null,
          "param"       => $request->all()
        );

        return response()->json($response,200);
      }

    }
}
