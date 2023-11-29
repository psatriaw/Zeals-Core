<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1>COMPLAIN</h1>
      </div>
    </div>
  </section>

  <div class="album py-1 pb-5 top30">
    <div class="container">
      <div class="text-right">
          <a href="{{ url('complain-add') }}" class='btn btn-white'>Tambah<a>
      </div>
      @include('backend.flash_message')

      <div class="album-content top20">
        <div class="table-responsive">
          <table class="table table-bordered table-hover dataTables-example" id="datatable">
            <thead>
              <tr>
                  <th style="width:30px;">No.</th>
                  <th>Subject</th>
                  <th style="width:120px;">Response</th>
                  <th style="width:120px;">Status</th>
                  <th style="width:150px;">Detail</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $no = 0;
                if($tickets){
                  foreach ($tickets as $key => $value) {
                    $no++;
                    ?>
                    <tr>
                      <td class="text-gray" style="width:30px;"><?=$no?></td>
                      <td class="text-gray"><?=$value->subject?></td>
                      <td class="text-gray"><?=date("Y-m-d H:i",$value->response)?></td>
                      <td class="text-gray"><?=$value->status?></td>
                      <td class="text-right text-gray">
                        <a href="{{ url('complain-manage/'.$value->id_ticket) }}" class="text-muted">[lihat]</a>
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
