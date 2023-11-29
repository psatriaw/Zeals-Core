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
                        <strong>Detail Produk</strong>
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
                          <h5>Detail Produk</h5>
                      </div>
                      <div class="ibox-content">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                        @endif
                        @include('backend.flash_message')
                        {!! Form::model($data,['url' => url('admin/product-uronshop/update/'.$data->id_product), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                          <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Waktu Terdaftar</label>
                              <div class="col-sm-4 col-xs-12">
                                  <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->time_created)?>">
                              </div>
                          </div>

                          <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Pembaruan Terakhir</label>
                              <div class="col-sm-4 col-xs-12">
                                  <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->last_update)?>">
                              </div>
                          </div>

                          <div class="form-group {{ ($errors->has('product_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Produk</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('product_name', null, ['class' => 'form-control disabled','disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                                  {!! $errors->first('product_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>

                          <div class="form-group {{ ($errors->has('price')?"has-error":"") }}"><label class="col-sm-2 control-label">Harga</label>
                              <div class="col-sm-4 col-xs-12">
                                <div class="input-group">
                                  <span class="input-group-addon">Rp. </span>
                                  {!! Form::number('price', null, ['class' => 'form-control disabled','disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                                </div>
                                  {!! $errors->first('price', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>

                          <div class="form-group {{ ($errors->has('price_gojek')?"has-error":"") }}"><label class="col-sm-2 control-label">Harga Gojek</label>
                              <div class="col-sm-4 col-xs-12">
                                <div class="input-group">
                                  <span class="input-group-addon">Rp. </span>
                                  {!! Form::number('price_gojek', null, ['class' => 'form-control disabled','disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                                </div>
                                  {!! $errors->first('price_gojek', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>

                          <div class="form-group {{ ($errors->has('price_grab')?"has-error":"") }}"><label class="col-sm-2 control-label">Harga Grab</label>
                              <div class="col-sm-4 col-xs-12">
                                <div class="input-group">
                                  <span class="input-group-addon">Rp. </span>
                                  {!! Form::number('price_grab', null, ['class' => 'form-control disabled','disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                                </div>
                                  {!! $errors->first('price_grab', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>

                          <div class="form-group {{ ($errors->has('discount')?"has-error":"") }}"><label class="col-sm-2 control-label">Diskon</label>
                              <div class="col-sm-2 col-xs-12">
                                  <div class="input-group">
                                    <span class="input-group-addon">Rp. </span>
                                    {!! Form::number('discount', null, ['class' => 'form-control disabled','disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                                  </div>
                                  {!! $errors->first('discount', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>

                          <div class="form-group {{ ($errors->has('product_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Produk</label>
                              <div class="col-sm-2 col-xs-12">
                                  {!! Form::text('product_code', null, ['class' => 'form-control disabled','disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                                  {!! $errors->first('product_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <!--
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('id_product_category')?"has-error":"") }}"><label class="col-sm-2 control-label">Kategori</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::select('id_product_category', $categories, null, ['class' => 'form-control disabled','disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                                  {!! $errors->first('id_product_category', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('product_type')?"has-error":"") }}"><label class="col-sm-2 control-label">Tipe Produk</label>
                              <div class="col-sm-2 col-xs-12">
                                  {!! Form::select('product_type', array("fix" => "Fix","custom" => "Custom"), null, ['class' => 'form-control disabled','disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                                  {!! $errors->first('product_type', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          -->

                          <div class="form-group {{ ($errors->has('description')?"has-error":"") }}"><label class="col-sm-2 control-label">Deskripsi Produk</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::textarea('description', null, ['class' => 'form-control disabled','disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                                  {!! $errors->first('description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>

                          <div class="form-group">
                              <div class="col-sm-4 col-sm-offset-2">
                                  <a class="btn btn-white" href="{{ url($main_url) }}">
                                      <i class="fa fa-angle-left"></i> kembali
                                  </a>
                              </div>
                          </div>
                        {!! Form::close() !!}
                      </div>
                  </div>

                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h5>Tabel Data Bahan Baku Non Siap Pakai Produk "<?=$data->product_name?>"</h5>
                      </div>
                      <div class="ibox-content">
                        @include('backend.flash_message')
                        <div class="alert alert-info">
                          Bahan baku berikut adalah untuk produksi setiap 1 unit/satuan produk.
                        </div>
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                            <thead>
                              <tr>
                                  <th>No.</th>
                                  <th>Nama Bahan</th>
                                  <th>Kode</th>
                                  <!--<th>Kategori</th>-->
                                  <th>Quantity</th>
                                  <th>Unit</th>
                                  <th>Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $counter = 0;

                                $product_status = array('active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'available' =>'Tersedia','unavailable' => 'Tidak Tersedia');

                                if(@$list){
                                  foreach ($list as $key => $value) {
                                    $counter++;
                                    ?>
                                    <tr>
                                      <td><?=$counter?></td>
                                      <td><?=$value->material_name?></td>
                                      <td><?=$value->material_code?></td>
                                      <!--<td><?=$value->category_name?></td>-->
                                      <td><?=$value->qty?></td>
                                      <td><?=$value->material_unit?></td>
                                      <td style="width:100px;">
                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit']) && $previlege->isAllow($login->id_user,$login->id_department,$config['mrp'])){?>
                                            <a href="{{ url($main_url.'/mrp/'.$data->id_product.'/edit/'.$value->id_mrp) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                        <?php }?>

                                      </td>
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
                      <br><br>
                      <div class="ibox float-e-margins">
                          <div class="ibox-title">
                              <h5>Tabel Rumus BOM bahan siap pakai untuk produk "<?=$data->product_name?>"</h5>
                          </div>
                          <div class="ibox-content">
                            @include('backend.flash_message')
                            <div class="alert alert-info">
                              Perlu diketahui bahwa loyang dibawah ini merupakan susunan dari 1 produk.
                            </div>
                            <div class="table-responsive">
                              <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                                <thead>
                                  <tr>
                                      <th>No.</th>
                                      <th>Nama Loyang</th>
                                      <th>Kode</th>
                                      <th>Quantity</th>
                                      <th>Aksi</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    $counter = 0;

                                    $product_status = array('active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'available' =>'Tersedia','unavailable' => 'Tidak Tersedia');

                                    if(@$list_rumus){
                                      foreach ($list_rumus as $key => $value) {
                                        $counter++;
                                        ?>
                                        <tr>
                                          <td><?=$counter?></td>
                                          <td><?=$value->cetakan_name?></td>
                                          <td><?=$value->cetakan_code?></td>
                                          <td><?=$value->qty?></td>
                                          <td style="width:100px;">
                                            <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit']) && $previlege->isAllow($login->id_user,$login->id_department,$config['rumus'])){?>
                                                <a href="{{ url($main_url.'/rumus/'.$data->id_product.'/edit/'.$value->id_product_cetakan) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                            <?php }?>

                                          </td>
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
                      </div>
                  </div>
                </div>
            </div>
        </div>
    @include('backend.footer')
  </div>
</div>
