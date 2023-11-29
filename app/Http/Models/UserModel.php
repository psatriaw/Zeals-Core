<?php

namespace App\Http\Models;

use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    public $timestamps      = false;
    const CREATED_AT        = 'date_created';
    const UPDATED_AT        = 'last_update';

    protected $table        = 'tb_user';
    protected $primaryKey   = 'id_user';
    protected $fillable     = ['id_user', 'first_name', 'last_name', 'username', 'password', 'email', 'date_created', 'affiliate_code', 'status', 'phone', 'address', 'activation_code', 'last_update', 'id_department', 'tipe_user', 'gaji', 'insentif', 'id_reff', 'uang_makan', 'token_id','id_job','id_wilayah','avatar','google_id','referral_code','id_brand','custom_field_1','custom_field_2','gender','dob'];
    protected $prestring    = "";

    public function getUserByCodeAndStatus($status = "", $rangemin = "" ,$rangemax = "", $organization = "", $except = ""){
      $data = (new UserModel());

      if($rangemin!="" && $rangemax!=""){
        $data = $data->whereBetween("date_created",[strtotime($rangemin." 00:00:00"),strtotime($rangemax." 23:59:59")]);
      }

  		if($status!=""){
  			$data = $data->where("status",$status);
  		}

      if($organization!=""){
  			$data = $data->where("id_department",$organization);
  		}

      if($except!=""){
  			$data = $data->whereNotIn("id_department",[$except]);
  		}

      $data = $data->count();

      return $data;
    }

    public function getRandom($type){
      $code = "";
      $length = 8;
      $status = true;
      $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

      do {
          $code = "";
          for ($i = 0; $i < $length; $i++) {
              $code = $code . substr($alphabet, rand(0, strlen($alphabet) - 1), 1);
          }

          switch ($type) {
            case 'username':
              $checkavailability = UserModel::where("username", $code)->count();
            break;

            case 'email':
              $checkavailability = UserModel::where("email", $code."@zeals.asia")->count();
            break;

            case 'name':
              $checkavailability = UserModel::where("first_name", $code)->count();
            break;
          }

          if ($checkavailability) {
              $status = true;
          } else {
              $status = false;
          }
      } while ($status);

      return $code;
    }

    public function getOtp()
    {
        $code = "";
        $length = 4;
        $status = true;
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        do {
            $code = "";
            for ($i = 0; $i < $length; $i++) {
                $code = $code . substr($alphabet, rand(0, strlen($alphabet) - 1), 1);
            }

            $checkavailability = UserModel::where("otp_code", $code)->count();
            if ($checkavailability) {
                $status = true;
            } else {
                $status = false;
            }
        } while ($status);

        return $code;
    }

    public function getUserResume($keyword, $id_penerbit="", $id_department="", $start="", $end=""){

        $users = ["sum-registered","sum-activated","sum-growth","sum-monthly"];
        if(in_array($keyword,$users)){
            $data = UserModel::wherein("status", array("active","inactive"));

            switch($keyword){
                case "sum-registered":

                break;

                case "sum-activated":
                    $data = $data->where("status","active");
                break;

                case "sum-growth":
                    $query        = UserModel::where("status","active");
                    $query2       = UserModel::where("status","active");

                    $lastmonthmin = time()-(60*60*24*30*2);
                    $lastmonthmax = time()-(60*60*24*30);
                    $first     = $query->whereBetween("date_created",[$lastmonthmin,$lastmonthmax]);
                    $first     = $first->get()->count();
                    //$first      = $first->toSql();


                    $thismonthmin = time()-(60*60*24*30)+1;
                    $thismonthmax = time();
                    $second     = $query2->whereBetween("date_created",[$thismonthmin,$thismonthmax]);
                    $second     = $second->get()->count();
                    //$second     = $second->toSql();
                    if($first>0){
                        return number_format($second/$first,2)."%";
                    }else{
                        return 0;
                    }
                break;

                case "sum-monthly":
                    $thismonthmin = time()-(60*60*24*30)+1;
                    $thismonthmax = time();
                    $data         = $data->whereBetween("date_created",[$thismonthmin,$thismonthmax]);

                break;
            }
            
            if($id_department!=""){
                $data = $data->where("id_department",$id_department);
            }

        }else{
            $data = CampaignTrackerModel::whereBetween("last_update",[strtotime($start." 00:00:00"), strtotime($end." 23:59:59")]);

            switch($keyword){
                case "sum-reach":
                    $data = $data->where('type_conversion','initial');
                break;

                case "sum-visitor":
                    $data = $data->where('type_conversion','visit');
                break;

                case "sum-reader":
                    $data = $data->where('type_conversion','read');
                break;

                case "sum-acquisition":
                    $data = $data->where('type_conversion','acquisition');
                break;
            }

            if($id_department!=""){
                $data = $data->join(DB::raw("(SELECT id_user FROM tb_user WHERE id_department = $id_department)us"),"us.id_user","=","tb_campaign_tracker.id_user");
            }
        }

        $data 	= $data->count();
  		return number_format($data,0,",",".");
    }

    public function countNewComer($timer){
      return UserModel::where("status","active")->where("date_created",">",$timer)->get()->count();
    }

    public function findUsersPegawai($keyword)
    {
        $data = UserModel::where(function ($query) use ($keyword) {
            if ($keyword != "") {
                $query->where("first_name", "like", "%" . $keyword . "%");
                $query->orwhere("last_name", "like", "%" . $keyword . "%");
                $query->orwhere("email", "like", "%" . $keyword . "%");
                $query->orwhere("username", "like", "%" . $keyword . "%");
                $query->orwhere("phone", "like", "%" . $keyword . "%");
                $query->orwhere("address", "like", "%" . $keyword . "%");
            }
        })
            ->orderBy("name", "asc")
            ->orderBy("first_name", "asc")
            ->where("tipe_user", "1")
            ->join("tb_department", "tb_department.id_department", "=", $this->table . ".id_department")
            ->wherein($this->table . ".status", array("active", "inactive"))
            ->limit(10)
            ->get();
        return $data;
    }


    public function getDetailKYCUser($id)
    {
        $data = UserModel::where('id_user', $id)
            // ->leftJoin('tb_kyc_akun_bank', 'tb_kyc_akun_bank.id_user', $this->table . '.id_user')
            // ->join('tb_kyc_alamat', 'tb_kyc_alamat.id_user', $this->table . '.id_user')
            // ->join('tb_kyc_biodata_keluarga', 'tb_kyc_biodata_keluarga.id_user', $this->table . '.id_user')
            // ->join('tb_kyc_pajak', 'tb_kyc_pajak.id_user', $this->table . '.id_user')
            // ->join('tb_kyc_pekerjaan', 'tb_kyc_pekerjaan.id_user', $this->table . '.id_user')
            // ->join('tb_kyc_signed', 'tb_kyc_signed.id_user', $this->table . '.id_user')
            ->select('*')
            ->first();

        if ($data != null) {
            $kyc_akun_bank = DB::table('tb_kyc_akun_bank')->where('id_user', $id)->get();
            $data->kyc_akun_bank = $kyc_akun_bank;

            $kyc_alamat = DB::table('tb_kyc_alamat')->where('id_user', $id)->first();
            $data->kyc_alamat = $kyc_alamat;

            $kyc_biodata_keluarga = DB::table('tb_kyc_biodata_keluarga')->where('id_user', $id)->first();
            $data->kyc_biodata_keluarga = $kyc_biodata_keluarga;

            $kyc_biodata_pribadi = DB::table('tb_kyc_biodata_pribadi')->where('id_user', $id)->first();
            $data->kyc_biodata_pribadi = $kyc_biodata_pribadi;

            $kyc_pajak = DB::table('tb_kyc_pajak')->where('id_user', $id)->first();
            $data->kyc_pajak = $kyc_pajak;

            $kyc_pekerjaan = DB::table('tb_kyc_pekerjaan')->where('id_user', $id)->first();
            $data->kyc_pekerjaan = $kyc_pekerjaan;

            $kyc_signed = DB::table('tb_kyc_signed')->where('id_user', $id)->first();
            $data->kyc_signed = $kyc_signed;
        }

        return $data;
    }

    public function checkCustomField($custom_field_1){
        return UserModel::where("custom_field_1",$custom_field_1)->first();
    }


    public function findUsers($keyword)
    {
        $data = UserModel::where(function ($query) use ($keyword) {
            if ($keyword != "") {
                $query->where("first_name", "like", "%" . $keyword . "%");
                $query->orwhere("last_name", "like", "%" . $keyword . "%");
                $query->orwhere("email", "like", "%" . $keyword . "%");
                $query->orwhere("username", "like", "%" . $keyword . "%");
                $query->orwhere("phone", "like", "%" . $keyword . "%");
                $query->orwhere("address", "like", "%" . $keyword . "%");
            }
        })
            ->orderBy("name", "asc")
            ->orderBy("first_name", "asc")
            ->join("tb_department", "tb_department.id_department", "=", $this->table . ".id_department")
            ->wherein($this->table . ".status", array("active", "inactive"))
            ->limit(10)
            ->get();
        return $data;
    }

    public function getData($str, $limit, $keyword, $short, $shortmode, $id_department="", $start_date="", $end_date="")
    {

        $data = UserModel::where(function ($query) use ($keyword) {
            if ($keyword != "") {
                $query->where("first_name", "like", "%" . $keyword . "%");
                $query->orwhere("last_name", "like", "%" . $keyword . "%");
                $query->orwhere("email", "like", "%" . $keyword . "%");
                $query->orwhere("username", "like", "%" . $keyword . "%");
                $query->orwhere("phone", "like", "%" . $keyword . "%");
                $query->orwhere("address", "like", "%" . $keyword . "%");
                $query->orwhere("name", "like", "%" . $keyword . "%");
            }
        })
        ->wherein($this->table . ".status", array("active", "inactive"))
        ->select($this->table . ".*", "tb_department.name","total_reach","total_visit","total_action","total_read","total_acquisition","total_created","total_redemption","total_joined","total_downline")
        ->join("tb_department", "tb_department.id_department", "=", $this->table . ".id_department");

        if($start_date!=""){
            $start_date = strtotime($start_date." 00:00:00");
		    $end_date 	= strtotime($end_date." 23:59:59");

            $data = $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_joined, id_user as idu FROM tb_campaign_unique_link GROUP BY id_user)thej"),"thej.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_downline, referral_code FROM tb_user WHERE status = 'active' GROUP BY referral_code)reff"),"reff.referral_code","=",$this->table.".activation_code")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'initial'  GROUP BY id_user)init"),"init.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_visit,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'visit'  GROUP BY id_user)init2"),"init2.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_read,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'read'  GROUP BY id_user)init3"),"init3.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_action,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'action'  GROUP BY id_user)init4"),"init4.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_acquisition,id_user as idu FROM tb_campaign_tracker WHERE last_update BETWEEN $start_date AND $end_date AND type_conversion = 'acquisition'  GROUP BY id_user)init5"),"init5.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_created,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker  GROUP BY id_user)init7"),"init7.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND tb_voucher.status = 'used'  GROUP BY id_user)init6"),"init6.idu","=",$this->table.".id_user");
        }else{

            $data = $data->leftjoin(DB::raw("(SELECT COUNT(*) as total_joined, id_user as idu FROM tb_campaign_unique_link GROUP BY id_user)thej"),"thej.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_downline, referral_code FROM tb_user WHERE status = 'active' GROUP BY referral_code)reff"),"reff.referral_code","=",$this->table.".activation_code")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_reach,id_user as idu FROM tb_campaign_tracker WHERE  type_conversion = 'initial'  GROUP BY id_user)init"),"init.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_visit,id_user as idu FROM tb_campaign_tracker WHERE  type_conversion = 'visit'  GROUP BY id_user)init2"),"init2.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_read,id_user as idu FROM tb_campaign_tracker WHERE  type_conversion = 'read'  GROUP BY id_user)init3"),"init3.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_action,id_user as idu FROM tb_campaign_tracker WHERE  type_conversion = 'action'  GROUP BY id_user)init4"),"init4.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_acquisition,id_user as idu FROM tb_campaign_tracker WHERE  type_conversion = 'acquisition'  GROUP BY id_user)init5"),"init5.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_created,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker  GROUP BY id_user)init7"),"init7.idu","=",$this->table.".id_user")
                        ->leftjoin(DB::raw("(SELECT COUNT(*) as total_redemption,id_user as idu FROM tb_campaign_tracker, tb_voucher WHERE tb_campaign_tracker.id_tracker = tb_voucher.id_tracker AND tb_voucher.status = 'used'  GROUP BY id_user)init6"),"init6.idu","=",$this->table.".id_user");
        }

        $data = $data->orderBy($short, $shortmode)
                ->skip($str)
                ->limit($limit);

        if($id_department!=""){
          $data = $data->where($this->table.".id_department",$id_department);
        }

        $data = $data->get();
        return $data;
    }

    public function countData($keyword,$id_department="")
    {
        $data = UserModel::where(function ($query) use ($keyword) {
            if ($keyword != "") {
                $query->where("first_name", "like", "%" . $keyword . "%");
                $query->orwhere("last_name", "like", "%" . $keyword . "%");
                $query->orwhere("email", "like", "%" . $keyword . "%");
                $query->orwhere("username", "like", "%" . $keyword . "%");
                $query->orwhere("phone", "like", "%" . $keyword . "%");
                $query->orwhere("address", "like", "%" . $keyword . "%");
                $query->orwhere("name", "like", "%" . $keyword . "%");
            }
        })
            ->wherein($this->table . ".status", array("active", "inactive"))
            ->join("tb_department", "tb_department.id_department", "=", $this->table . ".id_department");

            if($id_department!=""){
              $data = $data->where($this->table.".id_department",$id_department);
            }

        $data = $data->count();
        return $data;
    }

    public function getDetailByToken($token)
    {
        $datauser = UserModel::where("activation_code", $token)
            ->select("*", DB::raw("IFNULL(saldo,0) as saldo"), DB::raw("IFNULL(asset,0) as asset"))
            ->leftjoin(DB::raw("(SELECT saldo, id_user as idu FROM tb_rekening_dana WHERE status='active' ORDER BY id_rekening_dana DESC LIMIT 0,1)rek"), "rek.idu", "=", $this->table . ".id_user")
            ->leftjoin(DB::raw("(SELECT SUM(nilai_beli*quantity) as asset,id_user as idd FROM tb_saham WHERE status='active' GROUP BY idd)saham"), "saham.idd", "=", $this->table . ".id_user")
            ->first();

        if ($datauser != null) {
            $datakyc = DB::table('tb_kyc_signed')->where('id_user', $datauser->id_user)->first();
            //$datakyc = null;
            if ($datakyc == null) {
                $datauser->status_kyc = null;
            } else {
                if ($datakyc->status == 'submitted') {
                    $datauser->status_kyc = 'submitted';
                } else if ($datakyc->status == 'approved') {
                    $datauser->status_kyc = 'approved';
                } else if ($datakyc->status == 'rejected') {
                    $datauser->status_kyc = 'rejected';
                }
            }
        }

        // dd($datauser);
        return $datauser;
    }

    public function getListUserPegawai()
    {
        return UserModel::where("tipe_user", "1")->join("tb_department", "tb_department.id_department", "=", "tb_user.id_department")->get();
    }

    public function getListPenggajianPegawai($id_penggajian)
    {
        return UserModel::where("tipe_user", "1")
            ->join("tb_department", "tb_department.id_department", "=", "tb_user.id_department")
            ->leftjoin(DB::raw("(SELECT bonus, bonus_lembur, bonus_lembur_libur, bonus_lembur_minggu, potongan_angsuran, potongan_telat, potongan_extra, potongan_tanpa_izin, id_user, gaji, total_gaji, status as status_penggajian FROM tb_penggajian_pegawai WHERE id_penggajian = '$id_penggajian')tb_penggajian_pegawai"), "tb_penggajian_pegawai.id_user", "=", $this->table . ".id_user")
            ->leftjoin(DB::raw("(SELECT COUNT(*) as total_record, id_user as pr_ids FROM tb_penggajian_record WHERE id_penggajian = '$id_penggajian' GROUP BY id_user)tpr"), "tpr.pr_ids", "=", $this->table . ".id_reff")
            ->get();
    }

    public function getUserList($code)
    {
        return UserModel::join("tb_department", "tb_department.id_department", "=", $this->table . ".id_department")
            ->where("department_code", $code)
            ->orderBy('first_name')
            ->where($this->table . ".status", "active")
            ->pluck('first_name', 'id_user');
    }

    public function getDetail($id)
    {
        $data = UserModel::where("id_user",$id)->leftjoin(DB::raw("(SELECT name as department_name, department_code, id_department FROM tb_department)dep"),"dep.id_department","=",$this->table.".id_department")->first();

        return $data;
    }

    public function activateUsers($data){
        $result = UserModel::where("id_department", $data['id_department'])->update($data);
        return $result;
    }

    public function insertData($data)
    {
        $result = UserModel::create($data)->id_user;
        return $result;
    }

    public function updateData($data)
    {
        $result = UserModel::where($this->primaryKey, $data['id_user'])->update($data);
        return $result;
    }

    public function getDetailByEmail($data)
    {
        return UserModel::where("email", $data['email'])->where("status", "active")->first();
    }

    public function getDetailByEmailInactive($data)
    {
        return UserModel::where("email", $data)->where("status", "inactive")->first();
    }

    public function checkEmailAvailable($email)
    {
        return UserModel::where("email", $email)->where("status", "active")->first();
    }

    public function removeData($id)
    {
        $result = UserModel::where($this->primaryKey, '=', $id)->delete();
        return $result;
    }

    public function getAuthPublic($data)
    {
        return UserModel::where(function ($query) use ($data) {
            if ($data['email'] != "") {
                $query->where("username", $data['email']);
                $query->orwhere("phone", $data['email']);
                $query->orwhere("email", $data['email']);
              }
            })
            ->where("password", $data['password'])
            ->where("status","active")
            ->leftjoin(DB::raw("(SELECT name as department_name, department_code, id_department FROM tb_department)dp"),"dp.id_department","=",$this->table.".id_department")
            ->first();
    }

    public function getDetailByGoogleID($google_id)
    {
        return UserModel::where("google_id", $google_id)
            ->where("status","active")
            ->leftjoin(DB::raw("(SELECT name as department_name, department_code, id_department FROM tb_department)dp"),"dp.id_department","=",$this->table.".id_department")
            ->first();
    }

    public function getAuth($data)
    {
        return UserModel::where("username", $data['username'])
            ->where("password", $data['password'])
            ->where("status","active")
            ->leftjoin(DB::raw("(SELECT name as department_name, department_code, id_department FROM tb_department)dp"),"dp.id_department","=",$this->table.".id_department")
            ->first();
    }


    public function getAuthViaEmail($data, $path, $token)
    {

        $datauser =  UserModel::where(function ($query) use ($data) {
            if ($data['username'] != "") {
                $query->where("username", $data['username']);
                $query->orwhere("phone", $data['username']);
              }
            })
            ->where("password", $data['password'])
            ->where("status", "active")
            ->join(DB::raw("(SELECT name as department_name, department_code, id_department FROM tb_department)dp"), "dp.id_department", "=", $this->table . ".id_department")
            ->leftjoin(DB::raw("(SELECT mitra_name as shop_name, address as description, id_mitra as id_shop, tb_shop_user.id_user FROM tb_fremilt_mitra, tb_shop_user WHERE tb_fremilt_mitra.id_mitra = tb_shop_user.id_shop AND tb_fremilt_mitra.status NOT IN ('deleted') AND tb_shop_user.status = 'active')ss"), "ss.id_user", "=", $this->table . ".id_user")
            ->first();

        if ($datauser) {
            $dataupdate = array(
                "token_id"    => $token,
                "id_user"        => $datauser->id_user
            );

            $this->updateData($dataupdate);

            return $datauser;
        } else {
            return 0;
        }
    }

    public function activateAccount($code, $data)
    {
        return UserModel::where("activation_code", $code)->update($data);
    }

    public function activateviaHP($code, $data)
    {
        $data =  UserModel::where("phone", $code)->update($data);
        if($data){
          return UserModel::where("phone", $code)
                ->leftjoin(DB::raw("(SELECT name as department_name, department_code, id_department FROM tb_department)dp"),"dp.id_department","=",$this->table.".id_department")
                ->first();
        }else{
          return 0;
        }
    }

    public function createActivationCode()
    {
        $code = "";
        $length = 16;
        $status = true;
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        do {
            $code = "";
            for ($i = 0; $i < $length; $i++) {
                $code = $code . substr($alphabet, rand(0, strlen($alphabet) - 1), 1);
            }

            $checkavailability = UserModel::where("activation_code", $code)->count();
            if ($checkavailability) {
                $status = true;
            } else {
                $status = false;
            }
        } while ($status);

        return $code;
    }

    public function createaffiliatecode()
    {
        $code = "";
        $length = 8;
        $status = true;
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        do {
            $code = "";
            for ($i = 0; $i < $length; $i++) {
                $code = $code . substr($alphabet, rand(0, strlen($alphabet) - 1), 1);
            }

            $checkavailability = UserModel::where("affiliate_code", $code)->count();
            if ($checkavailability) {
                $status = true;
            } else {
                $status = false;
            }
        } while ($status);

        return $code;
    }

    public function getRandomPassword($length)
    {
        $code         = "";
        $status     = true;
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        for ($i = 0; $i < $length; $i++) {
            $code = $code . substr($alphabet, rand(0, strlen($alphabet) - 1), 1);
        }
        return $code;
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function toko_user()
    {
        return $this->belongsTo(Toko::class, "id_toko");
    }
    // public function toko_user()
    // {
    //     return $this->belongsToMany(Toko::class,"id_user");
    // }

    public function getUserOpt($usseradded)
    {
        // dd($usseradded);
        $cek = (count($usseradded));
        if ($cek != 0) {
            for ($i = 0; $i < count($usseradded); $i++) {
                $user[] = $usseradded[$i]->id_user;
            }
        } else {
            $user = array();
        }
        // dd($user);
        // return $user;

        return UserModel::select("id_user", "first_name")->whereIn("id_department", [10, 12, 13, 14])->whereNotIn("id_user", $user)->orderBy('first_name')->get();
    }


    public function getDataReseller($str, $limit, $keyword, $short, $shortmode)
    {
        $data = UserModel::where(function ($query) use ($keyword) {
            if ($keyword != "") {
                $query->where("first_name", "like", "%" . $keyword . "%");
                $query->orwhere("last_name", "like", "%" . $keyword . "%");
                $query->orwhere("email", "like", "%" . $keyword . "%");
                $query->orwhere("username", "like", "%" . $keyword . "%");
                $query->orwhere("phone", "like", "%" . $keyword . "%");
                $query->orwhere("address", "like", "%" . $keyword . "%");
            }
        })
            ->wherein($this->table . ".status", array("active", "inactive"))
            ->wherein($this->table . ".id_department", array(17, 18))
            ->select($this->table . ".*", "tb_department.name")
            ->join("tb_department", "tb_department.id_department", "=", $this->table . ".id_department")
            ->orderBy($short, $shortmode)
            ->skip($str)
            ->limit($limit)
            ->get();
        return $data;
    }

    public function getUserCustodian()
    {
        $data = UserModel::where([['is_kyc_stored', 1], ['is_exported', 0]])->get();

        foreach ($data as $dt) {
            $kycpribadi = DB::table('tb_kyc_biodata_pribadi')->where('id_user', $dt->id_user)->first();
            $kyckeluarga = DB::table('tb_kyc_biodata_keluarga')->where('id_user', $dt->id_user)->first();
            $kycalamat = DB::table('tb_kyc_alamat')->where('id_user', $dt->id_user)->first();
            $kycakunbank = DB::table('tb_kyc_akun_bank')->where('id_user', $dt->id_user)->get();
            $kycpekerjaan = DB::table('tb_kyc_pekerjaan')->where('id_user', $dt->id_user)->first();
            $kycpajak = DB::table('tb_kyc_pajak')->where('id_user', $dt->id_user)->first();

            $dt->kyc_biodata_pribadi = $kycpribadi;
            $dt->kyc_biodata_keluarga = $kyckeluarga;
            $dt->kyc_alamat = $kycalamat;
            $dt->kyc_akun_bank = $kycakunbank;
            $dt->kyc_pekerjaan = $kycpekerjaan;
            $dt->kyc_pajak = $kycpajak;
        }

        if ($data != null) {
            // update is_exported
            foreach ($data as $dt) {
                $this->updateData([
                    'id_user' => $dt->id_user,
                    'is_exported' => 1
                ]);
            }
        }

        return $data;
    }
}
