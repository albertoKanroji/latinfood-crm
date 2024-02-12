<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>Users | List</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="btn btn-primary mb-2 mr-2 btn-rounded" data-toggle="modal" data-target="#theModal">Add New User</a>
                    </li>
                </ul>
            </div>
            @include('common.searchbox')

            <div class="widget-content">

                <div class="table-responsive">
                    <table class="table table-bordered table striped mt-1">
                        <thead class="text-white"  style="background: #FF5100;">
                            <tr>
                                <th class="table-th text-white">USER</th>
                                <th class="table-th text-white text-center">Phone</th>
                                <th class="table-th text-white text-center">EMAIL</th>
                                <th class="table-th text-white text-center">Status</th>
                                <th class="table-th text-white text-center">Profile</th>
                                <th class="table-th text-white text-center">Image</th>
                                <th class="table-th text-white text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $r)
                            <tr>
                                <td><h6>{{$r->name}}</h6></td>
                                
                                <td class="text-center"><h6>{{$r->phone}}</h6></td>
                                <td class="text-center"><h6>{{$r->email}}</h6></td>
                                <td class="text-center">
                                    <span class="badge {{ $r->status == 'Active' ? 'badge-success' : 'badge-danger' }} text-uppercase">{{$r->status}}</span>
                                </td>
                                <td class="text-center text-uppercase">
                                    <h6>{{$r->profile}}</h6>
                                    <small><b>Roles:</b>{{implode(',',$r->getRoleNames()->toArray())}}</small>
                                </td>

                                <td class="text-center">
                                 @if($r->image != null) 
                                 <img class="card-img-top img-fluid"                                             
                                 src="{{ asset('storage/users/'.$r->image) }}" 
                                 style="width:180px; height: 180px;" 
                                 > 
                                 @endif                                  
                             </td>

                             <td class="text-center">
                                <a href="javascript:void(0)" 
                                wire:click="edit({{$r->id}})"
                                 class="btn btn-warning mb-2 mr-2 btn-rounded" title="Edit">
                                       <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                            </a>
                            @if(Auth()->user()->id != $r->id)
                            <a href="javascript:void(0)" 
                            onclick="Confirm('{{$r->id}}')" 
                           class="btn btn-danger mb-2 mr-2 btn-rounded" title="Delete">
                                      <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </a>
                        @endif


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

@include('livewire.users.form')
</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){
        window.livewire.on('user-added', Msg => {
            $('#theModal').modal('hide')
            noty(Msg)
        })
        window.livewire.on('user-updated', Msg => {
            $('#theModal').modal('hide')
            noty(Msg)
        })
        window.livewire.on('user-deleted', Msg => {           
            noty(Msg)
        })
        window.livewire.on('hide-modal', Msg => {           
            $('#theModal').modal('hide')
        })
        window.livewire.on('show-modal', Msg => {           
            $('#theModal').modal('show')
        })
        window.livewire.on('user-withsales', Msg => {           
            noty(Msg)
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