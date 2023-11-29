<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <div id="page-wrapper" class="gray-bg">
    <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Hak Akses Group</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo e(url($main_url)); ?>">Group Pengguna</a>
                    </li>
                    <li class="active">
                        <strong>Manage Hak Akses Group</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content margin-bottom-20">
                      <h3>Group: <?=$data->name?></h3>
                      <p>Terdaftar: <?=date("Y-m-d H:i",$data->time_created)?> | Diperbarui <?=date("Y-m-d H:i", $data->last_update)?></p>
                    </div>

                    <a class="btn btn-white btn-sm margin-bottom-10" href="<?php echo e(url('admin/group')); ?>">
                        <i class="fa fa-angle-left"></i> kembali
                    </a>

                    <div class="ibox-title">
                        <h5>Daftar Method</h5>
                    </div>
                    <div class="ibox-content">
                      <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <div class="table-responsive">
                        <?php echo Form::open(['url' => url('admin/group/updatemanage/'.$data->id_department), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>

                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>

                                <th>Method</th>
                                <th>Deskripsi</th>
                                <th>Beri Akses</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;
                              $premodule = 0;

                              if($datamethods){
                                foreach ($datamethods as $key => $value) {
                                  $counter++;
                                  if($premodule==0){
                                    $premodule = $value->id_module;
                                    ?>
                                    <tr>
                                      <th colspan="3" class="bg-primary"><strong><?=strtoupper($value->module_name)?></strong></th>
                                    </tr>
                                    <?php
                                    $counter = 1;
                                  }elseif($value->id_module!=$premodule){
                                    $premodule = $value->id_module;
                                    ?>
                                    <tr>
                                      <th colspan="3" class="bg-primary"><strong><?=strtoupper($value->module_name)?></strong></th>
                                    </tr>
                                    <?php
                                    $counter = 1;
                                  }
                                  ?>
                                  <tr>
                                    <td><?=@$value->method?></td>
                                    <td><?=$value->description?></td>
                                    <td>
                                      <label for="cb_<?=$value->id_method?>">
                                        <input id="cb_<?=$value->id_method?>" type="checkbox" name="cb_<?=$value->id_method?>" value="<?=$value->id_method?>" <?=($value->granted!="")?"checked":""?>/>
                                        Beri akses
                                      </label>
                                    </td>
                                  </tr>
                                  <?php
                                }
                              }
                            ?>
                          </tbody>
                          </tfoot>
                        </table>

                          <div>
                            <a class="btn btn-white pull-left" href="<?php echo e(url($main_url)); ?>">
                                <i class="fa fa-angle-left"></i> kembali
                            </a>

                            <button class="btn btn-primary pull-right btn-rounded" type="submit">Simpan</button>
                          </div>

                        <?php echo Form::close(); ?>

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
<?php /**PATH /home/zealsasi/new.zeals.asia/resources/views/backend/master/group/manage.blade.php ENDPATH**/ ?>