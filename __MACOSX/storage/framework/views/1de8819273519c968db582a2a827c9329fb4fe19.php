<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <div id="page-wrapper" class="gray-bg">
    <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Pencairan Rekening Dana</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Pencairan Rekening Dana</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
          <?php if($login->id_department!="1"){ ?>
          <div class="row">
              <div class="col-lg-4">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-primary pull-right"></span>
                          <h5>Your Balance</h5>
                      </div>
                      <div class="ibox-content">
                          <h1 class="no-margins"><?=number_format($balance,2)?> </h1>
                          <small>USD</small>
                      </div>
                  </div>
              </div>

              <div class="col-lg-4">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-primary pull-right"></span>
                          <h5>Withdrawn Money</h5>
                      </div>
                      <div class="ibox-content">
                          <h1 class="no-margins"><?=number_format($withdrawn,2)?></h1>
                          <small>USD</small>
                      </div>
                  </div>
              </div>
              <div class="col-lg-4">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right"></span>
                          <h5>Outstanding Money</h5>
                      </div>
                      <div class="ibox-content">
                          <h1 class="no-margins"><?=number_format($outstanding,2)?></h1>
                          <small>USD</small>
                      </div>
                  </div>
              </div>
          </div>
        <?php } ?>
        <div class="row">

          <div class="col-lg-4">
              <div class="ibox float-e-margins">
                  <div class="ibox-title">
                      <span class="label label-info pull-right">permintaan</span>
                      <h5>Total Pencairan Baru</h5>
                  </div>
                  <div class="ibox-content">
                      <h1 class="no-margins"><?=number_format(@$total_pending)?></h1>
                      <div class="stat-percent font-bold text-info">
                          <?=number_format((@$total_pending/@$total_data)*100,2)?>% baru
                      </div>
                      <small>transaksi</small>
                  </div>
              </div>
          </div>

          <div class="col-lg-4">
              <div class="ibox float-e-margins">
                  <div class="ibox-title">
                      <span class="label label-success pull-right">permintaan</span>
                      <h5>Total Pencairan Berhasil</h5>
                  </div>
                  <div class="ibox-content">
                      <h1 class="no-margins"><?=number_format(@$total_terlaksana)?></h1>
                      <div class="stat-percent font-bold text-success">
                          <?=number_format((@$total_terlaksana/@$total_data)*100,2)?>% berhasil
                      </div>
                      <small>transaksi</small>
                  </div>
              </div>
          </div>

          <div class="col-lg-4">
              <div class="ibox float-e-margins">
                  <div class="ibox-title">
                      <span class="label label-danger pull-right">permintaan</span>
                      <h5>Total Pencairan Ditolak</h5>
                  </div>
                  <div class="ibox-content">
                      <h1 class="no-margins"><?=number_format(@$total_rejected)?></h1>
                      <div class="stat-percent font-bold text-danger">
                          <?=number_format((@$total_rejected/@$total_data)*100,2)?>% gagal
                      </div>
                      <small>transaksi</small>
                  </div>
              </div>
          </div>
        </div>

            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Pencairan Rekening Dana</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,"withdrawal-create")){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="<?php echo e(url($config['main_url'].'/create')); ?>">
                                <i class="fa fa-plus"></i> tambah pencairan
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                      <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <div class="row">
                        <div class="col-sm-2">
                            <div class="input-group m-b">
                                <span class="input-group-addon">Urutkan</span>
                                <div class="input-group-btn bg-white">
                                    <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (@$input['short'] == "") ? $shorter[$default['short']] : $shorter[$input['short']] ?> <span class="caret"></span></button>

                                    <ul class="dropdown-menu">
                                        <?php
                                        foreach ($shorter as $key => $val) {
                                        ?>
                                            <li class="<?= ($key == Request::input("short")) ? "active" : "" ?>">
                                                <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . $key . '&shortmode=' . @$shortmode) ?>"><?= $val ?></a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group m-b">
                                <span class="input-group-addon">Mode</span>
                                <div class="input-group-btn bg-white">
                                    <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (@$shortmode == "asc") ? "A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru" : "Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama" ?> <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li class="<?= ("asc" == Request::input("shortmode")) ? "active" : "" ?>">
                                            <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=asc') ?>">A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru</a>
                                        </li>
                                        <li class="<?= ("desc" == Request::input("shortmode")) ? "active" : "" ?>">
                                            <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=desc') ?>">Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama</a>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-4">
                          <form class="" role="form" method="GET" id="loginForm">
                            <div class="input-group m-b">
                              <input type="hidden" name="page" value="<?=@$input['page']?>">
                              <input type="hidden" name="short" value="<?=@$input['short']?>">
                              <input type="hidden" name="shortmode" value="<?=@$shortmode?>">
                              <input type="text" placeholder="Search" class="input-sm form-control" name="keyword" value="<?= @$input['keyword'] ?>">
                              <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-search"> Cari</button>
                              </span>
                            </div>
                          </form>
                        </div>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Akun</th>
                                <th>Total Pencairan</th>
                                <th>No. Rekening</th>
                                <th>Nama Pemilik</th>
                                <th>Bank Tujuan</th>
                                <th>Waktu Permintaan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;
                              if($page!=""){
                                $counter = ($page-1)*$limit;
                              }

                              if($data){
                                foreach ($data as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->first_name?></td>
                                    <td>Rp. <?=number_format($value->total_pencairan,0)?></td>
                                    <td><?=$value->nomor_rekening?></td>
                                    <td><?=$value->nama_bank?></td>
                                    <td><?=$value->nama_pemilik_rekening?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td class="<?=($value->status=="approved")?"text-success":""?> <?=($value->status=="gagal")?"text-danger":""?>"><?=$value->status?></td>
                                    <td>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['approve'])){?>
                                          <a data-url="<?php echo e(url($config['main_url']) . '/approve/' . $value->id_withdrawal); ?>" class="btn btn-info btn-outline dim btn-xs confirm btn-rounded" title="approve"><i class="fa fa-check"></i> Tambah Record </a>
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
                        <?=$pagging?>
                      </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    <?php echo $__env->make('backend.do_confirm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('backend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>
</div>
<?php /**PATH /home/zealsasi/new.zeals.asia/resources/views/backend/withdrawal/index.blade.php ENDPATH**/ ?>