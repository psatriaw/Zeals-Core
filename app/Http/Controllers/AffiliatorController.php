<?php

namespace App\Http\Controllers;

//===============================
use App\Http\Controllers\Helpers\GoogleAnalyticsHelper;
use App\Http\Models\PenerbitModel;
use App\Models\CampaignProperty;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Helpers\ZController;
use App\Http\Controllers\Helpers\IGWHelper;

use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
use Illuminate\Support\Facades\Storage;
//===============================

use App\Http\Requests\Withdrawal\{CreateWithdrawalRequest};

use App\Services\Withdrawal\{CreateWithdrawal};

use App\Http\Models\OutletModel;
use App\Http\Models\UserModel;
use App\Http\Models\VoucherModel;
use App\Http\Models\SektorIndustriModel;
use App\Http\Models\CampaignModel;
use App\Http\Models\CampaignPropertyModel;
use App\Http\Models\CampaignProgramModel;
use App\Http\Models\CampaignJoinModel;
use App\Http\Models\CampaignTrackerModel;
use App\Http\Models\WithdrawalModel;
use App\Http\Models\BankModel;
use App\Http\Models\JobModel;
use App\Http\Models\VideoModel;
use App\Http\Models\WilayahModel;
use App\Http\Models\UserPreferencesModel;
use App\Http\Models\RekeningDanaModel;
use App\Http\Models\BannerModel;
use App\Http\Models\PageModel;
use App\Http\Models\DepartmentModel;

use Intervention\Image\ImageManagerStatic as Image;
use QrCode;

class AffiliatorController extends ZController
{
	private $setting_model;
	private $previlege_model;
	private $helper;
	private $config;

	private $user_model;
	private $post_model;
	private $comment_model;
	private $like_model;
	private $page_model;

	public function __construct()
	{
		$this->setting_model    = new SettingModel();
		$this->previlege_model  = new PrevilegeModel();
		$this->helper           = new IGWHelper();

		$config['main_url'] = "master/post";
		$config['dashboard']    = "affiliate-dashboard";
		$config['campaign']     = "affiliate-campaign";
		$config['logs']         = "affiliate-logs";
		$config['faq']          = "affiliate-faq";
		$config['tutorial']     = "affiliate-tutorial";
		$config['wallet']       = "affiliate-wallet";
		$config['profile']      = "affiliator-profile";

		$config['view']     = "post-view";
		$config['manage']   = "post-manage";
		$config['remove']   = "post-remove";
		$config['restore']  = "post-restore";
		$config['create']   = "post-create";

		$this->config       = $config;

		$this->user_model       = new UserModel();
		$this->outlet_model               = new OutletModel();
		$this->sektor_industri_model      = new SektorIndustriModel();
		$this->campaign_model             = new CampaignModel();
		$this->tutorial_model             = new VideoModel();
		$this->campaign_property_model    = new CampaignPropertyModel();
		$this->campaign_program_model     = new CampaignProgramModel();
		$this->campaign_join_model        = new CampaignJoinModel();
		$this->campaign_tracker_model     = new CampaignTrackerModel();
		$this->withdrawal_model           = new WithdrawalModel();
		$this->bank_model                 = new BankModel();
		$this->job_model                  = new JobModel();
		$this->wilayah_model              = new WilayahModel();
		$this->user_preferences_model     = new UserPreferencesModel();
		$this->rekening_dana_model        = new RekeningDanaModel();
		$this->voucher_model              = new VoucherModel();
		$this->banner_model               = new BannerModel();
		$this->page_model                 = new PageModel();
	}

	public function generate2ndlink(Request $request, $unique_id)
	{
		//format URL = domain/create2ndlink/
		//$unique_id    = Request::segment(1);
		//uniquelink active

		$check_code = $this->campaign_join_model->checkCode($unique_id);
		if ($check_code) {
			$infocampaign = $this->campaign_model->getDetail($check_code->id_campaign);
			//print_r($check_code);
			//exit();

			$detail       = $this->campaign_model->getDetail($infocampaign->id_campaign);
			$datajoin = array(
				"id_campaign"   => 26,
				"time_created"  => time(),
				"last_update"   => time(),
				"id_user"       => $check_code->id_user,
				"unique_link"   => $this->campaign_join_model->createLink()
			);

			//print_r($datajoin);
			//exit();
			$joined = $this->campaign_join_model->isproductJoined($check_code->id_user, $infocampaign->id_campaign);
			if ($joined) {
				return redirect(url('/link/' . $joined->unique_link));
			} else {
				$this->campaign_join_model->insertData($datajoin);
				return redirect(url('/link/' . $datajoin['unique_link']));
			}
			//Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Congratulation! You are joining the campaign rightnow!']);

		} else {
			echo ":(";
		}
		//uniquelink active

	}

	public function uservoucherevent(Request $request)
	{
		$voucher_code = $request->voucher_code;
		$outlet_id   = $request->outlet_id;

		$info_voucher = $this->voucher_model->getDetailByCode($voucher_code);

		if ($info_voucher) {
			$updatevoucher = array(
				"id_voucher"        => $info_voucher->id_voucher,
				"id_outlet_usage"   => $outlet_id,
				"last_update"       => time(),
				"status"            => "used",
			);

			$return = array(
				"status"    => "success",
				"response"  => "Voucher redemption success!"
			);

			$usevoucher = $this->voucher_model->updateData($updatevoucher);
		} else {
			$return = array(
				"status"    => "error",
				"response"  => "Voucher tidak dikenali!"
			);
		}

		return response(json_encode($return), 200);
	}

	public function fillevent(Request $request)
	{
		$voucher_code = $request->voucher_code;
		$info_voucher = $this->voucher_model->getDetailByCode($voucher_code);
		//verifikasi nomor telp
		$data = array(
			"optin_name"          => $request->optin_name,
			"optin_email"         => $request->optin_email,
			"optin_address"       => $request->optin_address,
			"optin_phone"         => $request->optin_phone,
			"additional_1"        => $request->additional_1,
			"disclaimer"          => $request->disclaimer,
			"optin_source"        => $request->optin_source,
			"optin_other_source"  => $request->optin_other_source,
			"optin_dob"           => $request->optin_dob,
			"optin_institution"   => $request->optin_institution,
			"optin_institution_name"  => $request->optin_institution_name,
			"optin_institution_division"  => $request->optin_institution_division,
		);

		$rules  = array(
			"optin_name"          => "required",
			"optin_email"         => "required|email",
			"optin_phone"         => 'required|numeric'
		);

		$messages = array(
			"optin_name.required"     => "Mohon mengisi nama anda disini",
			"optin_email.required"    => "Mohon mengisi email anda disini",
			"optin_email.email"       => "Penulisan email tidak valid",
			"optin_address.required"  => "Mohon mengisi alamat anda disini",
			"optin_phone.required"    => "Nomor Telp Dibutuhkan",
			"optin_phone.numeric"     => "Nomor Telp Harus berupa angka",
		);

		$validator = Validator::make($data, $rules, $messages);

		if ($validator->fails()) {
			$errorsarray  = array();
			$error        = json_decode($validator->messages(), true);

			foreach ($error as $key => $val) {
				foreach ($val as $kk => $vv) {
					$errorsarray[] = $vv;
				}
			}

			$return = array(
				"status"    => "invalid",
				"response"  => implode("!<br>", $errorsarray) . "!"
			);
		} else {

			//limit email
			$checklimitemail = $this->voucher_model->checkRecordWithEmail($data['optin_email'], "", $info_voucher->id_campaign);
			if ($checklimitemail) {
				$return = array(
					"status"    => "invalid",
					"response"  => "Email Sudah digunakan untuk redemption! Coba gunakan email anda yang aktif!"
				);
			} else {
				//verifikasi email
				$email  = $request->optin_email;
				$key    = $this->setting_model->getSettingVal("emaillistverify_api");

				// $curl = curl_init();
				//
				// curl_setopt_array($curl, [
				//  CURLOPT_URL => "https://ajith-verify-email-address-v1.p.rapidapi.com/varifyEmail?email=".$email,
				//  CURLOPT_RETURNTRANSFER => true,
				//  CURLOPT_FOLLOWLOCATION => true,
				//  CURLOPT_ENCODING => "",
				//  CURLOPT_MAXREDIRS => 10,
				//  CURLOPT_TIMEOUT => 30,
				//  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				//  CURLOPT_CUSTOMREQUEST => "GET",
				//  CURLOPT_HTTPHEADER => [
				//    "x-rapidapi-host: ajith-Verify-email-address-v1.p.rapidapi.com",
				//    "x-rapidapi-key: ".$key
				//  ],
				// ]);
				//
				// $response = curl_exec($curl);
				// $err = curl_error($curl);
				//
				// curl_close($curl);
				//
				// if ($err) {
				//  $hasil['exist'] = "false";
				// } else {
				//  $hasil = json_decode($response,true);
				// }

				// if($hasil['exist']){

				$updatevoucher = array(
					"id_voucher"        => $info_voucher->id_voucher,
					"last_update"       => time(),
					"status"            => "active",
					"optin_name"        => $request->optin_name,
					"optin_email"       => $request->optin_email,
					"optin_phone"       => $request->optin_phone,
					"optin_address"     => $request->optin_address,
					"additional_1"      => $request->additional_1,
					"disclaimer"        => $request->disclaimer,
					"optin_source"        => $request->optin_source,
					"optin_other_source"  => $request->optin_other_source,
					"optin_dob"  => $request->optin_dob,
					"optin_institution_division"  => $request->optin_institution_division,
					"optin_institution_name"  => $request->optin_institution_name,
					"optin_institution"  => $request->optin_institution,
				);

				$usevoucher = $this->voucher_model->updateData($updatevoucher);

				$realpath  = "uploads/" . $request->voucher_code . ".png";
				$path      = public_path($realpath);

				//QrCode::size(100)->generate($request->voucher_code, $path);

				$image = QrCode::format('png')->size(300)->generate($request->voucher_code, $path);


				// $output_file = $path;
				// Storage::disk('local')->put($output_file, $image);
				$campaign = $this->campaign_model->getDetail($info_voucher->id_campaign);
				$updatevoucher['qr_path'] = url($realpath);
				$updatevoucher['description'] = $campaign->campaign_description;
				$updatevoucher['event_title'] = $campaign->campaign_title;

				Mail::send('emails.event_attendance', $updatevoucher, function ($mail) use ($updatevoucher) {
					$this->settingmodel = new SettingModel();

					$sender             = $this->setting_model->getSettingVal("email_sender_name");
					$senderaddress      = $this->setting_model->getSettingVal("email_sender_address");

					$mail->from($senderaddress, $sender);
					$mail->to($updatevoucher['optin_email'], $updatevoucher['optin_name']);
					$mail->subject('Event Passport');
				});


				$return = array(
					"status"    => "valid",
					"response"  => "Congratulations! <br> we sent you an email with QRcode for future checkin of this event. Please make sure you get the email by checking your inbox!",
					"qr_path"   => $realpath
				);
				// }else{
				//     $return = array(
				//      "status"    => "invalid",
				//      "response"  => "Email Tidak valid! Coba gunakan email anda yang aktif!"
				//    );
				// }
			}
			//limit email
		}

		return response(json_encode($return), 200);
	}

	public function usevoucer(Request $request)
	{
		$outlet_code  = $request->outlet_code;
		$voucher_code = $request->voucher_code;

		$info_voucher = $this->voucher_model->getDetailByCode($voucher_code);
		$info_outlet  = $this->outlet_model->getDetailByCode($outlet_code);

		if (@$info_outlet->id_campaign == @$info_voucher->id_campaign) {

			if ($info_outlet->max_redemption_per_day != "") {
				$max          = $info_outlet->max_redemption_per_day;
				$total_today  = $this->voucher_model->getTotalVoucherOnOutletTotay($info_outlet->id_outlet);
				if ($total_today > $max) {
					$return = array(
						"status"    => "invalid",
						"response"  => "This outlet has reached total maximum redemption for a day. Please comeback the next day."
					);

					return response(json_encode($return), 200);
					exit();
				}
			}

			if ($info_outlet->max_redemption != "") {
				$max          = $info_outlet->max_redemption;
				$total_today  = $this->voucher_model->getTotalVoucherOnOutlet($info_outlet->id_outlet);
				if ($total_today > $max) {
					$return = array(
						"status"    => "invalid",
						"data"      => $total_today . " " . $max,
						"info_voucher"  => $info_outlet,
						"response"  => "This outlet has reached total maximum redemption. Please try another outlet below."
					);

					return response(json_encode($return), 200);
					exit();
				}
			}

			//verifikasi nomor telp
			$data = array(
				"optin_name"          => $request->optin_name,
				"optin_email"         => $request->optin_email,
				"optin_address"       => $request->optin_address,
				"optin_phone"         => $request->optin_phone,
				"additional_1"        => $request->additional_1,
				"disclaimer"          => $request->disclaimer,
				"optin_source"        => $request->optin_source,
				"optin_other_source"  => $request->optin_other_source,
				"optin_dob"  => $request->optin_dob,
				"optin_institution"  => $request->optin_institution,
				"optin_institution_name"  => $request->optin_institution_name,
				"optin_institution_division"  => $request->optin_institution_division,
			);

			$rules  = array(
				"optin_name"          => "required",
				"optin_email"         => "required|email",
				"optin_address"       => "required|max:256",
				"optin_phone"         => 'required|numeric'
			);

			$messages = array(
				"optin_name.required"     => "Mohon mengisi nama anda disini",
				"optin_email.required"    => "Mohon mengisi email anda disini",
				"optin_email.email"       => "Penulisan email tidak valid",
				"optin_address.required"  => "Mohon mengisi alamat anda disini",
				"optin_phone.required"    => "Nomor Telp Dibutuhkan",
				"optin_phone.numeric"     => "Nomor Telp Harus berupa angka",
			);

			$validator = Validator::make($data, $rules, $messages);

			if ($validator->fails()) {
				$errorsarray  = array();
				$error        = json_decode($validator->messages(), true);

				foreach ($error as $key => $val) {
					foreach ($val as $kk => $vv) {
						$errorsarray[] = $vv;
					}
				}

				$return = array(
					"status"    => "invalid",
					"response"  => implode("!<br>", $errorsarray) . "!"
				);
			} else {

				//limit email
				$checklimitemail = $this->voucher_model->checkRecordWithEmail($data['optin_email'], $info_outlet->id_outlet);
				if ($checklimitemail) {
					$return = array(
						"status"    => "invalid",
						"response"  => "Email Sudah digunakan untuk redemption! Coba gunakan email anda yang aktif!"
					);
				} else {
					//verifikasi email
					$email  = $request->optin_email;
					$key    = $this->setting_model->getSettingVal("emaillistverify_api");

					$curl = curl_init();

					curl_setopt_array($curl, [
						CURLOPT_URL => "https://ajith-verify-email-address-v1.p.rapidapi.com/varifyEmail?email=" . $email,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 30,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "GET",
						CURLOPT_HTTPHEADER => [
							"x-rapidapi-host: ajith-Verify-email-address-v1.p.rapidapi.com",
							"x-rapidapi-key: " . $key
						],
					]);

					$response = curl_exec($curl);
					$err = curl_error($curl);

					curl_close($curl);

					if ($err) {
						$hasil['exist'] = "false";
					} else {
						$hasil = json_decode($response, true);
					}

					// if($hasil['exist']){

					$updatevoucher = array(
						"id_voucher"        => $info_voucher->id_voucher,
						"time_usage"        => time(),
						"id_outlet_usage"   => $info_outlet->id_outlet,
						"last_update"       => time(),
						"status"            => "used",
						"optin_name"        => $request->optin_name,
						"optin_email"       => $request->optin_email,
						"optin_phone"       => $request->optin_phone,
						"optin_address"     => $request->optin_address,
						"additional_1"      => $request->additional_1,
						"additional_2"      => $request->additional_2,
						"disclaimer"        => $request->disclaimer,
						"optin_source"         => $request->optin_source,
						"optin_other_source"   => $request->optin_other_source,
						"optin_dob"            => $request->optin_dob,
						"optin_institution_division"  => $request->optin_institution_division,
						"optin_institution_name"   => $request->optin_institution_name,
						"optin_institution"        => $request->optin_institution,
					);

					$usevoucher = $this->voucher_model->updateData($updatevoucher);

					//acquisition
					/*
                 $check = $this->campaign_tracker_model->getDetailTrackerFromUniquecode('initial',$kode);
                 if($check){
                   $infoprogram = $this->campaign_program_model->getProgramByCampaign('acquisition',$check->id_campaign);
                   $alldata     = $check->toArray();

                   unset($alldata['id_tracker']);
                   $alldata['time_created']           = time();
                   $alldata['last_update']            = time();
                   $alldata['status']                 = "active";
                   $alldata['type_conversion']        = "acquisition";
                   $alldata['commission']             = ($check->id_user!=0)?@$infoprogram->commission:0;
                   $alldata['fee']                    = ($check->id_user!=0)?@$infoprogram->fee:0;
                   $alldata['callback_url']           = @$url;
                   $alldata['domain']                 = @$domain;
                   $alldata['protocol']               = @$protocol;


                   $checkdataavailable = $this->campaign_tracker_model->checkTrackerRecord('acquisition',$kode);
                   if(!$checkdataavailable){
                     $this->campaign_tracker_model->insertData($alldata);
                     $return['status'] = "success";

                   }else{
                     $return = array(
                       "status"    => "error",
                       "status"    => "Data duplikasi, tidak diperkenankan"
                     );
                   }
                 }else{
                   $return = array(
                     "status"    => "error",
                     "response"  => "Encrypted code tidak dikenali"
                   );
                 }
                 */
					//acquisition

					$return = array(
						"status"    => "valid",
						"response"  => "Congratulations! <br> Your voucher is valid! Please show this code to the receptionis/PIC on the outlet.<br><br><strong style='font-size:30px;'>" . $voucher_code . "</strong>" . "<div class='text-center' style='margin-top:25px;font-size:13px;color:#ffffff;'>Get more vouchers on Zeals Affiliate app <br><a href='https://play.google.com/store/apps/details?id=asia.zeals.mobile.pwa'><img src='https://app.zeals.asia/referrals/google-play-badge.png' style='width:120px;'/></a></div>"
					);
					// }else{
					//     $return = array(
					//      "status"    => "invalid",
					//      "response"  => "Email Tidak valid! Coba gunakan email anda yang aktif!"
					//    );
					// }
				}
				//limit email
			}
			//verifikasi nomor telp

		} else {
			$return = array(
				"status"    => "invalid",
				"response"  => "Invalid outlet for this voucher!"
			);
		}

		return response(json_encode($return), 200);
	}

	public function checkOutletsarea(Request $request)
	{
		$id_campaign = $request->campaign_id;
		$latitude    = $request->lat;
		$longitude   = $request->lng;
		$html        = "";

		$outlets     =  $this->outlet_model->getListOutletByDistance($id_campaign, $longitude, $latitude);

		if ($outlets) {

			foreach ($outlets as $index => $outlet) {
				$jarak = number_format($outlet->distance, 2) . "km";
				$html = $html . "<p><i class='fa fa-map-marker' style='width:15px;'></i> <strong>" . $outlet->outlet_name . "(" . $jarak . ")</strong><br><span class='text-small' style='padding-left:20px;display:inline-block;'>" . $outlet->outlet_address . "</span></p>";
			}

			$return = array(
				"status"  => "valid",
				"html"    => $html
			);
		} else {
			$outlets  = $this->outlet_model->getListOutlet($id_campaign, $longitude, $latitude);

			foreach ($outlets as $index => $outlet) {
				$jarak = number_format($outlet->distance, 2) . "km";
				$html = $html . "<p><i class='fa fa-map-marker' style='width:15px;'></i> <strong>" . $outlet->outlet_name . "(" . $jarak . ")</strong><br><span class='text-small' style='padding-left:20px;display:inline-block;'>" . $outlet->outlet_address . "</span></p>";
			}

			$return = array(
				"status"  => "valid",
				"html"    => $html
			);
		}

		return response(json_encode($return), 200);
	}

	public function checkOutlet(Request $request)
	{
		$outlet_code  = $request->outlet_code;
		$voucher_code = $request->voucher_code;

		$info_voucher = $this->voucher_model->getDetailByCode($voucher_code);
		$info_outlet  = $this->outlet_model->getDetailByCode($outlet_code);

		if (@$info_outlet->id_campaign == @$info_voucher->id_campaign) {
			$return = array(
				"status"  => "valid"
			);
		} else {
			$return = array(
				"status"  => "invalid",
				"voucher" => $info_voucher,
				"outlet"  => $info_outlet
			);
		}

		return response(json_encode($return), 200);
	}

	public function getvoucher($encrypted_code)
	{
		$tracker  = $this->campaign_tracker_model->getDetailTrackerFromUniquecode("initial", $encrypted_code);

		if ($tracker) {
			$campaign         = $this->campaign_model->getDetail($tracker->id_campaign);
			$voucher_code     = $this->voucher_model->createCode();
			$datavoucher      = array(
				"time_created"    => time(),
				"last_update"     => time(),
				"voucher_code"    => $voucher_code,
				"status"          => 'active',
				"id_tracker"      => $tracker->id_tracker,
				"id_campaign"     => $tracker->id_campaign
			);

			$this->voucher_model->insertData($datavoucher);

			return response(json_encode($datavoucher), 200);
		} else {
			$datavoucher = array();
			return response(json_encode($datavoucher), 400);
		}
	}

	public function voucher()
	{
		$encrypted_code = $_GET['encrypted_code'];
		$login          = Session::get("aff");
		$webname        = $this->setting_model->getSettingVal("website_name");
		$tracker        = $this->campaign_tracker_model->getDetailTrackerFromUniquecode("initial", $encrypted_code);

		if ($tracker) {
			$campaign    = $this->campaign_model->getDetail($tracker->id_campaign);
			$datacontent = array(
				"campaign"      => $campaign,
				"outlets"       => "", //$this->outlet_model->getListOutlet($campaign->id_campaign),
				"aff_attr"      => $this->campaign_join_model->checkCode($tracker->unique_link)
			);

			if ($campaign->campaign_type == 'event') {
				$view     = View::make("frontend.event", $datacontent);
			} else {
				$view     = View::make("frontend.voucher", $datacontent);
			}

			$content  = $view->render();

			$data     = array(
				"content"                 => $content,
				"login"                   => @$login,
				"page"                    => "admin_dashboard",
				"submenu"                 => "admin_dashboard",
				"helper"                  => $this->helper,
				"previlege"               => $this->previlege_model,
				"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
				"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
				"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
			);

			return view("frontend.body", $data);
		} else {
			$view     = View::make("frontend.404");
			$content  = $view->render();

			$data     = array(
				"content"                 => $content,
				"login"                   => $login,
				"page"                    => "admin_dashboard",
				"submenu"                 => "admin_dashboard",
				"helper"                  => $this->helper,
				"previlege"               => $this->previlege_model,
				"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
				"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
				"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
				"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
				"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
				"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
				"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
				"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
				"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
				"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
			);

			return view("frontend.body", $data);
		}
	}

	public function callbackACQ(Request $request, $aff_id, $campaign)
	{
		print_r($aff_id . " " . $campaign);

		$infoprogram = $this->campaign_program_model->getProgramByCampaign('acquisition', $check->id_campaign);
		$alldata     = $check->toArray();

		$check = $this->campaign_tracker_model->getDetailTrackerFromUniquecode('initial', $kode);
		if ($check) {
		} else {
		}

		unset($alldata['id_tracker']);
		$alldata['time_created']           = time();
		$alldata['last_update']            = time();
		$alldata['status']                 = "active";
		$alldata['type_conversion']        = "acquisition";
		$alldata['commission']             = @$infoprogram->commission;
		$alldata['fee']                    = @$infoprogram->fee;
		$alldata['callback_url']           = @$url;
		$alldata['domain']                 = @$domain;
		$alldata['protocol']               = @$protocol;


		$checkdataavailable = $this->campaign_tracker_model->checkTrackerRecord('acquisition', $kode);
		if (!$checkdataavailable) {
			$this->campaign_tracker_model->insertData($alldata);
			$return = array(
				"status"    => "true"
			);
		} else {
			$return = array(
				"status"    => "duplicated"
			);
		}

		return response()->json($return, 200);
	}

	public function dashboard()
	{

		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['dashboard'])) {

				$statistic_visit        = $this->campaign_tracker_model->getTotalTracker('visit', "", $login);
				$statistic_read         = $this->campaign_tracker_model->getTotalTracker('read', "", $login);
				$statistic_action       = $this->campaign_tracker_model->getTotalTracker('action', "", $login);
				$statistic_acquisition  = $this->campaign_tracker_model->getTotalTracker('acquisition', "", $login);
				$infosaldo              = $this->rekening_dana_model->getCurrentRekening($login->id_user);

				$datacontent = array(
					"login"          => $login,
					"saldo"          => $infosaldo->saldo,
					"campaigns"      => $this->campaign_model->getListCampaignAvailable($login),
					"logs"           => $this->campaign_tracker_model->getLast15Logs("", $login),
					"earning"         => array(
						"estimation"    => $this->campaign_tracker_model->sumTotalEstimation("", $login),
						"total"         => $this->campaign_tracker_model->sumTotalEarning("", $login),
						"top10"         => $this->campaign_model->getMost10Earning($login)
					)
				);

				if ($statistic_visit > 0) {
					$datacontent['statistic']['visit'] = array(
						"total"       => $statistic_visit,
						"percent"     => ($statistic_read * 100) / $statistic_visit
					);

					$datacontent['chart']['visit'] = array(
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', (4 * 60 * 60 * 24) + 1, 5 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', (3 * 60 * 60 * 24) + 1, 4 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', (2 * 60 * 60 * 24) + 1, 3 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', (1 * 60 * 60 * 24) + 1, 2 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', (12 * 60 * 60) + 1, 1 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', 0, 12 * 60 * 60, "", $login)
					);

					$datacontent['statistic']['read'] = array(
						"total"       => $statistic_read,
						"percent"     => ($statistic_read * 100) / $statistic_visit
					);

					$datacontent['chart']['read'] = array(
						$this->campaign_tracker_model->getTransactionTotalInRange('read', (4 * 60 * 60 * 24) + 1, 5 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('read', (3 * 60 * 60 * 24) + 1, 4 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('read', (2 * 60 * 60 * 24) + 1, 3 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('read', (1 * 60 * 60 * 24) + 1, 2 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('read', (12 * 60 * 60) + 1, 1 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('read', 0, 12 * 60 * 60, "", $login)
					);

					$datacontent['statistic']['action'] = array(
						"total"       => $statistic_action,
						"percent"     => ($statistic_action * 100) / $statistic_visit
					);

					$datacontent['chart']['action'] = array(
						$this->campaign_tracker_model->getTransactionTotalInRange('action', (4 * 60 * 60 * 24) + 1, 5 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('action', (3 * 60 * 60 * 24) + 1, 4 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('action', (2 * 60 * 60 * 24) + 1, 3 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('action', (1 * 60 * 60 * 24) + 1, 2 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('action', (12 * 60 * 60) + 1, 1 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('action', 0, 12 * 60 * 60, "", $login)
					);

					$datacontent['statistic']['acquisition'] = array(
						"total"       => $statistic_acquisition,
						"percent"     => ($statistic_acquisition * 100) / $statistic_visit
					);

					$datacontent['chart']['acquisition'] = array(
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', (4 * 60 * 60 * 24) + 1, 5 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', (3 * 60 * 60 * 24) + 1, 4 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', (2 * 60 * 60 * 24) + 1, 3 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', (1 * 60 * 60 * 24) + 1, 2 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', (12 * 60 * 60) + 1, 1 * 60 * 60 * 24, "", $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', 0, 12 * 60 * 60, "", $login)
					);
				}

				$view     = View::make("frontend.dashboard", $datacontent);
				$content  = $view->render();
				$infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);
				$data     = array(
					"saldo"                   => $infosaldo->saldo,
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
					"joined_campaign"         => $this->campaign_model->getJoinedCampaignByUser($login->id_user, 5),
					"might_like"              => $this->campaign_model->getMightlikeCampaign($login->id_user, 3)
				);

				return view("frontend.body", $data);
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function campaign(Request $request, $id_category = "")
	{
		$login    = Session::get("aff");
		$keyword = !empty($request->keyword) ? $request->keyword : '';
		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['campaign'])) {
				$infosaldo              = $this->rekening_dana_model->getCurrentRekening($login->id_user);

				$datacontent = array(
					"login"         => $login,
					"saldo"         => $infosaldo->saldo,
					"detail"        => $this->user_model->getDetail($login->id_user),
					"campaigns"     => $this->campaign_model->getListCampaignAvailable($login, $id_category, $keyword, 100),
					//"categories"    => $this->sektor_industri_model->getPluckPerusahaan(),
					//"categories"    => $this->user_preferences_model->getUserLabelPreferences($login->id_user),
					"categories"      => $this->sektor_industri_model->getAllDataActive(),
					"selected_category" => @$id_category,
					"banners"         => $this->banner_model->getAllBanners(url('')),
				);

				$view     = View::make("frontend.campaign_list", $datacontent);
				$content  = $view->render();
				$infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);
				$data     = array(
					"saldo"                   => $infosaldo->saldo,
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
					"joined_campaign"         => $this->campaign_model->getJoinedCampaignByUser($login->id_user, 5),
					"might_like"              => $this->campaign_model->getMightlikeCampaign($login->id_user, 3)
				);

				return view("frontend.body", $data);
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function joincampaign($id)
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['campaign'])) {
				$detail = $this->campaign_model->getDetail($id);
				$datajoin = array(
					"id_campaign"   => $id,
					"time_created"  => time(),
					"last_update"   => time(),
					"id_user"       => $login->id_user,
					"unique_link"   => $this->campaign_join_model->createLink()
				);

				$this->campaign_join_model->insertData($datajoin);

				Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Congratulation! You are joining the campaign rightnow!']);
				return redirect(url('campaign/detail/' . $detail->campaign_link . '#unique_link'));
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function AMPcallback(Request $request)
	{
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: *");

		$headers = apache_request_headers();

		foreach ($headers as $header => $value) {
			$return[$header] = $value;
		}

		$kode       = @$return['encrypted_code'];
		$type       = "acquisition";
		$protocol   = $request->protocol;
		$domain     = "";
		$url        = "";

		/*
    $body   = file_get_contents('php://input');
    $body   = json_decode($body,true);
    print_r($body);
    exit();
    */

		if ($kode == "" || !$return['aff_id']) {
			$body   = file_get_contents('php://input');
			$body   = json_decode($body, true);

			foreach ($body as $itembody => $value) {
				$return[$itembody] = $value;
			}

			$kode       = @$return['encrypted_code'];
			$type       = "acquisition";
			$protocol   = @$return['transaction_value'];
			$domain     = @$return['unique_random_code'];
			$url        = "";
		}

		if ($kode != "" && $return['aff_id']) {

			$check = $this->campaign_tracker_model->getDetailTrackerFromUniquecode('initial', $kode);
			if ($check) {
				$infoprogram = $this->campaign_program_model->getProgramByCampaign('acquisition', $check->id_campaign);
				if (@$infoprogram->type == "percent") {
					$commission = ($infoprogram->commission / 100) * $protocol;
					$fee        = ($infoprogram->fee / 100) * $protocol;
					$tier2      = 0.04 * $protocol;
					$tier3      = 0.01 * $protocol;
				} else {
					$commission = $infoprogram->commission;
					$fee        = $infoprogram->fee;
					$tier2      = 0.04 * $infoprogram->commission;
					$tier3      = 0.01 * $infoprogram->commission;
				}

				$alldata     = $check->toArray();

				unset($alldata['id_tracker']);
				$alldata['time_created']           = time();
				$alldata['last_update']            = time();
				$alldata['status']                 = "active";
				$alldata['type_conversion']        = "acquisition";
				$alldata['commission']             = ($check->id_user != 0) ? @$commission : 0;
				$alldata['fee']                    = ($check->id_user != 0) ? @$fee : 0;
				$alldata['callback_url']           = @$url;
				$alldata['domain']                 = @$domain;
				$alldata['protocol']               = @$protocol;


				$checkdataavailable = $this->campaign_tracker_model->checkTrackerRecord('acquisition', $kode);
				if (!$checkdataavailable) {
					$this->campaign_tracker_model->insertData($alldata);

					//commission beruntun
					$user   = UserModel::where("id_user", $check->id_user)->first();
					$bapak  = UserModel::where("activation_code", @$user->referral_code)->first();
					if (@$bapak) {
						$copy   = $alldata;
						$copy['commission'] = $tier2;
						$copy['fee']        = 0;
						$copy['id_user']    = $bapak->id_user;
						$copy['type_conversion']        = "commission";

						$this->campaign_tracker_model->insertData($copy);

						$kakek  = UserModel::where("activation_code", $bapak->referral_code)->first();

						if (@$kakek) {
							$copy   = $alldata;
							$copy['commission'] = $tier3;
							$copy['fee']        = 0;
							$copy['id_user']    = $kakek->id_user;
							$copy['type_conversion']        = "commission";

							$this->campaign_tracker_model->insertData($copy);
						}
					}
					//commission beruntun

					$return['status'] = "success";
				} else {
					$return = array(
						"status"    => "error",
						"status"    => "Data duplikasi, tidak diperkenankan"
					);
				}
			} else {
				$return = array(
					"status"    => "error",
					"response"  => "Encrypted code tidak dikenali"
				);
			}
		} else {
			$return = array(
				"status"    => "error",
				"response"  => "Parameter mandatory tidak ditemukan, mohon periksa kembali. Pastikan parameter ada pada Header Request. Parameters: encrypted_code, aff_id"
			);
		}

		return response()->json($return, 200);
	}

	public function setTrackerTrue(Request $request)
	{
		//header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: *");

		$kode = $request->kode;
		$check = $this->campaign_tracker_model->getDetailTrackerFromUniquecode('set', $kode);

		if ($check) {
			$updatedata = array(
				"id_tracker"      => $check->id_tracker,
				"status"          => "active",
				"last_update"     => time()
			);

			$this->campaign_tracker_model->updateData($updatedata);

			$return = array(
				"status"    => "true"
			);
		} else {
			$return = array(
				"status"    => "error",
				"response"  => "sudah pernah digunakan"
			);
		}

		return response()->json($return, 200);
	}

	public function checkCode(Request $request)
	{
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: *");

		$kode       = $request->kode;
		$type       = $request->type;
		$protocol   = $request->protocol;
		$domain     = $request->domain;
		$url        = $request->url;

		$check = $this->campaign_tracker_model->getDetailTrackerFromUniquecode('initial', $kode);
		if ($check) {
            $duplicate = false;
			switch ($type) {
				case 'visit':
					$infoprogram = $this->campaign_program_model->getProgramByCampaign('visit', $check->id_campaign);
					$alldata     = $check->toArray();

					unset($alldata['id_tracker']);
					$alldata['time_created']           = time();
					$alldata['last_update']            = time();
					$alldata['status']                 = "active";
					$alldata['type_conversion']        = "visit";
					$alldata['commission']             = ($check->id_user != 0) ? @$infoprogram->commission : 0;
					$alldata['fee']                    = ($check->id_user != 0) ? @$infoprogram->fee : 0;
					$alldata['callback_url']           = @$url;
					$alldata['domain']                 = @$request->unique_random_code;
					$alldata['protocol']               = @$protocol;


					$checkdataavailable = $this->campaign_tracker_model->checkTrackerRecord('visit', $kode);
					if (!$checkdataavailable) {
						$this->campaign_tracker_model->insertData($alldata);
						$return = array(
							"status"    => "true"
						);
					} else {
                        $duplicate = true;
						$return = array(
							"status"    => "duplicated"
						);
					}
					break;

				case 'read':
					$infoprogram = $this->campaign_program_model->getProgramByCampaign('read', $check->id_campaign);
					$alldata     = $check->toArray();

					unset($alldata['id_tracker']);
					$alldata['time_created']           = time();
					$alldata['last_update']            = time();
					$alldata['status']                 = "active";
					$alldata['type_conversion']        = "read";
					$alldata['commission']             = ($check->id_user != 0) ? @$infoprogram->commission : 0;
					$alldata['fee']                    = ($check->id_user != 0) ? @$infoprogram->fee : 0;
					$alldata['callback_url']           = @$url;
					$alldata['domain']                 = @$request->unique_random_code;
					$alldata['protocol']               = @$protocol;

					$checkdataavailable = $this->campaign_tracker_model->checkTrackerRecord('read', $kode);
					if (!$checkdataavailable) {
						$this->campaign_tracker_model->insertData($alldata);
						$return = array(
							"status"    => "true"
						);
					} else {
                        $duplicate = true;
						$return = array(
							"status"    => "duplicated"
						);
					}
					break;

				case 'action':
					$infoprogram = $this->campaign_program_model->getProgramByCampaign('action', $check->id_campaign);
					$alldata     = $check->toArray();

					unset($alldata['id_tracker']);
					$alldata['time_created']           = time();
					$alldata['last_update']            = time();
					$alldata['status']                 = "active";
					$alldata['type_conversion']        = "action";
					$alldata['commission']             = ($check->id_user != 0) ? @$infoprogram->commission : 0;
					$alldata['fee']                    = ($check->id_user != 0) ? @$infoprogram->fee : 0;
					$alldata['callback_url']           = @$url;
					$alldata['domain']                 = @$request->unique_random_code;
					$alldata['protocol']               = @$protocol;
					$alldata['info']                   = @$request->id;

					$checkdataavailable = $this->campaign_tracker_model->checkTrackerRecord('action', $kode);
					if (!$checkdataavailable) {
						$this->campaign_tracker_model->insertData($alldata);
						$return = array(
							"status"    => "true"
						);
					} else {
                        $duplicate = true;
						$return = array(
							"status"    => "duplicated"
						);
					}
					break;

				case 'acquisition':
					$infoprogram = $this->campaign_program_model->getProgramByCampaign('acquisition', $check->id_campaign);
					$alldata     = $check->toArray();

					unset($alldata['id_tracker']);
					$alldata['time_created']           = time();
					$alldata['last_update']            = time();
					$alldata['status']                 = "active";
					$alldata['type_conversion']        = "acquisition";
					$alldata['commission']             = ($check->id_user != 0) ? @$infoprogram->commission : 0;
					$alldata['fee']                    = ($check->id_user != 0) ? @$infoprogram->fee : 0;
					$alldata['callback_url']           = @$url;
					$alldata['domain']                 = @$request->unique_random_code;
					$alldata['protocol']               = @$protocol;


					$checkdataavailable = $this->campaign_tracker_model->checkTrackerRecord('acquisition', $kode);
					if (!$checkdataavailable) {
						$this->campaign_tracker_model->insertData($alldata);
						$return = array(
							"status"    => "true"
						);
					} else {
                        $duplicate = true;
						$return = array(
							"status"    => "duplicated"
						);
					}
					break;
			}

            if (!$duplicate) {
	            $infocampaign = $this->campaign_model->getDetail($check->id_campaign);
	            $category_ids = CampaignProperty::whereIdCampaign($infocampaign->id_campaign)->wherePropertyType('category')->get()->pluck('value');
	            $categories = '|';
	            foreach ($category_ids as $category_id) {
		            $categories = $categories . SektorIndustriModel::whereIdSektorIndustri($category_id)->get()->value('nama_sektor_industri') . '|';
	            }
	            (new GoogleAnalyticsHelper())->sendEvent(
		            $type,
		            [
			            "campaign_title" => $infocampaign->campaign_title,
			            "category" => $categories,
			            "commission" => CampaignProgramModel::whereIdCampaign($infocampaign->id_campaign)->whereTypeProgram($type)->whereStatus('active')->get()->pluck('commission')[0],
			            "commission_type" => CampaignProgramModel::whereIdCampaign($infocampaign->id_campaign)->get()->value('type'),
			            "brand" => PenerbitModel::find($infocampaign->id_penerbit)->nama_penerbit,
			            "link_domain" => parse_url($infocampaign->landing_url)['host'],
			            "has_dondont" => !empty($infocampaign->campaign_do_n_dont),
			            "has_instructions" => !empty($infocampaign->campaign_instruction)]
	            );
            }
		} else {
			$return = array(
				"status"    => "error"
			);
		}

		return response()->json($return, 200);
	}

	public function getIPLocation($ip)
	{
		$data = file_get_contents("https://api.ipdata.co/$ip?api-key=241e9decd27fd78b924a1ea13dbb80d56ab097b06c14b9b000e5774f");
		$data = json_decode($data, true);

		$country = $data['country_code'];
		$region  = "id-" . strtolower($data['region_code']);
		$city    = $data['city'];

		return array(
			"country"           => $country,
			"region_code"       => $region,
			"city"              => $city,
			"connection_info"   => json_encode($data['asn'])
		);
	}

	public function getIPInfo($ip)
	{
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://ip-geolocation-and-threat-detection.p.rapidapi.com/" . $ip,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"x-rapidapi-host: ip-geolocation-and-threat-detection.p.rapidapi.com",
				"x-rapidapi-key: d49698c65fmsh4ac2f6116ed2076p1c6841jsn870de346722e"
			],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			$data = json_decode($response, true);

			$lokasi = @$data['location'];
			$country = $lokasi['country']['code'];
			$region  = strtolower($lokasi['region']['code']);
			$city    = $lokasi['city'];

			return array(
				"country"           => $country,
				"region_code"       => $region,
				"city"              => $city,
				"connection_info"   => json_encode(@$data['connection'])
			);
		}
	}

	public function translate($unique_id)
	{
		$check_code = $this->campaign_join_model->checkCode($unique_id);
		if ($check_code) {
			$infocampaign = $this->campaign_model->getDetail($check_code->id_campaign);

			//$browser = get_browser(null, true);
			//$ipinfo       = $this->getIPLocation($_SERVER['REMOTE_ADDR']);
			$ipinfo['city']             = "-";
			$ipinfo['region_code']      = "-";
			$ipinfo['country']          = "-";
			$ipinfo['connection_info']  = "-";

			$arrayinitial = array(
				"time_created"    => time(),
				"last_update"     => time(),
				"unique_link"     => $unique_id,
				"id_campaign"     => $infocampaign->id_campaign,
				"id_user"         => $check_code->id_user,
				"ip"              => $_SERVER['REMOTE_ADDR'],
				"browser"         => $_SERVER['HTTP_USER_AGENT'],
				"date"            => date("Y-m-d"),
				"status"          => "initial",
				"commission"      => 0,
				"id_program"      => 0,
				"referrer"        => @$_SERVER['HTTP_REFERER'],
				"os"              => $_SERVER['HTTP_USER_AGENT'],
				"device_info"     => $_SERVER['HTTP_USER_AGENT'],
				"fee"             => 0,
				"type_conversion" => "initial",
				"encrypted_code"  => $this->campaign_tracker_model->createEncryptedCode(@$_SERVER['REMOTE_ADDR'], $unique_id),
				"city"            => $ipinfo['city'],
				"region_code"     => $ipinfo['region_code'],
				"country"         => $ipinfo['country'],
				"connection_info" => $ipinfo['connection_info']
			);

			$check = $this->campaign_tracker_model->getDetailTrackerFromUniquecode('initial', $arrayinitial['encrypted_code']);
			if (!$check) {
				$this->campaign_tracker_model->insertData($arrayinitial);
			}


			if ($infocampaign->campaign_type == "banner") {
				if ($infocampaign->tipe_url == "slashed") {
					$check = strpos($infocampaign->landing_url, "?");
					if ($check) {
						$backlink = $infocampaign->landing_url . '&encrypted_code=' . $arrayinitial['encrypted_code'];
					} else {
						$backlink = $infocampaign->landing_url . '/?encrypted_code=' . $arrayinitial['encrypted_code'];
					}
				} elseif ($infocampaign->tipe_url == "formatted") {
					$backlink = str_replace("[encrypted_code]", $arrayinitial['encrypted_code'], $infocampaign->landing_url);
				} else {
					$backlink = $infocampaign->landing_url . '?encrypted_code=' . $arrayinitial['encrypted_code'];
				}
			} else {
				$backlink = url('voucher?encrypted_code=' . $arrayinitial['encrypted_code']);
			}

			//htmltag
?>
			<html>

			<head>
				<title><?= $infocampaign->campaign_title ?></title>
				<meta property="description" content="<?= $infocampaign->campaign_description ?>" />

				<meta property="og:image" content="<?= url($infocampaign->photos) ?>" />
				<meta property="og:title" content="<?= $infocampaign->campaign_title ?>" />
				<meta property="og:description" content="<?= $infocampaign->campaign_description ?>" />
			</head>

			<body style="background:#2f2f7c;color:#fff;text-align:center;font-family:'Roboto', sans-serif !important;">
				<img src="<?= url('templates/admin/img/logo.jpg') ?>" style="background:#fff;border-radius:10px;padding:15px;">
				<h1>Please wait..</h1>
				<p>You are about to see an amazing thing!</p>
			</body>
			<script>
				setTimeout(function() {
					document.location = "<?= $backlink ?>";
				}, 1000);
			</script>

			</html>
<?php
			//htmltag
			//sleep(10);
			exit();
		} else {
			$view     = View::make("frontend.403");
			$content  = $view->render();

			$data     = array(
				"content"                 => $content,
				"login"                   => @$login,
				"page"                    => "admin_dashboard",
				"submenu"                 => "admin_dashboard",
				"helper"                  => $this->helper,
				"previlege"               => $this->previlege_model,
				"title"                   => @$webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
				"description"             => @$webname . " | " . $this->setting_model->getSettingVal("website_description"),
				"keywords"                => @$webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
				"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
				"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
				"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
				"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
				"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
				"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
				"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
			);

			return view("frontend.body", $data);
		}
	}

	public function detailcampaign($project_code)
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['campaign'])) {
				$detail           = $this->campaign_model->getDetailByCampaignLink($project_code, $login);

				$statistic_visit        = $this->campaign_tracker_model->getTotalTracker('visit', $detail->id_campaign, $login);
				$statistic_read         = $this->campaign_tracker_model->getTotalTracker('read', $detail->id_campaign, $login);
				$statistic_action       = $this->campaign_tracker_model->getTotalTracker('action', $detail->id_campaign, $login);
				$statistic_acquisition  = $this->campaign_tracker_model->getTotalTracker('acquisition', $detail->id_campaign, $login);

				$infosaldo              = $this->rekening_dana_model->getCurrentRekening($login->id_user);

				$datacontent = array(
					"login"          => $login,
					"saldo"          => $infosaldo->saldo,
					"detail"         => $detail,
					"categories"     => $this->campaign_property_model->getPropertyBy('category', $detail->id_campaign),
					"domisili"       => $this->campaign_property_model->getPropertyBy('location', $detail->id_campaign),
					"program"        => array(
						"visit"         => $this->campaign_program_model->getProgramByCampaign('visit', $detail->id_campaign),
						"read"          => $this->campaign_program_model->getProgramByCampaign('read', $detail->id_campaign),
						"action"        => $this->campaign_program_model->getProgramByCampaign('action', $detail->id_campaign),
						"acquisition"   => $this->campaign_program_model->getProgramByCampaign('acquisition', $detail->id_campaign),
						"voucher"       => $this->campaign_program_model->getProgramByCampaign('voucher', $detail->id_campaign),
						"cashback"      => $this->campaign_program_model->getProgramByCampaign('cashback', $detail->id_campaign),
					),
					"logs"           => $this->campaign_tracker_model->getLast15Logs($detail->id_campaign, $login),
					"earning"         => array(
						"estimation"    => $this->campaign_tracker_model->sumTotalEstimation($detail->id_campaign, $login),
						"total"         => $this->campaign_tracker_model->sumTotalEarning($detail->id_campaign, $login),
					)
				);

				if ($statistic_visit > 0) {
					$datacontent['statistic']['visit'] = array(
						"total"       => $statistic_visit,
						"percent"     => ($statistic_read * 100) / $statistic_visit
					);

					$datacontent['chart']['visit'] = array(
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', (4 * 60) + 1, 5 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', (3 * 60) + 1, 4 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', (2 * 60) + 1, 3 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', (1 * 60) + 1, 2 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', (30) + 1, 1 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('visit', 0, 30, $detail->id_campaign, $login)
					);

					$datacontent['statistic']['read'] = array(
						"total"       => $statistic_read,
						"percent"     => ($statistic_read * 100) / $statistic_visit
					);

					$datacontent['chart']['read'] = array(
						$this->campaign_tracker_model->getTransactionTotalInRange('read', (4 * 60) + 1, 5 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('read', (3 * 60) + 1, 4 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('read', (2 * 60) + 1, 3 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('read', (1 * 60) + 1, 2 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('read', (30) + 1, 1 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('read', 0, 30, $detail->id_campaign, $login)
					);

					$datacontent['statistic']['action'] = array(
						"total"       => $statistic_action,
						"percent"     => ($statistic_action * 100) / $statistic_visit
					);

					$datacontent['chart']['action'] = array(
						$this->campaign_tracker_model->getTransactionTotalInRange('action', (4 * 60) + 1, 5 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('action', (3 * 60) + 1, 4 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('action', (2 * 60) + 1, 3 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('action', (1 * 60) + 1, 2 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('action', (30) + 1, 1 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('action', 0, 30, $detail->id_campaign, $login)
					);

					$datacontent['statistic']['acquisition'] = array(
						"total"       => $statistic_acquisition,
						"percent"     => ($statistic_acquisition * 100) / $statistic_visit
					);

					$datacontent['chart']['acquisition'] = array(
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', (4 * 60) + 1, 5 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', (3 * 60) + 1, 4 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', (2 * 60) + 1, 3 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', (1 * 60) + 1, 2 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', (30) + 1, 1 * 60, $detail->id_campaign, $login),
						$this->campaign_tracker_model->getTransactionTotalInRange('acquisition', 0, 30, $detail->id_campaign, $login)
					);
				}

				$view     = View::make("frontend.campaign_detail", $datacontent);
				$content  = $view->render();
				$infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);
				$data     = array(
					"saldo"                   => $infosaldo->saldo,
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function campaign_landing($project_code)
	{
		$webname  = $this->setting_model->getSettingVal("website_name");
		$detail   = $this->campaign_model->getDetailByCampaignLink($project_code);

		$datacontent = array(
			"detail"         => $detail,
			"categories"     => $this->campaign_property_model->getPropertyBy('category', $detail->id_campaign),
		);

		return view("landing.landing_public", $datacontent);
	}


	public function logs()
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['logs'])) {
				$infosaldo              = $this->rekening_dana_model->getCurrentRekening($login->id_user);

				$datacontent = array(
					"login"          => $login,
					"saldo"          => $infosaldo->saldo,
					"detail"         => $this->user_model->getDetail($login->id_user),
					"total_log"      => $this->campaign_tracker_model->getTotalTracker("", "", $login),
					"logs"           => $this->campaign_tracker_model->getLast15Logs("", $login),
				);

				$view     = View::make("frontend.logs", $datacontent);
				$content  = $view->render();
				$infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);
				$data     = array(
					"saldo"                   => $infosaldo->saldo,
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
					"joined_campaign"         => $this->campaign_model->getJoinedCampaignByUser($login->id_user, 5),
					"might_like"              => $this->campaign_model->getMightlikeCampaign($login->id_user, 3)
				);

				return view("frontend.body", $data);
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function mywallet()
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			$this->checkPermission($login, $this->config['wallet']);

			$infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);

			$datacontent = array(
				"login"        => $login,
				"withdrawals"  => $this->withdrawal_model->getWithdrawalRequest($login),
				"detail"       => $this->user_model->getDetail($login->id_user),
				"saldo"        => ($infosaldo->saldo) ? $infosaldo->saldo : 0
			);

			$view     = View::make("frontend.wallet", $datacontent);
			$content  = $view->render();

			$data     = array(
				"saldo"                   => $infosaldo->saldo,
				"content"                 => $content,
				"login"                   => $login,
				"page"                    => "admin_dashboard",
				"submenu"                 => "admin_dashboard",
				"helper"                  => $this->helper,
				"previlege"               => $this->previlege_model,
				"title"                   => "My Wallet",
				"description"             => "Your earning in zeals",
				"keywords"                => "Your earning as affiliator",
				"joined_campaign"         => $this->campaign_model->getJoinedCampaignByUser($login->id_user, 5),
				"might_like"              => $this->campaign_model->getMightlikeCampaign($login->id_user, 3)
			);

			return view("frontend.body", $data);
		} else {
			return redirect(url('signin'));
		}
	}

	public function dowithdraw(CreateWithdrawalRequest $request)
	{
		$login    = Session::get("aff");
		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			$this->checkPermission($login, $this->config['wallet']);

			$infouser = $this->user_model->getDetail($login->id_user);

			$service = new CreateWithdrawal($request);
			$create  = $service->store($infouser, $login);

			Session::flash('info', $create);

			if ($create['status'] == 'success') {
				return redirect(url('my-wallet'));
			} else {
				return redirect(url('my-wallet/withdraw'));
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function profileupdate(Request $request)
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['profile'])) {
				$id   = $login->id_user;
				$data = array(
					"id_user"               => $id,
					"nomor_rekening"        => $request->input("nomor_rekening"),
					"nama_bank"             => $request->input("nama_bank"),
					"nama_pemilik_rekening" => $request->input("nama_pemilik_rekening"),
					"last_update"           => time(),
					"first_name"            => $request->first_name,
					"last_name"             => $request->last_name,
					"phone"                 => $request->phone,
					"id_job"                => $request->id_job,
					"id_wilayah"            => $request->id_wilayah,
					"email"                 => $request->email,
				);

				if ($request->hasFile('avatar')) {
					$filePhotosInput  = $request->file('avatar');
					$photos_path      = "uploads/avatars/" . md5($filePhotosInput->getClientOriginalName()) . '-' . time() . '.' . $filePhotosInput->getClientOriginalExtension();
					$filePhotosInput->move(public_path('uploads/avatars/'), $photos_path);

					$data['avatar']   = $photos_path;
				}

				if ($request->n_password != "") {
					if ($request->n_password != $request->c_password) {
						Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Password not match!']);
						return redirect(url('profile'));
					} else {
						$data['password']  = md5($request->n_password);
					}
				}

				$rules  = array(
					"first_name"                   => "required",
					"username"                     => ["required", Rule::unique('tb_user')->where("status", "active")->ignore($id, 'id_user')],
					"email"                        => ["required", Rule::unique('tb_user')->where("status", "active")->ignore($id, 'id_user')],
					"nomor_rekening"               => "required|numeric",
					"nama_pemilik_rekening"        => "required",
					"nomor_rekening"               => "required",
					"nama_bank"                    => "required"
				);

				// dd($rules);
				// exit();

				$messages = array(
					"first_name.required"               => "Please fill the field",
					"username.required"                 => "Please fill the field",
					"username.unique"                   => "Please fill the field with different value, this username already used!",
					"email.required"                    => "Please fill the field",
					"email.email"                       => "Please fill the field only with email format",
					"email.unique"                      => "Please fill the field with different value, this email already used!",
					"nomor_rekening.required"           => "Please fill the field",
					"nomor_rekening.numeric"            => "Please fill the field with account number",
					"nama_pemilik_rekening.required"    => "Please fill the field",
					"nama_bank.required"                => "Please fill the field"
				);

				$this->validate($request, $rules, $messages);


				$update                 = $this->user_model->updateData($data);
				if ($update) {

					//reset master qr
					$sessionqr = Session::get("masterqr");
					if (@$sessionqr && @$sessionqr->email != $data['email']) {
						Session::forget("masterqr");
					}
					//reset master qr

					$login = $this->user_model->getDetail($id);
					Session::put("user", $login);
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Profile updated!']);
				} else {
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to update the profile']);
				}

				return redirect(url('profile'));
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function checkemail()
	{
		$dataemail                    = $this->user_model->getDetail(88);
		$dataemail['activation_link'] = url('activate-account/KJSGHFISULSKDOF');
		$dataemail['real_password']   = "KJHWLKSJFD";

		return view("emails.user_activation_invitation_byemail", $dataemail);
	}

	public function profile()
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['profile'])) {
				$infosaldo              = $this->rekening_dana_model->getCurrentRekening($login->id_user);

				$datacontent = array(
					"login"        => $login,
					"saldo"        => $infosaldo->saldo,
					"detail"       => $this->user_model->getDetail($login->id_user),
					"pekerjaans"   => $this->job_model->getListPekerjaan(),
					"wilayah"      => $this->wilayah_model->getListKota(),
					"bank"         => $this->bank_model->getBankList(),
					"preferences"  => $this->user_preferences_model->getUserLabelPreferences($login->id_user)
				);

				$view     = View::make("frontend.profile", $datacontent);
				$content  = $view->render();

				$infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);

				$data     = array(
					"saldo"                   => $infosaldo->saldo,
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
					"joined_campaign"         => $this->campaign_model->getJoinedCampaignByUser($login->id_user, 5),
					"might_like"              => $this->campaign_model->getMightlikeCampaign($login->id_user, 3)
				);

				return view("frontend.body", $data);
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function withdraw()
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['wallet'])) {

				$infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);

				$datacontent = array(
					"withdrawals"  => $this->withdrawal_model->getWithdrawalRequest($login),
					"detail"       => $this->user_model->getDetail($login->id_user),
					"saldo"        => $infosaldo->saldo,
					"banks"        => $this->bank_model->getBankList(),
					"fee"          => $this->setting_model->getSettingVal("pajak_pencairan_value")
				);

				$view     = View::make("frontend.withdraw", $datacontent);
				$content  = $view->render();

				$data     = array(
					"saldo"                   => $infosaldo->saldo,
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
					"joined_campaign"         => $this->campaign_model->getJoinedCampaignByUser($login->id_user, 5),
					"might_like"              => $this->campaign_model->getMightlikeCampaign($login->id_user, 3)
				);

				return view("frontend.body", $data);
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function preferencesstore(Request $request)
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['profile'])) {
				$rules  = array(
					"preferences"                  => "required",
				);

				$messages = array(
					"preferences.required"           => "Please fill the field",
				);

				$this->validate($request, $rules, $messages);

				$this->user_preferences_model->removePreferences($login);

				foreach ($request->preferences as $key => $value) {
					$data = array(
						"time_created"        => time(),
						"last_update"         => time(),
						"id_user"             => $login->id_user,
						"id_sektor_industri"  => $value,
						'status'              => "active"
					);

					$update  = $this->user_preferences_model->insertData($data);
				}


				Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Personal Categories updated!']);

				return redirect(url('profile'));
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function preferences()
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['profile'])) {

				$infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);
				$detail    = $this->user_model->getDetail($login->id_user);
				$detail['preferences']  = $this->user_preferences_model->getUserPreferences($login->id_user);

				$infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);

				$datacontent = array(
					"login"             => $login,
					"saldo"             => $infosaldo->saldo,
					"preferences"       => $this->sektor_industri_model->getPluckPerusahaan(),
					"detail"            => $detail,
				);

				$view     = View::make("frontend.preferences", $datacontent);
				$content  = $view->render();

				$data     = array(
					"saldo"                   => $infosaldo->saldo,
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function tutorial()
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['tutorial'])) {
				$infosaldo              = $this->rekening_dana_model->getCurrentRekening($login->id_user);

				$datacontent = array(
					"login"         => $login,
					"saldo"         => $infosaldo->saldo,
					"detail"        => $this->user_model->getDetail($login->id_user),
					"tutorials"     => $this->tutorial_model->getTutorials()
				);

				$view     = View::make("frontend.tutorial", $datacontent);
				$content  = $view->render();
				$infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);
				$data     = array(
					"saldo"                   => $infosaldo->saldo,
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
					"joined_campaign"         => $this->campaign_model->getJoinedCampaignByUser($login->id_user, 5),
					"might_like"              => $this->campaign_model->getMightlikeCampaign($login->id_user, 3)
				);

				return view("frontend.body", $data);
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function faq()
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['faq'])) {
				$infosaldo       = $this->rekening_dana_model->getCurrentRekening($login->id_user);

				$datacontent = array(
					"login"        => $login,
					"saldo"        => $infosaldo->saldo,
					"detail"       => $this->user_model->getDetail($login->id_user)
				);

				$view     = View::make("frontend.faq", $datacontent);
				$content  = $view->render();
				$infosaldo = $this->rekening_dana_model->getCurrentRekening($login->id_user);
				$data     = array(
					"saldo"                   => $infosaldo->saldo,
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
					"joined_campaign"         => $this->campaign_model->getJoinedCampaignByUser($login->id_user, 5),
					"might_like"              => $this->campaign_model->getMightlikeCampaign($login->id_user, 3)
				);

				return view("frontend.body", $data);
			} else {
				$view     = View::make("frontend.403");
				$content  = $view->render();

				$data     = array(
					"content"                 => $content,
					"login"                   => $login,
					"page"                    => "admin_dashboard",
					"submenu"                 => "admin_dashboard",
					"helper"                  => $this->helper,
					"previlege"               => $this->previlege_model,
					"title"                   => $webname . " | " . $this->setting_model->getSettingVal("website_tagline"),
					"description"             => $webname . " | " . $this->setting_model->getSettingVal("website_description"),
					"keywords"                => $webname . " | " . $this->setting_model->getSettingVal("website_keywords"),
					"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
					"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
					"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
					"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
					"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
					"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
					"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
				);

				return view("frontend.body", $data);
			}
		} else {
			return redirect(url('signin'));
		}
	}

	public function proses($id)
	{
		$login    = Session::get("aff");
		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['manage'])) {
				$detail      = $this->production_model->getDetail($id);
				$datacontent = array(
					"login"          => $login,
					"helper"         => "",
					"config"         => $this->config,
					"previlege"      => $this->previlege_model,
					"head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
					"items"          => $this->order_model->getItemsMRP($detail->order_id),
					"data"           => $detail,
					"mitra_model"    => $this->mitra_model
				);

				$view     = View::make("backend.master.produksi.proses", $datacontent);
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Tambah produk",
					"description"   => $webname . " | Tambah produk",
					"keywords"      => $webname . " | Tambah produk"
				);

				$body = "backend.body_backend_with_sidebar";
			} else {
				$view     = View::make("backend.403");
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Halaman tidak diperbolehkan",
					"description"   => $webname . " | Halaman tidak diperbolehkan",
					"keywords"      => $webname . " | Halaman tidak diperbolehkan"
				);
				$body = "backend/body";
			}

			$data     = array(
				"content"   => $content,
				"login"     => $login,
				"page"      => "admin_dashboard",
				"submenu"   => "admin_dashboard",
				"meta"      => $metadata,
				"helper"    => $this->helper,
				"previlege" => $this->previlege_model
			);
			return view($body, $data);
		} else {
			return redirect(url('login'));
		}
	}

	public function create()
	{
		$login    = Session::get("aff");
		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
				$parents = array("0" => "Tidak menggunakan parent");
				$categoryproducts = $this->category_model->getList();
				if ($categoryproducts) {
					foreach ($categoryproducts as $key => $value) {
						$categories[$key] = $value;
					}
				}

				$datacontent = array(
					"login"          => $login,
					"helper"         => "",
					"config"         => $this->config,
					"previlege"      => $this->previlege_model,
					"head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
					"categories"     => $categories
				);

				$view     = View::make("backend.master.produksi.create", $datacontent);
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Tambah produk",
					"description"   => $webname . " | Tambah produk",
					"keywords"      => $webname . " | Tambah produk"
				);

				$body = "backend.body_backend_with_sidebar";
			} else {
				$view     = View::make("backend.403");
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Halaman tidak diperbolehkan",
					"description"   => $webname . " | Halaman tidak diperbolehkan",
					"keywords"      => $webname . " | Halaman tidak diperbolehkan"
				);
				$body = "backend/body";
			}

			$data     = array(
				"content"   => $content,
				"login"     => $login,
				"page"      => "admin_dashboard",
				"submenu"   => "admin_dashboard",
				"meta"      => $metadata,
				"helper"    => $this->helper,
				"previlege" => $this->previlege_model
			);
			return view($body, $data);
		} else {
			return redirect(url('login'));
		}
	}

	public function update($id, Request $request)
	{
		$login    = Session::get("aff");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {
				$data = array(
					"id_post"           => $id,
					"caption"           => $request->input("caption"),
					"mention"           => $request->input("mention"),
					"hashtag"           => $request->input("hashtag"),
					"last_update"       => time(),
					"status"            => $request->input("status"),
				);

				$rules  = array(
					"caption"         => "required"
				);

				$messages = array(
					"caption.required"    => "Please fill caption here"
				);

				$this->validate($request, $rules, $messages);

				$create                 = $this->post_model->updateData($data);
				if ($create) {
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully updated']);
				} else {
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to update data']);
				}
				return redirect(url($this->config['main_url'] . '/edit/' . $id));
			} else {
				$webname  = $this->setting_model->getSettingVal("website_name");
				$view     = View::make("backend.403");
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Halaman tidak diperbolehkan",
					"description"   => $webname . " | Halaman tidak diperbolehkan",
					"keywords"      => $webname . " | Halaman tidak diperbolehkan"
				);
				$body = "backend/body";

				$data     = array(
					"content"   => $content,
					"login"     => $login,
					"page"      => "admin_dashboard",
					"submenu"   => "admin_dashboard",
					"meta"      => $metadata,
					"helper"    => $this->helper,
					"previlege" => $this->previlege_model
				);
				return view($body, $data);
			}
		} else {
			return redirect(url('login'));
		}
	}

	public function storemrp($id, Request $request)
	{
		$login    = Session::get("aff");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create']) && $this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['mrp'])) {
				$data = array(
					"id_product"        => $id,
					"id_material"       => $request->input("id_material"),
					"qty"               => $request->input("qty"),
					"time_created"      => time(),
					"last_update"       => time(),
					"status"            => "active"
				);

				$rules  = array(
					"id_material"         => "required",
					"qty"                 => "required|numeric"
				);

				$messages = array(
					"id_material.required"    => "Mohon mengisi bahan baku disini",
					"qty.required"            => "Mohon mengisi quantity disini",
					"qty.numeric"             => "Mohon mengisi angka/nominal pada quantity bahan, gunakan . untuk menggantikan , (koma) )",
				);

				$this->validate($request, $rules, $messages);

				$create                 = $this->mrp_model->insertData($data);
				if ($create) {
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah bahan pada produk!']);
				} else {
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah  bahan pada produk']);
				}
				return redirect(url($this->config['main_url'] . '/mrp/' . $id . '/create'));
			} else {
				$webname  = $this->setting_model->getSettingVal("website_name");
				$view     = View::make("backend.403");
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Halaman tidak diperbolehkan",
					"description"   => $webname . " | Halaman tidak diperbolehkan",
					"keywords"      => $webname . " | Halaman tidak diperbolehkan"
				);
				$body = "backend/body";

				$data     = array(
					"content"   => $content,
					"login"     => $login,
					"page"      => "admin_dashboard",
					"submenu"   => "admin_dashboard",
					"meta"      => $metadata,
					"helper"    => $this->helper,
					"previlege" => $this->previlege_model
				);
				return view($body, $data);
			}
		} else {
			return redirect(url('login'));
		}
	}

	public function store(Request $request)
	{
		$login    = Session::get("aff");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['create'])) {
				$data = array(
					"product_name"        => $request->input("product_name"),
					"price"               => $request->input("price"),
					"discount"            => $request->input("discount"),
					"product_code"        => $request->input("product_code"),
					"id_product_category" => 0,
					"product_type"        => "fix",
					"description"         => $request->input("description"),
					"time_created"        => time(),
					"last_update"         => time(),
					"author"              => $login->id_user,
					"id_vendor"           => "0"
				);

				$rules  = array(
					"product_name"        => "required",
					"price"               => "required|numeric",
					"product_code"        => "required|unique:product,product_code"
				);

				$messages = array(
					"product_name.required"   => "Mohon mengisi nama produk disini",
					"price.required"          => "Mohon mengisi harga produk disini",
					"price.numeric"           => "Mohon mengisi angka/nominal pada harga produk",
					"product_code.required"   => "Mohon mengisi Kode produk disini",
					"product_code.unique"     => "Kode produk sudah digunakan oleh produk lain"
				);

				$this->validate($request, $rules, $messages);

				$create                 = $this->product_model->insertData($data);
				if ($create) {
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah produk!']);
				} else {
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah produk']);
				}
				return redirect(url($this->config['main_url']));
			} else {
				$webname  = $this->setting_model->getSettingVal("website_name");
				$view     = View::make("backend.403");
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Halaman tidak diperbolehkan",
					"description"   => $webname . " | Halaman tidak diperbolehkan",
					"keywords"      => $webname . " | Halaman tidak diperbolehkan"
				);
				$body = "backend/body";

				$data     = array(
					"content"   => $content,
					"login"     => $login,
					"page"      => "admin_dashboard",
					"submenu"   => "admin_dashboard",
					"meta"      => $metadata,
					"helper"    => $this->helper,
					"previlege" => $this->previlege_model
				);
				return view($body, $data);
			}
		} else {
			return redirect(url('login'));
		}
	}

	public function manage($id)
	{
		$login    = Session::get("aff");
		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['manage'])) {
				$categoryproducts = $this->category_model->getList();
				if ($categoryproducts) {
					foreach ($categoryproducts as $key => $value) {
						$categories[$key] = $value;
					}
				}

				$datacontent = array(
					"login"          => $login,
					"helper"         => "",
					"config"         => $this->config,
					"previlege"      => $this->previlege_model,
					"head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
					"data"           => $this->product_model->getDetail($id),
					"photos"         => $this->product_model->getPhotos($id),
					"categories"     => $categories
				);

				$data = $this->product_model->getPhotos($id);
				//dd($data);

				$view     = View::make("backend.master.produksi.manage", $datacontent);
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Ubah produk",
					"description"   => $webname . " | Ubah produk",
					"keywords"      => $webname . " | Ubah produk"
				);

				$body = "backend.body_backend_with_sidebar";
			} else {
				$view     = View::make("backend.403");
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Halaman tidak diperbolehkan",
					"description"   => $webname . " | Halaman tidak diperbolehkan",
					"keywords"      => $webname . " | Halaman tidak diperbolehkan"
				);
				$body = "backend/body";
			}

			$data     = array(
				"content"   => $content,
				"login"     => $login,
				"page"      => "admin_dashboard",
				"submenu"   => "admin_dashboard",
				"meta"      => $metadata,
				"helper"    => $this->helper,
				"previlege" => $this->previlege_model
			);
			return view($body, $data);
		} else {
			return redirect(url('login'));
		}
	}

	public function edit($id)
	{
		$login    = Session::get("aff");
		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['edit'])) {

				$datacontent = array(
					"login"          => $login,
					"helper"         => "",
					"config"         => $this->config,
					"previlege"      => $this->previlege_model,
					"head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
					"data"           => $this->post_model->getDetail($id),
				);

				$view     = View::make("backend.master.post.edit", $datacontent);
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Ubah produk",
					"description"   => $webname . " | Ubah produk",
					"keywords"      => $webname . " | Ubah produk"
				);

				$body = "backend.body_backend_with_sidebar";
			} else {
				$view     = View::make("backend.403");
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Halaman tidak diperbolehkan",
					"description"   => $webname . " | Halaman tidak diperbolehkan",
					"keywords"      => $webname . " | Halaman tidak diperbolehkan"
				);
				$body = "backend/body";
			}

			$data     = array(
				"content"   => $content,
				"login"     => $login,
				"page"      => "admin_dashboard",
				"submenu"   => "admin_dashboard",
				"meta"      => $metadata,
				"helper"    => $this->helper,
				"previlege" => $this->previlege_model
			);
			return view($body, $data);
		} else {
			return redirect(url('login'));
		}
	}

	public function updateproses($id, Request $request)
	{
		$login    = Session::get("aff");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['manage'])) {
				$detail = $this->production_model->getDetail($id);
				if ($detail->status == "production") {
					$items  = $this->order_model->getItemsMRP($detail->order_id);
					$data = array(
						"id_transaction"      => $id,
						"status"              => "production",
						"last_update"         => time()
					);
					$production = $this->production_model->updateData($data);

					if ($production) {
						$completions = 0;
						foreach ($items as $key => $value) {
							$item = $this->production_completion_model->getDetail($value->id_production);
							$update = array();

							$update = array(
								"id_production"           => $item->id_production,
								"status"                  => ($detail->production_quantity == $request->input("produce_" . $value)) ? "ready" : "production",
								"production_completion"   => $request->input("produce_" . $item->id_production),
							);

							if ($item->production_completion != $request->input("produce_" . $item->id_production)) {
								$update['last_update']  = time();
							}

							if ($update['status'] == "ready") {
								$completions++;
							}

							$this->production_completion_model->updateData($update);
						}
					}

					if ($completions >= sizeof($items)) {
						$updateproduksi = array(
							"id_transaction"  => $id,
							"last_update"     => time(),
							"status"          => "ready"
						);

						$this->production_model->updateData($updateproduksi);

						Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengupdate! Produksi pesanan telah selesai!']);
						return redirect(url($this->config['main_url'] . '/proses/' . $id));
					} else {
						Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengupdate status produksi']);
						return redirect(url($this->config['main_url'] . '/proses/' . $id));
					}
				} elseif ($detail->status == "ready") {
					$data = array(
						"id_transaction"      => $id,
						"status"              => "packed",
						"last_update"         => time()
					);
					$production = $this->production_model->updateData($data);

					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengupdate! Pesanan dalam status terpacking!']);
					return redirect(url($this->config['main_url'] . '/proses/' . $id));
				} elseif ($detail->status == "packed") {
					$data = array(
						"id_transaction"      => $id,
						"status"              => "shipted",
						"last_update"         => time()
					);
					$production = $this->production_model->updateData($data);

					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengupdate! Pesanan dalam status pengiriman!']);
					return redirect(url($this->config['main_url'] . '/proses/' . $id));
				}
				//return redirect(url('admin/product-uronshop/edit/'.$id));
			} else {
				$webname  = $this->setting_model->getSettingVal("website_name");
				$view     = View::make("backend.403");
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Halaman tidak diperbolehkan",
					"description"   => $webname . " | Halaman tidak diperbolehkan",
					"keywords"      => $webname . " | Halaman tidak diperbolehkan"
				);
				$body = "backend/body";

				$data     = array(
					"content"   => $content,
					"login"     => $login,
					"page"      => "admin_dashboard",
					"submenu"   => "admin_dashboard",
					"meta"      => $metadata,
					"helper"    => $this->helper,
					"previlege" => $this->previlege_model
				);
				return view($body, $data);
			}
		} else {
			return redirect(url('login'));
		}
	}


	public function remove(Request $request)
	{
		$login    = Session::get("aff");
		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['remove'])) {
				$id = $request->input("id");
				$detail = $this->post_model->getDetail($id);
				$data = array(
					"id_post"             => $id,
					"status"              => "deleted",
					"last_update"         => time()
				);
				$remove = $this->post_model->updateData($data);

				if ($remove) {
					$undolink = url($this->config['main_url'] . '/restore/' . $id);
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data  <strong>' . $detail->permalink . '</strong> successfully removed! click <a href="' . $undolink . '" class="text-danger"><strong>here</strong></a> ro recover the removed data.']);
				} else {
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to remove data']);
				}
				return redirect(url($this->config['main_url']));
			} else {
				$webname  = $this->setting_model->getSettingVal("website_name");
				$view     = View::make("backend.403");
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Halaman tidak diperbolehkan",
					"description"   => $webname . " | Halaman tidak diperbolehkan",
					"keywords"      => $webname . " | Halaman tidak diperbolehkan"
				);
				$body = "backend/body";

				$data     = array(
					"content"   => $content,
					"login"     => $login,
					"page"      => "admin_dashboard",
					"submenu"   => "admin_dashboard",
					"meta"      => $metadata,
					"helper"    => $this->helper,
					"previlege" => $this->previlege_model
				);
				return view($body, $data);
			}
		} else {
			return redirect(url('login'));
		}
	}

	public function restore($id)
	{
		$login    = Session::get("aff");
		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['restore'])) {
				$detail = $this->post_model->getDetail($id);
				$data = array(
					"id_post"               => $id,
					"status"                => "active",
					"last_update"           => time()
				);
				$remove = $this->post_model->updateData($data);

				if ($remove) {
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data <strong>' . $detail->permalink . '</strong> successfully restored']);
				} else {
					Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to restore data']);
				}
				return redirect(url($this->config['main_url']));
			} else {
				$webname  = $this->setting_model->getSettingVal("website_name");
				$view     = View::make("backend.403");
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Halaman tidak diperbolehkan",
					"description"   => $webname . " | Halaman tidak diperbolehkan",
					"keywords"      => $webname . " | Halaman tidak diperbolehkan"
				);
				$body = "backend/body";

				$data     = array(
					"content"   => $content,
					"login"     => $login,
					"page"      => "admin_dashboard",
					"submenu"   => "admin_dashboard",
					"meta"      => $metadata,
					"helper"    => $this->helper,
					"previlege" => $this->previlege_model
				);
				return view($body, $data);
			}
		} else {
			return redirect(url('login'));
		}
	}

	public function detail($id)
	{
		$login    = Session::get("aff");
		$webname  = $this->setting_model->getSettingVal("website_name");
		if ($login) {
			if ($this->previlege_model->isAllow($login->id_user, $login->id_department, $this->config['view'])) {

				$datacontent = array(
					"login"          => $login,
					"helper"         => "",
					"config"         => $this->config,
					"previlege"      => $this->previlege_model,
					"head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
					"data"           => $this->post_model->getDetail($id),
					"wholikes"       => $this->like_model->getPeopleWholikes($id),
					"whocomment"     => $this->comment_model->getPeopleWhoComments($id),
				);

				$view     = View::make("backend.master.post.detail", $datacontent);
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Detail produk",
					"description"   => $webname . " | Detail produk",
					"keywords"      => $webname . " | Detail produk"
				);

				$body = "backend.body_backend_with_sidebar";
			} else {
				$view     = View::make("backend.403");
				$content  = $view->render();

				$metadata = array(
					"title"         => $webname . " | Halaman tidak diperbolehkan",
					"description"   => $webname . " | Halaman tidak diperbolehkan",
					"keywords"      => $webname . " | Halaman tidak diperbolehkan"
				);
				$body = "backend/body";
			}

			$data     = array(
				"content"   => $content,
				"login"     => $login,
				"page"      => "admin_dashboard",
				"submenu"   => "admin_dashboard",
				"meta"      => $metadata,
				"helper"    => $this->helper,
				"previlege" => $this->previlege_model
			);
			return view($body, $data);
		} else {
			return redirect(url('login'));
		}
	}

	public function signin()
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");

		$this->page_model->setCode("signin-page");

		$items    = Session::get("cart_items");
		if ($items) {
			$backlink = url("cart");
		} else {
			$backlink = "";
		}

		if ($login) {
			return redirect(url("campaign"));
		}

		$datacontent = array(
			"content"		=> $this->page_model->getContent(),
			"title"			=> $this->page_model->getTitle(),
			"backlink"      => $backlink
		);

		$view     = View::make("frontend.signin", $datacontent);
		$content  = $view->render();

		$data     = array(
			"content"                 => $content,
			"login"                   => $login,
			"page"                    => "admin_dashboard",
			"submenu"                 => "admin_dashboard",
			"helper"                  => $this->helper,
			"previlege"               => $this->previlege_model,
			"title"                   => $webname . " | " . $this->page_model->getKeyword(),
			"description"             => $webname . " | " . $this->page_model->getKeyword(),
			"keywords"                => $webname . " | " . $this->page_model->getKeyword(),
			"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
			"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
			"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
			"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
			"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
			"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
			"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
		);

		return view("frontend.body", $data);
	}

	public function cancelviagoogle()
	{
		Session::forget("google_register");
		return redirect(url('register'));
	}

	public function forgetpassword()
	{
		$login    = Session::get("aff");

		$webname  = $this->setting_model->getSettingVal("website_name");

		$datacontent = array(
			"content"		=> $this->page_model->getContent(),
			"title"			=> $this->page_model->getTitle(),
		);

		$view     = View::make("frontend.reset_password", $datacontent);
		$content  = $view->render();

		$data     = array(
			"content"                 => $content,
			"login"                   => $login,
			"page"                    => "admin_dashboard",
			"submenu"                 => "admin_dashboard",
			"helper"                  => $this->helper,
			"previlege"               => $this->previlege_model,
			"title"                   => $webname . " | " . $this->page_model->getKeyword(),
			"description"             => $webname . " | " . $this->page_model->getKeyword(),
			"keywords"                => $webname . " | " . $this->page_model->getKeyword(),
			"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
			"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
			"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
			"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
			"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
			"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
			"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
		);


		return view("frontend/body", $data);
	}

	public function register(Request $request)
	{
		$this->page_model->setCode("registration-page");

		$this->department_model = new DepartmentModel();

		$customfields   = $this->department_model->getByCode($request->segment(2));

		$datacontent = array(
			"custom_fields"           => $customfields,
			"google_api_key"          => $this->setting_model->getSettingVal("google_api_key"),
			"base_city_coor"          => $this->setting_model->getSettingVal("base_city_lang_lat"),
			"content"		              => $this->page_model->getContent(),
			"title"			              => $this->page_model->getTitle(),
			"is_google_registration"  => Session::get("google_register"),
			"google_email"            => Session::get("google_email")
		);

      
      if($request->segment(2)!=""){
        $view     = View::make("frontend.custom.".strtolower($request->segment(2)).".register",$datacontent);
      }else{
        $view     = View::make("frontend.register",$datacontent);
      }

		$content  = $view->render();

		$webname  = $this->setting_model->getSettingVal("website_name");
		$metadata = array(
			"title"         => $webname . " | Registrasi akun kamu disini",
			"description"   => $webname . " | Registrasi akun uronshop kamu disini untuk melakukan transaksi pada jaringan uronshop",
			"keywords"      => $webname . " | Regsitrasi akun, Registrasi Uronshop"
		);

		if ($customfields != "") {
			$cf = json_decode($customfields->custom_user_fields, true);
			$webname = @$cf['title'];

			$meta_title       = $webname;
			$meta_description = @$cf['subtitle'];
			$meta_keywords    = @$cf['title'];
		} else {
			$meta_title       = $webname . " | " . $this->page_model->getKeyword();
			$meta_description = $webname . " | " . $this->page_model->getKeyword();
			$meta_keywords    = $webname . " | " . $this->page_model->getKeyword();
		}

		$login    = Session::get("aff");
		$data     = array(
			"content"                 => $content,
			"login"                   => $login,
			"page"                    => "admin_dashboard",
			"submenu"                 => "admin_dashboard",
			"helper"                  => $this->helper,
			"previlege"               => $this->previlege_model,
			"title"                   => $meta_title,
			"description"             => $meta_description,
			"keywords"                => $meta_keywords,
			"official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
			"official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
			"official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
			"official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
			"official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
			"head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
			"opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
		);

		return view("frontend/body_white", $data);
	}
}
