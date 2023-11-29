<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<?php
  $main_url = $config['main_url'];
  $methods  = $config;
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Detail Bahan Baku</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Bahan Baku</a>
                    </li>
                    <li class="active">
                        <strong>Detail Bahan Baku</strong>
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
                          <h5>Detail Bahan Baku</h5>
                      </div>
                      <div class="ibox-content">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                        @endif
                        @include('backend.flash_message')
                        {!! Form::model($data,['url' => url($main_url.'/update/'.$data->id_material), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                          <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal dibuat</label>
                              <div class="col-sm-4 col-xs-12">
                                  <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->time_created)?>">
                              </div>
                          </div>
                          <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Terakhir diubah</label>
                              <div class="col-sm-4 col-xs-12">
                                  <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->last_update)?>">
                              </div>
                          </div>
                          <div class="form-group {{ ($errors->has('material_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Bahan Baku</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('material_name', null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                  {!! $errors->first('material_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <!--
                          <div class="form-group {{ ($errors->has('material_price')?"has-error":"") }}"><label class="col-sm-2 control-label">Harga Satuan Bahan Baku</label>
                              <div class="col-sm-4 col-xs-12">
                                <div class="input-group">
                                  <span class="input-group-addon">Rp.</span>
                                  <input type="text" class="form-control disabled" disabled value="<?=number_format($data->material_price,0,",",".")?>">
                                </div>
                                  {!! $errors->first('material_price', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          -->
                          <div class="form-group {{ ($errors->has('material_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Bahan Baku</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('material_code', null, ['class' => 'form-control thetarget  disabled', 'disabled' => 'disabled','rows' => '3']) !!}
                                  {!! $errors->first('material_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="form-group {{ ($errors->has('material_unit')?"has-error":"") }}"><label class="col-sm-2 control-label">Satuan</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::email('material_unit', null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                  {!! $errors->first('material_unit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>

                          <div class="form-group {{ ($errors->has('stock')?"has-error":"") }}"><label class="col-sm-2 control-label">Quantity</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('stock', null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                  {!! $errors->first('stock', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>

                          <div class="form-group {{ ($errors->has('minimum_stock')?"has-error":"") }}"><label class="col-sm-2 control-label">Minimum Stok</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('minimum_stock', null, ['class' => 'form-control disabled', 'disabled' => 'disabled', 'id' => 'address', 'rows' => 2]) !!}
                                  {!! $errors->first('minimum_stock', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                              <div class="col-sm-4 col-xs-12">
                                {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'], null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="form-group {{ ($errors->has('type_material')?"has-error":"") }}"><label class="col-sm-2 control-label">Tipe Bahan</label>
                              <div class="col-sm-4 col-xs-12">
                                {!! Form::select('type_material', ['mrp' => 'MRP', 'nonmrp' => 'Bukan MRP'], null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                {!! $errors->first('type_material', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-sm-4 col-sm-offset-2">
                                  <a class="btn btn-white" href="{{ url($main_url) }}">
                                      <i class="fa fa-angle-left"></i> kembali
                                  </a>

                                  <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                      <a href="{{ url($main_url.'/edit/'.$data->id_material) }}" class="btn btn-primary"><i class="fa fa-paste"></i> ubah</a>
                                  <?php }?>
                              </div>
                          </div>
                        {!! Form::close() !!}
                      </div>

                      <?php //if($previlege->isAllow($login->id_user,$login->id_department,$config['material-cabang-view'])){?>
                        <br><br>
                        <div class="ibox-title">
                            <h5>Distribusi Stock</h5>
                        </div>
                        <div class="ibox-content">
                          <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                              <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Disribusi</th>
                                    <th>Kode Pembelian</th>
                                    <th>Quantity</th>
                                    <th>Digunakan</th>
                                    <th>Sisa</th>
                                    <th>Harga Satuan</th>
                                    <th>Tgl Daftar</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $bgcolors = array(
                                    "diterima"  => "success",
                                    "ditolak"   => "danger"
                                  );

                                  $counter = 0;
                                  if($page!=""){
                                    $counter = ($page-1)*$limit;
                                  }

                                  if($distribusi){
                                    foreach ($distribusi as $key => $value) {
                                      $counter++;
                                      $sisa = $value->quantity - $value->penggunaan;
                                      ?>
                                      <tr>
                                        <td><?=$counter?></td>
                                        <td><?=$value->distribution_date?></td>
                                        <td><?=$value->purchase_code?></td>
                                        <td><?=number_format($value->quantity,2,",",".")?></td>
                                        <td><?=number_format($value->penggunaan,2,",",".")?></td>
                                        <td><?=number_format($sisa,2,",",".")?></td>
                                        <td><?=number_format($value->item_price,2,",",".")?></td>
                                        <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
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
                            <?=$pagging?>
                          </div>
                        </div>

                      <?php //}else{ ?>
                        <br><br>
                        <div class="ibox-title">
                            <h5>Pembelian Stock</h5>
                        </div>
                        <div class="ibox-content">
                          <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                              <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Pembelian</th>
                                    <th>Kode Pembelian</th>
                                    <th>Quantity</th>
                                    <th>Terdistribusi</th>
                                    <th>Harga Satuan</th>
                                    <th>Tgl Daftar</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $bgcolors = array(
                                    "diterima"  => "success",
                                    "ditolak"   => "danger"
                                  );

                                  $counter = 0;
                                  if($page!=""){
                                    $counter = ($page-1)*$limit;
                                  }

                                  if($pembelian){
                                    foreach ($pembelian as $key => $value) {
                                      $counter++;
                                      ?>
                                      <tr>
                                        <td><?=$counter?></td>
                                        <td><?=$value->purchase_date?></td>
                                        <td><?=$value->purchase_code?></td>
                                        <td><?=number_format($value->item_quantity,2,",",".")?></td>
                                        <td><?=number_format($value->distibuted,2,",",".")?></td>
                                        <td><?=number_format($value->item_price,2,",",".")?></td>
                                        <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
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
                            <?=$pagging?>
                          </div>
                        </div>
                        
                        <br><br>
                        <div class="ibox-title">
                            <h5>Penjualan Outlet [dikirim ke outlet]</h5>
                        </div>
                        <div class="ibox-content">
                          <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                              <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Permintaan</th>
                                    <th>Kode Permintaan</th>
                                    <th>Outlet</th>
                                    <th>Quantity [Disetujui]</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $bgcolors = array(
                                    "diterima"  => "success",
                                    "ditolak"   => "danger"
                                  );

                                  $counter = 0;
                                  if($page!=""){
                                    $counter = ($page-1)*$limit;
                                  }

                                  if($penjualan){
                                    foreach ($penjualan as $key => $value) {
                                      $counter++;
                                      ?>
                                      <tr>
                                        <td><?=$counter?></td>
                                        <td><?=$value->cart_date?></td>
                                        <td><?=$value->cart_code?></td>
                                        <td><?=$value->mitra_name?></td>
                                        <td><?=number_format($value->disetujui_quantity,2,",",".")?></td>
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
                            <?=$pagging?>
                          </div>
                        </div>
                    <?php // } ?>

                  </div>
                </div>
            </div>
        </div>
    @include('backend.footer')
  </div>
</div>
