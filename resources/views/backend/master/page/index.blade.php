<?php 
	$main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
	@include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Pages</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Pages</strong>
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
                        <h5>Pages</h5>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      
                      <div class="row">
                          <div class="col-sm-12">
                            <form class="" role="form" method="GET" id="loginForm">
                              <div class="input-group m-b">
                                <input type="text" placeholder="Keyword" class="input-sm form-control" name="keyword" value="<?=@$input['keyword']?>">
                                <span class="input-group-btn">
                                  <button type="submit" class="btn btn-sm btn-primary"> Search</button>
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
                          <div class="col-sm-4">
                            <div class="input-group m-b">
                              <span class="input-group-addon">Short</span>
                              <div class="input-group-btn bg-white">
                                  <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?=(@$input['short']=="")?$shorter[$default['short']]:$shorter[$input['short']]?> <span class="caret"></span></button>

                                  <ul class="dropdown-menu">
                                    <?php
                                      foreach($shorter as $key=>$val){
                                        ?>
                                        <li><a href="<?=url($main_url.'?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.$key.'&shortmode='.@$shortmode)?>"><?=$val?></a></li>
                                        <?php
                                      }
                                    ?>
                                  </ul>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="input-group m-b">
                              <span class="input-group-addon">Order</span>
                              <div class="input-group-btn bg-white">
                                  <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?=(@$shortmode=="asc")?"A ke Z / Lower to Higher":"Z to A / Higher to Lower"?> <span class="caret"></span></button>
                                  <ul class="dropdown-menu">
                                      <li><a href="<?=url($main_url.'?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=asc')?>">A ke Z / Lower to Higher</a></li>
                                      <li><a href="<?=url($main_url.'?page='.@$input['page'].'&keyword='.@$input['keyword'].'&short='.@$input['short'].'&shortmode=desc')?>">Z to A / Higher to Lower</a></li>
                                  </ul>
                              </div>

                            </div>
                          </div>
                          <div class="col-sm-2"></div>
                          <div class="col-sm-2">
                            <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['create'])){?>
                            <div class="ibox-tools">
                                <a class="btn btn-primary add-btn btn-sm tooltips" href="{{ url($main_url.'/create') }}" title="Add Slide">
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
                                <th>Page Code</th>
                                <th>Keyword</th>
                                <th>Title</th>
                                <th>Time Created</th>
                                <th>Last Update</th>
                                <th>Action</th>
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
                                    <td><?=$value->page_code?></td>
									 <td><?=$value->title?></td>
									 <td><?=$value->keyword?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                    <td class="nowrap">
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['edit'])){?>
                                          <a href="{{ url($config['main_url'].'/edit/'.$value->id_page) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> edit</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['view'])){?>
                                          <a href="{{ url($config['main_url'].'/detail/'.$value->id_page) }}" class="btn btn-white btn-xs"><i class="fa fa-eye"></i> detail</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove'])){?>
                                          <a data-id="{{ $value->id_page }}" data-url="{{ url($config['main_url'].'/remove/') }}" class="btn btn-danger dim btn-xs confirm"><i class="fa fa-trash"></i> remove</a>
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
