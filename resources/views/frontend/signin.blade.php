<main role="main">
  <div class="container">
    <div class="bg-yellow registration">
      <div class="row">
        <div class="col-xs-12 col-md-7 reg-left">
          <h2>Join us now!</h2>
          <h1>
            Worlds #1<br>
            Digital Marketing <br>
            Platform
          </h1>
        </div>

        <div class="col-xs-12 col-md-5 reg-right">
          <img src="<?=url("templates/admin/img/logo.jpg")?>" class="logo">
          <h3>Welcome Back!</h3>
          {!! Form::open(['url' => url('login-public-auth'), 'method' => 'post', 'id' => 'loginForm','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
          <div class="row justify-content-md-center">
            <div class="col-12 mt-3">
              @include('backend.flash_message')
              <div class="row">
                <div class="col-xs-12 col-md-6">
                  <div class="form-group relative {{ ($errors->has('email')?"has-error":"") }}">
                      <label class="label-field">Email/Phone Number</label>
                      <div class="input-group">
                        {!! Form::text('email', null, ['class' => 'form-control','id' => 'email','autocomplete' => 'off']) !!}
                        <div class="input-group-addon"><i class='icon icon-email'></i></div>
                      </div>
                      <div class="alert alert-danger text-small" style="display:none;margin-top:5px;" id="alert-email"></div>
                      {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
                <div class="col-xs-12 col-md-6">
                  <div class="form-group relative {{ ($errors->has('password')?"has-error":"") }}">
                      <label class="label-field">Password</label>
                      <div class="input-group">
                        {!! Form::password('password', ['class' => 'form-control','id' => 'password','autocomplete' => 'off']) !!}
                        <div class="input-group-addon"><i class='icon icon-view-hide'></i></div>
                      </div>
                      <div class="alert alert-danger text-small" style="display:none;margin-top:5px;" id="alert-password"></div>
                      {!! $errors->first('password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-md-6">
                  <p><a href="{{ url('forgot-password') }}" class="text-blue text-strong">Forgot password</a></p>
                </div>
                <div class="col-xs-12 col-md-6">
                </div>
              </div>

              <div class="form-group mb-4">
                  <div class="text-center">
                    <div id="loginalert"></div>
                    <button class="btn btn-primary btn-block shadow-none" type="submit" id="loginbtn">Sign In</button>

                    <br>
                    <a href="{{ url('register') }}" class="btn btn-block btn-secondary">Create New Account</a>
                  </div>
              </div>

              <div class="form-group text-center top30">
                <p class="">
                  Or continue with
                </p>
                <p>
                  <a href="{{ url('startAuthGoogle') }}" class="btn btn-google btn-login mt-4 mb-3"><i class='fa fa-google'></i> &nbsp;&nbsp; Sign in with Google</a>
                </p>
                <p>
                  By clicking above you aggree to our <a href="{{ url('services') }}" class="text-blue text-strong">Terms & Conditions</a>
                </p>
              </div>
          </div>
        </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</main>
<script>
    //document.location = "https://play.google.com/store/apps/details?id=asia.zeals.mobile.pwa";

    $("#loginForm").submit(function(e){
      e.preventDefault();
      $("#loginbtn").addClass("disabled").prop("disabled",true).html("Processing..");
      $.ajax({
        type:"POST",
        dataType: "json",
        url:"<?=url('login-public-auth-aff')?>",
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
                document.location = "{{ url('campaign') }}";
                <?php
            }
          ?>
          <?php }else{?>
          <?php $backlink = @$_GET['backlink']; ?>
            document.location = "{{ url($backlink) }}";
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
