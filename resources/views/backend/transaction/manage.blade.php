<?php
  $main_url = $config['main_url'];
  //print "<pre>";
  //print_r($data->toArray());
  if($data->kredit>0){
    $data->tipe = "kredit";
    $data->total = "Rp.".number_format($data->kredit,0);
  }elseif($data->debit>0){
    $data->tipe = "Debit";
    $data->total = "Rp.".number_format($data->debit,0);
  }

  if($data->trx_action=="manual"){
    $data->trx_action = "Dibantu Staff";
  }else{
    $data->trx_action = "System Callback (Virtual Account)";
  }

  $data->trx_amount = "Rp.".number_format($data->trx_amount,0);

  $data->time_created = date("d M Y H:i",$data->time_created);
  $data->last_update  = date("d M Y H:i",$data->last_update);
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>TopUp</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ url('dashboard/view') }}">Dashboard</a>
                </li>
                <li>
                    <a href="{{ url('master/topup') }}">Top Up</a>
                </li>
                <li class="active">
                    <strong>Detail Top Up <?=$data->trx_code?></strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
          <div class="col-lg-6">
            <div class="ibox float-e-margins mt-3">
                <div class="ibox-title">
                    <h5>Detail Transaksi</h5>
                </div>

                <div class="ibox-content">
                  {!! Form::model($data,['url' => url($main_url), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                  <div class="form-group {{ ($errors->has('penggajian_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Waktu Transaksi</label>
                      <div class="col-sm-8 col-xs-12">
                          {!! Form::text('time_created', null, ['class' => 'form-control disabled','disabled' => true,'readonly' => 'readonly']) !!}
                      </div>
                  </div>

                  <div class="form-group {{ ($errors->has('penggajian_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Diupdate</label>
                      <div class="col-sm-8 col-xs-12">
                          {!! Form::text('last_update', null, ['class' => 'form-control disabled','disabled' => true,'readonly' => 'readonly']) !!}
                      </div>
                  </div>

                  <div class="form-group {{ ($errors->has('cart_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Transaksi</label>
                      <div class="col-sm-8 col-xs-12">
                          {!! Form::text('cart_code', null, ['class' => 'form-control disabled','disabled' => true,'readonly' => 'readonly']) !!}
                      </div>
                  </div>
                  <div class="form-group {{ ($errors->has('bulan')?"has-error":"") }}"><label class="col-sm-4 control-label">Akun</label>
                      <div class="col-sm-6 col-xs-12">
                        {!! Form::text('first_name', null, ['class' => 'form-control disabled','disabled' => true,'readonly' => 'readonly']) !!}
                      </div>
                      <div class="col-sm-2">
                        <a href="<?=url("/admin/user/detail/".$data->id_user)?>" target="_blank"><i class="fa fa-external-link-alt"></i> lihat akun</a>
                      </div>
                  </div>
                  <div class="form-group {{ ($errors->has('tahun')?"has-error":"") }}"><label class="col-sm-4 control-label">Saham</label>
                      <div class="col-sm-8 col-xs-12">
                          <input type="text" class="form-control disabled" value="<?=$data->campaign_title?>" disabled>
                      </div>
                  </div>

                  <div class="form-group {{ ($errors->has('tahun')?"has-error":"") }}"><label class="col-sm-4 control-label">Quantity</label>
                      <div class="col-sm-8 col-xs-12">
                          <input type="text" class="form-control disabled" value="<?=$data->quantity?>" disabled>
                      </div>
                  </div>

                  <div class="form-group {{ ($errors->has('total_bayar')?"has-error":"") }}"><label class="col-sm-4 control-label">Harga Saham</label>
                      <div class="col-sm-8 col-xs-12">
                          <input type="text" class="form-control disabled text-right" value="Rp.<?=number_format($data->harga_beli,0)?>,-" disabled>
                      </div>
                  </div>

                  <div class="form-group {{ ($errors->has('total_trx')?"has-error":"") }}"><label class="col-sm-4 control-label">Total Transaksi</label>
                      <div class="col-sm-8 col-xs-12">
                          <input type="text" class="form-control disabled text-right" value="Rp.<?=number_format($data->total_trx,0)?>,-" disabled>
                      </div>
                  </div>
                  <div class="form-group {{ ($errors->has('total_bayar')?"has-error":"") }}"><label class="col-sm-4 control-label">Total Bayar</label>
                      <div class="col-sm-8 col-xs-12">
                          <input type="text" class="form-control disabled text-right" value="Rp.<?=number_format($data->total_bayar,0)?>,-" disabled>
                      </div>
                  </div>

                  <div class="form-group {{ ($errors->has('tahun')?"has-error":"") }}"><label class="col-sm-4 control-label">Jenis Pembayaran</label>
                      <div class="col-sm-8 col-xs-12">
                        <input type="text" class="form-control disabled" value="<?=($data->trx_request=="" && $data->status_pembayaran=="paid")?"Saldo":"Online Payment"?>" disabled>
                      </div>
                  </div>
                  <div class="form-group {{ ($errors->has('tahun')?"has-error":"") }}"><label class="col-sm-4 control-label">Data Callback (online payment)</label>
                      <div class="col-sm-8 col-xs-12">
                          <div class="well">
                            <pre>
                                <?php
                                  $callback = $data->trx_callback;
                                  if($callback){
                                    $callback = json_decode($callback,true);
                                    print_r($callback);
                                  }
                                ?>
                            </pre>
                          </div>
                      </div>
                  </div>

                  <div class="form-group {{ ($errors->has('tahun')?"has-error":"") }}"><label class="col-sm-4 control-label">Data Callback (online payment)</label>
                      <div class="col-sm-8 col-xs-12">
                          <div class="well">
                            <pre>
                                <?php
                                  $request = $data->trx_request;
                                  if($request){
                                    $request = json_decode($request,true);
                                    print_r($request);
                                  }
                                ?>
                            </pre>
                          </div>
                      </div>
                  </div>
                  {!! Form::close() !!}
                </div>
            </div>
            <div class="form-group mb-5">
                <div class="col-sm-4">
                    <a class="btn btn-white" href="{{ url($main_url) }}">
                        <i class="fa fa-angle-left"></i> kembali
                    </a>
                </div>
                <div class="col-sm-8 text-right">

                </div>
            </div>
          </div>
        </div>
    </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>
