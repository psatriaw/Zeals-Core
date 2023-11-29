<?php
  $main_url    = $config['main_url'];
  $methods     = $config;

  $statuses = array(
    "pre-production"    => "pre-production [PERGUDANGAN]",
    "pre-inisiation"    => "pre-inisiation  [GUDANG PRODUKSI]",
    "production"        => "production  [OUTLET] ",
    "distribution"      => "distribusi  [PENGIRIMAN] ",
    "queue"             => "Pengecekan data pembayaran  [ANTRIAN]",
    "confirmed"         => "Sudah di konfirmasi outlet [Menunggu Pembayaran]",
    "accepted"          => "Pembayaran diterima [PERGUDANGAN]",
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

                    <?php if($data->type_order=="ppic"){ ?>

                    <div class="ibox-title">
                        <h5>Item Permintaan PPIC</h5>
                        <?php if($data->status=="pending"){?>
                          <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                          <div class="ibox-tools">
                              <a class="btn btn-primary btn-sm" href="{{ url($config['main_url'].'/'.$data->id_cart.'/additem') }}">
                                  <i class="fa fa-plus"></i> tambah item pembelian
                              </a>
                          </div>
                          <?php } ?>
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
                                <?php if($data->status=="pending"){?>
                                <th>Aksi</th>
                                <?php } ?>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $total_quantity = 0;
                              $counter = 0;
                              $total   = 0;
                              if($items){
                                foreach ($items as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->product_code?></td>
                                    <td><?=$value->product_name?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td class="text-right"><?=number_format($value->quantity,0,",",".")?></td>
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
                                  </tr>
                                  <?php
                                }
                                ?>
                                  <tr>
                                    <td colspan="4"></td>
                                    <td class="text-right"><strong><?=number_format($total_quantity,0,",",".")?></strong></td>
                                    <?php if($data->status=="pending"){?>
                                    <td></td>
                                    <?php } ?>
                                  </tr>
                                <?php
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
                    <br><br>

                    <?php }elseif($data->type_order=="outlet"){ ?>

                    <div class="ibox-title">
                        <h5>Item Permintaan</h5>
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

                            ?>
                          </tbody>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                    <br><br>
                    <?php } ?>

                    <div class="ibox-title">
                        <h5>Item Perencanaan Bahan Siap Pakai</h5>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Resep</th>
                                <th>Nama Resep</th>
                                <th>Quantity</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $total_quantity = 0;
                              $counter = 0;
                              $total   = 0;
                              if($planning){
                                foreach ($planning as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=@$value->naikan_rumus_code?></td>
                                    <td><?=@$value->naikan_rumus_name?></td>
                                    <td class="text-right"><?=number_format($value->total,0,",",".")?></td>
                                    <?php
                                      $total_quantity = $total_quantity + $value->total;
                                    ?>
                                  </tr>
                                  <?php
                                }
                                ?>
                                  <tr>
                                    <td colspan="3"></td>
                                    <td class="text-right"><strong><?=number_format($total_quantity,0,",",".")?></strong> items</td>
                                    <?php if($data->status=="pending"){?>
                                    <td></td>
                                    <?php } ?>
                                  </tr>
                                <?php
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
                    <br><br>
                    <div class="ibox-title">
                        <h5>Item Permintaan Bahan Baku ke Logistik</h5>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Bahan</th>
                                <th>Nama Bahan</th>
                                <th>Satuan</th>
                                <th>Kebutuhan</th>
                                <!--<th>Tersedia (BAG. RESEP)</th>-->
                                <th>Kekurangan</th>
                                <th>Kelola Sumber</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $total_quantity = 0;
                              $counter = 0;
                              $total   = 0;
                              if(@$permintaanbahan){
                                foreach ($permintaanbahan as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->material_code?></td>
                                    <td><?=$value->material_name?></td>
                                    <td><?=$value->material_unit?></td>
                                    <td class="text-right"><?=number_format($value->qty_total,2,",",".")?> </td>
                                    <!--
                                    <td class="text-right">
                                      <?php if(is_numeric($value->qty_moved)){ ?>
                                        <?=number_format(@$value->qty_moved,2,",",".")?>
                                      <?php }?>
                                    </td>
                                  -->
                                    <?php
                                      $kurang = $model_naikan->getNecessaryItemLeft($data->order_id,$value->id_material)
                                    ?>
                                    <?php //if(!is_numeric($value->qty_moved)){ ?>
                                      <td class="text-right <?=(@$kurang>0)?"bg-danger":""?>"><?=(($kurang<=0)?"terpenuhi":number_format(@$kurang,2,",","."))?> </td>
                                    <?php //}else{ ?>
                                    <?php //}?>
                                    <td>
                                      <?php if($data->type_order=="outlet"){ ?>
                                        <?php if(!is_numeric($value->qty_moved)){ ?>
                                          <a href="{{ url('master/logistik/permintaan/manage/'.$data->id_transaction.'/ambil-item/'.$value->id_material)}}" class="btn btn-xs btn-primary">kelola <i class="fa fa-angle-right"></i></a>
                                        <?php }else{ ?>
                                          Dipenuhi dari bahan siap pakai
                                        <?php } ?>
                                      <?php }else{ ?>
                                        <?php if($data->status!="pre-production"){?>
                                          <?php if(!is_numeric($value->qty_moved)){ ?>
                                            <a href="{{ url('master/logistik/permintaan/manage/'.$data->id_transaction.'/ambil-item/'.$value->id_material)}}" class="btn btn-xs btn-primary">kelola <i class="fa fa-angle-right"></i></a>
                                          <?php }else{ ?>
                                             Dipenuhi dari bahan siap pakai
                                          <?php } ?>
                                        <?php }else{ ?>
                                          <?php if(is_numeric($value->qty_moved)){ ?>
                                            <a href="{{ url('master/logistik/permintaan/manage/'.$data->id_transaction.'/ambil-item/'.$value->id_material)}}" class="btn btn-xs btn-primary">kelola <i class="fa fa-angle-right"></i></a>
                                          <?php }else{ ?>
                                             Dipenuhi dari bahan siap pakai
                                          <?php } ?>
                                        <?php }?>
                                      <?php }?>
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


                    <br><br>
                    <div class="ibox-title">
                        <h5>Item Pemenuhan Bahan Baku</h5>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Bahan</th>
                                <th>Nama Bahan</th>
                                <th>Source Pembelian</th>
                                <th>Total</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $total_quantity = 0;
                              $counter = 0;
                              $total   = 0;
                              if(@$pemenuhan){
                                foreach ($pemenuhan as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->material_code?></td>
                                    <td><?=$value->material_name?></td>
                                    <td><?=$value->purchase_code?></td>
                                    <td class="text-right"><?=number_format(@$value->movement_qty,2,",",".")?> <?=$value->material_unit?></td>
                                    <td>Rp. <?=number_format($value->item_price,2,",",".")?></td>
                                    <td>
                                      <?php
                                        $subtotal = $value->item_price * $value->movement_qty;
                                      ?>
                                      Rp. <?=number_format($subtotal)?>
                                    </td>
                                    <td>
                                      <?php if($data->status=="pre-production"){ ?>
                                      <a data-id="{{ $value->id_movement }}" parent-id="{{ $data->id_transaction }}" data-url="{{ url($main_url.'/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                      <?php }else{ ?>

                                      <?php } ?>
                                    </td>
                                  </tr>
                                  <?php
                                }
                              }else{
                                ?>
                                <tr>
                                  <td colspan="8">
                                    <div class="alert alert-danger">
                                        Tidak ada data pemenuhan yang sudah dilakukan, mohon melakukan pengelolaan pada section <strong>"Item Permintaan Bahan Baku ke Logistik"</strong>
                                    </div>
                                  </td>
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
                              <a class="btn btn-white" href="{{ url($main_url) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">

                            <?php if($data->status=="pre-production" || $data->status=="accepted"){?>

                            <button class="btn btn-info confirm" data-id="{{ $data->id_transaction }}" data-url="{{ url('master/logistik/permintaan/approve/') }}" type="button">Lanjutkan Ke Gudang Produksi <i class="fa fa-angle-right"></i></button>

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
