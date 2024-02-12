


<div>
	@include('common.modalHead')
	<div class="row">
	
<div class="col-sm-12 col-md-8">
	<div class="form-group">
		<label >Name</label>
		<input type="text" wire:model.lazy="nombre" 
		class="form-control product-name" placeholder="ej: Pollo" autofocus >
		@error('nombre') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>
<div class="col-sm-12 col-md-8">
	<div class="form-group">
		<label >Description (Optional)</label>
		<input type="text" wire:model.lazy="descripcion" 
		class="form-control product-name" placeholder="ej: Pollo" autofocus >
		@error('descripcion') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>

</div>

@include('common.modalFooter')
</div>
