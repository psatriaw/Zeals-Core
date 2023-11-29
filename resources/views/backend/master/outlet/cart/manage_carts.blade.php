<style>
  .fa{
    width:16px;
  }
</style>
<?php
  $main_url = $config['main_url'];
  $statuses = array(
    "pending"       => "<span class='text-danger'><i class='fa fa-exclamation'></i> Pending</span>",
    "disetujui"     => "<span class='text-info'><i class='fa fa-hourglass'></i> Proses Pengiriman</span>",
    "dikirim"       => "<span class='text-info'><i class='fa fa-truck'></i> Dikirim</span>",
    "selesai"       => "<span class='text-success'><i class='fa fa-check'></i> Selesai</span>",
  );
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Permintaan Outlet Tanggal <?=$date?></h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Permintaan</strong>
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
                        <h5>Tabel Data Permintaan</h5>
                        
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="{{ url($main_url.'/manage_material/'.$date) }}">
                                <i class="fa fa-plus"></i> kelola sesuai bahan baku
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
                                <th>Kode Permintaan</th>
                                <th>Outlet</th>
                                <th>Author</th>
                                <th>Waktu Permintaan</th>
                                <th>Status</th>
                                <th>Tgl Diperbarui</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;

                              $product_status = array('active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'available' =>'Tersedia','unavailable' => 'Tidak Tersedia');

                              //if($page!=""){
                              //    $counter = ($page-1)*$limit;
                              //}

                              if($data){
                                foreach ($data as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->cart_code?></td>
                                    <td><?=@$value->mitra_name?></td>
                                    <td><?=$value->first_name?></td>
                                    <td class="text-right"><?=date("H:i:s",@$value->time_created)?></td>
                                    <td><?=$statuses[$value->status]?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                          <a href="{{ url($main_url.'/manage/'.$value->id_cart) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-cogs"></i> kelola</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove'])){?>
                                          <a data-id="{{ $value->id_cart }}" data-url="{{ url($main_url.'/remove/trx/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
                </div>
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
          <br><br>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>
