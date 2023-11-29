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
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Produk</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url.'/rumus/'.$data->id_product) }}"> Rumus <?=$data->product_name?></a>
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
                          <h5>Tabel Rumus Bahan Siap Pakai untuk Produk "<?=$data->product_name?>"</h5>
                          <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['create']) && $previlege->isAllow($login->id_user,$login->id_department,$config['mrp'])){?>
                          <div class="ibox-tools">
                              <a class="btn btn-primary btn-sm" href="{{ url($main_url.'/rumus/'.$data->id_product.'/create') }}">
                                  <i class="fa fa-plus"></i> tambah bahan baku
                              </a>
                          </div>
                          <?php } ?>
                      </div>
                      <div class="ibox-content">
                        @include('backend.flash_message')
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                            <thead>
                              <tr>
                                  <th>No.</th>
                                  <th>Nama Bahan Setengah Jadi</th>
                                  <th>Kode</th>
                                  <th>Quantity</th>
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
                                      <td><?=$value->naikan_rumus_name?></td>
                                      <td><?=$value->naikan_rumus_code?></td>
                                      <td><?=$value->qty?></td>
                                      <td>
                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit']) && $previlege->isAllow($login->id_user,$login->id_department,$config['rumus'])){?>
                                            <a href="{{ url($main_url.'/rumus/'.$data->id_product.'/edit/'.$value->id_product_cetakan) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                        <?php }?>

                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove']) && $previlege->isAllow($login->id_user,$login->id_department,$config['rumus'])){?>

                                          <a data-url="{{ url($main_url.'/rumus/remove') }}" data-id="<?=$value->id_product_cetakan?>" parent-id="<?=$value->id_product?>" class="btn btn-danger confirm btn-xs"><i class="fa fa-trash"></i> hapus</a>
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
