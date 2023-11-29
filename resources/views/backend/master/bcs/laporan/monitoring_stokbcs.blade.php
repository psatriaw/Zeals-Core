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
                <h2>MONITORING STOK</h2>
                <ol class="breadcrumb">
                    <li>
                        <p>Laporan</p>
                    </li>
                    <li class="active">
                        <strong>Monitoring Stok</strong>
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
                            <input type="date" id="dates" name="dates" value="{{ $dates }}" class="form-control"/>
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
    <div class="col-md-12">
        <div class="ibox">
                <div class="ibox-title">
                    <h3>Monitoring Stok</h3>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="laporan" cellpadding="0">
                            <thead>
                                <th>Varian</th>
                                <th>Brand</th>
                                <th>Stok Awal</th>
                                <th>Datang</th>
                                <th>Jual</th>
                                <th>Retur</th>
                                <th>Stock Akhir</th>
                            </thead>
                            <tbody>
                              @foreach( $data as $value)
                            <tr>
                                <td>{{ $value->nama_barang }}</td>
                                <td>{{$value->brand}}</td>
                              @foreach( $stock_awal as $awal)
                              @if($awal->nama_barang == $value->nama_barang and
                              $awal->brand == $value->brand)
                                <td>{{ $awal->stock }}</td>
                              @endif
                              @endforeach
                                <td>{{ $value->datang }}</td>
                                <td>{{ $value->jual }}</td>
                                <td>{{ $value->rusak }}</td>
                              @foreach( $stock_akhir as $akhir)
                              @if($akhir->nama_barang == $value->nama_barang and
                              $akhir->brand == $value->brand)
                                <td>{{ $akhir->stock }}</td>
                              @endif
                              @endforeach
                            </tr>
                            @endforeach



                              <!--@foreach($data as $d)
                              <tr>
                                  <td>{{$d->nama_barang}}</td>
                                  <td>Stok awal</td>
                                  <td>{{$d->datang}}</td>
                                  <td>{{$d->jual}}</td>
                                  <td>{{$d->rusak}}</td>
                              </tr>
                              @endforeach-->
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>


</div>
@include('backend.footer')

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
