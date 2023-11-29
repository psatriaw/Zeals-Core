<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Notifikasi</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li class="active">
                        <strong>Notifikasi</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-8 col-12 col-sm-10">
                  <ul class="notifications" id="list-notif-all">
                    <li>
                        memuat...
                    </li>
                  </ul>
                </div>
            </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>
<script>
var last_id = 0;

function loadmorenotification(){
  $(".btn-load-more").html('<li class="btn-load-more"><a><div class="link-blocks">memuat...</a></div></li>');
  $.ajax({
    url: "<?=url("load-more-notification")?>",
    data: {last_id:last_id},
    type: "GET"
  })
  .done(function(msg){
    console.log(msg);
    $(".btn-load-more").remove();
    if(last_id==0){
      $("#list-notif-all").html("");
    }
    var defaulthtml = '<li class="btn-load-more"><div class="link-blocks"><a onclick="loadmorenotification();"><strong>Muat sebelumnya</strong><i class="fa fa-angle-right"></i></a></div></li>';
    if(msg.total>0){
      last_id = msg.last_id;
      $("#list-notif-all").append(msg.html+defaulthtml);
    }else{
      $("#list-notif-all").append("");
    }
  })
  .always(function(){
    setTimeout(function(){
      getnotifications();
    },15000);
  });
}

$(document).ready(function(){
  loadmorenotification();
});
</script>
