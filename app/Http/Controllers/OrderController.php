<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Helpers\IGWHelper;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

use App\Http\Models\SettingModel;
use App\Http\Models\PrevilegeModel;

use App\Http\Models\ProductModel;

class OrderController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $product_model;
  private $slide_model;
  private $stock_model;
  private $order_model;
  private $cart_detail_model;
  private $shipping_address_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->product_model    = new ProductModel();
  }

  public function index(){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
        if($this->previlege_model->isAllow($login->id_user,$login->id_department,"member-checkout")){
          $webname  = $this->setting_model->getSettingVal("website_name");


          $datacontent = array(
            "stock_model"   => $this->stock_model,
            "login"         => $login,
            "product_model" => $this->product_model,
            "shipping_address_model" => $this->shipping_address_model,
            "items"         => $this->product_model->getItemAvailable(url(""))
          );

          $view     = View::make("frontend.order",$datacontent);

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
        return redirect(url('signin/?backlink='));
      }
  }

  public function dopesanan(Request $request){

    $cart_items = Session::get("cart_items");
    //if($cart_items==""){
      $cart_items = array();
    //}

    foreach ($request->all() as $key => $value) {
      if(substr($key,0,3)=="qty" && $value>0){
        print $value;
        print "<br>";
        $id_product = substr($key,4);
        $size       = "allsize";
        $quantity   = $value;
        $permalink  = "all";

        $ketemu     = false;
        if($cart_items){
          foreach ($cart_items as $key => $value) {
            if($value['id_product']==$id_product && $value['size']==$size){
              $ketemu = true;
              $cart_items[$key]['quantity'] = $cart_items[$key]['quantity'] + $quantity;
              break;
            }
          }
        }

        if(!$ketemu){
          $cart_items[sizeof($cart_items)] = array(
            "id_product"    => $id_product,
            "size"          => $size,
            "quantity"      => $quantity
          );
        }

      }
    }

    //print_r($cart_items);


    Session::put("cart_items",$cart_items);

    Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Produk berhasil ditambahkan ke keranjang']);
    return redirect(url('cart'));

  }

}
