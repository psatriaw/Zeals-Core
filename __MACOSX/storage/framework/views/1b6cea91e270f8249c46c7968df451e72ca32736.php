<style>
  h3 .fa{
    color:#2f2f7c;
  }

  @media (max-width:480px){
    h3 {
      font-size: 19px;
    }
    .voucher-title{
      font-size: 32px;
    }
  }
  .qrPreviewVideo{
    max-width: 100%;
  }
  .frame{
    z-index: 10;
    position: absolute;
    top: 0px;
    width: 50%;
    height: 50%;
    border: 5px solid #ff9e06c7;
    background: #ff9e0636;
    top: 25%;
    left: 25%;
  }
  .btn, .btn:focus, .btn:active{
    font-size: 24px;
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

<main role="main">
  <div class="album pb-5 mt-4">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-12 col-sm-8 col-md-6">
          <div class="album-content text-center mt-5">
            <h3><i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp; VOUCHER &nbsp;&nbsp;&nbsp;<i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-circle'></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-circle'></i></h3>
            <?php echo Form::open(['url' => url('login-public-auth'), 'method' => 'post', 'id' => 'voucherform','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]); ?>

            <div class="row justify-content-md-center">
              <div class="col-12">
                <img src="<?php echo e(url($campaign->photos)); ?>" class="img img-responsive img-thumbnail"><br>
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
                        <div id="scanner" style="height:auto;background: #000;max-width:100%;width:100%;margin-bottom:15px;"></div>
                        <div class="frame"></div>
                        <div class="form-group relative <?php echo e(($errors->has('outlet_code')?"has-error":"")); ?>">
                            <label class="label-field">Outlet Code <sub>(get from the outlet)</sub></label>
                            <?php echo Form::text('outlet_code',null, ['class' => 'form-control','id' => 'outlet_code']); ?>

                        </div>
                      </div>

                      <div id="optin" style="display:none;">
                        <h4 class="text-left mb-3"><strong>Personal Info</strong></h4>

                        <div class="form-group relative <?php echo e(($errors->has('optin_name')?"has-error":"")); ?>">
                            <label class="label-field">Full Name</label>
                            <?php echo Form::text('optin_name',null, ['class' => 'form-control','id' => 'optin_name']); ?>

                        </div>

                        <div class="form-group relative <?php echo e(($errors->has('optin_phone')?"has-error":"")); ?>">
                            <label class="label-field">Phone/WA Number</label>
                            <?php echo Form::text('optin_phone',null, ['class' => 'form-control','id' => 'optin_phone']); ?>

                        </div>

                        <div class="form-group relative <?php echo e(($errors->has('optin_email')?"has-error":"")); ?>">
                            <label class="label-field">Email</label>
                            <?php echo Form::text('optin_email',null, ['class' => 'form-control','id' => 'optin_email']); ?>

                        </div>

                        <div class="form-group relative <?php echo e(($errors->has('optin_address')?"has-error":"")); ?>">
                            <label class="label-field">Address</label>
                            <?php echo Form::textarea('optin_address', null,['class' => 'form-control','id' => 'optin_address','rows' =>3]); ?>

                        </div>
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
                  <?php
                    if($outlets){
                      foreach ($outlets as $key => $value) {
                        ?>
                        <p><i class='fa fa-map-marker' style='width:15px;'></i> <strong><?=$value->outlet_name?></strong><br>
                        <span class='text-small' style='padding-left:20px;display:inline-block;'><?=$value->outlet_address?></span></p>
                        <?php
                      }
                    }
                  ?>
                </div>

                <div class='voucher-description text-left'>
                  <h4><strong>Terms & Services</strong></h4>
                  <p class='text-small mt-3'>
                    Redemption only available at the outlet listed above. Please make sure the outlet is open for redemption process.
                  </p>

                  <p class='text-small'>
                    <strong>Zeals</strong> do not responsible to the voucher redemption process at the outlet. Every single experiences on the outlet transaction
                    being the brands/outlet responsibility.
                  </p>
                </div>

                <div class="form-group text-center mt-5">
                  <p class="text-grey p1"><a href="<?php echo e(url('register')); ?>" class="text-grey"><strong>Register</strong></a> to earn money from your thumbs!</p>
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
  var voucher_code = "";
  var outlet_code  = "";

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $("#outlet_code").keyup(function(e){
    outlet_code = $("#outlet_code").val();
    if(outlet_code.length>=8){
      checkOutletCode();
    }

  });

  function checkOutletCode(){
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
      }else{
        alert("Outlet is not for this voucher!");
      }
    })
    .fail(function(e){
      console.log(e);
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
      bukaKamera();
      $("#getvoucherbtn").hide();
      $("#getvoucherbtn").removeClass("disabled").prop("disabled",false);
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
        voucher_code: voucher_code,
        outlet_code: outlet_code,
      };
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
        checkOutletCode();
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
<?php /**PATH /home/zealsasi/new.zeals.asia/resources/views/frontend/voucher.blade.php ENDPATH**/ ?>