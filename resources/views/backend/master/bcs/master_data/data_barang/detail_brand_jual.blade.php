<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
?>
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Data Barang</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <light>Data Barang</light>
                    </li>
                    <li class="active">
                        <light>{{ $cek[0]->jenis }}</light>
                    </li>
                    <li class="active">
                        <light>{{ $cek[0]->brand }}</light>
                    </li>
                    <li class="active">
                        <strong>Detail Penjualan</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Detail Penjualan {{$cek[0]->jenis}} {{$cek[0]->brand}}</h5>
                        
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No </th>
                                <th>Kode Transaksi</th>
                                <th>Jenis Transaksi</th>
                                <th>Qty</th>
                                <th>Tanggal</th>
                            </tr>
                          </thead>
                          @php $i=1 @endphp
                           @foreach($data as $value)
                                  <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $value->bcs_kode_datang_id }}</td>
                                    <?php 
                                    $cek=explode("-",$value->bcs_kode_datang_id);
                                    if($cek[1] == "D"){ ?>
                                      <td>Pembelian</td>
                                    <?php } ?>  
                                    <?php if($cek[1] == "J"){ ?>
                                      <td>Penjualan</td>
                                    <?php } ?>  
                                    <?php if($cek[1] == "R"){ ?>
                                      <td>Retur/Rusak</td>
                                    <?php } ?>  
                                                                      
                                    <td>{{ $value->jumlah }}</td>
                                    <td>{{ $value->created_at }}</td>
                                  </tr>
                            @endforeach
                          </tbody>  
                          </tfoot>
                        </table>
                      </div>
                    </div>
                </div>
                </div>
                        <div class="form-group">
                            <div class="col-sm-4 ">
                                <a class="btn btn-white" href="{{ url()->previous() }}">
                                    <i class="fa fa-angle-left"></i> kembali 
                                </a>
                            </div>
                        </div>
            </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
    @include('backend.master.purchase.modal_material_add')
  </div>
</div> 
