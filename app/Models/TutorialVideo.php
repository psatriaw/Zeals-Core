<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorialVideo extends Model
{
	public $timestamps    = false;
	const CREATED_AT      = 'time_created';
	const UPDATED_AT      = 'last_update';

  //Schema
	protected $table 	    = 'tb_tutorial_videos';
	protected $primaryKey = 'id_video';
	protected $fillable   = [
            'id_video',
            'url_video',
            'time_created',
            'last_update',
            'id_user',
            'video_code',
            'status',
            'title'
          ];

  //Relation

  //CRUD
  public function insertData($data)
  {
    $result = TutorialVideo::create($data);
    return $result;
  }
  public function findById($id)
  {
    $result = TutorialVideo::find($id);
    return $result;
  }
  public function updateData($data)
  {
    $result = TutorialVideo::where($this->primaryKey, $data[$this->primaryKey])->update($data);
    return $result;
  }
  public function removeData($id)
  {
    $result = TutorialVideo::where($this->primaryKey, '=', $id)->delete();
    return $result;
  }

  //Custom Query

}
