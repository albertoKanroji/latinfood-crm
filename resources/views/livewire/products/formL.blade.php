@include('common.modalHead')
<div wire:ignore.self class="modal fade" id="crudosModal" tabindex="-1" role="dialog" style="backdrop-filter: blur(10px);">

<div class="row">
	
<div class="col-sm-12 col-md-8">
	<div class="form-group">
		<label >Name</label>
		<input type="text" wire:model.lazy="name" 
		class="form-control product-name" placeholder="ej: Empanada" autofocus >
		@error('name') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>

<div class="col-sm-12 col-md-8">
	<div class="form-group">
		<label >Descripcion</label>
		<input type="text" wire:model.lazy="descripcion" 
		class="form-control " placeholder="ej: descripcion" autofocus >
		@error('descripcion') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>

<div class="col-sm-12 col-md-3">
<div class="form-group">
	<label>Estado</label>
	<select wire:model='estado'  class="form-control">
		<option value="Elegir" disabled>Elegir</option>
		
		
		<option value="CRUDO">CRUDO</option>
	
		
	</select>
	@error('estado') <span class="text-danger er">{{ $message}}</span>@enderror
</div>
</div>
<div class="col-sm-12 col-md-4">
	<div class="form-group">
		<label >Code</label>
		@if($selected_id>0)
		<input type="text" wire:model.lazy="barcode" class="form-control" >
		@else		
		
			<input type="text" wire:model.lazy="barcode" name="barcode" class="form-control"  placeholder="SKU"  >
			
		@endif
		@error('barcode') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>



<div class="col-sm-12 col-md-4">
	<div class="form-group">
		<label >Costo</label>
		<input type="text" data-type='currency' wire:model.lazy="cost" class="form-control" placeholder="ej: 0.00" >
		@error('cost') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>



<div class="col-sm-12 col-md-4">
	<div class="form-group">
		<label >Price</label>
		<input type="text" data-type='currency' wire:model.lazy="price" class="form-control" placeholder="ej: 0.00" >
		@error('price') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>

<div class="col-sm-12 col-md-4">
	<div class="form-group">
		<label >Stock</label>
		<input type="number"  wire:model.lazy="stock" class="form-control" placeholder="ej: 0" >
		@error('stock') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>

<div class="col-sm-12 col-md-4">
	<div class="form-group">
		<label >Alert</label>
		<input type="number"  wire:model.lazy="alerts" class="form-control" placeholder="ej: 10" >
		@error('alerts') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>


<div class="col-sm-12 col-md-4">
<div class="form-group">
	<label>Category</label>
	<select wire:model='categoryid' class="form-control">
		<option value="Elegir" disabled>Elegir</option>
		@foreach($categories as $category)
		<option value="{{$category->id}}" >{{$category->name}}</option>
		@endforeach
	</select>
	@error('categoryid') <span class="text-danger er">{{ $message}}</span>@enderror
</div>
</div>



<div class="col-sm-12 col-md-8">
<div class="form-group custom-file">
	<input type="file" class="custom-file-input form-control" wire:model="image"
	accept="image/x-png, image/gif, image/jpeg"  
	 >
	 <label class="custom-file-label">Imágen {{$image}}</label>
	 @error('image') <span class="text-danger er">{{ $message}}</span>@enderror
</div>
</div>



</div>

</div>


@include('common.modalFooter')

