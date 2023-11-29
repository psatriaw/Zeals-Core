<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg sidebar-content">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))

    <div class="wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">

                  <div class="ibox-title">
                    {!! Form::open(['url' => url('dashboard/transaction'), 'method' => 'get', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                    <div class="row">
                      <div class="col-lg-3">
                        <select class="form-control" name="type" onchange="setDatePick(this)">
                          <option value="daily" <?=(@$type=="daily")?"selected":""?>>Daily</option>
                          <option value="weekly" <?=(@$type=="weekly")?"selected":""?>>Weekly</option>
                          <!-- <option value="monthly" <?=(@$type=="monthly")?"selected":""?>>Monthly</option> -->
                          <option value="all" <?=(@$type=="all")?"selected":""?>>All Data</option>
                        </select>
                      </div>

                      <div class="col-lg-3">
                        <select class="form-control" name="chart">
                          <option value="line" <?=(@$chart=="line")?"selected":""?>>Spline</option>
                          <option value="bar" <?=(@$chart=="bar")?"selected":""?>>Bar</option>
                        </select>
                      </div>

                      <div class="col-lg-4">
                            <input type="text" class="form-control-sm form-control" id="date" name="dates" value="<?=$dates?>">
                      </div>
                      <div class="col-lg-2">
                        <button class="btn btn-primary btn-block">
                            <i class="fa fa-search"></i> Submit
                        </button>
                      </div>
                    </div>
                    {!! Form::close() !!}
                  </div>
                  <div class="ibox-content">
                      <div class="row">
                        <div class="col-sm-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <div class="ibox-tools">
                                      <span class="label label-info float-right">Reach</span>
                                  </div>
                                  <h5>Reach Campaign </h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['reach'],0,',','.')?></h1>
                                  <small>reach</small>
                              </div>
                          </div>
                        </div>


                        <div class="col-sm-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <div class="ibox-tools">
                                      <span class="label label-info float-right">Visitor</span>
                                  </div>
                                  <h5>Unique Visitor </h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['visit'],0,',','.')?></h1>
                                  <small>visitor</small>
                              </div>
                          </div>
                        </div>


                        <div class="col-sm-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <div class="ibox-tools">
                                      <span class="label label-info float-right">Stay/Interest</span>
                                  </div>
                                  <h5>Interest Campaign </h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['read'],0,',','.')?></h1>
                                  <small>unique interest</small>
                              </div>
                          </div>
                        </div>

                        <div class="col-sm-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <div class="ibox-tools">
                                      <span class="label label-info float-right">Action/Request/Click</span>
                                  </div>
                                  <h5>Click Campaign </h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['action'],0,',','.')?></h1>
                                  <small>clicks</small>
                              </div>
                          </div>
                        </div>

                        <div class="col-sm-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <div class="ibox-tools">
                                      <span class="label label-info float-right">Voucher Request</span>
                                  </div>
                                  <h5>Voucher Request</h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['voucher_request'],0,',','.')?></h1>
                                  <small>requests</small>
                              </div>
                          </div>
                        </div>

                        <div class="col-sm-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <div class="ibox-tools">
                                      <span class="label label-info float-right">Acquisition/Sales</span>
                                  </div>
                                  <h5>Unique Sales/Acquisition </h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['acquisition'],0,',','.')?></h1>
                                  <small>sales</small>
                              </div>
                          </div>
                        </div>

                        <div class="col-sm-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <div class="ibox-tools">
                                      <span class="label label-info float-right">Redemption/Sales</span>
                                  </div>
                                  <h5>Redemption </h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['voucher_usage'],0,',','.')?></h1>
                                  <small>redemptions</small>
                              </div>
                          </div>
                        </div>


                      </div>
                  </div>
                  <br>

                  <div class="ibox-title">
                    <h3 class="font-bold">
                      Graph
                    </h3>
                  </div>
                    <div class="ibox-content">
                        <div>
                            <h1 class="m-b-xs chart-title"></h1>
                            <h3 class="font-bold">
                                The Power of Affiliator
                            </h3>
                            <!--
                            <small>Sales marketing.</small>
                            -->
                        </div>
                        <?php if($type=='all'){ ?>
                          <p>Sorry, can't display the chart for all data</p>
                        <?php }else{ ?>
                        <div>
                            <canvas id="lineChart" height="120"></canvas>
                        </div>
                        <?php } ?>
                        <div class="m-t-md">
                            <small class="pull-right">
                                <i class="fa fa-clock-o"> </i>
                                Data Update per <?=date("d M Y H:i:s")?>
                            </small>
                           <small>
                               <strong>data-source:</strong> Zeals System
                           </small>
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
<script>
    $(document).ready(function() {

        var lineData = {
            labels:<?=json_encode($bulan_on_year)?>,
            datasets: [
              {
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
        new Chart(ctx, {type: '<?=$chart?>', data: lineData, options:lineOptions});

        $('#date').daterangepicker({
          locale: {
            format: 'YYYY/MM/DD',
          }
        });
    });

    function setDatePick(val){
      if(val.value=='all'){
        $("#date").val('').prop("disabled",true);
      }
    }
</script>
