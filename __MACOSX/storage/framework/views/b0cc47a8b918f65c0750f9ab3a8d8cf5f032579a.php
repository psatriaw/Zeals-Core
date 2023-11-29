<?php if(Session::has('info')): ?>
<div class="alert <?php echo e(Session::get('info.alert-class')); ?>" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
	<?=Session::get('info.message')?>
</div><!-- alert -->
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/flash_message.blade.php ENDPATH**/ ?>