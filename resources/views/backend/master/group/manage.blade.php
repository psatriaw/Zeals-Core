<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Group User</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Group User</a>
                    </li>
                    <li class="active">
                        <strong>Manage Group User</strong>
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
                      <p>Registered: <?=date("Y-m-d H:i",$data->time_created)?> | Last Update <?=date("Y-m-d H:i", $data->last_update)?></p>
                    </div>

                    <a class="btn btn-white btn-sm margin-bottom-10" href="{{ url('admin/group') }}">
                        <i class="fa fa-angle-left"></i> Back
                    </a>

                    <div class="ibox-title">
                        <h5>Methods</h5>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        {!! Form::open(['url' => url('admin/group/updatemanage/'.$data->id_department), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>

                                <th>Method</th>
                                <th>Description </th>
                                <th>Grant Permission</th>
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
                                        Give Access
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
                            <a class="btn btn-white pull-left" href="{{ url($main_url) }}">
                                <i class="fa fa-angle-left"></i> Back
                            </a>

                            <button class="btn btn-primary pull-right btn-rounded" type="submit">Save Changes</button>
                          </div>

                        {!! Form::close() !!}
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
