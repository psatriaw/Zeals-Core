<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
  $main_url    = $config['main_url'];
  $methods     = $config;
  $total_grand = 0;

  if($items){
    foreach ($items as $key => $value) {
      $subtotal = $value->quantity * ($value->price_outlet - $value->price_outlet_discount);
      $total_grand = $total_grand + $subtotal;
    }
  }
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Konfirmasi Pembayaran</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Transaksi</a>
                    </li>
                    <li class="active">
                        <strong>Konfirmasi Pembayaran</strong>
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
                        <h5>Detail Keranjang</h5>
                    </div>
                    <div class="ibox-content bottom30">
                      {!! Form::model($data,['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}">
                                  <label class="col-sm-4 control-label">Tanggal dibuat</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->time_created)?>">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Terakhir diubah</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->last_update)?>">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Order</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->cart_code?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Outlet</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->mitra_name?> [<?=$data->mitra_code?>] a/n <?=$data->first_name?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Besar Tagihan</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="Rp. <?=number_format($total_grand,0,",",".")?>" name="purchase_code">
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Transaksi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->transaction_code?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Tanggal Transaksi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->cart_date?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Status</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->status?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Creator</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->admin_name?>" name="purchase_code">
                                  </div>
                              </div>
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            Ada kesalahan! mohon cek formulir.
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        </div>
                    @endif
                    @include('backend.flash_message')

                    {!! Form::model($data,['url' => url($main_url.'/doconfirmpayment/'.$data->id_transaction), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                    <div class="ibox-title">
                        <h5>Detail Konfirmasi</h5>
                    </div>
                    <div class="ibox-content bottom30">
                        <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('mc_date')?"has-error":"") }}">
                                  <label class="col-sm-4 control-label">Tanggal Konfirmasi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::hidden('id', $data->id_transaction, ['class' => 'form-control']) !!}
                                      {!! Form::text('mc_date', null, ['class' => 'form-control thetarget']) !!}
                                      {!! $errors->first('mc_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_target')?"has-error":"") }}"><label class="col-sm-4 control-label">Tujuan Transfer</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_target', $tujuan_konfirmasi, ['class' => 'form-control','readonly']) !!}
                                      {!! $errors->first('mc_bank_target', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_total_amount')?"has-error":"") }}"><label class="col-sm-4 control-label">Nominal Transfer</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::number('mc_total_amount', null, ['class' => 'form-control']) !!}
                                      {!! $errors->first('mc_total_amount', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Bank</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_name', null, ['class' => 'form-control']) !!}
                                      {!! $errors->first('mc_bank_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_account_number')?"has-error":"") }}"><label class="col-sm-4 control-label">Nomor Rekening</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_account_number', null, ['class' => 'form-control']) !!}
                                      {!! $errors->first('mc_bank_account_number', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_account')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Pada Rekening</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_account', null, ['class' => 'form-control']) !!}
                                      {!! $errors->first('mc_bank_account', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="alert alert-warning">
                                <h3>Peringatan</h3>
                                <p>Permintaan belum diproses hingga pembayaran diterima oleh administrator.</p>
                              </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                      <div class="form-group top15">
                          <div class="col-sm-6">
                              <a class="btn btn-white" href="{{ url($main_url."/manage/".$data->id_transaction) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">
                            <button class="btn btn-info" type="submit">Simpan</button>
                          </div>
                      </div>
                    </div>

                    {!! Form::close() !!}
                </div>
                </div>
            </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>
<script>
  $(document).ready(function() {
    $('.thetarget').datepicker();
    $('.thetarget').datepicker("option", "dateFormat", "yy-mm-dd");
  });
</script>
