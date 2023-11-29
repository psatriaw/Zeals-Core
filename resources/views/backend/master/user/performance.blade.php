

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
    "event"   => "EVENT"
  );
?>

<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Accounts</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Accounts</a>
                    </li>
                    <li class="active">
                        <strong>Performance Report of "<?=$data->first_name?>"</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
            <div class="col-lg-12">
                  <div class="form-group">
                      <a class="btn btn-white" href="{{ url($main_url) }}">
                          <i class="fa fa-angle-left"></i> Back
                      </a>
                  </div>
                  <h3 class="text-black mb-3">Dashboard</h3>
                  <div class="row">
                    <div class="col-6 col-md-6 mt-2">
                      <div class="ibox">
                        <div class="ibox-title text-center">
                          SALDO
                        </div>
                        <div class="ibox-content text-center">
                          <h3 class="text-black text-center"><?=number_format(@$saldo,0,',','.')?></h3>
                          <small>IDR</small>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-6 col-md-3 mt-2">
                      <div class="ibox">
                        <div class="ibox-title text-center" style="background:#5eb6f0;color:#fff;">
                          Unique Visitor
                        </div>
                        <div class="ibox-content text-center">
                          <h3 class="text-black text-center"><?=number_format(@$statistic['visit']['total'],0,',','.')?></h3>
                          <small>visitor</small>
                        </div>
                      </div>
                    </div>

                    <div class="col-6 col-md-3 mt-2">
                      <div class="ibox">
                        <div class="ibox-title text-center" style="background:#c82360;color:#fff;">
                          Unique Read/Stay
                        </div>
                        <div class="ibox-content  text-center">
                          <h3 class="text-black text-center"><?=number_format(@$statistic['read']['percent'],2,',','.')?>%</h3>
                          <small>of visitor</small>
                        </div>
                      </div>
                    </div>

                    <div class="col-6 col-md-3 mt-2">
                      <div class="ibox">
                        <div class="ibox-title text-center" style="background:#fcb13b;color:#fff;">
                          Unique Action
                        </div>
                        <div class="ibox-content  text-center">
                          <h3 class="text-black text-center"><?=number_format(@$statistic['action']['percent'],2,',','.')?>%</h3>
                          <small>of visitor</small>
                        </div>
                      </div>
                    </div>

                    <div class="col-6 col-md-3 mt-2">
                      <div class="ibox">
                        <div class="ibox-title text-center" style="background:#961515;color:#fff;">
                          Unique Acquisition
                        </div>
                        <div class="ibox-content  text-center">
                          <h3 class="text-black text-center"><?=number_format(@$statistic['acquisition']['percent'],2,',','.')?>%</h3>
                          <small>of visitor</small>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-12 mt-3">
                      <div class="ibox">
                        <div class="ibox-title" style="background:#c82360;color:#fff;">
                          Realtime Graphic Performance
                        </div>
                        <div class="ibox-content" style="padding-left:15px;padding-right:15px;">
                          <canvas id="lineChart" height="120" class="view-web"></canvas>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 mt-3">
                      <div class="ibox">
                        <div class="ibox-title" style="background:#5eb6f0;color:#fff;">
                          Tracker Log
                        </div>
                        <div class="ibox-content box-log" >
                          <div class='table-responsive'>
                          <?php
                            if($logs!=""){
                              print '<table class="table table-log">';
                              print '<tr>';
                              print '<th>Time</th>';
                              print '<th>Type</th>';
                              print '<th>IP</th>';
                              print '<th>Campaign</th>';
                              print '<th>Commission</th>';
                              print '</tr>';
                              foreach ($logs as $key => $value) {
                                ?>
                                <tr>
                                  <td><?=date("d/m/Y H:i:s A",$value->time_created)?></td>
                                  <td><?=$value->type_conversion?></td>
                                  <td><?=$value->ip?></td>
                                  <!--<td><?=$value->encrypted_code?></td>-->
                                  <td><?=$value->campaign_title?></td>
                                  <td class='text-right'>Rp.<?=number_format($value->commission,0,',','.')?></td>
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

                    <div class="col-md-12 mt-3">
                      <div class="ibox">
                        <div class="ibox-title" style="background:#5eb6f0;color:#fff;">
                          10 Top Earnings
                        </div>
                        <div class="ibox-content box-log">
                          <table class="table table-log">

                            <?php
                              print '<table class="table table-log">';
                              print '<tr>';
                              print '<th>Campaign</th>';
                              print '<th>Type</th>';
                              print '<th>Total Earning</th>';
                              print '</tr>';

                              if($earning['top10']){
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
        </div>
      </div>
</div>

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

          var ctx = document.getElementById("lineChart").getContext("2d");
          
          var lineData = {
              labels:<?=json_encode($label_chart)?>,
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
                  },
                {
                      label: "Reach",
                      backgroundColor: "#4fc1ae",
                      borderColor: "#188371",
                      pointBackgroundColor: "#4fc1ae",
                      pointBorderColor: "#188371",
                      data: <?=json_encode(@$chart['reach'])?>
                  }
              ]
          };

          var lineOptions = {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: (ctx) => 'Chart.js Line Chart - stacked=' + ctx.chart.options.scales.y.stacked
                },
                tooltip: {
                  mode: 'index'
                },
              },
              interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
              },
              scales: {
                x: {
                  title: {
                    display: true,
                    text: 'Month'
                  }
                },
                y: {
                  stacked: true,
                  title: {
                    display: true,
                    text: 'Value'
                  }
                }
              }
          };

          new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});

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
