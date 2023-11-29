<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Sektor Industri</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url('master/category') }}">Sektor Industri</a>
                    </li>
                    <li class="active">
                        <strong>Detail Sektor Industri</strong>
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
                            <h5>Detail Sektor Industri</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::model($data,['url' => url('master/category/update/'.$data->id_sektor_industri), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Waktu Terdaftar</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->date_created) ?>">
                                </div>
                            </div>
                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Pembaruan Terakhir</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->last_update) ?>">
                                </div>
                            </div>
                            <div class="form-group {{ ($errors->has('nama_sektor_industri')?"has-error":"") }}"><label class="col-sm-2 control-label">Sektor Industri</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('nama_sektor_industri', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('nama_sektor_industri', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url('master/category') }}">
                                        <i class="fa fa-angle-left"></i> kembali
                                    </a>
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
