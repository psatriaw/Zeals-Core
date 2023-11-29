<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
  $main_url    = $config['main_url'];
  $methods     = $config;

?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kelola Keranjang</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Transaksi</a>
                    </li>
                    <li class="active">
                        <strong>Kelola Keranjang</strong>
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
                        <h5>Detail Keranjang</h5>
                    </div>
                    <div class="ibox-content bottom30">
                      {!! Form::model($data,['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}">
                                  <label class="col-sm-4 control-label">Tanggal dibuat</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->time_created)?>">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Terakhir diubah</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->last_update)?>">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Order</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->cart_code?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Gudang/Pabrik</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->mitra_name?>" name="purchase_code">
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="alert alert-info">Keranjang belum terjadi transaksi. Transaksi baru dibuat setelah checkout dilakukan.</div>
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                    @include('backend.flash_message')
                    <div class="ibox-title">
                        <h5>Bahan Baku Non MRP</h5>
                    </div>

                    {!! Form::model($data,['url' => url($main_url.'/storeaddbahan/'.$data->id_cart.'/'.$data->id_transaction), 'method' => 'post', 'id' => 'formmain', 'data-parsley-validate novalidate']) !!}
                    <div class="ibox-content">
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th style="width:300px;">Nama Bahan</th>
                                <th style="width:150px;">Kode Bahan</th>
                                <th style="width:150px;">Kode Pembelian</th>
                                <th style="width:150px;">Satuan</th>
                                <th>Rencana</th>
                                <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>

                            <?php

                              if($bahan){
                                foreach ($bahan as $key => $value) {
                                  //print_r($value);
                                  ?>
                                  <tr>
                                    <td><?=$value->material_name?></td>
                                    <td><?=$value->material_code?></td>
                                    <td><?=$value->purchase_code?></td>
                                    <td><?=$value->material_unit?></td>
                                    <td><div id="" class="label-area">0</div><div class="label-meter" id="slider_s_<?=$value->id_purchase_detail?>"></div><div id="" class="label-area"><?=(@$value->stock)?></div></td>
                                    <td>
                                      <input type="text" class="label-area" name="naik_<?=$value->id_purchase_detail?>" id="naik_<?=$value->id_purchase_detail?>">
                                    </td>
                                  </tr>
                                  <?php
                                }
                              }else{
                                ?>
                                <tr>
                                  <td colspan="10">Tidak ada data</td>
                                </tr>
                                <?php
                              }
                            ?>
                          </tbody>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group top15">
                          <div class="col-sm-6">
                              <a class="btn btn-white" href="{{ url($main_url."/manage/".$data->id_transaction) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">
                            <button class="btn btn-info" type="submit">Simpan</button>
                          </div>
                      </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>

<script>
  <?php
  if($bahan){
    foreach ($bahan as $key => $value) {
      ?>

      $( "#slider_s_<?=$value->id_purchase_detail?>" ).slider({
        value:0,
        min: 0,
        max: <?=(@$value->stock)?>,
        step: 0.1,
        slide: function( event, ui ) {
          $( "#label-info-<?=$value->id_purchase_detail?>" ).html(ui.value );
          $("#naik_<?=$value->id_purchase_detail?>").val(ui.value);

        }
      });

      $("#naik_<?=$value->id_purchase_detail?>").keyup(function(e){
        var nilai = $(this).val();
        $( "#slider_s_<?=$value->id_purchase_detail?>").slider("value",nilai);
      });
      <?php
    }
  }
  ?>
</script>
