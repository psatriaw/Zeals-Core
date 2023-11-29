<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
  ?>
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

<div id="wrapper"> 
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kasir</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Detail Jual</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>   

        <div class="row">
          <div class="col-md-12">
          <div class="ibox">
            <!-- <div class="callout callout-info">
              <h5><i class="fas fa-info"></i> Note:</h5>
              This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
            </div> -->
 
            <br/>
            <!-- Main content -->
            <div class="ibox-content">
              <!-- title row -->
              <div class="row">
                <div class="col-md-12">
                  <h4>
                    <i class="fas fa-globe"></i> Detail Barang Jual
                    <small class="pull-right">{{ $kode_jual->created_at }}</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Customer
                  <address>
                    <strong>{{ $kode_jual->bcs_customer[0]->nama}}</strong><br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  Tipe Penjualan
                  <address>
                    <strong>{{ $kode_jual->jenis_jual }}</strong><br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                Kode Jual
                <address>
                  <b>{{ $kode_jual->id }}</b><br>
                </address>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-md-12 table-responsive">
                  <table id="table1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>Kode Barang</th>
                      <th>Jenis</th>
                      <th>Brand</th> 
                      <th>Harga</th>
                      <th>Qty</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($barang_jual as $b)
                    <tr>
                      <td>{{ $b->kode_barang }}</td>
                      <td>{{ $b->jenis }}</td>
                      <td>{{ $b->brand }}</td>
                      <td>Rp {{ number_format($b->harga) }}</td>
                      <td>{{ $b->jumlah}}</td>
                      <td>Rp {{ number_format($b->subtotal) }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-md7">
                  
                </div>
                <!-- /.col -->
                <div class="col-md-5 pull-right">

                  <div class="table-responsive">
                    <table class="table">

                      <tr>
                        <th>Total:</th>
                        <td>Rp {{ number_format($kode_jual->total) }}</td>
                      </tr>
                    </table>
                  </div>
                  <form action="{{ route('penjualan.index') }}" method="GET">
                    <button type="submit" class="pull-right btn btn-secondary">Back</button>  
                  </form>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.invoice -->
            </div>
          </div><!-- /.col -->
            @include('backend.do_confirm')
            @include('backend.footer')
        </div><!-- /.row -->
   
