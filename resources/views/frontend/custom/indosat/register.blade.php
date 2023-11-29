<link href="{{ url('templates/admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<style>
    .ioh-subtitle{
        margin-top:15px;
        font-size:20px;
        font-weight:600;
        color:#505051;
        margin-bottom:25px;
    }

    .container-fluid{
        padding-left:0px;
        padding-right:0px;
    }

    .reg-right{
        background: url({{ url('referrals/ioh_bg.png') }}) no-repeat;
        padding-left: 55px;
        padding-right: 55px;
        background-size: 100%;
        background-position: bottom;
    }
     
    label{
        color:#505051 !important;
    }

    body{
        background:#fff;
    }

    input{
        border:0.5px solid #ef1b26 !important;
        border-radius:35px !important;
    }

    .float-bottom{
        position: fixed;
        height: 60px;
        bottom: 0px;
        width: 100%;
        left: 0px;
        text-align: center;
        background: #fff;
        border-top-left-radius: 35px;
        border-top-right-radius: 35px;
    }

    .float-bottom img{
        height:100%;
    }

    form{
        background: #7b7b7b1f;
        padding: 15px;
        border-radius: 15px;
        border: 0.5px solid #ef1b26;
        box-shadow: 0px 0px 23px -7px #838383;
    }

    @media (min-width:480px){
        .reg-right{
            padding: 32px;
            border-radius: 20px;
            background: #eee;
            border: 0.5px solid #ef1b26;
            box-shadow: 0px 0px 25px 0px #ccc;
        }

        .container-fluid{
            padding-left:15px !important;
            padding-right:15px !important;
        }

        body{
            background:#F6F6F6 !important;
        }

        form{
            background:none;
            border:none;
            box-shadow:none;
        }
    }

    .img-responsive{
        width:100%;
        border-radius:15px;
    }

    .btn-primary{
        background:#EE1C26 !important;
        border-radius:15px;
        color:#fff !important;
    }
</style>
<?php 
    $custombg = "";
    $fields = [];
    $mobile_bg = "";
    $close_other_fields = false;
    $check_url = "";
    $custom_style = "";
    $hide_fields = [];
    $after_form = "";

    if(isset($custom_fields)!="" && @$custom_fields->custom_user_fields!=""){
      $cf = json_decode($custom_fields->custom_user_fields,true);

      $fields     = @$cf['fields'];
      $custom_bg  = $cf['custom_bg'];

      $custombg = "
        background:#fff url(".url($custom_bg).") no-repeat;
        background-size: cover !important;
        background-position: center;
        border-radius: 49px;
      ";

      $mobile_bg = "<img src='".url($cf['custom_bg_mobile'])."' class='img mb-5 d-md-none img-responsive'/>";
      $close_other_fields = @$cf['close_other_fields'];
      $check_url          = @$cf['check']['url'];


      if(@$cf['submit_url']){
        $submit_url         = url($cf['submit_url']);
      }else{
        $submit_url         = url("register-submit");
      }

      if(@$cf['logo']){
        $logo_url           = url(@$cf['logo']);
      }else{
        $logo_url           = url("templates/admin/img/logo.jpg");
      }

      if(@$cf['custom_style']){
        $custom_style = $cf['custom_style'];
      }

      if(@$cf['hide_fields']){
        $hide_fields = $cf['hide_fields'];
      }

      if(@$cf['after_form']){
        $after_form = $cf['after_form'];
      } 

    }else{
      $submit_url         = url("register-submit");
      $logo_url           = url("templates/admin/img/logo.jpg");
    }

?>
<main role="main">
  <div class="container">
    <div class="bg-yellow registration"  <?=($custombg!='')?'style="background:none;"':''?>>
      <div class="row">
        <div class="col-xs-12 col-md-7 reg-left">
        <?php if($custombg==''){ ?>
          <h2>Join us now!</h2>
          <h1>
            Worlds #1<br>
            Digital Marketing <br>
            Platform
          </h1>
          <?php } ?>  
        </div>

        <div class="col-xs-12 col-md-5 reg-right">
          <a href="{{ url('') }}" class="ioh-subtitle">
            Join jadi DigiPreneur sekarang<br>
            dan dapatkan iPhone 14!*
          </a>


          <?php if($mobile_bg!=""){ ?>
            <!-- <div class="d-none d-md-block">
              <h3>Create Account</h3>
              <div class="form-group">
                <p class="">Already a member? <a href="{{ url('signin') }}" class="text-blue"><strong>Sign In here</strong></a></p>
              </div>
            </div> -->
            <?=$mobile_bg?>
          <?php }else{ ?>
            <!-- <h3>Create Account</h3>
            <div class="form-group">
              <p class="">Already a member? <a href="{{ url('signin') }}" class="text-blue"><strong>Sign In here</strong></a></p>
            </div> -->
          <?php } ?>
          
          <?=($is_google_registration!="")?"<div class='alert alert-warning'>You are about to create new Zeals Account and make it connected to your <span style='color:#6f0000;'>Google Account</span>. Or <a href='".url('cancel-integration')."' class='text-grey'>cancel (just register with email)</a></div>":''?>

          <form class="m-t top30 text-left" role="form" action="{{ $submit_url }}" method="POST" id="registerForm">
              <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
              
              <!-- custom fields -->
              <?php 
                if($fields){
                foreach($fields as $index=>$field){
                  ?>
                    <div class="form-group">
                        <label><?=$field['label']?></label>
                        <div class="input-group">
                          <input type="text" class="form-control" placeholder="<?=$field['label']?>" requireds="" name="<?=$field['name']?>" id="<?=$field['name']?>" data-placement="right"  autocomplete="off">
                          <div class="input-group-addon"><i class='icon icon-email'></i></div>
                        </div>
                        <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-<?=$field['name']?>"></div>
                    </div>
                  <?php 
                }
                }
              ?>
              <!-- custom fields -->
              
              <?php if($_SERVER["HTTP_HOST"]=='app.zeals.asia'){ ?>
                <div class="row">
                  <div class="col-xs-12 col-sm-12 col-lg-6">
                    <div class="form-group">
                      <label>First Name</label>
                      <div class="input-group">
                        <input type="text" <?=(@$close_other_fields['first_name'])?"readonly":""?> class="form-control" placeholder="First Name" requireds="" name="first_name" id="first_name" data-placement="right" autocomplete="off">
                        <div class="input-group-addon"><i class='icon icon-name'></i></div>
                      </div>
                      <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-first_name"></div>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-lg-6">
                    <div class="form-group">
                      <label>Last Name</label>
                      <div class="input-group">
                        <input type="text" <?=(@$close_other_fields['last_name'])?"readonly":""?> class="form-control" placeholder="Last Name" requireds="" name="last_name" id="last_name" data-placement="right" autocomplete="off">
                        <div class="input-group-addon"><i class='icon icon-name'></i></div>
                      </div>
                      <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-last_name"></div>
                    </div>
                  </div>
                </div>
              <?php }else{ ?>
                <div class="row">
                  <div class="col-xs-12 col-sm-12 col-lg-12">
                    <div class="form-group">
                      <label>Full Name</label>
                      <div class="input-group">
                        <input type="text" <?=(@$close_other_fields['first_name'])?"readonly":""?> class="form-control" placeholder="Full Name" requireds="" name="first_name" id="first_name" data-placement="right" autocomplete="off" style="border-radius:35px !important;">
                        <div class="input-group-addon"><i class='icon icon-name'></i></div>
                      </div>
                      <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-first_name"></div>
                    </div>
                  </div>
                </div>
              <?php } ?>
              
              <div class="form-group">
                  <label>Phone Number</label>
                  <div class="input-group">
                    <input type="tel" <?=(@$close_other_fields['phone'])?"readonly":""?> class="form-control" placeholder="0815xxx" requireds="" name="phone" id="phone" data-placement="right" autocomplete="off" style="border-radius:35px !important;">
                    <div class="input-group-addon"><i class='icon icon-phone'></i></div>
                  </div>
                  <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-phone"></div>
              </div>
              

              <?php if($_SERVER["HTTP_HOST"]=='app.zeals.asia'){ ?>

              <div class="form-group">
                  <label>Gender</label>
                  <div class="input-group">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="gender" id="gender_male" value="L" checked>
                      <label class="form-check-label text-strong" for="gender_male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="gender" id="gender_female" value="P">
                      <label class="form-check-label text-strong" for="gender_female">Female</label>
                    </div>
                  </div>
                  <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-gender"></div>
              </div>

              <div class="form-group">
                  <label>Date of birth</label>
                  <div class="input-group">
                    <input type="date" <?=(@$close_other_fields['dob'])?"readonly":""?> class="form-control" placeholder="Date of birth" requireds="" name="dob" id="dob" data-placement="right">
                    <div class="input-group-addon"><i class='icon icon-calendar'></i></div>
                  </div>
                  <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-dob"></div>
              </div>

              <?php } ?>

              <div class="form-group">
                  <label>Email</label>
                  <div class="input-group">
                    <input type="text" <?=(@$close_other_fields['email'])?"readonly":""?> class="form-control" placeholder="Email" requireds="" name="email" id="email" data-placement="right" <?=(@$is_google_registration!="")?"readonly":''?> value="<?=(@$is_google_registration!="")?$google_email:''?>" autocomplete="off" style="border-radius:35px !important;">
                    <div class="input-group-addon"><i class='icon icon-email'></i></div>
                  </div>
                  <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-email"></div>
              </div>

              <?php if(!@$hide_fields['password']){ ?>
              <div class="form-group">
                  <label>Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" placeholder="Password" requireds="" name="password" id="password" data-placement="right" autocomplete="off">
                    <div class="input-group-addon"><i class='icon icon-view-show'></i></div>
                  </div>
                  <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-password"></div>
              </div>
              <?php } ?>
              

              <?php if(Request::segment(2)!=""){ ?>
                <div class="form-group" style="<?=($_SERVER["HTTP_HOST"]=='app.zeals.asia')?'':'display:none;'?>">
                    <label>Referral Code</label>
                    <input type="text" class="form-control" value="<?=Request::segment(2)?>" name="department_code" id="department_code" data-placement="right" readonly>
                </div>
              <?php } ?>

              <div class="form-group">
                  <div class="checkbox i-checks text-grey p1"><label style="width:auto !important;"> <input type="checkbox" name="agree"><i></i> I agree to the <a href="<?=url("policy")?>" target="_blank" class="text-blue text-strong" style="display:inline-block !important;width:auto !important;">Terms & Conditions </a></label></div>
              </div>
              <div id="regalert"></div>
              <button type="submit" class="btn btn-primary block btn-block" id="btnreg">Register Now</button>
            

              <?php if(Request::segment(2)==""){ ?>
              <div class="mt-5 text-center">
                <p>
                  <a href="{{ url('startAuthGoogle') }}" class="btn btn-google btn-login mt-4 mb-3"><i class='fa fa-google'></i> &nbsp;&nbsp; Connect with Google</a>
                </p>
              </div>
              <?php } ?>
              
              <a class="btn btn-sm btn-white btn-block mt-3" href="{{ url('login') }}" style="color:#505051;font-weight:600;">*Syarat dan ketentuan berlaku</a>
             

              <?php if($is_google_registration!=""){ ?>
                <input type="hidden" name="google_id"  value="<?=$is_google_registration?>">
              <?php }?>
              <br>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<style>
<?=@$custom_style?>
</style>
<script src="{{ url('templates/admin/js/plugins/iCheck/icheck.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });

    $("#registerForm").submit(function(e){
      e.preventDefault();
      $(".alert").hide();
      $("#btnreg").addClass("disabled").prop("disabled",true).html("Processing..");
      $.ajax({
        type:"POST",
        dataType: "json",
        url:"<?=$submit_url?>",
        data: $(this).serialize()
      })
      .done(function(result){
        console.log(result);
        if(result.status=="success"){
          setTimeout(function(){
            //document.location = "{{ url('login') }}";
          },5000);
          $("#regalert").html(result.response);
          if(result.redirect!=""){
            document.location = result.redirect;
          }
        }else if(result.status == "error_validation"){
          var errors = result.response;
          for(var i in errors){
            $("#alert-"+i).html(errors[i][0]).show();
          }
          $("#regalert").html("<div class='alert alert-danger'>Please check the registration fields.</div>");
        }else{
          $("#regalert").html(result.response);
        }
        $("#btnreg").removeClass("disabled").prop("disabled",false).html("Register Now");
      })
      .fail(function(msg){
        console.log(msg);
        $("#btnreg").removeClass("disabled").prop("disabled",false).html("Register Now");
      })
      .always(function(){

      });
    });

    <?php if(@$cf['check']['trigger']){ ?>
    var checkTrigger;

    $("#<?=$cf['check']['trigger']?>").keydown(function(){
      clearTimeout(checkTrigger);

      checkTrigger = setTimeout(function(){
        checkData();
      },3000);

    });

    function checkData(){
      $.ajax({
        url: "<?=$check_url?>",
        type: "GET",
        data: {
          value: $("#<?=$cf['check']['trigger']?>").val(),
          _token:$("input[name='_token']").val()
        }
      })
      .done(function(result) {
        console.log(result);
        if(result.status=='success'){
          
          $("#first_name").val(result.data.first_name);
          $("#last_name").val(result.data.last_name);
          $("#phone").val(result.data.phone);
          $("#gender").val(result.data.gender);
          $("#email").val(result.data.email);

          alert("Hai "+result.data.first_name+"! Silahkan perbaiki/melengkapi data registrasi jika ada ketidaksesuaian");
        }else{
          alert("Data tidak dikenali! Mohon memasukkan data yang benar");
        }
      })
      .fail(function(msg) {
        alert("Data tidak dikenali! Mohon memasukkan data yang benar");
      });
    }
    <?php } ?>
</script>

<div class="float-bottom">
    <img src="<?=url('supported_by_zeals.png')?>">
</div>
