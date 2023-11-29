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
  .text-facebook{
    color:#3b5998;
  }
  .text-instagram{
    color:#c32aa3;
  }
  .text-twitter{
    color:#1da1f2;
  }
  .text-google{
    color:#ea4335;
  }
</style>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg sidebar-content">
        @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Campaign</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/campaign') }}">Campaign</a>
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
            <div class="col-lg-12">
              @include('backend.flash_message')
            </div>

            <div class="col-sm-6">
                <a class="btn btn-white" href="{{ url('master/campaign/resume/'.$detail->campaign_link) }}">
                    <i class="fa fa-angle-left"></i> back
                </a>
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-primary" href="{{ url('master/campaign/export-o2o/'.$detail->campaign_link) }}">
                    <i class="fa fa-file-excel"></i> Export
                </a>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-lg-3">
                <div class="ibox ">
                    <div class="ibox-title" style="background:#188371;color:#fff;">
                        <div class="ibox-tools">
                            <!--<span class="label label-info float-right">Bulan Ini</span>-->
                        </div>
                        <h5>Reach </h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=number_format(@$statistic['reach']['total'],0,',','.')?></h1>

                        <small>From all channels</small>
                    </div>
                </div>
            </div>

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
                  last logs
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
                          <td class='text-left'><?=$helper->checkReferrer($value->referrer)?></td>
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
                  list of redemptions
                </div>
                <div class="ibox-content text-center">
                  <div class='table-responsive'>
                  <?php
                    if(@$voucher_logs!=""){
                      print '<table class="table table-log">';
                      print '<tr>';
                      print '<th>No.</th>';
                      print '<th>Time</th>';
                      print '<th>Outlet</th>';
                      print '<th>Voucher</th>';
                      print '<th>IP</th>';
                      print '<th>City</th>';
                      print '<th>Name</th>';
                      print '<th>Email</th>';
                      print '<th>Phone</th>';
                      print '<th>Address</th>';
                      if($detail->additional_1!=""){
                        print '<th>'.$detail->additional_1.'</th>';
                      }
                      print '<th>Affiliator/Source</th>';
                      if($detail->disclaimer!=""){
                        print '<th>Disclaimer</th>';
                      }
                      print '<th>Referrer</th>';
                      print '</tr>';
                      $counter = 0;
                      foreach ($voucher_logs as $key => $value) {
                        $counter++;
                        ?>
                        <tr>
                          <td><?=$counter?></td>
                          <td class='text-left' style="width:180px;"><?=date("d/m/Y H:i:s A",@$value->time_usage)?></td>
                          <td class='text-left'><?=@$value->outlet_name?><br><small><i class='fa fa-map-marker'></i> <?=$value->outlet_address?></small></td>
                          <td class='text-left text-bold'><strong><?=@$value->voucher_code?></strong></td>
                          <td class='text-left'><?=@$value->ip?></td>
                          <td class='text-left'><?=@$value->city?></td>
                          <td class='text-left'><?=@$value->optin_name?></td>
                          <td class='text-left'><?=@$value->optin_email?></td>
                          <td class='text-left'><?=@$value->optin_phone?></td>
                          <td class='text-left'><?=@$value->optin_address?></td>
                          <?php if($detail->additional_1!=""){ ?>
                          <td class='text-left'><?=@$value->additional_1?></td>
                          <?php } ?>
                          <td class='text-left'><?=@$value->affiliator_name?></td>
                          <?php if($detail->disclaimer!=""){ ?>
                          <td class='text-left'><?=@$value->disclaimer?></td>
                          <?php } ?>
                          <td class='text-left'><?=$helper->checkReferrer($value->referrer)?></td>
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


          <h2 class="text-black">Affiliators</h2>
          <div class="row">
            <div class="col-sm-12">
              <div class="ibox">
                <div class="ibox-title" style="background:#5eb6f0;color:#fff;">
                  Affiliator's performance
                </div>
                <div class="ibox-content text-center">
                  <div class='table-responsive'>
                  <?php
                    if(@$affiliators!=""){
                      print '<table class="table table-log">';
                      print '<tr>';
                      print '<th>Affiliator</th>';
                      //print '<th>Last Active</th>';
                      print '<th>Reach</th>';
                      print '<th>Visitor</th>';
                      print '<th>Interest</th>';
                      print '<th>Action</th>';
                      print '<th>Acquisition</th>';
                      print '</tr>';
                      foreach ($affiliators as $key => $value) {
                        ?>
                        <tr>
                          <td class='text-left'><?=$value->first_name?> <?=$value->last_name?></td>
                          <!--<td class='text-left'><?=date("d/m/Y H:i:s A",$value->time_created)?></td>-->
                          <td class='text-left'><?=number_format($value->total_reach)?></td>
                          <td class='text-left'>
                            <?=number_format($value->total_visit)?>
                            <?php
                              if($value->total_visit>0){
                                echo "<ul>";
                                if($value->total_reach_facebook) echo "<li class='text-facebook'>Facebook: ".number_format($value->total_reach_facebook*100/$value->total_visit,2)."%</li>";
                                if($value->total_reach_instagram) echo "<li class='text-instagram'>Instagram: ".number_format($value->total_reach_instagram*100/$value->total_visit,2)."%</li>";
                                if($value->total_reach_twitter) echo "<li class='text-twitter'>Twitter: ".number_format($value->total_reach_twitter*100/$value->total_visit,2)."%</li>";
                                if($value->total_reach_google) echo "<li class='text-google'>Google: ".number_format($value->total_reach_google*100/$value->total_visit,2)."%</li>";
                                $all = $value->total_reach_facebook + $value->total_reach_instagram + $value->total_reach_twitter + $value->total_reach_google;
                                if($value->total_visit-$all>0) echo "<li>Other: ".number_format(($value->total_visit - $all)*100/$value->total_visit,2)."%</li>";
                                echo "</ul>";
                              }
                            ?>
                          </td>
                          <td class='text-left'><?=number_format($value->total_read)?>
                            <?php
                              if($value->total_read>0){
                                echo "<ul>";
                                if($value->total_read_facebook) echo "<li class='text-facebook'>Facebook: ".number_format($value->total_read_facebook*100/$value->total_read,2)."%</li>";
                                if($value->total_read_instagram) echo "<li class='text-instagram'>Instagram: ".number_format($value->total_read_instagram*100/$value->total_read,2)."%</li>";
                                if($value->total_read_twitter) echo "<li class='text-twitter'>Twitter: ".number_format($value->total_read_twitter*100/$value->total_read,2)."%</li>";
                                if($value->total_read_google) echo "<li class='text-google'>Google: ".number_format($value->total_read_google*100/$value->total_read,2)."%</li>";
                                $all = $value->total_read_facebook + $value->total_read_instagram + $value->total_read_twitter + $value->total_read_google;
                                if($value->total_read-$all>0) echo "<li>Other: ".number_format(($value->total_read - $all)*100/$value->total_read,2)."%</li>";
                                echo "</ul>";
                              }
                            ?>
                          </td>
                          <td class='text-left'><?=number_format($value->total_created)?>
                            <?php
                              if($value->total_created>0){
                                echo "<ul>";
                                if($value->total_request_facebook) echo "<li class='text-facebook'>Facebook: ".number_format($value->total_request_facebook*100/$value->total_created,2)."%</li>";
                                if($value->total_request_instagram) echo "<li class='text-instagram'>Instagram: ".number_format($value->total_request_instagram*100/$value->total_created,2)."%</li>";
                                if($value->total_request_twitter) echo "<li class='text-twitter'>Twitter: ".number_format($value->total_request_twitter*100/$value->total_created,2)."%</li>";
                                if($value->total_request_google) echo "<li class='text-google'>Google: ".number_format($value->total_request_google*100/$value->total_created,2)."%</li>";
                                $all = $value->total_request_facebook + $value->total_request_instagram + $value->total_request_twitter + $value->total_request_google;
                                if($value->total_created-$all>0) echo "<li>Other: ".number_format(($value->total_created - $all)*100/$value->total_created,2)."%</li>";
                                echo "</ul>";
                              }
                            ?>
                          </td>
                          <td class='text-left'><?=number_format($value->total_redemption)?>
                            <?php
                              if($value->total_redemption>0){
                                echo "<ul>";
                                if($value->total_redemption_facebook) echo "<li class='text-facebook'>Facebook: ".number_format($value->total_redemption_facebook*100/$value->total_redemption,2)."%</li>";
                                if($value->total_redemption_instagram) echo "<li class='text-instagram'>Instagram: ".number_format($value->total_redemption_instagram*100/$value->total_redemption,2)."%</li>";
                                if($value->total_redemption_twitter) echo "<li class='text-twitter'>Twitter: ".number_format($value->total_redemption_twitter*100/$value->total_redemption,2)."%</li>";
                                if($value->total_redemption_google) echo "<li class='text-google'>Google: ".number_format($value->total_redemption_google*100/$value->total_redemption,2)."%</li>";
                                $all = $value->total_redemption_facebook + $value->total_redemption_instagram + $value->total_redemption_twitter + $value->total_redemption_google;
                                if($value->total_redemption-$all>0) echo "<li>Other: ".number_format(($value->total_redemption - $all)*100/$value->total_redemption,2)."%</li>";
                                echo "</ul>";
                              }
                            ?>
                          </td>
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
        @include('backend.footer')
    </div>
</div>
<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<script>
  var lineData = {
      labels:<?=json_encode($label_chart)?>,
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
          },
          {
                label: "Reach",
                backgroundColor: "#4fc1ae",
                borderColor: "#188371",
                pointBackgroundColor: "#4fc1ae",
                pointBorderColor: "#188371",
                data: <?=json_encode(@$chart['reach'])?>
            }
      ]
  };

  var lineOptions = {
      responsive: true
  };


  var ctx = document.getElementById("lineChart").getContext("2d");
  new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});
</script>
