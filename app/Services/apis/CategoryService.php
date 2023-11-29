<?php 
namespace App\Services\apis;

use App\Models\ { SektorIndustri };
 
class CategoryService {

    public function __construct($request){
        $this->request = $request;
    }

    public function get(){
        $categories = SektorIndustri::where("status",'active')->orderBy("nama_sektor_industri","ASC")->get();
        if($categories){
            return $categories;
        }else{
            return null;
        }
    }

}

?>