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
		<h3>RESI PESANAN #<?=$cart_code?></h3>
		<p>
		  Hai, pesanan kamu sedang dalam pengiriman, untuk membantu pelacakan, silahkan klik pada link nomor resi dibawah ini
		</p>
		<h3>
			<a href="https://cekresi.com/?noresi=<?=$resi?>" target="_blank"><strong><?=$resi?></strong></a>
		</h3>
	  </body>
</html>