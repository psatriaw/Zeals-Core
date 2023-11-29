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
                <h2>Tambah Bahan Baku</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Bahan Baku</a>
                    </li>
                    <li class="active">
                        <strong>Tambah Bahan Baku</strong>
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
                        <h5>Tambah Bahan Baku</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('material_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Bahan Baku</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('material_name', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('material_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('material_price')?"has-error":"") }}"><label class="col-sm-2 control-label">Harga Satuan Bahan Baku</label>
                            <div class="col-sm-4 col-xs-12">
                              <div class="input-group">
                                <span class="input-group-addon">Rp.</span>
                                {!! Form::text('material_price', null, ['class' => 'form-control']) !!}
                              </div>
                              {!! $errors->first('material_price', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('material_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Bahan Baku</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('material_code', null, ['class' => 'form-control thetarget','rows' => '3']) !!}
                                {!! $errors->first('material_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('material_unit')?"has-error":"") }}"><label class="col-sm-2 control-label">Satuan</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::email('material_unit', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('material_unit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <!--
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('quantity')?"has-error":"") }}"><label class="col-sm-2 control-label">Quantity</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('quantity', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('quantity', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        -->
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('minimum_stock')?"has-error":"") }}"><label class="col-sm-2 control-label">Minimum Stok</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('minimum_stock', null, ['class' => 'form-control', 'id' => 'address', 'rows' => 2]) !!}
                                {!! $errors->first('minimum_stock', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'], null, ['class' => 'form-control']) !!}
                              {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('type_material')?"has-error":"") }}"><label class="col-sm-2 control-label">Tipe Bahan</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('type_material', ['mrp' => 'MRP', 'nonmrp' => 'Bukan MRP'], null, ['class' => 'form-control']) !!}
                              {!! $errors->first('type_material', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
                              <button class="btn btn-white" type="reset">Reset</button>
                              <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                </div>
                </div>
            </div>
        </div>
    @include('backend.footer')
  </div>
</div>
