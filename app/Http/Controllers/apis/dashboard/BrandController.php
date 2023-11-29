<?php

namespace App\Http\Controllers\apis\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

use App\Models\{Brand};
use Auth;

class BrandController extends Controller
{
    public function list(Request $request){
        $user       = auth('sanctum')->user();
        $limit      = $request->perPage;
        $skip       = ($request->currentPage-1)*$limit;
        $keyword    = $request->keyword;

        $subjects   = Brand::with('campaigns','industry')->whereIn("status",["inactive",'active']);
        $total      = $subjects->count();

        $data       = $subjects->where(function($query) use ($keyword){
                            $query->where("nama_penerbit",'LIKE', "%{$keyword}%");
                        })->skip($skip)->orderBy("nama_penerbit","ASC")->limit($limit)->get();

        if($data){
            $return = [
                "status"    => "success",
                "data"      => $data,
                "total"     => $total,
                "base_path" => url('')
            ];
        }else{
            $return = [
                "status"    => "error",
                "message"   => "No data found",
            ];
        }

        return response()->json($return, 200);
    }

    public function detail(Request $request){
        $user   = auth('sanctum')->user();

        $detail    = Brand::where("id_penerbit",$request->id)->first();

        if($detail){
            $return = [
                "status"    => "success",
                "data"      => $detail,
                "base_path" => url('')
            ];
        }else{
            $return = [
                "status"    => "error",
                "message"   => "No data found",
            ];
        }

        return response()->json($return, 200);
    }

    public function create(Request $request){
        $brand = new Brand();

        $data = array(
            "nama_penerbit"     => $request->nama_penerbit,
            "kode_penerbit"     => $brand->createCode(),
            "status"            => "active",
            "featured"          => $request->featured,
            "no_telp"           => $request->no_telp,
            "email"             => $request->email,
            "pic_name"          => $request->pic_name,
            "pic_telp"          => $request->pic_telp,
            "id_sektor_industri"    => $request->id_sektor_industri,
            "alamat"            => $request->alamat,
        );

        $rules  = array(
            "nama_penerbit"         => "required",
            "pic_name"              => "required",
            "pic_telp"              => "required",
            "email"                 => "required",
            "no_telp"               => "required",
            "id_sektor_industri"    => "required",
        );

        $messages = array(
            "nama_penerbit.required"        => "Please fill nama penerbit field!",
            "pic_name.required"             => "Please fill pic field!",
            "pic_telp.required"             => "Please fill pic phone field!",
            "email.required"                => "Please fill official email field!",
            "no_telp.required"              => "Please fill official phone field!",
            "id_sektor_industri.required"              => "Please fill Industry field!",
        );

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $error = array(
              "status"      => "error_validation",
              "data"        => $validator->messages()
            );
      
            return response()->json($error, 200);
            exit();
        }

        $create                 = $brand->create($data);

        if($create){

            $response =  [
                "status"    => "success",
                "messages"  => "Data successfully registered!"
            ];
        }else{
            $response =  [
                "status"    => "error",
                "messages"  => "Failed to register data!"
            ];
        }
       
        return response()->json($response,200);
    }

    public function update(Request $request){
        $data = array(
            "nama_penerbit"     => $request->nama_penerbit,
            "featured"          => $request->featured,
            "no_telp"           => $request->no_telp,
            "email"             => $request->email,
            "pic_name"          => $request->pic_name,
            "pic_telp"          => $request->pic_telp,
            "id_sektor_industri"    => $request->id_sektor_industri,
            "alamat"            => $request->alamat,
        );

        $rules  = array(
            "nama_penerbit"         => "required",
            "pic_name"              => "required",
            "pic_telp"              => "required",
            "email"                 => "required",
            "no_telp"               => "required",
            "id_sektor_industri"    => "required",
        );

        $messages = array(
            "nama_penerbit.required"        => "Please fill nama penerbit field!",
            "pic_name.required"             => "Please fill pic field!",
            "pic_telp.required"             => "Please fill pic phone field!",
            "email.required"                => "Please fill official email field!",
            "no_telp.required"              => "Please fill official phone field!",
            "id_sektor_industri.required"              => "Please fill Industry field!",
        );

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $error = array(
              "status"      => "error_validation",
              "data"        => $validator->messages()
            );
      
            return response()->json($error, 200);
            exit();
        }

        $update                 = (new Brand())->where("id_penerbit",$request->id_penerbit)->update($data);

        if($update){
            $response =  [
                "status"    => "success",
                "messages"  => "Data successfully updated!",
            ];
        }else{
            $response =  [
                "status"    => "error",
                "messages"  => "Failed to update data!"
            ];
        }
        return response()->json($response,200);
    }

    public function  delete(Request $request){
        $deletion =  (new  Brand())::where("id_penerbit",$request->id)->delete();
        if($deletion){
            $response =  [
                "status"    => "success",
                "messages"  => "Data successfully deleted!"
            ];
        }else{
            $response =  [
                "status"    => "error",
                "messages"  => "Failed to delete data!"
            ];
        }

        return response()->json($response,200);
    }
}
