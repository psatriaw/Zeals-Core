<style>
  .form-control, .btn{
    height: 52px;
  }
  .input-group-addon {
    border: 0px;
  }
  .btn-google{
    width: 100%;
    border: 2px solid #696969;
    padding: 13px;
    border-radius: 15px;
    color: #6f0000;
    background: #fff;
    height: 50px;
  }
  .text-white{
    color:#fff;
  }
</style>

<main role="main">
  <div class="album pb-5">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-12 col-sm-8 col-md-4">
          <div class="album-content text-center">
            <img src="<?php echo e(url('templates/admin/img/logo.jpg')); ?>" alt="Logo" class="main-logo">
            <h3>Sign In</h3>
            <?php echo Form::open(['url' => url('login-public-auth'), 'method' => 'post', 'id' => 'loginForm','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]); ?>

            <div class="row justify-content-md-center">
              <div class="col-12">
                <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="form-group relative <?php echo e(($errors->has('email')?"has-error":"")); ?>">
                    <label class="label-field">Email</label>
                    <?php echo Form::text('email', null, ['class' => 'form-control','id' => 'email']); ?>

                    <div class="alert alert-danger text-center text-small" style="display:none;margin-top:5px;" id="alert-email"></div>
                    <?php echo $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                </div>

                <div class="form-group relative <?php echo e(($errors->has('password')?"has-error":"")); ?>">
                    <label class="label-field">Password</label>
                    <?php echo Form::password('password', ['class' => 'form-control','id' => 'password']); ?>

                    <div class="alert alert-danger text-center text-small" style="display:none;margin-top:5px;" id="alert-password"></div>
                    <?php echo $errors->first('password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                </div>

                <div class="form-group mt-4 mb-4">
                    <div class="text-center">
                      <div id="loginalert"></div>
                      <button class="btn btn-primary btn-block shadow-none" type="submit" id="loginbtn">Sign In</button>
                    </div>
                </div>

                <div class="form-group text-center top30">
                  <p class="text-grey p1">
                    Forget Password? Reset Here<br>
                    or you may try another sign in method below
                  </p>
                    <a href="<?php echo e(url('startAuthGoogle')); ?>" class="btn btn-google btn-login mt-4 mb-3"><i class='fa fa-google'></i> google account</a>
                </div>
                <div class="form-group text-center">
                  <p class="text-grey p1"><a href="<?php echo e(url('register')); ?>" class="text-grey"><strong>Register</strong></a> new Account Here</p>
                </div>
            </div>
          </div>
          <?php echo Form::close(); ?>

        </div>
      </div>
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
        }else{
          $("#loginalert").html(result.response);
        }
        $("#loginbtn").removeClass("disabled").prop("disabled",false).html("Sign In");
      })
      .fail(function(msg){
        console.log(msg);
        $("#loginbtn").attr("disabled",false).removeClass("disabled").html("Sign In");
      })
      .always(function(){
        setTimeout(function(){
          $(".alert").hide();
        },3000);
      });
    });
</script>
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/frontend/signin.blade.php ENDPATH**/ ?>