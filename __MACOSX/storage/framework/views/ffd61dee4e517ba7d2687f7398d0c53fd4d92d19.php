<?php

use Illuminate\Support\Facades\Request;

$main_url = $config['main_url'];

//   linktype
$linktype = [
    "1" => "None",
    "2" => "Internal",
    "3" => "Eksternal"
];


?>
<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg">
        <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Banner</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Banner</strong>
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
                            <h5>Tabel Data Banner</h5>

                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['create'])) { ?>
                                <div class="ibox-tools">
                                    <a class="btn btn-secondary add-btn btn-sm tooltips" href="<?php echo e(url($main_url.'/create')); ?>" title="Tambah data">
                                        <i class="fa fa-plus"></i> Tambah
                                    </a>
                                </div>
                            <?php } ?>
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
                                                        <a href="<?= url('master/banner/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . $key . '&shortmode=' . @$shortmode) ?>"><?= $val ?></a>
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
                                                    <a href="<?= url('master/banner/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=asc') ?>">A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru</a>
                                                </li>
                                                <li class="<?= ("desc" == Request::input("shortmode")) ? "active" : "" ?>">
                                                    <a href="<?= url('master/banner/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=desc') ?>">Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama</a>
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
                                            <th>Gambar</th>
                                            <th>Judul</th>
                                            <th>Deskripsi</th>
                                            <th>Tgl Daftar</th>
                                            <th>Status</th>
                                            <th>Type</th>
                                            <th>Tgl Diperbarui</th>
                                            <th style="width:380px;">Aksi</th>
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
                                        ?>
                                                <tr>
                                                    <td><?= $counter ?></td>
                                                    <td>
                                                        <img src="<?= url(str_replace("public", "", $value->banner_path)); ?>" class="img-fluid" style="max-height: 40px" alt="">
                                                    </td>
                                                    <td><?= $value->title ?></td>
                                                    <td><?= $value->description ?></td>
                                                    <td><?= date("Y-m-d H:i:s", $value->time_created) ?></td>
                                                    <td <?= ($value->status == "active") ? "class='text-info'" : "class='text-danger'" ?>><?= $value->status ?></td>
                                                    <td><?= $linktype[$value->link_type]; ?></td>
                                                    <td><?= date("Y-m-d H:i:s", $value->last_update) ?></td>
                                                    <td>
                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['edit'])) { ?>
                                                            <a href="<?php echo e(url('master/banner/edit/'.$value->id_banner)); ?>" class="btn btn-primary btn-rounded dim btn-xs tooltips" title="ubah data <?= $value->title ?>" data-placement="top"><i class="fa fa-paste"></i> ubah</a>
                                                        <?php } ?>

                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['view'])) { ?>
                                                            <a href="<?php echo e(url('master/banner/detail/'.$value->id_banner)); ?>" class="btn btn-white btn-outline dim btn-xs tooltips" title="lihat detail data <?= $value->title ?>"><i class="fa fa-eye"></i> lihat</a>
                                                        <?php } ?>
                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['manage'])) { ?>
                                                            <a href="<?php echo e(url('master/banner/manage/'.$value->id_banner)); ?>" class="btn btn-white btn-outline dim btn-xs tooltips" title="kelola kecamatan <?= $value->title ?>"><i class="fa fa-cogs"></i> kelola</a>
                                                        <?php } ?>

                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['remove'])) { ?>
                                                            <a data-id="<?php echo e($value->id_banner); ?>" data-url="<?php echo e(url('master/banner/remove/' . $value->id_banner)); ?>" class="btn btn-danger btn-outline dim btn-xs confirm tooltips" title="hapus data <?= $value->title ?>"><i class="fa fa-trash"></i> hapus</a>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/master/banner/index.blade.php ENDPATH**/ ?>