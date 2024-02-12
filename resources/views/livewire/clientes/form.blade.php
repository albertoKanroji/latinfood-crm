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
		<input type="text" wire:model.lazy="name" 
		class="form-control" placeholder="Ex: Kenny"  >
		@error('name') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>


<div class="col-sm-12 col-md-5">
	<div class="form-group">
		<label >Last Name</label>
		<input type="text" wire:model.lazy="last_name" 
		class="form-control" placeholder="Ex: Gutierrez"  >
		@error('last_name') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>



<div class="col-sm-12 col-md-4">
	<div class="form-group">
		<label >Phone</label>
		<input type="text" wire:model.lazy="phone" 
		class="form-control" placeholder="Ex: 786 554 9831" maxlength="10" >
		@error('phone') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>


<div class="col-sm-12 col-md-2">
	<div class="form-group">
		<label >Wallet</label>
		<input type="text" wire:model.lazy="saldo" 
		class="form-control" placeholder="$100" maxlength="10" >
		@error('saldo') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>


<div class="col-sm-12 col-md-6">
	<div class="form-group">
		<label >Address</label>
		<input type="text" wire:model.lazy="address" 
		class="form-control"  placeholder="Address"  >
		@error('address') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>


<div class="col-sm-12 col-md-5">
	<div class="form-group">
		<label >Email</label>
		<input type="email" wire:model.lazy="email" 
		class="form-control" placeholder="Ex: juan@latinfood.com"  >
		@error('email') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>


<div class="col-sm-12 col-md-4">
	<div class="form-group">
		<label >Password</label>
		<input type="text" wire:model.lazy="password" 
		class="form-control"   >
		@error('password') <span class="text-danger er">{{ $message}}</span>@enderror
	</div>
</div>

<div class="col-sm-12 col-md-8">
<div class="form-group custom-file">
	<input type="file" class="custom-file-input form-control" wire:model="image"
	accept="image/x-png, image/gif, image/jpeg"  
	 >
	 <label class="custom-file-label">Imagen </label>
	 @error('image') <span class="text-danger er">{{ $message}}</span>@enderror
</div>
</div>


</div>



@include('common.modalFooter')

</div>