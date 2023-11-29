<main role="main">
<?php 
	$first_name = $login->first_name;
	$cart_code 	= $cart_code;
	$maks_bayar = date("d M Y H:i",@$maks_bayar);
	$total 		= number_format($total,0,",",".");
	$rekening_tujuan = $rekening_tujuan;
	$confirm_link = url('confirmation/'.$cart_code);
	
	$arraycode 	= array(
		"[nama]","[invoice_code]","[waktu_bayar]","[total]","[info_rekening]","[confirm_link]"
	);
	
	$arrayreplace = array(
		$first_name, $cart_code, $maks_bayar, $total, $rekening_tujuan, $confirm_link
	);
?>		
  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1><?=$title?></h1>
      </div>
    </div>
 
    <div class="container text-center top100">
      <?=str_replace($arraycode, $arrayreplace,$content)?>
    </div>
  </section>
</main>

