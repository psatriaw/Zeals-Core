<?php
  if(@$login['avatar']==""){
    $avatar = "https://us.123rf.com/450wm/apoev/apoev2107/apoev210700050/171681531-default-avatar-photo-placeholder-gray-profile-picture-icon-woman-in-t-shirt.jpg";
  }else{
    $avatar = url($login['avatar']);
  }
?>
<div class="menu" id="sidebarmenu">
  <div class="super-menu text-center">
    <img src="<?=$avatar?>" class="avatar img img-rounded">
    <br>
    <span class="avatar-name"><?=@$login['first_name']?> <?=@$login->last_name?></span>
    <div class="balance">
      <span>Balance</span>
      <div class="balance-amount">Rp. <?=number_format(@$saldo,0,',','.')?></div>
    </div>
    <a class="btn btn-block btn-white" href="<?php echo e(url('my-wallet/withdraw')); ?>">Withdraw</a>
  </div>
  <button class="btn btn-close view-mobile" onclick="closeMenu()"><i class="fa fa-times"></i></button>

  <div class="list-group">
    <a href="<?php echo e(url('dashboard')); ?>" class="list-group-item list-group-item-action <?=(Request::segment(1)=='dashboard')?'active':''?>">
      Dashboard
    </a>
    <a href="<?php echo e(url('campaign')); ?>" class="list-group-item list-group-item-action <?=(Request::segment(1)=='campaign')?'active':''?>">Campaign</a>
    <a href="<?php echo e(url('logs')); ?>" class="list-group-item list-group-item-action <?=(Request::segment(1)=='logs')?'active':''?>">Logs</a>
    <a href="<?php echo e(url('my-wallet')); ?>" class="list-group-item list-group-item-action <?=(Request::segment(1)=='my-wallet')?'active':''?>">My Wallet</a>
    <!--
    <a href="<?php echo e(url('my-network')); ?>" class="list-group-item list-group-item-action <?=(Request::segment(1)=='my-network')?'active':''?>">My Network</a>
    -->
    <a href="<?php echo e(url('faq')); ?>" class="list-group-item list-group-item-action <?=(Request::segment(1)=='faq')?'active':''?>">FAQ</a>
    <a href="<?php echo e(url('tutorial')); ?>" class="list-group-item list-group-item-action <?=(Request::segment(1)=='tutorial')?'active':''?>">Tutorial</a>
    <a href="<?php echo e(url('profile')); ?>" class="list-group-item list-group-item-action <?=(Request::segment(1)=='profile')?'active':''?>">Setting & Profile</a>
    <a href="<?php echo e(url('signout')); ?>" class="list-group-item list-group-item-action text-danger">Sign Out <span class='pull-right'><i class="fa fa-power-off"></i></span></a>
  </div>
</div>
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/frontend/menu_sidebar.blade.php ENDPATH**/ ?>