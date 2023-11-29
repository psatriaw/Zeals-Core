<?php
  $user = Session::get("user");
?>
<style>
  .form-control, .btn{
    height: 52px;
  }
</style>
<div class="middle-box login text-center loginscreen animated fadeInDown">
  <div>
    <div>
        <img src="<?=url("templates/admin/img/logo.jpg")?>" class="logo">
    </div>
    <h3>Selamat Datang</h3>
    <p>Mohon melakukan aktivasi akun sebelum melakukan login</p>
    <form class="m-t" role="form" action="<?php echo e(url('login-submit')); ?>" method="POST" id="loginForm">
      <input type="hidden" id="token" name="_token" value="<?php echo e(csrf_token()); ?>">
      <div class="form-group">
          <input type="text" class="form-control" placeholder="Username" name="username" id="username" data-placement="right">
      </div>
      <div class="form-group">
          <input type="password" class="form-control" placeholder="Password" name="password" id="password" data-placement="right">
      </div>
      <div id="loginalert"></div>
      <button type="submit" class="btn btn-primary block full-width m-b" id="btnlog">Masuk</button>
      <p class=" text-center"><small>Lupa password?</small></p>
      <a href="<?php echo e(url('forgot-password')); ?>">Reset disini</a>
      <br>
      <br>

      <p class=" text-center"><small>Belum terdaftar? Silahkan Hubungi Administrator</small></p>
      <!--
      <a class="btn btn-sm btn-white btn-block" href="<?php echo e(url('register')); ?>">Registrasi sebagai Merchant</a>
      -->
    </form>
    <p class="m-t"> <small>https://zeals.asia - 2021</small> </p>
  </div>
</div>
<script>

    $("#loginForm").submit(function(e){
      e.preventDefault();
      $("#btnlog").addClass("disabled").prop("disabled",true).html("Memproses..");
      $.ajax({
        type:"POST",
        dataType: "json",
        url:"<?=url('login-auth')?>",
        data: $(this).serialize()
      })
      .done(function(result){
        console.log(result);
        if(result.status=="success"){
          //setTimeout(function(){
            document.location = "<?php echo e(url('dashboard/view')); ?>";
          //},3000);
          $("#loginalert").html(result.response);
        }else if(result.status == "error_validation"){
          var errors = result.response;
          for(var i in errors){
            $("#"+i).prop("title",errors[i][0]).prop("data-placement","right").tooltip().parent(".form-group").addClass("has-error");
          }
          $("#loginalert").html("<div class='alert alert-danger'>Mohon check data yang anda masukkan, kami menemukan beberapa kesalahan yang harus diperbaiki.</div>");
        }else{
          $("#loginalert").html(result.response);
        }
        $("#btnlog").removeClass("disabled").prop("disabled",false).html("Masuk");
      })
      .fail(function(msg){
        console.log(msg);
        $("#btnlog").attr("disabled",false).removeClass("disabled").html("Masuk");
      })
      .always(function(){

      });
    });
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/user/login.blade.php ENDPATH**/ ?>