  <div class="list-group">
    <a href="{{ url('dashboard') }}" class="list-group-item list-group-item-action <?=(Request::segment(1)=='dashboard')?'active':''?>">
    <i class="fa fa-chart-line"></i>
      Dashboard
    </a>
    <a href="{{ url('campaign') }}" class="list-group-item list-group-item-action <?=(Request::segment(1)=='campaign')?'active':''?>">
      <i class="fa fa-map-signs"></i>
      Campaign
    </a>
    <a href="{{ url('logs') }}" class="list-group-item list-group-item-action <?=(Request::segment(1)=='logs')?'active':''?>">
      <i class="fa fa-clipboard"></i>
      Logs
    </a>
    <a href="{{ url('my-wallet') }}" class="list-group-item list-group-item-action <?=(Request::segment(1)=='my-wallet')?'active':''?>">
      <i class="fa fa-wallet"></i>
      My Wallet
    </a>
    <a href="{{ url('profile') }}" class="list-group-item list-group-item-action <?=(Request::segment(1)=='profile')?'active':''?>">
      <i class="fa fa-cogs"></i>
      Setting & Profile
    </a>
    
    <!--
    <a href="{{ url('my-network') }}" class="list-group-item list-group-item-action <?=(Request::segment(1)=='my-network')?'active':''?>">My Network</a>
    -->
    <a href="{{ url('faq') }}" class="list-group-item list-group-item-action <?=(Request::segment(1)=='faq')?'active':''?>">
      <i class="fa fa-question"></i>
      FAQ
    </a>
    
    <?php
      $sessionqr = Session::get("masterqr");
      if($sessionqr==""){
    ?>
    <a href="{{ url('signout') }}" class="list-group-item list-group-item-action text-orange">
    <i class="fa fa-lock"></i>
      Sign Out
    </a>
    <?php } ?>
  </div>
<button class="btn btn-close view-mobile" onclick="closeMenu()"><i class="fa fa-times"></i></button>
