<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Helpers\IGWHelper;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

use App\Http\Models\SettingModel;
use App\Http\Models\PrevilegeModel;

use App\Http\Models\ProductModel;
use App\Http\Models\BankModel;
use App\Http\Models\OrderModel;
use App\Http\Models\ShippingAddressModel;
use App\Http\Models\PageModel;

class ConfirmationController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $product_model;
  private $bank_model;
  private $order_model;
  private $shipping_address_model;
  private $page_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->product_model    = new ProductModel();
    $this->bank_model       = new BankModel();
    $this->order_model       = new OrderModel();
    $this->shipping_address_model       = new ShippingAddressModel();
    $this->page_model       = new PageModel();
  }

  public function index($cart_code=""){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");

      $webname  = $this->setting_model->getSettingVal("website_name");
	  $this->page_model->setCode("confirmation-page");
	  
      $datacontent = array(
		"content"				 => $this->page_model->getContent(),
	    "title"					 => $this->page_model->getTitle(),
        "cart_code"              => $cart_code,
        "rekeningtujuan"         => array($this->setting_model->getSettingVal("bank_account") => $this->setting_model->getSettingVal("bank_account")),
        "listofbank"             => $this->bank_model->getBankList() 
      );

      $view     = View::make("frontend.confirmation",$datacontent);
      $content  = $view->render();

      $data     = array(
          "content"                 => $content,
          "login"                   => $login,
          "page"                    => "admin_dashboard",
          "submenu"                 => "admin_dashboard",
          "helper"                  => $this->helper,
          "previlege"               => $this->previlege_model,
          "title"                   => $webname." | ".$this->page_model->getKeyword(),
          "description"             => $webname." | ".$this->page_model->getKeyword(),
          "keywords"                => $webname." | ".$this->page_model->getKeyword(),
          "official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
          "official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
          "official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
          "official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
          "official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
          "head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
          "opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
      );

      return view("frontend.body",$data);
  }

  public function email(){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");

      $webname  = $this->setting_model->getSettingVal("website_name");
	  
	  $cart_code 	= "ORD0000000108";
	  $detail 		= $this->order_model->getDetailByCartCode($cart_code);	
      $datacontent = array(
        "cart_code"     => $cart_code,
		"data"			=> $detail
      );

      $view     = View::make("frontend.email_confirmation",$datacontent);
      $content  = $view->render();

      return $view;
  }

  public function doconfirm(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    $cart_code  = $request->input("cart_code");
    if($login){
        if($this->previlege_model->isAllow($login->id_user,$login->id_department,"member-confirmation")){
          $check    = $this->order_model->checkCartCodeAvailable($cart_code);
          if($check){
			$created 	  		= $check->time_created;
			$total_maks_bayar 	= 60*60*$this->setting_model->getSettingVal("maks_jam_bayar");
			$expired 			= $created + $total_maks_bayar;
			
			if(time()>$expired){
				//expired
				Session::flash('info', ['status' => 'danger', 'alert-class' => 'alert-danger', 'message' => 'Kode Pembelian expired! Pesanan tidak dapat di lanjutkan.']);
				return redirect(url('confirmation'));
			}else{
			
					$webname  = $this->setting_model->getSettingVal("website_name");
					$detail   = $this->order_model->getDetailByCartCode($cart_code);
					$shipping = $this->shipping_address_model->getShippingAddress($login->id_user);

					$data_confirmation = array(
						"time"                      => time(),
						"nominal_transfer"          => $request->input("nominal_transfer"),
						"tujuan"                    => $request->input("tujuan"),
						"no_rekening_pengirim"      => $request->input("no_rekening_pengirim"),
						"nama_rekening_pengirim"    => $request->input("nama_rekening_pengirim"),
						"bank_pengirim"             => $request->input("bank_pengirim"),
						"cart_code"                 => $cart_code
					);

					$dataupdate = array(
					  "data_confirmation"   => json_encode($data_confirmation),
					  "last_update"         => time(),
					  "cart_code"           => $cart_code
					);

					$update = $this->order_model->updateDataByCartCode($dataupdate);
					if($update){
						
						$detail 		= $this->order_model->getDetailByCartCode($cart_code);	
						$dataemail 	= array(
							"first_name"	=> $login->first_name,
							"email"			=> $login->email,
							"cart_code"     => $cart_code,
							"data"			=> $detail
						);
						
						Mail::send('frontend/email_confirmation', $dataemail, function ($mail) use ($dataemail) {
							$this->settingmodel = new SettingModel();

							$sender             = $this->setting_model->getSettingVal("email_sender_name");
							$senderaddress      = $this->setting_model->getSettingVal("email_sender_address");

							$mail->from($senderaddress, $sender);
							$mail->to($senderaddress, $sender);
							$mail->subject('KONFIRMASI PEMBAYARAN #'.$dataemail['cart_code']);
						});
					
						
					  Session::put("confirm_cart",$cart_code);
					  Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Konfirmasi pembayaran berhasil dilakukan. Mohon menunggu.']);
					  return redirect(url('thankyou-confirm'));
				}else{
				  Session::flash('info', ['status' => 'danger', 'alert-class' => 'alert-danger', 'message' => 'Gagal melakukan konfirmasi pesanan']);
				  return redirect(url('confirmation/'.$cart_code));
				}
			}
          }else{
            Session::flash('info', ['status' => 'danger', 'alert-class' => 'alert-danger', 'message' => 'Kode Pembelian tidak ditemukan! Mohon cek kembali terlebih dahulu.']);
            return redirect(url('confirmation'));
          }
        }else{
          $view     = View::make("frontend.403");
          $content  = $view->render();

          $data     = array(
              "content"                 => $content,
              "login"                   => $login,
              "page"                    => "admin_dashboard",
              "submenu"                 => "admin_dashboard",
              "helper"                  => $this->helper,
              "previlege"               => $this->previlege_model,
              "title"                   => $webname." | ".$this->setting_model->getSettingVal("website_tagline"),
              "description"             => $webname." | ".$this->setting_model->getSettingVal("website_description"),
              "keywords"                => $webname." | ".$this->setting_model->getSettingVal("website_keywords"),
              "official_phone_number"   => $this->setting_model->getSettingVal("official_phone_number"),
              "official_email_address"  => $this->setting_model->getSettingVal("official_email_contact"),
              "official_facebook"       => $this->setting_model->getSettingVal("facebook_url"),
              "official_twitter"        => $this->setting_model->getSettingVal("twitter_url"),
              "official_instagram"      => $this->setting_model->getSettingVal("instagram_url"),
              "head_quarters"           => $this->setting_model->getSettingVal("head_quarter"),
              "opening_hour"            => $this->setting_model->getSettingVal("opening_hour"),
          );

          return view("frontend.body",$data);
        }
      }else{
        if($cart_code){
          return redirect(url('signin/?backlink=confirmation/'.$cart_code));
        }else{
          return redirect(url('signin'));
        }
      }
  }

  public function notfound(){
    $view     = View::make("backend.404");
    $content  = $view->render();

    $webname  = $this->setting_model->getSettingVal("website_name");
    $metadata = array(
      "title"         => $webname." | Not Found!",
      "description"   => $webname." | Not Found!",
      "keywords"      => $webname." | Not Found!"
    );

    $data     = array(
        "content"   => $content,
        "login"     => "",//$login,
        "page"      => "notification",
        "submenu"   => "notification",
        "meta"      => $metadata,
        "helper"    => "",//$this->frontier_helper,
        "previlege" => ""//$this->previlege
    );

    return view("backend/body",$data);
  }

}
