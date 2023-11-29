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
                        <strong>Detail Order Stok</strong>
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
                    <i class="fas fa-globe"></i> Detail Order stok
                    <small class="pull-right">{{ $kode_order_stok[0]->created_at }}</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Toko
                  <address>
                    <strong>{{ $kode_order_stok[0]->nama_toko }}</strong><br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <!-- Ke -->
                  <address>
                    <!-- <strong>{{ $kode_order_stok[0]->toko_id }}</strong><br> -->
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                Kode Order_stok
                <address>
                  <b>{{ $kode_order_stok[0]->id }}</b><br>
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
                      <th>Barang</th>
                      <th>Qty</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($barang_order_stok as $b)
                    <tr>
                      <td>{{ $b->nama_barang }}</td>
                      <td>{{ $b->qty}}</td> 
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
                <div class="col-md-7">
                  
                </div>
                <!-- /.col -->
                <div class="col-md-5">
                  <p class="lead"></p>

                  <div class="table-responsive">
                    <table class="table">
                    <tr>
                        <th>Total:</th>
                        <td>Rp {{ number_format($kode_order_stok[0]->total) }}</td>
                    </tr>
                    </table>
                  </div>
                  <form action="{{ route('stok.index') }}" method="GET">
                    <button type="submit" class="pull-right btn btn-secondary">Back</button>  
                  </form>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   

 