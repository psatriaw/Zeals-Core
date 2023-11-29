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
              <span class="m-r-sm text-muted welcome-message">Hi, <strong><?=$login->first_name?></strong>! as <strong><?=$login->department_name?></strong></span>
          </li>
          <li class="dropdown">
              <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                  <i class="fa fa-bell"></i>  <span class="label label-primary" id="unread-notif"></span>
              </a>
              <ul class="dropdown-menu dropdown-alerts" id="list-notif">
                <li>
                    loading...
                </li>
              </ul>
          </li>


          <li>
              <a href="{{ url("logout") }}">
                  <i class="fa fa-sign-out"></i> Sign Out
              </a>
          </li>
      </ul>

  </nav>
</div>
