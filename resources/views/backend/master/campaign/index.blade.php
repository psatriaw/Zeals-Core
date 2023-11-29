<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .fa {
        width: 18px;
    }

    .dropdown-menu li a {
        padding-left: 10px;
    }
</style>
<?php
  $main_url = $config['main_url'];
  $campaign_type = array(
      "banner"       => "AMP - Tracker",
      "o2o"          => "O2O - Outlet",
      "shopee"       => "AMP - Shopee",
      "event"         => "Event - O2O",
  );

  $campaign_type_colors = array(
    'banner'  => '#5eb6f0',
    'o2o'     => '#961516',
    'shopee'  => '#ff810e',
    'event'   => '#23c6c7'
  );
?>
<div id="wrapper">
    @include('backend.menus.sidebar_menu', ['login' => $login, 'previlege' => $previlege])
    <div id="page-wrapper" class="gray-bg">
        @include('backend.menus.top', ['login' => $login, 'previlege' => $previlege])
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Campaign</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Campaign</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">

        {!! Form::open(['url' => url('master/campaign/'), 'method' => 'get', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
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

            <div class="row">
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">running</span>
                            <h5>Total Running Campaign</h5>
                        </div>
                        <div class="ibox-content" id="sum-running">
                            <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-danger pull-right">stopped</span>
                            <h5>Total Stopped Campaign</h5>
                        </div>
                        <div class="ibox-content" id="sum-stopped">
                            <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-danger pull-right">expired</span>
                            <h5>Total Expired Campaign</h5>
                        </div>
                        <div class="ibox-content" id="sum-expired">
                            <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-default pull-right">drafted</span>
                            <h5>Total Drafted Campaign</h5>
                        </div>
                        <div class="ibox-content" id="sum-drafted">
                            <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-info pull-right">revenue</span>
                            <h5>Total Revenue</h5>
                        </div>
                        <div class="ibox-content" id="sum-revenue">
                            <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">paid</span>
                            <h5>Total Paid</h5>
                        </div>
                        <div class="ibox-content" id="sum-paid">
                            <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-danger pull-right">outstanding</span>
                            <h5>Total Outstanding</h5>
                        </div>
                        <div class="ibox-content" id="sum-outstanding">
                            <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Campaigns</h5>
                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['create'])) { ?>
                            <div class="ibox-tools">
                                <a class="btn btn-secondary btn-sm" href="{{ url('master/campaign/choose/create') }}">
                                    <i class="fa fa-plus"></i> create campaign
                                </a>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="ibox-content">
                            @include('backend.flash_message')
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="input-group m-b">
                                        <span class="input-group-addon">Sort By</span>
                                        <div class="input-group-btn bg-white">
                                            <button data-toggle="dropdown"
                                                class="btn btn-white dropdown-toggle btn-sm bg-white" type="button"
                                                aria-expanded="false"><?= @$input['short'] == '' ? $shorter[$default['short']] : $shorter[$input['short']] ?>
                                                <span class="caret"></span></button>

                                            <ul class="dropdown-menu">
                                                <?php
                                              foreach ($shorter as $key => $val) {
                                              ?>
                                                <li class="<?= $key == Request::input('short') ? 'active' : '' ?>">
                                                    <a
                                                        href="<?= url($main_url . '/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . $key . '&shortmode=' . @$shortmode.'&dates='.$start_date." - ".$end_date) ?>"><?= $val ?></a>
                                                </li>
                                                <?php
                                              }
                                              ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group m-b">
                                        <span class="input-group-addon">Mode</span>
                                        <div class="input-group-btn bg-white">
                                            <button data-toggle="dropdown"
                                                class="btn btn-white dropdown-toggle btn-sm bg-white" type="button"
                                                aria-expanded="false"><?= @$shortmode == 'asc' ? 'A to Z / Lowest to Highest / Old to New' : 'Z to A / Highest to Lowest / New to Old' ?>
                                                <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li class="<?= 'asc' == Request::input('shortmode') ? 'active' : '' ?>">
                                                    <a
                                                        href="<?= url($main_url . '/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=asc&dates='.$start_date." - ".$end_date) ?>">A
                                                        to Z / Lowest to Highest/ Old to New</a>
                                                </li>
                                                <li
                                                    class="<?= 'desc' == Request::input('shortmode') ? 'active' : '' ?>">
                                                    <a
                                                        href="<?= url($main_url . '/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=desc&dates='.$start_date." - ".$end_date) ?>">Z
                                                        to A / Highest to Lowest / New to Old</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                </div>
                                <div class="col-sm-4">
                                    <form class="" role="form" method="GET" id="loginForm">
                                        <div class="input-group m-b">
                                            <input type="hidden" name="page" value="<?= @$input['page'] ?>">
                                            <input type="hidden" name="short" value="<?= @$input['short'] ?>">
                                            <input type="hidden" name="shortmode" value="<?= @$shortmode ?>">
                                            <input type="hidden" name="dates" value="<?= @$dates ?>">
                                            <input type="text" placeholder="Search" class="input-sm form-control"
                                                name="keyword" value="<?= @$input['keyword'] ?>"
                                                style="height:36px;">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-sm btn-search"> Search</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table
                                    class="table table-striped table-bordered table-hover dataTables-example table-nowrap"
                                    id="datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Title</th>
                                            <th>ID</th>
                                            <th>Brand</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Type</th>
                                            <th>Budget</th>
                                            <th>Running Budget</th>
                                            <th>Revenue</th>
                                            <th>Affs</th>
                                            <th>Status</th>
                                            <th>Updated</th>
                                            <th>Action</th>
                                            <th>Reach</th>
                                            <th>Visit</th>
                                            <th>Interest</th>
                                            <th>Request</th>
                                            <th>Acquisition</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $runningstatus = array(
                                            "close"     => "<span class='text-danger'><i class='fa fa-ban'></i> Drafted</span>",
                                            "closed"    => "<span class='text-danger'><i class='fa fa-ban'></i> Stopped</span>",
                                            "open"      => "<span class='text-success'><i class='fa fa-check'></i> Running</span>",
                                            "expired"   => "<span class='text-danger'><i class='fa fa-ban'></i> Expired</span>",
                                            "released"  => "<span class='text-info'><i class='fa fa-check'></i> Rilis Pendanaan</span>"
                                        );

                                        $tipeuser = ['0' => 'External', '1' => 'Internal (Bergaji)'];
                                        $counter = 0;
                                        if ($page != "") {
                                            $counter = ($page - 1) * $limit;
                                        }

                                        if ($data) {
                                            foreach ($data as $key => $value) {
                                                $counter++;
                                                if($value->running_status=='open'){
                                                  if(strtotime($value->end_date." 23:59:59")< time()){
                                                    $value->running_status = "expired";
                                                  }
                                                }

                                                if($value->release_status=="released"){
                                                  $value->running_status = 'released';
                                                }
                                                ?>
                                        <tr>
                                            <td>{{ $counter }}</td>
                                            <td class="ellipsis"><strong>[{{ $value->affiliate_id }}]</strong>
                                                {{ $value->campaign_title }}</td>
                                            <td><strong><?= $value->campaign_link ?></strong></td>
                                            <td>{{ $value->nama_penerbit }}</td>
                                            <td>{{ $value->start_date }}</td>
                                            <td>{{ $value->end_date }}</td>
                                            <td><span class='label'
                                                    style='color:#fff;background:{{ @$campaign_type_colors[$value->campaign_type] }};'>{{ @$campaign_type[$value->campaign_type] }}</span>
                                            </td>
                                            <td class="text-right">Rp.{{ number_format($value->budget) }}</td>
                                            <td class="text-right">Rp.{{ number_format(@$value->running_budget) }}
                                            </td>
                                            <td class="text-right">Rp.{{ number_format(@$value->system_rev) }}</td>
                                            <td class="nowrap"><?= number_format($value->joined, 0, ',', '.') ?></td>
                                            <td class="nowrap"><?= $runningstatus[$value->running_status] ?></td>
                                            <td><?= date('Y-m-d H:i', $value->last_update) ?></td>
                                            <td class="nowrap">

                                                <!--
                                                        <?php if($previlege->isAllow($login->id_user, $login->id_department, $config['view'])) { ?>
                                                            <a href="{{ url('master/campaign/detail/' . $value->id_campaign) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-eye"></i> detail</a>
                                                        <?php } ?>
                                                        -->



                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn dropdown-toggle btn-xs btn-info"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="glyphicon glyphicon-option-horizontal"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <?php if($previlege->isAllow($login->id_user, $login->id_department, @$config['edit'])) { ?>
                                                        <?php if($value->running_status=="closed" || $value->running_status=="close"){ ?>
                                                        <li>
                                                            <a
                                                                href="{{ url('master/campaign/edit/' . $value->campaign_link) }}"><i
                                                                    class="fa fa-paste"></i> edit</a>
                                                        </li>
                                                        <?php }else{ ?>
                                                        <li class="disabled">
                                                            <a disabled><i class="fa fa-paste"></i> edit</a>
                                                        </li>
                                                        <?php }?>
                                                        <?php } ?>
                                                        <?php if($previlege->isAllow($login->id_user, $login->id_department, @$config['boost'])) { ?>
                                                        <li class="text-info">
                                                            <a
                                                                href="{{ url('master/campaign/boost/' . $value->campaign_link) }}"><i
                                                                    class="fa fa-bolt"></i> boost</a>
                                                        </li>
                                                        <?php } ?>
                                                        <li>
                                                            <a href="{{ url($main_url . '/resume/' . $value->campaign_link) }}"
                                                                title="deactivate data <?= $value->title ?>"><i
                                                                    class="fa fa-cogs"></i> manage </a></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url($main_url . '/report/' . $value->campaign_link) }}"
                                                                title="deactivate data <?= $value->title ?>"><i
                                                                    class="fa fa-chart-pie"></i> report </a></a>
                                                        </li>
                                                        <li>
                                                            <?php if($value->running_status=="closed" || $value->running_status=="close"){ ?>
                                                            <a data-url="{{ url('master/campaign/setrun/?backlink=master/campaign/resume/' . $value->campaign_link) }}"
                                                                data-id="<?= $value->id_campaign ?>" class="confirm"
                                                                type="submit"><i class="fa fa-play"></i> Run</a>
                                                            <?php }else{ ?>
                                                            <a data-url="{{ url('master/campaign/setclose/?backlink=master/campaign/resume/' . $value->campaign_link) }}"
                                                                data-id="<?= $value->id_campaign ?>" class="confirm"
                                                                type="submit"><i class="fa fa-stop"></i> Stop</a>
                                                            <?php } ?>
                                                        </li>

                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['remove'])) { ?>
                                                        <li class="text-danger">
                                                            <a data-id="{{ $value->id_campaign }}"
                                                                data-url="{{ url('master/campaign/remove/' . $value->id_campaign) }}"
                                                                class="confirm"><i class="fa fa-trash"></i> remove</a>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td class="text-right">{{ number_format(@$value->total_reach) }}</td>
                                            <td class="text-right">{{ number_format(@$value->total_visit) }}</td>
                                            <td class="text-right">{{ number_format(@$value->total_read) }}</td>
                                            <td class="text-right">{{ number_format(@$value->total_action) }}</td>
                                            <td class="text-right">
                                                {{ $value->campaign_type == 'o2o' ? number_format(@$value->total_usage) : number_format(@$value->total_acquisition) }}
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    </tfoot>
                                </table>
                                <?= $pagging ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('backend.do_confirm')
        @include('backend.footer')
    </div>
</div>
<script>
    $('#date').daterangepicker({
        locale: {
          format: 'DD-MM-YYYY',
        }
    });

    var dataset = [
        "sum-running", "sum-budget", "sum-stopped", "sum-expired", "sum-drafted", "sum-paid", "sum-revenue",
        "sum-outstanding"
    ];
    var datasetunit = [
        "Campaigns", "IDR", "Campaigns", "Campaigns", "Campaigns", "IDR", "IDR", "IDR"
    ];

    function getData(setdata) {
        var item = setdata[0];
        if (dataset.length > 0) {
            $.ajax({
                    url: '{{ url('api/campaign-resume') }}',
                    type: "GET",
                    dataType: 'json',
                    data: {
                        keyword: item,
                        id_penerbit: <?= @$login->id_brand != '' ? $login->id_brand : '0' ?>,
                        dates: '<?=$dates?>'
                    }
                })
                .done(function(result) {

                    var html = "<h1 class='no-margins'>" + (result.total) +
                        "</h1><div class='stat-percent font-bold text-info'>" + result.percent + "</div><small>" +
                        datasetunit[0] + "</small>";
                    $("#" + item).html(html);

                    dataset.splice(0, 1);
                    datasetunit.splice(0, 1);
                    getData(dataset);
                })
                .fail(function(e) {
                    console.log(e);
                })
                .always(function(e) {

                });
        }
    }
    $(document).ready(function() {
        getData(dataset);
    });
</script>
