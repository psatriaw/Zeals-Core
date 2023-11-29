<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kelurahan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Kelurahan</strong>
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
                        <h5>Kelurahan</h5>
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
                                  <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?=(@$input['short']=="")?$shorter[$default['short']]:$shorter[$input['short']]?> <span class="caret"></span></button>

                                  <ul class="dropdown-menu">
                                    <?php
                                      foreach($shorter as $key=>$val){
                                        ?>
                                        <li class="<?=($key==Request::input("short"))?"active":""?>">
                                            <a href="<?=url('master/kelurahan/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.$key.'&shortmode='.@$shortmode)?>"><?=$val?></a>
                                        </li>
                                        <?php
                                      }
                                    ?>
                                  </ul>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="input-group m-b">
                              <span class="input-group-addon">Mode</span>
                              <div class="input-group-btn bg-white">
                                  <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?=($shortmode=="asc")?"A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru":"Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama"?> <span class="caret"></span></button>
                                  <ul class="dropdown-menu">
                                      <li class="<?=("asc"==Request::input("shortmode"))?"active":""?>">
                                        <a href="<?=url('master/kelurahan/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=asc')?>">A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru</a>
                                      </li>
                                      <li class="<?=("desc"==Request::input("shortmode"))?"active":""?>">
                                        <a href="<?=url('master/kelurahan/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=desc')?>">Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama</a>
                                      </li>
                                  </ul>
                              </div>

                            </div>
                          </div>
                          <div class="col-sm-4">

                          </div>
                          <div class="col-sm-4">
                            <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['create'])){?>
                            <div class="ibox-tools">
                                <a class="btn btn-primary add-btn btn-sm tooltips" href="{{ url('master/kelurahan/create') }}" title="Tambah data">
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
                                <th>Kode</th>
                                <th>Nama Kelurahan</th>
                                <th>Nama Kecamatan</th>
                                <th>Tgl Daftar</th>
                                <th>Status</th>
                                <th>Tgl Diperbarui</th>
                                <th style="width:280px;">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;


                              if($data){
                                foreach ($data as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->id_reff?></td>
                                    <td><?=$value->nama_kelurahan?></td>
                                    <td><?=$value->nama_kecamatan?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td <?=($value->status=="active")?"class='text-info'":""?>><?=$value->status?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit'])){?>
                                          <a href="{{ url('master/kelurahan/edit/'.$value->id_kelurahan) }}" class="btn btn-primary dim btn-xs tooltips" title="ubah data <?=$value->nama_kelurahan?>"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove'])){?>
                                          <a data-id="{{ $value->id_kelurahan }}" data-url="{{ url('master/kelurahan/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm tooltips" title="hapus data <?=$value->nama_kelurahan?>"><i class="fa fa-trash"></i> hapus</a>
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
<script>
  $(document).ready(function(){
    $(".tooltips").tooltip();
  });
</script>
