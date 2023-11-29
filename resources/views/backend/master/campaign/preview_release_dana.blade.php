<?php
  $backlink = @$_GET['backlink'];
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Release Dana</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url(@$_GET['backlink']) }}">Campaign</a>
                    </li>
                    <li class="active">
                        <strong>Release Dana Ke Penerbit</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
              @if ($errors->any())
              <div class="alert alert-danger">
                  Ada kesalahan! mohon cek formulir.
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              </div>
              @endif
              @include('backend.flash_message')
              {!! Form::model($data,['url' => url('payment/release'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Detail Pendanaan</h5>
                        </div>
                        <div class="ibox-content">
                            <!-- campaign title -->
                            <input type="hidden" name="backlink" value="<?=@$_GET['backlink']?>">
                            <input type="hidden" name="id_campaign" value="<?=$data->id_campaign?>">
                            <div class="form-group {{ ($errors->has('invoice_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Nomor Invoice</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::text('invoice_code', null, ['class' => 'form-control text-right disabled','readonly']) !!}
                                    {!! $errors->first('invoice_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('total_terpenuhi')?"has-error":"") }}"><label class="col-sm-4 control-label">Total Pendanaan</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::text('total_terpenuhi', 'Rp.'.number_format($data->total_terpenuhi,0), ['class' => 'form-control text-right disabled','disabled']) !!}
                                    {!! $errors->first('total_terpenuhi', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <?php

                              $fee_type         = $fee_type;
                              $fee_value        = $fee_value;
                              $fee_persen       = $fee_persen;
                              $pajak_type       = $pajak_type;
                              $pajak_value      = $pajak_value;
                              $pajak_persen     = $pajak_persen;

                              $admin_fee_total      = 0;
                              $admin_pajak_total    = 0;

                              switch ($fee_type) {
                                case "value":
                                  $admin_fee_total = $fee_value;
                                  $admin_fee_label = "Rp.".number_format($fee_value,0);
                                break;

                                case "persen":
                                  $admin_fee_total = $data->total_terpenuhi * ($fee_persen/100);
                                  $admin_fee_label = $fee_persen."%";
                                break;
                              }

                              $data->admin_fee = $admin_fee_total;

                              switch ($pajak_type) {
                                case "value":
                                  $admin_pajak_total = $pajak_value;
                                  $admin_pajak_label = "Rp.".number_format($pajak_value,0);
                                break;

                                case "persen":
                                  $admin_pajak_total = ($data->total_terpenuhi - $data->admin_fee) * ($pajak_persen/100);
                                  $admin_pajak_label = $pajak_persen."%";
                                break;
                              }

                              $data->admin_pajak = $admin_pajak_total;

                              $data->sub_total = $data->total_terpenuhi - $data->admin_fee - $data->admin_pajak;

                              if($data->release_status=="released" && $data->id_payout!=""){
                                $admin_pajak_total  = $payout_data->total_pajak;
                                $admin_fee_total    = $payout_data->total_fee;

                                if($payout_data->pajak_type=='persen'){
                                  $afteradmin = $data->total_terpenuhi - $admin_fee_total;
                                  $persent    = number_format($admin_pajak_total*100/$afteradmin,2);
                                  $admin_pajak_label = $persent."%";
                                }else{
                                  $admin_pajak_label = "Rp.".number_format($admin_pajak_total,0);
                                }

                                if($payout_data->fee_type=='persen'){
                                  $afteradmin = $data->total_terpenuhi;
                                  $persent    = number_format($admin_fee_total*100/$afteradmin,2);
                                  $admin_fee_label = $persent."%";
                                }else{
                                  $admin_fee_label = "Rp.".number_format($admin_fee_total,0);
                                }

                                $data->admin_fee    = $admin_fee_total;
                                $data->admin_pajak  = $admin_pajak_total;

                                $data->sub_total    = $data->total_terpenuhi - $data->admin_fee - $data->admin_pajak;
                              }

                            ?>
                            <div class="form-group {{ ($errors->has('biaya_admin')?"has-error":"") }}"><label class="col-sm-4 control-label">Administrasi</label>
                                <div class="col-sm-2 col-xs-12 text-right" style="margin-top:5px;">
                                  <?=$admin_fee_label?>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    {!! Form::hidden('biaya_admin_value', (int)$data->admin_fee, ['class' => 'form-control']) !!}
                                    {!! Form::text('biaya_admin', 'Rp.'.number_format($data->admin_fee,0), ['class' => 'form-control text-right disabled','disabled']) !!}
                                    {!! $errors->first('biaya_admin', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('admin_pajak')?"has-error":"") }}"><label class="col-sm-4 control-label">Pajak <sub>(setelah biaya admin)</sub></label>
                                <div class="col-sm-2 col-xs-12 text-right" style="margin-top:5px;">
                                  <?=$admin_pajak_label?>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    {!! Form::hidden('biaya_pajak_value', (int)$data->admin_pajak, ['class' => 'form-control']) !!}
                                    {!! Form::text('biaya_pajak', 'Rp.'.number_format($data->admin_pajak,0), ['class' => 'form-control text-right disabled','disabled']) !!}
                                    {!! $errors->first('admin_pajak', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <hr>
                            <div class="form-group {{ ($errors->has('sub_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Total Diterima Penerbit</label>
                                <div class="col-sm-2 col-xs-12 text-right" style="margin-top:5px;">

                                </div>
                                <div class="col-sm-6 col-xs-12">

                                    {!! Form::hidden('sub_total', (int)$data->sub_total, ['class' => 'form-control text-right disabled','readonly']) !!}
                                    {!! Form::text('sub_total_label', 'Rp.'.number_format($data->sub_total,0), ['class' => 'form-control text-right disabled','readonly']) !!}
                                    {!! $errors->first('sub_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <?php if($data->release_status=='released'){?>
                              <h5>Detail Pengiriman Dana</h5>
                            <?php }else{ ?>
                              <h5>Detail Rekening Penerbit</h5>
                            <?php }?>
                        </div>
                        <div class="ibox-content">

                            <!-- campaign title -->
                            <input type="hidden" name="backlink" value="<?=@$_GET['backlink']?>">
                            <?php if($data->release_status=='released'){?>
                              <div class="form-group {{ ($errors->has('bank_account')?"has-error":"") }}"><label class="col-sm-4 control-label">Waktu Pengiriman</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('payment_time', date("d M Y H:i:s", @$payout_data->payment_time),  ['class' => 'form-control','id' => 'bank_account',($data->release_status=='released')?'disabled':'']) !!}
                                  </div>
                              </div>
                            <?php }?>
                            <div class="form-group {{ ($errors->has('bank_account')?"has-error":"") }}"><label class="col-sm-4 control-label">Bank Penerima</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::select('bank_account', $banks, @$payout_data->bank_account,  ['class' => 'form-control','id' => 'bank_account',($data->release_status=='released')?'disabled':'']) !!}
                                    {!! $errors->first('bank_account', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('bank_account_number')?"has-error":"") }}"><label class="col-sm-4 control-label">Nomor Rekening</label>
                              <div class="col-sm-8 col-xs-12">
                                  {!! Form::text('bank_account_number', @$payout_data->bank_account_number, ['class' => 'form-control',($data->release_status=='released')?'disabled':'']) !!}
                                  {!! $errors->first('bank_account_number', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                            </div>

                            <div class="form-group {{ ($errors->has('bank_account_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Pemilik Rekening</label>
                              <div class="col-sm-8 col-xs-12">
                                  {!! Form::text('bank_account_name', @$payout_data->bank_account_name, ['class' => 'form-control',($data->release_status=='released')?'disabled':'']) !!}
                                  {!! $errors->first('bank_account_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                            </div>

                            <div class="form-group {{ ($errors->has('description')?"has-error":"") }}"><label class="col-sm-4 control-label">Berita Acara</label>
                              <div class="col-sm-8 col-xs-12">
                                  {!! Form::textarea('description', $berita, ['class' => 'form-control','rows' => 3,($data->release_status=='released')?'disabled':'']) !!}
                                  {!! $errors->first('description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                            </div>

                            <?php if($data->release_status=='released'){?>
                              <div class="form-group {{ ($errors->has('description')?"has-error":"") }}"><label class="col-sm-4 control-label">Status Transaksi Pengiriman</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::text('trx_callback', @$payout_data->trx_status, ['class' => 'form-control',($data->release_status=='released')?'disabled':'']) !!}
                                </div>
                              </div>

                              <div class="form-group {{ ($errors->has('description')?"has-error":"") }}"><label class="col-sm-4 control-label">Disbursement Info</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::textarea('trx_callback', @$payout_data->trx_callback, ['class' => 'form-control','rows' => 5,($data->release_status=='released')?'disabled':'']) !!}
                                </div>
                              </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">

                </div>
                <div class="col-sm-12">
                  <div class="hr-line-dashed"></div>
                  <div class="form-group">
                      <div class="col-sm-4">
                          <a class="btn btn-white" href="{{ url($backlink) }}">
                              <i class="fa fa-angle-left"></i> kembali
                          </a>
                      </div>
                      <div class="col-sm-8 text-right">
                        <?php if($data->release_status!='released'){ ?>
                          <button class="btn btn-primary btn-rounded" type="submit">Release Dana ke Penerbit</button>
                        <?php } ?>
                      </div>
                  </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        @include('backend.footer')
    </div>
</div>
<script>
  $(document).ready(function() {
    $('#bank_account').select2();
  });
</script>
