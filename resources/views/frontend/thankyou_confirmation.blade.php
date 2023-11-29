<main role="main">
 <?php 
		$arraycode = array("[invoice_code]","[invoice_code]");
		$arrayreplace = array("#".$cart_code, "#".$cart_code);
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
	  <br>
	  <a href="{{ url('history/'.$cart_code) }}">[lihat pesanan]</a>
    </div>
	
  </section>
</main>

