<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName}} | List</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="btn btn-primary mb-2 mr-2 btn-rounded" data-toggle="modal" data-target="#theModal">Add New Role</a>
                    </li>
                </ul>
            </div>
            @include('common.searchbox')

            <div class="widget-content">

                <div class="table-responsive">
                    <table class="table table-bordered table striped mt-1">
                        <thead class="text-white"  style="background: #FF5100;">
                            <tr>
                                <th class="table-th text-white">ID</th>
                                <th class="table-th text-white text-center">Description</th>
                                <th class="table-th text-white text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td><h6>{{$role->id}}</h6></td>
                                <td class="text-center">
                                 <h6>{{$role->name}}</h6>
                             </td>

                             <td class="text-center">
                                <a href="javascript:void(0)" 
                                wire:click="Edit({{$role->id}})"
                                class="btn btn-warning mb-2 mr-2 btn-rounded" title="Edit">
                                       <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                            </a>

                            <a href="javascript:void(0)" 
                            onclick="Confirm('{{$role->id}}')" 
                            class="btn btn-danger mb-2 mr-2 btn-rounded" title="Delete">
                                      <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </a>


                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$roles->links()}}
    </div>

</div>


</div>


</div>

@include('livewire.roles.form')
</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){
          


        window.livewire.on('role-added', Msg => {
            $('#theModal').modal('hide')
            noty(Msg)
        })
        window.livewire.on('role-updated', Msg => {
            $('#theModal').modal('hide')
            noty(Msg)
        })
        window.livewire.on('role-deleted', Msg => {           
            noty(Msg)
        })
        window.livewire.on('role-exists', Msg => {            
            noty(Msg)
        })
        window.livewire.on('role-error', Msg => {            
            noty(Msg)
        })
        window.livewire.on('hide-modal', Msg => {
            $('#theModal').modal('hide')            
        })
        window.livewire.on('show-modal', Msg => {
            $('#theModal').modal('show')
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
                window.livewire.emit('destroy', id)
                swal.close()
            }

        })
    }
    
    

</script>