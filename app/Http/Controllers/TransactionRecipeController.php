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
use App\Http\Models\CetakanModel;

use Intervention\Image\ImageManagerStatic as Image;

class TransactionRecipeController extends Controller{
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
  private $cetakan_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $config['main_url'] = "master/transaksi-resep";
    $config['create']   = "transaction-recipe-create";
    $config['edit']     = "transaction-recipe-edit";
    $config['view']     = "transaction-recipe-view";
    $config['manage']   = "transaction-recipe-manage";
    $config['remove']   = "transaction-recipe-remove";
    $config['restore']  = "transaction-recipe-restore";
    $config['payment']  = "transaction-recipe-payment";

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
    $this->cetakan_model      = new CetakanModel();
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
        $data       = $this->transaction_model->getData($str, $limit, $keyword, $short, $shortmode,"recipe");

        $totaldata  = $this->transaction_model->countData($keyword,"recipe");
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
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.transaction.recipe.index",$datacontent);
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


  public function indexresep(Request $request){
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
        $data       = $this->transaction_model->getData($str, $limit, $keyword, $short, $shortmode,"recipe");

        $totaldata  = $this->transaction_model->countData($keyword,"recipe");
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
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

  public function checkoutproduksi($id_production, Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
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
          return redirect(url("master/produksi-resep/".$id_production."/join/item/".$id));
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menyimpan data transaksi']);
          return redirect(url($this->config['main_url']."/manage-production/".$id.'/'.$id_production));
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

        $view     = View::make("backend.transaction.recipe.create",$datacontent);
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
          "id_mitra"      => 0,
          "trx_type"      => "offline",
          "status"        => "pending",
          "cart_code"     => $request->input("order_code"),
          "description"   => $request->input("description"),
          "type_order"    => "recipe",
          "time_created"  => time(),
          "last_update"   => time(),
          "cart_date"     => $request->input("cart_date")
        );

        $rules  = array(
          "description"        => "required"
    		);

    		$messages = array(
          "description.required"   => "Mohon mengisi deskripsi disini",
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
          "loyang"         => $this->cetakan_model->getAllLoyangAvailable()
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

  public function manage($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
        $transaksi = $this->transaction_model->getDetail($id);
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $transaksi,
          "items"          => $this->order_model->getItems($transaksi->order_id),
          "planning"       => $this->naikan_model->getNaikanItemByCartGrouped($transaksi->order_id),
          "naikan"         => $this->naikan_model->getDataNaikan($transaksi->order_id)
        );

        $view     = View::make("backend.transaction.recipe.manage",$datacontent);
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
          "id_vendor"           => "0"
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
