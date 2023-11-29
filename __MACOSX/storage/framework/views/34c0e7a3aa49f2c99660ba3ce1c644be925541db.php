<?php
  $login    = Session::get("user");
?>
<div class="row border-bottom">
  <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <a class="navbar-minimalize minimalize-styl-2 btn btn-default " href="#"><i class="fa fa-bars"></i> </a>
    </div>
      <ul class="nav navbar-top-links navbar-right">
          <li>
              <span class="m-r-sm text-muted welcome-message">Hai, <strong><?=$login->first_name?></strong>! sebagai <strong><?=$login->department_name?></strong></span>
          </li>
          <li class="dropdown">
              <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                  <i class="fa fa-bell"></i>  <span class="label label-primary" id="unread-notif"></span>
              </a>
              <ul class="dropdown-menu dropdown-alerts" id="list-notif">
                <li>
                    memuat...
                </li>
              </ul>
          </li>


          <li>
              <a href="<?php echo e(url("logout")); ?>">
                  <i class="fa fa-sign-out"></i> Keluar
              </a>
          </li>
      </ul>

  </nav>
</div>
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/menus/top.blade.php ENDPATH**/ ?>