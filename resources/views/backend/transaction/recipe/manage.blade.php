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
                        <strong>Kelola Transaksi</strong>
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

                    <div class="ibox-title">
                        <h5>Item Permintaan</h5>
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
                    <div class="ibox-title">
                        <h5>Item Resep</h5>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Rumus</th>
                                <th>Nama Rumus</th>
                                <th>Waktu Penambahan</th>
                                <th>Quantity</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $total_quantity = 0;
                              $counter = 0;
                              $total   = 0;
                              if($naikan){
                                foreach ($naikan as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->naikan_rumus_code?></td>
                                    <td><?=$value->naikan_rumus_name?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td class="text-right"><?=number_format($value->qty,0,",",".")?></td>
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
                              <a class="btn btn-white" href="{{ url($main_url) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">

                            <?php if($data->status=="pre-production"){?>

                            <button class="btn btn-danger confirm" data-id="{{ $data->id_transaction }}" data-url="{{ url($config['main_url'].'/remove/trx') }}" type="button">Batalkan Transaksi</button>

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
