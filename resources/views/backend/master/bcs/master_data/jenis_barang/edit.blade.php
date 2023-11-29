<?php
  $main_url = $config['main_url'];
  ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Jenis</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Jenis</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Jenis</strong>
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
                        <h5>Ubah Jenis</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url($main_url.'/update/'.$data->id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('id')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('id', null, ['class' => 'form-control', 'readonly']) !!}
                                {!! $errors->first('id', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('jenis')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Jenis</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('jenis', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('jenis', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('bcs_uom_id')?"has-error":"") }}"><label class="col-sm-2 control-label">Satuan</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('bcs_uom_id', $optuom, null, ['class' => 'form-control']) !!}
                                {!! $errors->first('bcs_uom_id', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('id_brand')?"has-error":"") }}"><label class="col-sm-2 control-label">Brand*)</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('id_brand[]', $optbrand, null, ['class' => 'form-control select2',"multiple" => "multiple",'required'=>'required']) !!}
                                <span class="help-block">*) untuk pengelompokan barang</span>
                                {!! $errors->first('id_brand', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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

<script>
$(document).ready(function() {
  $(".select2").select2();
});
</script>
