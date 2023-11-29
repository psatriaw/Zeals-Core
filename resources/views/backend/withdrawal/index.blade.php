<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Withdrawals</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Withdrawals</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
          <?php if($login->id_department!="1"){ ?>
          <div class="row">
              <div class="col-lg-4">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-primary pull-right"></span>
                          <h5>Your Balance</h5>
                      </div>
                      <div class="ibox-content">
                          <h1 class="no-margins"><?=number_format($balance,2)?> </h1>
                          <small>USD</small>
                      </div>
                  </div>
              </div>

              <div class="col-lg-4">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-primary pull-right"></span>
                          <h5>Withdrawn Money</h5>
                      </div>
                      <div class="ibox-content">
                          <h1 class="no-margins"><?=number_format($withdrawn,2)?></h1>
                          <small>USD</small>
                      </div>
                  </div>
              </div>
              <div class="col-lg-4">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <span class="label label-info pull-right"></span>
                          <h5>Outstanding Money</h5>
                      </div>
                      <div class="ibox-content">
                          <h1 class="no-margins"><?=number_format($outstanding,2)?></h1>
                          <small>USD</small>
                      </div>
                  </div>
              </div>
          </div>
        <?php } ?>
        <div class="row">

          <div class="col-lg-3 col-md-6">
              <div class="ibox float-e-margins">
                  <div class="ibox-title">
                      <span class="label label-info pull-right">requests</span>
                      <h5>Total Request</h5>
                  </div>
                  <div class="ibox-content" id="sum-request">
                    <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                  </div>
              </div>
          </div>

          <div class="col-lg-3 col-md-6">
              <div class="ibox float-e-margins">
                  <div class="ibox-title">
                      <span class="label label-info pull-right">request</span>
                      <h5>Total Outstanding</h5>
                  </div>
                  <div class="ibox-content" id="sum-outstanding">
                    <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                  </div>
              </div>
          </div>

          <div class="col-lg-3 col-md-6">
              <div class="ibox float-e-margins">
                  <div class="ibox-title">
                      <span class="label label-info pull-right">requests</span>
                      <h5>Total New Request</h5>
                  </div>
                  <div class="ibox-content" id="sum-new-request">
                    <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                  </div>
              </div>
          </div>


        </div>

            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Withdrawals</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,"withdrawal-create")){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="{{ url($config['main_url'].'/create') }}">
                                <i class="fa fa-plus"></i> Add Request
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
                                    <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (Request::input("shortmode") == "asc") ? "A to Z / Lowest to Highest / Old to New" : "Z to A / Highest to Lowest / New to Old" ?> <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li class="<?= ("asc" == Request::input("shortmode")) ? "active" : "" ?>">
                                            <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=asc') ?>">A to Z / Lowest to Highest / Old to New</a>
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
                              <input type="hidden" name="page" value="<?=@$input['page']?>">
                              <input type="hidden" name="short" value="<?=@$input['short']?>">
                              <input type="hidden" name="shortmode" value="<?=@$shortmode?>">
                              <input type="text" placeholder="Search" class="input-sm form-control" name="keyword" value="<?= @$input['keyword'] ?>"  style="height:36px;">
                              <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-search"> Search</button>
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
                                <th>Withdrawal Code</th>
                                <th>Account</th>
                                <th>Amount</th>
                                <th>Bank Account</th>
                                <th>Bank Account Name</th>
                                <th>Bank</th>
                                <th>Request time</th>
                                <th>Status</th>
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
                                    <td><?=$value->withdrawal_code?></td>
                                    <td><?=$value->first_name?></td>
                                    <td>Rp. <?=number_format($value->total_pencairan,0)?></td>
                                    <td><?=$value->nomor_rekening?></td>
                                    <td><?=$value->nama_bank?></td>
                                    <td><?=$value->nama_pemilik_rekening?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td class="<?=($value->status=="approved")?"text-success":""?> <?=($value->status=="gagal")?"text-danger":""?>"><?=$value->status?></td>
                                    <td>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['approve'])){?>
                                            <?php if($value->status=="approved" || $value->status=="completed"){?>
                                              <?php if($value->trx_status=="completed"){ ?>
                                                Fund Released
                                              <?php }else{ ?>
                                                <?=$value->trx_status?>
                                              <?php }?>
                                            <?php }elseif($value->status=="invalid"){ ?>
                                              <i class='fa fa-triangle-exclamation'></i> Payment Failure
                                            <?php }else{ ?>
                                                <a data-url="{{ url($config['main_url']) . '/approve/' . $value->id_withdrawal }}" class="btn btn-info btn-outline dim btn-xs confirm btn-rounded" title="approve"><i class="fa fa-check"></i> Approve/Release </a>
                                            <?php }?>
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
    "sum-request","sum-outstanding","sum-new-request"
  ];
  var datasetunit = [
    "Transaction","IDR","Requests"
  ];

  function getData(setdata){
    var item = setdata[0];
    if(dataset.length > 0){
      $.ajax({
          url: '{{ url("api/withdrawal-resume") }}',
          type:"GET",
          dataType: 'json',
          data:{keyword:item, id_penerbit:<?=(@$login->id_brand!="")?$login->id_brand:"0"?>}
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
</script>
