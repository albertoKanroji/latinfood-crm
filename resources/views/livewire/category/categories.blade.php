
<div class="row sales layout-top-spacing">
	
	<div class="col-sm-12">
		<div class="widget widget-chart-one">
			<div class="widget-heading">
				<h4 class="card-title">
					<b>CATEGORY | LIST</b>
				</h4>
				<ul class="tabs tab-pills">		
					@can('Category_Create')	
					<li>
						<a href="javascript:void(0)" class="btn btn-primary mb-2 mr-2 btn-rounded" data-toggle="modal" data-target="#theModal" 
						>Add</a>
					</li>	
					@endcan
				</ul>
			</div>
			@can('Category_Search')	
			@include('common.searchbox')
			@endcan
			
			<div class="widget-content">		
				

				<div class="table-responsive">
					<table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #FF5100; border-radius: 10px;">
							<tr>
								<th class="table-th text-white">Description</th>
								<th class="table-th text-white text-center">Image</th>
								<th class="table-th text-white text-center">Actions</th>
							</tr>
						</thead>
						<tbody>
    @php
        $sortedCategories = $categories->sortBy(function ($category) {
            return (int) explode('-', $category->name, 2)[0];
        });
    @endphp
    @foreach($sortedCategories as $category)
        <tr>
            <td><h6>{{$category->name}}</h6></td>
            <td class="text-center">
                <span>
                    <img src="{{ asset('storage/categories/' . $category->imagen) }}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
                </span>
            </td>
            <td class="text-center">
                @can('Category_Update')
                    <a href="javascript:void(0)" wire:click="Edit({{$category->id}})" class="btn btn-warning mb-2 mr-2 btn-rounded" title="Edit">
                                       <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    </a>
                @endcan
                @if($category->products->count() < 1)
                    @can('Category_Destroy')
                        <!-- Tu código para el botón de eliminación -->
                    @endcan
                @endif
            </td>
        </tr>
    @endforeach
</tbody>

			</table>
			{{$categories->links()}}
		</div>

	</div>


</div>


</div>

@include('livewire.category.form')
</div>


<script>
	document.addEventListener('DOMContentLoaded', function(){

		window.livewire.on('show-modal', msg =>{
			$('#theModal').modal('show')
		});
		window.livewire.on('category-added', msg =>{
			$('#theModal').modal('hide')
		});
		window.livewire.on('category-updated', msg =>{
			$('#theModal').modal('hide')
		});


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