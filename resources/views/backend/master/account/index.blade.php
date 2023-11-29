<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Akun Layanan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li class="active">
                        <strong>Akun Layanan</strong>
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
                        <h5>Tabel Akun Layanan Uronshop</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-user-create")){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="{{ url('admin/account/create') }}">
                                <i class="fa fa-plus"></i> tambah pengguna
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
                                          <a href="<?=url('admin/account/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.$key.'&shortmode='.@$input['shortmode'])?>"><?=$val?></a>
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
                                      <a href="<?=url('admin/account/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=asc')?>">asc</a>
                                    </li>
                                    <li class="<?=("desc"==Request::input("shortmode"))?"active":""?>">
                                      <a href="<?=url('admin/account/?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=desc')?>">desc</a>
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
                                <th>Nama Depan</th>
                                <th>Nama Belakang</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Tgl Aktif</th>
                                <th>Tgl Berakhir</th>
                                <th>Status</th>
                                <th>Jenis Layanan</th>
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
                                    <td><?=$value->first_name?></td>
                                    <td><?=$value->last_name?></td>
                                    <td><?=$value->username?></td>
                                    <td><?=$value->email?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->account_start_time)?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->account_end_time)?></td>
                                    <td <?=($value->status=="active")?"class='text-info'":""?>><?=$value->status?></td>
                                    <td><?=$value->service_name?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-account-show")){?>
                                          <a href="{{ url('admin/account/detail/'.$value->id_user) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-eye"></i> detail</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-account-approve")){?>
                                          <?php if($value->status=="active"){?>
                                            <a data-id="{{ $value->id_account }}" data-url="{{ url('admin/account/setinactive/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-minus"></i> tidak aktif</a>
                                          <?php }else{?>
                                            <a data-id="{{ $value->id_account }}" data-url="{{ url('admin/account/setactive/') }}" class="btn btn-primary btn-outline dim btn-xs confirm"><i class="fa fa-check"></i> aktifkan</a>
                                          <?php }?>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-account-remove")){?>
                                          <a data-id="{{ $value->id_account }}" data-url="{{ url('admin/account/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
