


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <?php
    $main_url = $config['main_url'];
  ?>
  <div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
      <div class="row wrapper border-bottom white-bg page-heading">
              <div class="col-lg-10">
                  <h2>Detail Paket Bahan Baku</h2>
                  <ol class="breadcrumb">
                      <li>
                          <a href="{{ url('dashboard/view') }}">Dashboard</a>
                      </li>
                      <li>
                          <a href="{{ url($main_url) }}">Bahan Baku</a>
                      </li>
                      <li class="active">
                          <strong>Detail Paket Bahan Baku</strong>
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
                          <h5>Detail Paket Bahan Baku</h5>
                      </div>
                      <div class="ibox-content">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                        @endif
                        @include('backend.flash_message')
                        {!! Form::model($data,['url' => url($main_url.'/update/'.$data->id_material_package), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
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

                          <div class="form-group {{ ($errors->has('package_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Paket</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('package_name', null, ['class' => 'form-control','disabled' => true]) !!}
                                  {!! $errors->first('package_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="form-group {{ ($errors->has('stock')?"has-error":"") }}"><label class="col-sm-2 control-label">Stock</label>
                              <div class="col-sm-4 col-xs-12">
                                {!! Form::text('stock', null, ['class' => 'form-control','disabled' => true]) !!}
                                {!! $errors->first('stock', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="form-group {{ ($errors->has('package_unit')?"has-error":"") }}"><label class="col-sm-2 control-label">Satuan</label>
                              <div class="col-sm-4 col-xs-12">
                                {!! Form::text('package_unit', null, ['class' => 'form-control','disabled' => true]) !!}
                                {!! $errors->first('package_unit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>

                          <div class="hr-line-dashed"></div>
                          <div class="form-group">
                              <div class="col-sm-4 col-sm-offset-2">
                                  <a class="btn btn-white" href="{{ url($main_url) }}">
                                      <i class="fa fa-angle-left"></i> kembali
                                  </a>
                              </div>
                              <div class="col-sm-6 text-right">

                              </div>
                          </div>
                        {!! Form::close() !!}
                      </div>
                  </div>

                  <br><br>
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h5>Komposisi Paket Bahan Baku</h5>
                      </div>
                      <div class="ibox-content">
                        @include('backend.flash_message')
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                            <thead>
                              <tr>
                                  <th>No.</th>
                                  <th>Nama Bahan</th>
                                  <th>Quantity</th>
                                  <th>Satuan</th>
                                  <th>Tgl Daftar</th>
                                  <th>Status</th>
                                  <th>Update</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $bgcolors = array(
                                  "diterima"  => "success",
                                  "ditolak"   => "danger"
                                );


                                $counter = 0;


                                if($list){
                                  foreach ($list as $key => $value) {
                                    $counter++;
                                    ?>
                                    <tr>
                                      <td><?=$counter?></td>
                                      <td><?=$value->material_name?></td>
                                      <td><?=$value->quantity?></td>
                                      <td><?=$value->material_unit?></td>
                                      <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                      <td <?=($value->status=="active")?"class='text-info'":""?>><?=$value->status?> <?=($value->quantity<=$value->minimum_stock)?"<span class='text-danger'>minim</span>":""?></td>
                                      <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
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

                                  if($usage){
                                    foreach ($usage as $key => $value) {
                                      $counter++;
                                      ?>
                                      <tr>
                                        <td><?=$counter?></td>
                                        <td><?=$value->cart_date?></td>
                                        <td><?=$value->cart_code?></td>
                                        <td><?=$value->mitra_name?></td>
                                        <td><?=number_format($value->quantity,2,",",".")?></td>
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
                  </div>
                  </div>
              </div>
          </div>
      @include('backend.footer')
    </div>
  </div>
