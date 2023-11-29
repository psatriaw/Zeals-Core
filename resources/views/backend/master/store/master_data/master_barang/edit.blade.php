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
                <h2>Master_barang</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Master_barang</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Master_barang</strong>
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
                        <h5>Ubah Master_barang</h5>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url($main_url.'/update/'.$id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('nama')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Produk</label>
                            <div class="col-sm-4 col-xs-12">
                            {!! Form::hidden('toko_id', $id_toko, ['class' => 'form-control']) !!}
                                {!! Form::text('nama', null, ['class' => 'form-control']) !!} 
                                {!! Form::hidden('toko_harga[]', $id_toko, ['class' => 'form-control']) !!}
                                {!! $errors->first('nama', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('kategori')?"has-error":"") }}"><label class="col-sm-2 control-label">Kategori</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('kategori', $optkategori,null, ['class' => 'form-control']) !!}
                                {!! $errors->first('kategori', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('brand')?"has-error":"") }}"><label class="col-sm-2 control-label">Brand</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('brand', $optbrand,null, ['class' => 'form-control']) !!}
                                {!! $errors->first('brand', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('hpp')?"has-error":"") }}"><label class="col-sm-2 control-label">Hpp</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('hpp', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('hpp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('harga_outlet')?"has-error":"") }}"><label class="col-sm-2 control-label">Harga</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('harga_outlet', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('harga_outlet', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div> 
            <span>Harga Optional</span>
                        <div class="hr-line-dashed"></div> 
                        <div class="form-group {{ ($errors->has('harga_gofood')?"has-error":"") }}"><label class="col-sm-2 control-label">Harga Go Resto</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('harga_gofood',null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('harga_grab')?"has-error":"") }}"><label class="col-sm-2 control-label">Harga Grab</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('harga_grab',null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url('store/master_barang?toko='.$id_toko) }}">
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


