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
                        <strong>Detail Datang</strong>
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
            <div class="ibox-content p-3 mb-3">
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
                  Toko
                  <address>
                    <strong>{{ $nama_toko }}</strong><br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <!-- Ke -->
                  <address>
                    <!-- <strong>{{ $kode_jual->toko_id }}</strong><br> -->
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
                      <th>Barang</th>
                      <th>Qty</th>
                      <th>Tipe Barang</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($barang_jual as $b)
                    <tr>
                      <td>{{ $b->store_master_barang->nama }}</td>
                      <td>{{ $b->jumlah}}</td> 
                      <td>{{ $b->store_master_barang->store_tipe->tipe }}</td>
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
                        <th>Total Awal:</th>
                        <td>Rp {{ number_format($kode_jual->total_awal) }}</td>
                    </tr>
                        <th>Diskon:</th>
                        <td>Rp {{ number_format($kode_jual->diskon) }}</td>
                      <tr>
                        <th>Total:</th>
                        <td>Rp {{ number_format($kode_jual->total) }}</td>
                      </tr>
                    </table>
                  </div>
                  <form action="{{ route('barang_jual.index') }}" method="GET">
                    <button type="submit" class="pull-right btn btn-secondary">Back</button>  
                  </form>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
          </div>
          @include('backend.do_confirm')
            @include('backend.footer')
        </div><!-- /.row -->
