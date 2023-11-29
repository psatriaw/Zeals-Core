<link href="{{ url('templates/admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<?php 
    $custombg = "";
    $fields = [];
    $mobile_bg = "";
    $close_other_fields = false;
    $check_url = "";
    $custom_style = "";
    $hide_fields = [];
    $after_form = "";
    $simple_form = false;
    $redirect = "";
    $title  = "";
    $subtitle = "";

    if(isset($custom_fields)!="" && @$custom_fields->custom_user_fields!=""){
      $cf = json_decode($custom_fields->custom_user_fields,true);

      $fields       = @$cf['fields'];
      $custom_bg    = @$cf['custom_bg'];
      $redirect     = @$cf['redirect'];
      $title        = @$cf['title'];
      $subtitle     = @$cf['subtitle'];
      $absolute_bg  = @$cf['absolute_bg'];

      $custombg = "
        background:#fff url(".url($custom_bg).") no-repeat;
        background-size: cover;
        background-position: left;
      ";

      $url_google_play = "https://play.google.com/store/apps/details?id=asia.zeals.mobile.pwa";

      if(@$cf['custom_bg_mobile']){
        $mobile_bg = "<a href='$url_google_play'><img src='".url(@$cf['custom_bg_mobile'])."' class='img img-thumbnail mb-5 d-md-none '/></a>";
      }else{
        $mobile_bg = "";
      }
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

      if(@$cf['simple_form']){
        $simple_form = $cf['simple_form'];
      }

    }else{
      $submit_url         = url("register-submit");
      $logo_url           = url("templates/admin/img/logo.jpg");
    }

?>
<?php if($title!=""){ ?>
<title>
  <?=$title?>
</title>
<?php } ?>

<?php if(@$absolute_bg){ 
    print $absolute_bg; 
}?>

<main role="main">
  <div class="container">
    <div class="bg-yellow registration"  <?=($custombg!='')?'style="background:none;"':''?>>
      <div class="row">
        <div class="col-xs-12 col-md-7 reg-left" style="<?=$custombg?>">
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
          <a href="{{ url('') }}">
            <img src="<?=$logo_url?>" class="logo">
          </a>


          <?php if($mobile_bg!=""){ ?>
            <div class="d-none d-md-block create-account-head">
              <h3>Create Account</h3>
              <div class="form-group">
                <p class="">Already a member? <a href="{{ url('signin') }}" class="text-blue"><strong>Sign In here</strong></a></p>
              </div>
            </div>
            <?=$mobile_bg?>
          <?php }else{ ?>
            <div class="create-account-head">
              <h3>Create Account</h3>
              <div class="form-group">
                <p class="">Already a member? <a href="{{ url('signin') }}" class="text-blue"><strong>Sign In here</strong></a></p>
              </div>
            </div>
          <?php } ?>
          <?=($is_google_registration!="")?"<div class='alert alert-warning'>You are about to create new Zeals Account and make it connected to your <span style='color:#6f0000;'>Google Account</span>. Or <a href='".url('cancel-integration')."' class='text-grey'>cancel (just register with email)</a></div>":''?>
          <?php if($title!=""){ ?>
            <h1>
              <?=$title?>
            </h1>
          <?php } ?>

          <?php if($subtitle!=""){ ?>
            <h4>
              <?=$subtitle?>
            </h4>
          <?php } ?>
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
              
              <?php if(!$simple_form){ ?>
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
                        <input type="text" <?=(@$close_other_fields['first_name'])?"readonly":""?> class="form-control" placeholder="Full Name" requireds="" name="first_name" id="first_name" data-placement="right" autocomplete="off">
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
                    <input type="tel" <?=(@$close_other_fields['phone'])?"readonly":""?> class="form-control" placeholder="812xxx" requireds="" name="phone" id="phone" data-placement="right" autocomplete="off">
                    <div class="input-group-addon"><i class='icon icon-phone'></i></div>
                  </div>
                  <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-phone"></div>
              </div>
              

              <?php if(!$simple_form){ ?>

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
                    <input type="text" <?=(@$close_other_fields['email'])?"readonly":""?> class="form-control" placeholder="Email" requireds="" name="email" id="email" data-placement="right" <?=(@$is_google_registration!="")?"readonly":''?> value="<?=(@$is_google_registration!="")?$google_email:''?>" autocomplete="off">
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
                <div class="form-group" style="<?=(!$simple_form)?'':'display:none;'?>">
                    <label>Referral Code</label>
                    <input type="text" class="form-control" value="<?=Request::segment(2)?>" name="department_code" id="department_code" data-placement="right" readonly>
                </div>
              <?php } ?>

              <div class="form-group">
                  <div class="checkbox i-checks text-grey p1"><label style="width:auto !important;"> <input type="checkbox" name="agree"><i></i> I agree to the <a href="<?=url("policy")?>" target="_blank" class="text-blue text-strong" style="display:inline-block !important;width:auto !important;">Terms & Conditions </a></label></div>
              </div>
              <div id="regalert"></div>
              <button type="submit" class="btn btn-primary block btn-block" id="btnreg">Register Now</button>
              
              <?php if($after_form!=""){ ?>
                <?=$after_form?>
              <?php } ?>

              <?php if(Request::segment(2)==""){ ?>
              <div class="mt-5 text-center">
                <p>
                  <a href="{{ url('startAuthGoogle') }}" class="btn btn-google btn-login mt-4 mb-3"><i class='fa fa-google'></i> &nbsp;&nbsp; Connect with Google</a>
                </p>
              </div>
              <?php } ?>
              <!--
              <p class="text-muted text-center"><small>Sudah punya akun?</small></p>
              <a class="btn btn-sm btn-white btn-block" href="{{ url('login') }}">Login</a>
              -->

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
            ///document.location = "{{ url('login') }}";
            document.location = "https://play.google.com/store/apps/details?id=asia.zeals.mobile.pwa";
          },3000);
          $("#regalert").html(result.response);
          
          // if(result.redirect!=""){
          //   <?php if($redirect!=""){ ?>
          //     setTimeout(function(){
          //       document.location = "<?=$redirect?>";
          //     },3000);
          //   <?php }else{ ?>
          //     document.location = result.redirect;
          //   <?php } ?>
          // }
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
