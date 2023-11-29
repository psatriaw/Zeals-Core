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
                    {!! Form::open(['url' => url($config['main_url']), 'method' => 'get', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
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
                        <div class="ibox-tools">
                            <input type="text" class="form-control-sm form-control" id="date" name="dates" value="<?=$dates?>">
                        </div>
                      </div>
                      <div class="col-lg-2">
                        <button class="btn btn-primary btn-block">
                            <i class="fa fa-search"></i> siapkan data dashboard
                        </button>
                      </div>
                    </div>
                    {!! Form::close() !!}
                  </div>
                  <br>

                    <div class="ibox-content">
                            <div>
                                <h1 class="m-b-xs chart-title"></h1>
                                <h3 class="font-bold">
                                    Biaya Pengeluaran Item dari Logistik
                                </h3>
                                <!--
                                <small>Sales marketing.</small>
                                -->
                            </div>

                        <div>
                            <canvas id="lineChart" height="70"></canvas>
                        </div>

                        <div class="m-t-md">
                            <small class="pull-right">
                                <i class="fa fa-clock-o"> </i>
                                Update per <?=date("d M Y H:i:s")?>
                            </small>
                           <small>
                               <strong>Sumber data:</strong> database sistem.
                           </small>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="row">

            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-primary pull-right">Hari ini</span>
                        <h5>Total Biaya Pengeluaran</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=number_format(@$total_biaya_pengeluaran_hari_ini)?></h1>
                        <div class="stat-percent font-bold text-navy"> <i class="fa fa-level-down"></i></div>
                        <small>dalam rupiah</small>
                    </div>
                    <div class="ibox-footer">
                        <a href="" class="block text-primary">buka detail <i class="fa fa-angle-right pull-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">Hari ini</span>
                        <h5>Pembelian/Belanja</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=number_format(@$total_belanaja_hari_ini)?></h1>
                        <div class="stat-percent font-bold text-info"> <i class="fa fa-level-up"></i></div>
                        <small>dalam rupiah</small>
                    </div>
                    <div class="ibox-footer">
                        <a href="" class="block text-primary">buka detail <i class="fa fa-angle-right pull-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-warning pull-right">Hari ini</span>
                        <h5>Permintaan PPIC</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=number_format(@$total_permintaan_ppic)?></h1>
                        <!--<div class="stat-percent font-bold text-warning">16% <i class="fa fa-level-up"></i></div>-->
                        <small>permintaan</small>
                    </div>
                    <div class="ibox-footer">
                        <a href="" class="block text-primary">buka detail <i class="fa fa-angle-right pull-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-warning pull-right">Hari ini</span>
                        <h5>Permintaan Resep</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=number_format(@$total_permintaan_resep)?></h1>
                        <!--<div class="stat-percent font-bold text-warning">16% <i class="fa fa-level-up"></i></div>-->
                        <small>permintaan</small>
                    </div>
                    <div class="ibox-footer">
                        <a href="" class="block text-primary">buka detail <i class="fa fa-angle-right pull-right"></i></a>
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
    $(document).ready(function() {

        var lineData = {
            labels: <?=json_encode(@$data_date_14_hari)?>,
            datasets: [
                {
                    label: "Pengeluaran Harian",
                    backgroundColor: "#fccc06",
                    borderColor: "#311202",
                    pointBackgroundColor: "#fccc06",
                    pointBorderColor: "#311202",
                    data: <?=json_encode(@$data_pengeluaran_14_hari)?>
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
            format: 'YYYY/MM/DD'
          }
        });
    });
</script>
