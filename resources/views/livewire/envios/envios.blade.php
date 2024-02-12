<link href='https://unpkg.com/css.gg@2.0.0/icons/css/glass-alt.css' rel='stylesheet'>
<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>Shipping | LIST</b>
                </h4>

            </div>



            <div class="widget-content">
                <div id="accordion" class="accordion-icons">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h4 class="card-title">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <div class="d-flex align-items-center">
                                        <h2>Shipping | Pending to send</h2>
                                        <span id="counter1" class="badge badge-warning ml-2"></span>
                                    </div>
                                </button>
                            </h4>
                        </div>


                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mt-1">
                                        <thead class="text-white" style="background: #ff5100">
                                            <tr>

                                                <th class="table-th text-center text-white">Order No.</th>
                                                <th class="table-th text-center text-white">Order No. Woocomerce</th>
                                                <th class="table-th text-center text-white">Client</th>
                                                <th class="table-th text-center text-white">Address</th>
                                                <th class="table-th text-center text-white">Customer Phone</th>
                                                <th class="table-th text-center text-white">Driver</th>
                                                <th class="table-th text-center text-white">Company</th>
                                                <th class="table-th text-center text-white">Status</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $counter = 0;
                                            @endphp
                                            @foreach($data as $pendientes)
                                            @if($pendientes->sales->status_envio == 'PENDIENTE')

                                            @php
                                            $pendingSales = $pendientes->sales()->where('status_envio',
                                            'PENDIENTE')->get();

                                            @endphp
                                            @foreach($pendingSales as $venta)




                                            <tr>



                                                @foreach($cliente as $clientes)
                                                @if($pendientes->sales->CustomerID == $clientes->id)
                                                <th class="table-th text-center">{{ $pendientes->sales->id }}</th>
                                                <th class="table-th text-center">
                                                    {{ $pendientes->sales->woocommerce_order_id }}</th>
                                                <th class="table-th text-center">{{ $clientes->name }}
                                                    {{ $clientes->last_name }}</th>
                                                <th class="table-th text-center">{{ $clientes->address }}</th>
                                                <th class="table-th text-center">{{ $clientes->phone }}</th>
                                                @endif
                                                @endforeach


                                                @foreach($operario as $operarios)
                                                @if($pendientes->id_transport == $operarios->id)
                                                <th class="table-th text-center">{{ $operarios->nombre }}
                                                    {{ $operarios->apellido }}</th>
                                                <th class="table-th text-center">{{ $operarios->compañia }}</th>
                                                @endif
                                                @endforeach

                                                <th class="table-th text-center">
                                                    <button class="btn btn-success btnQR"
                                                        data-sale-id="{{ $pendientes->sales->id }}"
                                                        title="Update On transit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="feather feather-video">
                                                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2">
                                                            </rect>
                                                        </svg>
                                                        <br>
                                                        Open Scan
                                                    </button>






                                                </th>

                                            </tr>

                                            @php
                                            $counter++;
                                            @endphp

                                            @endforeach


                                            @endif

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @include('livewire.envios.sale-details')

                        @include('livewire.envios.scan')
                    </div>

                </div>

                <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    var btnsUpdateFin = document.getElementsByClassName('btnQR');
                    for (var i = 0; i < btnsUpdateFin.length; i++) {
                        btnsUpdateFin[i].addEventListener('click', function() {
                            var saleId = this.getAttribute('data-sale-id');
                            console.log(saleId);
                            ventaId = saleId;
                            // Abre el modal para ingresar la firma
                            $('#modalScan').modal('show');

                            // Agrega un event listener para el botón de confirmación de firma
                            document.getElementById('CargarQR').addEventListener('click', function() {

                                // Realizar una petición AJAX para cambiar el estado
                                fetch('/intranet/public/qr/' + saleId, {


                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            ID: saleId
                                        })
                                    })
                                    .then(function(response) {
                                        if (response.ok) {
                                            // Si la respuesta es exitosa, mostrar el resultado por consola
                                            return response.json();
                                        } else {
                                            // Si hay un error, mostrar el mensaje de error por consola
                                            throw new Error('Error en la petición AJAX');
                                        }
                                    })
                                    .then(function(data) {
                                        // Mostrar el resultado por consola
                                        console.log(data);
                                    })
                                    .catch(function(error) {
                                        // Error en la petición AJAX

                                        console.log(error);
                                    });
                            });
                        });
                    }
                });
                </script>


                <script>
                document.addEventListener('DOMContentLoaded', function() {

                    window.livewire.on('despacho-added', msg => {
                        $('#theModal').modal('hide') //agregar lote
                    });

                    window.livewire.on('despacho-edit', msg => {
                        $('#theModal').modal('hide') //editar lote
                    });
                    window.livewire.on('despacho-delete', msg => {
                        $('#theModal').modal('hide') //eliminar lote
                    });
                    window.livewire.on('mostrar', msg => {
                        $('#modalQR').modal('show')
                    });






                })
                </script>



                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('counter1').textContent = "{{ $counter }}";
                });
                </script>




                <br>
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h4 class="card-title">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    <div class="d-flex align-items-center">
                                        <i class="gg-glass-alt"></i>
                                        <h3> Shipping | On Transit </h3> <span id="counter"
                                            class="badge badge-primary ml-2"></span>
                                    </div>
                                </button>
                            </h4>
                        </div>

                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mt-1">
                                        <thead class="text-white" style="background: #ff5100">
                                            <tr>
                                                <th class="table-th text-center text-white">Order No.</th>
                                                <th class="table-th text-center text-white">Order No. Woocomerce</th>
                                                <th class="table-th text-center text-white">Client</th>
                                                <th class="table-th text-center text-white">Address</th>
                                                <th class="table-th text-center text-white">Customer Phone</th>
                                                <th class="table-th text-center text-white">Driver</th>
                                                <th class="table-th text-center text-white">Company</th>
                                                <th class="table-th text-center text-white">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $counter = 0;
                                            @endphp
                                            @foreach($data as $pendientes)
                                            @if($pendientes->sales->status_envio =='ACTUAL')
                                            @foreach($operario as $operarios)
                                            @if($pendientes->id_transport ==$operarios->id)
                                            @foreach($cliente as $clientes)
                                            @if($pendientes->sales->CustomerID ==$clientes->id)
                                            <tr>
                                                <th class="table-th text-center">{{ $pendientes->sales->id }}</th>
                                                <th class="table-th text-center">
                                                    {{ $pendientes->sales->woocommerce_order_id }}</th>
                                                <th class="table-th text-center">{{ $clientes->name }}
                                                    {{ $clientes->last_name }}</th>
                                                <th class="table-th text-center">{{ $clientes->address }}</th>
                                                <th class="table-th text-center">{{ $clientes->phone }}</th>
                                                <th class="table-th text-center">{{ $operarios->nombre }}
                                                    {{ $operarios->apellido }}</th>
                                                <th class="table-th text-center">{{ $operarios->compañia }}</th>
                                                <th class="table-th text-center">
                                                    <button class="btn btn-success btnUpdateFin"
                                                        data-sale-id="{{ $pendientes->sales->id }}"
                                                        title="Update to On transit">Change to Received</button>
                                                </th>
                                            </tr>
                                            @php
                                            $counter++;
                                            @endphp
                                            @endif
                                            @endforeach
                                            @endif
                                            @endforeach
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('livewire.envios.firma')
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('counter').textContent = "{{ $counter }}";
                });
                </script>
                <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    var btnsUpdateFin = document.getElementsByClassName('btnUpdateFin');
                    for (var i = 0; i < btnsUpdateFin.length; i++) {
                        btnsUpdateFin[i].addEventListener('click', function() {
                            var saleId = this.getAttribute('data-sale-id');

                            // Abre el modal para ingresar la firma
                            $('#modalFirma').modal('show');

                            // Agrega un event listener para el botón de confirmación de firma
                            document.getElementById('btnConfirmarFirma').addEventListener('click',
                                function() {
                                    // Obtén la firma del signaturePad
                                    var canvas = document.getElementById('signature-pad');
                                    var signaturePad = new SignaturePad(canvas);
                                    var firma = signaturePad.toDataURL();

                                    // Realizar una petición AJAX para cambiar el estado
                                    fetch('/intranet/public/update-fin/' + saleId, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                firma: firma
                                            })
                                        })
                                        .then(function(response) {
                                            if (response.ok) {
                                                // Estado actualizado correctamente
                                                alert('Delivery Status changed.');
                                                window.location.reload(); // Recargar la página
                                            } else {
                                                // Error al actualizar el estado
                                                alert('Error - Delivery status not changed.');
                                            }
                                        })
                                        .catch(function(error) {
                                            // Error en la petición AJAX
                                            alert('Error in AJAX request.');
                                        });
                                });
                        });
                    }
                });
                </script>
                <br>
                <style type="text/css">
                .record {
                    transition: opacity 0.3s ease;
                }

                #tableBody.expand .record {
                    opacity: 1;
                }

                .record.hidden {
                    display: none;

                }

                .button {
                    background-color: #ff5100;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 4px;
                    cursor: pointer;
                }

                .button:hover {
                    background-color: #ff6f33;
                }

                .button:active {
                    background-color: #e64800;
                }
                </style>
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h4 class="card-title">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                    <div class="d-flex align-items-center">
                                        <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-home">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                            </svg></div>
                                        <h4>Shipping | Received </h4><span id="counter2"
                                            class="badge badge-success ml-2"></span>
                                    </div>
                                </button>
                            </h4>
                        </div>

                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                            data-parent="#accordion">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mt-1">
                                        <thead class="text-white" style="background: #ff5100">
                                            <tr>
                                                <th class="table-th text-center text-white">Order No.</th>
                                                <th class="table-th text-center text-white">Order No. Woocomerce</th>
                                                <th class="table-th text-center text-white">Client</th>
                                                <th class="table-th text-center text-white">Address</th>
                                                <th class="table-th text-center text-white">Customer Phone</th>
                                                <th class="table-th text-center text-white">Driver</th>
                                                <th class="table-th text-center text-white">Company</th>
                                                <th class="table-th text-center text-white">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <!-- Renderiza solo los primeros 2 registros -->
                                            @php
                                            $counter = 0;
                                            @endphp
                                            @foreach($data as $pendientes)
                                            @if($pendientes->sales->status_envio =='FIN')
                                            @foreach($operario as $operarios)
                                            @if($pendientes->id_transport ==$operarios->id)
                                            @foreach($cliente as $clientes)
                                            @if($pendientes->sales->CustomerID ==$clientes->id)
                                            <tr class="record">
                                                <th class="table-th text-center">{{ $pendientes->sales->id }}</th>
                                                <th class="table-th text-center">
                                                    {{ $pendientes->sales->woocommerce_order_id }}</th>
                                                <th class="table-th text-center">{{ $clientes->name }}
                                                    {{ $clientes->last_name }}</th>
                                                <th class="table-th text-center">{{ $clientes->address }}</th>
                                                <th class="table-th text-center">{{ $clientes->phone }}</th>
                                                <th class="table-th text-center">{{ $operarios->nombre }}
                                                    {{ $operarios->apellido }}</th>
                                                <th class="table-th text-center">{{ $operarios->compañia }}</th>
                                                <th class="table-th text-center">RECEIVED</th>
                                            </tr>
                                            @php
                                            $counter++;
                                            @endphp
                                            @endif
                                            @endforeach
                                            @endif
                                            @endforeach
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div id="buttonsContainer">
                                        @if ($counter > 2)
                                        <button id="verMas" class="button" onclick="verMasRegistros()">Ver mas</button>
                                        <button id="verMenos" class="button" onclick="verMenosRegistros()"
                                            style="display: none;">Ver menos</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <script>
                function verMasRegistros() {
                    var registrosOcultos = document.querySelectorAll('.record.hidden');
                    registrosOcultos.forEach(function(registro) {
                        registro.classList.remove('hidden');
                    });

                    // Cambiar la visibilidad de los botones
                    document.getElementById('verMas').style.display = 'none';
                    document.getElementById('verMenos').style.display = 'inline-block';

                    // Agregar clase para animación de transición
                    document.getElementById('tableBody').classList.add('expand');
                }

                function verMenosRegistros() {
                    var registrosExtras = document.querySelectorAll('.record:not(:nth-child(-n+2))');
                    registrosExtras.forEach(function(registro) {
                        registro.classList.add('hidden');
                    });

                    // Cambiar la visibilidad de los botones
                    document.getElementById('verMas').style.display = 'inline-block';
                    document.getElementById('verMenos').style.display = 'none';

                    // Remover clase para animación de transición
                    document.getElementById('tableBody').classList.remove('expand');
                }
                </script>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('counter2').textContent = "{{ $counter }}";
                });
                </script>
            </div>
        </div>
    </div>
</div>
<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>Shipping | Analist</b>
                </h4>

            </div>



            <div class="widget-content">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget-one ">
                        <div class="widget-content">
                            <div class="w-numeric-value">
                                <div class="w-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-shopping-cart">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                        </path>
                                    </svg>
                                </div>
                                <div class="w-content">
                                    <span class="w-value">{{ $counter }}</span>
                                    <span class="w-numeric-title">Total Envios terminados</span>
                                </div>
                            </div>
                            <div class="w-chart" style="position: relative;">
                                <div id="total-orders" style="min-height: 295px;">
                                    <div id="apexchartsy7e34bu7h" class="apexcharts-canvas apexchartsy7e34bu7h light"
                                        style="width: 410px; height: 295px;"><svg id="SvgjsSvg1949" width="410"
                                            height="295" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg"
                                            xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                            style="background: transparent;">
                                            <g id="SvgjsG1951" class="apexcharts-inner apexcharts-graphical"
                                                transform="translate(0, 125)">
                                                <defs id="SvgjsDefs1950">
                                                    <clipPath id="gridRectMasky7e34bu7h">
                                                        <rect id="SvgjsRect1955" width="412" height="172" x="-1" y="-1"
                                                            rx="0" ry="0" fill="#ffffff" opacity="1" stroke-width="0"
                                                            stroke="none" stroke-dasharray="0"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMasky7e34bu7h">
                                                        <rect id="SvgjsRect1956" width="412" height="172" x="-1" y="-1"
                                                            rx="0" ry="0" fill="#ffffff" opacity="1" stroke-width="0"
                                                            stroke="none" stroke-dasharray="0"></rect>
                                                    </clipPath>
                                                    <linearGradient id="SvgjsLinearGradient1962" x1="0" y1="0" x2="0"
                                                        y2="1">
                                                        <stop id="SvgjsStop1963" stop-opacity="0.4"
                                                            stop-color="rgba(255,255,255,0.4)" offset="0.45"></stop>
                                                        <stop id="SvgjsStop1964" stop-opacity="0.05"
                                                            stop-color="rgba(255,255,255,0.05)" offset="1"></stop>
                                                        <stop id="SvgjsStop1965" stop-opacity="0.05"
                                                            stop-color="rgba(255,255,255,0.05)" offset="1"></stop>
                                                    </linearGradient>
                                                </defs>
                                                <line id="SvgjsLine1954" x1="0" y1="0" x2="0" y2="170" stroke="#b6b6b6"
                                                    stroke-dasharray="3" class="apexcharts-xcrosshairs" x="0" y="0"
                                                    width="1" height="170" fill="#b1b9c4" filter="none"
                                                    fill-opacity="0.9" stroke-width="1"></line>
                                                <g id="SvgjsG1968" class="apexcharts-xaxis" transform="translate(0, 0)">
                                                    <g id="SvgjsG1969" class="apexcharts-xaxis-texts-g"
                                                        transform="translate(0, -4)"></g>
                                                </g>
                                                <g id="SvgjsG1972" class="apexcharts-grid">
                                                    <line id="SvgjsLine1974" x1="0" y1="170" x2="410" y2="170"
                                                        stroke="transparent" stroke-dasharray="0"></line>
                                                    <line id="SvgjsLine1973" x1="0" y1="1" x2="0" y2="170"
                                                        stroke="transparent" stroke-dasharray="0"></line>
                                                </g>
                                                <g id="SvgjsG1958"
                                                    class="apexcharts-area-series apexcharts-plot-series">
                                                    <g id="SvgjsG1959" class="apexcharts-series" seriesName="Sales"
                                                        data:longestSeries="true" rel="1" data:realIndex="0">
                                                        <path id="apexcharts-area-0"
                                                            d="M 0 170L 0 92.72727272727272C 15.944444444444445 92.72727272727272 29.611111111111114 59.6103896103896 45.55555555555556 59.6103896103896C 61.5 59.6103896103896 75.16666666666667 70.64935064935064 91.11111111111111 70.64935064935064C 107.05555555555556 70.64935064935064 120.72222222222224 26.493506493506487 136.66666666666669 26.493506493506487C 152.61111111111111 26.493506493506487 166.2777777777778 65.12987012987011 182.22222222222223 65.12987012987011C 198.16666666666669 65.12987012987011 211.83333333333334 4.415584415584391 227.7777777777778 4.415584415584391C 243.72222222222226 4.415584415584391 257.3888888888889 65.12987012987011 273.33333333333337 65.12987012987011C 289.2777777777778 65.12987012987011 302.94444444444446 26.493506493506487 318.8888888888889 26.493506493506487C 334.83333333333337 26.493506493506487 348.5 70.64935064935064 364.44444444444446 70.64935064935064C 380.3888888888889 70.64935064935064 394.05555555555554 59.6103896103896 410 59.6103896103896C 410 59.6103896103896 410 59.6103896103896 410 170M 410 59.6103896103896z"
                                                            fill="url(#SvgjsLinearGradient1962)" fill-opacity="1"
                                                            stroke-opacity="1" stroke-linecap="butt" stroke-width="0"
                                                            stroke-dasharray="0" class="apexcharts-area" index="0"
                                                            clip-path="url(#gridRectMasky7e34bu7h)"
                                                            pathTo="M 0 170L 0 92.72727272727272C 15.944444444444445 92.72727272727272 29.611111111111114 59.6103896103896 45.55555555555556 59.6103896103896C 61.5 59.6103896103896 75.16666666666667 70.64935064935064 91.11111111111111 70.64935064935064C 107.05555555555556 70.64935064935064 120.72222222222224 26.493506493506487 136.66666666666669 26.493506493506487C 152.61111111111111 26.493506493506487 166.2777777777778 65.12987012987011 182.22222222222223 65.12987012987011C 198.16666666666669 65.12987012987011 211.83333333333334 4.415584415584391 227.7777777777778 4.415584415584391C 243.72222222222226 4.415584415584391 257.3888888888889 65.12987012987011 273.33333333333337 65.12987012987011C 289.2777777777778 65.12987012987011 302.94444444444446 26.493506493506487 318.8888888888889 26.493506493506487C 334.83333333333337 26.493506493506487 348.5 70.64935064935064 364.44444444444446 70.64935064935064C 380.3888888888889 70.64935064935064 394.05555555555554 59.6103896103896 410 59.6103896103896C 410 59.6103896103896 410 59.6103896103896 410 170M 410 59.6103896103896z"
                                                            pathFrom="M -1 170L -1 170L 45.55555555555556 170L 91.11111111111111 170L 136.66666666666669 170L 182.22222222222223 170L 227.7777777777778 170L 273.33333333333337 170L 318.8888888888889 170L 364.44444444444446 170L 410 170">
                                                        </path>
                                                        <path id="apexcharts-area-0"
                                                            d="M 0 92.72727272727272C 15.944444444444445 92.72727272727272 29.611111111111114 59.6103896103896 45.55555555555556 59.6103896103896C 61.5 59.6103896103896 75.16666666666667 70.64935064935064 91.11111111111111 70.64935064935064C 107.05555555555556 70.64935064935064 120.72222222222224 26.493506493506487 136.66666666666669 26.493506493506487C 152.61111111111111 26.493506493506487 166.2777777777778 65.12987012987011 182.22222222222223 65.12987012987011C 198.16666666666669 65.12987012987011 211.83333333333334 4.415584415584391 227.7777777777778 4.415584415584391C 243.72222222222226 4.415584415584391 257.3888888888889 65.12987012987011 273.33333333333337 65.12987012987011C 289.2777777777778 65.12987012987011 302.94444444444446 26.493506493506487 318.8888888888889 26.493506493506487C 334.83333333333337 26.493506493506487 348.5 70.64935064935064 364.44444444444446 70.64935064935064C 380.3888888888889 70.64935064935064 394.05555555555554 59.6103896103896 410 59.6103896103896"
                                                            fill="none" fill-opacity="1" stroke="#ffffff"
                                                            stroke-opacity="1" stroke-linecap="butt" stroke-width="2"
                                                            stroke-dasharray="0" class="apexcharts-area" index="0"
                                                            clip-path="url(#gridRectMasky7e34bu7h)"
                                                            pathTo="M 0 92.72727272727272C 15.944444444444445 92.72727272727272 29.611111111111114 59.6103896103896 45.55555555555556 59.6103896103896C 61.5 59.6103896103896 75.16666666666667 70.64935064935064 91.11111111111111 70.64935064935064C 107.05555555555556 70.64935064935064 120.72222222222224 26.493506493506487 136.66666666666669 26.493506493506487C 152.61111111111111 26.493506493506487 166.2777777777778 65.12987012987011 182.22222222222223 65.12987012987011C 198.16666666666669 65.12987012987011 211.83333333333334 4.415584415584391 227.7777777777778 4.415584415584391C 243.72222222222226 4.415584415584391 257.3888888888889 65.12987012987011 273.33333333333337 65.12987012987011C 289.2777777777778 65.12987012987011 302.94444444444446 26.493506493506487 318.8888888888889 26.493506493506487C 334.83333333333337 26.493506493506487 348.5 70.64935064935064 364.44444444444446 70.64935064935064C 380.3888888888889 70.64935064935064 394.05555555555554 59.6103896103896 410 59.6103896103896"
                                                            pathFrom="M -1 170L -1 170L 45.55555555555556 170L 91.11111111111111 170L 136.66666666666669 170L 182.22222222222223 170L 227.7777777777778 170L 273.33333333333337 170L 318.8888888888889 170L 364.44444444444446 170L 410 170">
                                                        </path>
                                                        <g id="SvgjsG1960" class="apexcharts-series-markers-wrap">
                                                            <g class="apexcharts-series-markers">
                                                                <circle id="SvgjsCircle1980" r="0" cx="0" cy="0"
                                                                    class="apexcharts-marker werl2y1vr no-pointer-events"
                                                                    stroke="#ffffff" fill="#ffffff" fill-opacity="1"
                                                                    stroke-width="2" stroke-opacity="0.9"
                                                                    default-marker-size="0"></circle>
                                                            </g>
                                                        </g>
                                                        <g id="SvgjsG1961" class="apexcharts-datalabels"></g>
                                                    </g>
                                                </g>
                                                <line id="SvgjsLine1975" x1="0" y1="0" x2="410" y2="0" stroke="#b6b6b6"
                                                    stroke-dasharray="0" stroke-width="1"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line id="SvgjsLine1976" x1="0" y1="0" x2="410" y2="0"
                                                    stroke-dasharray="0" stroke-width="0"
                                                    class="apexcharts-ycrosshairs-hidden"></line>
                                                <g id="SvgjsG1977" class="apexcharts-yaxis-annotations"></g>
                                                <g id="SvgjsG1978" class="apexcharts-xaxis-annotations"></g>
                                                <g id="SvgjsG1979" class="apexcharts-point-annotations"></g>
                                            </g>
                                            <rect id="SvgjsRect1953" width="0" height="0" x="0" y="0" rx="0" ry="0"
                                                fill="#fefefe" opacity="1" stroke-width="0" stroke="none"
                                                stroke-dasharray="0"></rect>
                                            <g id="SvgjsG1970" class="apexcharts-yaxis" rel="0"
                                                transform="translate(-21, 0)">
                                                <g id="SvgjsG1971" class="apexcharts-yaxis-texts-g"></g>
                                            </g>
                                        </svg>
                                        <div class="apexcharts-legend"></div>
                                        <div class="apexcharts-tooltip dark">
                                            <div class="apexcharts-tooltip-series-group"><span
                                                    class="apexcharts-tooltip-marker"
                                                    style="background-color: rgb(255, 255, 255);"></span>
                                                <div class="apexcharts-tooltip-text"
                                                    style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span
                                                            class="apexcharts-tooltip-text-label"></span><span
                                                            class="apexcharts-tooltip-text-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span
                                                            class="apexcharts-tooltip-text-z-label"></span><span
                                                            class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 411px; height: 296px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>