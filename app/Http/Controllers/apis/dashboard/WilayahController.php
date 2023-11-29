<?php

namespace App\Http\Controllers\apis\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

use App\Models\{Wilayah};

class WilayahController extends Controller
{
    public function search(Request $request){
        $keyword    = $request->keyword; 
        
        $sektors      = Wilayah::where("namakab","LIKE","%$keyword%")->orderBy("namakab","ASC")->get();

        $result     = [];
        foreach($sektors as $index=>$pack){
            $result[] = [
                "value"     => $pack->id_wilayah,
                "label"     => ucfirst($pack->namakab)." - ".ucfirst($pack->namaprov)
            ];
        }

        $result = [
            "status"        => "success",
            "data"          => $result 
        ];
        return response()->json($result,200);
    }

    public function list(Request $request){
        $user       = auth('sanctum')->user();
        $limit      = $request->perPage;
        $skip       = ($request->currentPage-1)*$limit;
        $keyword    = $request->keyword;

        $subjects   = SektorIndustri::with('campaigns','brands')->whereIn("status",["inactive",'active']);
        $total      = $subjects->count();

        $data       = $subjects->where(function($query) use ($keyword){
                            $query->where("nama_sektor_industri",'LIKE', "%{$keyword}%");
                        })->skip($skip)->orderBy("nama_sektor_industri","ASC")->limit($limit)->get();

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

        $detail    = SektorIndustri::where("id_sektor_industri",$request->id)->first();

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
        $industry = new SektorIndustri();

        $data = array(
            "nama_sektor_industri"      => $request->nama_sektor_industri,
            "status"                    => "active",
            "time_created"              => time(),
            "last_update"               => time(),
        );

        $rules  = array(
            "nama_sektor_industri"         => "required",
        );

        $messages = array(
            "nama_sektor_industri.required"        => "Please fill name field!",
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

        if($request->hasFile('icon')){
            $file           = $request->file('icon'); 
            $upload_path    = public_path('templates/category/icon/');
  
            File::isDirectory($upload_path) or File::makeDirectory($upload_path, 0777, true, true);
  
            $file_name          = $file->getClientOriginalName();
            $size               = $file->getSize();
  
            $generated_new_name = time().'-'.rand(1111,9999).$file_name;
            $file->move($upload_path, $generated_new_name);
  
            $photo    = "templates/category/icon/".$generated_new_name;
  
            $data['icon']     = $photo;
        }

        $create                 = $industry->create($data);

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
            "id_sektor_industri"        => $request->id_sektor_industri,
            "nama_sektor_industri"      => $request->nama_sektor_industri,
            "status"                    => $request->status,
            "last_update"               => time(),
        );

        $rules  = array(
            "nama_sektor_industri"         => "required",
        );

        $messages = array(
            "nama_sektor_industri.required"        => "Please fill name field!",
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

        if($request->hasFile('icon')){
            $file           = $request->file('icon'); 
            $upload_path    = public_path('templates/category/icon/');
  
            File::isDirectory($upload_path) or File::makeDirectory($upload_path, 0777, true, true);
  
            $file_name          = $file->getClientOriginalName();
            $size               = $file->getSize();
  
            $generated_new_name = time().'-'.rand(1111,9999).$file_name;
            $file->move($upload_path, $generated_new_name);
  
            $photo    = "templates/category/icon/".$generated_new_name;
  
            $data['icon']     = $photo;
        }

        $update                 = (new SektorIndustri())->where("id_sektor_industri",$request->id_sektor_industri)->update($data);

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
        $deletion =  (new  SektorIndustri())::where("id_sektor_industri",$request->id)->delete();
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
