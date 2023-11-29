<?php
  $user = Session::get("user");
?>
<div class="middle-box text-center loginscreen animated fadeInDown">
  <div>
    <div>
        <img src="<?=url("templates/admin/img/logo.jpg")?>" class="logo">
    </div>
    <h3>Account Activation</h3>

    <?php
      if($status=="success"){
        ?>
        <p class="alert alert-success"><?=$response?></p>
        <?php
      }else{
        ?>
        <p class="alert alert-danger"><?=$response?></p>
        <?php
      }
    ?>
    <div class="form-group py-4 text-center bottom100" style="margin-bottom:50px !important;">
      <br><br>
    </div>
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
        if(result.status=="success"){
          setTimeout(function(){
            document.location = "{{ url('user/dashboard') }}";
          },5000);
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
