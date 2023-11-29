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

use App\Http\Models\TicketModel;
use App\Http\Models\TicketCommentModel;

class TicketController extends Controller{
  private $setting_model;
  private $previlege_model;
  private $helper;

  private $ticket_model;
  private $ticketcomment_model;


  public function __construct(){
    $this->setting_model    = new SettingModel();
    $this->previlege_model  = new PrevilegeModel();
    $this->helper           = new IGWHelper();

    $this->ticket_model     = new TicketModel();
    $this->ticketcomment_model = new TicketCommentModel();
  }

  public function index(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"master-ticket-show")){
        $default    = array(
          "short"     => "last_update",
          "shortmode" => "desc"
        );
        $shorter = array(
          "subject"           => "Subjek",
          "name_sender"       => "Nama Pembuat",
          "name_target"       => "Nama Penerima",
          "time_created"      => "Tgl Daftar",
          "last_update"       => "Tgl Diperbarui"
        );
        $page       = $request->input("page");
        $limit      = $this->setting_model->getSettingVal("limit_data_perpage");
        $keyword    = $request->input("keyword");
        $short      = ($request->input("short")!="")?$request->input("short"):$default['short'];
        $shortmode  = ($request->input("shortmode")!="")?$request->input("shortmode"):$default['shortmode'];
        $str        = ($page!="")?(($page-1)*$limit):0;
        $data       = $this->ticket_model->getData($str, $limit, $keyword, $short, $shortmode);

        $totaldata  = $this->ticket_model->countData($keyword);
        $pagging    = $this->helper->showPagging($totaldata, url('admin/ticket/?keyword='.$keyword."&short=".$short."&shortmode=".$shortmode), $position = "", $page, $limit , 2);

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
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model)
        );

        $view     = View::make("backend.master.ticket.index",$datacontent);
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

  public function create(){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"master-ticket-create")){

        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "ticket_code"    => $this->ticket_model->createNewCode("TC")
        );

        $view     = View::make("backend.master.ticket.create",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Tambah ticket",
          "description"   => $webname." | Tambah ticket",
          "keywords"      => $webname." | Tambah ticket"
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

  public function store(Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"master-ticket-create")){
        $data = array(
          "ticket_code"       => $this->ticket_model->createNewCode("TC"),
          "target"            => $request->input("target"),
          "sender"            => $request->input("sender"),
          "subject"           => $request->input("subject"),
          "status"            => $request->input("status"),
          "time_created"      => time(),
          "last_update"       => time(),
        );

        $rules  = array(
          "subject"           => "required",
          "target"            => "required",
          "sender"            => "required",
    		);

    		$messages = array(
          "subject.required"  => "Mohon mengisi subjek disini",
          "target.required"   => "Mohon mengisi penerima disini",
          "sender.required"   => "Mohon mengisi pembuat disini"
        );

    		$this->validate($request, $rules, $messages);

        $create                 = $this->ticket_model->insertData($data);
        if($create){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menambah ticket!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menambah ticket']);
        }
        return redirect(url('master/ticket/manage/'.$create));
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

  public function storecomment($id, Request $request){
    $login    = Session::get("user");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"master-ticket-manage")){
        $detail = $this->ticket_model->getDetail($id);
        $data = array(
          "id_ticket"           => $id,
          "content"             => $request->input("content"),
          "author"              => $login->id_user,
          "time_created"        => time(),
          "last_update"         => time(),
        );

        if($request->file('photo')){
          $uploadedFile = $request->file('photo');
          $path         = public_path('ticket');
          $filename     = time()."_".$uploadedFile->getClientOriginalName();
          $file         = $uploadedFile->move($path, $filename);
          $data['type'] = "file";
          if($file){
              $data['file_path']  = "public/ticket/".$filename;
          }else{
            Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengunggah file']);
          }
        }else{
          $rules  = array(
            "content"         => "required",
      		);

      		$messages = array(
            "content.required"  => "Mohon mengisi balasan disini",
          );

      		$this->validate($request, $rules, $messages);

          $data['type'] = "text";
        }

        $update                 = $this->ticketcomment_model->insertData($data);
        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengirim balasan!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengubah data kategori']);
        }
        return redirect(url('master/ticket/manage/'.$id));
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"master-ticket-remove")){
        $id = $request->input("id");
        $detail = $this->ticket_model->getDetail($id);
        $data = array(
          "id_ticket"             => $id,
          "status"                => "deleted",
          "last_update"           => time()
        );
        $remove = $this->ticket_model->updateData($data);

        if($remove){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menghapus data ticket']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menghapus data ticket']);
        }
        return redirect(url('master/ticket'));
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-category-restore")){
        $detail = $this->ticket_model->getDetail($id);
        $data = array(
          "id_product_category"   => $id,
          "status"                => "active",
          "last_update"           => time()
        );
        $remove = $this->ticket_model->updateData($data);

        if($remove){
          $undolink = url('admin/service/restore/'.$id);
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses mengembalikan data <strong>'.$detail->category_name.'</strong>!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat mengembalikan data ']);
        }
        return redirect(url('admin/category'));
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"master-ticket-show")){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->ticket_model->getDetail($id),
          "percakapan"     => $this->ticketcomment_model->getListBalasan($id)
        );

        $view     = View::make("backend.master.ticket.detail",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail kategori",
          "description"   => $webname." | Detail kategori",
          "keywords"      => $webname." | Detail kategori"
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
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"master-ticket-manage")){
        $datacontent = array(
          "login"          => $login,
          "helper"         => "",
          "previlege"      => $this->previlege_model,
          "head_menu"      => $this->helper->getMenuContent($login, $this->previlege_model),
          "data"           => $this->ticket_model->getDetail($id),
          "percakapan"     => $this->ticketcomment_model->getListBalasan($id)
        );

        $view     = View::make("backend.master.ticket.manage",$datacontent);
        $content  = $view->render();

        $metadata = array(
          "title"         => $webname." | Detail kategori",
          "description"   => $webname." | Detail kategori",
          "keywords"      => $webname." | Detail kategori"
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

  public function markdone(Request $request){
    $login    = Session::get("user");
    $webname  = $this->setting_model->getSettingVal("website_name");
    if($login){
      if($this->previlege_model->isAllow($login->id_user,$login->id_department,"master-ticket-manage")){
        $id = $request->input("id");
        $detail = $this->ticket_model->getDetail($id);
        $data = array(
          "id_ticket"             => $id,
          "status"                => "close",
          "last_update"           => time()
        );
        $update = $this->ticket_model->updateData($data);

        if($update){
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-success', 'message' => 'Sukses menandai ticket selesai!']);
        }else{
          Session::flash('info', ['status' => 'success', 'alert-class' => 'alert-danger', 'message' => 'Tidak dapat menandai ticket selesai!']);
        }
        return redirect(url('master/ticket'));
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

}
