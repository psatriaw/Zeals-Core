<style>
  .form-group label{
    color:#ffffff !important;
  }

  .text-white{
    color:#ffffff !important;
  }
</style>
<script>
  var coordinate = {
    longitude: null,
    latitude:null
  };

  var x = document.getElementById("landing_voucher");
  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      x.innerHTML = "Geolocation is not supported by this browser.";
    }
  }

  function showPosition(position) {
    coordinate.longitude  = position.coords.longitude;
    coordinate.latitude   = position.coords.latitude;

    $.ajax({
      url:"<?=url('checkOutletsarea')?>",
      type:'POST',
      data:{campaign_id:<?=$campaign->id_campaign?>, lat:coordinate.latitude, lng:coordinate.longitude}
    })
    .done(function(res){
      var data = JSON.parse(res);
      console.log(data);

      if(data.status=="valid"){
        $("#valid_outlets").html(data.html);
      }else{
        alert("Outlet is not for this voucher!");
        checkouting = false;
      }
    })
    .fail(function(e){
      console.log(e);
      checkouting = false;
    });
  }

  $(document).ready(function(){
    getLocation();
  });

</script>

<style>
  h3 .fa{
    color:#fff;
  }
  .relative{
    position: relative;
  }
  .text-left{
    text-align: left;
  }
  .label-field{
    font-size: 14px;
  }
  .scanner_container{
    height: auto;
    position: relative;
  }
  @media (max-width:480px){
    h3 {
      padding-bottom: 20px;
      color: #ffffff;
      font-weight: 700;
      font-size: 20px;
    }
    .voucher-title{
      font-size: 32px;
      padding-top: 20px;
      line-height: 1.2;
      color:#ffffff;
    }
    h3 .fa{
      font-size: 15px;
    }
  }
  .album-content{
    background: #5DB6F2;
    padding: 25px 15px;
    border-top-right-radius: 30px;
    border-top-left-radius: 30px;
  }
  .qrPreviewVideo{
    max-width: 100%;
  }
  .frame{
    z-index: 10;
    position: absolute;
    top: 0px;
    width: 150px;
    height: 150px;
    border: 5px solid #ff9e06c7;
    top: 50%;
    left: 50%;
    margin-left: -75px;
    margin-top: -75px;
  }
  .btn, .btn:focus, .btn:active{
    font-size: 24px !important;
    height: 70px !important;
  }
  #voucher_valid{
    font-size: 21px;
    color: #ffffff;
    background: #cb2961;
    padding: 15px;
    border: 2px solid #2f2f7c;
    display: none;
  }
  .label{
    background: #fbb03b;
    color: #fff;
    padding: 5px 13px;
    border-radius: 15px;
    font-size: 14px;
    vertical-align: middle;
  }

</style>
<script type="text/javascript" src="<?=url('templates/frontend/assets/js/jsqrscanner/docs/js/jsqrscanner.nocache.js')?>"></script>

<main role="main" id="landing_voucher">
  <div class="album pb-5 mt-4">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-12 col-sm-8 col-md-6" style="padding:0px;">
          <div class="album-content text-center mt-5">
            <h3><i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp; VOUCHER &nbsp;&nbsp;&nbsp;<i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-circle'></i></h3>
            {!! Form::open(['url' => url('login-public-auth'), 'method' => 'post', 'id' => 'voucherform','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
            <div class="row justify-content-md-center">
              <div class="col-12">
                <img src="{{ url($campaign->photos) }}" class="img img-responsive img-thumbnail"><br>
                <h1 class='voucher-title'><?=$campaign->campaign_title?></h1>
                <div class="text-center mt-3 mb-2">
                  <label class='label label-default'>Voucher valid until: <?=$campaign->end_date?></label>
                </div>
                <div class='voucher-description text-left'>
                  <p><?=nl2br($campaign->campaign_description)?></p>
                </div>
                <div class="form-group mt-4 mb-4">
                    <div class="text-center">
                      <div class="alert alert-info text-small">Click the button below to get your voucher, then click again to use the voucher.</div>
                      <div class="relative" id="scanner_view" style="display:none;">
                        <div class="scanner_container">
                          <div id="scanner" style="background: #000;max-width:100%;width:100%;margin-bottom:15px;position:relative;">
                            <div class="frame"></div>
                          </div>
                          <div class="form-group relative {{ ($errors->has('outlet_code')?"has-error":"") }}">
                              <label class="label-field">Outlet Code <sub>(get from the outlet)</sub></label>
                              {!! Form::text('outlet_code',null, ['class' => 'form-control','id' => 'outlet_code']) !!}
                          </div>
                        </div>
                      </div>

                      <div id="optin" style="display:none;text-align:left;">
                        <h4 class="text-left mb-3"><strong>Personal Info</strong></h4>

                        <div class="form-group relative {{ ($errors->has('optin_name')?"has-error":"") }}">
                            <label class="label-field">Full Name</label>
                            {!! Form::text('optin_name',null, ['class' => 'form-control','id' => 'optin_name']) !!}
                        </div>

                        <div class="form-group relative {{ ($errors->has('optin_dob')?"has-error":"") }}">
                            <label class="label-field">Date of Birth</label>
                            {!! Form::date('optin_dob',null, ['class' => 'form-control','id' => 'optin_dob']) !!}
                        </div>


                        <div class="form-group relative {{ ($errors->has('optin_institution')?"has-error":"") }}">
                            <label class="label-field">School/Institution</label>
                            {!! Form::select('optin_institution', ['school' => 'School', 'institution' => 'Institution','other' => 'Others'],null,['placeholder' => 'Select here','class' => 'form-control','id' => 'optin_institution','onchange' => 'checkInstitution(this.value)']) !!}
                        </div>

                        <div class="form-group relative {{ ($errors->has('optin_institution_name')?"has-error":"") }}" id="name-group" style="display:none;">
                            <label class="label-field" id="label-institution-name">Name</label>
                            {!! Form::text('optin_institution_name',null, ['class' => 'form-control','id' => 'optin_institution_name']) !!}
                        </div>

                        <div class="form-group relative {{ ($errors->has('optin_institution_division')?"has-error":"") }}" id="area-group" style="display:none;">
                            <label class="label-field" id="label-institution-division">Class</label>
                            {!! Form::text('optin_institution_division',null, ['class' => 'form-control','id' => 'optin_institution_division']) !!}
                        </div>
                        <script>
                          function checkInstitution(institution){
                            switch(institution){
                              case "school":
                                $("#label-institution-name").html('School Name');
                                $("#label-institution-division").html('Class');
                              break;

                              case "institution":
                                $("#label-institution-name").html('Institution Name');
                                $("#label-institution-division").html('Division');
                              break;

                              default:
                                $("#label-institution-name").html('Describe Here');
                              break;
                            }

                            $('#name-group').show();

                            if(institution=='other'){
                              $('#area-group').hide();
                            }else{
                              $('#area-group').show();
                            }
                          }
                        </script>


                        <div class="form-group relative {{ ($errors->has('optin_phone')?"has-error":"") }}">
                            <label class="label-field">Phone/WA Number</label>
                            {!! Form::text('optin_phone',null, ['class' => 'form-control','id' => 'optin_phone']) !!}
                        </div>

                        <div class="form-group relative {{ ($errors->has('optin_email')?"has-error":"") }}">
                            <label class="label-field">Email</label>
                            {!! Form::text('optin_email',null, ['class' => 'form-control','id' => 'optin_email']) !!}
                        </div>

                        <div class="form-group relative {{ ($errors->has('optin_address')?"has-error":"") }}">
                            <label class="label-field">City</label>
                            {!! Form::textarea('optin_address', null,['class' => 'form-control','id' => 'optin_address','rows' =>3]) !!}
                        </div>

                        <!-- new form -->

                        <div class="form-group relative {{ ($errors->has('optin_source')?"has-error":"") }}">
                            <label class="label-field">Where do you get info about this event?</label>
                            {!! Form::select('source', ['instagram' => 'Instagram/Facebook Ads', 'friend' => 'Your Friend','other' => 'Others'],null,['placeholder' => 'Select here','class' => 'form-control','id' => 'optin_source', 'onchange' => 'checkSourceFrom(this.value)']) !!}
                        </div>

                        <div class="form-group relative {{ ($errors->has('optin_other_source')?"has-error":"") }}" style="display:none;" id="control_optin_other_source">
                            <label class="label-field">From (please type here)</label>
                            {!! Form::text('from_source',null, ['class' => 'form-control','id' => 'optin_other_source']) !!}
                        </div>
                        <script>
                          function checkSourceFrom(from){
                            if(from=='other'){
                              $('#control_optin_other_source').show();
                            }else{
                              $('#control_optin_other_source').hide();
                            }
                          }
                        </script>
                        <!-- new form -->
                        

                        <?php if($campaign->additional_1){ ?>
                        <div class="form-group relative {{ ($errors->has('additional_1')?"has-error":"") }}">
                            <label class="label-field"><?=$campaign->additional_1?></label>
                            {!! Form::textarea('additional_1', null,['class' => 'form-control','id' => 'additional_1','rows' =>3]) !!}
                        </div>
                        <?php } ?>

                        <?php if($campaign->disclaimer){ ?>
                          <div style='background: #ffce80;padding: 20px;margin-top: 50px;font-size:14px;margin-bottom:15px;'>
                            <div class="form-check">
                              <input type="checkbox" class="form-check-input" id="disclaimer" name="disclaimer" value="yes">
                              <label class="form-check-label" for="disclaimer"><?=nl2br($campaign->disclaimer)?></label>
                            </div>
                          </div>
                        <?php } ?>
                        <div class="alert alert-danger text-small" id="alert-voucher-usage" style="display:none;">

                        </div>
                        <button class="btn btn-primary btn-lg btn-block shadow-none" type="button" id="usevoucher">Redeem Voucher</button>
                      </div>

                      <button class="btn btn-primary btn-lg btn-block shadow-none" type="button" id="getvoucherbtn">Get Your Voucher</button>

                      <div id="voucher_valid" class="text-center">

                      </div>
                    </div>
                </div>

                <div class='voucher-description text-left mt-4'>
                  <h4 class="mb-4">
                      <strong>Valid Outlets</strong>
                  </h4>
                  <div id="valid_outlets">
                    loading your location... 
                  </div>
                  <?php
                    if($outlets){
                      foreach ($outlets as $key => $value) {
                        ?>
                        <!-- <p><i class='fa fa-map-marker' style='width:15px;'></i> <strong><?=$value->outlet_name?></strong><br>
                        <span class='text-small' style='padding-left:20px;display:inline-block;'><?=$value->outlet_address?></span></p> -->
                        <?php
                      }
                    }
                  ?>
                </div>

                <div class='voucher-description text-left' style='background: #0465a6;color:#fff;padding: 20px;margin-top: 50px;'>
                  <h4><strong>Terms & Services</strong></h4>
                  <p class='text-small mt-3'>
                    Redemption only available at the outlet listed above. Please make sure the outlet is open for redemption process.
                  </p>

                  <p class='text-small'>
                    <strong>Zeals</strong> do not responsible to the voucher redemption process at the outlet. Every single experiences on the outlet transaction
                    being the brands/outlet responsibility.
                  </p>
                </div>

                <div class='text-center text-white' style='margin-top:25px;font-size:13px;color:#ffffff;'>Powered by <br><img src='https://app.zeals.asia/templates/admin/img/logo.jpg' style='width:120px;'/></div> <div class='text-center text-white' style='margin-top:25px;font-size:13px;color:#ffffff;'>Get Zeals Affiliate App on google play by clicking the badge below  <br><a href='https://play.google.com/store/apps/details?id=asia.zeals.mobile.pwa'><img src='https://app.zeals.asia/referrals/google-play-badge.png' style='width:120px;'/></a></div>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
</main>
<script>
  var voucher_code = "";
  var outlet_code  = "";
  var checkouting = false;

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $("#outlet_code").keyup(function(e){
    outlet_code = $("#outlet_code").val();
    if(outlet_code.length>=8){
      if(!checkouting){
        checkOutletCode();
      }
    }

  });

  function checkOutletCode(){
    checkouting = true;
    var outlet_code = $("#outlet_code").val();

    $.ajax({
      url:"<?=url('checkOutlet')?>",
      type:'POST',
      data:{outlet_code:outlet_code, voucher_code: voucher_code}
    })
    .done(function(res){
      var data = JSON.parse(res);
      console.log(data);

      if(data.status=="valid"){
        $("#optin").show();
        $("#getvoucherbtn").hide();
        $("#scanner_view").hide();
        checkouting = true;
      }else{
        alert("Outlet is not for this voucher!");
        checkouting = false;
      }
    })
    .fail(function(e){
      console.log(e);
      checkouting = false;
    });
  }

  $("#getvoucherbtn").click(function(){
    $("#getvoucherbtn").addClass("disabled").prop("disabled",true).html("getting your voucher..");
    if(voucher_code==""){
      $.ajax({
        url:"<?=url('getvoucher/'.$_GET['encrypted_code'])?>",
        type:'POST',
        data: []
      })
      .done(function(res){
        var data = JSON.parse(res);
        voucher_code = data.voucher_code;

        $("#getvoucherbtn").html(data.voucher_code);
        $("#getvoucherbtn").removeClass("disabled").prop("disabled",false);
      })
      .fail(function(e){
        console.log(e);
        $("#getvoucherbtn").removeClass("disabled").prop("disabled",false).html("Get Your Voucher");
      });
    }else{
      $("#scanner_view").show();
      <?php if($aff_attr->outlet_code!=''){ ?>
          $("#outlet_code").val('<?=$aff_attr->outlet_code?>');
          outlet_code = '<?=$aff_attr->outlet_code?>';
          $("#outlet_code").val(outlet_code);
          if(!checkouting){
            checkOutletCode();
          }
      <?php }else{ ?>
          bukaKamera();
          $("#getvoucherbtn").hide();
          $("#getvoucherbtn").removeClass("disabled").prop("disabled",false);
      <?php } ?>
    }
  });

    $("#usevoucher").click(function(){
      $("#usevoucher").addClass("disabled").prop("disabled",true).html("Checking your voucher!");
       var data = $("#voucherform").serialize();
       console.log(data);
       if($("#optin_name").val()==""){
         alert("Please fill your name!");
         $("#usevoucher").removeClass("disabled").prop("disabled",false).html("Redeem Voucher");
         return false;
       }

       if($("#optin_phonoe").val()==""){
         alert("Please fill your phone/WA Number!");
         $("#usevoucher").removeClass("disabled").prop("disabled",false).html("Redeem Voucher");
         return false;
       }

       if($("#optin_email").val()==""){
         alert("Please fill your email!");
         $("#usevoucher").removeClass("disabled").prop("disabled",false).html("Redeem Voucher");
         return false;
       }

       if($("#optin_address").val()==""){
         alert("Please fill your address!");
         $("#usevoucher").removeClass("disabled").prop("disabled",false).html("Redeem Voucher");
         return false;
       }

      var optins = {
        optin_name: $("#optin_name").val(),
        optin_phone: $("#optin_phone").val(),
        optin_email: $("#optin_email").val(),
        optin_address: $("#optin_address").val(),
        optin_dob: $("#optin_dob").val(),
        voucher_code: voucher_code,
        outlet_code: outlet_code,
      };

      <?php if($campaign->additional_1!=""){ ?>
        optins["additional_1"] = $("#additional_1").val();
      <?php }?>
      <?php if($campaign->disclaimer!=""){ ?>
        optins["disclaimer"] = $("#disclaimer").is(':checked')?$("#disclaimer").val():"no";
      <?php }?>


        optins["optin_source"]        = $("#optin_source").val();
        optins["optin_other_source"]  = $("#optin_other_source").val();
        optins["optin_institution_division"]  = $("#optin_institution_division").val();
        optins["optin_institution_name"]  = $("#optin_institution_name").val();
        optins["optin_institution"]       = $("#optin_institution").val();


      console.log(optins);
      $.ajax({
        url:"<?=url('usevoucer')?>",
        type:'POST',
        data: optins
      })
      .done(function(res){
        var data = JSON.parse(res);
        console.log(data);

        if(data.status=="valid"){
          $("#voucher_valid").show().html(data.response);
          $("#optin").hide();
        }else{
          $("#alert-voucher-usage").show().html(data.response);
          $("#usevoucher").removeClass("disabled").prop("disabled",false).html("Redeem Voucher");
        }
      })
      .fail(function(e){
        console.log(e);
        $("#usevoucher").removeClass("disabled").prop("disabled",false).html("Redeem Voucher");
      });
    });

    function onQRCodeScanned(scannedText)
    {
      console.log(scannedText);
      outlet_code = scannedText;
      if(outlet_code!="" && outlet_code.length>=8){
        $("#outlet_code").val(outlet_code);
        if(!checkouting){
          checkOutletCode();
        }
      }
    }

    //funtion returning a promise with a video stream
    function provideVideoQQ()
    {
        return navigator.mediaDevices.enumerateDevices()
        .then(function(devices) {
            var exCameras = [];
            devices.forEach(function(device) {
            if (device.kind === 'videoinput') {
              exCameras.push(device.deviceId)
            }
         });

            return Promise.resolve(exCameras);
        }).then(function(ids){
            if(ids.length === 0)
            {
              return Promise.reject('Could not find a webcam');
            }

            return navigator.mediaDevices.getUserMedia({
                video: {
                  'optional': [{
                    'sourceId': ids[1]//this way QQ browser opens the rear camera
                    //'sourceId': ids.length === 1 ? ids[0] : ids[1]//this way QQ browser opens the rear camera
                    }]
                }
            });
        });
    }

    //this function will be called when JsQRScanner is ready to use
    function bukaKamera()
    {
        //create a new scanner passing to it a callback function that will be invoked when
        //the scanner succesfully scan a QR code
        var jbScanner = new JsQRScanner(onQRCodeScanned);
        //var jbScanner = new JsQRScanner(onQRCodeScanned, provideVideoQQ);
        //reduce the size of analyzed images to increase performance on mobile devices
        jbScanner.setSnapImageMaxSize(300);
    	var scannerParentElement = document.getElementById("scanner");
    	if(scannerParentElement)
    	{
    	    //append the jbScanner to an existing DOM element
    		jbScanner.appendTo(scannerParentElement);
    	}
    }

    $(document).ready(function(){
      setTimeout(function(){
        //bukaKamera();
      },2000);

    });
</script>

<script src="<?=url('platform/js/zealsamp.js')?>"> </script>
