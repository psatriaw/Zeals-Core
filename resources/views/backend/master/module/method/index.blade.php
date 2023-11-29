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
                    <li>
                        <a href="{{ url($main_url) }}">Modules</a>
                    </li>
                    <li class="active">
                        <strong>Manage Module "<?=$detail->module_name?>"</strong>
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
                        <h5>Methods</h5>

                        <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-module-create")){?>
                        <div class="ibox-tools">
                            <a class="btn btn-secondary btn-sm" href="{{ url('admin/module/method/create/'.$parent_id) }}" style="margin-top:-5px;">
                                <i class="fa fa-plus"></i> Add Method
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Method Code</th>
                                <th>Decription</th>
                                <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;
                              if(count($data)>0){
                                foreach ($data as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->method?></td>
                                    <td><?=$value->description?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-module-edit")){?>
                                          <a href="{{ url('admin/module/method/edit/'.$parent_id.'/'.$value->id_method) }}" class="btn btn-primary btn-rounded dim btn-xs"><i class="fa fa-paste"></i> edit</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-module-remove")){?>
                                          <a data-id="{{ $value->id_method }}" data-url="{{ url('admin/module/method/remove/'.$parent_id) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> remove</a>
                                      <?php }?>
                                    </td>
                                  </tr>
                                  <?php
                                }
                              }else{
                                ?><tr><td colspan="4">There is no data here</td></tr><?php
                              }
                            ?>
                          </tbody>
                          </tfoot>
                        </table>
                      </div>
                      <a class="btn btn-white btn-sm" href="{{ url($main_url) }}">
                          <i class="fa fa-angle-left"></i> Back
                      </a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>
