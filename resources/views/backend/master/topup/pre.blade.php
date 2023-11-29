<?php

use Illuminate\Support\Facades\Request;

$main_url = $config['main_url'];

//   linktype
$trxstatus = [
    "paid" => "text-info",
    "unpaid" => "text-danger"
];


?>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
        @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>TopUp</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Top Up</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <h2 class="text-center">We are about to bring a good feature to you</h2>
        </div>
        @include('backend.do_confirm')
        @include('backend.footer')
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".tooltips").tooltip();
    });
</script>
