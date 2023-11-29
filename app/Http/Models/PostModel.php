<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PostModel extends Model {
    public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

    protected $table 	      = 'post';
    protected $primaryKey   = 'id_post';
    protected $fillable     = ['id_post', 'photo','time_created','author','caption','status','mention','hastag','last_update','permalink','rotation','tagged_brand','original_id',"original_poster"];

    public function getTotalPost(){
        $data = PostModel::wherein("status",array("active","inactive"))->count();
        return $data;
    }

    public function getTotalPostPermonth($year, $month){
        $open 	= strtotime($year."-".$month."-01 00:00:00");
        $close 	= strtotime($year."-".$month."-31 23:59:59");
        $data 	= PostModel::whereBetween('time_created', [$open, $close])->count();
        return $data;
    }

    public function getData($str, $limit, $keyword, $short, $shortmode){
        $data = PostModel::where(function($query) use ($keyword){
            if($keyword!=""){
                $query->where("caption","like","%".$keyword."%");
                $query->orwhere("mention","like","%".$keyword."%");
                $query->orwhere("hashtag","like","%".$keyword."%");
            }
        })
            ->wherein($this->table.".status",array("active","inactive"))
            ->leftjoin(DB::raw("(SELECT first_name as author_name,id_user FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."user"),"user.id_user","=",$this->table.".author")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_like, id_post as id_p1 FROM ".DB::getTablePrefix()."like_post GROUP BY id_post) AS ".DB::getTablePrefix()."tbl"),"tbl.id_p1","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_comment, id_post as id_p2 FROM ".DB::getTablePrefix()."comment GROUP BY id_post) AS ".DB::getTablePrefix()."tbc"),"tbc.id_p2","=",$this->table.".id_post")
            ->orderBy($short, $shortmode)
            ->skip($str)
            ->limit($limit)
            ->get();
        return $data;
    }

    public function getfriendsposts($id_user, $limit, $id_post, $keyword = "", $gender = "0", $base, $id_friend = "", $main_url="", $fr_id= 0){
        $data = PostModel::select(
                    DB::raw("\"post\" as type_data "),
                    "id_post",
                    "photo",
                    "time_created",
                    "last_update",
                    "author",
                    "caption",
                    "mention",
                    "status",
                    "hashtag",
                    "permalink",
                    "total_like",
                    "total_comment",
                    "total_share",
                    "author_name",
                    "author_avatar",
                    "following",
                    "request_status",
                    "rotation",
                    "original_id",
                    "original_poster",
                    "loved",
                    DB::raw("'0' as fundraising_target"),
                    DB::raw("'0' as fund_collected"),
                    DB::raw("'0' as paid_by_author"))
            ->leftjoin(DB::raw("(SELECT id_friendship as following, id_target,status as request_status FROM ".DB::getTablePrefix()."friendship WHERE id_author = '$id_user') AS ".DB::getTablePrefix()."tbf"),"tbf.id_target","=",$this->table.".author")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_like, id_post as idptbl FROM ".DB::getTablePrefix()."like_post GROUP BY id_post) AS ".DB::getTablePrefix()."tbl"),"tbl.idptbl","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT id_like as loved, id_post as idpliked FROM ".DB::getTablePrefix()."like_post WHERE author = '$id_user') AS ".DB::getTablePrefix()."tblike"),"tblike.idpliked","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_comment, id_post as idptbc FROM ".DB::getTablePrefix()."comment GROUP BY id_post) AS ".DB::getTablePrefix()."tbc"),"tbc.idptbc","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_share, id_post as ids FROM ".DB::getTablePrefix()."share GROUP BY id_post) AS ".DB::getTablePrefix()."tbs"),"tbs.ids","=",$this->table.".id_post")
            ->join(DB::raw("(SELECT id_user,CONCAT('$base/',avatar) as author_avatar, first_name as author_name,gender FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."user"),"user.id_user","=",$this->table.".author");

        $data2 = FundraisingModel::select(
                    DB::raw("\"fundraising\" as type_data "),
                    DB::raw(DB::getTablePrefix()."fundraising.id_fundraising as id_post "),
                    DB::raw("image_path as photo "),
                    "fundraising.time_created",
                    "fundraising.last_update",
                    DB::raw("id_member as author "),
                    DB::raw("title as caption "),
                    DB::raw("\"\" as mention"),
                    "fundraising.status",
                    DB::raw("\"\" as hashtag "),
                    DB::raw("'' as permalink"),
                    DB::raw("'null' as total_like"),
                    DB::raw("'null' as total_comment"),
                    DB::raw("'null' as total_share"),
                    DB::raw("first_name as author_name"),
                    DB::raw("CONCAT('$base/',avatar) as author_avatar"),
                    DB::raw("'null' as following"),
                    DB::raw("'null' as request_status"),
                    DB::raw("0 as rotation"),
                    DB::raw("'null' as original_id"),
                    DB::raw("'null' as original_poster"),
                    DB::raw("'null' as loved"),
                    "fundraising_target",
                    "fund_collected",
                    "paid_by_author")
                ->orderBy('fundraising.time_created', 'DESC')
                ->where('fundraising.status', 'active')
                ->join(DB::raw("(SELECT * FROM ".DB::getTablePrefix()."user)".DB::getTablePrefix()."mm"),"mm.id_user","=","fundraising.id_member")
                ->leftjoin(DB::raw("(SELECT GROUP_CONCAT(CONCAT('".$main_url."/',path) SEPARATOR '#') AS image_path, id_product FROM tb_photo,tb_product_detail WHERE ".DB::getTablePrefix()."photo.id_photo = ".DB::getTablePrefix()."product_detail.value AND ".DB::getTablePrefix()."product_detail.status = 'active' GROUP BY id_product)".DB::getTablePrefix()."tbph"),"tbph.id_product","=","fundraising.id_product")
                ->leftjoin(DB::raw("(SELECT SUM(total_fund) as fund_collected, id_fundraising FROM ".DB::getTablePrefix()."fundraising_funds GROUP BY id_fundraising)".DB::getTablePrefix()."fund"),"fund.id_fundraising","=","fundraising.id_fundraising");
        /*
        ->where(function($query) use ($keyword){
                if($keyword!=""){
    $query->where("caption","like","%".$keyword."%");
                    $query->orwhere("mention","like","%".$keyword."%");
                    $query->orwhere("hashtag","like","%".$keyword."%");
                    $query->orwhere("author_name","like","%".$keyword."%");
                }
            });
        */

        if($id_post!=0){
            $data = $data->where("id_post","<",$id_post);
        }

        if($fr_id!=0){
            $data2 = $data2->where("fundraising.id_fundraising","<",$fr_id);
        }

        if($gender!="0"){
            $data = $data->where("gender",$gender);
        }

        if($id_friend!=""){
            $data = $data->where($this->table.".author",$id_friend);
        }

        $data = $data->wherein($this->table.".status",array("active"));

        $data   = $data->orderBy("last_update","DESC");
        $data   = $data->limit($limit);

        $data = $data->union($data2)->orderBy("last_update","DESC")->get();
        //$data = $data->get();
        return $data;
    }

    public function getDetailPost($id_post, $id_user, $base){
        $data = PostModel::leftjoin(DB::raw("(SELECT id_friendship as following, id_target,status as request_status FROM ".DB::getTablePrefix()."friendship WHERE id_author = '$id_user') AS ".DB::getTablePrefix()."tbf"),"tbf.id_target","=",$this->table.".author")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_like, id_post as idptbl FROM ".DB::getTablePrefix()."like_post GROUP BY id_post) AS ".DB::getTablePrefix()."tbl"),"tbl.idptbl","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT id_like as loved, id_post as idpliked FROM ".DB::getTablePrefix()."like_post WHERE author = '$id_user') AS ".DB::getTablePrefix()."tblike"),"tblike.idpliked","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_comment, id_post as idptbc FROM ".DB::getTablePrefix()."comment GROUP BY id_post) AS ".DB::getTablePrefix()."tbc"),"tbc.idptbc","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_share, id_post as ids FROM ".DB::getTablePrefix()."share GROUP BY id_post) AS ".DB::getTablePrefix()."tbs"),"tbs.ids","=",$this->table.".id_post")
            ->join(DB::raw("(SELECT id_user,CONCAT('$base',avatar) as author_avatar, first_name as author_name FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."user"),"user.id_user","=",$this->table.".author");


        $data = $data->where("id_post",$id_post);
        $data = $data->first();
        return $data;
    }

    public function getUserPosts($id_user, $limit, $id_post, $keyword = "", $base){
        $data = PostModel::leftjoin(DB::raw("(SELECT id_friendship as following, id_target,status as request_status FROM ".DB::getTablePrefix()."friendship WHERE id_author = '$id_user') AS ".DB::getTablePrefix()."tbf"),"tbf.id_target","=",$this->table.".author")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_like, id_post as idptbl FROM ".DB::getTablePrefix()."like_post GROUP BY id_post) AS ".DB::getTablePrefix()."tbl"),"tbl.idptbl","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT id_like as loved, id_post as idpliked FROM ".DB::getTablePrefix()."like_post WHERE author = '$id_user') AS ".DB::getTablePrefix()."tblike"),"tblike.idpliked","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_comment, id_post as idptbc FROM ".DB::getTablePrefix()."comment GROUP BY id_post) AS ".DB::getTablePrefix()."tbc"),"tbc.idptbc","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_share, id_post as ids FROM ".DB::getTablePrefix()."share GROUP BY id_post) AS ".DB::getTablePrefix()."tbs"),"tbs.ids","=",$this->table.".id_post")
            ->join(DB::raw("(SELECT id_user,CONCAT('$base',avatar) as author_avatar, first_name as author_name FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."user"),"user.id_user","=",$this->table.".author")
            ->where($this->table.".author",$id_user)
            ->where(function($query) use ($keyword){
                if($keyword!=""){
                    $query->where("caption","like","%".$keyword."%");
                    $query->orwhere("mention","like","%".$keyword."%");
                    $query->orwhere("hashtag","like","%".$keyword."%");
                    $query->orwhere("author_name","like","%".$keyword."%");
                }
            });

        if($id_post!=0){
            $data = $data->where("id_post","<",$id_post);
        }
        $data = $data->orderBy("last_update","DESC");
        $data = $data->limit($limit);
        $data = $data->get();
        return $data;
    }

    public function countData($keyword){
        $data = PostModel::where(function($query) use ($keyword){
            if($keyword!=""){
                $query->where("caption","like","%".$keyword."%");
                $query->orwhere("mention","like","%".$keyword."%");
                $query->orwhere("hashtag","like","%".$keyword."%");
            }
        })
            ->wherein($this->table.".status",array("active","inactive"))
            ->count();
        return $data;
    }

    public function getDetail($id) {
        $data = PostModel::where($this->primaryKey,$id)
            ->leftjoin(DB::raw("(SELECT first_name as author_name,id_user FROM ".DB::getTablePrefix()."user) AS ".DB::getTablePrefix()."user"),"user.id_user","=",$this->table.".author")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_like, id_post as idptbl FROM ".DB::getTablePrefix()."like_post GROUP BY id_post) AS ".DB::getTablePrefix()."tbl"),"tbl.idptbl","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_comment, id_post as idptbc FROM ".DB::getTablePrefix()."comment GROUP BY id_post) AS ".DB::getTablePrefix()."tbc"),"tbc.idptbc","=",$this->table.".id_post")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_share, id_post as ids FROM ".DB::getTablePrefix()."share GROUP BY id_post) AS ".DB::getTablePrefix()."tbs"),"tbs.ids","=",$this->table.".id_post")
            ->first();
        return $data;
    }

    public function checkIsLiked($id_author, $id_post){
        $data = DB::table("like")->where("author",$id_author)->where("id_post",$id_post)->count();
        return $data;
    }

    public function likePost($data){
        return DB::table("like")->insert($data);
    }

    public function unlikePost($data){
        $statement  = DB::table("like");
        foreach ($data as $key => $value) {
            $statement =  $statement->where($key,$value);
        }
        return $hasil = $statement->delete();
    }

    public function gettotallikes($id_post){
        $data = DB::table("like")->where("id_post",$id_post)->count();
        return $data;
    }

    public function checkShared($id_author, $id_post){
        $data = DB::table("share")->where("author",$id_author)->where("id_post",$id_post)->count();
        return $data;
    }

    public function addShare($data){
        return DB::table("share")->insert($data);
    }

    public function gettotalshared($id_post){
        $data = DB::table("share")->where("id_post",$id_post)->count();
        return $data;
    }

    public function getProductUsage($id_production){
        return PostModel::find($id_production)->itemusage()->get();

    }

    public function getMaterials($id_production){
        return PostModel::find($id_production)->materialusage()->get();
    }

    public function insertData($data){
        $result = PostModel::create($data)->id_post;
        return $result;
    }

    public function updateData($data){
        $result = PostModel::where($this->primaryKey,$data[$this->primaryKey])->update($data);
        return $result;
    }


}
