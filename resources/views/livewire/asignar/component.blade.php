<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName}}</b>
                </h4>
                
            </div>
            

            <div class="widget-content">
                <div class="form-inline">
                    <div class="form-group mr-5">
                        <select wire:model ="role" class="form-control">
                            <option value="Elegir" selected>== Select Role ==</option>
                            @foreach($roles as $role)
                            <option value="{{$role->id}}" >{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button wire:click.prevent="SyncAll()" type="button" class="btn btn-dark mbmobile inblock mr-5">Sync All</button>

                    <button onclick="Revocar()" type="button" class="btn btn-dark mbmobile mr-5">Revoke all</button>
                </div>



                
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table striped mt-1">
                                <thead class="text-white" style="background: #3B3F5C">
                                    <tr>
                                        <th class="table-th text-white text-center">ID</th>
                                        <th class="table-th text-white text-center">Permission</th>
                                        <th class="table-th text-white text-center">Roles With Permission</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permisos as $permiso)
                                    <tr>
                                        <td><h6 class="text-center">{{$permiso->id}}</h6></td>
                                        <td class="text-center">
                                            <div class="n-check">
                                                <label class="new-control new-checkbox checkbox-primary">
                                                    <input type="checkbox"
                                                    wire:change="syncPermiso($('#p' + {{ $permiso->id }}).is(':checked'), '{{ $permiso->name}}' )"
                                                    id="p{{ $permiso->id }}"
                                                    value="{{ $permiso->id }}" 
                                                    class="new-control-input"
                                                    {{ $permiso->checked == 1 ? 'checked' : '' }}
                                                    >
                                                    <span class="new-control-indicator"></span>
                                                    <h6>{{ $permiso->name}}</h6>
                                                </label>
                                            </div> 
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ \App\Models\User::permission($permiso->name)->count() }}</h6>
                                            
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$permisos->links()}}
                        </div>
                    </div>
                    
                </div>

            </div>


        </div>


    </div>
   
</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){
        
        window.livewire.on('sync-error', Msg => {
            noty(Msg)
        })
        window.livewire.on('permi', Msg => {
            noty(Msg)
        })
        window.livewire.on('syncall', Msg => {
            noty(Msg)
        })
        window.livewire.on('removeall', Msg => {
            noty(Msg)
        })


    });


    function Revocar()
    {   

        swal({
            title: 'CONFIRMAR',
            text: '¿CONFIRMAS REVOCAR TODOS LOS PERMISOS?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            if(result.value){
                window.livewire.emit('revokeall')
                swal.close()
            }

        })
    }

</script>