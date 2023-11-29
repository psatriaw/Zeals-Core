<style>
  .fa{
    width:18px;
  }
  .dropdown-menu li a{
    padding-left: 10px;
  }
</style>

<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Modules</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Modules</strong>
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
                        <h5>Modules</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-user-create")){?>
                        <div class="ibox-tools">
                            <a class="btn btn-secondary btn-sm" href="{{ url('admin/module/create') }}">
                                <i class="fa fa-plus"></i> Add Module
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="row">
                        <div class="col-sm-2">
                            <div class="input-group m-b">
                                <span class="input-group-addon">Short By</span>
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
                                    <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (Request::input("shortmode") == "asc") ? "A to Z / Lowest to Highest / Old to Newest" : "Z to A / Highest to Lowest / New to Old" ?> <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li class="<?= ("asc" == Request::input("shortmode")) ? "active" : "" ?>">
                                            <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=asc') ?>">A to Z / Lowest to Highest / Old to Newest</a>
                                        </li>
                                        <li class="<?= ("desc" == Request::input("shortmode")) ? "active" : "" ?>">
                                            <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=desc') ?>">Z to A / Highest to Lowest / New to Old</a>
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
                                    <input type="text" placeholder="Search" class="input-sm form-control" name="keyword" value="<?= @$input['keyword'] ?>" style="height:36px;">
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
                                <th>No.</th>
                                <th>Module Name</th>
                                <th>Methods</th>
                                <th>Group Module</th>
                                <th>Registered</th>
                                <th>Last Update</th>
                                <th>Action</th>
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
                                    <td><?=$value->module_name?></td>
                                    <td><?=($value->total_method!="")?$value->total_method:0?> method</td>
                                    <td><?=$value->name?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                    <td class="nowrap">
                                      <div class="btn-group" role="group">
                                          <button type="button" class="btn dropdown-toggle btn-xs btn-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <i class="glyphicon glyphicon-option-horizontal"></i>
                                          </button>
                                          <ul class="dropdown-menu">
                                            <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-module-edit")){?>
                                              <li>
                                                <a href="{{ url('admin/module/edit/'.$value->id_module) }}" class=""><i class="fa fa-paste"></i> edit</a>
                                              </li>
                                            <?php }?>

                                            <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-module-show")){?>
                                              <li>
                                                <a href="{{ url('admin/module/detail/'.$value->id_module) }}" class=""><i class="fa fa-eye"></i> detail</a>
                                              </li>
                                            <?php }?>

                                            <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-module-manage")){?>
                                              <li>
                                                <a href="{{ url('admin/module/manage/'.$value->id_module) }}" class=""><i class="fa fa-cogs"></i> manage</a>
                                              </li>
                                            <?php }?>

                                            <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-module-remove")){?>
                                              <li class="text-danger">
                                                <a data-id="{{ $value->id_module }}" data-url="{{ url('admin/module/remove/') }}" class="confirm"><i class="fa fa-trash"></i> remove</a>
                                              </li>
                                            <?php }?>
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
                        <?=$pagging?>
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
