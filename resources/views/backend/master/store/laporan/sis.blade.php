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
                <h2>Laporan Keuangan Outlet</h2>
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
 
<?php if($toko){ ?>
                      <div class="col-lg-3">
                            <input id="toko" name="toko" value="{{ $nama_toko }}" class="form-control input-sm" readonly>
                      </div>
<?php } else { ?> 
                      <div class="col-lg-3">
                      <select class="form-control" name="toko"> 
                          <!-- <option value="">- Pilih -</option> -->
                          @foreach($opttoko as $k)
                          <option value="{{ $k->id }}" <?=($opttoko2== $k->id)?"selected":""?>>{{ $k->nama }}</option>
                          <!-- <option value="{{ $k->id }}" {{ old('toko', $k->id) == $k->id ? 'selected' : '' }}> {{ $k->nama }}</option> -->
                          <!-- <option value="{{ $k->id }}" @if(old('toko') ==  $k->id  )selected @endif >{{ $k->nama }}</option> -->

                          @endforeach

                        </select>
                      </div>
<?php } ?>
                      <div class="col-lg-3">
                            <input type="text" id="date" name="dates" value="{{ $dates }}" class="form-control"/>
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

@if($uang_real or session::get("user")->id_user == 29)
<div class="row">
    <div class="col-md-6">
        <div class="ibox">
                <div class="ibox-title">
                    <div class="col-sm-6">
                    <strong>
                            Pemasukan : Rp {{ number_format($pemasukan)}}
                    </strong>
                    </div>
                    <div class="col-sm-6 text-right">
                        <strong>
                                Uang Real     : Rp {{ number_format($uang_real)}}
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
                                    <th>Penjualan Outlet</th>
                                    <th>Rp. {{  number_format($omset_outlet) }}</th>
                                </tr>
                                <tr>
                                    <th>Penjualan Brownies Retur </th>
                                    <th>Rp. {{ number_format($penjualan_retur) }}</th>
                                </tr>
                                <tr>
                                    <th>Penjualan SF</th>
                                    <form action="{{ url('store/sf') }}">
                                    <th>Rp. {{ number_format($omsetsf)}}
                                    <button class="pull-right btn-primary">+</button>
                                    </form>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Penjualan Reseller</th>
                                    <th>Rp. {{ number_format( $omset_reseller) }}</th>
                                </tr>
                                <tr>
                                    <th>Penjualan HNI</th>
                                    <th>Rp. {{ number_format($hni) }}</th>
                                </tr>
                                <tr>
                                    <th>Penerimaan Basil Olshop</th>
                                    <th>Rp. {{ number_format($basil_olshop)}}</th>
                                </tr>
                                <tr>
                                    <th>Penerimaan Basil Agen</th>
                                    <th>Rp. {{ number_format($basil_agen)}}</th>
                                </tr>
                                <tr>
                                    <th>Penerimaan Basil Reseller</th>
                                    <th>Rp. {{ number_format($penerimaan_basil_reseller)}}</th>
                                </tr>
                                <tr>
                                    <th>Penerimaan DP Outlet</th>
                                    <th>Rp. {{ number_format($dp) }}</th>
                                </tr>
                                <tr>
                                    <th>Penerimaan DP HNI</th>
                                    <th>Rp. {{ number_format($dp_hni) }}</th>
                                </tr>
                                <tr>
                                    <th>Pelunasan Piutang Outlet</th>
                                    <th>Rp. {{ number_format($pelunasan) }}</th>
                                </tr>
                                <tr>
                                    <th>Pelunasan Piutang BC HNI</th>
                                    <th>Rp. {{ number_format($pelunasan_hni) }}</th>
                                </tr>
                                <tr>
                                    <th>Pelunasan Retur</th>
                                    <th>Rp. {{ number_format($pelunasan_piutang_retur) }}</th>
                                </tr>
                                <tr>
                                    <th>Titipan Uang Olshop</th>
                                    <th>Rp. {{ number_format($titipan_olshop)}}</th>
                                </tr> 
                                <tr>
                                    <th>Titipan Uang Reseller</th>
                                    <th>Rp. {{ number_format($titipan_reseller) }}</th>
                                </tr> 
                                <tr>
                                    <th>Lain-lain</th>
                                    <form action="{{ url('store/lain') }}">
                                    <th>Rp. {{ number_format($lain)}}
                                    <button class="pull-right btn-primary">+</button>
                                    </form>
                                    </th>
                                
                                </form>
                                    
                                </tr>
                                <tr>
                                    <th>Omset</th>
                                    <th>Rp. {{ number_format($omset_total) }}</th>
                                </tr>
                            </tbody>
                        </table>
                        <br>    
                        <!-- <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                <th>Saldo</th>
                                <th>Rp {{ number_format($saldo)}}</th>
                                </tr>
                            </thead>
                        </table> -->
                    </div>
                </div>
        </div>
    </div>

    <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="col-sm-6 left-text">
                        <strong>
                            Pengeluaran : Rp {{ number_format($pengeluaran)}}
                        </strong>
                    </div> 
                        <div class="col-sm-6 text-right">
                        <strong>
                                Saldo     : Rp {{ number_format($saldo)}}
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
                                <th>Transfer Rek.Outlet</th>
                                <form action="{{ url('store/transfer') }}">
                                <th>Rp. {{ number_format($tf_outlet) }}
                                    <button class="pull-right btn-primary">+</button>
                                </th>
                                </form>
                            </tr>
                            <tr>
                                <th>Transfer Rek.HNI</th>
                                <form action="{{ url('store/transfer') }}">
                                <th>Rp. {{ number_format($tf_hni) }}
                                    <button class="pull-right btn-primary">+</button>
                                </th>
                                </form>
                            </tr>
                            <tr>
                                <th>Transfer Rek.BC Store</th>
                                <form action="{{ url('store/transfer') }}">
                                <th>Rp. {{ number_format($tf_bcs) }}
                                    <button class="pull-right btn-primary">+</button>
                                </th>
                                </form>
                            </tr>
                            <tr>
                                <th>Pengeluaran DP Outlet</th>
                                <th>Rp. {{ number_format($pengeluaran_dp_outlet)}}</th>
                            </tr>
                            <tr>
                                <th>Pengeluaran DP BC HNI</th>
                                <th>Rp. {{ number_format($pengeluaran_dp_hni)}}</th>
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
                                <th>Piutang Penjulan Langsung</th>
                                <th>Rp. {{ number_format($piutang_langsung)}}</th>
                            </tr>
                            <tr>
                                <th>Piutang Go Resto</th>
                                <!-- <form action="{{ url('store/gograb') }}"> -->
                                <th>Rp. {{number_format($gofood)}}
                                    <!-- <button class="pull-right btn-primary">+</button>
                                </th>
                                </form> -->
                            </tr>
                            <tr>
                                <th>Piutang GrabFood</th>
                                <!-- <form action="{{ url('store/gograb') }}"> -->
                                <th>Rp. {{number_format($grab)}}
                                    <!-- <button class="pull-right btn-primary">+</button>
                                </th>
                                </form> -->
                            </tr>
                            <tr>
                                <th>Piutang Basil Olshop</th>
                                <th>Rp. {{ number_format($piutang_basil_olshop)}}</th>
                            </tr>
                            <tr>
                                <th>Piutang Basil Agen</th>
                                <th>Rp. {{ number_format($basil_agen)}}</th>
                            </tr>
                            <tr>
                                <th>Piutang Reseller</th>
                                <th>Rp. {{ number_format($piutang_reseller)}}</th>
                            </tr>
                            <tr>
                                <th>Piutang BC HNI</th>
                                <th>Rp. {{ number_format($piutang_hni)}}</th>
                            </tr>
                            <tr>
                                <th>Biaya Basil Reseller</th>
                                <th>Rp. {{ number_format($biaya_basil_reseller)}}</th>
                            </tr>
                            <tr>
                                <th>Diskon Penjualan</th>
                                <th>Rp. {{ number_format($diskon_penjualan) }}</th>
                            </tr>
                            <tr>
                                <th>Diskon Retur</th>
                                <th>Rp. {{ number_format($diskon_retur) }}</th>
                            </tr>
                            <tr>
                                <th>Diskon SF</th>
                                <form action="{{ url('store/sf') }}">
                                    <th>Rp. {{ number_format($diskonsf)}}
                                    <button class="pull-right btn-primary">+</button>
                                    </th>      
                                </form>
                                </tr>
                            <!-- <tr>
                                <th>Refund</th>
                                <th>Rp. 3123213</th>
                            </tr> -->
                            <tr>
                                <th>Tester</th>
                                <form action="{{ url('store/tester') }}">
                                <th>Rp. {{ number_format($tester)}}
                                    <button class="pull-right btn-primary">+</button>
                                </th>
                                </form>
                            </tr>
                            <tr>
                                <th>Entertaint</th>
                                <form action="{{ url('store/entertaint') }}">
                                <th>Rp. {{ number_format($entertaint)}}
                                    <button class="pull-right btn-primary">+</button>
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
@else
<div class="ibox">
    <div class="ibox-title">
    
    <h3>
    <center>
    LAPORAN KEUANGAN AKAN TAMPIL JIKA UANG REAL PADA TANGGAL INI SUDAH DI INPUTKAN
    </center>
    </div>
    </h3>

</div>
@endif
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
