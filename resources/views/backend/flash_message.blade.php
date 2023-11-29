@if(Session::has('info'))
<div class="alert {{ Session::get('info.alert-class')}}" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
	<?=Session::get('info.message')?>
</div><!-- alert -->
@endif
