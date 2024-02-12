
<div class="row sales layout-top-spacing">
	
	<div class="col-sm-12">
		<div class="widget widget-chart-one">
			<div class="widget-heading">
				<h4 class="card-title">
					<b>Denominations | List</b>
				</h4>
				<ul class="tabs tab-pills">
					<li>
						<a href="javascript:void(0)"class="btn btn-primary mb-2 mr-2 btn-rounded" data-toggle="modal" data-target="#theModal">Add New Coin</a>
					</li>
				</ul>
			</div>
			@include('common.searchbox')

			<div class="widget-content">
				
				<div class="table-responsive">
					<table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #FF5100">
							<tr>
								<th class="table-th text-white">Type</th>
								<th class="table-th text-white text-center">Value</th>
								<th class="table-th text-white text-center">Image</th>
								<th class="table-th text-white text-center">ACTIONS</th>
							</tr>
						</thead>
						<tbody>
							@foreach($data as $coin)
							<tr>
								<td><h6>{{$coin->type}}</h6></td>
								<td ><h6 class="text-center">${{number_format($coin->value,2)}}</h6></td>								
								<td class="text-center">
									<span>
										<img src="{{ asset('storage/' . $coin->imagen) }}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
									</span>
								</td>

								<td class="text-center">
									<a href="javascript:void(0)" 
									wire:click="Edit({{$coin->id}})"
									 class="btn btn-warning mb-2 mr-2 btn-rounded" title="Edit">
                                       <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
									</a>

									
									<a href="javascript:void(0)"
									onclick="Confirm('{{$coin->id}}')" 
									class="btn btn-danger mb-2 mr-2 btn-rounded" title="Delete">
                                      <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
									</a>
									
									
							

								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					{{$data->links()}}
				</div>

			</div>


		</div>


	</div>

	@include('livewire.denominations.form')
</div>


<script>
	document.addEventListener('DOMContentLoaded', function(){

		window.livewire.on('item-added', msg =>{
			$('#theModal').modal('hide')
		});
		window.livewire.on('item-updated', msg =>{
			$('#theModal').modal('hide')
		});
		window.livewire.on('item-deleted', msg =>{
			// noty
		});
		window.livewire.on('show-modal', msg =>{
			$('#theModal').modal('show')
		});
		window.livewire.on('modal-hide', msg =>{
			$('#theModal').modal('hide')
		});
		$('#theModal').on('hidden.bs.modal', function (e) {			
			$('.er').css('display','none')			
		})


	});
	function Confirm(id)
	{	

		swal({
			title: 'CONFIRMAR',
			text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
			type: 'warning',
			showCancelButton: true,
			cancelButtonText: 'Cerrar',
			cancelButtonColor: '#fff',
			confirmButtonColor: '#3B3F5C',
			confirmButtonText: 'Aceptar'
		}).then(function(result) {
			if(result.value){
				window.livewire.emit('deleteRow', id)
				swal.close()
			}

		})
	}
</script>