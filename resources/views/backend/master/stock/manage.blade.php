<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Stok Produk</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/stock') }}">Stok</a>
                    </li>
                    <li class="active">
                        <strong>Detail Stok "<?=$detail->product_name?>"</strong>
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
                          <h5>Tabel Stok Produk</h5>
                      </div>
                      <div class="ibox-content">
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                            <thead>
                              <tr>
                                  <th>No.</th>
                                  <th>Kode Produksi</th>
                                  <th>Tgl Produksi</th>
                                  <th>Produk</th>
                                  <th>Quantity</th>
                                  <th>Admin</th>
                                  <th>Dipakai</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $counter = 0;

                                $productionb_status = array('queue' => 'Dalam Antrian', "production" => "Proses Produksi","ready" => "Selesai Produksi","packed" => "Siap/Packing","shipted" => "Dikirim", "received" => "Diterima");

                                if($page!=""){
                                  $counter = ($page-1)*$limit;
                                }

                                if($data_production){
                                  foreach ($data_production as $key => $value) {
                                    $counter++;
                                    ?>
                                    <tr class="<?=($value->status=="received")?"success":""?>">
                                      <td><?=$counter?></td>
                                      <td><?=$value->production_code?></td>
                                      <td><?=date("Y-m-d",$value->time_created)?></td>
                                      <td><?=$value->product_name?> [<?=$value->product_code?>]</td>
                                      <td><?=number_format($value->production_quantity,0,",",".")?></td>
                                      <td><?=$value->admin_name?></td>
                                      <td><?=number_format($value->penggunaan,0,",",".")?></td>
                                    </tr>
                                    <?php
                                  }
                                }
                              ?>
                            </tbody>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                  </div>

                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h5>Penggunaan Item Produksi (Packing)</h5>
                      </div>
                      <div class="ibox-content">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Transaksi</th>
                                <th>Kode Packing</th>
                                <th>Tgl Packing</th>
                                <th>Quantity</th>
                                <th>Admin Packing</th>
                                <th>Packing Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              if($usages){
                                $counter = 0;
                                foreach ($usages as $key => $value) {
                                  $counter++;

                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->cart_code?></td>
                                    <td><?=$value->packing_code?></td>
                                    <td><?=$value->tgl_packing?></td>
                                    <td><?=$value->quantity?></td>
                                    <td><?=$value->admin_name?></td>
                                    <td><?=$value->packing_status?> <?=($value->courier_name!="")?"pada <strong>".date("d M Y H:i",$value->packing_time)."</strong> oleh <strong>".$value->courier_name."</strong>":""?></td>
                                  </tr>
                                  <?php
                                }
                              }
                            ?>
                          </tbody>
                        </table>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>
