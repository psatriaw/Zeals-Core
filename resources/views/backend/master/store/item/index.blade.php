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
                <h2>Item</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Item</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
    </div>
        <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox">
            <div class="ibox-title">
                <h3 class="ibox-title">Master Barang</h3>
                <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                        <div class="ibox-tools">
                            <a class="btn btn-primary btn-sm" href="{{ url($main_url.'/create') }}">
                                <i class="fa fa-plus"></i> tambah rumus
                            </a>
                        </div>
                        <?php } ?>
            </div>
            <!-- /.ibox-title -->
            <div class="ibox-content"> 
                <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Kategori</th>
                                <th>Brand</th>
                                <th>Last Upadate</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                            <td>
                                <a class="btn btn-warning btn-sm" href=""><i class="fa fa-edit"></i></a>

                                <form action="" method="post" class=d-inline
                                    onsubmit="return confirm('Yakin hapus data?')">
                                 
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                </form>

                            </td>
                        </tr>
                        </tbody>    
                </table>
            </div>
        </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>
