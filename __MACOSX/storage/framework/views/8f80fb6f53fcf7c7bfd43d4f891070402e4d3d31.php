<?php

$main_url = $config['main_url'];
$statuses = array(
  "approved" => "<label class='label label-success'>diterima</label>",
  "rejected" => "<label class='label label-danger'>ditolak</label>",
  "pending"  => "<label class='label label-default'>pending</label>",
);
?>
<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg">
        <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Laporan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Laporan</strong>
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
                            <h5>Tabel Laporan</h5>
                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['create'])) { ?>
                                <div class="ibox-tools">
                                    <a class="btn btn-secondary btn-sm" href="<?php echo e(url('master/laporan-campaign/create/'.@$id_penerbit.'?backlink='.$main_url)); ?>">
                                        <i class="fa fa-plus"></i> tambah
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
                                          <th>No</th>
                                          <th>Kode Laporan</th>
                                          <th>Penawaran</th>
                                          <th>Bulan</th>
                                          <th>Tahun</th>
                                          <th>Status</th>
                                          <th>Keuntungan</th>
                                          <th>Author</th>
                                          <th>Tanggal Upload</th>
                                          <th>Catatan</th>
                                          <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['manage']) || $previlege->isAllow($login->id_user, $login->id_department, $config['remove'])) { ?>
                                          <th>Aksi</th>
                                          <?php } ?>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $tipeuser = ['0' => 'External', '1' => 'Internal (Bergaji)'];
                                      $counter = 0;
                                      if ($page != "") {
                                          $counter = ($page - 1) * $limit;
                                      }

                                      if ($data) {
                                          foreach ($data as $key => $value) {
                                              $counter++;
                                      ?>
                                              <tr>
                                                  <td><?php echo e($counter); ?></td>
                                                  <td><?php echo e($value->report_code); ?></td>
                                                  <td><?php echo e($value->campaign_title); ?></td>
                                                  <td><?=$months[$value->report_month]?></td>
                                                  <td><?php echo e($value->report_year); ?></td>
                                                  <td><?=$statuses[$value->status]?></td>
                                                  <td class="text-right"><strong>Rp.<?=number_format($value->profit,0)?></strong></td>
                                                  <td><?php echo e($value->first_name); ?></td>
                                                  <td><?php echo e(date("d M Y H:i",$value->time_created)); ?></td>
                                                  <td><?php echo e($value->catatan); ?></td>
                                                  <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['manage']) || $previlege->isAllow($login->id_user, $login->id_department, $config['remove'])) { ?>
                                                  <td>
                                                      
                                                      <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['manage'])) { ?>
                                                          <a href="<?php echo e(url('master/laporan-campaign/detail/'.$value->id_report.'?backlink=master/campaign/manage/'.$value->id_campaign)); ?>" class="btn btn-primary btn-rounded dim btn-xs"><i class="fa fa-cogs"></i> kelola </a>
                                                      <?php } ?>

                                                      <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['remove'])) { ?>
                                                          <a data-id="<?php echo e($value->id_report); ?>" data-url="<?php echo e(url('master/laporan-campaign/remove/' . $value->id_report)); ?>" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                                      <?php } ?>
                                                  </td>
                                                  <?php } ?>
                                              </tr>
                                      <?php
                                          }
                                      }
                                      ?>
                                    </tbody>
                                    </tfoot>
                                </table>
                                <?= $pagging ?>
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
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/master/campaignreport/index.blade.php ENDPATH**/ ?>