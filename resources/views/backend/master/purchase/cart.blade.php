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
                <h2>Kelola Pembelian</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Pembelian</a>
                    </li>
                    <li class="active">
                        <strong>Kelola Pembelian</strong>
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
                        <h5>Detail Pembelian</h5>
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
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Pembelian</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->purchase_code?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Keterangan Pembelian</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::textarea('purchase_title', null, ['class' => 'form-control disabled', 'disabled'=> 'disabled','rows' => 3]) !!}
                                      {!! $errors->first('purchase_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}">
                                <label class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled value="<?=$statuses[$data->status]?>">
                                </div>
                              </div>

                              <?php if($data->status=="received"){ ?>
                                <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}">
                                  <label class="col-sm-4 control-label">Penerima</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" disabled value="<?=($data->receiver_name!="")?$data->receiver_name:"belum diterima"?>">
                                  </div>
                                </div>

                                <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}">
                                  <label class="col-sm-4 control-label">Waktu diterima</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->time_receive)?>">
                                  </div>
                                </div>
                              <?php } ?>

                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Tanggal Transaksi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('purchase_date', null, ['class' => 'form-control disabled', 'disabled'=> 'disabled']) !!}
                                      {!! $errors->first('purchase_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>

                              <div class="form-group {{ ($errors->has('tipe_pembayaran')?"has-error":"") }}"><label class="col-sm-4 control-label">Tipe Pembayaran</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('tipe_pembayaran', null, ['class' => 'form-control disabled', 'disabled'=> 'disabled']) !!}
                                      {!! $errors->first('tipe_pembayaran', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>

                    <div class="ibox-title">
                        <h5>Item Pembelian</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create']) && $data->status!="received"){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="{{ url('master/material') }}">
                                <i class="fa fa-plus"></i> <i class="fa fa-cube"></i> Tambah Item Bahan Baku
                            </a>
                        
                            <a class="btn btn-primary btn-sm" href="{{ url('master/package') }}">
                                <i class="fa fa-plus"></i> <i class="fa fa-cubes"></i> Tambah Item Paket
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
                                <th>Kode Bahan</th>
                                <th>Nama Bahan</th>
                                <th>Waktu Penambahan</th>
                                <th>Harga Satuan</th>
                                <th>Quantity</th>
                                <th>Satuan</th>
                                <?php if($data->status!="received"){ ?>
                                <th>Aksi</th>
                                <?php } ?>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;
                              $total   = 0;
                              if($items){
                                foreach ($items as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->material_code?></td>
                                    <td><?=$value->material_name?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td class="text-right">Rp. <?=number_format($value->item_price,2,",",".")?></td>
                                    <td class="text-right"><?=number_format($value->item_quantity,2,",",".")?> <?=$value->material_unit?></td>
                                    <?php
                                      $subtot = $value->item_price*$value->item_quantity;
                                      $total  = $total+$subtot;
                                    ?>
                                    <td class="text-right"><?=$value->material_unit?></td>
                                    <?php if($data->status!="received"){ ?>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a parent-id="{{ $data->id_purchase }}" data-id="{{ $value->id_purchase_detail }}" data-url="{{ url($main_url.'/remove/item/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                      <?php }?>
                                    </td>
                                    <?php } ?>
                                  </tr>
                                  <?php
                                }
                                
                                
                                ?>
                                  <tr>
                                    <td colspan="6"></td>
                                    <td class="text-right"><strong>Rp. <?=number_format($total,2,",",".")?></strong></td>
                                    <?php if($data->status!="received"){ ?>
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
                        
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Paket</th>
                                <th>Nama Paket</th>
                                <th>Waktu Penambahan</th>
                                <th>Harga Satuan</th>
                                <th>Quantity</th>
                                <th>Satuan</th>
                                <?php if($data->status!="received"){ ?>
                                <th>Aksi</th>
                                <?php } ?>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;
                              $total   = 0;
                              if($items_package){
                                foreach ($items_package as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->package_code?></td>
                                    <td><?=$value->package_name?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td class="text-right">Rp. <?=number_format($value->item_price,2,",",".")?></td>
                                    <td class="text-right"><?=number_format($value->item_quantity,2,",",".")?> <?=$value->material_unit?></td>
                                    <?php
                                      $subtot = $value->item_price*$value->item_quantity;
                                      $total  = $total+$subtot;
                                    ?>
                                    <td class="text-right"><?=$value->package_unit?></td>
                                    <?php if($data->status!="received"){ ?>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a parent-id="{{ $data->id_purchase }}" data-id="{{ $value->id_purchase_detail }}" data-url="{{ url($main_url.'/remove/item/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                      <?php }?>
                                    </td>
                                    <?php } ?>
                                  </tr>
                                  <?php
                                }
                                
                                
                                ?>
                                  <tr>
                                    <td colspan="6"></td>
                                    <td class="text-right"><strong>Rp. <?=number_format($total,2,",",".")?></strong></td>
                                    <?php if($data->status!="received"){ ?>
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
                    <div class="row">
                      <div class="form-group top15">
                          <div class="col-sm-6">
                              <a class="btn btn-white" href="{{ url($main_url) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">
                            <?php if($data->status!="received"){ ?>
                            <button class="btn btn-danger confirm" data-id="{{ $data->id_purchase }}" data-url="{{ url($config['main_url'].'/remove') }}" type="submit">Batalkan Pembelian</button>
                            <a class="btn btn-primary" href="{{ url($config['main_url'].'/manage/'.$data->id_purchase) }}">Lakukan Pembelian</a>
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
