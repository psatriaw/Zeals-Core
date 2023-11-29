<div class="row border-bottom white-bg">
    <nav class="navbar navbar-static-top" role="navigation">
      <div class="navbar-header">
          <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
              <i class="fa fa-reorder"></i>
          </button>
          <a href="#" class="navbar-brand">uronShop</a>
      </div>
      <div class="navbar-collapse collapse" id="navbar">
          <ul class="nav navbar-nav">
              <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-wallet-show")){?>
              <li <?=(Request::segment(1)=="admin" && Request::segment(2)=="wallet")?"class='active'":""?>>
                  <a aria-expanded="false" role="button" href="<?php echo e(url('admin/wallet')); ?>"> uronWallet</a>
              </li>
              <?php } ?>

              <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-report-show")){?>
              <li <?=(Request::segment(1)=="admin" && Request::segment(2)=="report")?"class='active'":""?>>
                  <a aria-expanded="false" role="button" href="<?php echo e(url('admin/report')); ?>"> Laporan</a>
              </li>
              <?php } ?>

              <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-accountant-show")){?>
              <li <?=(Request::segment(1)=="admin" && Request::segment(2)=="accountant")?"class='active'":""?>>
                  <a aria-expanded="false" role="button" href="<?php echo e(url('admin/accountant')); ?>"> Daftar Akun</a>
              </li>
              <?php } ?>

              <li class="dropdown <?=(Request::segment(1)=="admin" && Request::segment(2)=="transaction" && in_array(array("bank","purchase","sale","fee"),Request::segment(3)))?"active":""?>">
                  <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Transaksi <span class="caret"></span></a>
                  <ul role="menu" class="dropdown-menu">
                      <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-transaction-bank-show")){?>
                        <li <?=(Request::segment(1)=="admin" && Request::segment(3)=="bank")?"class='active'":""?>><a href="<?php echo e(url('admin/transaction/bank')); ?>">Kas dan Bank</a></li>
                      <?php } ?>

                      <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-transaction-sale-show")){?>
                        <li <?=(Request::segment(1)=="admin" && Request::segment(3)=="sale")?"class='active'":""?>><a href="<?php echo e(url('admin/transaction/sale')); ?>">Penjualan</a></li>
                      <?php } ?>

                      <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-transaction-purchase-show")){?>
                        <li <?=(Request::segment(1)=="admin" && Request::segment(3)=="purchase")?"class='active'":""?>><a href="<?php echo e(url('admin/transaction/purchase')); ?>">Pembelian</a></li>
                      <?php } ?>

                      <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-transaction-cost-show")){?>
                        <li <?=(Request::segment(1)=="admin" && Request::segment(3)=="cost")?"class='active'":""?>><a href="<?php echo e(url('admin/transaction/cost')); ?>">Biaya</a></li>
                      <?php } ?>
                  </ul>
              </li>

              <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-shop-show")){?>
              <li <?=(Request::segment(1)=="admin" && Request::segment(2)=="shop")?"class='active'":""?>>
                  <a aria-expanded="false" role="button" href="<?php echo e(url('admin/shop')); ?>"> Toko</a>
              </li>
              <?php } ?>

              <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-vendor-show")){?>
              <li <?=(Request::segment(1)=="admin" && Request::segment(2)=="vendor")?"class='active'":""?>>
                  <a aria-expanded="false" role="button" href="<?php echo e(url('admin/vendor')); ?>"> Vendor</a>
              </li>
              <?php } ?>

              <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-product-show")){?>
              <li <?=(Request::segment(1)=="admin" && Request::segment(2)=="product")?"class='active'":""?>>
                  <a aria-expanded="false" role="button" href="<?php echo e(url('admin/product')); ?>"> Produk</a>
              </li>
              <?php } ?>

              <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-academy-show")){?>
              <li <?=(Request::segment(1)=="admin" && Request::segment(2)=="academy")?"class='active'":""?>>
                  <a aria-expanded="false" role="button" href="<?php echo e(url('admin/academy')); ?>"> uronAcademy</a>
              </li>
              <?php } ?>

              <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"admin-master-show")){?>
              <li <?=(Request::segment(1)=="admin" && Request::segment(2)=="master")?"class='active'":""?>>
                  <a aria-expanded="false" role="button" href="<?php echo e(url('admin/master')); ?>"> Master</a>
              </li>
              <?php } ?>

              <?php if($previlege_model->isAllow($login->id_user,$login->id_department,"master-pengaturan")){?>
              <li <?=(Request::segment(1)=="master" && Request::segment(2)=="pengaturan")?"class='active'":""?>>
                  <a aria-expanded="false" role="button" href="<?php echo e(url('master/pengaturan')); ?>"> Pengaturan</a>
              </li>
              <?php } ?>
          </ul>
          <ul class="nav navbar-top-links navbar-right">
              <li>
                  <a href="<?php echo e(url('logout')); ?>">
                      <i class="fa fa-sign-out"></i> Log out
                  </a>
              </li>
          </ul>
      </div>
    </nav>
</div>
<?php /**PATH /home/zealsasi/new.zeals.asia/resources/views/backend/menus/admin_menu.blade.php ENDPATH**/ ?>