<style>
  .form-control, .btn{
    height: 52px;
  }
  .input-group-addon {
    border: 0px;
  }
</style>

<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <img src="<?php echo e(url('templates/admin/img/logo.jpg')); ?>" alt="Logo" class="main-logo">
      </div>
    </div>
    <div class="container text-center top100">
      <?=$content?>
    </div>
  </section>

  <div class="album pb-5">
    <div class="container">
      <div class="album-contents">
        <?php echo Form::open(['url' => url('login-public-auth'), 'method' => 'post', 'id' => 'loginForm','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]); ?>

        <div class="row justify-content-md-center">
          <div class="col-12 col-sm-6 col-md-4">
            <div class="form-group <?php echo e(($errors->has('email')?"has-error":"")); ?>">
                <?php echo Form::text('email', null, ['class' => 'form-control','placeholder' => 'Email','id' => 'email']); ?>

                <div class="alert alert-danger text-center text-small" style="display:none;margin-top:5px;" id="alert-email"></div>
                <?php echo $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

            </div>

            <div class="form-group <?php echo e(($errors->has('password')?"has-error":"")); ?>">
                <?php echo Form::password('password', ['class' => 'form-control','placeholder' => 'Password','id' => 'password']); ?>

                <div class="alert alert-danger text-center text-small" style="display:none;margin-top:5px;" id="alert-password"></div>
                <?php echo $errors->first('password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

            </div>

            <div class="form-group">
                <div class="text-center">
                  <div id="loginalert"></div>
                  <button class="btn btn-primary btn-block shadow-none" type="submit" id="loginbtn">LOGIN</button>
                </div>
            </div>
            <div class="form-group py-4 text-center">
              <p class="text-white"><a href="<?php echo e(url('register')); ?>" class="text-white"><strong>Registrasi</strong></a> akun baru disini</p>
            </div>
        </div>
      </div>
      <?php echo Form::close(); ?>

    </div>
  </div>
</div>
</main>

<script>

    $("#loginForm").submit(function(e){
      e.preventDefault();
      $("#loginbtn").addClass("disabled").prop("disabled",true).html("Processing..");
      $.ajax({
        type:"POST",
        dataType: "json",
        url:"<?=url('login-public-auth')?>",
        data: $(this).serialize()
      })
      .done(function(result){
        console.log(result);
        if(result.status=="success"){
          //setTimeout(function(){
          <?php if(@$_GET['backlink']==""){ ?>
            <?php
            if(@$backlink!=""){
                ?>
                document.location = "<?=$backlink?>";
                <?php
            }else{
                ?>
                document.location = "<?php echo e(url('profile')); ?>";
                <?php
            }
          ?>
          <?php }else{?>
          <?php $backlink = @$_GET['backlink']; ?>
            document.location = "<?php echo e(url($backlink)); ?>";
          <?php }?>

          //},3000);
          $("#loginalert").html(result.response);
        }else if(result.status == "error_validation"){
          var errors = result.response;
          for(var i in errors){
            console.log(errors[i]);
            $("#alert-"+i).html(errors[i][0]).show();
            $("#"+i).prop("title",errors[i][0]).prop("data-placement","right").tooltip().parent(".form-group").addClass("has-error");
          }
          $("#loginalert").html("<div class='alert alert-danger text-small'>Tolong cek input! Ada kesalahan.</div>");
        }else{
          $("#loginalert").html(result.response);
        }
        $("#loginbtn").removeClass("disabled").prop("disabled",false).html("MASUK");
      })
      .fail(function(msg){
        console.log(msg);
        $("#loginbtn").attr("disabled",false).removeClass("disabled").html("MASUK");
      })
      .always(function(){

      });
    });
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/frontend/signin.blade.php ENDPATH**/ ?>