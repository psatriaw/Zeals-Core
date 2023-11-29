<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Banner</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Banner</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Banner</strong>
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
                        <h5>Ubah Banner</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url('master/banner/update/'.$data->id_banner), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('title')?"has-error":"") }}"><label class="col-sm-2 control-label">Judul</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('title', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('file_banner_update')?"has-error":"") }}"><label class="col-sm-2 control-label">Gambar</label>
                            <div class="col-sm-4 col-xs-12">
                                <img src="{{url(str_replace('public','',$data->banner_path))}}" style="max-height: 200px;margin-bottom:10px;" alt="">
                                {!! Form::file('file_banner_update', null) !!}
                                {!! $errors->first('file_banner_update', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('description')?"has-error":"") }}"><label class="col-sm-2 control-label">Deskripsi</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('description', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'], null, ['class' => 'form-control']) !!}
                              {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('link')?"has-error":"") }}"><label class="col-sm-2 control-label">Link</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::text('link', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('link', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('link_type')?"has-error":"") }}"><label class="col-sm-2 control-label">Tipe Link</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('link_type', ['1' => 'Tanpa Action', '2' => 'Path Internal', '3' => 'Link Eksternal'], null, ['class' => 'form-control']) !!}
                              {!! $errors->first('link_type', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
                              <button class="btn btn-primary btn-rounded" type="submit">Simpan Perubahan</button>
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
