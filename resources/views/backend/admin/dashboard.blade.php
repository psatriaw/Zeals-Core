<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg sidebar-content">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))

    <div class="wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                            <div>
                                <span class="pull-right text-right">
                                <small>HPP Rata-rata tertinggi: <strong>November</strong></small>
                                    <br/>
                                    Total: Rp.12.310/per Produk
                                </span>
                                <h1 class="m-b-xs chart-title">Rp. 12.123,-</h1>
                                <h3 class="font-bold">
                                    HPP Rata-rata tahun 2020
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
                        <span class="label label-primary pull-right"></span>
                        <h5>Stok Hampir Habis</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">3 </h1>
                        <div class="stat-percent font-bold text-danger"><i class="fa fa-exclamation-triangle"></i> wajib beli</div>
                        <small>bahan</small>
                    </div>
                    <div class="ibox-footer">
                        <a href="" class="block text-primary">buka detail <i class="fa fa-angle-right pull-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-primary pull-right">Hari ini</span>
                        <h5>Total Penjualan</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">16.425.000</h1>
                        <div class="stat-percent font-bold text-navy">10% <i class="fa fa-level-down"></i></div>
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
                        <h5>Pengeluaran/Pembelian</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">800.000</h1>
                        <div class="stat-percent font-bold text-info">40% <i class="fa fa-level-up"></i></div>
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
                        <h5>Permintaan</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">124</h1>
                        <!--<div class="stat-percent font-bold text-warning">16% <i class="fa fa-level-up"></i></div>-->
                        <small>Packages</small>
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
            labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli","Agustus","September","Oktober","November","Desember"],
            datasets: [
                {
                    label: "HPP Rata-rata",
                    backgroundColor: "#fccc06",
                    borderColor: "#311202",
                    pointBackgroundColor: "#fccc06",
                    pointBorderColor: "#311202",
                    data: [28, 48, 40, 19, 86, 27, 90, 35, 40, 56, 12, 0]
                }
            ]
        };

        var lineOptions = {
            responsive: true
        };


        var ctx = document.getElementById("lineChart").getContext("2d");
        new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});

    });
</script>
