<div class="modal fade" id="confirmmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Sytem Confirmation</h4>
      </div>
      <div class="modal-body text-center">
        <p>
            Are you sure to do this?
        </p>
        <div>
          <?php echo Form::open(['url' => url(''), 'method' => 'post', 'id' => 'confirmaction','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>

            <input type="hidden" id="primaryKey" name="id">
            <input type="hidden" id="parent_id" name="parent_id">
            <button type="submit" id="confirmbtn" class="btn btn-danger">Yes</button>
            <button type="button" id="noconfirm" class="btn btn-white">No, Cancel</button>
          <?php echo Form::close(); ?>

        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(".confirm").click(function(){
    var id          = $(this).attr("data-id");
    var parent_id   = $(this).attr("parent-id");
    var url         = $(this).attr("data-url");

    $("#confirmaction").prop("action",url);
    $("#primaryKey").val(id);
    $("#parent_id").val(parent_id);
    $("#confirmmodal").modal("show");
  });

  $("#noconfirm").click(function(){
    $("#confirmmodal").modal("hide");
  });
</script>
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/do_confirm.blade.php ENDPATH**/ ?>