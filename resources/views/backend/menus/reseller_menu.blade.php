<div class="row border-bottom white-bg">
  <nav class="navbar navbar-static-top" role="navigation">
    <div class="navbar-header">
        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
            <i class="fa fa-reorder"></i>
        </button>
        <a href="#" class="navbar-brand"></a>
    </div>
    <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav">
          <?php if($previlege->isAllow($login->id_user,$login->id_department,"penerbit-profile-view")){ ?>
          <?php
            $id_department = $previlege->getDepartmentByCode('penerbit');
            if($login->id_department==$id_department){
              $id_penerbit = $previlege->getIDPenerbit($login->id_user);
            }else{
              $id_penerbit = 0;
            }
          ?>
          <li <?=(Request::segment(1)=="master" && Request::segment(2)=="pra-penawaran")?"class='active'":""?>>
              <a href="{{ url('master/pra-penawaran/edit/'.$id_penerbit) }}"><i class="fa fa-building"></i> <span class="nav-label">Profil Perusahaan</span></a>
          </li>
          <?php } ?>

          <?php
            $active['dashboard_main']       = (Request::segment(1)=="dashboard" && Request::segment(2)=="view")?true:false;
            $active['dashboard_penerbit']   = (Request::segment(1)=="dashboard" && Request::segment(2)=="penerbit")?true:false;
            $active['dashboard_pemodal']    = (Request::segment(1)=="dashboard" && Request::segment(2)=="pemodal")?true:false;


            $allow['dashboard_main']        = $previlege->isAllow($login->id_user,$login->id_department,"dashboard-view");
            $allow['dashboard_penerbit']    = $previlege->isAllow($login->id_user,$login->id_department,"dashboard-penerbit");
            $allow['dashboard_pemodal']     = $previlege->isAllow($login->id_user,$login->id_department,"dashboard-pemodal");

          ?>
          <?php if(in_array(true,$allow)){ ?>
          <li <?=(in_array(true,$active))?"class='active'":""?>>
              <a href="#"><i class="fa fa-chart-line"></i> <span class="nav-label">Dashboard</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level collapse in">

                <?php if($allow['dashboard_main']){ ?>
                <li <?=($active['dashboard_main'])?"class='active'":""?>>
                    <a href="{{ url('dashboard/view') }}"><i class="fa fa-chart-line"></i> <span class="nav-label">Super Dashboard</span></a>
                </li>
                <?php } ?>

                <?php if($allow['dashboard_penerbit']){ ?>
                <li <?=($active['dashboard_penerbit'])?"class='active'":""?>>
                    <a href="{{ url('dashboard/penerbit') }}"><i class="fa fa-chart-pie"></i> <span class="nav-label">Penerbit</span></a>
                </li>
                <?php } ?>

                <?php if($allow['dashboard_pemodal']){ ?>
                <li <?=($active['dashboard_pemodal'])?"class='active'":""?>>
                    <a href="{{ url('dashboard/pemodal') }}"><i class="fa fa-chart-bar"></i> <span class="nav-label">Pemodal</span></a>
                </li>
              <?php } ?>
              </ul>
          </li>
          <?php } ?>

          <?php
            $active = array();
            $allow  = array();

            $active['master_penerbit']        = (Request::segment(1)=="master" && Request::segment(2)=="penerbit")?true:false;
            $active['master_pra_penawaran']   = (Request::segment(1)=="master" && Request::segment(2)=="pra-penawaran")?true:false;
            $active['master_industri']        = (Request::segment(1)=="admin" && Request::segment(2)=="category")?true:false;
            $active['rups']                   = (Request::segment(1)=="master" && Request::segment(2)=="rups")?true:false;

            $allow['master_penerbit']         = $previlege->isAllow($login->id_user,$login->id_department,"penerbit-view");
            $allow['master_pra_penawaran']    = $previlege->isAllow($login->id_user,$login->id_department,"prapenawaran-view");
            $allow['master_industri']         = $previlege->isAllow($login->id_user,$login->id_department,"category-view");
            $allow['rups']                    = $previlege->isAllow($login->id_user,$login->id_department,"rups-view");

          ?>

          <?php if(in_array(true,$allow)){ ?>
          <li <?=(in_array(true,$active))?"class='active'":""?>>
              <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Penerbit</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level collapse in">

                <?php if($allow['master_penerbit']){?>
                <li <?=($active['master_penerbit'])?"class='active'":""?>>
                    <a href="{{ url('master/penerbit') }}"><i class="fa fa-users"></i> <span class="nav-label">Data Penerbit</span></a>
                </li>
                <?php } ?>

                <?php if($allow['master_pra_penawaran']){?>
                <li <?=($active['master_pra_penawaran'])?"class='active'":""?>>
                    <a href="{{ url('master/pra-penawaran') }}"><i class="fa fa-landmark"></i> <span class="nav-label">Pra Penawaran</span></a>
                </li>
                <?php } ?>

                <?php if($allow['master_industri']){?>
                <li <?=($active['master_industri'])?"class='active'":""?>>
                    <a href="{{ url('master/category') }}"><i class="fa fa-layer-group"></i> <span class="nav-label">Industri Penerbit</span></a>
                </li>
                <?php } ?>

                <?php if($allow['rups']){?>
                <li <?=($active['rups'])?"class='active'":""?>>
                    <a href="{{ url('master/rups') }}"><i class="fa fa-archive"></i> <span class="nav-label">RUPS</span></a>
                </li>
                <?php } ?>

              </ul>
          </li>
          <?php } ?>

          <?php
            $active = array();
            $allow  = array();

            $active['finance_withdrawal']         = (Request::segment(1)=="master" && Request::segment(2)=="withdrawal")?true:false;
            $active['finance_topup']              = (Request::segment(1)=="master" && Request::segment(2)=="topup")?true:false;
            $active['finance_deviden']            = (Request::segment(1)=="master" && Request::segment(2)=="deviden")?true:false;
            $active['finance_transaction']        = (Request::segment(1)=="master" && Request::segment(2)=="transaksi")?true:false;


            $allow['finance_withdrawal']          = $previlege->isAllow($login->id_user,$login->id_department,"withdrawal-view");
            $allow['finance_topup']               = $previlege->isAllow($login->id_user,$login->id_department,"topup-view");
            $allow['finance_deviden']             = $previlege->isAllow($login->id_user,$login->id_department,"deviden-view");
            $allow['finance_transaction']         = $previlege->isAllow($login->id_user,$login->id_department,"transaction-view");


          ?>
          <?php if(in_array(true,$allow)){ ?>
          <?php if(count(array_filter($allow))>2){ ?>
          <li <?=(in_array(true,$active))?"class='active'":""?>>
              <a href="#"><i class="fa fa-money-bill"></i> <span class="nav-label">Finansial</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level collapse in">
                <?php } ?>

                <?php if($allow['finance_withdrawal']){?>
                <li <?=(Request::segment(1)=="master" && Request::segment(2)=="withdrawal")?"class='active'":""?>>
                    <a href="{{ url('master/withdrawal') }}"><i class="fa fa-credit-card"></i> <span class="nav-label">Pencairan</span></a>
                </li>
                <?php } ?>

                <?php if($allow['finance_topup']){?>
                <li <?=($active['finance_topup'])?"class='active'":""?>>
                    <a href="{{ url('master/topup') }}"><i class="fa fa-id-card"></i> <span class="nav-label">Topup</span></a>
                </li>
                <?php } ?>

                <?php if($allow['finance_deviden']){?>
                <li <?=($active['finance_deviden'])?"class='active'":""?>>
                    <a href="{{ url('master/deviden') }}"><i class="fa fa-money-bill"></i> <span class="nav-label">Deviden</span></a>
                </li>
                <?php } ?>

                <?php if($allow['finance_transaction']){?>
                <li <?=($active['finance_transaction'])?"class='active'":""?>>
                    <a href="{{ url('master/transaksi') }}"><i class="fa fa-cubes"></i> <span class="nav-label">Transaksi Beli</span></a>
                </li>
                <?php } ?>

              <?php if(count(array_filter($allow))>2){ ?>
              </ul>
          </li>
          <?php } ?>
          <?php } ?>

          <?php
            $active = array();
            $allow  = array();

            $active['custodian_user']         = (Request::segment(1)=="custodian" && Request::segment(2)=="user")?true:false;
            $active['custodian_purchase']     = (Request::segment(1)=="custodian" && Request::segment(2)=="purchase")?true:false;

            $allow['custodian_user']          = $previlege->isAllow($login->id_user,$login->id_department,"custodian-user-view");
            $allow['custodian_purchase']      = $previlege->isAllow($login->id_user,$login->id_department,"custodian-purchase-view");

          ?>
          <?php if(in_array(true,$allow)){ ?>
          <li <?=(in_array(true,$active))?"class='active'":""?>>
              <a href="#"><i class="fa fa-university"></i> <span class="nav-label">Custodian</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level collapse in">
                <?php if($allow['custodian_user']){?>
                <li <?=($active['custodian_user'])?"class='active'":""?>>
                    <a href="{{ url('custodian/user') }}"><i class="fa fa-users"></i> <span class="nav-label">Registran</span></a>
                </li>
                <?php } ?>

                <?php if($allow['custodian_purchase']){?>
                <li <?=($active['custodian_purchase'])?"class='active'":""?>>
                    <a href="{{ url('custodian/purchase') }}"><i class="fa fa-university"></i> <span class="nav-label">Pembelian Saham</span></a>
                </li>
                <?php } ?>

              </ul>
          </li>
          <?php } ?>

          <?php
            $active = array();
            $allow  = array();

            $active['campaign']         = (Request::segment(1)=="master" && Request::segment(2)=="campaign")?true:false;
            $active['pasar_sekunder']   = (Request::segment(1)=="master" && Request::segment(2)=="pasar-sekunder-view")?true:false;
            $active['laporan_campaign'] = (Request::segment(1)=="master" && Request::segment(2)=="laporan-campaign")?true:false;

            $allow['campaign']          = $previlege->isAllow($login->id_user,$login->id_department,"campaign-view");
            $allow['pasar_sekunder']    = $previlege->isAllow($login->id_user,$login->id_department,"pasar-sekunder-view");
            $allow['laporan_campaign']  = $previlege->isAllow($login->id_user,$login->id_department,"laporan-view");

          ?>
          <?php if(in_array(true,$allow)){ ?>
          <?php if(count(array_filter($allow))>2){ ?>
          <li <?=(in_array(true,$active))?"class='active'":""?>>
              <a href="#"><i class="fa fa-puzzle-piece"></i> <span class="nav-label">Campaign</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level collapse in">
              <?php } ?>

                <?php if($allow['campaign']){ ?>
                <li <?=($active['campaign'])?"class='active'":""?>>
                    <a href="{{ url('master/campaign') }}"><i class="fa fa-cubes"></i> <span class="nav-label">Campaign</span></a>
                </li>
                <?php } ?>

                <?php if($allow['pasar_sekunder']){?>
                <li <?=($active['pasar_sekunder'])?"class='active'":""?>>
                    <a href="{{ url('master/pasar-sekunder-view') }}"><i class="fa fa-puzzle-piece"></i> <span class="nav-label">Pasar Sekunder</span></a>
                </li>
                <?php } ?>

                <?php if($allow['laporan_campaign']){?>
                <li <?=($active['laporan_campaign'])?"class='active'":""?>>
                    <a href="{{ url('master/laporan-campaign') }}"><i class="fa fa-chart-line"></i> <span class="nav-label">Laporan</span></a>
                </li>
                <?php } ?>

                <?php if(count(array_filter($allow))>2){ ?>
              </ul>
          </li>
          <?php } ?>

        <?php } ?>

            <?php
              $active = array();
              $allow  = array();

              $active['user_group']   = (Request::segment(1)=="admin" && Request::segment(2)=="group")?true:false;
              $active['module']       = (Request::segment(1)=="admin" && Request::segment(2)=="module")?true:false;
              $active['pengguna']     = (Request::segment(1)=="admin" && Request::segment(2)=="user")?true:false;

              $allow['user_group']    = $previlege->isAllow($login->id_user,$login->id_department,"admin-master-group-show");
              $allow['module']        = $previlege->isAllow($login->id_user,$login->id_department,"admin-master-module-show");
              $allow['pengguna']      = $previlege->isAllow($login->id_user,$login->id_department,"admin-master-user-show");
            ?>
            <?php if($allow['user_group'] || $allow['module'] || $allow['pengguna']){ ?>
            <li <?=($active['user_group'] || $active['module'] || $active['pengguna'])?"class='active'":""?>>
                <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Pegawai & Akses</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse in">

                    <?php if($allow['module']){?>
                    <li <?=($active['module'])?"class='active'":""?>>
                        <a href="{{ url('admin/module') }}"><i class="fa fa-minus"></i> Module</a>
                    </li>
                    <?php } ?>

                    <?php if($allow['user_group']){?>
                      <li <?=($active['user_group'])?"class='active'":""?>>
                          <a href="{{ url('admin/group') }}"><i class="fa fa-minus"></i> Group Pengguna</a>
                      </li>
                    <?php } ?>

                    <?php if($allow['pengguna']){?>
                    <li <?=($active['pengguna'])?"class='active'":""?>>
                        <a href="{{ url('admin/user') }}"><i class="fa fa-minus"></i> Akun</a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>

            <!--
            <?php if($previlege->isAllow($login->id_user,$login->id_department,"locator-view")){?>
            <li <?=(Request::segment(1)=="master" && Request::segment(2)=="locator")?"class='active'":""?>>
                <a href="{{ url('master/locator') }}"><i class="fa fa-map-marked-alt"></i> <span class="nav-label">Merchant Locator</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"master-ticket-show")){?>
            <li <?=(Request::segment(1)=="master" && Request::segment(2)=="branch")?"class='active'":""?>>
                <a href="{{ url('master/branch') }}"><i class="fa fa-code-branch"></i> <span class="nav-label">Fremilt Branch</span></a>
            </li>
            <?php } ?>
            -->

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"banner-view")){ ?>
            <li <?=(Request::segment(1)=="master" && Request::segment(2)=="banner")?"class='active'":""?>>
                <a href="{{ url('master/banner') }}"><i class="fa fa-images"></i> <span class="nav-label">Banner</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"kyc-view")){ ?>
            <li <?=(Request::segment(1)=="master" && Request::segment(2)=="kyc")?"class='active'":""?>>
                <a href="{{ url('master/kyc') }}"><i class="fa fa-user-shield"></i> <span class="nav-label">KYC</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"tutorial-view")){ ?>
            <li <?=(Request::segment(1)=="master" && Request::segment(2)=="tutorial")?"class='active'":""?>>
                <a href="{{ url('master/tutorial') }}"><i class="fa fa-video"></i> <span class="nav-label">Tutorial</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"feesetting-view")){ ?>
            <li <?=(Request::segment(1)=="master" && Request::segment(2)=="feesetting")?"class='active'":""?>>
                <a href="{{ url('master/feesetting') }}"><i class="fa fa-money-bill-wave"></i> <span class="nav-label">Fee Setting</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"tagihan-view")){ ?>
            <li <?=(Request::segment(1)=="master" && Request::segment(2)=="tagihan")?"class='active'":""?>>
                <a href="{{ url('master/tagihan') }}"><i class="fa fa-money-bill"></i> <span class="nav-label">Tagihan</span></a>
            </li>
            <?php } ?>


            <?php if($previlege->isAllow($login->id_user,$login->id_department,"master-pengaturan")){?>
            <li <?=(Request::segment(1)=="master" && Request::segment(2)=="pengaturan")?"class='active'":""?>>
                <a href="{{ url('master/pengaturan') }}"><i class="fa fa-cogs"></i> <span class="nav-label">Pengaturan Sistem</span></a>
            </li>
            <?php } ?>


        </ul>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <a href="{{ url('logout') }}">
                    <i class="fa fa-sign-out"></i> Log out
                </a>
            </li>
        </ul>
    </div>
  </nav>
</div>
