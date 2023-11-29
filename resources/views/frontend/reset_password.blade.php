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
          <h3>Reset password</h3>
          <div class="form-group">
            <p class="">Remember your account? <a href="{{ url('signin') }}" class="text-blue"><strong>Sign In here</strong></a></p>
          </div>
          {!! Form::open(['url' => url('login-public-auth'), 'method' => 'post', 'id' => 'resetForm','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
          <div class="row justify-content-md-center">
            <div class="col-12 mt-3">
              @include('backend.flash_message')
              <div class="row">
                <div class="col-xs-12 col-md-12">
                  <div class="form-group relative {{ ($errors->has('email')?"has-error":"") }}">
                      <label class="label-field">Email</label>
                      <div class="input-group">
                        {!! Form::text('email', null, ['class' => 'form-control','id' => 'email','autocomplete' => 'off']) !!}
                        <div class="input-group-addon"><i class='icon icon-email'></i></div>
                      </div>
                      <div class="alert alert-danger text-small" style="display:none;margin-top:5px;" id="alert-email"></div>
                      {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
              </div>
              <div class="form-group mb-4">
                  <div class="text-center">
                    <div id="resetalert"></div>
                    <button class="btn btn-primary btn-block shadow-none" type="submit" id="loginbtn">Reset Password</button>
                  </div>
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
      $("#resetalert").html("<div class='alert alert-danger'>Mohon check data yang anda masukkan</div>");
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
