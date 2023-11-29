<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1>ADDRESS</h1>
      </div>
    </div>
  </section>
  <div class="album py-1 pb-5 top30">
    <div class="container">
      <div class="text-right">
          <a href="{{ url('address-add') }}" class='btn btn-white'>Tambah<a>
      </div>
      @include('backend.flash_message')
      <div class="album-content top20">
        <div class="table-responsive">
          <table class="table table-bordered table-hover dataTables-example" id="datatable">
            <thead>
              <tr>
                  <th style="width:30px;">No.</th>
                  <th>Alamat</th>
                  <th style="width:150px;">Detail</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $no = 0;
                if($address){
                  foreach ($address as $key => $value) {
                    $no++;
                    ?>
                    <tr>
                        <td class="text-gray" style="width:30px;"><?=$no?></td>
                        <td class="text-gray"><?=$value->address_1?>, <?=$value->kecamatan?>, <?=$value->kota?>, <?=$value->provinsi?></td>
                        <td class="text-right text-gray">
                          <?php if($value->status=="default"){?>
                          <span class="text-success">[default]</span>
                          <?php }else{ ?>
                          <a href="{{ url('address-set-as-default/'.$value->id_shipping_address) }}" class="text-muted">[set as default]</a>
                          <?php }?>
                          <a href="{{ url('address-detail/'.$value->id_shipping_address) }}" class="text-muted">[lihat]</a>
                        </td>
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
