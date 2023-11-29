<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="<?=url('templates/frontend/assets/js/jsqrscanner/docs/js/jsqrscanner.nocache.js')?>"></script>
<?php
$main_url = "";
?>
<style>
  .box-log .row{
    border-bottom: 1px solid #eee;
    margin-bottom: 10px;
    padding-bottom: 10px;
  }
  .box-log .row div{
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
</style>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg sidebar-content">
        @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Campaign Report: Outlet</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/campaign') }}">Campaign</a>
                    </li>
                    <li class="active">
                        <strong>Report</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper-content">
          <div class="row form-group">
            <div class="col-sm-12">
                <a class="btn btn-white" href="{{ url('master/campaign/resume/'.$detail->campaign_link) }}">
                    <i class="fa fa-angle-left"></i> back
                </a>
            </div>
          </div>

          <h2 class="text-black">Logs</h2>
          <div class="row">
            <div class="col-sm-12">
              <div class="ibox">
                <div class="ibox-title" style="background:#5eb6f0;color:#fff;">
                  Redemptions
                </div>
                <div class="ibox-content text-center">
                  <div class='table-responsive'>
                  <style>

                  </style>
                  <div class="text-center">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                      <div id="scanner" style="background: #000;max-width:100%;width:100%;margin-bottom:15px;position:relative;">
                        <div class="frame"></div>
                      </div>
                    </div>
                  </div>
                  <?php
                    if(@$voucher_logs!=""){
                      print '<table class="table table-log">';
                      print '<tr>';
                      print '<th>Time</th>';
                      print '<th>Outlet</th>';
                      print '<th>Voucher</th>';
                      print '<th>IP</th>';
                      print '<th>City</th>';
                      print '<th>Name</th>';
                      print '<th>Email</th>';
                      print '<th>Phone</th>';
                      print '<th>Address</th>';
                      print '</tr>';
                      foreach ($voucher_logs as $key => $value) {
                        ?>
                        <tr>
                          <td class='text-left' style="width:180px;"><?=date("d/m/Y H:i:s A",@$value->time_usage)?></td>
                          <td class='text-left'><?=@$value->outlet_name?><br><small><i class='fa fa-map-marker'></i> <?=$value->outlet_address?></small></td>
                          <td class='text-left text-bold'><strong><?=@$value->voucher_code?></strong></td>
                          <td class='text-left'><?=@$value->ip?></td>
                          <td class='text-left'><?=@$value->city?></td>
                          <td class='text-left'><?=@$value->optin_name?></td>
                          <td class='text-left'><?=@$value->optin_email?></td>
                          <td class='text-left'><?=@$value->optin_phone?></td>
                          <td class='text-left'><?=@$value->optin_address?></td>
                        </tr>
                        <?php
                      }
                      print "</table>";
                    }else{
                      ?>
                      <p>No data found!</p>
                      <?php
                    }
                  ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        @include('backend.footer')
    </div>
</div>
<script>
  var outletcode = "<?=$outlet_id?>";
  var vouchercode = "";
  function onQRCodeScanned(scannedText)
  {
    console.log(scannedText);
    vouchercode = scannedText;
    useVoucher();
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
      bukaKamera();
    },2000);

  });

  function useVoucher(){
    $.ajax({
      url:"<?=url('uservoucherevent')?>",
      type:'POST',
      data: {voucher_code:vouchercode, outlet_id:outletcode}
    })
    .done(function(res){
      var data = JSON.parse(res);
      if(data.status=="success"){
        alert("Berhasil checkin!");
        location.reload();
      }else{
        alert(data.response);
        location.reload();
      }
    })
    .fail(function(e){
      console.log(e);
      alert("Voucher tidak dikenali!")
    });
  }
</script>
