<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<div class="row mt-3">
	<div class="col-sm-12">

		<div class="connect-sorting">

			<h5 class="text-center mb-2">DENOMINATIONS</h5>


			<div class="col-sm-12 col-md-8">
				<div class="form-group">
					<label>Customer</label>
					<input type="text" wire:model="buscar" class="form-control" placeholder="Buscar cliente...">
					<select wire:model="cliente" id="cliente" name="cliente" class="form-control">
						<option value="Elegir" disabled>Elegir</option>
						@foreach($data3 as $cliente)
						<option value="{{$cliente->id}}">{{$cliente->name}}-{{$cliente->last_name}}- ${{$cliente->saldo}} USD</option>
						@endforeach
					</select>
					@error('cliente') <span class="text-danger er">{{ $message}}</span>@enderror
				</div>
			</div>
			<style type="text/css">
				select[wire:model="cliente"] option {
					display: none;
				}
			</style>


			<div class="container">
				<div class="row">
					@foreach($denominations as $d)
					<div class="col-sm mt-2">

						<button wire:click.prevent="ACash({{$d->value}})" class="btn btn-dark btn-block den">
							{{ $d->value >0 ? '$' . number_format($d->value,2, '.', '') : 'Exact' }}
						</button>
					</div>
					@endforeach
				</div>
			</div>
			<div class="col-sm mt-2">
				<button wire:click.prevent="payWithCredit" class="btn btn-dark btn-block den">
					Pay With credit
				</button>
			</div>
			<div class="connect-sorting-content mt-4">
				<div class="card simple-title-task ui-sortable-handle">
					<div class="card-body">
						<div class="input-group input-group-md mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text input-gp hideonsm" style="background: #f39022; color:white">Cash F8
								</span>
							</div>
							<input type="number" id="cash" wire:model="efectivo" wire:keydown.enter="saveSale" class="form-control text-center" value="{{$efectivo}}">
							<div class="input-group-append">
								<span wire:click="$set('efectivo', 0)" class="input-group-text" style="background: #3B3F5C; color:white">
									<i class="fas fa-backspace fa-2x"></i>
								</span>
							</div>
						</div>

						<h4 class="text-muted">Change: ${{number_format($change,2)}}</h4>

						<div class="row justify-content-between mt-5">
							<div class="col-sm-12 col-md-12 col-lg-6">
								@if($total > 0)
								<button onclick="Confirm('','clearCart','SURE TO DELETE CART?')" class="btn btn-dark mtmobile">
									CANCEL F4
								</button>
								@endif
							</div>

							<div class="col-sm-12 col-md-12 col-lg-6">
								@if($efectivo>= $total && $total > 0)
								<button wire:click.prevent="saveSale" class="btn btn-dark btn-md btn-block" style="  backgound: #FF5100;">SAVE F6</button>
								@endif
							</div>


						</div>




					</div>
					<div class="col-sm-12 mt-1 text-center">
						<p class="text-muted" style="  color: #FF5100;">Print Last F7</p>
					</div>
				</div>
			</div>

		</div>

	</div>
</div>