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
                <h2>Permintaan PPIC</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Transaksi</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Kelola Transaksi</a>
                    </li>
                    <li class="active">
                        <strong>Kelola Hasil Produksi</strong>
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
                        <h5>Detail Permintaan</h5>
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
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->mitra_name?> [<?=$data->mitra_code?>] a/n <?=$data->first_name?>" name="purchase_code">
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Transaksi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->transaction_code?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Status</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->status?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Creator</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->admin_name?>" name="purchase_code">
                                  </div>
                              </div>
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                    <br>

                    <div class="col-sm-offset-3 col-sm-6 col-xs-12">
                        <?php
                          $kurangan = 0;//$data_cart->quantity - $data_cart->total_dapat;
                          if($kurangan>0){ ?>
                          <div class="alert alert-info text-center">
                            <h3>Dibutuhkan: <?=number_format(@$kurangan,0,",",".")?></h3>
                          </div>
                        <?php }else{ ?>
                          <div class="alert alert-success text-center">
                            <h3>Terpenuhi</h3>
                          </div>
                        <?php }?>
                    </div>
                    <br><br><br><br>
                    <div class="panel panel-default">
                      <div class="panel-heading">Pilih Penggunaan Stock</div>
                      <div class="panel-body">
                        <div id="" class="label-area">0</div><div class="label-meter" id="keperluan"></div><div id="" class="label-area">1000</div>
                        <div class="text-center" style="margin-top:25px;">
                            <input type="text" name="movement_qty" id="picked" class="form-control" style="width: 100px;display: inline;border: 1px solid #aaa;height: 30px;border-radius: 3px;font-weight: 700;color: #62291e;margin-top: -1px;margin-right:10px;" value="0">
                            dari <div id="" class="label-area" style="width:200px;"><?=number_format(@$stock,0,",",".")?> <?=@$data->material_unit?></div>
                        </div>
                      </div>
                      <div class="panel-footer text-right">
                          <button type="submit" name="submit" class="btn btn-primary">Gunakan <i class='fa fa-angle-right'></i></button>
                      </div>
                    </div>
                    <br><br>

                    <div class="ibox-title">
                        <h5>Item Produksi untuk pemenuhan  <?=@$datasub->naikan_rumus_name?> [<?=@$datasub->naikan_rumus_code?>]</h5>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="alert alert-info">
                        List dibawah ini hanya merupakan stock yang tersedia/belum digunakan untuk pemenuhan permintaan PPIC.
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Stock</th>
                                <th>Kode Produksi</th>
                                <th>Waktu Produksi</th>
                                <th>Expirasi</th>
                                <th>HPP</th>
                                <?php if($data->status=="pre-production"){ ?>
                                <th>Aksi</th>
                                <?php } ?>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;

                              $product_status = array('active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'available' =>'Tersedia','unavailable' => 'Tidak Tersedia');
                              $now = time();
                              if(@$pemenuhan){
                                foreach ($pemenuhan as $key => $value) {
                                  $counter++;
                                  $stock_date = strtotime($value->stock_date);
                                  if((time() - $stock_date)>86400){
                                    $sisatime = ((time() - $stock_date)%86400);
                                    $sisa = $expirasi - ((time() - $stock_date - $sisatime)/86400);
                                  }else{
                                    $sisa = $expirasi;
                                  }
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->stock_unit_code?></td>
                                    <td><?=@$value->production_code?></td>
                                    <td><?=@$value->stock_date?></td>
                                    <td><?=$sisa?> hari
                                      <?php if($data->status=="pre-production"){ ?>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                          <a href="{{ url('master/stock-resep/manage/'.$datasub->id_naikan_rumus.'/restock/'.$value->id_unit) }}" target="_blank" class="btn btn-info btn-outline dim btn-xs"> perbarui <i class="fa fa-reply"></i></a>
                                      <?php }?>
                                      <?php }?>
                                    </td>
                                    <td class="text-right">Rp. <?=number_format($value->hpp,2,",",".")?></td>
                                    <?php if($data->status=="pre-production"){ ?>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                          <a data-url="{{ url($main_url.'/manage/'.@$data->id_transaction.'/pemenuhan/'.$datasub->id_naikan.'/hapuspemenuhan/'.$value->id_unit) }}" class="btn btn-danger btn-outline dim btn-xs confirm"> hapus <i class="fa fa-times"></i></a>
                                      <?php }?>
                                    </td>
                                    <?php } ?>
                                  </tr>
                                  <?php
                                }
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
                              <a class="btn btn-white" href="{{ url($main_url.'/manage/'.$data->id_transaction) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">

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
  $("#bulk_checkbox").change(function(e){

    if($("#bulk_checkbox").prop("checked")){
      console.log("checked");
      $(".ctrl_bulk").prop("checked",true);
    }else{
      console.log("unchecked");
      $(".ctrl_bulk").prop("checked",false);
    }
  });
</script>
<script>
$( "#keperluan" ).slider({
  value:0,
  min: 0,
  max: 1000,
  step: 1,
  slide: function( event, ui ) {
    $( "#picked" ).val(ui.value );
  }
});

$(document).ready(function(){
  $( "#picked" ).keyup(function(e){
    var values = $("#picked").val();
    var max    = 1000;

    if(values>=max){
      $("#picked").val(max);
      $( "#keperluan" ).slider('value',max);
    }else{
      $( "#keperluan" ).slider('value',values);
    }
  });
});
</script>
