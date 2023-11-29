<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\UserModel;

class DemographModel extends Model {
	public $timestamps      = false;
	const CREATED_AT        = 'time_created';
	const UPDATED_AT        = 'last_update';

	protected $table           = 'tb_user';
	protected $primaryKey   = 'id_user';

	public function demographAffiliator(){
		$result["city"]=$this->getDataByCity(15);
		$result["job"]=$this->getDataByJob(15);
		$result["activity"]=$this->getDataByCreatedDate(15);
		return $result;
	}


	public function getDataByCity($department){
		$data = UserModel::select(DB::raw('COUNT(*) as total_data'),'namakab as city')
						->join('tb_wilayah', 'tb_wilayah.id_wilayah', 'tb_user.id_wilayah')
						->where('tb_user.status','active')
						->where('id_department',$department)
						->groupBy('namakab')
						->orderBy('namakab','asc')
						// ->skip(0)
						// ->limit(25)
						->get();
		return $data;
	}
	public function getDataByJob($department){
		$data = UserModel::select(DB::raw('COUNT(*) as total_data'),'job_name as job')
						->join('tb_job', 'tb_job.id_job', 'tb_user.id_job')
						->where('tb_user.status','active')
						->where('id_department',$department)
						->groupBy('job_name')
						->orderBy('job_name','asc')
						// ->skip(0)
						// ->limit(25)
						->get();
		return $data;
	}
	public function getDataByCreatedDate($department){
		// $data = UserModel::select(DB::raw('COUNT(*) as total_data'),'job_name as job')
		// 				->join('tb_job', 'tb_job.id_job', 'tb_user.id_job')
		// 				->where('tb_user.status','active')
		// 				->where('id_department',$department)
		// 				->groupBy('job_name')
		// 				->orderBy('job_name','asc')
		// 				// ->skip(0)
		// 				// ->limit(25)
		// 				->get();

		$query      = "SELECT COUNT(1) AS total_data, (CASE"
					." WHEN date_created BETWEEN ".mktime(date("d"))." AND ".mktime(date("d")-7)." THEN '<1 week'"
					." WHEN date_created BETWEEN ".mktime(date("d"))." AND ".mktime(date("d")-30)." THEN '<1 month'"
					." WHEN date_created BETWEEN ".mktime(date("d"))." AND ".mktime(date("d")-365)." THEN '<1 year'"
					." ELSE 'More than a year'"
					." END) AS time"
					." FROM tb_user"
					." where id_department = ".$department." and status = 'active'"
					." GROUP BY time";
		$data= $this->executeQuery($query);
		return $data;
	}
	public function getWilayahAndDistribution(){
		$wilayah=WilayahModel::select(DB::raw("DISTINCT(map_code)"))->get();
		// $data = UserModel::select(DB::raw("IFNULL(total_data,0) as total_data"),"map_code")
		// 				->leftjoin(DB::raw("(SELECT COUNT(*) as total_data, id_wilayah FROM tb_user WHERE status='active' GROUP BY kode_prov)reg"),"reg.id_wilayah","=","tb_wilayah.id_wilayah")
		// 				->orderBy("total_data","DESC")
		// 				->get();

		foreach ($wilayah as $key => $value) {
				$collection[$value->map_code] = 0;
		}

		$query	="SELECT COUNT(1) AS total_data, map_code"
				." FROM tb_user"
				." JOIN tb_wilayah ON tb_wilayah.id_wilayah = tb_user.id_wilayah"
				." WHERE id_department = 15 and tb_user.status = 'active'"
				." GROUP BY map_code";
		$data= $this->executeQuery($query);
		if($data){
			foreach ($data as $key => $value) {
				if($value->map_code!=""){
					$collection[$value->map_code] = $value->total_data;
				}
			}
		}
		return $collection;
	}

	public function executeQuery($query){
		return DB::select(DB::raw($query));
	}
}
