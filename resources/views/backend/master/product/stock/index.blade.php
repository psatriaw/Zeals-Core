<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Product Stock</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/product') }}">Products</a>
                    </li>
                    <li class="active">
                        <strong>Stock</strong>
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
                          <h5>Stock of "<?=$data->product_name?>" [<?=$data->product_code?>] with total <span style='color:#f00;'><?=number_format($data->stock,0,",",".")?></span> items available</h5>
                          <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['create']) && $previlege->isAllow($login->id_user,$login->id_department,$config['manage-stock'])){?>
                          <div class="ibox-tools">
                              <a class="btn btn-primary btn-sm" href="{{ url($main_url.'/stock/'.$data->id_product.'/create') }}">
                                  <i class="fa fa-plus"></i> create stock
                              </a>
                          </div>
                          <?php } ?>
                      </div>
                      <div class="ibox-content">
                        @include('backend.flash_message')
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                            <thead>
                              <tr>
                                  <th>No.</th>
                                  <th>Quantity</th>
                                  <th>Actual Qty</th>
                                  <th>Size</th>
                                  <!--<th>Color</th>-->
                                  <th>Last update</th>
                                  <th></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $counter = 0;

                                $product_status = array('active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'available' =>'Tersedia','unavailable' => 'Tidak Tersedia');

                                if(@$list){
                                  foreach ($list as $key => $value) {
                                    $counter++;
									
									$maxtime 	= $setting_model->getSettingVal("maks_jam_bayar");
									$datasize   = $stock_model->getStockOfSize($value->id_product,$value->size,$maxtime);

	
                                    ?>
                                    <tr>
                                      <td><?=$counter?></td>
                                      <td><?=$value->stock?></td>
                                      <td><?=$datasize?></td>
                                      <td><?=$value->size?></td>
                                      <!--<td><?=@$color[$value->color]?></td>-->
                                      <td><?=date("M d, Y H:i",$value->last_update)?></td>
                                      <td>
                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit']) && $previlege->isAllow($login->id_user,$login->id_department,$config['manage-stock'])){?>
                                            <a href="{{ url($main_url.'/stock/'.$data->id_product.'/edit/'.$value->id_product_stock) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> edit</a>
                                        <?php }?>

                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove']) && $previlege->isAllow($login->id_user,$login->id_department,$config['manage-stock'])){?>
                                            <a data-id="{{ $value->id_product_stock }}" parent-id="{{ $data->id_product }}" data-url="{{ url($main_url.'/stock/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> remove</a>
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
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group top15">
                            <div class="col-sm-6">
                                <a class="btn btn-white" href="{{ url($main_url) }}">
                                    <i class="fa fa-angle-left"></i> back
                                </a>
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
