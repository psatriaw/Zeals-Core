<?php
  $user = Session::get("user");
  print_r($user);
?>
<div class="middle-box login text-center loginscreen animated fadeInDown">
  <div>
    <div>
        <img src="<?=url("templates/admin/img/logo.jpg")?>" class="logo">
    </div>
    <h3>Resete password</h3>
    <p>Pastikan alamat email sudah terdaftar</p>
    <form class="m-t" role="form" action="<?php echo e(url('forgot-password-submit')); ?>" method="POST" id="resetForm">
      <input type="hidden" id="token" name="_token" value="<?php echo e(csrf_token()); ?>">
      <div class="form-group">
          <input type="text" class="form-control" placeholder="Alamat email" name="email" id="email" data-placement="right">
      </div>
      <div id="resetalert"></div>
      <button type="submit" class="btn btn-primary block full-width m-b" id="btnreset">Reset Password</button>

      <p class=" text-center"><small>Sudah punya akun?</small></p>
      <a href="<?php echo e(url('login')); ?>">Login</a>
      <br>
      <br>
    </form>
    <p class=" text-center"><small>Belum terdaftar? Silahkan Hubungi Administrator</small></p>
  </div>
</div>
<script>

    $("#resetForm").submit(function(e){
      e.preventDefault();
      $("#btnreset").addClass("disabled").prop("disabled",true).html("Memproses..");
      $.ajax({
        type:"POST",
        dataType: "json",
        url:"<?=url('forgot-password-submit')?>",
        data: $(this).serialize()
      })
      .done(function(result){
        console.log(result);
        if(result.status=="success"){
          $("#resetalert").html(result.response);
        }else if(result.status == "error_validation"){
          var errors = result.response;
          for(var i in errors){
            $("#"+i).prop("title",errors[i][0]).prop("data-placement","right").tooltip().parent(".form-group").addClass("has-error");
          }
          $("#resetalert").html("<div class='alert alert-danger'>Mohon check data yang anda masukkan, kami menemukan beberapa kesalahan yang harus diperbaiki.</div>");
        }else{
          $("#resetalert").html(result.response);
        }
        $("#btnreset").removeClass("disabled").prop("disabled",false).html("Masuk");
      })
      .fail(function(msg){
        console.log(msg);
        $("#btnreset").attr("disabled",false).removeClass("disabled").html("Masuk");
      })
      .always(function(){

      });
    });
</script>
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/user/reset_password.blade.php ENDPATH**/ ?>