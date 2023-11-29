<?php
$main_url = $config['main_url'];
$linktype = [
    "1" => "None",
    "2" => "Internal",
    "3" => "Eksternal"
];

?>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Banner Detail</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Banner</a>
                    </li>
                    <li class="active">
                        <strong>Detail Banner</strong>
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
                            <h5>Detail Banner</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::model($data,['url' => url('admin/user/update/'.$data->id_user), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Waktu Terdaftar</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->time_created) ?>">
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Pembaruan Terakhir</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->last_update) ?>">
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Judul</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('title', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Gambar</label>
                                <div class="col-sm-4 col-xs-12">
                                    <img class="img-fluid" style="height: 300px" src="<?= url(str_replace("public", "", $data->banner_path)) ?>" alt="">
                                    {!! $errors->first('banner_path', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Deskripsi</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('description', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('status', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Link</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('link', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('link', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Tipe Link </label>
                                <div class="col-sm-4 col-xs-12">
                                    <!-- {!! Form::text('link_type', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!} -->
                                    <input type="text" class="form-control disabled" disabled readonly value="<?= $linktype[$data->link_type]; ?>">
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

                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
