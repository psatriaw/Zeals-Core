<?php
  $main_url    = $config['main_url'];
  $methods     = $config;
  $production_cost = 0;
  $production_permintaan = 0;

  $statuses = array(
    "pre-production"    => "penyiapan bahan baku [PROSES LOGISTIK]",
    "pre-inisiation"    => "penyiapan bahan baku & pengiriman  [PROSES LOGISTIK]",
    "production"        => "production  [OUTLET] ",
    "confirmed"         => "dikonfirmasi outlet  [OUTLET] ",
    "accepted"          => "Pembayaran disetujui finance  [PROSES LOGISTIK] ",
    "distribution"      => "Distribusi  [PENGIRIMAN] ",
    "queue"             => "Pengecekan data pembayaran  [ANTRIAN]",
    "post-production"   => "post-production [OUTLET]",
    "done"              => "Selesai",
  );
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kelola Permintaan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Permintaan</a>
                    </li>
                    <li class="active">
                        <strong>Kelola Permintaan</strong>
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
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Tanggal Transaksi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->cart_date?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Status</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$statuses[$data->status]?>" name="purchase_code">
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

                    <div class="ibox-title">
                        <h5>Item Permintaan <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['produksi'])){?>/Hasil Produksi<?php } ?></h5>
                        <?php if($data->status=="pending"){?>
                          <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                          <div class="ibox-tools">
                              <a class="btn btn-primary btn-sm" href="{{ url($config['main_url'].'/'.$data->id_cart.'/additem') }}">
                                  <i class="fa fa-plus"></i> tambah item pembelian
                              </a>
                          </div>
                          <?php } ?>
                        <?php } ?>

                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['produksi']) && $data->status=="production"){?>
                          <div class="ibox-tools">
                              <a class="btn btn-primary btn-sm" href="{{ url($config['main_url'].'/'.$data->id_cart.'/addproduksi/'.$data->id_transaction) }}">
                                  <i class="fa fa-plus"></i> tambah hasil produksi
                              </a>

                              <a class="btn btn-danger btn-sm text-white" href="{{ url($config['main_url'].'/'.$data->id_cart.'/addreject/'.$data->id_transaction) }}">
                                  <i class="fa fa-plus"></i> reject produksi
                              </a>
                          </div>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Waktu Penambahan</th>
                                <th>Quantity</th>
                                <th>Harga Produk</th>
                                <th>Harga Potongan</th>
                                <th>Sub Total</th>
                                <?php if($data->status=="pending"){?>
                                <th>Aksi</th>
                                <?php } ?>

                                <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['produksi'])){?>
                                <th>Hasil Produksi</th>
                                <th>Reject</th>
                                <th>Biaya Produk</th>
                                <?php } ?>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $total_reject = 0;
                              $total_biaya = 0;
                              $total_pemenuhan = 0;
                              $total_quantity = 0;
                              $counter = 0;
                              $total   = 0;
                              $total_grand = 0;
                              if($items){
                                foreach ($items as $key => $value) {
                                  $counter++;
                                  $subtotal = $value->quantity * ($value->price_outlet - $value->price_outlet_discount);
                                  $total_grand = $total_grand + $subtotal;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->product_code?></td>
                                    <td><?=$value->product_name?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td  style="width:150px;" class="text-right"><?=number_format($value->quantity,0,",",".")?></td>
                                    <td  style="width:150px;" class="text-right"><?=number_format($value->price_outlet,0,",",".")?></td>
                                    <td  style="width:150px;" class="text-right"><?=number_format($value->price_outlet_discount,0,",",".")?></td>
                                    <td  style="width:150px;" class="text-right"><?=number_format($subtotal,0,",",".")?></td>
                                    <?php
                                      $total_quantity = $total_quantity + $value->quantity;
                                      $subtot = $value->item_price*$value->quantity;
                                      $total  = $total+$subtot;
                                    ?>
                                    <?php if($data->status=="pending"){?>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a parent-id="{{ $data->id_cart }}" data-id="{{ $value->id_cart_detail }}" data-url="{{ url($main_url.'/remove/item/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                      <?php }?>
                                    </td>
                                    <?php } ?>

                                    <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['produksi'])){ ?>
                                      <?php
                                        $total_pemenuhan = $total_pemenuhan + $value->pemenuhan_produksi;
                                        $biaya           = $value->biaya_hpp;
                                        $total_biaya     = $total_biaya + $biaya;
                                        $reject          = $productreject_model->countProductReject($value->id_product,$data->order_id);
                                        $total_reject    = $total_reject + $reject;

                                      ?>
                                      <td style="width:150px;" class="text-right"><?=number_format($value->pemenuhan_produksi,0)?></td>
                                      <td style="width:150px;" class="text-right"><?=number_format($reject,0)?></td>
                                      <td style="width:150px;" class="text-right"><?=number_format($biaya,2,",",".")?></td>
                                    <?php } ?>
                                  </tr>
                                  <?php
                                }
                                ?>
                                  <tr>
                                    <td colspan="4"></td>
                                    <td class="text-right"><strong><?=number_format($total_quantity,0,",",".")?></strong></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"><strong><?=number_format($total_grand,0,",",".")?></strong></td>
                                    <?php if($data->status=="pending"){?>
                                    <td></td>
                                    <?php } ?>

                                    <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['produksi'])){?>
                                      <td class="text-right"><strong><?=number_format($total_pemenuhan,0,",",".")?></strong></td>
                                      <td class="text-right"><strong><?=number_format($total_reject,0,",",".")?></strong></td>
                                      <td class="text-right"><strong><?=number_format($total_biaya,2,",",".")?></strong></td>
                                    <?php } ?>
                                  </tr>
                                <?php
                                $production_permintaan = $total_quantity;
                                $production_pemenuhan = $total_pemenuhan;
                              }else{
                                ?>
                                <tr>
                                  <td colspan="10">Tidak ada data</td>
                                </tr>
                                <?php
                              }
                              $production_cost = $production_cost + $total_biaya;
                            ?>
                          </tbody>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                    <br><br>
                    <?php //if($previlege->isAllow($login->id_user,$login->id_department,$methods['produksi']) ){?>

                      <div class="panel panel-default">
                        <div class="panel-heading">Resume Permintaan #<?=$data->transaction_code?></div>
                        <div class="panel-body">
                          <div class="row">
                              <div class="col-sm-4">
                                <div class="alert alert-info text-center">
                                  <strong>Total Permintaan</strong>
                                    <br>
                                    <h3><?=number_format(@$production_permintaan,0,",",".")?> <sub>Items</sub></h3>
                                </div>
                              </div>
                              <!--
                              <div class="col-sm-4">
                                <div class="alert alert-info text-center">
                                  <strong>Total Produksi</strong>
                                    <br>
                                    <h3><?=number_format(@$production_pemenuhan,2,",",".")?></h3>
                                </div>
                              </div>
                              -->
                              <div class="col-sm-4">
                                <div class="alert alert-info text-center">
                                    <strong>Total Biaya</strong>
                                      <br>
                                      <h3>Rp. <?=number_format($total_grand,2,",",".")?></h3>
                                </div>
                              </div>
                          </div>
                        </div>
                        <div class="panel-footer text-right">

                        </div>
                      </div>

                    <?php //} ?>
                    <?php if($data->mc_status!=""){ ?>
                    {!! Form::model($data,['url' => url($main_url.'/doconfirmpayment/'.$data->id_transaction), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                    <div class="ibox-title">
                        <h5>Detail Konfirmasi</h5>
                    </div>
                    <div class="ibox-content bottom30">
                        <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('mc_date')?"has-error":"") }}">
                                  <label class="col-sm-4 control-label">Tanggal Konfirmasi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::hidden('id', $data->id_transaction, ['class' => 'form-control','readonly']) !!}
                                      {!! Form::text('mc_date', null, ['class' => 'form-control thetarget','readonly']) !!}
                                      {!! $errors->first('mc_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_target')?"has-error":"") }}"><label class="col-sm-4 control-label">Tujuan Transfer</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_target', $tujuan_konfirmasi, ['class' => 'form-control','readonly']) !!}
                                      {!! $errors->first('mc_bank_target', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <?php
                                $data->mc_total_amount = "Rp. ".number_format($data->mc_total_amount,0,",",".");
                              ?>
                              <div class="form-group {{ ($errors->has('mc_total_amount')?"has-error":"") }}"><label class="col-sm-4 control-label">Nominal Transfer</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_total_amount', null, ['class' => 'form-control','readonly']) !!}
                                      {!! $errors->first('mc_total_amount', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Bank</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_name', null, ['class' => 'form-control','readonly']) !!}
                                      {!! $errors->first('mc_bank_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_account_number')?"has-error":"") }}"><label class="col-sm-4 control-label">Nomor Rekening</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_account_number', null, ['class' => 'form-control','readonly']) !!}
                                      {!! $errors->first('mc_bank_account_number', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_account')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Pada Rekening</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_account', null, ['class' => 'form-control','readonly']) !!}
                                      {!! $errors->first('mc_bank_account', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="alert alert-info">
                                <h3>Informasi</h3>
                                <p>Data konfirmasi pembayaran sedang dalam moderasi oleh bagian finance, mohon menunggu.</p>
                                <p>Setelah proses moderasi selesai dan konfirmasi pembayaran diterima, pesanan akan segera diproses</p>
                              </div>
                            </div>
                        </div>

                    </div>

                    {!! Form::close() !!}
                    <?php } ?>

                    <div class="row">
                      <div class="form-group top15">
                          <div class="col-sm-6">
                              <a class="btn btn-white" href="{{ url($main_url) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">

                            <?php if($data->status=="pre-production" && ($previlege->isAllow($login->id_user,$login->id_department,$methods['remove']))){ ?>
                              <button class="btn btn-danger confirm" data-id="{{ $data->id_transaction }}" data-url="{{ url($config['main_url'].'/remove/trx') }}" type="button">Batalkan Transaksi</button>
                            <?php } ?>

                            <?php if(($data->status=="pre-production" || $data->status=="confirmed") && ($previlege->isAllow($login->id_user,$login->id_department,$methods['remove']))){ ?>
                              <button class="btn btn-info confirm" data-id="{{ $data->id_transaction }}" data-url="{{ url($config['main_url'].'/confirm/trx') }}" type="button">Konfirmasi Pembayaran <i class="fa fa-angle-right"></i></button>
                            <?php } ?>

                            <?php if($data->status=="pre-inisiation" && $previlege->isAllow($login->id_user,$login->id_department,$methods['pemenuhan'])  && $previlege->isAllow($login->id_user,$login->id_department,$methods['kirim'])){?>
                              <button class="btn btn-info confirm" data-id="{{ $data->id_transaction }}" data-url="{{ url($config['main_url'].'/send') }}" type="button">Kirim ke Dapur <i class='fa fa-angle-right'></i></button>
                            <?php } ?>

                            <?php if($data->status=="production" && ($previlege->isAllow($login->id_user,$login->id_department,$methods['produksi']))){ ?>
                              <button class="btn btn-info confirm" data-id="{{ $data->id_transaction }}" data-url="{{ url($config['main_url'].'/done/trx') }}" type="button">Selesai Produksi <i class='fa fa-check'></i></button>
                            <?php } ?>
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
