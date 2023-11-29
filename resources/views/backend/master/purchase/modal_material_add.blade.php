<div class="modal fade" id="purchasecart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-lg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Keranjang Belanja</h4>
      </div>
      <div class="modal-body" id="pendingchart">


      </div>
    </div>
  </div>
</div>
<script>
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $(".purchase").click(function(){
    var id_item = $(this).attr("data-id");
    var unit    = $(this).attr("data-unit");
    console.log(id_item);
    $("#purchasecart").modal("show");
    loadPendingCart(id_item,unit);
  });

  function loadPendingCart(id_item,unit){
    $("#pendingchart").html("memuat...");
    $.ajax({
      url:"{{ url('get-list-pending-purchase') }}",
      type:"GET",
    })
    .done(function(result){
      $("#pendingchart").html("");
      if(result.status=="success"){
        var html = "<table class=\"table table-striped table-bordered table-hover dataTables-example\"><tbody>";
        html = html+"<tr>";
        html = html+"<th>Keterangan keranjang</th>";
        html = html+"<th>Terakhir diperbarui</th>";
        html = html+"<th>Total item</th>";
        html = html+"<th>Aksi</th>";
        html = html+"</tr>";
        html = html+"</tbody></table>";
        $("#pendingchart").append(html);

        for(var i in result.data){
          var html = "";
          var button = "<button type='button' class='btn btn-default btn-xs' onclick='prepareAdd("+result.data[i].id_purchase+","+id_item+",\""+unit+"\")'> masukkan ke sini <i class='fa fa-angle-right'></i></button>";
          html = html+"<tr>";
          html = html+"<td class='text-left'><i class='fa fa-angle-right'></i> "+result.data[i].title+"</td>";
          html = html+"<td class='text-left'>"+result.data[i].last_update+"</td>";
          html = html+"<td class='text-left'>"+((result.data[i].total_item==null)?0:result.data[i].total_item)+" item</td>";
          html = html+"<td class='text-left'>"+button+"</td>";
          html = html+"</tr>";
          $("#pendingchart table tbody").append(html);
        }
      }else if(result.status=="error"){
        //tidak ada cart ready
        var html = "<div class='form-group row'>";
            /*
            html = html+"<div class='col-sm-12'><div class='alert alert-info'><h3>Keranjang tidak ditemukan!</h3>Tidak ada keranjang sebelumnya. Mohon mengisi formulir berikut untuk membuat keranjang belanja.</div></div>";
            html = html+"<label class='col-sm-12'>Keterangan Pembelian</label>";
            html = html+"<div class='col-sm-12'>";
            html = html+"<textarea rows='2' class='form-control' id='purchase_title'></textarea>";
            html = html+"</div>";

            html = html+"<div class='col-sm-12 top15'>";
            html = html+"<button type='button' class='btn btn-primary' onclick='createPurchase("+id_item+",\""+unit+"\")'>Buat Pembelian</button>";
            html = html+"</div>";
            html = html+"</div>";
            */
            
            html = html+"<div class='col-sm-12'><div class='alert alert-info'><h3>Keranjang tidak ditemukan!</h3>Tidak ada keranjang sebelumnya. Mohon membuat keranjang belanja terlebih dahulu.</div></div>";
            html = html+"<div class='col-sm-12'>";
            html = html+"<a href='<?=url('master/purchase/create')?>' class='btn btn-primary' >Buat keranjang belanja</a>";
            html = html+"</div>";
            html = html+"</div>";

        $("#pendingchart").html(html);
        //tidak ada cart ready
      }else if(result.status=="error_forbidden"){
        $("#pendingchart").html("Fitur ini tidak diperbolehkan untuk akun anda.");
      }else if(result.status=="error_login"){
        $("#pendingchart").html("Mohon login terlebih dahulu!");
      }
    })
    .fail(function(msg){
      $("#pendingchart").html("gagal memuat..");
    });
  }

  function prepareAdd(id_purchase, id_item, unit){
    var html = "<div class='form-group row'>";
        html = html+"<label class='col-sm-12'>Banyaknya</label>";
        html = html+"<div class='col-sm-12'>";
        html = html+"<div class='input-group'>";
        html = html+"<input type='number' id='qty' value='1' class='form-control'>";
        html = html+"<span class='input-group-addon'>"+unit+"</span>";
        html = html+"</div>";
        html = html+"</div>";

        html = html+"<div class='col-sm-12 top5'>";
        html = html+"<button type='button' class='btn btn-primary' onclick='additemtocart("+id_purchase+","+id_item+",\""+unit+"\")'>Tambahkan</button>";
        html = html+"</div>";
        html = html+"</div>";

    $("#pendingchart").html(html);
  }

  function createPurchase(id_item, unit){
    $.ajax({
      url:"{{ url('master/purchase/createacart') }}",
      type:"POST",
      data:{
        purchase_title: $("#purchase_title").val(),
        _token: $('meta[name="csrf-token"]').attr('content')
      }
    })
    .done(function(result){
      if(result.status=="success"){
        $("#pendingchart").html("<p class='alert alert-success'>Keranjang berhasil dibuat</p>");
        setTimeout(function(){
          loadPendingCart(id_item, unit);
        },2000);
      }else{
        $("#pendingchart").html("<p class='alert alert-danger'>Keranjang gagal dibuat</p>");
      }
    })
    .fail(function(msg){
      $("#pendingchart").html(msg);
      //$("#pendingchart").html("gagal memuat..");
    });
  }

  function additemtocart(id_purchase, id_item, unit){
    $.ajax({
      url:"{{ url('master/purchase/additem') }}",
      type:"POST",
      data:{
        id_purchase: id_purchase,
        id_item: id_item,
        qty: $("#qty").val(),
        _token: $('meta[name="csrf-token"]').attr('content')
      }
    })
    .done(function(result){
      if(result.status=="success"){
        $("#pendingchart").html("<p class='alert alert-success'>Item berhasil ditambahkan pada pembelian.</p>");
        document.location = result.url;
      }else if(result.status=="error"){
        $("#pendingchart").html("<p class='alert alert-danger'>Tidak dapat menambah item pada pembelian</p>");
      }else if(result.status=="error_forbidden"){
        $("#pendingchart").html("<p class='alert alert-danger'>Fitur ini tidak diperbolehkan untuk akun anda.</p>");
      }else if(result.status=="error_login"){
        $("#pendingchart").html("<p class='alert alert-danger'>Mohon login terlebih dahulu!</p>");
      }
    })
    .fail(function(msg){
      $("#pendingchart").html(msg);
      //$("#pendingchart").html("gagal memuat..");
    });
  }
</script>
