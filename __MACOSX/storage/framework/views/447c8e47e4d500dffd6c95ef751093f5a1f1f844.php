<?php
  $main_url = $config['main_url'];
    $campaign_type = array(
        "banner"       => "AMP - Tracker",
        "o2o"          => "O2O - Outlet",
        "shopee"       => "AMP - Shopee"
    );
?>
<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg">
        <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Campaign</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Campaign</strong>
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
                          <span class="label label-success pull-right">running</span>
                          <h5>Total Running Campaign</h5>
                      </div>
                      <div class="ibox-content">
                          <h1 class="no-margins"><?=number_format(@$total_running)?></h1>
                          <div class="stat-percent font-bold text-success">
                              <?=number_format((@$total_running/@$total_data)*100,2)?>% campaign
                          </div>
                          <small>campaign</small>
                      </div>
                      <!--
                      <div class="ibox-footer">
                          <?php $keyword = ""; ?>
                          <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . $keyword . '&short=' . @$input['short'] . '&shortmode='.@$shortmode) ?>" class="block text-primary">lihat data <i class="fa fa-angle-right pull-right"></i></a>
                      </div>
                    -->
                  </div>
              </div>

              <div class="col-lg-4">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right">berhasil</span>
                          <h5>Total Running Campaign</h5>
                      </div>
                      <div class="ibox-content">
                          <h1 class="no-margins"><?=number_format(@$total_rejected)?></h1>
                          <div class="stat-percent font-bold text-info">
                              <?=number_format((@$total_completed/@$total_data)*100,2)?>% campaign
                          </div>
                          <small>campaign</small>
                      </div>
                  </div>
              </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Tabel Data Campaign</h5>
                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['create'])) { ?>
                                <div class="ibox-tools">
                                    <a class="btn btn-secondary btn-sm" href="<?php echo e(url('master/campaign/choose/create')); ?>">
                                        <i class="fa fa-plus"></i> create campaign
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
                                      <button type="submit" class="btn btn-sm btn-search"> Search</button>
                                    </span>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="table-responsives">
                                <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Title</th>
                                            <th>ID</th>
                                            <th>Brand</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Type</th>
                                            <th>Budget</th>
                                            <th>Running Budget</th>
                                            <th>Status</th>
                                            <th>Updated</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $runningstatus = array(
                                            "close"     => "<span class='text-danger'><i class='fa fa-ban'></i> Drafted</span>",
                                            "closed"    => "<span class='text-danger'><i class='fa fa-ban'></i> Stopped</span>",
                                            "open"      => "<span class='text-success'><i class='fa fa-check'></i> Running</span>",
                                            "expired"   => "<span class='text-danger'><i class='fa fa-ban'></i> Expired</span>",
                                            "released"  => "<span class='text-info'><i class='fa fa-check'></i> Rilis Pendanaan</span>"
                                        );

                                        $tipeuser = ['0' => 'External', '1' => 'Internal (Bergaji)'];
                                        $counter = 0;
                                        if ($page != "") {
                                            $counter = ($page - 1) * $limit;
                                        }

                                        if ($data) {
                                            foreach ($data as $key => $value) {
                                                $counter++;
                                                if($value->running_status=='open'){
                                                  if(strtotime($value->end_date)< time()){
                                                    $value->running_status = "expired";
                                                  }
                                                }

                                                if($value->release_status=="released"){
                                                  $value->running_status = 'released';
                                                }
                                                ?>
                                                <tr>
                                                    <td><?php echo e($counter); ?></td>
                                                    <td class="ellipsis"><strong>[<?php echo e($value->affiliate_id); ?>]</strong> <?php echo e($value->campaign_title); ?></td>
                                                    <td><strong><?=$value->campaign_random_code?></strong></td>
                                                    <td><?php echo e($value->nama_penerbit); ?></td>
                                                    <td><?php echo e($value->start_date); ?></td>
                                                    <td><?php echo e($value->end_date); ?></td>
                                                    <td><?php echo e(@$campaign_type[$value->campaign_type]); ?></td>
                                                    <td class="text-right">Rp.<?php echo e(number_format($value->budget)); ?></td>
                                                    <td class="text-right">Rp.<?php echo e(number_format(@$value->running_budget)); ?></td>
                                                    <td class="nowrap"><?=$runningstatus[$value->running_status]?></td>
                                                    <td><?=date("Y-m-d H:i",$value->last_update)?></td>
                                                    <td class="nowrap">
                                                        
                                                        <!--
                                                        <?php if($previlege->isAllow($login->id_user, $login->id_department, $config['view'])) { ?>
                                                            <a href="<?php echo e(url('master/campaign/detail/'.$value->id_campaign)); ?>" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-eye"></i> detail</a>
                                                        <?php } ?>
                                                        -->



                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn dropdown-toggle btn-xs btn-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="glyphicon glyphicon-option-horizontal"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <?php if($previlege->isAllow($login->id_user, $login->id_department, @$config['edit'])) { ?>
                                                                    <li>
                                                                        <a href="<?php echo e(url('master/campaign/edit/'.$value->campaign_link)); ?>" ><i class="fa fa-paste"></i> edit</a>
                                                                    </li>
                                                                <?php } ?>
                                                                    <li>
                                                                        <a href="<?php echo e(url($main_url.'/resume/' . $value->campaign_link)); ?>"  title="deactivate data <?= $value->title ?>"><i class="fa fa-cogs"></i> manage </a></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="<?php echo e(url($main_url.'/report/' . $value->campaign_link)); ?>"  title="deactivate data <?= $value->title ?>"><i class="fa fa-chart-pie"></i> report </a></a>
                                                                    </li>
                                                                <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['remove'])) { ?>
                                                                    <li>
                                                                        <a data-id="<?php echo e($value->id_campaign); ?>" data-url="<?php echo e(url('master/campaign/remove/' . $value->id_campaign)); ?>" class="confirm"><i class="fa fa-trash"></i> remove</a>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
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
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/master/campaign/index.blade.php ENDPATH**/ ?>