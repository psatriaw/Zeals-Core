<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
  $main_url = $config["main_url"];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Ubah Pembelian</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Pembelian</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Pembelian</strong>
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
                        <h5>Ubah Pembelian</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($detail,['url' => url($main_url.'/updatepurchase/'.$detail->id_purchase), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Pembelian</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('purchase_code', null, ['class' => 'form-control','readonly' => true]) !!}
                                {!! $errors->first('purchase_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-2 control-label">Keterangan Pembelian</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::textarea('purchase_title', null, ['class' => 'form-control','rows' => 3]) !!}
                                {!! $errors->first('purchase_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal Pembelian</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('purchase_date', null, ['class' => 'form-control','rows' => 3,'id' => 'production_date']) !!}
                                {!! $errors->first('purchase_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('tipe_pembayaran')?"has-error":"") }}"><label class="col-sm-2 control-label">Tipe Pembayaran</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('tipe_pembayaran', array('lunas' => 'Lunas', 'hutang' => 'Hutang'), null,['class' => 'form-control','id' => 'tipe_pembayaran']) !!}
                                {!! $errors->first('tipe_pembayaran', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('id_supplier')?"has-error":"") }}"><label class="col-sm-2 control-label">Supplier</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('id_supplier', $option_supplier, null,['class' => 'form-control','id' => 'id_supplier']) !!}
                                {!! $errors->first('id_supplier', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
    $('#production_date').datepicker("setDate",new Date(<?=$detail->purchase_date?>));
    $('#production_date').datepicker("option", "dateFormat", "yy-mm-dd");
  });
</script>
