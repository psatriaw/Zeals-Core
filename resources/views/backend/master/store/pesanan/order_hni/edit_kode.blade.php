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
                <h2>Transfer</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Edit</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Detail Datang</strong>
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
                        <h5>Ubah Detail Datang</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($unit_kode,['url' => url($main_url.'/update_admin/'.$unit_kode->id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('transfer')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Pesanan</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('id', null, ['class' => 'form-control','readonly'=>'readonly']) !!}
                                {!! $errors->first('tanggal', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('toko_id')?"has-error":"") }}"><label class="col-sm-2 control-label">Tempat Ambil</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('toko_id', null,['class' => 'form-control','readonly'=>'readonly']) !!}
                                {!! $errors->first('toko_id', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group {{ ($errors->has('nama_pemesan')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Pemesan</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('nama_pemesan', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('nama_pemesan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('id_member')?"has-error":"") }}"><label class="col-sm-2 control-label">ID Member</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::number('id_member', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('id_member', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('tanggal_ambil')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal Ambil</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::date('tanggal_ambil', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('tanggal_ambil', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('dp')?"has-error":"") }}"><label class="col-sm-2 control-label">DP</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::number('dp', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('dp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>                        
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ route('order_hni.index') }}">
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
