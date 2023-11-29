<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?php
$main_url = '';
?>
<style>
    .box-log .row {
        border-bottom: 1px solid #eee;
        margin-bottom: 10px;
        padding-bottom: 10px;
    }

    .box-log .row div {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

<div id="wrapper">
    @include('backend.menus.sidebar_menu', ['login' => $login, 'previlege' => $previlege])
    <div id="page-wrapper" class="gray-bg">
        @include('backend.menus.top', ['login' => $login, 'previlege' => $previlege])
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Microsite</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Microsite</a>
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
                    <a class="btn btn-white" href="{{ url('master/microsite') }}">
                        <i class="fa fa-angle-left"></i> back
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
                            <h1 class="no-margins"><?= number_format(@$statistic['reach']['total'], 0, ',', '.') ?></h1>

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
                            <h1 class="no-margins"><?= number_format(@$statistic['visit']['total'], 0, ',', '.') ?></h1>

                            <small>of <?= number_format(@$statistic['visit']['items']->total_item, 0, ',', '.') ?>
                                unique visitor</small>
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
                            <h1 class="no-margins"><?= number_format(@$statistic['read']['total'], 0, ',', '.') ?></h1>

                            <small>of <?= number_format(@$statistic['read']['items']->total_item, 0, ',', '.') ?>
                                readers</small>
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
                            <h1 class="no-margins"><?= number_format(@$statistic['action']['total'], 0, ',', '.') ?>
                            </h1>

                            <small>of <?= number_format(@$statistic['action']['items']->total_item, 0, ',', '.') ?>
                                actions</small>
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
                            <h1 class="no-margins">
                                <?= number_format(@$statistic['acquisition']['total'], 0, ',', '.') ?></h1>
                            <div class="stat-percent font-bold label label-info">
                                <?= number_format(@$statistic['acquisition']['percent'], 2, ',', '.') ?>% of visitor
                            </div>
                            <small>of <?= number_format(@$statistic['acquisition']['items']->total_item, 0, ',', '.') ?>
                                acquisitions</small>
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
                                <small class="stats-label"><?= date('d-m-Y H:i:s ', $value->time_created) ?></small>
                            </div>
                            <div class="col-sm-2">
                                <small class="stats-label"><?= @$value->country ?> <?= @$value->city ?></small>
                            </div>
                            <div class="col-sm-2">
                                <small class="stats-label"><?= $value->type_conversion ?></small>
                            </div>
                            <div class="col-sm-3">
                                <small class="stats-label"><?= @$value->campaign_title ?></small>
                            </div>
                            <div class="col-sm-2">
                                <small class="stats-label"><?= $value->ip ?></small>
                            </div>
                        </div>
                        <?php
                            }
                          }
                        ?>
                    </div>
                </div>
            </div>
            <div>
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="text-black">Graphic Statistic</h2>
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
                                    <td class='text-left'><?= date('d/m/Y H:i:s A', $value->time_created) ?></td>
                                    <td class='text-left'><?= $value->type_conversion ?></td>
                                    <td class='text-left'><?= @$value->first_name ?></td>
                                    <td class='text-left'><?= $value->ip ?></td>
                                    <td class='text-left'><?= $value->city ?></td>
                                    <!--<td><?= $value->encrypted_code ?></td>-->
                                    <td class='text-right'>
                                        Rp.<?= number_format($value->commission + $value->fee, 0, ',', '.') ?></td>
                                    <td class='text-left'><?= $value->browser ?></td>
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

<?php
$group_color = [
    'reach' => '#00c5a6',
    'visit' => '#5eb6f0',
    'read' => '#c82360',
    'action' => '#fcb13b',
    'acquisition' => '#961515',
];

$group_opt_label = [
    'reach' => 'Reach',
    'visit' => 'Visit',
    'read' => 'Read/Stay',
    'action' => 'Action/Click',
    'acquisition' => 'Acquisition',
];

$group_match = [];
$group_pie = [];
$group_label = [];
if ($logs_group) {
    foreach ($logs_group as $key => $value) {
        if ($value->type_conversion != 'initial' && $value->type_conversion != 'custom') {
            $group_pie[] = $value->total_data;
            $group_match[] = $group_color[$value->type_conversion];
            $group_label[] = $group_opt_label[$value->type_conversion];
        }
    }
}
?>

<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<script>
    var lineData = {
        labels: <?= json_encode($label_chart) ?>,
        datasets: [{
                label: "Acquisition",
                backgroundColor: "#961515",
                borderColor: "#961515",
                pointBackgroundColor: "#ffffff",
                pointBorderColor: "#961515",
                data: < ? = json_encode(@$chart['acquisition']) ? >
            },
            {
                label: "Action",
                backgroundColor: "#fcb13b",
                borderColor: "#fcb13b",
                pointBackgroundColor: "#ffffff",
                pointBorderColor: "#fcb13b",
                data: < ? = json_encode(@$chart['action']) ? >
            },
            {
                label: "Stay/Read",
                backgroundColor: "#c82360",
                borderColor: "#c82360",
                pointBackgroundColor: "#ffffff",
                pointBorderColor: "#c82360",
                data: < ? = json_encode(@$chart['read']) ? >
            },
            {
                label: "Visits",
                backgroundColor: "#5eb6f0",
                borderColor: "#5eb6f0",
                pointBackgroundColor: "#ffffff",
                pointBorderColor: "#5eb6f0",
                data: < ? = json_encode(@$chart['visit']) ? >
            },
            {
                label: "Reach",
                backgroundColor: "#4fc1ae",
                borderColor: "#188371",
                pointBackgroundColor: "#4fc1ae",
                pointBorderColor: "#188371",
                data: < ? = json_encode(@$chart['reach']) ? >
            }
        ]
    };


    var lineOptions = {
        responsive: true
    };


    var ctx = document.getElementById("lineChart").getContext("2d");
    new Chart(ctx, {
        type: 'line',
        data: lineData,
        options: lineOptions
    });

    var lineData = {
        labels: <?= json_encode($group_label) ?>,
        datasets: [{
            backgroundColor: <?= json_encode($group_match) ?>,
            borderColor: <?= json_encode($group_match) ?>,
            pointBackgroundColor: <?= json_encode($group_match) ?>,
            pointBorderColor: <?= json_encode($group_match) ?>,
            data: <?= json_encode($group_pie) ?>
        }]
    };

    var lineOptions = {
        responsive: true
    };


    var ctx = document.getElementById("log_pie").getContext("2d");
    new Chart(ctx, {
        type: 'pie',
        data: lineData,
        options: lineOptions
    });
</script>
