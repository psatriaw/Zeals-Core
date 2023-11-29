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
		  <?php
			$status = array(
			  "unpaid"  => "<span class='text-gray'>UNPAID</span>",
			  "paid"  => "<span class='text-success'>PAID</span>",
			);
		  ?>
		  <section class="jumbotron text-center">
			<div class="container">
			  <div class="superline"></div>
			  <div class="custom-container">
				<h1>TRANSAKSI</h1>
			  </div>
			</div>
			<div class="container text-center top100">
			  <h3> <?=$detail->cart_code?> [<?=($status[$detail->status])?>]</h3>
			</div>
		  </section>
		  <div class="album py-1 pb-5 top20">
			<div class="container">
			  <div class="album-content top20">
				<div class="table-responsive">
				  <table class="table table-bordered table-hover dataTables-example" id="datatable">
					<thead>
					  <tr>
						  <th style="padding:10px;width:30px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-left: 1px solid #ccc;border-top: 1px solid #ccc;">No.</th>
						  <th style="border-left:0px;padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;">Item</th>
						  <th style="border-left:0px;padding:10px;width:150px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;">Ukuran</th>
						  <th style="border-left:0px;padding:10px;width:150px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;">Qty</th>
						  <th style="border-left:0px;padding:10px;width:150px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;">Unit</th>
						  <th class="text-right" style="border-left:0px;padding:10px;width:150px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;">Potongan</th>
						  <th class="text-right" style="border-left:0px;padding:10px;width:150px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;">Sub Berat</th>
						  <th class="text-right" style="border-left:0px;padding:10px;width:150px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;">Sub Total</th>
					  </tr>
					</thead>
					<tbody>
					  <?php
						$totalamount = 0;
						$totalberat = 0;
						$no = 0;
						if(@$items){
						  foreach ($items as $key => $value) {
							$no++;
							$subberat = $value->berat * $value->quantity;
							$totalberat = $totalberat + $subberat;

							$subtotal = ($value->item_price - $value->item_discount)*$value->quantity;
							$totalamount = $totalamount + $subtotal;
							?>
							<tr>
								<td class="text-gray" style="padding:10px;width:30px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-left: 1px solid #ccc;"><?=$no?></td>
								<td class="text-gray" style="border-left:0px;padding:10px;width:200px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;"><?=$value->product_name?></td>
								<td class="text-gray" style="border-left:0px;padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;"><?=$value->size?></td>
								<td class="text-gray" style="border-left:0px;padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;"><?=$value->quantity?>
								</td>
								<td class="text-gray text-right" style="border-left:0px;padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">Rp. <?=number_format($value->item_price,0,",",".")?></td>
								<td class="text-gray text-right" style="border-left:0px;padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">Rp. <?=number_format($value->item_discount,0,",",".")?></td>
								<td class="text-gray text-right" style="border-left:0px;padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;"><?=number_format($subberat,0,",",".")?>gr</td>
								<td class="text-gray text-right" style="border-left:0px;padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">Rp. <?=number_format($subtotal,0,",",".")?></td>
							</tr>
							<?php
						  }
						}
					  ?>

					  <tr>
						  <td colspan="6" class='text-gray' style="padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-left: 1px solid #ccc;">
							<?php
							  $totafeeberat = 0;
								  ?>
								  <div class='row'>
									<div class='col-2 text-black'>
									  Shipping to
									</div>
									<div class="col-10 custom-break">
									  <?php
										$detailshipping = $shipping_address_model->getDetail($detail->id_shipping_address);
										print $detailshipping->address_1.", ".$detailshipping->kecamatan.", ".$detailshipping->kota.", ".$detailshipping->provinsi
									  ?>
									</div>
								  </div>
							</td>
						  <td class=" text-right"  style="border-left:0px;padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;"><?=number_format($totalberat,0,",",".")?>gr</td>
						  <td class=" text-right"  style="border-left:0px;padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">Rp. <?=number_format($totafeeberat,0,",",".")?></td>
					  </tr>
					  <tr>
						  <?php
							$totalamount = $totafeeberat + $totalamount;
						  ?>
						  <td colspan="7" class='text-gray' style="padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-left: 1px solid #ccc;">
							Total
						  </td>
						  <td class=" text-right" id="grand_cost" style="border-left:0px;padding:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">Rp. <?=number_format($totalamount,0,",",".")?></td>
					  </tr>

					  
					  <tr>
						  <td colspan="8" class='text-gray custom-break' style="padding:10px;">
							  <h3 class="text-black">Pembeli </h3>
							  <strong><?=$login->first_name?> <?=$login->last_name?></strong>
							  <br>
							  <strong><?=$login->phone?></strong>
						  </td>
					  </tr>
					  
					  <tr>
						  <td colspan="8" class='text-gray custom-break' style="padding:10px;">
							  <h3 class="text-black">Pembayaran </h3>
							  <strong><?=$info_rekening?></strong>
							  <br>
							  Mohon melakukan pembayaran sebelum <strong><?=$maks_bayar?></strong>
						  </td>
					  </tr>
					</tbody>
				  </table>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	</body>
</html>