<?php

namespace App\Http\Controllers;

//===============================
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\IGWHelper;

use App\Http\Models\PrevilegeModel;
use App\Http\Models\SettingModel;
//===============================


use App\Http\Models\OrderModel;
use App\Http\Models\TransactionModel;
use App\Http\Models\MaterialModel;
use App\Http\Models\MrpModel;
use App\Http\Models\CategoryModel;
use App\Http\Models\ProductModel;
use App\Http\Models\UserModel;
use App\Http\Models\PhotoModel;
use App\Http\Models\ProductDetailModel;
use App\Http\Models\CartDetailModel;
use App\Http\Models\CategoryJurnalModel;
use App\Http\Models\JurnalModel;
use App\Http\Models\NaikanRumusModel;
use App\Http\Models\NaikanRumusItemsModel;
use App\Http\Models\NaikanModel;
use App\Http\Models\NaikanProductPlanningModel;
use App\Http\Models\RumusProductModel;
use App\Http\Models\StockUnitModel;
use App\Http\Models\CartPemenuhanModel;
use App\Http\Models\MaterialMovementModel;
use App\Http\Models\BahanPenyertaModel;
use App\Http\Models\MaterialDistributionModel;
use App\Http\Models\PenerbitModel;

use Intervention\Image\ImageManagerStatic as Image;

class TransactionController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;
  private $config;

  private $user_model;
  private $product_model;
  private $category_model;
  private $photo_model;
  private $productdetail_model;
  private $material_model;
  private $mrp_model;
  private $transaction_model;
  private $order_model;
  private $cartdetail_model;
  private $category_jurnal_model;
  private $jurnal_model;
  private $naikanrumus_model;
  private $naikanrumus_items_model;
  private $naikan_model;
  private $naikan_product_planning;
  private $rumus_product_model;
  private $stock_unit_model;
  private $cart_detail_model;
  private $cart_pemenuhan_model;
  private $material_movement_model;
  private $bahan_penyerta_model;
  private $material_distribution_model;
  private $product_reject_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $config['main_url'] = "master/transaksi";
    $config['create']   = "transaction-create";
    $config['edit']     = "transaction-edit";
    $config['view']     = "transaction-view";
    $config['manage']   = "transaction-manage";
    $config['remove']   = "transaction-remove";
    $config['restore']  = "transaction-restore";
    $config['pemenuhan']  = "transaction-pemenuhan";
    $config['kirim']    = "transaction-send";
    $config['produksi']    = "transaction-production";
    $config['alokasi-resep']    = "transaction-allocation-resep";

    $this->config       = $config;

    $this->user_model       = new UserModel();
    $this->product_model    = new ProductModel();
    $this->category_model   = new CategoryModel();
    $this->photo_model      = new PhotoModel();
    $this->productdetail_model = new ProductDetailModel();
    $this->material_model   = new MaterialModel();
    $this->mrp_model        = new MrpModel();
    $this->transaction_model = new TransactionModel();
    $this->order_model      = new OrderModel();
    $this->cartdetail_model = new CartDetailModel();
    $this->category_jurnal_model      = new CategoryJurnalModel();
    $this->jurnal_model      = new JurnalModel();
    $this->naikanrumus_model      = new NaikanRumusModel();
    $this->naikanrumus_items_model      = new NaikanRumusItemsModel();
    $this->naikan_model      = new NaikanModel();
    $this->naikan_product_planning      = new NaikanProductPlanningModel();
    $this->rumus_product_model      = new RumusProductModel();
    $this->stock_unit_model      = new StockUnitModel();
    $this->cart_detail_model      = new CartDetailModel();
    $this->cart_pemenuhan_model      = new CartPemenuhanModel();
    $this->material_movement_model      = new MaterialMovementModel();
    $this->bahan_penyerta_model      = new BahanPenyertaModel();
    $this->material_distribution_model      = new MaterialDistributionModel();
    $this->penerbit_model      = new PenerbitModel();
  }

  public function getTransactionCost(Request $request){
    $id = $request->input("id_cart");
    $cost = $this->transaction_model->getTransactionCost($id);
    $return = array(
      "cost"  => "Rp.".number_format($cost,2,",",".")
    );
    return response()->json($return, 200);
  }

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $default    = array(
          "short"     => "time_created",
          "shortmode" => "desc"
        );
        $shorter = array(
          "transaction_code"      => "Kode Transaksi",
          "total_amount"          => "Total Transaksi",
          "cart_code"             => "Kode Order",
          "time_created"          => "Tgl Daftar",
          "last_update"           => "Tgl Diperbarui"
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;

        $checkis_penerbit = $this->penerbit_model->checkUserIsPenerbit($login->id_user);
        if($checkis_penerbit){
          $id_penerbit = $checkis_penerbit->id_penerbit;
        }

        $data       = $this->order_model->getData($str, $limit, $keyword, $short, $shortmode, @$id_penerbit);
        // $data = $this->order_model::get();
        // dd($data);

        $totaldata  = $this->order_model->countData($keyword);
        $pagging    = $this->helper->showPagging($totaldata, url($this->config['main_url'].'/?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode), $position = "", $page, $limit , 2);

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "data"           => $data,
          "pagging"        => $pagging,
          "input"          => $request->all(),
          "default"        => $default,
          "shorter"        => $shorter,
          "page"           => $page,
          "limit"          => $limit,
          "config"         => $this->config,
          "total_pembelian"  => $this->order_model->getTotalTrxPaid(),
          "total_pembayaran" => $this->order_model->getTotalBayarPaid(),
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.transaction.index",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Daftar Kategori",
          "description"   => $webname." | Daftar Kategori",
          "keywords"      => $webname." | Daftar Kategori"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }


  public function editmrp($parent, $id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit']) && $this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['mrp'])) {
        $bahanbaku   = $this->material_model->getlist();

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->product_model->getDetail($parent),
          "bahan"          => $bahanbaku,
          "detail"         => $this->mrp_model->getDetail($id)
        );

        $view     = View::make("backend.master.product.mrp.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah produk",
          "description"   => $webname." | Tambah produk",
          "keywords"      => $webname." | Tambah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function checkout(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['remove'])){
        $id = $request->input("id");
        $detail = $this->product_model->getDetail($id);
        $data = array(
          "id_cart"             => $id,
          "status"              => "ordered",
          "last_update"         => time()
        );
        $order = $this->order_model->updateData($data);

        if($order){
          $totalamount = $this->order_model->getTotalTransaction($id);

          $datatransaction = array(
            "total_amount"  => $totalamount,
            "time_created"  => time(),
            "last_update"   => time(),
            "id_author"     => $login->id_user,
            "id_ref"        => $id,
            "order_id"      => $id,
            "type"          => "purchase",
            "transaction_code"    => $this->transaction_model->createCode(),
            "status"              => "pre-production",
            "transaction_status"  => "belum terbayar",
            "discount"            => 0,
          );

          $trx = $this->transaction_model->insertData($datatransaction);

          $id_cart = $id;
          $data_naikan = $this->naikan_model->getNaikanItemByCart($id_cart);
          if($data_naikan){
            foreach ($data_naikan as $key => $value) {
              $arrayplanning_product = array(
                'id_naikan'       => $value->id_naikan,
                'id_cetakan'      => $value->id_cetakan,
                'qty'             => $value->total,
                'time_created'    => time(),
                'last_update'     => time(),
                'author'          => $login->id_user,
                'status'          => "active"
              );

              $this->naikan_product_planning->insertData($arrayplanning_product);
            }
          }

          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-info', 'message' => 'Transaksi Berhasil dilakukan. Transaksi masuk ke LOGISTIK, RESEP dan DAPUR PRODUKSI']);
          return redirect(url($this->config['main_url']."/manage/".$trx));
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menyimpan data transaksi']);
          return redirect(url($this->config['main_url']."/order/".$id));
        }
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function addproduksi($id, $id_transaction){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['produksi'])){
        $bahanbaku   = $this->material_model->getlist();

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->order_model->getDetail($id),
          "produks"         => $this->product_model->getlistproductfull(),
          "produk"          => $this->order_model->getItems($id),
          "id_transaction"  => $id_transaction
        );

        $view     = View::make("backend.transaction.addproduksi",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah produk",
          "description"   => $webname." | Tambah produk",
          "keywords"      => $webname." | Tambah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function addreject($id, $id_transaction){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['produksi'])){
        $bahanbaku   = $this->material_model->getlist();

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->order_model->getDetail($id),
          "produks"         => $this->product_model->getlistproductfull(),
          "produk"          => $this->order_model->getItems($id),
          "id_transaction"  => $id_transaction
        );

        $view     = View::make("backend.transaction.addreject",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah produk",
          "description"   => $webname." | Tambah produk",
          "keywords"      => $webname." | Tambah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function manageresep($id, $id_transaction){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['produksi'])){
        $transaksi  = $this->transaction_model->getDetail($id_transaction);
        //print_r($transaksi);
        //exit();
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $transaksi,
          "expirasi"       => $this->setting_model->getSettingVal("expirasi_resep"),
          "stock"          => $this->stock_unit_model->getAttachedStockToCart($transaksi->order_id),
          "id_transaction" => $id_transaction
        );

        $view     = View::make("backend.transaction.manage_resep",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah produk",
          "description"   => $webname." | Ubah produk",
          "keywords"      => $webname." | Ubah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function storeaddbahan($id, $id_transaction, Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['produksi'])){
        foreach ($request->all() as $key_aa => $value_aa) {
          if(substr($key_aa,0,5)=="naik_" && $value_aa!="" && $value_aa > 0){
            $id_purchase_detail = substr($key_aa,5);
            $dataarray = array(
              "id_purchase_detail" => $id_purchase_detail,
              "quantity"       => $value_aa,
              "time_created"    => time(),
              "last_update"     => time(),
              "status"          => 'active',
              "id_cart"         => $id,
              "author"          => $login->id_user
            );

            $this->bahan_penyerta_model->insertData($dataarray);
          }
        }

        Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-info', 'message' => 'Transaksi Berhasil dilakukan. Transaksi masuk ke LOGISTIK, RESEP dan DAPUR PRODUKSI']);
        return redirect(url($this->config['main_url']."/manage/".$id_transaction));

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function addbahan($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['produksi'])){
        $bahanbaku   = $this->material_model->getlist();

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->transaction_model->getDetail($id),
          "bahan"          => $this->material_distribution_model->getlistFull($login->id_user)
        );

        $view     = View::make("backend.transaction.addbahan",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah produk",
          "description"   => $webname." | Tambah produk",
          "keywords"      => $webname." | Tambah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function additem($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $bahanbaku   = $this->material_model->getlist();

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->order_model->getDetail($id),
          "items"          => $this->order_model->getItems($id)
        );

        $view     = View::make("backend.transaction.cart.additem",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah produk",
          "description"   => $webname." | Tambah produk",
          "keywords"      => $webname." | Tambah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function storeproduksi($id, $id_transaction, Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['produksi'])){

        foreach ($request->all() as $key_aa => $value_aa) {
          if(substr($key_aa,0,5)=="naik_" && $value_aa!="" && $value_aa > 0){
            $id_cart_detail = substr($key_aa,5);
            //hitung hpp
            $mrp                = $this->mrp_model->getMRPproductByIdCart($id_cart_detail,$value_aa);
            $bahanavail         = $this->material_movement_model->getAvailableMaterial($id);

            $terpenuhi     = false;
            $lanjut        = true;
            $datapemenuhan = array();
            $hpp           = 0;
            $butuh         = 0;
            $ada           = 0;

            //print "<pre>";
            //print_r($mrp);
            //print_r($bahanavail->toArray());
            //exit();

            foreach ($mrp as $key => $value) {
              $terpenuhi = false;
              $butuh  = $value->total;
              foreach ($bahanavail as $keys => $values) {
                if($value->id_material == $values->id_material){
                  if($value->total>$values->rest){   // jika stok mepet dipake dari sini
                    print "disini 1 butuh ".$value->total." sisa ".$values->rest." ".$values->id_movement."<br>";
                    $usage = $values->rest;
                    $datapemenuhan[] = array(
                      "id_movement" => $values->id_movement,
                      "usage"       => $values->usage + $usage,
                      "item_price"  => $values->item_price,
                      "butuh"       => $usage
                    );
                    $value->total = $value->total - $usage;
                  }else{
                    print "disini 2 butuh ".$value->total." sisa ".$values->rest." ".$values->id_movement."<br>";
                    $usage = $value->total;
                    $datapemenuhan[] = array(
                      "id_movement" => $values->id_movement,
                      "usage"       => $values->usage + $usage,
                      "item_price"  => $values->item_price,
                      "butuh"       => $usage
                    );
                    $terpenuhi = true;
                    break;
                  }
                }
              }

              if(!$terpenuhi){
                $lanjut = false;
                $namabahan = $value->material_name." [".$value->material_code."]";
                $alasan = "Bahan baku <strong>".$namabahan."</strong> pada bagian resep tidak mencukupi, dibutuhkan $butuh!";
                break;
              }
            }

            //print_r($datapemenuhan);
            //exit();

            if($lanjut){
              $hpp = 0;
              foreach ($datapemenuhan as $key => $value) {
                $update = array(
                  "id_movement" => $value['id_movement'],
                  "usage"       => $value['usage'],
                  "last_update" => time()
                );
                $hpp = $hpp + ($value['item_price'] * $value['butuh']);
                $updatequery = $this->material_movement_model->updateData($update);
              }
              //hitung HPP

              $data = array(
                "id_cart_detail"  => $id_cart_detail,
                "qty"             => $value_aa,
                "time_created"    => time(),
                "last_update"     => time(),
                "author"          => $login->id_user,
                "status"          => "active",
                "hpp_produk"      => ($hpp/$value_aa)
              );
              //print_r($data);
              //exit();
              $this->cart_pemenuhan_model->insertData($data);

              Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Berhasil membuat data produksi']);
              //print "sukses!";
            }else{
              //print 'Tidak dapat membuat data produksi! Alasan: '.$alasan;
              Session::flash('info', ['status' => 'danger', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat membuat data produksi! Alasan: '.$alasan]);
              break;
            }
            //hitung hpp
          }
        }

        return redirect(url($this->config['main_url']."/".$id."/addproduksi/".$id_transaction));

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function storereject($id, $id_transaction, Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['produksi'])){

        foreach ($request->all() as $key_aa => $value_aa) {
          if(substr($key_aa,0,5)=="naik_" && $value_aa!="" && $value_aa > 0){
            $id_product = substr($key_aa,5);
              //hitung HPP

              $data = array(
                "id_cart"         => $id,
                "id_product"      => $id_product,
                "qty"             => $value_aa,
                "time_created"    => time(),
                "last_update"     => time(),
                "author"          => $login->id_user,
                "status"          => "active",
                "date_rejection"  => date("Y-m-d"),
                "alasan"          => $request->input("alasan")
              );
              //print_r($data);
              //exit();
              $this->product_reject_model->insertData($data);

              Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Berhasil membuat data reject produksi']);
              //print "sukses!";

            //hitung hpp
          }
        }

        return redirect(url($this->config['main_url']."/".$id."/addreject/".$id_transaction));

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function create(){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $parents = array("0" => "Tidak menggunakan parent");
        $categoryproducts = $this->category_model->getList();
        if($categoryproducts){
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
          "transaction_code"  => $this->transaction_model->createCode(),
          "order_code"     => $this->order_model->createCode()
        );

        $view     = View::make("backend.transaction.create",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah produk",
          "description"   => $webname." | Tambah produk",
          "keywords"      => $webname." | Tambah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function updatemrp($parent, $id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit']) && $this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['mrp'])){
        $data = array(
          "id_mrp"            => $id,
          "id_material"       => $request->input("id_material"),
          "qty"               => $request->input("qty"),
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

        $create                 = $this->mrp_model->updateData($data);
        if($create){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah bahan pada produk!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah  bahan pada produk']);
        }
        return redirect(url($this->config['main_url'].'/mrp/'.$parent.'/edit/'.$id));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function storeitem($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){
        $product = $this->product_model->getDetail($request->input("id_product"));

        $data = array(
          "id_cart"           => $id,
          "id_product"        => $request->input("id_product"),
          "quantity"          => $request->input("quantity"),
          "item_price"        => $product->price,
          "item_discount"     => 0,
          "time_created"      => time(),
          "last_update"       => time(),
          "status"            => "active"
        );

        $rules  = array(
          "id_product"         => "required",
          "quantity"                 => "required|numeric"
    		);

    		$messages = array(
          "id_product.required"          => "Mohon mengisi produk disini",
          "quantity.required"            => "Mohon mengisi quantity disini",
          "quantity.numeric"             => "Mohon mengisi angka/nominal pada quantity bahan, gunakan . untuk menggantikan , (koma) )",
        );

    		$this->validate($request, $rules, $messages);

        $create                 = $this->cartdetail_model->insertData($data);
        if($create){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah produk dalam keranjang!']);
          return redirect(url($this->config['main_url'].'/order/'.$id));
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah produk dalam keranjang!']);
          return redirect(url($this->config['main_url'].'/'.$id.'/additem'));
        }

      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function store(Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create'])){

        $data = array(
          "id_user"       => $login->id_user,
          "id_mitra"      => $request->input("id_mitra"),
          "trx_type"      => "offline",
          "status"        => "pending",
          "cart_code"     => $request->input("order_code"),
          "time_created"  => time(),
          "last_update"   => time(),
          "type_order"    => $login->department_code,
          "cart_date"     => $request->input("cart_date")
        );

        //print_r($data);
        //exit();

        $rules  = array(
          "id_mitra"        => "required"
    		);

    		$messages = array(
          "id_mitra.required"   => "Mohon memilih merchant/mitra disini",
        );

    		$this->validate($request, $rules, $messages);

        $create                 = $this->order_model->insertData($data);
        if($create){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Keranjang berhasil dibuat. Silahkan memulai transaksi']);
          return redirect(url($this->config['main_url']."/order/".$create));
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat membuat keranjang']);
          return redirect(url($this->config['main_url']."/create"));
        }
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function mrp($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['mrp'])){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->product_model->getDetail($id),
          "photos"         => $this->product_model->getPhotos($id),
          "list"           => $this->product_model->getlistmrpproduct($id)
        );

        $data = $this->product_model->getPhotos($id);
        //dd($data);

        $view     = View::make("backend.master.product.mrp",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah produk",
          "description"   => $webname." | Ubah produk",
          "keywords"      => $webname." | Ubah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function updateOrderNaikan(Request $request){
    $login            = Session::get("user");
    $id_cart          = $request->input("id_order");
    $id_naikan_rumus  = $request->input("id_naikan_rumus");
    $quantity         = $request->input("qty");

    $arraydata        = array(
      "id_cart"             => $id_cart,
      "id_naikan_rumus"     => $id_naikan_rumus,
      "qty"                 => $quantity,
      "time_created"        => time(),
      "last_update"         => time(),
      "author"              => $login->id_user,
      "status"              => "active"
    );

    $check    = $this->naikan_model->checkData($arraydata);
    if($check){
      unset($arraydata['time_created']);
      $result   = $this->naikan_model->updateDataByArray($arraydata);
    }else{
      $result   = $this->naikan_model->insertData($arraydata);
    }

    if($result){
      $result = array(
        "status" 	  => 200,
        "response" 	=> "Berhasil menyimpan data"
      );
    }else{
      $result = array(
        "status" 	  => 400
      );
    }

    return response()->json($result,200);
  }

  public function manageorder($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->order_model->getDetail($id),
          "items"          => $this->order_model->getItems($id),
          "rumus"          => $this->naikanrumus_model->getDataRumus(),
          "rumus_item"     => $this->naikanrumus_items_model,
          "loyang"         => $this->rumus_product_model->getLoyangFromKebutuhanProduct($id)
        );

        $view     = View::make("backend.transaction.cart.manage",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Manage Order",
          "description"   => $webname." | Manage Order",
          "keywords"      => $webname." | Manage Order"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function dobulk($id, $naikan, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['pemenuhan'])){

        foreach ($request->all() as $key => $value) {
          $idi   = substr($value,0,3);
          $val  = substr($value,3);
          if($idi=="cb_"){
            $data = array(
              "id_unit"         => $val,
              "id_naikan"       => $naikan,
              "last_update"     => time()
            );

            $update                 = $this->stock_unit_model->updateData($data);
            if(!$update){
              Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal melakukan penggunaan stock!']);
              return redirect(url('master/transaksi/manage/'.$id.'/pemenuhan/'.$naikan));
            }
          }
        }

        Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Berhasil melakukan penggunaan stock']);

        return redirect(url('master/transaksi/manage/'.$id.'/pemenuhan/'.$naikan));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function usagedobulk($id, $id_transaction, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['pemenuhan'])){
        $submit = $request->all();
        //print "<pre>";
        //print_r($submit);
        //print "</pre>";

        foreach ($submit as $key => $value) {
          //print_r($value);
          //exit();
          $idi    = substr($key,0,4);
          $val    = $value;
          //print($idi);
          //print "<br>";
          //print($val);
          //exit();
          if($idi=="ccb_"){

            $data = array(
              "id_unit"                    => $value,
              "production_status"          => "used",//($value!="")?"used":"ready",
              "last_update"                => time()
            );

              //print_r($data);
            //exit();

            $update                 = $this->stock_unit_model->updateData($data);
            if(!$update){
              Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal melakukan penggunaan stock!']);
              return redirect(url('master/transaksi/'.$id.'/manage_pakai/'.$id_transaction));
            }
          }
        }

        Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Berhasil melakukan penggunaan stock']);

        return redirect(url('master/transaksi/'.$id.'/manage_pakai/'.$id_transaction));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function dopemenuhan($id, $naikan, $unit, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['pemenuhan'])){
        $data = array(
          "id_unit"         => $unit,
          "id_naikan"       => $naikan,
          "last_update"     => time()
        );

        $update                 = $this->stock_unit_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Berhasil melakukan penggunaan stock']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal melakukan penggunaan stock!']);
        }
        return redirect(url('master/transaksi/manage/'.$id.'/pemenuhan/'.$naikan));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function hapuspemenuhan($id, $naikan, $unit, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
        $data = array(
          "id_unit"         => $unit,
          "id_naikan"       => null,
          "last_update"     => time()
        );

        $update                 = $this->stock_unit_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Berhasil membatalkan penggunaan stock']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Gagal membatalkan penggunaan stock!']);
        }
        return redirect(url('master/transaksi/manage/'.$id.'/pemenuhan/'.$naikan));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }


  public function pemenuhan($id, $subid){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
        $transaksi  = $this->transaction_model->getDetail($id);
        $datasub    = $this->naikan_model->getDetail($subid);
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $transaksi,
          "datasub"        => $datasub,
          "expirasi"       => $this->setting_model->getSettingVal("expirasi_resep"),
          "stock"          => $this->stock_unit_model->getData($datasub->id_naikan_rumus),
          "pemenuhan"      => $this->stock_unit_model->getUsedStock($subid)
        );

        $view     = View::make("backend.transaction.pemenuhan",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah produk",
          "description"   => $webname." | Ubah produk",
          "keywords"      => $webname." | Ubah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function produksi($id, $subid){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['produksi'])){
        $transaksi  = $this->transaction_model->getDetail($id);
        $datacart   = $this->cart_detail_model->getDetail($subid);

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $transaksi,
          "data_cart"      => $datacart,
          "pemenuhan"      => $this->cart_pemenuhan_model->getPemenuhan($subid)
        );

        $view     = View::make("backend.transaction.produksi",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah produk",
          "description"   => $webname." | Ubah produk",
          "keywords"      => $webname." | Ubah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function manage($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){

        $transaksi = $this->order_model->getDetail($id);

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $transaksi,
          "productreject_model" => $this->product_reject_model
        );

        $view     = View::make("backend.transaction.manage",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah produk",
          "description"   => $webname." | Ubah produk",
          "keywords"      => $webname." | Ubah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function edit($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){
        $categoryproducts = $this->category_model->getList();
        if($categoryproducts){
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
          "categories"     => $categories
        );

        $view     = View::make("backend.master.product.edit",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Ubah produk",
          "description"   => $webname." | Ubah produk",
          "keywords"      => $webname." | Ubah produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }

  public function update($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){

        $data = array(
          "id_product"          => $id,
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
          "id_vendor"           => "0",
          "cart_date"           => "Cart daate "
        );

        $rules  = array(
          "product_name"        => "required",
          "price"               => "required|numeric",
          "product_code"        => "required|unique:tb_product,product_code,".$id.",id_product"
    		);

    		$messages = array(
          "product_name.required"   => "Mohon mengisi nama produk disini",
          "price.required"          => "Mohon mengisi harga produk disini",
          "price.numeric"           => "Mohon mengisi angka/nominal pada harga produk",
          "product_code.required"   => "Mohon mengisi Kode produk disini",
          "product_code.unique"     => "Kode produk sudah digunakan oleh produk lain"
        );

    		$this->validate($request, $rules, $messages);

        $update                 = $this->product_model->updateData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengubah data produk!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data produk']);
        }
        return redirect(url('admin/product-uronshop/edit/'.$id));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function removeitem(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['remove'])){
        $id         = $request->input("id");
        $id_parent  = $request->input("parent_id");

        $detail = $this->product_model->getDetail($id);
        $data = array(
          "id_cart_detail"      => $id,
          "status"              => "deleted",
          "last_update"         => time()
        );
        $remove = $this->cartdetail_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data']);
        }
        return redirect(url($this->config['main_url'].'/order/'.$id_parent));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }


  public function removeitembahanextra(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['produksi'])){
        $id         = $request->input("id");
        $id_parent  = $request->input("parent_id");

        $detail = $this->product_model->getDetail($id);
        $data = array(
          "id_bahan_penyerta_produksi"      => $id,
          "status"                          => "removed",
          "last_update"                     => time()
        );
        $remove = $this->bahan_penyerta_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data']);
        }
        return redirect(url($this->config['main_url'].'/manage/'.$id_parent));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function removetrx(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['remove'])){
        $id = $request->input("id");
        $detailtransaction = $this->transaction_model->getDetail($id);
        $data = array(
          "id_transaction"      => $id,
          "transaction_status"  => "dibatalkan",
          "last_update"         => time()
        );
        $remove = $this->transaction_model->updateData($data);

        $data = array(
          "id_cart"             => $detailtransaction->order_id,
          "status"              => "cancelled",
          "last_update"         => time()
        );
        $remove = $this->order_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Transaksi dibatalkan']);
          return redirect(url($this->config['main_url']));
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat membatalkan']);
          return redirect(url($this->config['main_url']."/manage/".$id));
        }
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function donetrx(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['produksi'])){
        $id = $request->input("id");
        $detailtransaction = $this->transaction_model->getDetail($id);
        $data = array(
          "id_transaction"      => $id,
          "transaction_status"  => "done",
          "status"              => "done",
          "last_update"         => time()
        );
        $remove = $this->transaction_model->updateData($data);

        $data = array(
          "id_cart"             => $detailtransaction->order_id,
          "status"              => "done",
          "last_update"         => time()
        );
        $remove = $this->order_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Permintaan telah diselesaikan!']);
          return redirect(url($this->config['main_url']));
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menyelesaikan permintaan']);
          return redirect(url($this->config['main_url']."/manage/".$id));
        }
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function confirmpayment(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['payment'])){
        $id = $request->input("id");
        $detail = $this->product_model->getDetail($id);
        $data = array(
          "id_transaction"      => $id,
          "transaction_status"  => "terbayar",
          "status"              => "queue",
          "last_update"         => time()
        );

        $confirmed = $this->transaction_model->updateData($data);

        if($confirmed){
          $detailtransaction  = $this->transaction_model->getDetail($id);
          $category           = $this->category_jurnal_model->getDetailByCode("JUAL");
          $total              = $this->order_model->getTotalTransaction($detailtransaction->order_id);

          $datajurnal = array(
            "id_reff"               => $id,
            "id_category_jurnal"    => $category->id_category_jurnal,
            "type"                  => "kredit",
            "transaction"           => "Konfirmasi pembayaran manual #".$detailtransaction->transaction_code,
            "time_created"          => time(),
            "last_update"           => time(),
            "author"                => $login->id_user,
            "total"                 => $total,
            "reff_code"             => $detailtransaction->transaction_code
          );

          $this->jurnal_model->insertData($datajurnal);

          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Transaksi Terbayar dan Masuk Status Antrian Produksi']);
          return redirect(url($this->config['main_url']));
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat membayar transaksi']);
          return redirect(url($this->config['main_url']."/manage/".$id));
        }
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function remove(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['remove'])){
        $id = $request->input("id");
        $detail = $this->product_model->getDetail($id);
        $data = array(
          "id_cart"             => $id,
          "status"              => "cancelled",
          "last_update"         => time()
        );
        $remove = $this->order_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-info', 'message' => 'Order dibatalkan']);
          return redirect(url($this->config['main_url']));
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat membatalkan']);
          return redirect(url($this->config['main_url']."/manage/".$id));
        }
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function restore($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['restore'])){
        $detail = $this->product_model->getDetail($id);
        $data = array(
          "id_product"            => $id,
          "status"                => "available",
          "last_update"           => time()
        );
        $remove = $this->product_model->updateData($data);

        if($remove){
          $undolink = url('admin/service/restore/'.$id);
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengembalikan data <strong>'.$detail->product_name.'</strong>']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengembalikan data ']);
        }
        return redirect(url($this->config['main_url']));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function send(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['kirim'])){
        $id   = $request->input("id");

        $data = array(
          "id_transaction"        => $id,
          "status"                => "production",
          "last_update"           => time()
        );
        $send = $this->transaction_model->updateData($data);

        if($send){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Berhasil mengirim resep ke dapur']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengirim resep ']);
        }
        return redirect(url($this->config['main_url'].'/manage/'.$id));
      }else{
        $webname  = $this->setting_model->getSettingVal("website_name");
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
        return view($body,$data);
      }
    }else{
      return redirect(url('login'));
    }
  }

  public function detail($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['view'])){
        $categoryproducts = $this->category_model->getList();
        if($categoryproducts){
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
          "categories"     => $categories
        );

        $view     = View::make("backend.master.product.detail",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail produk",
          "description"   => $webname." | Detail produk",
          "keywords"      => $webname." | Detail produk"
        );

        $body = "backend.body_backend_with_sidebar";

      }else{
        $view     = View::make("backend.403");
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Halaman tidak diperbolehkan",
          "description"   => $webname." | Halaman tidak diperbolehkan",
          "keywords"      => $webname." | Halaman tidak diperbolehkan"
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
      return view($body,$data);
    }else{
      return redirect(url('login'));
    }
  }
}
