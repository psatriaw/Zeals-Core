<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<?php
  $main_url = $config['main_url'];
  $methods  = $config;
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Detail Supplier</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Supplier</a>
                    </li>
                    <li class="active">
                        <strong>Detail Supplier</strong>
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
                          <h5>Detail Bahan Baku</h5>
                      </div>
                      <div class="ibox-content">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                        @endif
                        @include('backend.flash_message')
                        {!! Form::model($data,['url' => url($main_url.'/update/'.$data->id_material), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="form-group {{ ($errors->has('supplier_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Supplier</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('supplier_name', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('supplier_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('phone')?"has-error":"") }}"><label class="col-sm-2 control-label">No. Telp</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('phone', null, ['class' => 'form-control thetarget','rows' => '3']) !!}
                                {!! $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('email')?"has-error":"") }}"><label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('address')?"has-error":"") }}"><label class="col-sm-2 control-label">Alamat</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::textarea('address', null, ['class' => 'form-control','rows' => 3]) !!}
                                {!! $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'], null, ['class' => 'form-control']) !!}
                              {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                          <div class="form-group">
                              <div class="col-sm-4 col-sm-offset-2">
                                  <a class="btn btn-white" href="{{ url($main_url) }}">
                                      <i class="fa fa-angle-left"></i> kembali
                                  </a>

                                  <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                      <a href="{{ url($main_url.'/edit/'.$data->id_supplier) }}" class="btn btn-primary"><i class="fa fa-paste"></i> ubah</a>
                                  <?php }?>
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
