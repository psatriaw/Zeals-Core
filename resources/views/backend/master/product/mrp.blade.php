<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Produk</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li class="active">
                        <strong>Bahan Baku Produksi</strong>
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
                          <h5>Tabel Data Bahan Baku Produk "<?=$data->product_name?>"</h5>
                          <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['create']) && $previlege->isAllow($login->id_user,$login->id_department,$config['mrp'])){?>
                          <div class="ibox-tools">
                              <a class="btn btn-primary btn-sm" href="{{ url($main_url.'/mrp/'.$data->id_product.'/create') }}">
                                  <i class="fa fa-plus"></i> tambah bahan baku
                              </a>
                          </div>
                          <?php } ?>
                      </div>
                      <div class="ibox-content">
                        @include('backend.flash_message')
                        <div class="alert alert-info">
                          Bahan baku berikut adalah untuk produksi setiap 1 unit/satuan produk.
                        </div>
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                            <thead>
                              <tr>
                                  <th>No.</th>
                                  <th>Nama Bahan</th>
                                  <th>Kode</th>
                                  <!--<th>Kategori</th>-->
                                  <th>Quantity</th>
                                  <th>Unit</th>
                                  <th>Biaya</th>
                                  <th>Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $counter = 0;

                                $product_status = array('active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'available' =>'Tersedia','unavailable' => 'Tidak Tersedia');

                                if(@$list){
                                  foreach ($list as $key => $value) {
                                    $counter++;
                                    ?>
                                    <tr>
                                      <td><?=$counter?></td>
                                      <td><?=$value->material_name?></td>
                                      <td><?=$value->material_code?></td>
                                      <!--<td><?=$value->category_name?></td>-->
                                      <td><?=$value->qty?></td>
                                      <td><?=$value->material_unit?></td>
                                      <td class="text-right">Rp.<?=number_format(($value->material_price * $value->qty),0,",",".")?></td>
                                      <td>
                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit']) && $previlege->isAllow($login->id_user,$login->id_department,$config['mrp'])){?>
                                            <a href="{{ url($main_url.'/mrp/'.$data->id_product.'/edit/'.$value->id_mrp) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                        <?php }?>

                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove']) && $previlege->isAllow($login->id_user,$login->id_department,$config['mrp'])){?>
                                            <a data-id="{{ $value->id_mrp }}" parent-id="{{ $data->id_product }}" data-url="{{ url($main_url.'/mrp/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                        <?php }?>
                                      </td>
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
                      <div class="row">
                        <div class="form-group top15">
                            <div class="col-sm-6">
                                <a class="btn btn-white" href="{{ url($main_url) }}">
                                    <i class="fa fa-angle-left"></i> kembali
                                </a>
                            </div>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>
