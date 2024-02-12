<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>Lots | LIST</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="btn btn-primary mb-2 mr-2 btn-rounded" data-toggle="modal"
                            data-target="#theModal">Add</a>
                    </li>

                </ul>
            </div>
            @include('common.searchbox')
            <div class="widget-content">
                <div id="accordion" class="accordion">
                    @foreach($sabor as $sabores)
                    <div class="card">
                        <div class="card-header" id="heading{{ $sabores->id }}">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse"
                                    data-target="#collapse{{ $sabores->id }}" aria-expanded="true"
                                    aria-controls="collapse{{ $sabores->id }}">
                                    <h3>{{ $sabores->nombre }}</h3>
                                </button>
                            </h5>
                        </div>

                        <div id="collapse{{ $sabores->id }}" class="collapse"
                            aria-labelledby="heading{{ $sabores->id }}" data-parent="#accordion">
                            <div class="accordion-body">
                                @php
                                $lotes = $lotesAsociados[$sabores->id] ?? [];
                                @endphp
                                @if(count($lotes) > 0)
                                <div id="accordion-lotes-{{ $sabores->id }}">
                                    @foreach($lotes as $codigoBarras => $productos)
                                    <div class="card">
                                        <div class="card-header"
                                            id="heading-lote-{{ $sabores->id }}-{{ $codigoBarras }}">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" data-toggle="collapse"
                                                    data-target="#collapse-lote-{{ $sabores->id }}-{{ $codigoBarras }}"
                                                    aria-expanded="true"
                                                    aria-controls="collapse-lote-{{ $sabores->id }}-{{ $codigoBarras }}">
                                                    <h5> Lote {{ $loop->iteration }} - Código de Barras:
                                                        {{ $codigoBarras }}
                                                        @if(count($productos) > 1)
                                                        (Lote con {{ count($productos) }} productos)
                                                        @elseif(count($productos) == 1)
                                                        (Producto único)
                                                        @endif
                                                    </h5>

                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapse-lote-{{ $sabores->id }}-{{ $codigoBarras }}" class="collapse"
                                            aria-labelledby="heading-lote-{{ $sabores->id }}-{{ $codigoBarras }}"
                                            data-parent="#accordion-lotes-{{ $sabores->id }}">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered">
                                                        <thead class="text-white" style="background: #FF5100">
                                                            <tr>
                                                                <th class="table-th text-white text-center ">ID</th>
                                                                <th class="table-th text-white text-center ">SKU</th>
                                                                <th class="table-th text-white text-center ">NAME</th>
                                                                <th class="table-th text-white text-center ">CATEGORIA
                                                                </th>
                                                                <th class="table-th text-white text-center ">Items</th>
                                                                <th class="table-th text-white text-center ">Acciones
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($productos as $producto)
                                                            <tr>
                                                                <td class="text-center">
                                                                    <h6> {{ $producto->producto->id }} </h6>
                                                                </td>
                                                                <td class="text-center">
                                                                    <h6>{{ $producto->producto->barcode }} </h6>
                                                                </td>
                                                                <td class="text-center">
                                                                    <h6> {{ $producto->producto->name }}</h6>
                                                                </td>
                                                                </td>
                                                                <td class="text-center">
                                                                    <h6> {{ $producto->producto->category->name }}</h6>
                                                                </td>
                                                                <td class="text-center">
                                                                    <h6>{{ $producto->Cantidad_Articulos }} </h6>
                                                                </td>
                                                                <td class="text-center">
                                                                    <a style="background:#f39022;"
                                                                        href="javascript:void(0)"
                                                                         onclick="Cambio('{{$producto->producto->id}}', '{{$producto->id}}')"
                                                                        class="btn btn-warning mb-2 mr-2 btn-rounded"
                                                                        title="Cambio a PRECOCIDO">
                                                                        <svg viewBox="0 0 24 24" width="24" height="24"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            fill="none" stroke-linecap="round"
                                                                            stroke-linejoin="round" class="css-i6dzq1">
                                                                            <polyline points="16 16 12 12 8 16">
                                                                            </polyline>
                                                                            <line x1="12" y1="12" x2="12" y2="21">
                                                                            </line>
                                                                            <path
                                                                                d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3">
                                                                            </path>
                                                                            <polyline points="16 16 12 12 8 16">
                                                                            </polyline>
                                                                        </svg>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                </div>
                                @else
                                No hay lotes asociados a este sabor.
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>




            </div>



        </div>

        @include('livewire.LotesNew.form')

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('lote-added', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('lote-updated', msg => {
            $('#theModal').modal('hide')
        });


    });

function Cambio(id,id_lote) {
    Swal.fire({
        title: '¿Pasar de Crudo a Pre-Cocido?',
        html: '<input type="number" id="pre" class="swal2-input" placeholder="Ingrese la cantidad">',
        showCancelButton: true,
        cancelButtonText: 'Cerrar',
        cancelButtonColor: '#fff',
        confirmButtonColor: '#3B3F5C',
        confirmButtonText: 'Aceptar',
        preConfirm: function () {
            var inputValue = document.getElementById('pre').value;
            var mensaje = "ID: " + id + ", Cantidad: " + inputValue + "Id del lote: " + id_lote;
          //  alert(mensaje);

            // Luego, emite el evento con el ID y la cantidad
            window.livewire.emit('Cambio', id , inputValue,id_lote );
        }
    }).then(function (result) {
        if (result.value) {
            // El usuario hizo clic en "Aceptar"
        }
    });
}


    </script>