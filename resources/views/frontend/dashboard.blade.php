<!-- carousel -->
<!-- owl carousel -->
<link rel="stylesheet" href="{{ url('templates/frontend/plugin/owlcarousel/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ url('templates/frontend/plugin/owlcarousel/assets/owl.theme.default.min.css') }} ">
<link type="text/css" rel="stylesheet" href="{{ url('templates/frontend/plugin/lightslider/css/lightslider.min.css') }}" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<script src="{{ url('templates/frontend/plugin/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ url('templates/frontend/plugin/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ url('templates/frontend/plugin/lightslider/js/lightslider.min.js') }}"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<style>
th{
  font-weight: 500;
  border-top: 0px !important;
  color: #666;
  font-size: 14px;
}
</style>

<?php
  $campaigntypes = array(
    "banner"  => "AMP",
    "o2o"     => "O2O",
    "shopee"     => "AMP",
    "event"     => "Event",
  );
?>

<main role="main" class="main-content">
    <div class="">
      <div class="album-content">
        <h1 class="text-black mb-3">Dashboard</h1>
        <h3>Summary</h3>
        <div class="row">
          <div class="col-6 col-6 mt-2">
            <div class="panel">
              <div class="panel-content">
                <h1 class="text-black"><?=number_format(@$statistic['visit']['total'],0,',','.')?></h1>
                <small>Unique visitor</small>
              </div>
              <div class="badge-unique-visitor"></div>
            </div>
          </div>

          <div class="col-6 col-md-6 mt-2">
            <div class="panel">
              <div class="panel-content">
                <h1 class="text-black"><?=number_format(@$statistic['read']['percent'],2,',','.')?>%</h1>
                <small>Unique Reader</small>
                <div class="badge-unique-reader"></div>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-6 mt-2">
            <div class="panel">
              <div class="panel-content">
                <h1 class="text-black"><?=number_format(@$statistic['action']['percent'],2,',','.')?>%</h1>
                <small>Unique Action</small>
                <div class="badge-unique-action"></div>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-6 mt-2">
            <div class="panel">
              <div class="panel-content">
                <h1 class="text-black"><?=number_format(@$statistic['acquisition']['percent'],2,',','.')?>%</h1>
                <small>Unique Acquisition</small>
                <div class="badge-unique-acquisition"></div>
              </div>
            </div>
          </div>

          <div class="col-12 mt-5">
            <h3>Realtime Graphic Performance</h3>
            <div class="panel mt-4">
              <div class="panel-content" style="padding-left:15px;padding-right:15px;">
                <canvas id="lineChart" height="120" class="view-web"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-12 col-12 mt-3">
            <h3>Tracker Logs</h3>
            <div class="panel mt-4">
              <div class="panel-content box-log" style="height:300px;overflow-y:scroll;overflow-x:hidden;">
                <div class='table-responsive table-blue'>
                <?php
                  if($logs!=""){
                    print '<table class="table table-log">';
                    print '<tr>';
                    print '<th>Time</th>';
                    print '<th>Type</th>';
                    print '<th>IP</th>';
                    print '<th>Commission</th>';
                    print '<th>Location</th>';
                    print '</tr>';
                    foreach ($logs as $key => $value) {
                      ?>
                      <tr>
                        <td><?=date("d/m/Y H:i A",$value->time_created)?></td>
                        <td><?=$value->type_conversion?></td>
                        <td><?=$value->ip?></td>
                        <!--<td><?=$value->encrypted_code?></td>-->
                        <td class='text-right'>Rp.<?=number_format($value->commission,0,',','.')?></td>
                        <td><?=$value->city?></td>
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

          <div class="col-md-12 col-12 mt-3">
            <h3>Top 10 Earnings</h3>
            <div class="panel mt-4">
              <div class="panel-content box-log" style="height:300px;overflow-y:scroll;overflow-x:hidden;">
                <div class="table-responsive table-yellow">
                  <table class="table table-log">
                    <?php
                      if($earning['top10']){
                        print '<tr>';
                        print '<th>Campaign Title</th>';
                        print '<th>Campaign Type</th>';
                        print '<th>Total Earning</th>';
                        print '</tr>';
                        foreach ($earning['top10'] as $key => $value) {
                          ?>
                          <tr>
                            <td><?=$value->campaign_title?></td>
                            <td><?=$campaigntypes[@$value->campaign_type]?></td>
                            <td class="text-right"><strong>Rp.<?=number_format($value->earning,0,',','.')?></strong></td>
                          </tr>
                          <?php
                        }
                      }
                    ?>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<script>

    $("#formmain").submit(function(e){
      e.preventDefault();
      $("#updatealert").html("");
      $("#btnupdate").addClass("disabled").prop("disabled",true).html("Processing..");
      $.ajax({
        type:"POST",
        dataType: "json",
        url:"<?=url('user/ajax-update')?>",
        data: $(this).serialize()
      })
      .done(function(result){
        console.log(result);
        if(result.status=="success"){
          $("#updatealert").html(result.response);
        }else if(result.status == "error_validation"){
          var errors = result.response;
          for(var i in errors){
            $("#"+i).prop("title",errors[i][0]).prop("data-placement","right").tooltip().parent(".form-group").addClass("has-error");
          }
          $("#updatealert").html("<div class='alert alert-danger'>Tolong cek input! Ada kesalahan.</div>");
        }else{
          $("#updatealert").html(result.response);
        }
        $("#btnupdate").removeClass("disabled").prop("disabled",false).html("SIMPAN PERUBAHAN");
      })
      .fail(function(msg){
        console.log(msg);
        $("#btnupdate").attr("disabled",false).removeClass("disabled").html("SIMPAN PERUBAHAN");
      })
      .always(function(){

      });
    });


          var lineData = {
              labels:['5 days ago', '4 days ago', '3 days ago', '2 days ago', '1 days ago', 'under 12 hours'],
              datasets: [{
                    label: "Acquisition",
                    backgroundColor: "#961515",
                    borderColor: "#961515",
                    pointBackgroundColor: "#ffffff",
                    pointBorderColor: "#961515",
                    data: <?=json_encode(@$chart['acquisition'])?>
                },
                {
                    label: "Action",
                    backgroundColor: "#fcb13b",
                    borderColor: "#fcb13b",
                    pointBackgroundColor: "#ffffff",
                    pointBorderColor: "#fcb13b",
                    data: <?=json_encode(@$chart['action'])?>
                },
                {
                      label: "Stay/Read",
                      backgroundColor: "#c82360",
                      borderColor: "#c82360",
                      pointBackgroundColor: "#ffffff",
                      pointBorderColor: "#c82360",
                      data: <?=json_encode(@$chart['read'])?>
                  },
                {
                      label: "Visits",
                      backgroundColor: "#5eb6f0",
                      borderColor: "#5eb6f0",
                      pointBackgroundColor: "#ffffff",
                      pointBorderColor: "#5eb6f0",
                      data: <?=json_encode(@$chart['visit'])?>
                  }
              ]
          };

          var lineOptions = {
              responsive: true
          };


          var ctx = document.getElementById("lineChart").getContext("2d");
          new Chart(ctx, {type: 'bar', data: lineData, options:lineOptions});

          function initializeTestimoniSlider(event){
            setTimeout(function(){
            var yay = $('#owl-carousel-testimoni').find('.active.center').find('.item');
            //  console.log(yay.data('testi'))
              $('#testi-text-user').text(yay.data('testi'))

            }, 100);
          }

          var owl = $('#owl-carousel-testimoni').owlCarousel({
            loop:true,
            margin:10,
            dots:true,
            startPosition:2,
            center:true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                1000:{
                    items:3
                }
            },
            onInitialized : initializeTestimoniSlider
          })

          // Go to the next item
          $('.testimoni-prev').click(function() {
              owl.trigger('prev.owl.carousel');
          })
          // Go to the previous item
          $('.testimoni-next').click(function() {
              owl.trigger('next.owl.carousel', [300]);
          })

          owl.on('changed.owl.carousel', function(event) {
            setTimeout(function(){
            var yay = $('#owl-carousel-testimoni').find('.active.center').find('.item');
            //  console.log(yay.data('testi'))
              $('#testi-text-user').text(yay.data('testi'))

            }, 100);
          })
</script>
