<html lang="en">
	<head>
			<style>
				td,th{
					border:0px;
					padding:10px;
				}
				th{
					background:#000;
					color:#fff;
					border-color:#000;
				}
			</style>
	  </head>
	  <body>
		<h3>KONFIRMASI PEMBAYARAN #<?=$cart_code?></h3>
		<?php 
			$datakonfirmasi = json_decode($data->data_confirmation,true);
		?>
		<p>
		  Ada transaksi konfirmasi pada toko online anda Pada <strong class='text-black'><?=date("d M Y, H:i", $datakonfirmasi['time'])?></strong> konfirmasi dilakukan pembeli ke <strong class='text-black'><?=$datakonfirmasi['tujuan']?></strong>, <br>
		  sejumlah <strong class='text-black'>Rp. <?=number_format($datakonfirmasi['nominal_transfer'])?></strong>
		  melalui  <strong class='text-black'><?=$datakonfirmasi['bank_pengirim']?></strong> dengan nomor rekening <strong class='text-black'><?=$datakonfirmasi['no_rekening_pengirim']?></strong>
		  atas nama <strong class='text-black'><?=$datakonfirmasi['nama_rekening_pengirim']?></strong>
		</p>
		
	  </body>
</html>