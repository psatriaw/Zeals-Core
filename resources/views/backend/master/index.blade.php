<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Dashboard Master</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Dashboard Master</a>
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
                    <div class="ibox-content">
                      <div class="row">
                          <div class="col-sm-3">
                            <div class="widget style1 navy-bg">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-cloud fa-5x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span> Today degrees </span>
                                        <h2 class="font-bold">26'C</h2>
                                    </div>
                                </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="widget style1 lazur-bg">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-cloud fa-5x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span> Today degrees </span>
                                        <h2 class="font-bold">26'C</h2>
                                    </div>
                                </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="widget style1 yellow-bg">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-cloud fa-5x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span> Today degrees </span>
                                        <h2 class="font-bold">26'C</h2>
                                    </div>
                                </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="widget style1 red-bg">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <i class="fa fa-cloud fa-5x"></i>
                                    </div>
                                    <div class="col-xs-8 text-right">
                                        <span> Today degrees </span>
                                        <h2 class="font-bold">26'C</h2>
                                    </div>
                                </div>
                            </div>
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
