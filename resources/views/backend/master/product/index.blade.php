<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Produk</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Produk</strong>
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
                        <h5>Tabel Data Produk</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['create'])){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="{{ url($main_url.'/create') }}">
                                <i class="fa fa-plus"></i> tambah produk
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
                                <th>Nama Produk</th>
                                <th>Kode</th>
                                <!--<th>Kategori</th>-->
                                <th>Harga Normal</th>
                                <th style='color:#096504;'>Harga Gojek</th>
                                <th style='color:#3cb936;'>Harga GrabFood</th>
                                <th>Potongan</th>
                                <th>Tgl Daftar</th>
                                <th>Status</th>
                                <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['rules'])){?>
                                <th>Rules</th>
                                <?php } ?>
                                <th>Tgl Diperbarui</th>
                                <th style="width:200px;">Aksi</th>
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
                                        <?=$value->product_name?>
                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['rumus'])){?>
                                          <br>
                                          <br>
                                        <?=$value->para_loyang?>
                                        <?php } ?>
                                    </td>
                                    <td><?=$value->product_code?> <br><?=$value->code?></td>
                                    <!--<td><?=$value->category_name?></td>-->
                                    <td class="text-right">Rp.<?=number_format($value->price,0,",",".")?></td>
                                    <td class="text-right" style="background: #096504;color: #fff;">Rp.<?=number_format(@$value->price_gojek,0,",",".")?></td>
                                    <td class="text-right" style="background: #3cb936;color: #fff;">Rp.<?=number_format(@$value->price_grab,0,",",".")?></td>
                                    <td class="text-right">Rp.<?=number_format($value->discount,0,",",".")?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td><?=$product_status[$value->status]?></td>
                                    <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['rules'])){?>
                                    <td><?=($value->rules!="")?$value->rules." produk":"tidak ada"?></td>
                                    <?php } ?>
                                    <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit'])){?>
                                          <a href="{{ url($main_url.'/edit/'.$value->id_product) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['view'])){?>
                                          <a href="{{ url($main_url.'/detail/'.$value->id_product) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-eye"></i> detail</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                          <a href="{{ url($main_url.'/manage/'.$value->id_product) }}" class="btn btn-danger btn-outline dim btn-xs"><i class="fa fa-picture-o"></i> kelola</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['mrp'])){?>
                                          <a href="{{ url($main_url.'/mrp/'.$value->id_product) }}" class="btn btn-danger btn-outline dim btn-xs"><i class="fa fa-puzzle-piece"></i> BOM</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['rumus'])){?>
                                          <a href="{{ url($main_url.'/rumus/'.$value->id_product) }}" class="btn btn-danger btn-outline dim btn-xs"><i class="fa fa-puzzle-piece"></i> BOM SP</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['rules'])){?>
                                          <a href="{{ url($main_url.'/restriction/'.$value->id_product) }}" class="btn btn-danger btn-outline dim btn-xs"><i class="fa fa-cogs"></i> rules</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove'])){?>
                                          <a data-id="{{ $value->id_product }}" data-url="{{ url($main_url.'/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
