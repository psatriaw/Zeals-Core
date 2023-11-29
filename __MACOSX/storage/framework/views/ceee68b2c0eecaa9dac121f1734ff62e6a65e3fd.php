<div class="footer">
  <div class="pull-right">
    <!--10GB of <strong>250GB</strong> Free.-->
  </div>
  <div>
    <strong>Copyright</strong> <img src="<?php echo e(url('templates/admin/img/favicon.png')); ?>" style="width:18px;"> ZEALSASIA - <?=date("Y")?>
  </div>
</div>
<script>
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

function getnotifications(){
  $.ajax({
    url: "<?=url("get-unread-notifications")?>",
    data: {},
    type: "GET"
  })
  .done(function(msg){
    var defaultnohtml = '<li><div class="text-center link-block">Tidak ada peringatan</div></li>';
    var defaulthtml = '<li><div class="text-center link-block"><a href="<?=url('notifications')?>"><strong>Lihat semua</strong><i class="fa fa-angle-right"></i></a></div></li>';
    if(msg.total>0){
      $("#unread-notif").html(msg.total);
      $("#list-notif").html(msg.html+defaulthtml);
    }else{
      $("#unread-notif").html("");
      $("#list-notif").html(defaultnohtml+defaulthtml);
    }
  })
  .always(function(){
    setTimeout(function(){
      getnotifications();
    },15000);
  });
}

function readnotification(id_notification,url){
  $.ajax({
    url: "<?=url("read-notification")?>",
    data: {id_notification: id_notification},
    type: "POST"
  })
  .done(function(msg){
    console.log(msg);
    if(msg.result==200){
      document.location = url;
    }
  });
}

$(document).ready(function(){
  getnotifications();
});
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/footer.blade.php ENDPATH**/ ?>