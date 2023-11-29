<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CampaignProgramModel extends Model
{
    public $timestamps      = false;
    const CREATED_AT        = 'time_created';
    const UPDATED_AT        = 'last_update';

    protected $table        = 'tb_campaign_program';
    protected $primaryKey   = 'id_program';
    protected $fillable     = ['id_program', 'id_campaign', 'type_program', 'status', 'time_created', 'last_update','commission','fee','total_item','reff_code','custom_link','type'];

    public function getCustodianUser($str, $limit, $keyword, $short, $shortmode)
    {

        $data = CampaignProgramModel::where(function ($query) use ($keyword) {
            if ($keyword != "") {
                $query->where("file_name", "like", "%" . $keyword . "%");
            }
        })
            ->where($this->table . ".status", array("active"))
            ->where($this->table . ".type", "user")
            ->orderBy($short, $shortmode)
            ->skip($str)
            ->limit($limit)
            ->get();
        return $data;

    }

    public function getCustodianSaham($str, $limit, $keyword, $short, $shortmode)
    {

        $data = CampaignProgramModel::where(function ($query) use ($keyword) {
            if ($keyword != "") {
                $query->where("file_name", "like", "%" . $keyword . "%");
            }
        })
            ->where($this->table . ".status", array("active"))
            ->where($this->table . ".type", "saham")
            ->orderBy($short, $shortmode)
            ->skip($str)
            ->limit($limit)
            ->get();
        return $data;
    }

    public function getAllCommentOnPost($id_penerbit)
    {
        $data = CampaignProgramModel::wherein($this->table . ".status", array("active"))
            ->where("id_penerbit", $id_penerbit)
            ->leftjoin(DB::raw("(SELECT first_name,avatar, id_user as idu FROM tb_user)tbu"), "tbu.idu", "=", $this->table . ".id_user")
            ->orderBy("time_created", "ASC")
            ->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $hasil[$key] = $value;
                $hasil[$key]['time_created']    = date("d M Y H:i", $value->time_created);
            }
        } else {
            $hasil = "";
        }
        return $data;
    }
    public function getTotalComment()
    {
        $data = CampaignProgramModel::wherein($this->table . ".status", array("active", "inactive"))->count();
        return $data;
    }

    public function getTotalCommentsPermonth($year, $month)
    {
        $open     = strtotime($year . "-" . $month . "-01 00:00:00");
        $close     = strtotime($year . "-" . $month . "-31 23:59:59");
        $data     = CampaignProgramModel::whereBetween('time_created', [$open, $close])->count();
        return $data;
    }

    public function getData($str, $limit, $keyword, $short, $shortmode)
    {
        $data = CampaignProgramModel::where(function ($query) use ($keyword) {
            if ($keyword != "") {
                $query->where("content", "like", "%" . $keyword . "%");
            }
        })
            ->wherein($this->table . ".status", array("active", "inactive"))
            ->leftjoin(DB::raw("(SELECT first_name as author_name,id_user FROM " . DB::getTablePrefix() . "user) AS " . DB::getTablePrefix() . "user"), "user.id_user", "=", $this->table . ".author")
            ->orderBy($short, $shortmode)
            ->skip($str)
            ->limit($limit)
            ->get();
        return $data;
    }

    public function countData($keyword)
    {
        $data = CampaignProgramModel::where(function ($query) use ($keyword) {
            if ($keyword != "") {
                $query->where("content", "like", "%" . $keyword . "%");
            }
        })
            ->wherein($this->table . ".status", array("active", "inactive"))
            ->count();
        return $data;
    }

    public function getDetail($id, $base)
    {
        $data = CampaignProgramModel::where($this->primaryKey, $id)
            ->leftjoin(DB::raw("(SELECT first_name as author_name,id_user,CONCAT('$base',avatar) as avatar FROM " . DB::getTablePrefix() . "user) AS " . DB::getTablePrefix() . "user"), "user.id_user", "=", $this->table . ".author")
            ->first();
        return $data;
    }

    public function getcommentofpost($id_user, $limit, $id_post, $id_comment, $base)
    {

        $data = CampaignProgramModel::leftjoin(DB::raw("(SELECT CONCAT('$base',avatar) as avatar, first_name as author_name, last_name, id_user as ids FROM " . DB::getTablePrefix() . "user) AS " . DB::getTablePrefix() . "tbu"), "tbu.ids", "=", $this->table . ".author")
            ->where("id_post", $id_post);

        if ($id_comment != 0) {
            $data = $data->where("id_comment", "<", $id_comment);
        }


        $data = $data->orderBy("last_update", "ASC");
        $data = $data->limit($limit);
        $data = $data->get();
        return $data;
    }

    public function getProductUsage($id_production)
    {
        return CampaignProgramModel::find($id_production)->itemusage()->get();
    }

    public function getPeopleWhoComments($id_post)
    {
        $data = CampaignProgramModel::where("id_post", $id_post)
            ->leftjoin(DB::raw("(SELECT first_name,id_user,avatar,email FROM " . DB::getTablePrefix() . "user) AS " . DB::getTablePrefix() . "user"), "user.id_user", "=", $this->table . ".author")
            ->get();
        return $data;
    }

    public function removeAllData($id_campaign){
  		$result = CampaignProgramModel::where("id_campaign",$id_campaign)->update(array("last_update" => time(),"status" => 'deleted'));
  		return $result;
  	}

    public function getProgramByCampaign($type_program, $id_campaign)
    {
        return CampaignProgramModel::where("type_program",$type_program)->where("id_campaign",$id_campaign)->where("status","active")->first();
    }

    public function insertData($data)
    {
        $result = CampaignProgramModel::create($data)->id_program;
        return $result;
    }

    public function updateData($data)
    {
        $result = CampaignProgramModel::where($this->primaryKey, $data[$this->primaryKey])->update($data);
        return $result;
    }
}
