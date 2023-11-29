<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
?>
<!-- Select2 -->
<!-- <link rel="stylesheet" href="{{ asset('style2/plugins/daterangepicker/daterangepicker.css') }}"> -->

<!-- DataTables -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"> -->
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
  
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Laporan Keuangan BCStore</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>laporan keuangan</strong>
                    </li>
                </ol>
            </div>
    </div> 
    <br>

    <div class="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                  <div class="ibox-title"> 
                    {!! Form::open(['url' => url($config['main_url']), 'method' => 'get', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                    <div class="row">

                      <div class="col-lg-3">
                            <input type="text" id="date" name="dates" value="{{$dates}}" class="form-control"/>
                      </div>
                      <div class="col-lg-2">
                        <button class="btn btn-primary btn-block">
                            <i class="fa fa-search"></i> Pilih
                        </button>
                      </div>
                    </div>
                    {!! Form::close() !!}
                  </div>
                </div>
            </div>
        </div>           
    </div>


<div class="row">
    <div class="col-md-6">
        <div class="ibox">
                <div class="ibox-title">
                    <div class="col-sm-6">
                    <strong>
                            Pemasukan : Rp {{ number_format($jualG[0]->jual + $jualR[0]->jual + $PDP)}}
                    </strong>
                    </div>
                    <div class="col-sm-6 text-right">
                        <strong>
                                Uang Real     : Rp {{ number_format($UR)}}
                        </strong>
                        </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="laporan" cellpadding="0">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="2"><p><b>Penjualan Grosir</b></p></th>
                                </tr>
                                <tr>
                                    <th>Penjualan BCSTORE</th>
                                    <th>Rp. {{ number_format($jualBCG[0]->jual)}}</th>
                                </tr>
                                <tr>
                                    <th>Penjualan Minyak dan Tepung </th>
                                    <th>Rp. {{ number_format($jualMYG[0]->jual+$jualTPG[0]->jual)}}</th>
                                </tr>
                                <tr>
                                    <th>Penjualan Gula </th>
                                    <th>Rp. {{ number_format($jualGLG[0]->jual)}}</th>
                                </tr>
                                <tr>
                                    <th>Total Penjualan Grosir</th>
                                    <th>Rp. {{ number_format($jualG[0]->jual)}}</th>
                                </tr>
                                
                                <tr>
                                    <th colspan="2"><p><b>Penjualan Retail</b></p></th>
                                </tr>
                                <tr>
                                    <th>Penjualan BCSTORE</th>
                                    <th>Rp. {{ number_format($jualBCR[0]->jual)}}</th>
                                </tr>
                                <tr>
                                    <th>Penjualan Minyak dan Tepung </th>
                                    <th>Rp. {{ number_format($jualMYR[0]->jual+$jualTPR[0]->jual)}}</th>
                                </tr>
                                <tr>
                                    <th>Penjualan Gula </th>
                                    <th>Rp. {{ number_format($jualGLR[0]->jual)}}</th>
                                </tr>
                                <tr>
                                    <th>Total Penjualan Retail</th>
                                    <th>Rp. {{ number_format($jualR[0]->jual)}}</th>
                                </tr>
                                <tr>
                                    <th colspan="2">Pelunasan Piutang</th>
                                </tr>
                                <tr>
                                    <th>Pelunasan Piutang BC Store</th>
                                    <th>Rp. {{ number_format($pelunasanBCS[0]->lunas)}}</th>
                                </tr>
                                <tr>
                                    <th>Pelunasan Minyak dan Tepung</th>
                                    <th>Rp. {{ number_format($pelunasanMY[0]->lunas + $pelunasanTP[0]->lunas)}}</th>
                                </tr>
                                <tr>
                                    <th>Pelunasan Piutang Gula</th>
                                    <th>Rp. {{ number_format($pelunasanGL[0]->lunas)}}</th>
                                </tr>
                                <tr>
                                    <th>Penerimaan DP</th>
                                    <th>Rp. {{ number_format($PDP)}}</th>
                                </tr>
                            </tbody>
                        </table>
                        <br>    
                    </div>
                </div>
        </div>
    </div>

    <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="col-sm-6 left-text">
                        <strong>
                            Pengeluaran : Rp {{ number_format($piutangBCS[0]->tempo + $piutangTP[0]->tempo + $piutangMY[0]->tempo + $piutangGL[0]->tempo + $PO)}}
                        </strong>
                    </div> 
                        <div class="col-sm-6 text-right">
                        <strong>
                                Saldo     : Rp {{ number_format($UR + $jualG[0]->jual + $jualR[0]->jual - $piutangBCS[0]->tempo - $piutangTP[0]->tempo - $piutangMY[0]->tempo - $piutangGL[0]->tempo - $PO)}}
                        </strong>
                        </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">  
                        <table class="table table-striped table-bordered" id="laporan" cellpadding="0">
                        <thead>
                        </thead>
                        <tbody>
                        <tr>
                                <th>Transfer Bank</th>
                                <th>Rp. {{ number_format($jualMYG[0]->jual + $jualTPG[0]->jual + $jualGLG[0]->jual + $jualMYR[0]->jual + $jualTPR[0]->jual + $jualGLR[0]->jual + $pelunasanMY[0]->lunas + $pelunasanTP[0]->lunas + $pelunasanGL[0]->lunas - $piutangMY[0]->tempo - $piutangTP[0]->tempo - $piutangGL[0]->tempo)}}</th>
                            </tr>
                            <tr>
                                <th>Piutang BC Store</th>
                                <th>Rp. {{ number_format($piutangBCS[0]->tempo)}}</th>
                            </tr>
                            <tr>
                                <th>Piutang Minyak dan Tepung</th>
                                <th>Rp. {{ number_format($piutangMY[0]->tempo + $piutangTP[0]->tempo)}}</th>
                            </tr>
                            <tr>
                                <th>Piutang Gula</th>
                                <th>Rp. {{ number_format($piutangGL[0]->tempo)}}</th>
                            </tr>
                            <tr>
                                <th>Piutang Outlet</th>
                                <th>Rp. {{ number_format($PO)}}</th>
                            </tr>
                            
                        </tbody>
                    </table>
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

        $('#date').daterangepicker({
          locale: {
            format: 'YYYY-MM-DD'
          },
          singleDatePicker: true,
        });
    });
</script>
