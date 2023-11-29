<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">

        <ul class="nav metismenu" id="side-menu">
            <?php if($previlege->isAllow($login->id_user,$login->id_department,"penerbit-profile-view")){ ?>
            <?php
            $id_department = $previlege->getDepartmentByCode('penerbit');
            if ($login->id_department == $id_department) {
                $id_penerbit = $previlege->getIDPenerbit($login->id_user);
            } else {
                $id_penerbit = 0;
            }
            ?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'pra-penawaran' ? "class='active'" : '' ?>>
                <a href="{{ url('master/pra-penawaran/edit/' . $id_penerbit) }}"><i class="fa fa-building"></i> <span
                        class="nav-label">Profil Perusahaan</span></a>
            </li>
            <?php } ?>

          <?php
            $active['dashboard_main']         = (Request::segment(1)=="dashboard" && Request::segment(2)=="view")?true:false;
            $active['dashboard_transaction']  = (Request::segment(1)=="dashboard" && Request::segment(2)=="transaction")?true:false;
            $active['dashboard_affiliator']   = (Request::segment(1)=="dashboard" && Request::segment(2)=="affiliator")?true:false;


            $allow['dashboard_main']           = $previlege->isAllow($login->id_user,$login->id_department,"dashboard-view");
            $allow['dashboard_transaction']    = $previlege->isAllow($login->id_user,$login->id_department,"dashboard-transaction");
            $allow['dashboard_affiliator']     = $previlege->isAllow($login->id_user,$login->id_department,"dashboard-affiliator");

          ?>
          <?php if(in_array(true,$allow)){ ?>
          <li <?=(in_array(true,$active))?"class='active'":""?>>
              <a href="#"><i class="fa fa-chart-line"></i> <span class="nav-label">Dashboard</span><span class="fa arrow"></span></a>
              <ul class="nav nav-second-level collapse">

                    <?php if($allow['dashboard_main']){ ?>
                    <li <?= $active['dashboard_main'] ? "class='active'" : '' ?>>
                        <a href="{{ url('dashboard/view') }}"><i class="fa fa-chart-line"></i> <span
                                class="nav-label">Super Dashboard</span></a>
                    </li>
                    <?php } ?>

                <?php if(@$allow['dashboard_transaction']){ ?>
                <li <?=($active['dashboard_transaction'])?"class='active'":""?>>
                    <a href="{{ url('dashboard/transaction') }}"><i class="fa fa-chart-pie"></i> <span class="nav-label">Transaction</span></a>
                </li>
                <?php } ?>

                <?php if(@$allow['dashboard_affiliator']){ ?>
                <li <?=($active['dashboard_affiliator'])?"class='active'":""?>>
                    <a href="{{ url('dashboard/affiliator') }}"><i class="fa fa-chart-bar"></i> <span class="nav-label">Growth</span></a>
                </li>

                    <?php } ?>
                </ul>
            </li>
            <?php } ?>

            <?php
            $active = [];
            $allow = [];
            
            $active['master_penerbit'] = Request::segment(1) == 'master' && Request::segment(2) == 'penerbit' ? true : false;
            $active['master_pra_penawaran'] = Request::segment(1) == 'master' && Request::segment(2) == 'pra-penawaran' ? true : false;
            $active['master_industri'] = Request::segment(1) == 'master' && Request::segment(2) == 'category' ? true : false;
            $active['rups'] = Request::segment(1) == 'master' && Request::segment(2) == 'rups' ? true : false;
            
            $allow['master_penerbit'] = $previlege->isAllow($login->id_user, $login->id_department, 'penerbit-view');
            $allow['master_pra_penawaran'] = $previlege->isAllow($login->id_user, $login->id_department, 'prapenawaran-view');
            $allow['master_industri'] = $previlege->isAllow($login->id_user, $login->id_department, 'category-view');
            $allow['rups'] = $previlege->isAllow($login->id_user, $login->id_department, 'rups-view');
            
            ?>

            <?php if(in_array(true,$allow)){ ?>
            <?php if(count(array_filter($allow))>2){ ?>
            <li <?= in_array(true, $active) ? "class='active'" : '' ?>>
                <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Penerbit</span><span
                        class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <?php } ?>

                    <?php if($allow['master_penerbit']){?>
                    <li <?= $active['master_penerbit'] ? "class='active'" : '' ?>>
                        <a href="{{ url('master/penerbit') }}"><i class="fa fa-users"></i> <span class="nav-label">Data
                                Brand</span></a>
                    </li>
                    <?php } ?>

                    <?php if($allow['master_pra_penawaran']){?>
                    <li <?= $active['master_pra_penawaran'] ? "class='active'" : '' ?>>
                        <a href="{{ url('master/pra-penawaran') }}"><i class="fa fa-landmark"></i> <span
                                class="nav-label">Pra Penawaran</span></a>
                    </li>
                    <?php } ?>

                    <?php if($allow['master_industri']){?>
                    <li <?= $active['master_industri'] ? "class='active'" : '' ?>>
                        <a href="{{ url('master/category') }}"><i class="fa fa-layer-group"></i> <span
                                class="nav-label">Industries/Interests</span></a>
                    </li>
                    <?php } ?>

                    <?php if($allow['rups']){?>
                    <li <?= $active['rups'] ? "class='active'" : '' ?>>
                        <a href="{{ url('master/rups') }}"><i class="fa fa-archive"></i> <span
                                class="nav-label">RUPS</span></a>
                    </li>
                    <?php } ?>

                    <?php if(count(array_filter($allow))>2){ ?>
                </ul>
            </li>
            <?php } ?>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"agency-view")){?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'agency' ? "class='active'" : '' ?>>
                <a href="{{ url('master/agency') }}"><i class="fa fa-map-marked-alt"></i> <span
                        class="nav-label">Agencies</span></a>
            </li>
            <?php } ?>

            <?php
            $active = [];
            $allow = [];
            
            $active['finance_withdrawal'] = Request::segment(1) == 'master' && Request::segment(2) == 'withdrawal' ? true : false;
            $active['finance_topup'] = Request::segment(1) == 'master' && Request::segment(2) == 'topup' ? true : false;
            $active['finance_deviden'] = Request::segment(1) == 'master' && Request::segment(2) == 'deviden' ? true : false;
            $active['finance_transaction'] = Request::segment(1) == 'master' && Request::segment(2) == 'transaksi' ? true : false;
            
            $allow['finance_withdrawal'] = $previlege->isAllow($login->id_user, $login->id_department, 'withdrawal-view');
            $allow['finance_topup'] = $previlege->isAllow($login->id_user, $login->id_department, 'topup-view');
            $allow['finance_deviden'] = $previlege->isAllow($login->id_user, $login->id_department, 'deviden-view');
            $allow['finance_transaction'] = $previlege->isAllow($login->id_user, $login->id_department, 'transaction-view');
            
            ?>
            <?php if(in_array(true,$allow)){ ?>
            <?php if(count(array_filter($allow))>2){ ?>
            <li <?= in_array(true, $active) ? "class='active'" : '' ?>>
                <a href="#"><i class="fa fa-money-bill"></i> <span class="nav-label">Finansial</span><span
                        class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <?php } ?>

                    <?php if($allow['finance_withdrawal']){?>
                    <li
                        <?= Request::segment(1) == 'master' && Request::segment(2) == 'withdrawal' ? "class='active'" : '' ?>>
                        <a href="{{ url('master/withdrawal') }}"><i class="fa fa-credit-card"></i> <span
                                class="nav-label">Withdrawals</span></a>
                    </li>
                    <?php } ?>

                    <?php if($allow['finance_topup']){?>
                    <li <?= $active['finance_topup'] ? "class='active'" : '' ?>>
                        <a href="{{ url('master/topup') }}"><i class="fa fa-id-card"></i> <span
                                class="nav-label">Topup</span></a>
                    </li>
                    <?php } ?>

                    <?php if($allow['finance_deviden']){?>
                    <li <?= $active['finance_deviden'] ? "class='active'" : '' ?>>
                        <a href="{{ url('master/deviden') }}"><i class="fa fa-money-bill"></i> <span
                                class="nav-label">Deviden</span></a>
                    </li>
                    <?php } ?>

                    <?php if($allow['finance_transaction']){?>
                    <li <?= $active['finance_transaction'] ? "class='active'" : '' ?>>
                        <a href="{{ url('master/transaksi') }}"><i class="fa fa-cubes"></i> <span
                                class="nav-label">Transaksi Beli</span></a>
                    </li>
                    <?php } ?>

                    <?php if(count(array_filter($allow))>2){ ?>
                </ul>
            </li>
            <?php } ?>
            <?php } ?>

            <?php
            $active = [];
            $allow = [];
            
            $active['custodian_user'] = Request::segment(1) == 'custodian' && Request::segment(2) == 'user' ? true : false;
            $active['custodian_purchase'] = Request::segment(1) == 'custodian' && Request::segment(2) == 'purchase' ? true : false;
            
            $allow['custodian_user'] = $previlege->isAllow($login->id_user, $login->id_department, 'custodian-user-view');
            $allow['custodian_purchase'] = $previlege->isAllow($login->id_user, $login->id_department, 'custodian-purchase-view');
            
            ?>
            <?php if(in_array(true,$allow)){ ?>
            <li <?= in_array(true, $active) ? "class='active'" : '' ?>>
                <a href="#"><i class="fa fa-university"></i> <span class="nav-label">Custodian</span><span
                        class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <?php if($allow['custodian_user']){?>
                    <li <?= $active['custodian_user'] ? "class='active'" : '' ?>>
                        <a href="{{ url('custodian/user') }}"><i class="fa fa-users"></i> <span
                                class="nav-label">Registran</span></a>
                    </li>
                    <?php } ?>

                    <?php if($allow['custodian_purchase']){?>
                    <li <?= $active['custodian_purchase'] ? "class='active'" : '' ?>>
                        <a href="{{ url('custodian/purchase') }}"><i class="fa fa-university"></i> <span
                                class="nav-label">Pembelian Saham</span></a>
                    </li>
                    <?php } ?>

                </ul>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"campaign-view")){ ?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'campaign' ? "class='active'" : '' ?>>
                <a href="{{ url('master/campaign') }}"><i class="fa fa-cubes"></i> <span
                        class="nav-label">Campaigns</span></a>
            </li>
            <?php } ?>

            <?php
            $active = [];
            $allow = [];
            
            $active['user_group'] = Request::segment(1) == 'admin' && Request::segment(2) == 'group' ? true : false;
            $active['module'] = Request::segment(1) == 'admin' && Request::segment(2) == 'module' ? true : false;
            $active['pengguna'] = Request::segment(1) == 'admin' && Request::segment(2) == 'user' && Request::segment(3) != 'profile' ? true : false;
            $active['demograph'] = Request::segment(1) == 'admin' && Request::segment(2) == 'demograph' ? true : false;
            
            $allow['user_group'] = $previlege->isAllow($login->id_user, $login->id_department, 'admin-master-group-show');
            $allow['module'] = $previlege->isAllow($login->id_user, $login->id_department, 'admin-master-module-show');
            $allow['pengguna'] = $previlege->isAllow($login->id_user, $login->id_department, 'admin-master-user-show');
            $allow['demograph'] = $previlege->isAllow($login->id_user, $login->id_department, 'demograph-view');
            ?>

            <?php if(in_array(true,$allow)){ ?>
            <li <?= in_array(true, $active) ? "class='active'" : '' ?>>
                <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Account, Access</span><span
                        class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">

                    <?php if($allow['module']){?>
                    <li <?= $active['module'] ? "class='active'" : '' ?>>
                        <a href="{{ url('admin/module') }}"><i class="fa fa-minus"></i> Modules</a>
                    </li>
                    <?php } ?>

                    <?php if($allow['user_group']){?>
                    <li <?= $active['user_group'] ? "class='active'" : '' ?>>
                        <a href="{{ url('admin/group') }}"><i class="fa fa-minus"></i> Group User</a>
                    </li>
                    <?php } ?>

                    <?php if($allow['pengguna']){?>
                    <li <?= $active['pengguna'] ? "class='active'" : '' ?>>
                        <a href="{{ url('admin/user') }}"><i class="fa fa-minus"></i> Accounts</a>
                    </li>
                    <?php } ?>

                    <?php if($allow['demograph']){?>
                    <li <?= $active['demograph'] ? "class='active'" : '' ?>>
                        <a href="{{ url('admin/demograph') }}"><i class="fa fa-minus"></i> Demographic</a>
                    </li>
                    <?php } ?>

                    <?php if($allow['pengguna']){?>
                    <li <?=($active['pengguna'])?"class='active'":""?>>
                        <a href="{{ url('admin/tempuser') }}"><i class="fa fa-minus"></i> Migration</a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>

            <!--
            <?php if($previlege->isAllow($login->id_user,$login->id_department,"locator-view")){?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'locator' ? "class='active'" : '' ?>>
                <a href="{{ url('master/locator') }}"><i class="fa fa-map-marked-alt"></i> <span class="nav-label">Merchant Locator</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"master-ticket-show")){?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'branch' ? "class='active'" : '' ?>>
                <a href="{{ url('master/branch') }}"><i class="fa fa-code-branch"></i> <span class="nav-label">Fremilt Branch</span></a>
            </li>
            <?php } ?>
            -->

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"banner-view")){ ?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'banner' ? "class='active'" : '' ?>>
                <a href="{{ url('master/banner') }}"><i class="fa fa-images"></i> <span
                        class="nav-label">Banner</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"kyc-view")){ ?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'kyc' ? "class='active'" : '' ?>>
                <a href="{{ url('master/kyc') }}"><i class="fa fa-user-shield"></i> <span
                        class="nav-label">KYC</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"tutorial-view")){ ?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'tutorial' ? "class='active'" : '' ?>>
                <a href="{{ url('master/tutorial') }}"><i class="fa fa-video"></i> <span
                        class="nav-label">Tutorials</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"feesetting-view")){ ?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'feesetting' ? "class='active'" : '' ?>>
                <a href="{{ url('master/feesetting') }}"><i class="fa fa-money-bill-wave"></i> <span
                        class="nav-label">Fee Setting</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"tagihan-view")){ ?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'tagihan' ? "class='active'" : '' ?>>
                <a href="{{ url('master/tagihan') }}"><i class="fa fa-money-bill"></i> <span
                        class="nav-label">Tagihan</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"master-pengaturan")){?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'pengaturan' ? "class='active'" : '' ?>>
                <a href="{{ url('master/pengaturan') }}"><i class="fa fa-cogs"></i> <span class="nav-label">System
                        Configuration</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"namecard-show")){?>
            <li <?= Request::segment(1) == 'admin' && Request::segment(2) == 'namecard' ? "class='active'" : '' ?>>
                <a href="{{ url('admin/namecards') }}"><i class="fa fa-id-card"></i> <span
                        class="nav-label">Namecards</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"bank-show")){?>
            <li <?= Request::segment(1) == 'admin' && Request::segment(2) == 'bank' ? "class='active'" : '' ?>>
                <a href="{{ url('admin/bank') }}"><i class="fa fa-id-card"></i> <span class="nav-label">Bank
                        List</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"profile-view")){ ?>
            <li
                <?= Request::segment(1) == 'admin' && Request::segment(2) == 'user' && Request::segment(3) == 'profile' ? "class='active'" : '' ?>>
                <a href="{{ url('admin/user/profile/' . $login->id_user) }}"><i class="fa fa-user"></i> <span
                        class="nav-label">Account Profile</span></a>
            </li>
            <?php } ?>

            <?php if($previlege->isAllow($login->id_user,$login->id_department,"microsite-view")){ ?>
            <li <?= Request::segment(1) == 'master' && Request::segment(2) == 'microsite' ? "class='active'" : '' ?>>
                <a href="{{ url('master/microsite') }}"><i class="fa fa-toolbox"></i> <span
                        class="nav-label">Microsite Builder</span></a>
            </li>
            <?php } ?>
        </ul>

    </div>
</nav>
