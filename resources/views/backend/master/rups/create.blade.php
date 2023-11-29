<?php
  $main_url = $config['main_url'];
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>RUPS Penerbit</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">RUPS Penerbit</a>
                    </li>
                    <li class="active">
                        <strong>Tambah RUPS Penerbit</strong>
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
                            <h5>Tambah RUPS Penerbit</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}

                            <div class="form-group {{ ($errors->has('id_penerbit')?"has-error":"") }}"><label class="col-sm-2 control-label">Penerbit</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::select('id_penerbit', $penerbits,null, ['class' => 'form-control','id' => 'id_penerbit']) !!}
                                    {!! $errors->first('id_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ ($errors->has('id_campaign')?"has-error":"") }}"><label class="col-sm-2 control-label">Campaign/Penawaran</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::select('id_campaign', [] ,null, ['class' => 'form-control','id' => 'id_campaign']) !!}
                                    {!! $errors->first('id_campaign', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('tanggal_rups')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal RUPS</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('tanggal_rups', null, ['class' => 'form-control','id' => 'tanggal_rups']) !!}
                                    {!! $errors->first('tanggal_rups', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('agenda')?"has-error":"") }}"><label class="col-sm-2 control-label">Agenda</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::textarea('agenda', null, ['class' => 'form-control', 'rows' => 8]) !!}
                                    {!! $errors->first('agenda', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('notif_email')?"has-error":"") }}"><label class="col-sm-2 control-label">Notifikasi Email</label>
                                <div class="col-sm-4 col-xs-12" style="padding-top:7px;">
                                    {!! Form::checkbox('notif_email', 'yes') !!}
                                    <lable for="notif_email"> Kirimkan notifikasi email ke seluruh pemodal pada campaign</lable>
                                    {!! $errors->first('notif_email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('notif_push')?"has-error":"") }}"><label class="col-sm-2 control-label">Notifikasi Push (Apps)</label>
                                <div class="col-sm-4 col-xs-12" style="padding-top:7px;">
                                    {!! Form::checkbox('notif_push', 'yes') !!}
                                    <lable for="notif_push"> Kirimkan notifikasi Push ke seluruh Aplikasi pemodal pada campaign</lable>
                                    {!! $errors->first('notif_push', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
                                    <button class="btn btn-primary btn-rounded" type="submit">Simpan</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        @include('backend.footer')
    </div>
</div>
<script>
  $(document).ready(function() {
    $('#id_penerbit').select2();
  });

  $(document).ready(function() {
    $('#tanggal_rups').datepicker();
    $('#tanggal_rups').datepicker("option", "dateFormat", "yy-mm-dd");
    changePenerbit();
  });

  $("#id_penerbit").change(function(){
    changePenerbit();
  });

  function changePenerbit(){
    $("#id_campaign").select2({
      ajax: {
        url: '{{ url("get-list-campaign-by-penerbit") }}',
        dataType: 'json',
        data: function (params) {
          var id_penerbit = $("#id_penerbit").val();
          var query = {
            search: params.term,
            id_penerbit: id_penerbit,
            type: 'public'
          }
          // Query parameters will be ?search=[term]&type=public
          return query;
        }
      }
    });
  }
</script>
