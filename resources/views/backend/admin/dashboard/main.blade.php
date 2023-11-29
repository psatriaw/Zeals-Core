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
    /*text-overflow: ellipsis;*/
    white-space: nowrap;
  }
</style>
<?php
  $campaigntypes = array(
    "banner"  => "AMP",
    "o2o"     => "O2O",
    "event"   => "EVENT"
  );

  if($estimated_budget==0){
    $estimated_budget = 1;
  }

  if($in_budget_range==0){
    $in_budget_range = 1;
  }
?>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg sidebar-content">
        @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))

        <div class="wrapper-content">
          <div class="row">
          <div class="col-lg-2" id="dashboard_running_project">
              <div class="ibox ">
                  <div class="ibox-title">
                      <div class="ibox-tools">
                          <span class="label label-info float-right">this month</span>
                      </div>
                      <h5>Running Project </h5>
                  </div>
                  <div class="ibox-content">
                      <h1 class="no-margins"><?=number_format($running_project,0,',','.')?></h1>
                      <div class="stat-percent font-bold">67.38% <i class="fa fa-bolt"></i></div>
                      <small>project</small>
                  </div>
              </div>
          </div>
          <div class="col-lg-2">
              <div class="ibox ">
                  <div class="ibox-title">
                      <div class="ibox-tools">
                          <span class="label label-info float-right">this month</span>
                      </div>
                      <h5>New Account</h5>
                  </div>
                  <div class="ibox-content">
                      <h1 class="no-margins"><?=number_format($new_comer,0,',','.')?></h1>
                      <div class="stat-percent font-bold">naik 20% <i class="fa fa-level-up"></i></div>
                      <small>Akun baru</small>
                  </div>
              </div>
          </div>

          <div class="col-lg-4">
              <div class="ibox ">
                  <div class="ibox-title">
                      <div class="ibox-tools">
                          <span class="label label-info">Per <?=date("Y-m-d H:i")?></span>
                      </div>
                      <h5>Running Budget</h5>
                  </div>
                  <div class="ibox-content">

                      <div class="row">
                          <div class="col-md-6">
                              <h1 class="no-margins">Rp.<?=number_format($estimated_budget/1000,0,',','.')?>K</h1>
                              <div class="font-bold"><?=number_format(($estimated_budget*100)/$in_budget_range,2,',','.')?>% <i class="fa fa-level-up"></i> <small>Estimation this month</small></div>
                          </div>
                          <div class="col-md-6">
                              <h1 class="no-margins">Rp.<?=number_format($running_budget/1000,0,',','.')?>K</h1>
                              <div class="font-bold"><?=number_format(($running_budget*100)/$in_budget,2,',','.')?>% <i class="fa fa-level-up"></i> <small>Total running budget</small></div>
                          </div>
                      </div>


                  </div>
              </div>
          </div>
          <div class="col-lg-4">
              <div class="ibox ">
                  <div class="ibox-title">
                      <h5>Platform Revenue</h5>
                      <div class="ibox-tools">
                          <span class="label label-info">Per <?=date("Y-m-d H:i")?></span>
                      </div>
                  </div>
                  <div class="ibox-content text-right">
                      <h1 class="no-margins">Rp.<?=number_format($total_income,0,',','.')?></h1>
                      <div class="font-bold ">Platform Revenue</div>
                  </div>

              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-8">
              <div class="ibox ">
                  <div class="ibox-content">
                      <div>
                          <span class="float-right text-right">
                          <small>Power of <strong>Affiliator</strong></small>
                              <br/>
                              Total data : <?=number_format($all_data_tracker,0,',','.')?> data
                          </span>
                          <h3 class="font-bold no-margins">
                              Year: <?=$tahun_data?>
                          </h3>
                          <small>Data Tracking</small>
                      </div>

                      <div class="m-t-sm">

                          <div class="row">
                              <div class="col-md-9">
                                  <div>
                                      <canvas id="lineChart" height="200"></canvas>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <ul class="stat-list m-t-lg">
                                      <li>
                                          <h2 class="no-margins"><?=number_format($all_reach,0,',','.')?></h2>
                                          <small>Total reach on period</small>
                                          <div class="progress progress-mini">
                                              <div class="progress-bar" style="width: 100%;"></div>
                                          </div>
                                      </li>
                                      <li>
                                          <h2 class="no-margins"><?=number_format($all_data_tracker,0,',','.')?></h2>
                                          <small>Total tracking on period</small>
                                          <div class="progress progress-mini">
                                              <div class="progress-bar" style="width: <?=number_format($all_data_tracker*100/$all_reach,2)?>%;"></div>
                                          </div>
                                      </li>
                                      <li>
                                          <h2 class="no-margins "><?=number_format($effectiveness['read']['percent'],2,',','.')?>%</h2>
                                          <small>Effectivity of reader/stay</small>
                                          <div class="progress progress-mini">
                                              <div class="progress-bar" style="width: <?=number_format($effectiveness['read']['percent'],2)?>%;"></div>
                                          </div>
                                      </li>
                                      <li>
                                          <h2 class="no-margins "><?=number_format($effectiveness['action']['percent'],2,',','.')?>%</h2>
                                          <small>Effectivity of action</small>
                                          <div class="progress progress-mini">
                                              <div class="progress-bar" style="width: <?=number_format($effectiveness['action']['percent'],2)?>%;"></div>
                                          </div>
                                      </li>
                                      <li>
                                          <h2 class="no-margins "><?=number_format($effectiveness['acquisition']['percent'],2,',','.')?>%</h2>
                                          <small>Effectivity of acquisition</small>
                                          <div class="progress progress-mini">
                                              <div class="progress-bar" style="width: <?=number_format($effectiveness['acquisition']['percent'],2)?>%;"></div>
                                          </div>
                                      </li>
                                      <li>
                                          <h2 class="no-margins "><?=number_format($avg_joined,2,',','.')?></h2>
                                          <small>Average affiliator per project</small>
                                          <div class="progress progress-mini">
                                              <div class="progress-bar" style="width: 100%;"></div>
                                          </div>
                                      </li>
                                  </ul>
                              </div>
                          </div>

                      </div>

                      <div class="m-t-md">
                          <small class="float-right">
                              <i class="fa fa-clock-o"> </i>
                              Update on 16.07.2015
                          </small>
                          <small>
                              <strong>Analysis of sales:</strong> The value has been changed over time, and last month reached a level over $50,000.
                          </small>
                      </div>

                  </div>
              </div>
          </div>
          <div class="col-lg-4">
              <div class="ibox ">
                  <div class="ibox-title">
                    <div class="ibox-tools">
                      <span class="label label-info float-right">Realtime</span>
                    </div>
                      <h5>Last 1.000 Activities of Infuencer</h5>
                  </div>
                  <div class="ibox-content">
                    <div>
                        <canvas id="log_pie" style="height:400px;"></canvas>
                    </div>
                  </div>
                  <div class="ibox-content box-log" style="height:347px;overflow-y:scroll;">
                      <?php
                        if(@$logs){
                          foreach ($logs as $key => $value) {
                            ?>
                            <div class="row">
                                <div class="col-sm-3">
                                  <small class="stats-label"><?=date("d-m-Y H:i:s ",$value->time_created)?></small>
                                </div>
                                <div class="col-sm-2">
                                    <small class="stats-label"><?=@$value->country?> <?=@$value->city?></small>
                                </div>
                                <div class="col-sm-2">
                                    <small class="stats-label"><?=$value->type_conversion?></small>
                                </div>
                                <div class="col-sm-3">
                                    <small class="stats-label"><?=@$value->campaign_title?></small>
                                </div>
                                <div class="col-sm-2">
                                    <small class="stats-label"><?=$value->ip?></small>
                                </div>
                            </div>
                            <?php
                          }
                        }
                      ?>
                  </div>
              </div>
          </div>

      </div>

      <div class="row">
        <div class="col-lg-8">
          <div id="mapcontainer" style="height:450px;margin-bottom:25px;padding:25px;background:#fff;">
          </div>
        </div>
        <div class="col-lg-4">
          <div class="ibox ">
              <div class="ibox-title">
                <div class="ibox-tools">
                  <span class="label label-info float-right">Realtime</span>
                </div>
                  <h5>Highest Traffic Project By City</h5>
              </div>
              <div class="ibox-content box-log" style="height:402px;overflow-y:scroll;">
                <div class="row">
                    <div class="col-sm-4">
                      <span class="stats-label text-strong">City</span>
                    </div>
                    <div class="col-sm-2 text-right text-strong">
                        <span class="stats-label">Total Data</span>
                    </div>
                </div>

                  <?php
                    if($data_kota){
                      foreach ($data_kota as $key => $value) {
                        ?>
                        <div class="row">
                            <div class="col-sm-4">
                              <span class="stats-label "><?=($value->city=="")?"unknown":ucfirst(@$value->city)?></span>
                            </div>
                            <div class="col-sm-2 text-right ">
                                <span class="stats-label"><?=number_format($value->total_data_visit,0,',','.')?></span>
                            </div>
                        </div>
                        <?php
                      }
                    }
                  ?>
              </div>
          </div>
        </div>
      </div>
      <div class="row">

        <div class="col-lg-12">
          <div class="ibox ">
          <div class="ibox-title">
              <h5>10 Project Terakhir </h5>
              <div class="ibox-tools">
                  <a class="collapse-link">
                      <i class="fa fa-chevron-up"></i>
                  </a>
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                      <i class="fa fa-wrench"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-user">
                      <li><a href="#" class="dropdown-item">Config option 1</a>
                      </li>
                      <li><a href="#" class="dropdown-item">Config option 2</a>
                      </li>
                  </ul>
                  <a class="close-link">
                      <i class="fa fa-times"></i>
                  </a>
              </div>
          </div>
          <div class="ibox-content">
              <div class="table-responsive">
                  <table class="table table-striped">
                      <thead>
                      <tr>

                          <th>#</th>
                          <th>Project </th>
                          <th>Brand </th>
                          <th>Project Type</th>
                          <th>Total Budget </th>
                          <th>Running Budget </th>
                          <th>Progress </th>
                          <th>Status </th>
                          <th>Total Affiliator </th>
                          <th>Start</th>
                          <th>End</th>
                          <th></th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php
                          $counter = 0;
                          if($campaigns){
                            foreach ($campaigns as $key => $value) {
                              $counter++;
                              ?>
                              <tr>
                                  <td><?=$counter?></td>
                                  <td><?=$value->campaign_title?></td>
                                  <td><?=@$value->nama_penerbit?></td>
                                  <td><?=$campaigntypes[$value->campaign_type]?></td>
                                  <td class="text-right">Rp. <?=number_format($value->budget,0,",",".")?></td>
                                  <td class="text-right">Rp. <?=number_format(@$value->running_budget,0,",",".")?></td>
                                  <td><span class="pie">0.52/1.561</span></td>
                                  <td><?=$value->running_status?></td>
                                  <td><?=number_format($value->joined,0,',','.')?></td>
                                  <td><?=date("M dS, Y",strtotime($value->start_date))?></td>
                                  <td><?=date("M dS, Y",strtotime($value->end_date))?></td>
                                  <td><a href="{{ url('master/campaign/report/'.$value->campaign_link) }}" class='btn btn-xs btn-primary'><i class="fa fa-chart-pie"></i> report </a></td>
                              </tr>
                              <?php
                            }
                          }
                        ?>
                      </tbody>
                  </table>
              </div>

          </div>
          </div>
          </div>

        </div>
        </div>
        @include('backend.footer')
    </div>
    <?php
      $group_color = array(
        "reach"       => "#00c5a6",
        "visit"       => "#5eb6f0",
        "read"        => "#c82360",
        "action"      => "#fcb13b",
        "acquisition" => "#961515",
      );

      $group_opt_label = array(
        "reach"       => "Reach",
        "visit"       => "Visit",
        "read"        => "Read/Stay",
        "action"      => "Action/Click",
        "acquisition" => "Acquisition",
      );

      $group_match = array();
      $group_pie = array();
      $group_label = array();
      if($logs_group){
        foreach ($logs_group as $key => $value) {
          if($value->type_conversion!="initial" && $value->type_conversion!="custom"){
            $group_pie[]    = $value->total_data;
            $group_match[]  = $group_color[$value->type_conversion];
            $group_label[]  = $group_opt_label[$value->type_conversion];
          }
        }
      }
    ?>
</div>
<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/mapdata/countries/id/id-all.js"></script>

<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<script>
      var lineData = {
          labels:<?=json_encode($bulan_on_year)?>,
          datasets: [{
                label: "<?=$group_opt_label['acquisition']?>",
                backgroundColor: "<?=$group_color['acquisition']?>",
                borderColor: "<?=$group_color['acquisition']?>",
                pointBackgroundColor: "#ffffff",
                  pointBorderColor: "<?=$group_color['acquisition']?>",
                data: <?=json_encode($data_bulan_on_year['acquisition'])?>
            },
            {
                label: "<?=$group_opt_label['action']?>",
                backgroundColor: "<?=$group_color['action']?>",
                borderColor: "<?=$group_color['action']?>",
                pointBackgroundColor: "#ffffff",
                pointBorderColor: "<?=$group_color['action']?>",
                data: <?=json_encode($data_bulan_on_year['action'])?>
            },
            {
                  label: "<?=$group_opt_label['read']?>",
                  backgroundColor: "<?=$group_color['read']?>",
                  borderColor: "<?=$group_color['read']?>",
                  pointBackgroundColor: "#ffffff",
                  pointBorderColor: "<?=$group_color['read']?>",
                  data: <?=json_encode($data_bulan_on_year['read'])?>
              },
            {
                  label: "<?=$group_opt_label['visit']?>",
                  backgroundColor: "<?=$group_color['visit']?>",
                  borderColor: "<?=$group_color['visit']?>",
                  pointBackgroundColor: "#ffffff",
                  pointBorderColor: "<?=$group_color['visit']?>",
                  data: <?=json_encode($data_bulan_on_year['visit'])?>
              },
            {
                  label: "<?=$group_opt_label['reach']?>",
                  backgroundColor: "<?=$group_color['reach']?>",
                  borderColor: "<?=$group_color['reach']?>",
                  pointBackgroundColor: "#ffffff",
                  pointBorderColor: "<?=$group_color['reach']?>",
                  data: <?=json_encode($data_bulan_on_year['reach'])?>
              }
          ]
      };

      var lineOptions = {
          responsive: true
      };


      var ctx = document.getElementById("lineChart").getContext("2d");
      new Chart(ctx, {type: 'bar', data: lineData, options:lineOptions});



      var lineData = {
          labels: <?=json_encode($group_label)?>,
          datasets: [
            {
                backgroundColor: <?=json_encode($group_match)?>,
                borderColor: <?=json_encode($group_match)?>,
                pointBackgroundColor: <?=json_encode($group_match)?>,
                pointBorderColor: <?=json_encode($group_match)?>,
                data: <?=json_encode($group_pie)?>
            }
          ]
      };

      var lineOptions = {
          responsive: true
      };


      var ctx = document.getElementById("log_pie").getContext("2d");
      new Chart(ctx, {type: 'pie', data: lineData, options:lineOptions});

      var data = [];

      <?php
      if($data_map){
        foreach ($data_map as $key => $value) {
          echo "data.push(['".$key."',".$value."]);";
        }
      }
      ?>

      Highcharts.mapChart('mapcontainer', {
        chart: {
          height:400,
            map: 'countries/id/id-all'
        },

        title: {
            text: 'Target Reached By IP Location'
        },

        subtitle: {
            text: 'Source map: <a href="http://code.highcharts.com/mapdata/countries/id/id-all.js">Indonesia</a>'
        },

        mapNavigation: {
            enabled: true,
            buttonOptions: {
                verticalAlign: 'bottom'
            }
        },

        colorAxis: {
            min: 0,
            minColor: '#ffffff',
            maxColor: '#c82360',
        },

        series: [{
            data: data,
            name: 'Data Tracking',
            states: {
                hover: {
                    color: '#ab4444'
                }
            },
            dataLabels: {
                enabled: true,
                format: '{point.name} ({point.value})'
            }
        }]
      });

    let datadashboard = [
        {
            id: "dashboard_running_project",
            url: "<?=url('api/dashboard/running_project')?>",
            params: {}
        }
    ];


    function loadDataDashboard(){
        if(datadashboard.length > 0){
            getData(datadashboard[0]);
        }
    }

    function getData(item){
        $.ajax({
            url: item.url,
            data: item.params,
            type: "POST"
        })
        .done(function(result){
            $("#dashboard_running_project").html(result);
            console.log(result,"SUCC");
        })
        .fail(function(err){
            console.log(err,"ERROR");
        })
        .always(function(){
            datadashboard.splice(0,1)
            loadDataDashboard()
        })
    }

    $(document).ready(function(){
        loadDataDashboard()
    });
</script>

