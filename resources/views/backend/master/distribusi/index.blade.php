<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Bahan Baku</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Bahan Baku</strong>
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
                        <h5>Data Bahan Baku</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="{{ url($main_url.'/create') }}">
                                <i class="fa fa-plus"></i> tambah distribusi
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
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
                                        <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.$key.'&shortmode='.@$input['shortmode'].'&status='.@$input['status'])?>"><?=$val?></a></li>
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
                                  <button data-toggle="dropdown" class="btn btn-white dropdown-toggle bg-white" type="button" aria-expanded="false"><?=(@$input['shortmode']=="")?$default['shortmode']:$input['shortmode']?> <span class="caret"></span></button>
                                  <ul class="dropdown-menu">
                                      <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=asc&status='.@$input['status'])?>">asc</a></li>
                                      <li><a href="<?=url($main_url.'/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=desc&status='.@$input['status'])?>">desc</a></li>
                                  </ul>
                              </div>

                            </div>
                          </div>
                          <div class="col-sm-2">

                          </div>
                          <div class="col-sm-2 col-sm-offset-4">
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
                                <th>Nama Bahan</th>
                                <th>Kode Bahan</th>
                                <th>Kode Pembelian</th>
                                <th>Quantity</th>
                                <th>Tujuan</th>
                                <th>Tgl Distribusi</th>
                                <th>Update</th>
                                <th>Aksi</th>
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
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->material_name?></td>
                                    <td><?=$value->material_code?></td>
                                    <td><?=$value->purchase_code?></td>
                                    <td><?=number_format($value->quantity,2,",",".")?></td>
                                    <td><?=$value->mitra_name?></td>
                                    <td><?=$value->distribution_date?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a data-id="{{ $value->id_material_distribution }}" data-url="{{ url($main_url.'/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
                        <?php //$pagging?>
                      </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
    @include('backend.master.purchase.modal_material_add')
  </div>
</div>
