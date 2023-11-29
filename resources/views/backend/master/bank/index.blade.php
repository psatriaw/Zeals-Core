<style>
  .fa{
    width:18px;
  }
  .dropdown-menu li a{
    padding-left: 10px;
  }
</style>

<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Banks</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ url('dashboard/view') }}">Dashboard</a>
                </li>
                <li class="active">
                    <strong>Banks</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Banks</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-user-create")){?>
                        <div class="ibox-tools">
                            <a class="btn btn-secondary btn-sm" href="{{ url('admin/bank/create') }}">
                                <i class="fa fa-plus"></i> Add Bank
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="row">
                        <div class="col-sm-2">
                            <div class="input-group m-b">
                                <!-- <span class="input-group-addon">Short By</span> -->
                                <div class="input-group-btn bg-white">
                                    <!-- <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?//= (@$input['short'] == "") ? $shorter[$default['short']] : $shorter[$input['short']] ?> <span class="caret"></span></button> -->

                                    <!-- <ul class="dropdown-menu"> -->
                                        <?php
                                        // foreach ($shorter as $key => $val) {
                                        ?>
                                            <li class="<?//= ($key == Request::input("short")) ? "active" : "" ?>">
                                                <!-- <a href="<?//= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . $key . '&shortmode=' . @$shortmode) ?>"><?//= $val ?></a> -->
                                            </li>
                                        <?php
                                        //}
                                        ?>
                                    <!-- </ul> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group m-b">
                                <!-- <span class="input-group-addon">Mode</span>
                                <div class="input-group-btn bg-white">
                                    <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (@$shortmode == "asc") ? "A to Z / Lowest to Highest / Old to New" : "Z to A / Highest to Lowest / New to Old" ?> <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                      <li class="<?= ("asc" == Request::input("shortmode")) ? "active" : "" ?>">
                                          <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=asc') ?>">A to Z / Lowest to Highest/ Old to New</a>
                                      </li>
                                      <li class="<?= ("desc" == Request::input("shortmode")) ? "active" : "" ?>">
                                          <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=desc') ?>">Z to A / Highest to Lowest / New to Old</a>
                                      </li>
                                    </ul>
                                </div> -->

                            </div>
                        </div>
                        <div class="col-sm-2">

                        </div>
                        <div class="col-sm-4">
                            <!-- <form class="" role="form" method="GET" id="loginForm">
                                <div class="input-group m-b">
                                    <input type="hidden" name="page" value="<?=@$input['page']?>">
                                    <input type="hidden" name="short" value="<?=@$input['short']?>">
                                    <input type="hidden" name="shortmode" value="<?=@$shortmode?>">
                                    <input type="text" placeholder="Search" class="input-sm form-control" name="keyword" value="<?= @$input['keyword'] ?>" style="height:36px;">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-sm btn-search"> Search</button>
                                    </span>
                                </div>
                            </form> -->
                        </div>
                      </div>
                      <div class="table-responsives">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Bank</th>
                                <th>ID Bank</th>
                                <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $tipeuser = ['0' => 'External', '1' => 'Internal (Bergaji)'];
                              $counter = 0;
                              // if($page!=""){
                              //   $counter = ($page-1)*$limit;
                              // }

                              if($data){
                                foreach ($data as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=strval($value->kode)?></td>
                                    <td><?=$value->nama?></td>
                                    <td><?=$value->bank_code?></td>
                                    <td class="nowrap">
                                      <div class="btn-group" role="group">
                                          <button type="button" class="btn dropdown-toggle btn-xs btn-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <i class="glyphicon glyphicon-option-horizontal"></i>
                                          </button>
                                          <ul class="dropdown-menu">
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit'])){?>
                                        <li>
                                          <a href="{{ url('admin/bank/edit/'.$value->kode) }}" class=""><i class="fa fa-paste"></i> edit</a>
                                        </li>
                                      <?php }?>
<!--
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['view'])){?>
                                          <li>
                                            <a href="{{ url('admin/bank/detail/'.$value->kode) }}" class=""><i class="fa fa-eye"></i> detail</a>
                                          </li>
                                      <?php }?> -->

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove'])){?>
                                          <li>
                                            <a data-id="{{ $value->kode }}" data-url="{{ url('admin/bank/remove/') }}" class="confirm"><i class="fa fa-trash"></i> remove</a>
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
                          </tfoot>
                        </table>
                        <?//=$pagging?>
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
    "sum-registered","sum-activated","sum-growth","sum-monthly"
  ];
  var datasetunit = [
    "Bank","Bank","From activated Last month","Last 1 month","Campaigns","IDR","IDR","IDR"
  ];

  function getData(setdata){
    var item = setdata[0];
    if(dataset.length > 0){
      $.ajax({
          url: '{{ url("api/user-resume") }}',
          type:"GET",
          dataType: 'json',
          data:{keyword:item, id_penerbit:<?=(@$login->id_brand!="")?$login->id_brand:"0"?>}
      })
      .done(function(result){

        var html = "<h1 class='no-margins'>"+(result.total)+"</h1><div class='stat-percent font-bold text-info'>"+result.percent+"</div><small>"+datasetunit[0]+"</small>";
        $("#"+item).html(html);

        dataset.splice(0,1);
        datasetunit.splice(0,1);
        getData(dataset);
      })
      .fail(function(e){
        console.log(e);
      })
      .always(function(e){

      });
    }
  }
  $(document).ready(function() {
    getData(dataset);
  });
</script>
