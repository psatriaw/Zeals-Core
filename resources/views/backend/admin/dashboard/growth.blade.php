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
                    {!! Form::open(['url' => url('dashboard/affiliator'), 'method' => 'get', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
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
                                      <span class="label label-info float-right">Auto Activated</span>
                                  </div>
                                  <h5>Super QR Account </h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['super_qr'],0,',','.')?></h1>
                                  <small>accounts</small>
                              </div>
                          </div>
                        </div>


                        <div class="col-sm-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <div class="ibox-tools">
                                      <span class="label label-info float-right">Registered Account</span>
                                  </div>
                                  <h5>Registered Account </h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['new_reg'],0,',','.')?></h1>
                                  <small>accounts</small>
                              </div>
                          </div>
                        </div>


                        <div class="col-sm-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <div class="ibox-tools">
                                      <span class="label label-info float-right">Activated Account</span>
                                  </div>
                                  <h5>Activated Account </h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['activated'],0,',','.')?></h1>
                                  <small>accounts</small>
                              </div>
                          </div>
                        </div>

                        <div class="col-sm-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <div class="ibox-tools">
                                      <span class="label label-info float-right">Activated Account</span>
                                  </div>
                                  <h5>Organic </h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['organic'],0,',','.')?></h1>
                                  <small>accounts</small>
                              </div>
                          </div>
                        </div>

                        <div class="col-sm-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <div class="ibox-tools">
                                      <span class="label label-info float-right">Activated Account</span>
                                  </div>
                                  <h5>Referral/Event Account</h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?=number_format($effectiveness['referral'],0,',','.')?></h1>
                                  <small>accounts</small>
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
                                Affiliator Growth
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
    "super_qr"      => "#00c5a6",
    "new_reg"       => "#5eb6f0",
    "activated"     => "#c82360",
    "organic"       => "#fcb13b",
    "referral"      => "#961515",
  );

  $group_opt_label = array(
    "super_qr"      => "Super QR",
    "new_reg"       => "New Registrant",
    "activated"     => "Activated Account",
    "organic"       => "Organic",
    "referral"      => "Referral",
  );

?>
<script>
    $(document).ready(function() {

        var lineData = {
            labels:<?=json_encode($bulan_on_year)?>,
            datasets: [
              {
                    label: "<?=$group_opt_label['organic']?>",
                    backgroundColor: "<?=$group_color['organic']?>",
                    borderColor: "<?=$group_color['organic']?>",
                    pointBackgroundColor: "#ffffff",
                    pointBorderColor: "<?=$group_color['organic']?>",
                    data: <?=json_encode($data_bulan_on_year['organic'])?>
                },
              {
                  label: "<?=$group_opt_label['activated']?>",
                  backgroundColor: "<?=$group_color['activated']?>",
                  borderColor: "<?=$group_color['activated']?>",
                  pointBackgroundColor: "#ffffff",
                  pointBorderColor: "<?=$group_color['activated']?>",
                  data: <?=json_encode($data_bulan_on_year['activated'])?>
              },
              {
                    label: "<?=$group_opt_label['referral']?>",
                    backgroundColor: "<?=$group_color['referral']?>",
                    borderColor: "<?=$group_color['referral']?>",
                    pointBackgroundColor: "#ffffff",
                    pointBorderColor: "<?=$group_color['referral']?>",
                    data: <?=json_encode($data_bulan_on_year['referral'])?>
                },
              {
                    label: "<?=$group_opt_label['super_qr']?>",
                    backgroundColor: "<?=$group_color['super_qr']?>",
                    borderColor: "<?=$group_color['super_qr']?>",
                    pointBackgroundColor: "#ffffff",
                      pointBorderColor: "<?=$group_color['super_qr']?>",
                    data: <?=json_encode($data_bulan_on_year['super_qr'])?>
                },
              {
                  label: "<?=$group_opt_label['new_reg']?>",
                  backgroundColor: "<?=$group_color['new_reg']?>",
                  borderColor: "<?=$group_color['new_reg']?>",
                  pointBackgroundColor: "#ffffff",
                    pointBorderColor: "<?=$group_color['new_reg']?>",
                  data: <?=json_encode($data_bulan_on_year['new_reg'])?>
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
