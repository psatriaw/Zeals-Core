<link href="<?=url("public/templates/plugin/jquery-ui.css")?>" rel="stylesheet">
<link href="<?=url("public/templates/plugin/datepicker/css/bootstrap-datepicker.css")?>" rel="stylesheet">
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Diskon</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/discount') }}">Layanan</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Diskon</strong>
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
                        <h5>Tambah Kategori</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data, ['url' => url('admin/discount/update/'.$data->id_discount), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('discount_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Diskon</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('discount_code', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('discount_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                <p class="help-block">*) Harus unik/belum digunakan sebelumnya</p>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('description')?"has-error":"") }}"><label class="col-sm-2 control-label">Deskripsi</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::textarea('description', null, ['class' => 'form-control','rows' => '3']) !!}
                                {!! $errors->first('description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('time_expired')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal Berakhir Diskon</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('time_expired', null, ['class' => 'form-control datepicker']) !!}
                                {!! $errors->first('time_expired', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url('admin/discount') }}">
                                    <i class="fa fa-angle-left"></i> kembali
                                </a>
                            </div>
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">Reset</button>
                              <button class="btn btn-primary" type="submit">Perbarui</button>
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
<script type="text/javascript" src="{{ url("public/templates/plugin/datepicker/js/bootstrap-datepicker.js") }}" charset="UTF-8"></script>
<script>
  $(function () {
      $('.datepicker').datepicker({
        defaultDate: "<?=$data->time_expired?>",
        pickTime: false,
        autoclose: true
      });
  });
</script>
