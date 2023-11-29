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
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg sidebar-content">
        @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Campaign "<?=$detail->campaign_title?>"</h2>
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
            <div class="col-sm-12">
                <a class="btn btn-white" href="{{ url('master/campaign/resume/'.$detail->campaign_link) }}">
                    <i class="fa fa-angle-left"></i> back
                </a>
            </div>
          </div>

          {!! Form::open(['url' => url('master/campaign/report/'.$detail->campaign_link), 'method' => 'get', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
          <div class="row form-group">
            <div class="col-lg-12">
              <div class="input-group">
                  <input type="text" class="form-control-sm form-control" id="date" name="dates" value="<?=@$dates?>">
                  <div class="input-group-addon">
                    <button type="submit" class="btn btn-secondary">Set data range <i class="fa fa-angle-right"></i></button>
                  </div>
              </div>
            </div>
          </div>
          {!! Form::close() !!}

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

                        <small>of <?=number_format(@$statistic['visit']['items']->total_item,0,',','.')?> unique visitor</small>
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

                        <small>of <?=number_format(@$statistic['read']['items']->total_item,0,',','.')?> readers</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ibox ">
                    <div class="ibox-title" style="background:#fcb13b;color:#fff;">
                        <div class="ibox-tools">
                            <!--<span class="label label-info float-right">Bulan Ini</span>-->
                        </div>
                        <h5>Unique Action</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=number_format(@$statistic['action']['total'],0,',','.')?></h1>

                        <small>of <?=number_format(@$statistic['action']['items']->total_item,0,',','.')?> actions</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ibox ">
                    <div class="ibox-title" style="background:#961515;color:#fff;">
                        <div class="ibox-tools">
                            <!--<span class="label label-info float-right">Bulan Ini</span>-->
                        </div>
                        <h5>Unique Acquisition</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=number_format(@$statistic['acquisition']['total'],0,',','.')?></h1>
                        <div class="stat-percent font-bold label label-info"><?=number_format(@$statistic['acquisition']['percent'],2,',','.')?>% of visitor</div>
                        <small>of <?=number_format(@$statistic['acquisition']['items']->total_item,0,',','.')?> acquisitions</small>
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
                  <small>per data range</small>
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
                  <small>per data range</small>
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
                        print '<th class="text-right">Commission</th>';
                        print '<th class="text-right">Fee</th>';
                        print '<th class="text-right">Sub total</th>';
                        print '</tr>';
                        foreach ($affiliators as $key => $value) {
                          ?>
                          <tr>
                            <td class='text-left'><?=$value->first_name?> <?=$value->last_name?></td>
                            <!--<td class='text-left'><?=date("d/m/Y H:i:s A",$value->time_created)?></td>-->
                            <td class='text-left'><?=number_format($value->total_reach)?></td>
                            <td class='text-left'><?=number_format($value->total_visit)?></td>
                            <td class='text-left'><?=number_format($value->total_read)?></td>
                            <td class='text-left'><?=number_format($value->total_action)?></td>
                            <td class='text-left'><?=number_format($value->total_acquisition)?></td>
                            <td class='text-right'>Rp.<?=number_format($value->total_commission)?></td>
                            <td class='text-right'>Rp.<?=number_format($value->total_fee)?></td>
                            <td class='text-right'>Rp.<?=number_format($value->total_commission + $value->total_fee)?></td>
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
                      print '<th>Affiliator</th>';
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
                          <td class='text-left'><?=@$value->first_name?></td>
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

        </div>
        @include('backend.footer')
    </div>
</div>
<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<script>
  $('#date').daterangepicker({
    locale: {
      format: 'YYYY/MM/DD',
    }
  });

  var lineData = {
      labels:<?=json_encode($label_chart)?>,
      datasets: [{
            label: "Acquisition",
            backgroundColor: "#961515",
            borderColor: "#961515",
            pointBackgroundColor: "#ffffff",
            pointBorderColor: "#961515",
            data: <?=json_encode(@$chart['acquisition'])?>
        },
        {
            label: "Action",
            backgroundColor: "#fcb13b",
            borderColor: "#fcb13b",
            pointBackgroundColor: "#ffffff",
            pointBorderColor: "#fcb13b",
            data: <?=json_encode(@$chart['action'])?>
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
