<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?php
$main_url = "";
?>
<style>
  .box-log .row{
    border-bottom: 1px solid #eee;
    margin-bottom: 10px;
    padding-bottom: 10px;
  }
  .box-log .row div{
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
</style>
<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg sidebar-content">
        <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Campaign</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('master/campaign')); ?>">Campaign</a>
                    </li>
                    <li class="active">
                        <strong>Report</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper-content">
          <div class="row form-group">
            <div class="col-sm-12">
                <a class="btn btn-white" href="<?php echo e(url('master/campaign/resume/'.$detail->campaign_link)); ?>">
                    <i class="fa fa-angle-left"></i> back
                </a>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-lg-3">
                <div class="ibox ">
                    <div class="ibox-title" style="background:#5eb6f0;color:#fff;">
                        <div class="ibox-tools">
                            <!--<span class="label label-info float-right">Bulan Ini</span>-->
                        </div>
                        <h5>Unique Visitor </h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=number_format(@$statistic['visit']['total'],0,',','.')?></h1>

                        <small>-</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox ">
                    <div class="ibox-title" style="background:#c82360;color:#fff;">
                        <div class="ibox-tools">
                            <!--<span class="label label-info float-right">Bulan Ini</span>-->
                        </div>
                        <h5>Unique Reader/Stay</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=number_format(@$statistic['read']['total'],0,',','.')?></h1>
                        <div class="stat-percent font-bold label label-info"><?=number_format(@$statistic['read']['percent'],2,',','.')?>% of visitor</div>
                        <small>-</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ibox ">
                    <div class="ibox-title" style="background:#fcb13b;color:#fff;">
                        <div class="ibox-tools">
                            <!--<span class="label label-info float-right">Bulan Ini</span>-->
                        </div>
                        <h5>Voucher Created</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=number_format(@$statistic['voucher']['total'],0,',','.')?></h1>
                        <div class="stat-percent font-bold label label-info"><?=number_format(@$statistic['voucher']['percent'],2,',','.')?>% of visitor</div>
                        <small>-</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ibox ">
                    <div class="ibox-title" style="background:#961515;color:#fff;">
                        <div class="ibox-tools">
                            <!--<span class="label label-info float-right">Bulan Ini</span>-->
                        </div>
                        <h5>Redemption</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=number_format(@$statistic['usage']['total'],0,',','.')?></h1>
                        <div class="stat-percent font-bold label label-info"><?=number_format(@$statistic['usage']['percent'],2,',','.')?>% of vouchers</div>
                        <small>of <?=number_format(@$statistic['usage']['items']->total_item,0,',','.')?> redemptions</small>
                    </div>
                </div>
            </div>
          </div>

          <h2 class="text-black">Running Budget</h2>
          <p class="campaign-description">
            Estimation of running budget will be transferred to affiliator's balance every month as total running budget.
          </p>

          <div class="row">
            <div class="col-12 col-md-6">
              <div class="ibox">
                <div class="ibox-title text-center" style="background:#5eb6f0;color:#fff;">
                  Estimation of Running Budget
                </div>
                <div class="ibox-content text-center">
                  <h2 class="text-black text-center">Rp. <?=number_format(@$earning['estimation'],0,',','.')?></h2>
                  <small>this month</small>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="ibox">
                <div class="ibox-title text-center" style="background:#5eb6f0;color:#fff;">
                  Total Running Budget
                </div>
                <div class="ibox-content text-center">
                  <h2 class="text-black text-center">Rp. <?=number_format(@$earning['total'],0,',','.')?></h2>
                  <small>exclude estimation of running budget</small>
                </div>
              </div>
            </div>

          </div>

          <h2 class="text-black">Graphic Statistic</h2>
          <div class="row">
            <div class="col-sm-12">
              <div class="ibox">
                <div class="ibox-title" style="background:#5eb6f0;color:#fff;">
                  Last 7 Days Activities
                </div>
                <div class="ibox-content">
                  <canvas id="lineChart" height="120"></canvas>
                </div>
              </div>
            </div>
          </div>


          <h2 class="text-black">Logs</h2>
          <div class="row">
            <div class="col-sm-12">
              <div class="ibox">
                <div class="ibox-title" style="background:#5eb6f0;color:#fff;">
                  last 25 logs
                </div>
                <div class="ibox-content text-center">
                  <div class='table-responsive'>
                  <?php
                    if(@$logs!=""){
                      print '<table class="table table-log">';
                      print '<tr>';
                      print '<th>Time</th>';
                      print '<th>Type</th>';
                      print '<th>IP</th>';
                      print '<th>City</th>';
                      //print '<th>Encrypted Identifier</th>';
                      print '<th>Cost</th>';
                      print '<th >Referrer</th>';
                      print '</tr>';
                      foreach ($logs as $key => $value) {
                        ?>
                        <tr>
                          <td class='text-left'><?=date("d/m/Y H:i:s A",$value->time_created)?></td>
                          <td class='text-left'><?=$value->type_conversion?></td>
                          <td class='text-left'><?=$value->ip?></td>
                          <td class='text-left'><?=$value->city?></td>
                          <!--<td><?=$value->encrypted_code?></td>-->
                          <td class='text-right'>Rp.<?=number_format($value->commission+$value->fee,0,',','.')?></td>
                          <td class='text-left'><?=$value->browser?></td>
                        </tr>
                        <?php
                      }
                      print "</table>";
                    }else{
                      ?>
                      <p>No data found!</p>
                      <?php
                    }
                  ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <h2 class="text-black">Logs</h2>
          <div class="row">
            <div class="col-sm-12">
              <div class="ibox">
                <div class="ibox-title" style="background:#5eb6f0;color:#fff;">
                  last 50 redemptions
                </div>
                <div class="ibox-content text-center">
                  <div class='table-responsive'>
                  <?php
                    if(@$voucher_logs!=""){
                      print '<table class="table table-log">';
                      print '<tr>';
                      print '<th>Time</th>';
                      print '<th>Outlet</th>';
                      print '<th>Voucher</th>';
                      print '<th>IP</th>';
                      print '<th>City</th>';
                      print '<th>Name</th>';
                      print '<th>Email</th>';
                      print '<th>Phone</th>';
                      print '<th>Address</th>';
                      print '</tr>';
                      foreach ($voucher_logs as $key => $value) {
                        ?>
                        <tr>
                          <td class='text-left' style="width:180px;"><?=date("d/m/Y H:i:s A",@$value->time_usage)?></td>
                          <td class='text-left'><?=@$value->outlet_name?><br><small><i class='fa fa-map-marker'></i> <?=$value->outlet_address?></small></td>
                          <td class='text-left text-bold'><strong><?=@$value->voucher_code?></strong></td>
                          <td class='text-left'><?=@$value->ip?></td>
                          <td class='text-left'><?=@$value->city?></td>
                          <td class='text-left'><?=@$value->optin_name?></td>
                          <td class='text-left'><?=@$value->optin_email?></td>
                          <td class='text-left'><?=@$value->optin_phone?></td>
                          <td class='text-left'><?=@$value->optin_address?></td>
                        </tr>
                        <?php
                      }
                      print "</table>";
                    }else{
                      ?>
                      <p>No data found!</p>
                      <?php
                    }
                  ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <?php echo $__env->make('backend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<script src="<?php echo e(url('templates/admin/js/plugins/chartJs/Chart.min.js')); ?>"></script>
<script>
  var lineData = {
      labels:['8 days ago','7 days ago','6 days ago','5 days ago', '4 days ago', '3 days ago', '2 days ago', '1 days ago', 'under 12 hours'],
      datasets: [{
            label: "Redemption",
            backgroundColor: "#961515",
            borderColor: "#961515",
            pointBackgroundColor: "#ffffff",
            pointBorderColor: "#961515",
            data: <?=json_encode(@$chart['usage'])?>
        },
        {
            label: "Voucher Created",
            backgroundColor: "#fcb13b",
            borderColor: "#fcb13b",
            pointBackgroundColor: "#ffffff",
            pointBorderColor: "#fcb13b",
            data: <?=json_encode(@$chart['voucher'])?>
        },
        {
              label: "Stay/Read",
              backgroundColor: "#c82360",
              borderColor: "#c82360",
              pointBackgroundColor: "#ffffff",
              pointBorderColor: "#c82360",
              data: <?=json_encode(@$chart['read'])?>
          },
        {
              label: "Visits",
              backgroundColor: "#5eb6f0",
              borderColor: "#5eb6f0",
              pointBackgroundColor: "#ffffff",
              pointBorderColor: "#5eb6f0",
              data: <?=json_encode(@$chart['visit'])?>
          }
      ]
  };

  var lineOptions = {
      responsive: true
  };


  var ctx = document.getElementById("lineChart").getContext("2d");
  new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});
</script>
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/master/campaign/report_o2o.blade.php ENDPATH**/ ?>