<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<?php
  $main_url = $config['main_url'];
  $backlink = @$_GET["backlink"];

  if($backlink==""){
    @$backlink = $config['main_url'];
  }
?>
<style>
  .box-opsi-keuangan span{
    font-size: 12px;
  }
  .box-opsi-keuangan.active{
    background: #3ab6ff;
    color: #fff;
    border-color: #444;
  }
  .box-opsi-keuangan i{
    font-size: 18px;
  }
  .box-opsi-keuangan{
    border: 2px solid #e5e6e7;
    border-radius: 5px;
    text-align: center;
    padding: 15px;
    cursor:pointer;
    margin-bottom: 15px;
  }


</style>
<?php
  $statuses = array(
    'unpaid'    => "Belum dibayar",
    'paid'      => "Telah Terbayar"
  );
?>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Tagihan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Tagihan</a>
                    </li>
                    <li class="active">
                        <strong>Invoice Deviden ke Penerbit "<?=$data->invoice_code?>"</strong>
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
              {!! Form::model($data,['url' => url('payment/charge/deviden'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data','data-parsley-validate novalidate']) !!}

                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Detail Tagihan</h5>
                        </div>
                        <div class="ibox-content">
                            <!-- create date -->
                            <input type="hidden" name="id_penerbit" value="<?=$data->id_penerbit?>">
                            <input type="hidden" name="id_deviden" value="<?=$data->id_deviden?>">

                            <!-- report code -->
                            <div class="form-group {{ ($errors->has('invoice_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Nomor Invoice</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::text('invoice_code', null, ['class' => 'form-control','readonly']) !!}
                                    {!! $errors->first('invoice_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <!-- report code -->
                            <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-4 control-label">Status Pembayaran</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::text('status', $statuses[$data->status], ['class' => 'form-control','readonly']) !!}
                                    {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <!-- PIC -->
                            <div class="form-group {{ ($errors->has('nama_penerbit')?"has-error":"") }}"><label class="col-sm-4 control-label">Tertagih</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::text('nama_penerbit', null, ['class' => 'form-control', 'readonly']) !!}
                                    {!! $errors->first('nama_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- PIC -->
                            <div class="form-group {{ ($errors->has('pic_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama PIC</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::text('pic_name', null, ['class' => 'form-control', 'readonly']) !!}
                                    {!! $errors->first('pic_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('campaign_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Campaign/Penawaran</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::text('campaign_title', null, ['class' => 'form-control disabled','disabled']) !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('id_rups')?"has-error":"") }}"><label class="col-sm-4 control-label">Acuan RUPS</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::text('rups_code', null, ['class' => 'form-control disabled','disabled']) !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('besar_pembagian')?"has-error":"") }}"><label class="col-sm-4 control-label">Nilai Pembagian</label>
                                <div class="col-sm-8 col-xs-12">
                                  <div class="row">
                                    <div class="col-sm-8">
                                      {!! Form::text('besar_pembagian', $data->besar_pembagian.'%', ['class' => 'form-control','readonly','id' => 'besar_pembagian']) !!}
                                    </div>
                                    <div class="col-sm-8" id="loading_detail_rups">

                                    </div>
                                  </div>
                                    {!! $errors->first('besar_pembagian', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('deviden_month')?"has-error":"") }}"><label class="col-sm-4 control-label">Bulan Deviden</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::select('deviden_month', $months, @$data->report_month, ['class' => 'form-control disabled','disabled']) !!}
                                    {!! $errors->first('deviden_month', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('deviden_year')?"has-error":"") }}"><label class="col-sm-4 control-label">Tahun Deviden</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::select('deviden_year', $years, @$data->report_year, ['class' => 'form-control disabled','disabled']) !!}
                                    {!! $errors->first('deviden_year', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('deviden_year')?"has-error":"") }}"><label class="col-sm-4 control-label">Laporan Keuangan</label>
                                <div class="col-sm-8 col-xs-12">
                                  <div class="row">
                                    <?php
                                      if(@$laporans){
                                        foreach ($laporans as $key => $value) {
                                          $value = (object)$value;
                                          ?>
                                          <div class="col-sm-6 col-xs-6">
                                            <div class="box-opsi-keuangan" id="laporan_<?=@$value->id_report?>" onclick="pilihLaporan(<?=$value->id_report?>,'<?=$value->profit?>')">
                                                <i class="fa fa-file"></i>
                                                <br>
                                                <span><?=$months[$value->report_month]?> - <?=$value->report_year?></span><br>
                                                <span>Rp.<?=number_format($value->profit,0)?></span>
                                            </div>
                                          </div>
                                          <?php
                                          $data->profit = $data->profit + $value->profit;
                                        }
                                      }
                                    ?>
                                  </div>
                                </div>
                                <input type="hidden" name="total_pajak" value="<?=$data->pajak_total?>">
                                <input type="hidden" name="total_fee" value="<?=$data->fee_total?>">
                                <input type="hidden" name="pajak_type" value="<?=$data->pajak_type?>">
                                <input type="hidden" name="fee_type" value="<?=$data->fee_type?>">

                            </div>

                            <div class="form-group {{ ($errors->has('profit')?"has-error":"") }}"><label class="col-sm-4 control-label">Profit</label>
                                <div class="col-sm-8 col-xs-12">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      {!! Form::text('profit', 'Rp.'.number_format($data->keuntungan,0), ['class' => 'form-control text-right','id' => 'profit_label','readonly']) !!}
                                      {!! $errors->first('profit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                  </div>
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('pajak')?"has-error":"") }}"><label class="col-sm-4 control-label">Pajak</label>
                                <div class="col-sm-8 col-xs-12">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      {!! Form::text('pajak_total', 'Rp.'.number_format($data->pajak_total,0), ['class' => 'form-control text-right','readonly','id' => 'admin_pajak_label']) !!}
                                      {!! $errors->first('pajak', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                  </div>
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('admin_fee')?"has-error":"") }}"><label class="col-sm-4 control-label">Biaya Admin</label>
                                <div class="col-sm-8 col-xs-12">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      {!! Form::text('fee_total', 'Rp.'.number_format($data->fee_total,0), ['class' => 'form-control text-right','readonly','id' => 'admin_fee_label']) !!}
                                    </div>
                                  </div>
                                </div>
                            </div>


                            <div class="form-group {{ ($errors->has('total_tagihan')?"has-error":"") }}"><label class="col-sm-4 control-label">Total Tagihan</label>
                                <div class="col-sm-8 col-xs-12">
                                    <div class="row">
                                      <div class="col-sm-12">
                                        {!! Form::hidden('total_tagihan', null, ['class' => 'form-control text-right','readonly','id' => 'total_tagihan']) !!}
                                        {!! Form::text('total_tagihan_label', 'Rp.'.number_format($data->total_tagihan,0), ['class' => 'form-control text-right','readonly','id' => 'total_invoice']) !!}
                                        {!! $errors->first('total_tagihan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h5>Detail Pembayaran</h5>
                      </div>
                      <div class="ibox-content">

                        <?php if($data->status=="unpaid"){ ?>
                          <h1 class="text-center"><?=$statuses[$data->status]?></h1>
                        <?php }?>
                        <?php if($data->id_payment==""){ ?>
                          <div class="alert alert-info text-center">
                            Sistem pembayaran menggunakan Virtual Account (VA) dari beberapa bank terpilih dibawah ini. Klik "proses pembayaran" setelah menetapkan bank untuk mendapatkan nomor VA.
                          </div>
                        <?php }else{ ?>
                          <div class="alert alert-warning text-center" style="margin-bottom:25px;">
                            <?php
                              $payment_json = json_decode($payment->trx_callback,true);
                            ?>
                            Nomor Virtual Account <?=$payment_json['bank_code']?> Pembayaran Anda adalah: <h1><?=@$payment->va_number?></h1>
                          </div>
                        <?php }?>

                        <div class="form-group {{ ($errors->has('bank_account')?"has-error":"") }}"><label class="col-sm-4 control-label">Pilihan Bank Pembayaran</label>
                            <div class="col-sm-8 col-xs-12">
                                {!! Form::select('bank_account', $bank, null, ['class' => 'form-control','id' => 'bank_account']) !!}
                                {!! $errors->first('bank_account', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">

                            </div>
                            <div class="col-sm-6 text-left">
                                <button class="btn btn-primary btn-rounded" type="submit">Buat Nomor Virtual Account Baru</button>
                            </div>
                        </div>
                      </div>
                  </div>
                </div>

                <div class="col-sm-12">
                  <div class="rows">
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <a class="btn btn-white" href="{{ url($backlink) }}">
                                <i class="fa fa-angle-left"></i> kembali
                            </a>
                        </div>
                        <div class="col-sm-6 text-right">

                        </div>
                    </div>
                  </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        @include('backend.footer')
    </div>
</div>
<script>
  var profit = 0;
  var profits = new Array();
  var besar_pembagi = 0;
  var fee_value   = <?=$data['fee_value']?>;
  var fee_persen  = <?=$data['fee_persen']?>;
  var fee_type    = "<?=$data['fee_type']?>";

  var pajak_type    = "<?=$data['pajak_type']?>";
  var pajak_value   = "<?=$data['pajak_value']?>";
  var pajak_persen  = "<?=$data['pajak_persen']?>";

  function pilihLaporan(id, keuntungan){
    $("#laporan_"+id).toggleClass("active");
    var ketemu = false;
    if(profits.length>0){
      for(var i in profits){
        if(profits[i].id==id){
          console.log("ketemu");
          profits.splice(i,1);
          ketemu = true;
          break;
        }
      }

      if(!ketemu){
        console.log("tidak ketemu 1");
        profits.push({id:id, keuntungan:keuntungan});
      }
    }else{
      console.log("tidak ketemu 2");
      profits.push({id:id, keuntungan:keuntungan});
    }

    profit = 0;
    for(var i in profits){
        profit = profit + parseInt(profits[i].keuntungan);
    }

    listprofit = [];
    for (var i in profits) {
      listprofit.push(profits[i].id);
    }
    $("#akumulasi_laporan").val(listprofit);

    $("#profit_label").val("Rp."+profit.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1\."));
    $("#profit").val(profit);

    hitungInvoice();
  }

  function getNilaiPembagian(id_rups){
    $("#loading_detail_rups").html("mengambil detail..");
    $.ajax({
      type: "POST",
      url: "<?=url("get-detail-rups")?>",
      data: {
        id_rups: id_rups
      }
    })
    .done(function(result){
      console.log(result);
      besar_pembagi = result.besar_pembagian;
      $("#besar_pembagian").val(besar_pembagi);
      $("#besar_pembagian_label").val(besar_pembagi+"%");

      hitungInvoice();
    })
    .fail(function(err){
      $("#loading_detail_rups").html("Gagal mengambil data!");
    })
    .always(function(){
      $("#loading_detail_rups").html("");
    });
  }

  function hitungInvoice(){
    var total_bagian  = (profit * besar_pembagi)/100;
    var admin_fee_total = 0;
    var admin_pajak_total = 0;
    var total_invoice = 0;

    switch (fee_type) {
      case "value":
        admin_fee_total = fee_value;
        $("#besaran_admin_fee").val("Rp."+fee_value.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1\."));
      break;

      case "persen":
        admin_fee_total = total_bagian * (fee_persen/100);
        $("#besaran_admin_fee").val(fee_persen+"%");
      break;
    }

    switch (pajak_type) {
      case "value":
        admin_pajak_total = pajak_value;
        $("#besaran_admin_pajak").val("Rp."+pajak_value.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1\."));
      break;

      case "persen":
        admin_pajak_total = total_bagian * (pajak_persen/100);
        $("#besaran_admin_pajak").val(pajak_persen+"%");
      break;
    }

    $("#profit_label").val("Rp."+profit.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1\."));

    $("#total_kena_pajak").val("Rp."+(total_bagian - admin_pajak_total).toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1\."));

    $("#admin_fee_label").val("Rp."+admin_fee_total.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1\."));
    $("#admin_pajak_label").val("Rp."+admin_pajak_total.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1\."));
    $("#besar_pajak").val(admin_pajak_total);
    $("#besar_fee").val(admin_fee_total);

    $("#besar_deviden").val(total_bagian);
    $("#besar_deviden_label").val("Rp."+total_bagian.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1\."));

    total_invoice = total_bagian + admin_fee_total - admin_pajak_total;
    $("#total_invoice").val("Rp."+total_invoice.toString().replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1\."));
    $("#total_tagihan").val(total_invoice);
  }

  $(document).ready(function(){
    hitungInvoice();
  });
</script>
<script>
  $(document).ready(function() {
    $('#bank_account').select2();
  });
</script>
