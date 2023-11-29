<?php
  $main_url = $config['main_url'];

  $status   = array(
    "pending" => "<span class='text-danger'>Perencanaan <i class='fa fa-triangle'></i></span>",
    "done"    => "<span class='text-success'>Selesai <i class='fa fa-check'></i></span>"
  );
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Produksi Outlet</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Produksi Outlet</strong>
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
                        <h5>Tabel Data Produksi pada Outlet</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['create'])){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="{{ url($main_url.'/create') }}">
                                <i class="fa fa-plus"></i> tambah produksi
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="row">
                          <div class="col-sm-1">
                            <div class="input-group m-b">
                              <div class="input-group-btn bg-white">
                                  <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?=(@$input['short']=="")?$shorter[$default['short']]:$shorter[$input['short']]?> <span class="caret"></span></button>

                                  <ul class="dropdown-menu">
                                    <?php
                                      foreach($shorter as $key=>$val){
                                        ?>
                                        <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.$key.'&shortmode='.@$input['shortmode'])?>"><?=$val?></a></li>
                                        <?php
                                      }
                                    ?>
                                  </ul>
                              </div>

                              <div class="input-group-btn bg-white">
                                  <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?=(@$input['shortmode']=="")?$default['shortmode']:$input['shortmode']?> <span class="caret"></span></button>
                                  <ul class="dropdown-menu">
                                      <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=asc')?>">asc</a></li>
                                      <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=desc')?>">desc</a></li>
                                  </ul>
                              </div>

                            </div>
                          </div>
                          <div class="col-sm-7">

                          </div>
                          <div class="col-sm-4">
                              <form class="" role="form" method="GET" id="loginForm">
                                <div class="input-group m-b">
                                  <input type="text" placeholder="Search" class="input-sm form-control" name="keyword" value="<?=@$input['keyword']?>">
                                  <span class="input-group-btn">
                                    <button type="submit" class="btn btn-sm btn-primary"> Cari</button>
                                  </span>
                                </div>
                              </form>
                          </div>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Produksi</th>
                                <th>Tanggal Produksi</th>
                                <th>Quantity</th>
                                <th>Total Terjual</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;

                              $product_status = array('active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'available' =>'Tersedia','unavailable' => 'Tidak Tersedia');

                              if($page!=""){
                                $counter = ($page-1)*$limit;
                              }

                              if($data){
                                foreach ($data as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td>
                                        <?=@$value->production_code?>
                                    </td>
                                    <td><?=$value->production_date?></td>
                                    <!--<td><?=@$value->category_name?></td>-->
                                    <td><?=number_format($value->total_production,0,",",".")?></td>
                                    <td><?=number_format($value->total_sell,0,",",".")?></td>
                                    <td><?=$status[$value->status]?></td>
                                    <td style="width:250px;">
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit'])){?>
                                          <?php if($value->status=="pending"){ ?>
                                            <a href="{{ url($main_url.'/edit/'.$value->id_outlet_production) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                          <?php }else{ ?>
                                            <a href="{{ url($main_url) }}" class="btn btn-primary dim btn-xs disabled"><i class="fa fa-paste"></i> ubah</a>
                                          <?php } ?>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                          <a href="{{ url($main_url.'/manage/'.$value->id_outlet_production) }}" class="btn btn-danger btn-outline dim btn-xs"><i class="fa fa-picture-o"></i> kelola</a>
                                      <?php }?>


                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove'])){?>
                                          <a data-id="{{ $value->id_outlet_production }}" data-url="{{ url($main_url.'/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                      <?php }?>
                                    </td>
                                  </tr>
                                  <?php
                                }
                              }
                            ?>
                          </tbody>
                          </tfoot>
                        </table>
                        <?=$pagging?>
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
