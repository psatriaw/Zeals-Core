<main role="main">
  <?php
    $status = array(
      "unpaid"  => "<span class='text-gray'>UNPAID</span>",
      "paid"  => "<span class='text-success'>PAID</span>",
      "cancelled"  => "<span class='text-danger'>CANCELLED</span>",
    );
	//print_r($detail);

	if(time()>$expired){
		//print "this";
		//print $detail->status;
		if($detail->status=="unpaid"){
			$detail->status="cancelled";
		}
	}
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
      <p class="description"><a href='{{ url('history') }}' class="text-black"><i class='fa fa-arrow-left'></i> kembali</a></p>
    </div>
  </section>
  <div class="album py-1 pb-5 top20">
    <div class="container">
      @include('backend.flash_message')
      <div class="album-content top20">
        <div class="table-responsive">
          <table class="table table-bordered table-hover dataTables-example" id="datatable">
            <thead>
              <tr>
                  <th style="width:30px;">No.</th>
                  <th>Item</th>
                  <th style="width:150px;">Ukuran</th>
                  <th style="width:150px;">Qty</th>
                  <th style="width:150px;">Unit</th>
                  <th class="text-right" style="width:150px;">Potongan</th>
                  <th class="text-right" style="width:150px;">Sub Berat</th>
                  <th class="text-right" style="width:150px;">Sub Total</th>
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
                        <td class="text-gray" style="width:30px;"><?=$no?></td>
                        <td class="text-gray"><?=$value->product_name?></td>
                        <td class="text-gray"><?=$value->size?></td>
                        <td class="text-gray"><?=$value->quantity?>
                        </td>
                        <td class="text-gray text-right">Rp. <?=number_format($value->item_price,0,",",".")?></td>
                        <td class="text-gray text-right">Rp. <?=number_format($value->item_discount,0,",",".")?></td>
                        <td class="text-gray text-right"><?=number_format($subberat,0,",",".")?>gr</td>
                        <td class="text-gray text-right">Rp. <?=number_format($subtotal,0,",",".")?></td>
                    </tr>
                    <?php
                  }
                }
              ?>

              <tr>
                  <td colspan="6" class='text-gray'>
                    <?php
                      $totafeeberat = 0;

                      if(@$shipping){
                        foreach ($shipping as $key => $value) {
                          $shipping_opt[$value->id_shipping_address]  =  $value->address_1.", ".$value->kecamatan.", ".$value->kota.", ".$value->provinsi;
                        }

                        if($detail->shipping_info=="" || @$_GET['re_shipping']=="yes"){
                          ?>
                          {!! Form::open(['url' => url('address-store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
                          <div class='row'>
                            <div class='col-2'>
                              Shipping to
                            </div>
                            <div class="col-10">
                              {!! Form::select('shipping_address', $shipping_opt, null, ['class' => 'form-control custom-select-checkout','style' => 'width:100%;','id' => 'id_provinsi','placeholder' => 'Pilih alamat tujuan','onchange' => 'getcourier(this.value)']) !!}
                            </div>
                          </div>
                          <div class='row' style="margin-top:10px;">
                            <div class='col-2'>
                              Courier
                            </div>
                            <div class="col-10">
                              <select name="shipping_courier" id="shipping_courier" class="form-control custom-select-checkout" onchange="setshipping(this.value)" style="width:100%;">

                              </select>
                            </div>
                          </div>
                          {!! Form::close() !!}
                          <?php
                        }else{
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
                          
                          <?php
                        }
                      }else{
                        echo "<span class='text-black'>Shipping</span> (Belum ditentukan alamat pengiriman, silahkan tambahkan alamat pengiriman <a href='".url('address')."' class='text-black'>disini</a>)";
                        echo "<br>";
                        echo "<span class='text-black'>Courier</span> (belum ditentukan)";
                      }

                    ?>
                    </td>
                  <td class=" text-right" ><?=number_format($totalberat,0,",",".")?>gr</td>
                  <td class=" text-right" id="shipping_cost">Rp. <?=number_format($totafeeberat,0,",",".")?></td>
              </tr>
              <tr>
                  <?php
                    $totalamount = $totafeeberat + $totalamount;
                  ?>
                  <td colspan="7" class='text-gray'>
                    Total
                  </td>
                  <td class=" text-right" id="grand_cost">Rp. <?=number_format($totalamount,0,",",".")?></td>
              </tr>

              <?php
                if($detail->data_confirmation!=""){
                  $datakonfirmasi = json_decode($detail->data_confirmation,true);
                  ?>
                  <tr>
                      <td colspan="8" class='text-gray custom-break'>
                          <h3 class="text-black">Konfimasi Pembayaran</h3>
                          Pada <strong class='text-black'><?=date("d M Y, H:i", $datakonfirmasi['time'])?></strong> konfirmasi dilakukan pembeli ke <strong class='text-black'><?=$datakonfirmasi['tujuan']?></strong>, <br>
                          sejumlah <strong class='text-black'>Rp. <?=number_format($datakonfirmasi['nominal_transfer'])?></strong>
                          melalui  <strong class='text-black'><?=$datakonfirmasi['bank_pengirim']?></strong> dengan nomor rekening <strong class='text-black'><?=$datakonfirmasi['no_rekening_pengirim']?></strong>
                          atas nama <strong class='text-black'><?=$datakonfirmasi['nama_rekening_pengirim']?></strong>
                      </td>
                  </tr>
                  <?php
                }
              ?>

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
          <?php if($detail->data_confirmation==""){ ?>
          {!! Form::open(['url' => url('confirmation/'.Request::segment(2)), 'method' => 'GET', 'id' => 'formconfirm','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}

          {!! Form::close() !!}
          <?php } ?>
        </div>
		<?php
			//$expired = time()-($maxtime*60*60);

			if(time()<$expired && $detail->data_confirmation==""){
		?>
		<button class="btn btn-primary pull-right" id="button-confirm">KONFIRMASI PEMBAYARAN</button>
		<?php }else{ ?>
		<?php if($detail->status=="cancelled"){ ?>
		<div class='alert alert-info'>Mohon maaf, masa konfirmasi pembayaran sudah terlewat. Order kamu sudah dibatalkan oleh sistem.</div>
		<?php } ?>
		<?php } ?>
      </div>
    </div>
  </div>
</div>
</main>
<script>
  function getcourier(val){
    $.ajax({
      type:"GET",
      dataType: "json",
      url:"<?=url('get-list-of-courier')?>",
      data: {id_shipping_address: val, total_weight: <?=$totalberat?>}
    })
    .done(function(result){
      console.log(result);

      if(result.status=="success"){
        $("#shipping_courier").html(result.html);
      }
    })
    .fail(function(msg){
      console.log(msg);
    })
    .always(function(){

    });
  }

  function setshipping(val){
    $.ajax({
      type:"GET",
      dataType: "json",
      url:"<?=url('set-shipping-to-cart')?>",
      data: {id_cart: <?=$detail->id_cart?>, info_shipping: val, sub_total : <?=$totalamount?>}
    })
    .done(function(result){
      console.log(result);
      if(result.status=="success"){
        console.log("sukses!");
        $("#shipping_cost").html(result.cost);
        $("#grand_cost").html(result.grand);
      }
    })
    .fail(function(msg){
      console.log(msg);
    })
    .always(function(){

    });
  }

  $("#button-confirm").click(function(){
    $("#formconfirm").submit();
  })

  $("#formtracking").submit(function(e){
	 $.ajax({
      type:"POST",
      dataType: "json",
      url:"<?=url('track-shipping')?>",
      data: {id_cart: <?=$detail->id_cart?>}
    })
    .done(function(result){
      console.log(result);
      if(result.status=="success"){
        $("#grand_cost").html(result.html);
      }
    })
    .fail(function(msg){
      console.log(msg);
    })
    .always(function(){

    });
  });
</script>
