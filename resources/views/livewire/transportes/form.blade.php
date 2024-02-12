
<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->


<div>

	@include('common.modalHead')
	<div class="row">
	
<div class="col-sm-12 col-md-5">
	<div class="form-group">
		<label >Name</label>
		<input type="text"  wire:model="nombre" 
		class="form-control" placeholder="ej: Nombre" autofocus >
		@error('nombre') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>
<div class="col-sm-12 col-md-5">
	<div class="form-group">
		<label >Apellido Paterno</label>
		<input type="text"   wire:model="apellido" 
		class="form-control" placeholder="ej: Apellido 1" autofocus >
		@error('apellido') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>

<div class="col-sm-12 col-md-5">
	<div class="form-group">
		<label >Edad</label>
		<input type="text"   wire:model="edad" 
		class="form-control" placeholder="ej: Edad" autofocus >
		@error('edad') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>

<div class="col-sm-12 col-md-3">
<div class="form-group">
	<label>De Planta?</label>
	<select wire:model='de_Planta'  class="form-control">
		<option value="Elegir" disabled>Elegir</option>
		
		<option value="SI"> SI</option>
		<option value="NO"> NO</option>
	
		
	</select>
	@error('de_Planta') <span class="text-danger er">{{ $message}}</span>@enderror
</div>
</div>

<div class="col-sm-12 col-md-5">
	<div class="form-group">
		
		<label >Compañia</label>
		<input type="text"   wire:model="compañia" @if ($de_Planta=='SI') disabled @endif
		class="form-control" placeholder="ej: Nombre" autofocus >
		@error('compañia') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>



<script type="text/javascript">
	


</script>



</div>

@include('common.modalFooter')

</div>


