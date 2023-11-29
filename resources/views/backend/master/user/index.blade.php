<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

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
                <h2>Accounts</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Accounts</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
          <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['resume'])){?>

            <div class="row">
              <div class="col-lg-12" style="margin-bottom:15px;">
                <div class="input-group">
                  <input type="text" class="form-control-sm form-control" id="date" name="dates" value="<?=implode(" - ",@$range)?>" placeholder="Range data">
                  <div class="input-group-addon">
                    <button type="button" class="btn btn-secondary" onclick="setRange()">Set data range <i class="fa fa-angle-right"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-md-6 mt-2">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right">accounts</span>
                          <h5>Total Registered</h5>
                      </div>
                      <div class="ibox-content" id="sum-registered">
                        <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right">accounts</span>
                          <h5>Total Activated</h5>
                      </div>
                      <div class="ibox-content" id="sum-activated">
                        <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right">growth</span>
                          <h5>Growth last month</h5>
                      </div>
                      <div class="ibox-content" id="sum-growth">
                        <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right">activated account</span>
                          <h5>Total New Account</h5>
                      </div>
                      <div class="ibox-content" id="sum-monthly">
                        <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right">per data range</span>
                          <h5>Total Reach</h5>
                      </div>
                      <div class="ibox-content" id="sum-reach">
                        <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right">per data range</span>
                          <h5>Total Visitor</h5>
                      </div>
                      <div class="ibox-content" id="sum-visitor">
                        <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right">per data range</span>
                          <h5>Total Interest</h5>
                      </div>
                      <div class="ibox-content" id="sum-reader">
                        <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right">per data range</span>
                          <h5>Total Acquisition</h5>
                      </div>
                      <div class="ibox-content" id="sum-acquisition">
                        <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                      </div>
                  </div>
              </div>

            </div>
          <?php } ?>
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Accounts</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-user-create")){?>
                        <div class="ibox-tools">
                            <a class="btn btn-secondary btn-sm" href="{{ url('admin/user/create') }}">
                                <i class="fa fa-plus"></i> Add Account
                            </a>
                        </div>
                        <?php } ?>

                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['mass_activation'])){?>
                        <div class="ibox-tools">
                            <a class="btn btn-secondary btn-danger btn-sm text-white confirm" data-url="{{ url('admin/user/activate_group') }}">
                                <i class="fa fa-check"></i> Activate Group Account
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
                                                <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . $key . '&shortmode=' . @$shortmode.'&range='.implode(" - ",@$range)) ?>"><?= $val ?></a>
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
                                    <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (@$shortmode == "asc") ? "A to Z / Lowest to Highest / Old to New" : "Z to A / Highest to Lowest / New to Old" ?> <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                      <li class="<?= ("asc" == Request::input("shortmode")) ? "active" : "" ?>">
                                          <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=asc'.'&range='.implode(" - ",@$range)) ?>">A to Z / Lowest to Highest/ Old to New</a>
                                      </li>
                                      <li class="<?= ("desc" == Request::input("shortmode")) ? "active" : "" ?>">
                                          <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=desc'.'&range='.implode(" - ",@$range)) ?>">Z to A / Highest to Lowest / New to Old</a>
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
                                <th>First Name</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Registered</th>
                                <th>Department</th>
                                <!--<th>Kode Referral</th>-->
                                <!--<th>Alamat</th>-->
                                <th>Status</th>
                                <th>Update</th>
                                <th>Joined</th>
                                <th>Reach</th>
                                <th>Visitor</th>
                                <th>Interest</th>
                                <th>Acquisition</th>
                                <th>Downline</th>
                                <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $tipeuser = ['0' => 'External', '1' => 'Internal (Bergaji)'];
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
                                    <td> 
                                        <?=($value->custom_field_1!="")?"<strong>[".$value->custom_field_1."]</strong> ":""?>
                                        <?=$value->first_name?>
                                    </td>
                                    <!--<td><?=@$tipeuser[$value->tipe_user]?></td>-->
                                    <td><?=$value->phone?></td>
                                    <td><?=$value->gender?></td>
                                    <td><?=$value->email?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->date_created)?></td>
                                    <td><?=($value->name!="")?$value->name:"tidak ditemukan"?></td>
                                    <!--<td><?=($value->affiliate_code!="")?$value->affiliate_code:"tidak memiliki"?></td>-->
                                    <!--<td><?=($value->address!="")?$value->address:"belum terisi"?></td>-->
                                    <td <?=($value->status=="active")?"class='text-info'":""?>><?=$value->status?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                    <td><?=number_format($value->total_joined)?></td>
                                    <td><?=number_format($value->total_reach)?></td>
                                    <td><?=number_format($value->total_visit)?></td>
                                    <td><?=number_format($value->total_read)?></td>
                                    <td><?=number_format($value->total_acquisition)?></td>
                                    <td><?=number_format(@$value->total_downline)?></td>
                                    <td class="nowrap">
                                      <div class="btn-group" role="group">
                                          <button type="button" class="btn dropdown-toggle btn-xs btn-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <i class="glyphicon glyphicon-option-horizontal"></i>
                                          </button>
                                          <ul class="dropdown-menu">
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit'])){?>
                                        <li>
                                          <a href="{{ url('admin/user/edit/'.$value->id_user) }}" class=""><i class="fa fa-paste"></i> edit</a>
                                        </li>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['view'])){?>
                                          <li>
                                            <a href="{{ url('admin/user/detail/'.$value->id_user) }}" class=""><i class="fa fa-eye"></i> detail</a>
                                          </li>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['performance'])){?>
                                          <li>
                                            <a href="{{ url('admin/user/performance/'.$value->id_user) }}" class=""><i class="fa fa-chart-pie"></i> performance</a>
                                          </li>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                          <li>
                                            <a href="{{ url('admin/user/manage/'.$value->id_user) }}" class=""><i class="fa fa-cogs"></i> restrictions</a>
                                          </li>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove'])){?>
                                          <li>
                                            <a data-id="{{ $value->id_user }}" data-url="{{ url('admin/user/remove/') }}" class="confirm"><i class="fa fa-trash"></i> remove</a>
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

<script>
  var dataset = [
    "sum-registered","sum-activated","sum-growth","sum-monthly","sum-reach","sum-visitor","sum-reader","sum-acquisition"
  ];
  var datasetunit = [
    "Account","Account","From activated Last month","Last 1 month","unique URL shared","unique audiens","unique interested audiens","unique sales"
  ];

  function getData(setdata){
    var item = setdata[0];
    if(dataset.length > 0){
      $.ajax({
          url: '{{ url("api/user-resume") }}',
          type:"GET",
          dataType: 'json',
          data:{keyword:item, id_penerbit:<?=(@$login->id_brand!="")?$login->id_brand:"0"?>,id_user:<?=$login->id_user?>, range: <?=json_encode($range)?>}
      })
      .done(function(result){

        var html = "<h1 class='no-margins'>"+(result.total)+"</h1><div class='stat-percent font-bold text-info'>"+result.percent+"</div><small>"+datasetunit[0]+"</small>";
        $("#"+item).html(html);

        dataset.splice(0,1);
        datasetunit.splice(0,1);
        getData(dataset);
      })
      .fail(function(e){
        console.log(e);
      })
      .always(function(e){

      });
    }
  }
  $(document).ready(function() {
    getData(dataset);
  });

  $('#date').daterangepicker({
    locale: {
      format: 'DD-MM-YYYY',
    }
  });

  function setRange(){
    var range = $("#date").val();
    document.location = "<?=url('admin/user/?range=')?>"+range;
  }
</script>
