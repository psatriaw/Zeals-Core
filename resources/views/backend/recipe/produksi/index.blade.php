<?php
  $main_url = $config['main_url'];
  $bgstatus = array(
    "done"        => "",
    "active"      => "",
    "cancelled"   => "danger"
  );

  $statuses = array(
    "active"      => "Direncanakan",
    "done"        => "Sudah dilakukan",
    "cancelled"   => "Dibatalkan"
  );
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Produksi Stock</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li class="active">
                        <strong>Produksi Stock</strong>
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
                        <h5>Tabel Data Stock</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['create'])){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="{{ url($main_url.'/create') }}">
                                <i class="fa fa-plus"></i> tambah hasil produksi
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
                                <th>Tgl Produksi</th>
                                <th>Items</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Tanggal dibuat</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;

                              $productionb_status = array('queue' => 'Dalam Antrian', "production" => "Proses Produksi","ready" => "Selesai Produksi","packed" => "Siap/Packing","shipted" => "Dikirim", "received" => "Diterima");

                              if($page!=""){
                                $counter = ($page-1)*$limit;
                              }

                              if($data){
                                foreach ($data as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr class="<?=$bgstatus[$value->status]?>">
                                    <td><?=$counter?></td>
                                    <td><?=$value->production_code?></td>
                                    <td><?=$value->production_date?></td>
                                    <td><?=number_format($value->total_item,0,",",".")?></td>
                                    <td><?=$value->admin_name?></td>
                                    <td><?=$statuses[$value->status]?></td>
                                    <td><?=date("Y-m-d",$value->time_created)?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                          <a href="{{ url($main_url.'/manage/'.$value->id_production) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-cogs"></i> kelola</a>
                                      <?php }?>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove']) && $value->status=="active"){?>
                                          <a data-id="{{ $value->id_production }}" data-url="{{ url($main_url.'/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> batalkan</a>
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
