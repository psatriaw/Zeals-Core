<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
?>
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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
                <h2>Kategori</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>SIS</strong>
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
                            <input id="toko" name="toko" value="{{ $nama_toko }}" class="form-control input-sm" readonly>
                      </div>
                      <div class="col-lg-3">
                            <input type="date" id="date" name="dates" value="{{ $today }}" class="form-control"/>
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
                    Pemasukan : Rp 9999999
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="laporan" cellpadding="0">
                            <thead>
                            </thead>
                            <tbody>
                            <tr>
                                    <th>Penjualan Outlet</th>
                                    <th>Rp. {{  number_format($omset_outlet) }}</th>
                                </tr>
                                <tr>
                                    <th>Penjualan Brownies Retur </th>
                                    <th>Rp. {{ number_format($penjualan_retur) }}</th>
                                </tr>
                                <tr>
                                    <th>Penjualan SF</th>
                                    <th>Rp. 3123213</th>
                                </tr>
                                <tr>
                                    <th>Penjualan HNI</th>
                                    <th>Rp. {{ number_format($hni) }}</th>
                                </tr>
                                <tr>
                                    <th>Penerimaan Basil Olshop</th>
                                    <th>Rp. 3123213</th>
                                </tr>
                                <tr>
                                    <th>Penerimaan Basil Agen</th>
                                    <th>Rp. 3123213</th>
                                </tr>
                                <tr>
                                    <th>Penerimaan DP Outlet</th>
                                    <th>Rp. {{ number_format($dp) }}</th>
                                </tr>
                                <tr>
                                    <th>Pelunasan Piutang Outlet</th>
                                    <th>Rp. 3123213</th>
                                </tr>
                                <tr>
                                    <th>Titipan Uang Olshop</th>
                                    <th>Rp. 3123213</th>
                                </tr>
                                <tr>
                                    <th>Lain-lain</th>
                                    <th>Rp. 3123213</th>
                                </tr>
                                <tr>
                                    <th>Omset</th>
                                    <th>Rp. 3123213</th>
                                </tr>
                            </tbody>
                        </table>
                        <br>    
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                <th>Saldo</th>
                                <th>Rp99999</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
        </div>
    </div>

    <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-title">
                    Pengeluaran : Rp 999999
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">  
                        <table class="table table-striped table-bordered" id="laporan" cellpadding="0">
                        <thead>
                        </thead>
                        <tbody>
                        <tr>
                                <th>Transfer Rek.Outlet</th>
                                <form action="{{ url('store/transfer') }}">
                                <th>Rp. {{ number_format($tf_outlet) }}
                                    <button class="pull-right btn-primary">add</button>
                                </th>
                                </form>
                            </tr>
                            <tr>
                                <th>Transfer Rek.HNI</th>
                                <form action="{{ url('store/transfer') }}">
                                <th>Rp. {{ number_format($tf_hni) }}
                                    <button class="pull-right btn-primary">add</button>
                                </th>
                                </form>
                            </tr>
                            <tr>
                                <th>Transfer Rek.BC Store</th>
                                <form action="{{ url('store/transfer') }}">
                                <th>Rp. {{ number_format($tf_bcs) }}
                                    <button class="pull-right btn-primary">add</button>
                                </th>
                                </form>
                            </tr>
                            <tr>
                                <th>Pengeluaran DP Outlet</th>
                                <th>Rp. 3123213</th>
                            </tr>
                            <tr>
                                <th>Piutang Pesanan</th>
                                <th>Rp. {{ number_format($piutang_pesanan) }}</th>
                            </tr>
                            <tr>
                                <th>Piutang Retur</th>
                                <th>Rp. {{ number_format($piutang_retur) }}</th>
                            </tr>
                            <tr>
                                <th>Piutang Penjulan Retur</th>
                                <th>Rp. 3123213</th>
                            </tr>
                            <tr>
                                <th>Piutang Go Resto</th>
                                <form action="{{ url('store/tester') }}">
                                <th>Rp. {{$gofood}}
                                    <button class="pull-right btn-primary">add</button>
                                </th>
                                </form>
                            </tr>
                            <tr>
                                <th>Piutang GrabFood</th>
                                <form action="{{ url('store/tester') }}">
                                <th>Rp. {{$grab}}
                                    <button class="pull-right btn-primary">add</button>
                                </th>
                                </form>
                            </tr>
                            <tr>
                                <th>Piutang Basil Agen</th>
                                <th>Rp. 3123213</th>
                            </tr>
                            <tr>
                                <th>Diskon Penjualan</th>
                                <th>Rp. {{ number_format($diskon_outlet) }}</th>
                            </tr>
                            <tr>
                                <th>Diskon Retur</th>
                                <th>Rp. {{ number_format($diskon_retur) }}</th>
                            </tr>
                            <tr>
                                <th>Diskon SF</th>
                                <th>Rp. 3123213</th>
                            </tr>
                            <!-- <tr>
                                <th>Refund</th>
                                <th>Rp. 3123213</th>
                            </tr> -->
                            <tr>
                                <th>Tester</th>
                                <form action="{{ url('store/tester') }}">
                                <th>Rp. 0
                                    <button class="pull-right btn-primary">add</button>
                                </th>
                                </form>
                            </tr>
                            <tr>
                                <th>Entertaint</th>
                                <form action="{{ url('store/entertaint') }}">
                                <th>Rp. 0
                                    <button class="pull-right btn-primary">add</button>
                                </th>
                                </form>
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