<?php
  $main_url = $config['main_url'];
  $status   = array(
    "pending" => "<span class='text-danger'>Perencanaan <i class='fa fa-triangle'></i></span>",
    "done"    => "<span class='text-success'>Selesai <i class='fa fa-check'></i></span>"
  );
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kelola Rencana Produksi #<?=$data->production_code?> [<?=$status[$data->status]?>]</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Kelola Produksi</strong>
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
                        <h5>Kelola Rencana Produksi</h5>
                        <?php if($data->status=="pending" && $previlege->isAllow($login->id_user,$login->id_department,$config['create'])){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="{{ url($main_url.'/create/item/'.$data->id_outlet_production) }}">
                                <i class="fa fa-plus"></i> tambah item produksi
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
                                <th>Produk</th>
                                <th>Quantity</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;

                              $product_status = array('active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'available' =>'Tersedia','unavailable' => 'Tidak Tersedia');

                              if($items){
                                foreach ($items as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td style="width:80px;"><?=$counter?></td>
                                    <td>
                                        <?=@$value->product_name?>
                                    </td>
                                    <td><?=@$value->quantity?></td>
                                    <td style="width:230px;">
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit'])){?>
                                          <a href="{{ url($main_url.'/edit/'.$data->id_outlet_production.'/item/'.@$value->id_outlet_production_item) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>


                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove'])){?>
                                          <a data-id="{{ @$value->id_outlet_production_item }}" data-url="{{ url($main_url.'/remove/item/'.$data->id_outlet_production) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
                      <a class="btn btn-white" href="{{ url($main_url) }}">
                          <i class="fa fa-angle-left"></i> kembali
                      </a>

                      <?php if($data->status=="pending" && $previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){ ?>
                        <button class="btn btn-info confirm pull-right" data-id="{{ $data->id_outlet_production }}" data-url="{{ url($config['main_url'].'/process/') }}" type="button">Lakukan Produksi <i class='fa fa-angle-right'></i></button>
                      <?php } ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>
