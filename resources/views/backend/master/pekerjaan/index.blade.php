<?php
  $methods    = $config;
  $main_url   = $methods['main_url'];
  $statuses   = array(
    "active"    => "Aktif",
    "inactive"  => "Tidak aktif",
    "semua"     => "Semua"
  );
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Pekerjaan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Pekerjaan</strong>
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
                        <h5>Data Pekerjaan</h5>

                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="row">
                          <div class="col-lg-12">
                            <form class="" role="form" method="GET" id="loginForm">
                              <div class="input-group m-b">
                                <input type="text" placeholder="Kata kunci" class="input-sm form-control" name="keyword" value="<?=@$input['keyword']?>">
                                <span class="input-group-btn">
                                  <button type="submit" class="btn btn-sm btn-primary"> Cari</button>
                                </span>
                              </div>
                            </form>
                          </div>
                      </div>

                      <?php
                        $shortmode = @$input['shortmode'];
                        if($shortmode==""){
                          $shortmode = $default['shortmode'];
                        }
                      ?>

                      <div class="row">
                          <div class="col-sm-2">
                            <div class="input-group m-b">
                              <span class="input-group-addon">Urutkan</span>
                              <div class="input-group-btn bg-white">
                                  <button data-toggle="dropdown" class="btn btn-white dropdown-toggle bg-white" type="button" aria-expanded="false"><?=(@$input['short']=="")?$shorter[$default['short']]:$shorter[$input['short']]?> <span class="caret"></span></button>

                                  <ul class="dropdown-menu">
                                    <?php
                                      foreach($shorter as $key=>$val){
                                        ?>
                                        <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.$key.'&shortmode='.@$shortmode.'&status='.@$input['status'])?>"><?=$val?></a></li>
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
                                  <button data-toggle="dropdown" class="btn btn-white dropdown-toggle bg-white" type="button" aria-expanded="false"><?=(@$shortmode=="asc")?"A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru":"Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama"?> <span class="caret"></span></button>
                                  <ul class="dropdown-menu">
                                      <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=asc&status='.@$input['status'])?>">A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru</a></li>
                                      <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=desc&status='.@$input['status'])?>">Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama</a></li>
                                  </ul>
                              </div>

                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group m-b">
                              <span class="input-group-addon">Status</span>
                              <div class="input-group-btn bg-white">
                                  <button data-toggle="dropdown" class="btn btn-white dropdown-toggle bg-white" type="button" aria-expanded="false"><?=(@$input['status']=="")?$statuses[$default['status']]:$statuses[$input['status']]?> <span class="caret"></span></button>
                                  <ul class="dropdown-menu">
                                      <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode='.@$input['shortmode'].'&status=semua')?>">semua</a></li>
                                      <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode='.@$input['shortmode'].'&status=active')?>">Aktif</a></li>
                                      <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode='.@$input['shortmode'].'&status=inactive')?>">Tidak Aktif</a></li>
                                  </ul>
                              </div>

                            </div>
                          </div>
                          <div class="col-sm-2 col-sm-offset-2">
                            <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                            <div class="ibox-tools">
                                <a class="btn btn-primary btn-sm add-btn tooltips" href="{{ url($main_url.'/create') }}" title="Tambah data">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                            <?php } ?>
                          </div>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Pekerjaan</th>
                                <th>Jumlah Pekerja</th>
                                <th>Tgl Daftar</th>
                                <th>Status</th>
                                <th>Update</th>
                                <th style="width:280px;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $bgcolors = array(
                                "diterima"  => "success",
                                "ditolak"   => "danger"
                              );

                              $counter = 0;
                              if($page!=""){
                                $counter = ($page-1)*$limit;
                              }

                              if($data){
                                foreach ($data as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr class="<?=@$bgcolors[$value->status]?>">
                                    <td><?=$counter?></td>
                                    <td><?=$value->pekerjaan?></td>
                                    <td><?=number_format(@$value->jumlah_pakerja,0,",",".")?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td <?=($value->status=="active")?"class='text-info'":""?>><?=$statuses[$value->status]?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                          <a href="{{ url($main_url.'/edit/'.$value->id_pekerjaan) }}" class="btn btn-primary dim btn-xs tooltips" title="ubah data <?=$value->pekerjaan?>"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['view'])){?>
                                          <a href="{{ url($main_url.'/detail/'.$value->id_pekerjaan) }}" class="btn btn-white btn-outline dim btn-xs tooltips" title="lihat detail data <?=$value->pekerjaan?>"><i class="fa fa-eye"></i> detail</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a data-id="{{ $value->id_pekerjaan }}" data-url="{{ url($main_url.'/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm tooltips" title="hapus data <?=$value->pekerjaan?>"><i class="fa fa-trash"></i> hapus</a>
                                      <?php }?>
                                    </td>
                                  </tr>
                                  <?php
                                }
                              }else{
                                ?>
                                <tr>
                                  <td colspan="10">Tidak ada data</td>
                                </tr>
                                <?php
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
