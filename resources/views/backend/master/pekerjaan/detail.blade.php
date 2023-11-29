<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<?php
    $methods    = $config;
    $main_url   = $methods['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Detail Pekerjaan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Pekerjaan</a>
                    </li>
                    <li class="active">
                        <strong>Detail Pekerjaan</strong>
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
                        <h5>Detail Pekerjaan</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url($main_url.'/update/'.$data->id_pekerjaan), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal dibuat</label>
                            <div class="col-sm-4 col-xs-12">
                                <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->time_created)?>">
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Terakhir diubah</label>
                            <div class="col-sm-4 col-xs-12">
                                <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->last_update)?>">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('pekerjaan')?"has-error":"") }}"><label class="col-sm-2 control-label">Pekerjaan</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('pekerjaan', null, ['class' => 'form-control','readonly' => true]) !!}
                                {!! $errors->first('pekerjaan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('id_reff')?"has-error":"") }}"><label class="col-sm-2 control-label">Data Reff</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('id_reff', null, ['class' => 'form-control','readonly' => true]) !!}
                                {!! $errors->first('id_reff', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control','readonly' => true, 'disabled' => 'disabled']) !!}
                              {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url($main_url) }}">
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
<script>
  $(document).ready(function() {
    $('.thetarget, .thesender').select2({
      ajax: {
        url: '{{ url("get-list-user") }}',
        dataType: 'json',
        data: function (params) {
          var query = {
            search: params.term,
            type: 'public'
          }
          // Query parameters will be ?search=[term]&type=public
          return query;
        }
      }
    });
  });

</script>
