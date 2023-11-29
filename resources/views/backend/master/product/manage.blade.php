<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Produk</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Produk</a>
                    </li>
                    <li class="active">
                        <strong>Atur Foto Produk</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Atur Foto Produk <span class="text-info">[{{$data->product_name}}]</span></h5>
                    </div>
                    <div class="ibox-content" id="alert-test">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      <div class="row" id="item-images">
                        <?php
                            if($photos){
                              foreach($photos as $key=>$val){
                                ?>
                                <div class="col-sm-1">
                                  <img src="<?=url($val->thumbnail)?>" class="img-responsive img-thumbnail">
                                  <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove'])){?>
                                    <div class="float-right-top confirm" data-url="{{ url('remove-photo') }}" data-id="{{ $val->id_product_detail }}">
                                        <i class="fa fa-trash"></i> hapus
                                    </div>
                                  <?php } ?>
                                </div>
                                <?php
                              }
                            }
                        ?>
                          <div class="col-sm-1">
                              <button class="btn btn-upload-photo" onclick="showuploadmodal();">
                                  <i class="fa fa-plus fa-lg"></i>
                              </button>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-4">
                            <a class="btn btn-white" href="{{ url($main_url) }}">
                                <i class="fa fa-angle-left"></i> kembali
                            </a>
                            <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove']) && sizeof($photos)){?>
                            <a class="btn btn-danger btn-outline confirm" data-url="{{ url('remove-all-photo') }}" data-id="{{ $data->id_product }}">
                                <i class="fa fa-trash"></i> hapus semua foto
                            </a>
                          <?php } ?>
                        </div>
                        <div class="col-sm-6 text-right">

                        </div>
                      </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @include('backend.footer')
    @include('backend.do_confirm')
  </div>
</div>
@include('backend.master.product.photouploader',array("id_product" => $data->id_product))
