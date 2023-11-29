<div class="list-group menu">
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
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/frontend/menu_sidebar.blade.php ENDPATH**/ ?>