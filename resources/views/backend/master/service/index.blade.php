<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Akun Pengguna</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li class="active">
                        <strong>Layanan</strong>
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
                        <h5>Tabel Layanan</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-service-create")){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="{{ url('admin/service/create') }}">
                                <i class="fa fa-plus"></i> tambah layanan
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
                                        <li class="<?=($key==Request::input("short"))?"active":""?>">
                                            <a href="<?=url('admin/service/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.$key.'&shortmode='.@$input['shortmode'])?>"><?=$val?></a>
                                        </li>
                                        <?php
                                      }
                                    ?>
                                  </ul>
                              </div>

                              <div class="input-group-btn bg-white">
                                  <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?=(@$input['shortmode']=="")?$default['shortmode']:$input['shortmode']?> <span class="caret"></span></button>
                                  <ul class="dropdown-menu">
                                      <li class="<?=("asc"==Request::input("shortmode"))?"active":""?>">
                                        <a href="<?=url('admin/service/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=asc')?>">asc</a>
                                      </li>
                                      <li class="<?=("desc"==Request::input("shortmode"))?"active":""?>">
                                        <a href="<?=url('admin/service/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=desc')?>">desc</a>
                                      </li>
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
                                <th>Nama Layanan</th>
                                <th>Deskripsi</th>
                                <th>Tgl Daftar</th>
                                <th>Masa Layanan</th>
                                <th>Kode</th>
                                <th>Harga</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Tgl Diperbarui</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
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
                                    <td><?=$value->service_name?></td>
                                    <td><?=$value->description?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td><?=$value->expiration?> hari</td>
                                    <td><?=$value->code?></td>
                                    <td>Rp. <?=number_format($value->price,0,",",".")?></td>
                                    <td><?=($value->type!="")?$value->type:"tidak memiliki"?></td>
                                    <td <?=($value->status=="active")?"class='text-info'":""?>><?=$value->status?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-user-create")){?>
                                          <a href="{{ url('admin/service/edit/'.$value->id_service) }}" class="btn btn-primary btn-outline dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-user-create")){?>
                                          <a href="{{ url('admin/service/detail/'.$value->id_service) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-eye"></i> detail</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-user-create")){?>
                                          <a data-id="{{ $value->id_service }}" data-url="{{ url('admin/service/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
