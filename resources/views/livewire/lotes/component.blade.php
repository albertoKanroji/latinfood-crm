

<!--Lotes de Productos -->



   

    <div class="row sales layout-top-spacing">

        <div class="col-sm-12">
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h4 class="card-title">
                        <b>Lots of Products | Categories</b>
                    </h4>
                    <ul class="tabs tab-pills">
                       @role('Admin|Employee')
                        <li>
                            <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Add Lot</a>
                        </li>

                        @endcan
                    </ul>
                </div>

                @include('common.searchbox')



                <div class="widget-content">

                   

                    <div id="accordion">
                        @php
        $sortedCategories = $dataC->sortBy(function ($cat) {
            return (int) explode('-', $cat->name, 2)[0];
        });
    @endphp
    @foreach($sortedCategories as $cat)
                        @php
                     
                        $hasLots = false;
                        @endphp
                        <div class="card">
                            <div class="card-header" id="heading{{$cat->id}}">
                                <h3 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$cat->id}}" aria-expanded="true" aria-controls="collapse{{$cat->id}}">
                                        <h3>  {{$cat->name}}</h3>
                                    </button>
                                </h3>
                            </div>
                            <div class="contenedor">
                                <div class="izquierda">
                                    <div id="collapse{{$cat->id}}" class="collapse" aria-labelledby="heading{{$cat->id}}" data-parent="#accordion">
                                        <div class="card-body">
                                            <style type="text/css">
                                                p {
                                                    font-size: 25px;
                                                }
                                            </style>

                                        </div>
                                    </div>
                                </div>
                                <div id="collapse{{$cat->id}}" class="collapse derecha" aria-labelledby="heading{{$cat->id}}" data-parent="#accordion">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped  mt-1">
                                            <thead class="text-white" style="background: #FF5100">
                                                <tr>
                                                      <th class="table-th text-white  text-center">STATUS</th>
                                                    <th class="table-th text-white text-center ">SKU</th>
                                                    <th class="table-th text-white  text-center">Name</th>
                                                    <th class="table-th text-white text-center ">BarCode</th>
                                                    <th class="table-th text-white text-center ">Ammount</th>
                                                    <th class="table-th text-white  text-center">Expire dateo</th>
                                                    <th class="table-th text-white text-center ">Create Date</th>
                                                    <th class="table-th text-white text-center ">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                
                                                @endphp
                                                @foreach($data as $lot)
                                                @if ($lot->producto->category_id == $cat->id && $lot->estado == "Normal")
                                                @php
                                                $hasLots = true;
                                              
                                                @endphp
                                                <tr>
                                                     <td class="text-center">
                                                        <h6>{{$lot->estado}}</h6>
                                                    </td>
                                                    <td class="text-center">
                                                        <h6>{{$lot->producto->barcode}}</h6>
                                                    </td>
                                                    <td class="text-center">
                                                        <h6>{{$lot->producto->name}}</h6>
                                                    </td>
                                                    <td class="text-center">
                                                        <h6>{{$lot->CodigoBarras}}</h6>
                                                    </td>
                                                    <td class="text-center">
                                                        <h6>{{$lot->Cantidad_Articulos}}</h6>
                                                    </td>
                                                    <td class="text-center">
                                                        <h6>{{ \Carbon\Carbon::parse($lot->Fecha_Vencimiento)->format('M-d-y')}}</h6>
                                                    </td>
                                                    <td class="text-center">
                                                        <h6>{{ \Carbon\Carbon::parse($lot->created_at)->format('M-d-y')}}</h6>
                                                    </td>
                                                    <td class="text-center">
                                                        @role('Admin|Employee')
                                                        <a style="background:#f39022;" href="javascript:void(0)" onclick="Cambio('{{$lot->id}}')" class="btn btn-dark mtmobile" title="Cambio">
                                                            <i class="fas fa-upload"></i>
                                                        </a>
                                                        <a class="btn btn-dark" href="{{ url('detail/pdf' . '/' . $lot->id ) }}" title="print" target="_blank" style="background:#f39022;"> <i class="fas fa-print"></i></a>
                                                      
                                                        @endcan
                                                        @role('Admin')
                                                        <a style="background:#f39022;" href="javascript:void(0)" onclick="Confirm('{{$lot->id}}')" class="btn btn-dark mtmobile" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                        
                                                        @endcan
                                                    </td>

                                                </tr>

                                                @endif
                                                @endforeach
                                                @if (!$hasLots)
                                                <tr>
                                                    <td colspan="7" class="text-center">
                                                        <h3>No Lots Here.</h3>
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                        </div>
                     
                        @endforeach
                    </div>




<br>



                </div>


            </div>


        </div>

        @include('livewire.lotes.form')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            window.livewire.on('lote-added', msg => {
                $('#theModal').modal('hide') //agregar lote
            });

            window.livewire.on('lote-edit', msg => {
                $('#theModal').modal('hide') //editar lote
            });
            window.livewire.on('lote-delete', msg => {
                $('#theModal').modal('hide') //eliminar lote
            });

            window.livewire.on('modal-show', msg => {
                $('#theModal').modal('show')
            });
            window.livewire.on('modal-hide', msg => {
                $('#theModal').modal('hide')
            });


            window.livewire.on('hidden.bs.modal', msg => {
                $('.er').css('display', 'none')
            });

            $('#theModal').on('shown.bs.modal', function(e) {
                $('.Nombre_Lote').focus()
            })
        });


        function Confirm(id) {

            swal({
                title: '¿CONFIRM DELETE THIS REG? ',
                text: 'THIS ACTION CAN BE REVERTED',
                type: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cerrar',
                cancelButtonColor: '#fff',
                confirmButtonColor: '#3B3F5C',
                confirmButtonText: 'Aceptar'
            }).then(function(result) {
                if (result.value) {
                    window.livewire.emit('deleteRow', id)
                    swal.close()
                }

            })
        }
  function Cambio(id) {

            swal({
                title: '¿Pasar de Crudo a Pre-Cocido? ',
                text: 'THIS ACTION CAN BE REVERTED',
                type: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cerrar',
                cancelButtonColor: '#fff',
                confirmButtonColor: '#3B3F5C',
                confirmButtonText: 'Aceptar'
            }).then(function(result) {
                if (result.value) {
                   window.livewire.emit('Cambio', id)
                    swal({
        title: 'Sucess',
        text: 'Stock update.',
        type: 'success',
        confirmButtonColor: '#3B3F5C',
        confirmButtonText: 'Aceptar'
      });
                }

            })
        }
      document.addEventListener('livewire:load', function () {
        Livewire.on('tableRendered', function () {
            // Encontrar el contenedor de la tabla del subformulario dentro de la modal
            var subformTable = document.querySelector(' #ff');

            // Forzar una actualización de Livewire solo para la tabla del subformulario
            Livewire.find(subformTable.getAttribute('wire:id')).call('render');
        });
    });
    </script>

           


