<div>
    <div class="row sales layout-top-spacing">

        <div class="col-sm-12">
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h4 class="card-title">
                        <b>Lots de insumo | Sabores</b>
                    </h4>
                    <ul class="tabs tab-pills">
                        @role('Admin|Employee')
                        <li>
                            <button wire:click.prevent="LoteInsumo()" class="btn btn-warning mb-2 mr-2 btn-rounded">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                            </button>
                        </li>
                        @endcan
                    </ul>
                </div>

                @include('common.searchbox')



                <div class="widget-content">
                    <div id="accordion">
                        @foreach($sabor as $cat)
                        @php
                        $hasLots = false;
                        $lotCount = $cat->insumos->count(); // Contar los lotes del sabor actual
                        @endphp
                        <div class="card">
                            <div class="card-header" id="heading{{$cat->id}}">
                                <h3 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse"
                                        data-target="#collapse{{$cat->id}}" aria-expanded="true"
                                        aria-controls="collapse{{$cat->id}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h3 style="margin-right: 10px;">{{$cat->nombre}}</h3>
                                            <span class="badge badge-secondary">{{$lotCount}}</span>
                                        </div>
                                        <!-- Contador de lotes -->
                                    </button>
                                </h3>
                            </div>
                            <div class="contenedor">
                                <div class="izquierda">
                                    <div id="collapse{{$cat->id}}" class="collapse"
                                        aria-labelledby="heading{{$cat->id}}" data-parent="#accordion">
                                        <div class="card-body">
                                            <style type="text/css">
                                            p {
                                                font-size: 25px;
                                            }
                                            </style>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapse{{$cat->id}}" class="collapse derecha"
                                    aria-labelledby="heading{{$cat->id}}" data-parent="#accordion">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mt-1">
                                            <thead class="text-white" style="background: #FF5100">
                                                <tr>
                                                    <th class="table-th text-white text-center">BarCode</th>
                                                    <th class="table-th text-white text-center">Amount</th>
                                                    <th class="table-th text-white text-center">Expiration Date</th>
                                                    <th class="table-th text-white text-center">Create Date</th>
                                                    <!-- <th class="table-th text-white text-center">Actions</th>-->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $insumos = $cat->insumos; 
                                                @endphp
                                                @foreach($insumos as $lot)
                                                @php
                                                $hasLots = true;
                                                @endphp
                                                <tr>
                                                    <td class="text-center" id="textarea-copy">
                                                        <h6>{{$lot->CodigoBarras}}</h6>
                                                        <a class="btn btn-outline-primary btn-rounded mb-2"
                                                            href="javascript:;" data-clipboard-action="copy"
                                                            data-clipboard-target="#textarea-copy">Copy Barcode in
                                                            clipboard</a>
                                                    </td>

                                                    <!-- Incluye la biblioteca clipboard.js -->
                                                    <script
                                                        src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js">
                                                    </script>

                                                    <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        // Inicializa el objeto ClipboardJS
                                                        new ClipboardJS('[data-clipboard-action="copy"]');
                                                    });
                                                    </script>

                                                    <td class="text-center">
                                                        <h6>{{$lot->Cantidad_Articulos}}</h6>
                                                    </td>
                                                    <td class="text-center">
                                                        <h6>{{ \Carbon\Carbon::parse($lot->Fecha_Vencimiento)->format('M-d-y')}}
                                                        </h6>
                                                    </td>
                                                    <td class="text-center">
                                                        <h6>{{ \Carbon\Carbon::parse($lot->created_at)->format('M-d-y')}}
                                                        </h6>
                                                    </td>
                                                    <!--     <td class="text-center">
                                        @role('Admin|Employee')
                                        <a style="background:#f39022;" href="javascript:void(0)" onclick="Cambio('{{$lot->id}}')" class="btn btn-dark mtmobile" title="Cambio">
                                            <i class="fas fa-upload"></i>
                                        </a>
                                        <a class="btn btn-dark" href="{{ url('detail/pdf' . '/' . $lot->id ) }}" title="Print" target="_blank" style="background:#f39022;">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @endcan
                                        @role('Admin')
                                        <a style="background:#f39022;" href="javascript:void(0)" onclick="Confirm('{{$lot->id}}')" class="btn btn-dark mtmobile" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        @endcan
                                    </td>-->
                                                </tr>
                                                @endforeach
                                                @if (!$hasLots)
                                                <tr>
                                                    <td colspan="5" class="text-center">
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
                </div>




            </div>


        </div>

        @include('livewire.insumos.form')
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('show-modal', msg => {
            $('#modalINSUMO').modal('show');
        });
        window.livewire.on('lote-added', msg => {
            $('#modalINSUMO').modal('hide') //agregar lote
        });

        window.livewire.on('lote-edit', msg => {
            $('#modalINSUMO').modal('hide') //editar lote
        });
        window.livewire.on('lote-delete', msg => {
            $('#modalINSUMO').modal('hide') //eliminar lote
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
    </script>


</div>