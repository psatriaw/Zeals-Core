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
                        <strong>Detail Mutasi</strong>
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
                    <i class="fas fa-globe"></i> Detail Barang Mutasi
                    <small class="pull-right">{{ $kode_mutasi[0]->created_at }}</small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Dari
                  <address>
                    <strong>{{ $kode_mutasi[0]->nama_toko }}</strong><br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  Ke
                  <address>
                    <strong>{{ $kode_mutasi[0]->tujuan }}</strong><br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                Kode Mutasi
                <address>
                  <b>{{ $kode_mutasi[0]->id }}</b><br>
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
                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                      <th>Aksi</th>
                      <?php }?>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($barang_mutasi as $b)
                    <tr>
                      <td>{{ $b->nama }}</td>
                      <td>{{ $b->jumlah}}</td>
                      <td>Rp {{ number_format($b->subtotal) }}</td>
                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                      <td>
                                          <a href="{{ route('mutasi_edit_admin',$b->id) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a data-id="{{ $b->id }}" data-url="{{ route('mutasi_hapus_admin',$b->id) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                    </td>
                                      <?php }?>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.col-md- -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments col-md-umn -->
                <div class="col-md-7">
                  
                </div>
                <!-- /.col-md- -->
                <div class="col-md-5">
                  <p class="lead"></p>

                  <div class="table-responsive">
                    <table class="table">

                      <tr>
                        <th>Total:</th>
                        <td>Rp {{ number_format($kode_mutasi[0]->total) }}</td>
                      </tr>
                    </table>
                  </div>
                  <form action="{{ route('barang_mutasi.index') }}" method="GET">
                    <button type="submit" class="pull-right btn btn-secondary">Back</button>  
                  </form>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
          @include('backend.do_confirm')
            @include('backend.footer')
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
 
  