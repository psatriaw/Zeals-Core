<?php
  if(@$login['avatar']==""){
    $avatar = "https://us.123rf.com/450wm/apoev/apoev2107/apoev210700050/171681531-default-avatar-photo-placeholder-gray-profile-picture-icon-woman-in-t-shirt.jpg";
  }else{
    $avatar = url($login['avatar']);
  }
?>
<div class="menu mb-4">
  <div class="media mt-3 mb-3">
    <img src="<?=$avatar?>" class="avatar img img-rounded logo" >
    <div class="media-body">
      <h5 class="mt-0">Hi, <span class='text-blue text-strong'><?=@$login['first_name']?> <?=@$login->last_name?></span>,<br><span class='text-grey'>Welcome back!</span></h5>
    </div>
  </div>

  <h3>My Wallet</h3>
  <div class="panel mt-3 mb-5">
    <div class="row saldo">
      <div class="col-xs-12 col-md-7 d-flex saldo-current">
        <h1 class="saldo-amount">IDR. <?=number_format($saldo,0,",",".")?></h1>
      </div>
      <div class="col-xs-12 col-md-5 saldo-button">
        <a href="{{ url('my-wallet/withdraw') }}" class="btn btn-primary btn-block">Withdraw</a>
      </div>
    </div>
  </div>

  <h3>Joined Campaign</h3>
    <?php
      if($joined_campaign){
        foreach ($joined_campaign as $key => $value) {
          ?>
          <div class="panel mt-3">
            <a href="{{ url('campaign/detail/'.$value->campaign_link) }}">
              <h4><?=$value->campaign_title?></h4>
            </a>
            <div class="row highlight-campaign">
              <div class="col-6 pr05 highlight-item">
                <div class="outlined-yellow-box">
                  <span>Reach:</span>  <span class='text-strong text-black'><?=number_format($value->total_reach,0,",",".")?></span>
                </div>
              </div>
              <div class="col-6 pl05 highlight-item">
                <div class="outlined-yellow-box">
                  <span>Earning:</span> <span class='text-strong text-black'>IDR <?=number_format($value->total_earning,0,",",".")?></span>
                </div>
              </div>
              <div class="col-12 highlight-item">

                <?php
                  if($value->data_categories){
                    $categories = explode(":",$value->data_categories);
                    foreach ($categories as $kx => $vx) {
                      $item = explode("_",$vx);
                      ?><a  href="{{ url('campaign/'.$item[1]) }}" class="yellow-box"><?=$item[0]?></a><?php
                    }
                  }
                ?>
                <a href="{{ url('campaign/detail/'.$value->campaign_link) }}" class="pull-right bullet-blue"><i class="fa fa-angle-right"></i> </a>
              </div>
            </div>
          </div>
          <?php
        }
      }
    ?>

  <h3 class="mt-5">You might like this</h3>

  <?php
    if($might_like){
      foreach ($might_like as $key => $value) {
        ?>
        <div class="panel mt-3">
          <a href="{{ url('campaign/detail/'.$value->campaign_link) }}">
            <h4><?=$value->campaign_title?></h4>
          </a>
          <div class="row">
            <div class="col-12">

              <?php
                if($value->data_categories){
                  $categories = explode(":",$value->data_categories);
                  foreach ($categories as $kx => $vx) {
                    $item = explode("_",$vx);
                    ?><a  href="{{ url('campaign/'.$item[1]) }}" class="yellow-box"><?=$item[0]?></a><?php
                  }
                }
              ?>
              <a href="{{ url('campaign/detail/'.$value->campaign_link) }}" class="pull-right bullet-blue"><i class="fa fa-angle-right"></i> </a>
              <br>
              <p class="mt-2">
                <i class="fa fa-calendar"></i> valid until <?=date("M d",strtotime($value->end_date))?><sup><?=date("S",strtotime($value->end_date))?></sup> <?=date("Y",strtotime($value->end_date))?>
              </p>
            </div>
          </div>
        </div>
        <?php
      }
    }else{
      ?>
      <div class="panel mt-3">
        <p>Please set up your campaign preferences from your profile</p>
      </div>
      <?php
    }
  ?>
</div>
