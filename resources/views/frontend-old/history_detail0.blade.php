<main role="main">
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
      <p class="text-gray">Hai <strong class="text-black"><?=$login->first_name?> </strong>! Silahkan cek kembali  keranjang belanja kamu dibawah ini,<br>
pastikan shipping kamu sudah ke alamat yang tepat ya!</p>

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
                  <td colspan="5" class='text-gray'>
                    </td>
                  <td class=" text-right" ><?=number_format($totalberat,0,",",".")?>gr</td>
                  <td class=" text-right" id="shipping_cost">Rp. <?=number_format(0,0,",",".")?></td>
              </tr>
              <tr>
                  <?php
                    $totalamount =  $totalamount;
                  ?>
                  <td colspan="6" class='text-gray'>
                    Total
                  </td>
                  <td class=" text-right" id="grand_cost">Rp. <?=number_format($totalamount,0,",",".")?></td>
              </tr>

              <?php
                if($detail->data_confirmation!=""){
                  $datakonfirmasi = json_decode($detail->data_confirmation,true);
                  ?>
                  <tr>
                      <td colspan="7" class='text-gray'>
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
            </tbody>
          </table>
          <?php if($detail->data_confirmation==""){ ?>
          {!! Form::open(['url' => url('confirmation/'.Request::segment(2)), 'method' => 'GET', 'id' => 'formconfirm','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}

          {!! Form::close() !!}
          <button class="btn btn-primary pull-right" id="button-confirm">KONFIRMASI PEMBAYARAN</button>
          <?php } ?>
        </div>
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
</script>
