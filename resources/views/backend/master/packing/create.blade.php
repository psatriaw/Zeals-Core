<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Produk</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Produk</a>
                    </li>
                    <li class="active">
                        <strong>Tambah Produk</strong>
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
                        <h5>Tambah Produk</h5>
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

                        <div class="form-group {{ ($errors->has('product_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Produk</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('product_name', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('product_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('price')?"has-error":"") }}"><label class="col-sm-2 control-label">Harga Jual</label>
                            <div class="col-sm-4 col-xs-12">
                              <div class="input-group">
                                <span class="input-group-addon">Rp. </span>
                                {!! Form::number('price', null, ['class' => 'form-control']) !!}
                              </div>
                                {!! $errors->first('price', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('discount')?"has-error":"") }}"><label class="col-sm-2 control-label">Diskon</label>
                            <div class="col-sm-2 col-xs-12">
                                <div class="input-group">
                                  <span class="input-group-addon">Rp. </span>
                                  {!! Form::number('discount', null, ['class' => 'form-control']) !!}
                                </div>
                                {!! $errors->first('discount', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('product_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Produk</label>
                            <div class="col-sm-2 col-xs-12">
                                {!! Form::text('product_code', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('product_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                <span class="help-block">Harus unik/belum pernah digunakan</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('description')?"has-error":"") }}"><label class="col-sm-2 control-label">Deskripsi Produk</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
