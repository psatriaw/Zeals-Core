<main role="main">
  <?php
  
    $status = array(
      "unpaid"  => "<span class='text-gray'>UNPAID</span>",
      "paid"  => "<span class='text-success'>PAID</span>",
	  "cancelled"  => "<span class='text-danger'>CANCELLED</span>",
    );
  ?>
  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1>HISTORY</h1>
      </div>
    </div>
  </section>

  <div class="album py-1 pb-5 top30">
    <div class="container">
      <div class="album-content">
        <div class="table-responsive">
          <table class="table table-bordered table-hover dataTables-example" id="datatable">
            <thead>
              <tr>
                  <th style="width:30px;">No.</th>
                  <th>Transaksi</th>
                  <th style="width:120px;">Tanggal</th>
                  <th style="width:120px;">Total</th>
                  <th style="width:120px;">Status</th>
                  <th style="width:80px;">Detail</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $no = 0;
                if($histories){
                  foreach ($histories as $key => $value) {
                    $no++;
					/*
					$shipping = $value->shipping_info;
					if($shipping){
						  $shipping = explode("_",$shipping);
						  $shipping_cost = $shipping[0];
					}else{
						$shipping_cost = 0;
					}
					
					$expired = ($maks_bayar*60*60)+$value->time_created;
					
					if(time()>$expired){
						
						if($value->status=="unpaid"){
							$value->status="cancelled";
						}
					}
					*/		  
                    ?>
                    <tr>
                        <td style="width:30px;"><?=$no?></td>
                        <td><?=$value->cart_code?></td>
                        <td><?=date("d M Y, H:i",$value->last_update)?></td>
                        <td class="text-right">Rp. <?=number_format($value->amounts,0,",",".")?></td>
                        <td><?=$status[$value->status]?></td>
                        <?php if($value->trx_type=="confirmed"){ ?>
                          <td><a href="{{ url('history/'.$value->cart_code) }}" class="text-muted">[lihat]</a></td>
                        <?php }else{?>
                          <td><a href="{{ url('checkout/'.$value->cart_code) }}" class="text-muted">[lihat]</a></td>
                        <?php }?>
                    </tr>
                    <?php
                  }
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</main>
