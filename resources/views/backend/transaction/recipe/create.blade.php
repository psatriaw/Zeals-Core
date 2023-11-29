<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Transaksi / Order</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Permintaan</a>
                    </li>
                    <li class="active">
                        <strong>Tambah Permintaan</strong>
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
                        <h5>Tambah Permintaan</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                      <!--
                      <div class="form-group {{ ($errors->has('transaction_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Transaksi</label>
                          <div class="col-sm-4 col-xs-12">
                              <input type="text" name="transaction_code" value="<?=$transaction_code?>" class="form-control disabled" disabled>
                              {!! $errors->first('product_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>
                      -->
                      <div class="alert alert-info">
                          Anda sedang membuat keranjang transaksi, data tidak akan masuk dalam transaksi sebelum keranjang checkout.
                      </div>
                      <div class="hr-line-dashed"></div>
                      <div class="form-group {{ ($errors->has('order_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Order</label>
                          <div class="col-sm-4 col-xs-12">
                              <input type="text" name="order_code" value="<?=$order_code?>" class="form-control" readonly>
                              {!! $errors->first('order_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>
                      <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('description')?"has-error":"") }}"><label class="col-sm-2 control-label">Deskripsi</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::textarea('description',null, ['class' => 'form-control thetarget','rows' => 3]) !!}
                                {!! $errors->first('description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('cart_date')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal Transaksi</label>
                            <div class="col-sm-3 col-xs-12">
                                {!! Form::text('cart_date', null, ['class' => 'form-control thetarget','id' => "production_date"]) !!}
                                {!! $errors->first('cart_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
                              <button class="btn btn-primary" type="submit">Buat Keranjang Transaksi</button>
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
    $('#production_date').datepicker();
    $('#production_date').datepicker("option", "dateFormat", "yy-mm-dd");
  });
</script>
