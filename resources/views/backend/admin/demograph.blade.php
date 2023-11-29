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
  );

  // if($estimated_budget==0){
    $estimated_budget = 1;
  // }

  // if($in_budget_range==0){
    $in_budget_range = 1;
  // }
?>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg sidebar-content">
        @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))

        <div class="wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div id="mapcontainer" style="height:650px;margin-bottom:25px;padding:25px;background:#fff;">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="ibox ">
                        <div class="ibox-title">
                        <div class="ibox-tools">
                            <span class="label label-info float-right">Currently</span>
                        </div>
                            <h5>Affiliator Distribution By City</h5>
                        </div>
                        <div class="ibox-content box-log" style="height:402px;overflow-y:scroll;">
                          <div class="row">
                              <div class="col-sm-4">
                                  <span class="stats-label text-strong">City</span>
                              </div>
                              <div class="col-sm-2 text-right text-strong">
                                  <span class="stats-label">Total Affiliator</span>
                              </div>
                          </div>

                          <?php
                          if($data_affiliator){
                              foreach ($data_affiliator["city"] as $key => $value) {
                              ?>
                              <div class="row">
                                  <div class="col-sm-4">
                                      <span class="stats-label "><?=($value->city=="")?"unknown":ucfirst(@$value->city)?></span>
                                  </div>
                                  <div class="col-sm-2 text-right ">
                                      <span class="stats-label"><?=number_format($value->total_data,0,',','.')?></span>
                                  </div>
                              </div>
                              <?php
                              }
                          }
                          ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="ibox ">
                        <div class="ibox-title">
                        <div class="ibox-tools">
                            <span class="label label-info float-right">Currently</span>
                        </div>
                            <h5>Affiliator Distribution By Job</h5>
                        </div>
                        <div class="ibox-content box-log" style="height:402px;overflow-y:scroll;">
                          <div class="row">
                              <div class="col-sm-4">
                                  <span class="stats-label text-strong">Job</span>
                              </div>
                              <div class="col-sm-2 text-right text-strong">
                                  <span class="stats-label">Total Affiliator</span>
                              </div>
                          </div>

                          <?php
                          if($data_affiliator){
                              foreach ($data_affiliator["job"] as $key => $value) {
                              ?>
                              <div class="row">
                                  <div class="col-sm-4">
                                      <span class="stats-label "><?=($value->job=="")?"unknown":ucfirst(@$value->job)?></span>
                                  </div>
                                  <div class="col-sm-2 text-right ">
                                      <span class="stats-label"><?=number_format($value->total_data,0,',','.')?></span>
                                  </div>
                              </div>
                              <?php
                              }
                          }
                          ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox ">
                        <div class="ibox-title">
                        <div class="ibox-tools">
                            <span class="label label-info float-right">Currently</span>
                        </div>
                            <h5>Affiliator Distribution By Register</h5>
                        </div>
                        <div class="ibox-content box-log" style="height:402px;overflow-y:scroll;">
                          <div class="row">
                              <div class="col-sm-4">
                                  <span class="stats-label text-strong">Register Time</span>
                              </div>
                              <div class="col-sm-2 text-right text-strong">
                                  <span class="stats-label">Total Affiliator</span>
                              </div>
                          </div>

                          <?php
                          if($data_affiliator){
                              foreach ($data_affiliator["activity"] as $key => $value) {
                              ?>
                              <div class="row">
                                  <div class="col-sm-4">
                                      <span class="stats-label "><?=($value->time=="")?"unknown":ucfirst(@$value->time)?></span>
                                  </div>
                                  <div class="col-sm-2 text-right ">
                                      <span class="stats-label"><?=number_format($value->total_data,0,',','.')?></span>
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
        </div>
        @include('backend.footer')
    </div>
</div>
<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/mapdata/countries/id/id-all.js"></script>

<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<script>

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
          height:550,
            map: 'countries/id/id-all'
        },

        title: {
            text: 'Area Distribution'
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

</script>
