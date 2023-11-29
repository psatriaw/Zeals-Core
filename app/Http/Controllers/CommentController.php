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

use App\Http\Models\CommentModel;
use App\Http\Models\UserModel;
use App\Http\Models\PhotoModel;

use Intervention\Image\ImageManagerStatic as Image;

class CommentController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;
  private $config;

  private $user_model;
  private $comment_model;

  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $config['main_url'] = "master/comment";
    $config['reset']    = "comment-reset";
    $config['edit']     = "comment-edit";
    $config['view']     = "comment-view";
    $config['manage']   = "comment-manage";
    $config['remove']   = "comment-remove";
    $config['restore']  = "comment-restore";
    $config['create']   = "comment-create";

    $this->config       = $config;

    $this->user_model       = new UserModel();
    $this->comment_model    = new CommentModel();
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
          "content"               => "Content",
          "time_created"          => "Time created",
          "last_update"           => "Last update"
        );

        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;
        $data       = $this->comment_model->getData($str, $limit, $keyword, $short, $shortmode);

        $totaldata  = $this->comment_model->countData($keyword);
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

        $view     = View::make("backend.master.comment.index",$datacontent);
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

  public function proses($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])) {
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

        $view     = View::make("backend.master.produksi.proses",$datacontent);
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
          "categories"     => $categories
        );

        $view     = View::make("backend.master.produksi.create",$datacontent);
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

  public function update($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['edit'])){
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
        if($create){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully updated']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to update data']);
        }
        return redirect(url($this->config['main_url'].'/edit/'.$id));
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

  public function storemrp($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['create']) && $this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['mrp'])){
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
        if($create){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah bahan pada produk!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah  bahan pada produk']);
        }
        return redirect(url($this->config['main_url'].'/mrp/'.$id.'/create'));
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
        if($create){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah produk!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah produk']);
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

  public function manage($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
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
          "photos"         => $this->product_model->getPhotos($id),
          "categories"     => $categories
        );

        $data = $this->product_model->getPhotos($id);
        //dd($data);

        $view     = View::make("backend.master.produksi.manage",$datacontent);
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

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->post_model->getDetail($id),
        );

        $view     = View::make("backend.master.post.edit",$datacontent);
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

  public function updateproses($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['manage'])){
        $detail = $this->production_model->getDetail($id);
        if($detail->status=="production"){
          $items  = $this->order_model->getItemsMRP($detail->order_id);
          $data = array(
            "id_transaction"      => $id,
            "status"              => "production",
            "last_update"         => time()
          );
          $production = $this->production_model->updateData($data);

          if($production){
            $completions = 0;
            foreach ($items as $key => $value) {
              $item = $this->production_completion_model->getDetail($value->id_production);
              $update = array();

                $update = array(
                  "id_production"           => $item->id_production,
                  "status"                  => ($detail->production_quantity==$request->input("produce_".$value))?"ready":"production",
                  "production_completion"   => $request->input("produce_".$item->id_production),
                );

                if($item->production_completion!=$request->input("produce_".$item->id_production)){
                  $update['last_update']  = time();
                }

                if($update['status']=="ready"){
                  $completions++;
                }

                $this->production_completion_model->updateData($update);
              }
            }

            if($completions>=sizeof($items)){
              $updateproduksi = array(
                "id_transaction"  => $id,
                "last_update"     => time(),
                "status"          => "ready"
              );

              $this->production_model->updateData($updateproduksi);

              Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengupdate! Produksi pesanan telah selesai!']);
              return redirect(url($this->config['main_url'].'/proses/'.$id));
            }else{
              Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengupdate status produksi']);
              return redirect(url($this->config['main_url'].'/proses/'.$id));
            }

        }elseif($detail->status=="ready"){
          $data = array(
            "id_transaction"      => $id,
            "status"              => "packed",
            "last_update"         => time()
          );
          $production = $this->production_model->updateData($data);

          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengupdate! Pesanan dalam status terpacking!']);
          return redirect(url($this->config['main_url'].'/proses/'.$id));
        }elseif($detail->status=="packed"){
          $data = array(
            "id_transaction"      => $id,
            "status"              => "shipted",
            "last_update"         => time()
          );
          $production = $this->production_model->updateData($data);

          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengupdate! Pesanan dalam status pengiriman!']);
          return redirect(url($this->config['main_url'].'/proses/'.$id));
        }
        //return redirect(url('admin/product-uronshop/edit/'.$id));
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
        $detail = $this->comment_model->getDetail($id, url(""));
        $data = array(
          "id_comment"          => $id,
          "status"              => "deleted",
          "last_update"         => time()
        );
        $remove = $this->comment_model->updateData($data);

        if($remove){
          $undolink = url($this->config['main_url'].'/restore/'.$id);
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully removed! click <a href="'.$undolink.'" class="text-danger"><strong>here</strong></a> ro recover the removed data.']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to remove data']);
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

  public function restore($id){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,$this->config['restore'])){
        $detail = $this->comment_model->getDetail($id,url(""));
        $data = array(
          "id_comment"            => $id,
          "status"                => "active",
          "last_update"           => time()
        );
        $remove = $this->comment_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Data successfully restored']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Failed to restore data']);
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

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "config"         => $this->config,
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->comment_model->getDetail($id, url("")),
        );

        $view     = View::make("backend.master.comment.detail",$datacontent);
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
