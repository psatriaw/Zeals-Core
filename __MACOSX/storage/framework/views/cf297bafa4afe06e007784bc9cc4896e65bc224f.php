<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <div id="page-wrapper" class="gray-bg">
    <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Akun Layanan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo e(url($main_url)); ?>">Daftar Module Sistem</a>
                    </li>
                    <li class="active">
                        <strong>Manage Module</strong>
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
                        <h5>Tabel Modul System</h5>

                        <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-module-create")){?>
                        <div class="ibox-tools">
                            <a class="btn btn-secondary btn-sm" href="<?php echo e(url('admin/module/method/create/'.$parent_id)); ?>">
                                <i class="fa fa-plus"></i> tambah method
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                      <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Method</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;
                              if($data){
                                foreach ($data as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->method?></td>
                                    <td><?=$value->description?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-module-edit")){?>
                                          <a href="<?php echo e(url('admin/module/method/edit/'.$parent_id.'/'.$value->id_method)); ?>" class="btn btn-primary btn-rounded dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-module-remove")){?>
                                          <a data-id="<?php echo e($value->id_method); ?>" data-url="<?php echo e(url('admin/module/method/remove/'.$parent_id)); ?>" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
                      <a class="btn btn-white btn-sm" href="<?php echo e(url($main_url)); ?>">
                          <i class="fa fa-angle-left"></i> kembali
                      </a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    <?php echo $__env->make('backend.do_confirm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('backend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/master/module/method/index.blade.php ENDPATH**/ ?>