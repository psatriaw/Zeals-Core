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
?>

<div id="wrapper">
  @include('backend.menus.sidebar_menu', ['login' => $login, 'previlege' => $previlege])
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top', ['login' => $login, 'previlege' => $previlege])
    <div class="row wrapper border-bottom white-bg page-heading">
      <div class="col-lg-10">
        <h2>Microsites</h2>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('dashboard/view') }}">Dashboard</a>
          </li>
          <li class="active">
            <strong>Microsite</strong>
          </li>
        </ol>
      </div>
      <div class="col-lg-2">

      </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="row">
          <div class="col-lg-3">
            <div class="ibox float-e-margins">
              <div class="ibox-title">
                <span class="label label-success pull-right">drafted</span>
                <h5>Total Drafted</h5>
              </div>
              <div class="ibox-content" id="sum-drafted">
                <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
              </div>
            </div>
          </div>

          <div class="col-lg-3">
            <div class="ibox float-e-margins">
              <div class="ibox-title">
                <span class="label label-danger pull-right">pending</span>
                <h5>Total Pending</h5>
              </div>
              <div class="ibox-content" id="sum-pending">
                <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
              </div>
            </div>
          </div>

          <div class="col-lg-3">
            <div class="ibox float-e-margins">
              <div class="ibox-title">
                <span class="label label-danger pull-right">review</span>
                <h5>Total Review</h5>
              </div>
              <div class="ibox-content" id="sum-review">
                <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
              </div>
            </div>
          </div>

          <div class="col-lg-3">
            <div class="ibox float-e-margins">
              <div class="ibox-title">
                <span class="label label-default pull-right">running</span>
                <h5>Total Running</h5>
              </div>
              <div class="ibox-content" id="sum-running">
                <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
              </div>
            </div>
          </div>

          <div class="col-lg-3">
            <div class="ibox float-e-margins">
              <div class="ibox-title">
                <span class="label label-default pull-right">stopped</span>
                <h5>Total Stopped</h5>
              </div>
              <div class="ibox-content" id="sum-stopped">
                <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
              </div>
            </div>
          </div>

          <div class="col-lg-3">
            <div class="ibox float-e-margins">
              <div class="ibox-title">
                <span class="label label-default pull-right">expired</span>
                <h5>Total Expired</h5>
              </div>
              <div class="ibox-content" id="sum-expired">
                <i class="fa fa-spinner fa-spin fa-duotone"></i> loading
              </div>
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="ibox float-e-margins">
              <div class="ibox-title">
                <h5>List of Microsite</h5>
                <?php if ($previlege->isAllow($login->id_user, $login->id_department, "penerbit-create")) { ?>
                <div class="ibox-tools">
                  <a class="btn btn-secondary btn-sm" href="{{ url('master/microsite/create') }}">
                    <i class="fa fa-plus"></i> Create Microsite
                  </a>
                </div>
                <?php } ?>
              </div>
              <div class="ibox-content">
                @include('backend.flash_message')
                <div class="row">
                  <div class="tabel-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>ID Website</th>
                          <th>Website Name</th>
                          <th>Brand</th>
                          <th>Start</th>
                          <th>End</th>
                          <th>Status</th>
                          <th>action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $counter = 0;
                          if($list){
                            foreach($list as $key => $value){
                              $counter++;
                        ?>
                        <tr>
                          <td><?=$counter?></td>
                          <td><?=$value->id_microsite?></td>
                          <td><?=$value->nama_microsite?></td>
                          <td><?=$value->nama_penerbit?></td>
                          <td><?=$value->start_date?></td>
                          <td><?=$value->end_date?></td>
                          <td><?=$value->status?></td>
                          <td class="nowrap">
                            <div class="btn-group" role="group">
                              <button type="button" class="btn dropdown-toggle btn-xs btn-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="glyphicon glyphicon-option-horizontal"></i>
                              </button>
                              <ul class="dropdown-menu">
                                <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit'])){?>
                                <li>
                                  <a href="{{ url('master/microsite/edit/'.$value->id_microsite) }}" class=""><i class="fa fa-paste"></i> edit</a>
                                </li>
                                <?php }?>

                                <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit'])){?>
                                <li>
                                  <a href="{{ url('master/microsite/manage/'.$value->id_microsite) }}" class=""><i class="fa fa-eye"></i> manage</a>
                                </li>
                                <?php }?>

                                <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['report'])){?>
                                <li>
                                  <a href="{{ url('master/microsite/report/'.$value->id_microsite) }}" class=""><i class="fa fa-eye"></i> report</a>
                                </li>
                                <?php }?>

                                <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove'])){?>
                                <li class="text-danger">
                                  <a data-id="{{ $value->id_microsite }}" data-url="{{ url('master/microsite/remove/') }}" class="confirm"><i class="fa fa-trash"></i> remove</a>
                                </li>
                                <?php }?>
                              </ul>
                            </div>
                          </td>
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
      </div>
    </div>
      @include('backend.do_confirm')
      @include('backend.footer')
  </div>
</div>
<script>
    var dataset = [
      "sum-drafted", "sum-pending", "sum-review", "sum-running", "sum-stopped", "sum-expired"
    ];
    var datasetunit = [
        "Sites", "Sites", "Sites", "Sites", "Sites", "Sites"
    ];

    function getData(setdata) {
        var item = setdata[0];
        if (dataset.length > 0) {
            $.ajax({
                    url: '{{ url('api/site-resume') }}',
                    type: "GET",
                    dataType: 'json',
                    data: {
                        keyword: item,
                        id_penerbit: <?= @$login->id_brand != '' ? $login->id_brand : '0' ?>
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
