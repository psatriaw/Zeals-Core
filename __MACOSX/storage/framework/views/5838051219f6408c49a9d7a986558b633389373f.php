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

        <div class="wrapper-content">
          <div class="row">
          <div class="col-lg-2">
              <div class="ibox ">
                  <div class="ibox-title">
                      <div class="ibox-tools">
                          <span class="label label-info float-right">Bulan Ini</span>
                      </div>
                      <h5>Project Berjalan </h5>
                  </div>
                  <div class="ibox-content">
                      <h1 class="no-margins">3</h1>
                      <div class="stat-percent font-bold">67.38% <i class="fa fa-bolt"></i></div>
                      <small>project</small>
                  </div>
              </div>
          </div>
          <div class="col-lg-2">
              <div class="ibox ">
                  <div class="ibox-title">
                      <div class="ibox-tools">
                          <span class="label label-info float-right">Bulan ini</span>
                      </div>
                      <h5>Penambahan Akun</h5>
                  </div>
                  <div class="ibox-content">
                      <h1 class="no-margins">135</h1>
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
                      <h5>Transaksi Influencer</h5>
                  </div>
                  <div class="ibox-content">

                      <div class="row">
                          <div class="col-md-6">
                              <h1 class="no-margins">Rp.2.506K</h1>
                              <div class="font-bold">12% <i class="fa fa-level-up"></i> <small>Pencairan</small></div>
                          </div>
                          <div class="col-md-6">
                              <h1 class="no-margins">Rp.206,120K</h1>
                              <div class="font-bold">88% <i class="fa fa-level-up"></i> <small>Saldo Influencer</small></div>
                          </div>
                      </div>


                  </div>
              </div>
          </div>
          <div class="col-lg-4">
              <div class="ibox ">
                  <div class="ibox-title">
                      <h5>Project Tereksekusi</h5>
                      <div class="ibox-tools">
                          <span class="label label-info">Per <?=date("Y-m-d H:i")?></span>
                      </div>
                  </div>
                  <div class="ibox-content text-right">
                      <h1 class="no-margins">Rp.2.304.575.000</h1>
                      <div class="font-bold ">Budget project diselesaikan</div>
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
                          <small>Kemampuan Kinerja <strong>Influencer Zeals</strong></small>
                              <br/>
                              Seluruh Data : 162,862 data
                          </span>
                          <h3 class="font-bold no-margins">
                              Tahun 2021
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
                                          <h2 class="no-margins">223,346</h2>
                                          <small>Total tracking dalam periode</small>
                                          <div class="progress progress-mini">
                                              <div class="progress-bar" style="width: 48%;"></div>
                                          </div>
                                      </li>
                                      <li>
                                          <h2 class="no-margins ">41,422</h2>
                                          <small>Total action dalam periode</small>
                                          <div class="progress progress-mini">
                                              <div class="progress-bar" style="width: 60%;"></div>
                                          </div>
                                      </li>
                                      <li>
                                          <h2 class="no-margins ">45.23%</h2>
                                          <small>Nilai efektifitas konversi Read/Stay</small>
                                          <div class="progress progress-mini">
                                              <div class="progress-bar" style="width: 45.23%;"></div>
                                          </div>
                                      </li>
                                      <li>
                                          <h2 class="no-margins ">15.23%</h2>
                                          <small>Nilai efektifitas konversi Action</small>
                                          <div class="progress progress-mini">
                                              <div class="progress-bar" style="width: 15.23%;"></div>
                                          </div>
                                      </li>
                                      <li>
                                          <h2 class="no-margins ">8.23%</h2>
                                          <small>Nilai efektifitas konversi Akuisisi</small>
                                          <div class="progress progress-mini">
                                              <div class="progress-bar" style="width: 8.23%;"></div>
                                          </div>
                                      </li>
                                      <li>
                                          <h2 class="no-margins ">103.2</h2>
                                          <small>Rata-rata influencer yang terlibat per periode</small>
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
                      <h5>Aktivitas Influencer</h5>
                  </div>
                  <div class="ibox-content">
                    <div>
                        <canvas id="log_pie" style="height:400px;"></canvas>
                    </div>
                  </div>
                  <div class="ibox-content box-log" style="height:347px;overflow-y:scroll;">
                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:31</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Pandu Satria Wiguna</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP - Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:14</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Tommy</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:13</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Tommy</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:13</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Tommy</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:31</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Pandu Satria Wiguna</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:14</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Tommy</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:13</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Tommy</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:13</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Tommy</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:13</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Tommy</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:13</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Tommy</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:31</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Pandu Satria Wiguna</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:14</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Tommy</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:13</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Tommy</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-3">
                            <small class="stats-label">28-09-2021 10:13</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">Tommy</small>
                          </div>

                          <div class="col-sm-3">
                              <small class="stats-label">AMP Maranatha PSB</small>
                          </div>
                          <div class="col-sm-3">
                              <small class="stats-label">140.154.151.012</small>
                          </div>
                      </div>
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
                  <h5>Traffic Persebaran Project</h5>
              </div>
              <div class="ibox-content box-log" style="height:402px;overflow-y:scroll;">
                  <div class="row">
                      <div class="col-sm-4">
                        <span class="stats-label text-strong">Kota</span>
                      </div>
                      <div class="col-sm-2 text-right text-strong">
                          <span class="stats-label">visits</span>
                      </div>
                      <div class="col-sm-2 text-right text-strong">
                          <span class="stats-label">reads/stay</span>
                      </div>
                      <div class="col-sm-2 text-right text-strong">
                          <span class="stats-label">action</span>
                      </div>
                      <div class="col-sm-2 text-right text-strong">
                          <span class="stats-label">akuisisi</span>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-sm-4">
                        <small class="stats-label">DKI Jakarta</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">13.421</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">3.421</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">2.938</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">1.872</small>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-sm-4">
                        <small class="stats-label">Aceh</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">1.493</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">431</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">237</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">153</small>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-sm-4">
                        <small class="stats-label">Bandung</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">1.123</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">422</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">344</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">251</small>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-sm-4">
                        <small class="stats-label">DI Yogyakarta</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">1.103</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">401</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">397</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">356</small>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-sm-4">
                        <small class="stats-label">Jawa Barat</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">983</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">648</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">372</small>
                      </div>
                      <div class="col-sm-2 text-right">
                          <small class="stats-label">138</small>
                      </div>
                  </div>
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
                          <th>Jenis Project</th>
                          <th>Nilai Budget </th>
                          <th>Progress </th>
                          <th>Status </th>
                          <th>Total Influencer </th>
                          <th>Mulai</th>
                          <th>Selesai</th>
                          <th>Aksi</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                          <td>1</td>
                          <td>Project <small>This is example of project</small></td>
                          <td>IndoFood</td>
                          <td>O2O</td>
                          <td class="text-right">Rp. 140.000.000</td>
                          <td><span class="pie">0.52/1.561</span></td>
                          <td>90%</td>
                          <td>143</td>
                          <td>Jul 14, 2021</td>
                          <td>Desember 14, 2021</td>
                          <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                      </tr>
                      <tr>
                          <td>2</td>
                          <td>Maranatha PSB </td>
                          <td>Maranatha</td>
                          <td>AMP</td>
                          <td class="text-right">Rp. 100.000.000</td>
                          <td><span class="pie">51,52</span></td>
                          <td>90%</td>
                          <td>193</td>
                          <td>Agustus 18, 2021</td>
                          <td>September 18, 2021</td>
                          <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                      </tr>
                      <tr>
                          <td>3</td>
                          <td>BSI Integration Web Tracking </td>
                          <td>BSI</td>
                          <td>AMP</td>
                          <td class="text-right">Rp. 120.000.000</td>
                          <td><span class="pie">32,22</span></td>
                          <td>90%</td>
                          <td>124</td>
                          <td>September 08, 2021</td>
                          <td>Desember 18, 2021</td>
                          <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                      </tr>
                      <tr>
                          <td>4</td>
                          <td>BSI Registration Offline</td>
                          <td>BSI</td>
                          <td>O2O</td>
                          <td class="text-right">Rp. 150.000.000</td>
                          <td><span class="pie">72,32</span></td>
                          <td>90%</td>
                          <td>192</td>
                          <td>September 08, 2021</td>
                          <td>Desember 18, 2021</td>
                          <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                      </tr>
                      </tbody>
                  </table>
              </div>

          </div>
          </div>
          </div>

        </div>
        </div>
        <?php echo $__env->make('backend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/mapdata/countries/id/id-all.js"></script>

<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script src="<?php echo e(url('templates/admin/js/plugins/chartJs/Chart.min.js')); ?>"></script>
<script>
      var lineData = {
          labels:['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'],
          datasets: [{
                label: "Akuisisi",
                backgroundColor: "#961515",
                borderColor: "#961515",
                pointBackgroundColor: "#ffffff",
                pointBorderColor: "#961515",
                data: [35, 44, 38, 31, 15, 15]
            },
            {
                label: "Action",
                backgroundColor: "#fcb13b",
                borderColor: "#fcb13b",
                pointBackgroundColor: "#ffffff",
                pointBorderColor: "#fcb13b",
                data: [50,60,60,42,21,33]
            },
            {
                  label: "Stay/Read",
                  backgroundColor: "#c82360",
                  borderColor: "#c82360",
                  pointBackgroundColor: "#ffffff",
                  pointBorderColor: "#c82360",
                  data: [110, 130, 83, 65, 72, 43]
              },
            {
                  label: "Visits",
                  backgroundColor: "#5eb6f0",
                  borderColor: "#5eb6f0",
                  pointBackgroundColor: "#ffffff",
                  pointBorderColor: "#5eb6f0",
                  data: [120, 190, 130, 95, 122, 83]
              }
          ]
      };

      var lineOptions = {
          responsive: true
      };


      var ctx = document.getElementById("lineChart").getContext("2d");
      new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});



      var lineData = {
          labels:['visit','read/stay','action','acquisition'],
          datasets: [
            {
                label: "Action",
                backgroundColor: ["#5eb6f0","#c82360","#fcb13b","#961515"],
                borderColor: ["#5eb6f0","#c82360","#fcb13b","#961515"],
                pointBackgroundColor: ["#5eb6f0","#c82360","#fcb13b","#961515"],
                pointBorderColor: ["#5eb6f0","#c82360","#fcb13b","#961515"],
                data: [60,25,10,5]
            }
          ]
      };

      var lineOptions = {
          responsive: true
      };


      var ctx = document.getElementById("log_pie").getContext("2d");
      new Chart(ctx, {type: 'pie', data: lineData, options:lineOptions});

      var data = [];

      Highcharts.mapChart('mapcontainer', {
        chart: {
          height:400,
            map: 'countries/id/id-all'
        },

        title: {
            text: 'Peta Wilayah Persebaran Domisili Influencer'
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
            minColor: '#5eb6f0',
            maxColor: '#5eb6f085',
        },

        series: [{
            data: data,
            name: 'Random data',
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/admin/dashboard/main.blade.php ENDPATH**/ ?>