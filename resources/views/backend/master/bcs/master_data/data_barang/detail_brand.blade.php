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
                        <light>{{ $data[0]->jenis }}</light>
                    </li>
                    <li class="active">
                        <strong>{{ $data[0]->brand }}</strong>
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
                        <h5>Detail Stok {{$data[0]->jenis}} {{$data[0]->brand}}</h5>
                        
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No </th>
                                <th>Kode Pembelian</th>
                                <th>Harga Satuan</th>
                                <th>Stok</th>
                                <th>Opsi</th>
                            </tr>
                          </thead>
                          @php $i=1 @endphp
                           @foreach($stok_barang as $value)
                                  <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $value->kode_barang }}</td>
                                    <td>Rp {{ number_format($value->hpp) }}</td>
                                    <td>{{ $value->stok }}</td>
                                    <td>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                          <a href="{{ route('data_barang_brand_jual.detail',$value->kode_barang) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-cogs"></i> detail</a>
                                      <?php }?>

                                      
                                    </td>
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
                                <a class="btn btn-white" href="{{ route('data_barang.detail',$value->jenis_id) }}">
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
