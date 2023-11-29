<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Stock Resep</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Stock Resep</a>
                    </li>
                    <li class="active">
                        <strong>Stock Tersedia untuk Resep "<?=$detail->naikan_rumus_name?>"</strong>
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
                          <h5>Stock Resep Siap Pakai "<?=$detail->naikan_rumus_name?>"</h5>
                      </div>
                      <div class="ibox-content">
                        @include('backend.flash_message')
                        <div class="alert alert-info">
                          List dibawah ini hanya merupakan stock yang tersedia/belum digunakan untuk pemenuhan permintaan PPIC.
                        </div>
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                            <thead>
                              <tr>
                                  <th>No.</th>
                                  <th>Kode Stock</th>
                                  <th>Kode Produksi</th>
                                  <th>Waktu Produksi</th>
                                  <th>Expirasi</th>
                                  <th>HPP</th>
                                  <th>Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $counter = 0;

                                $product_status = array('active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'available' =>'Tersedia','unavailable' => 'Tidak Tersedia');
                                $now = time();
                                if(@$data){
                                  foreach ($data as $key => $value) {
                                    $counter++;
                                    $stock_date = strtotime($value->stock_date);
                                    if((time() - $stock_date)>86400){
                                      $sisatime = ((time() - $stock_date)%86400);
                                      $sisa = $expirasi - ((time() - $stock_date - $sisatime)/86400);
                                    }else{
                                      $sisa = $expirasi;
                                    }
                                    ?>
                                    <tr>
                                      <td><?=$counter?></td>
                                      <td><?=$value->stock_unit_code?></td>
                                      <td><?=@$value->production_code?></td>
                                      <td><?=@$value->stock_date?></td>
                                      <td><?=$sisa?> hari</td>
                                      <td class="text-right">Rp. <?=number_format($value->hpp,2,",",".")?></td>
                                      <td>
                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                            <a href="{{ url($main_url.'/manage/'.@$detail->id_naikan_rumus.'/use-item/'.$value->id_unit) }}" class="btn btn-primary dim btn-xs"> gunakan <i class="fa fa-angle-right"></i></a>
                                        <?php }?>

                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                            <a href="{{ url($main_url.'/manage/'.$detail->id_naikan_rumus.'/restock/'.$value->id_unit) }}" class="btn btn-info btn-outline dim btn-xs"> perbarui <i class="fa fa-reply"></i></a>
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
