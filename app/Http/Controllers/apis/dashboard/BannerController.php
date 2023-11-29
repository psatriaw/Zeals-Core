<?php

namespace App\Http\Controllers\apis\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

use App\Models\{Banner};
use Auth;

class BannerController extends Controller
{
    public function list(Request $request){
        $user       = auth('sanctum')->user();
        $limit      = $request->perPage;
        $skip       = ($request->currentPage-1)*$limit;
        $keyword    = $request->keyword;

        $subjects   = new Banner();
        $total      = $subjects->count();

        $data       = $subjects->where(function($query) use ($keyword){
                            $query->where("title",'LIKE', "%{$keyword}%");
                        })->skip($skip)->orderBy("title","ASC")->limit($limit)->get();

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

        $detail    = Banner::where("id_banner",$request->id)->first();

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
        $banner = new Banner();

        $data = array(
            "title"         => $request->title,
            "link"          => $request->link,
            "description"   => $request->description,
            "last_update"   => time(),
            "time_created"  => time()
        );

        $rules  = array(
            "title"         => "required",
            "link"          => "required",
            "banner"        => "required"
        );

        $messages = array(
            "link.required"         => "Please fill title field!",
            "title.required"        => "Please fill link field!",
            "banner.required"       => "Please fill banner field!",
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

        if($request->hasFile('banner')){
            $file           = $request->file('banner'); 
            $upload_path    = public_path('banners/');
  
            File::isDirectory($upload_path) or File::makeDirectory($upload_path, 0777, true, true);
  
            $file_name          = $file->getClientOriginalName();
            $size               = $file->getSize();
  
            $generated_new_name = time().'-'.rand(1111,9999).$file_name;
            $file->move($upload_path, $generated_new_name);
  
            $photo    = "banners/".$generated_new_name;
  
            $data['banner_path']     = $photo;
        }

        $create                 = $banner->create($data);

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
            "title"         => $request->title,
            "link"          => $request->link,
            "description"   => $request->description,
            "status"        => $request->status,
            "last_update"   => time(),
        );

        $rules  = array(
            "title"         => "required",
            "link"          => "required",
        );

        $messages = array(
            "link.required"         => "Please fill title field!",
            "title.required"        => "Please fill link field!",
            "banner.required"       => "Please fill banner field!",
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

        if($request->hasFile('banner')){
            $file           = $request->file('banner'); 
            $upload_path    = public_path('banners/');
  
            File::isDirectory($upload_path) or File::makeDirectory($upload_path, 0777, true, true);
  
            $file_name          = $file->getClientOriginalName();
            $size               = $file->getSize();
  
            $generated_new_name = time().'-'.rand(1111,9999).$file_name;
            $file->move($upload_path, $generated_new_name);
  
            $photo    = "banners/".$generated_new_name;
  
            $data['banner_path']     = $photo;
        }

        $update                 = (new Banner())->where("id_banner",$request->id_banner)->update($data);

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
        $deletion =  (new  Banner())::where("id_banner",$request->id)->delete();
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
