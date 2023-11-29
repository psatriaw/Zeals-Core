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
                        <strong>Kelola Pemenuhan</strong>
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
                          $kurangan = $datasub->qty - $datasub->total_dapat;
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
                    <br><br>
                    <br><br>
                    <br><br>
                    <?php //if($data->status=="pre-production"){ ?>
                    {!! Form::model($data,['url' => url($main_url.'/manage/'.$data->id_transaction.'/pemenuhan/'.$datasub->id_naikan.'/dobulk'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                    <div class="ibox-title">
                        <h5>Item Tersedia pada Resep untuk Resep <?=$datasub->naikan_rumus_name?> [<?=$datasub->naikan_rumus_code?>]</h5>
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
                                <th>Catatan Produksi</th>
                                <th>Expirasi</th>
                                <th>HPP</th>
                                <th>Aksi</th>
                                <th>Bulk
                                    <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['alokasi-resep'])){?>
                                    [ semua <input type='checkbox' id='bulk_checkbox'> ]
                                    <?php }?>
                                </th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;
                              $penuhan_kekurangan = $kurangan;

                              $product_status = array('active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'available' =>'Tersedia','unavailable' => 'Tidak Tersedia');
                              $now = time();
                              if(@$stock){
                                foreach ($stock as $key => $value) {
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
                                    <td><?=(@$value->id_naikan!="" && $value->production_status=="ready")?"<span class='text-danger'>ada di dapur (belum dipakai)</span>":"ada di resep"?></td>
                                    <td><?=$sisa?> hari
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                          <a href="{{ url('master/stock-resep/manage/'.$datasub->id_naikan_rumus.'/restock/'.$value->id_unit) }}"  target="_blank" class="btn btn-info btn-outline dim btn-xs"> perbarui <i class="fa fa-reply"></i></a>
                                      <?php }?>
                                    </td>
                                    <td class="text-right">Rp. <?=number_format($value->hpp,2,",",".")?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['alokasi-resep'])){?>
                                          <a href="{{ url($main_url.'/manage/'.@$data->id_transaction.'/pemenuhan/'.$datasub->id_naikan.'/dopemenuhan/'.$value->id_unit) }}" class="btn btn-primary dim btn-xs"> gunakan <i class="fa fa-angle-right"></i></a>
                                      <?php }?>
                                    </td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['alokasi-resep'])){?>
                                          <input type="checkbox" class="ctrl_bulk" name="cb_<?=$value->id_unit?>" value="cb_<?=$value->id_unit?>" <?=($penuhan_kekurangan>0)?"checked":""?>>
                                      <?php }?>
                                    </td>
                                  </tr>
                                  <?php
                                  $penuhan_kekurangan--;
                                }
                              }
                            ?>
                            <tr>
                              <td colspan="8"></td>
                              <td>
                                <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['alokasi-resep'])){?>
                                <button type="submit" name="btn_bulk"  class="btn btn-info">Lakukan Bulk</button>
                                <?php }?>
                              </td>
                            </tr>
                          </tbody>
                          </tfoot>
                        </table>

                      </div>
                    </div>
                    {!! Form::close() !!}
                    <br>
                    <br>
                    <?php //} ?>
                    <div class="ibox-title">
                        <h5>Item Terpilih/Disiapkan untuk Memenuhi  <?=$datasub->naikan_rumus_name?> [<?=$datasub->naikan_rumus_code?>]</h5>
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
