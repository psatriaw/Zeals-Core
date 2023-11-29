<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg">
        <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Video Tutorial</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Video Tutorial</strong>
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
                            <h5>Tabel Data Video Tutorial</h5>
                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['create'])) { ?>
                                <div class="ibox-tools">
                                    <a class="btn btn-secondary btn-sm" href="<?php echo e(url('master/tutorial/create')); ?>">
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
                                            <th>Video</th>
                                            <th>Judul</th>
                                            <th>Tgl. Dibuat</th>
                                            <th>Tgl. Update</th>
                                            <th>Aksi</th>
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
                                                    <td><?= $counter ?></td>
                                                    <td>
                                                        <a href="<?= $value->url_video ?>" target="_blank">
                                                            <img class="img-fluid" style="max-height: 40px;" src="<?= 'http://i3.ytimg.com/vi/' . $value->video_code . '/hqdefault.jpg' ?>" onclick="<?= 'http://i3.ytimg.com/vi/' . $value->vide_code . '/hqdefault.jpg' ?>">
                                                        </a>
                                                    </td>
                                                    <td> <?php echo e($value->title); ?> </td>
                                                    <td><?= date("Y-m-d H:i:s", $value->time_created) ?></td>
                                                    <td><?= date("Y-m-d H:i:s", $value->last_update) ?></td>
                                                    <td>
                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['edit'])) { ?>
                                                            <a href="<?php echo e(url('master/tutorial/edit/'.$value->id_video)); ?>" class="btn btn-primary btn-rounded dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                                        <?php } ?>

                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['view'])) { ?>
                                                            <a href="<?php echo e(url('master/tutorial/detail/'.$value->id_video)); ?>" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-eye"></i> detail</a>
                                                        <?php } ?>

                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['remove'])) { ?>
                                                            <a data-id="<?php echo e($value->id_video); ?>" data-url="<?php echo e(url('master/tutorial/remove/' . $value->id_video)); ?>" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
<?php /**PATH /home/zealsasi/new.zeals.asia/resources/views/backend/master/tutorial/index.blade.php ENDPATH**/ ?>