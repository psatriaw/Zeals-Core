<?php

use Illuminate\Support\Facades\Request;

$main_url = $config['main_url'];

//   linktype
$trxstatus = [
    "paid" => "text-info",
    "unpaid" => "text-danger"
];


?>
<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg">
        <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>TopUp</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Top Up</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
              <div class="col-lg-4">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right">rupiah</span>
                          <h5>Total Topup</h5>
                      </div>
                      <div class="ibox-content">
                          <h1 class="no-margins">Rp.<?=number_format(@$total_paid_topup,0)?></h1>
                          <div class="stat-percent font-bold text-info">

                          </div>
                          <small>topup</small>
                      </div>
                  </div>
              </div>

              <div class="col-lg-4">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-danger pull-right">transaksi</span>
                          <h5>Total Gagal Transaksi</h5>
                      </div>
                      <div class="ibox-content">
                          <h1 class="no-margins"><?=number_format(0,0)?> x</h1>
                          <div class="stat-percent font-bold text-info">

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
                            <h5>Top Up</h5>
                        </div>
                        <div class="ibox-content">
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <?php
                            $shortmode = @$input['shortmode'];
                            if ($shortmode == "") {
                                $shortmode = $default['shortmode'];
                            }
                            ?>

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
                                      <input type="text" placeholder="Search" class="input-sm form-control" name="keyword" value="<?= @$input['keyword'] ?>">
                                      <span class="input-group-btn">
                                        <button type="submit" class="btn btn-sm btn-search"> Cari</button>
                                      </span>
                                    </div>
                                  </form>

                                    <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['create'])) { ?>
                                        <div class="ibox-tools">
                                            <a class="btn btn-primary add-btn btn-sm tooltips" href="<?php echo e(url($main_url.'/create')); ?>" title="Tambah data">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Kode Trx</th>
                                            <th>Akun</th>
                                            <th>Deskripsi</th>
                                            <th>Jenis Trx</th>
                                            <th>Status</th>
                                            <th>Total Topup</th>
                                            <th>Total Bayar</th>
                                            <th>Tgl Dibuat</th>
                                            <th>Diperbarui</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 0;
                                        if ($page != "") {
                                            $counter = ($page - 1) * $limit;
                                        }

                                        if ($data) {
                                            foreach ($data as $key => $value) {
                                                $counter++;
                                                if($value->trx_action=="manual"){
                                                  $value->trx_action = "Dibantu Staff";
                                                }else{
                                                  $value->trx_action = "Virtual Account";
                                                }

                                                ?>
                                                <tr>
                                                    <td><?= $counter ?></td>
                                                    <td><?= $value->trx_code ?></td>
                                                    <td><?= $value->first_name ?> (<?=$value->email?>)</td>
                                                    <td><?= $value->description ?></td>
                                                    <td><?= $value->trx_action ?></td>
                                                    <td class="<?= $trxstatus[$value->status] ?>"><?= $value->status ?></td>
                                                    <td class="text-right">Rp. <?= number_format($value->kredit,0) ?></td>
                                                    <td class="text-right">Rp. <?= number_format($value->trx_amount,0) ?></td>
                                                    <td><?= date("Y-m-d H:i:s", $value->time_created) ?></td>
                                                    <td><?= date("Y-m-d H:i:s", $value->last_update) ?></td>
                                                    <td>
                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['approve']) && $value->status != 'paid') { ?>
                                                            <a data-id="<?php echo e($value->id_transaksi); ?>" data-url="<?php echo e(url('master/topup/approve/' . $value->id_transaksi)); ?>" class="btn btn-primary btn-outline dim btn-xs confirm tooltips text-white btn-rounded" title="approve data <?= $value->trx_code ?>">Approve</a>
                                                        <?php }else{ ?>
                                                            <a class="btn btn-primary btn-outline dim btn-xs confirm tooltips text-white disabled btn-rounded" disabled title="approve data <?= $value->trx_code ?>">Approve</a>
                                                        <?php } ?>

                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['view'])) { ?>
                                                            <a href="<?php echo e(url('master/topup/detail/' . $value->id_transaksi)); ?>" class="btn btn-white btn-outline dim btn-xs" title="detail data <?= $value->trx_code ?>">Detail</a>
                                                        <?php }else{ ?>
                                                            <a class="btn btn-white btn-outline dim btn-xs disabled" disabled title="detail data <?= $value->trx_code ?>">Detail</a>
                                                        <?php } ?>
                                                    </td>
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
<script>
    $(document).ready(function() {
        $(".tooltips").tooltip();
    });
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/master/topup/index.blade.php ENDPATH**/ ?>