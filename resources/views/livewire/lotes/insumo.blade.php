<div wire:ignore.self class="modal fade" id="modalINSUMO" tabindex="-1" role="dialog" style="backdrop-filter: blur(10px);">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white">
          <b>CREAR LOTE DE INSUMO</b>
        </h5>
        <h6 class="text-center text-warning" wire:loading>PLEASE WAIT</h6>
      </div>
      <div class="modal-body">


   <div class="col-sm-9 col-md-5">
    <div class="form-group">
        <label>Sabor</label>
        <input type="text" wire:model="search" class="form-control" placeholder="Buscar producto...">
        <select wire:model="SKU" wire:model="Nombre_Lote" id="sku" name="sku" class="form-control">
            <option value="Elegir" disabled>Elegir</option>
            @foreach($sabor as $data)
               
                    <option value="{{$data->id}}">{{$data->nombre}}-{{$data->stock}}</option>
               
            @endforeach
        </select>
        @error('nombre') <span class="text-danger er">{{ $message}}</span>@enderror
    </div>
</div>
<div class="col-sm-12 col-md-4">
	<div class="form-group">
		<label >Stock</label>
		<input type="number"  wire:model.lazy="stock" class="form-control" placeholder="ej: 0" >
		@error('stock') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>
	<div class="col-sm-12 col-md-5">
			<div class="form-group">
				<label>CodigoBarras</label>


				<input type="text" wire:model.lazy="CodigoBarras" name="CodigoBarras" class="form-control" placeholder="Generating CodeBar.." disabled wire:loading.attr="disabled" id="CodigoBarras" x-data="{barcodePlaceholder: 'Generating CodeBar..'}" x-init="Livewire.on('CodigoBarras-generated',CodigoBarras=>{
				document.querySelector('#CodigoBarras').placeholder=CodigoBarras;
				barcodePlaceholder=CodigoBarras;
			});" x-bind:placeholder="barcodePlaceholder">


				@error('CodigoBarras') <span class="text-danger er">{{ $message}}</span>@enderror
			</div>
		</div>
			<div class="col-sm-12 col-md-6">
			<div class="form-group">

				@php
				$fecha= now()->addMonth()->toDateString() ;
				@endphp

				<label>Fecha Vencimiento</label>
				<input type="date" wire:model.lazy="Fecha_Vencimiento" class="form-control" value="{{ $fecha }}" readonly>
				@error('Fecha_Vencimiento') <span class="text-danger er">{{ $message}}</span>@enderror
			</div>
		</div>
	<div class="col-sm-12 col-md-4">
			<div class="form-group">
				<label>User</label>
				<input type="text" wire:model.lazy="User" class="form-control" placeholder="{{$user}}" value="{{$user}}" disabled wire:loading.attr="disabled" id="User" x-data="{barcodePlaceholder: '{{$user}}'}" x-init="Livewire.on('CodigoBarras-generated',User=>{
				document.querySelector('#User').placeholder=User;
				barcodePlaceholder=User;
			});" x-bind:placeholder="barcodePlaceholder">



				@error('User') <span class="text-danger er">{{ $message}}</span>@enderror
			</div>
		</div>

      </div>
      <div class="modal-footer">

    



       
       <button   type="button" class="btn btn-dark close-btn text-info" data-dismiss="modal">
  <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
    <line x1="9" y1="9" x2="15" y2="15"></line>
    <line x1="15" y1="9" x2="9" y2="15"></line>
  </svg>
  Close
</button>

   

      </div>
    </div>
  </div>
</div>
