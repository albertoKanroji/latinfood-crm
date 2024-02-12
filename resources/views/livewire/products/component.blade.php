<div>
    <div class="row sales layout-top-spacing">

        <div class="col-sm-12">
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h4 class="card-title">

                        <b>PRODUCTS | LIST (RAW)</b>
                        <br>
                        @if($productsOutOfStock->isNotEmpty())
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Products Without Stock
                            </button>
                            <div class="dropdown-menu dropdown-blur border animate__animated animate__fadeInDown"
                                aria-labelledby="dropdownMenuButton" style="overflow-y: auto; max-height: 200px;">
                                @foreach($productsOutOfStock as $product)
                                <a class="dropdown-item" href="{{ url('lotes') }}">
                                    <div class="d-flex align-items-center">
                                        <span>{{ $product->barcode }} - {{ $product->name }}</span>
                                        <p class="ml-2 mb-0 {{ $product->stock < 90 ? 'text-danger' : '' }}"> - STOCK
                                            {{ $product->stock }} </p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        <style type="text/css">
                        .dropdown-blur {
                            background-color: rgba(255, 255, 255, 0.8);
                            backdrop-filter: blur(5px);
                        }
                        </style>
                        @endif

                    </h4>
                    <ul class="tabs tab-pills">
                        @role('Admin')
                        <li>
                            <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal"
                                data-target="#theModal">Add</a>
                              <!-- <button id="showNotificationBtn">Mostrar Notificación</button>--> 

<script>
  // Obtén una referencia al botón
  var showNotificationBtn = document.getElementById('showNotificationBtn');

  // Agrega un controlador de eventos al botón
  showNotificationBtn.addEventListener('click', function () {
    // Crear una notificación cuando se hace clic en el botón
    Push.create('Hello World!', {
      body: 'Esta es una notificación de ejemplo.',
      icon: 'https://firebasestorage.googleapis.com/v0/b/latin-food-8635c.appspot.com/o/splash%2FlogoAnimadoNaranjaLoop.gif?alt=media&token=0f2cb2ee-718b-492c-8448-359705b01923',
      timeout: 4000,
      onClick: function () {
        window.focus();
        this.close();
      }
    });
  });
</script>
                        </li>

                        @endcan
                    </ul>
                </div>

                @include('common.searchbox')

                <div class="widget-content">

                    <div class="accordion" id="accordionCategories1">
                        @foreach($categories as $category)
                        <div class="card">
                            <div class="card-header" id="heading{{$category->id}}">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                        data-target="#collapse{{$category->id}}" aria-expanded="true"
                                        aria-controls="collapse{{$category->id}}">
                                        <h3>{{$category->name}} <span class="badge badge-primary">Total de productos:
                                                {{count($category->products->where('estado', 'CRUDO'))}}</span></h3>
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse{{$category->id}}" class="collapse"
                                aria-labelledby="heading{{$category->id}}" data-parent="#accordionCategories1">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mt-1">
                                            <thead class="text-white" style="background: #FF5100;">
                                                <tr>
                                                    <th class="table-th text-white text-center text-nowrap">SKU</th>
                                                    <th class="table-th text-white text-center text-nowrap">NAME</th>
                                                    <th class="table-th text-white text-center text-nowrap">SABOR</th>

                                                    <th class="table-th text-white text-center text-nowrap">PRICE</th>
                                                    <th class="table-th text-white text-center text-nowrap  ">STOCK</th>
                                                    <th class="table-th text-white text-center text-nowrap">MIN.STOCK</th>
                                                    
                                                    <th class="table-th text-white text-center text-nowrap">IN WOOCOMERCE</th>
                                                    <th class="table-th text-white text-center text-nowrap">Whith KEY</th>

                                                    <th class="table-th text-white text-center text-nowrap">ACTIONS</th>

                                                    <th class="table-th text-white text-center text-nowrap">QR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data as $product)
                                                @if($product->category_id == $category->id && $product->estado ==
                                                'CRUDO')

                                                <tr>
                                                    <td>
                                                        <h6 class="text-center">{{$product->barcode}}</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="text-center">{{$product->name}}</h6>
                                                    </td>

                                                    <td>
                                                        <h6 class="text-center">{{$product->sabor->nombre}}</h6>
                                                    </td>

                                                    <td>
                                                        <h6 class="text-center">${{$product->price}}</h6>
                                                    </td>
                                                    <td>
                                                        <h6
                                                            class="text-center {{$product->stock <= $product->alerts ? 'text-danger' : '' }}">
                                                            {{$product->stock}}
                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="text-center">{{$product->alerts}}</h6>
                                                    </td>

                                                   
                                                    <td>
                                                        <h6 class="text-center">
                                                            {{ strtoupper($product->EstaEnWoocomerce) }}</h6>

                                                    </td>
                                                    <td>
                                                        <h6 class="text-center">{{($product->TieneKey) }}</h6>

                                                    </td>


                                                    <td class="text-center" >
                                                        @role('Admin')
                                                        <a href="javascript:void(0)"
                                                            wire:click.prevent="Edit({{$product->id}})"
                                                            class="btn btn-warning mb-2 mr-2 btn-rounded" title="Edit">
                                                            <svg viewBox="0 0 24 24" width="24" height="24"
                                                                stroke="currentColor" stroke-width="2" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="css-i6dzq1">
                                                                <path d="M12 20h9"></path>
                                                                <path
                                                                    d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                        @if ($product->visible === 'no')
    <a href="javascript:void(0)"
        wire:click.prevent="novisible({{$product->id}})"
        class="btn btn-warning mb-2 mr-2 btn-rounded" title="Publicar">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c-7 0-11 8-11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
        </svg>
    </a>
@else
    <a href="javascript:void(0)"
        wire:click.prevent="visible({{$product->id}})"
        class="btn btn-warning mb-2 mr-2 btn-rounded" title="Ocultar">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        </svg>
    </a>
@endif

                                                        <a href="javascript:void(0)" title="Delete Product"
                                                            onclick="Confirm('{{$product->id}}')"
                                                            class="btn btn-danger mb-2 mr-2 btn-rounded" title="Delete">
                                                            <svg viewBox="0 0 24 24" width="24" height="24"
                                                                stroke="currentColor" stroke-width="2" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="css-i6dzq1">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path
                                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                </path>
                                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                                            </svg>
                                                        </a>
                                                        @endcan
                                                        @role('Admin|Employee')
                                                        @if ($product->TieneKey == 'SI')
                                                        <button type="button" title="Add Cart"
                                                            wire:click.prevent="ScanCode('{{$product->barcode}}')"
                                                            class="btn btn-warning mb-2 mr-2 btn-rounded"
                                                            title="Delete">
                                                            <svg viewBox="0 0 24 24" width="24" height="24"
                                                                stroke="currentColor" stroke-width="2" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="css-i6dzq1">
                                                                <circle cx="9" cy="21" r="1"></circle>
                                                                <circle cx="20" cy="21" r="1"></circle>
                                                                <path
                                                                    d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        @endcan
                                                        @if(strtoupper($product->EstaEnWoocomerce) != 'SI')
                                                        <a class="btn btn-warning mb-2 mr-2 btn-rounded"
                                                            title="Create in Woocomerce"
                                                            wire:click.prevent="CrearProWoo('{{ $product->id }}')">
                                                            <svg viewBox="0 0 24 24" width="24" height="24"
                                                                stroke="currentColor" stroke-width="2" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="css-i6dzq1">
                                                                <polyline points="16 16 12 12 8 16"></polyline>
                                                                <line x1="12" y1="12" x2="12" y2="21"></line>
                                                                <path
                                                                    d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3">
                                                                </path>
                                                                <polyline points="16 16 12 12 8 16"></polyline>
                                                            </svg>
                                                        </a>
                                                        @endif
                                                        @if ($product->TieneKey == 'NO')
                                                        <a class="btn btn-warning mb-2 mr-2 btn-rounded"
                                                            title="Generate Key product"
                                                            wire:click.prevent="GenerateKey('{{ $product->id }}')">
                                                            <svg viewBox="0 0 24 24" width="24" height="24"
                                                                stroke="currentColor" stroke-width="2" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="css-i6dzq1">
                                                                <path
                                                                    d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                        @endif
                                                        @endcan
                                                    </td>
                                                    @if ($product->TieneKey == 'SI')
                                                    <td>
                                                        <a class="btn btn-warning mb-2 mr-2 btn-rounded"
                                                            href="{{ url('detail/pdf' . '/' . $product->id ) }}"
                                                            title="print" target="_blank" style="background:#f39022;">
                                                            <svg viewBox="0 0 24 24" width="24" height="24"
                                                                stroke="currentColor" stroke-width="2" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="css-i6dzq1">
                                                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                                                <path
                                                                    d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                                                </path>
                                                                <rect x="6" y="14" width="12" height="8"></rect>
                                                            </svg></a>
                                                    </td>
                                                    @endif
                                                </tr>

                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @include('livewire.products.form')
        @include('livewire.products.formL')
    </div>
    <script>
    Livewire.on('swal-loading', function(message) {
        Swal.fire({
            title: message,
            text: 'Por favor, espera...',
            showConfirmButton: false,
            allowOutsideClick: false,
            willOpen: function() {
                Swal.showLoading();
            },
        });
    });

    Livewire.on('producto-creado', function() {
        Swal.fire('Éxito', 'Producto creado en WooCommerce', 'success').then(function() {
            location.reload();
        });
    });
    </script>
    <script>
    document.getElementById('create-product-button').addEventListener('click', function() {
        var productId = this.getAttribute('data-product-id');

        // Mostrar un mensaje de carga
        Swal.fire({
            title: 'Creando producto en WooCommerce',
            text: 'Por favor, espera...',
            showConfirmButton: false,
            allowOutsideClick: false,
            willOpen: function() {
                Swal.showLoading();
            },
        });

        // Realizar la solicitud AJAX para crear el producto en WooCommerce
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/create-product-in-woocommerce');
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
            if (xhr.status === 200) {
                Swal.fire('Éxito', 'Producto creado en WooCommerce', 'success');
            } else {
                Swal.fire('Error', 'No se pudo crear el producto en WooCommerce', 'error');
            }
        };
        xhr.send(JSON.stringify({
            product_id: productId
        }));
    });
    </script>
    <script>
    function Confirm(id) {
        swal({
            title: 'CHECK',
            text: '¿CONFIRM DELETE THIS REG?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Close',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Ok'
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('deleteRow', id)
                swal.close()
            }

        })
    }
  /*  window.addEventListener('DOMContentLoaded', (event) => {
        @if($productsOutOfStock -> isNotEmpty())
        // Función para mostrar la alerta
        swal({
            title: 'WARNING',
            text: 'MANY PRODUCTS ARE OUT STOCK, PLEASE CHECK',
            type: 'warning',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Ok',
            showCancelButton: false,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {

            }
        });
        @endif
    });*/
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('product-added', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('product-updated', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('product-deleted', msg => {
            // noty
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
        $('#theModal').on('hidden.bs.modal', function(e) {
            $('.er').css('display', 'none')
        })
        $('#theModal').on('shown.bs.modal', function(e) {
            $('.product-name').focus()
        })
    });

    function Confirm(id) {
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
            if (result.value) {
                window.livewire.emit('deleteRow', id)
                swal.close()
            }

        })
    }
    </script>
    <div class="row sales layout-top-spacing">
        <div class="col-sm-12">
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h4 class="card-title">
                        <b>PRODUCTS | LIST (PRE-COCKED)</b>
                    </h4>
                   
                </div>
                <div class="widget-content">

                    <div class="accordion" id="accordionCategories2">
                        @foreach($categories as $category)
                        <div class="card">
                            <div class="card-header" id="heading{{$category->id}}">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                        data-target="#collapse{{$category->id}}" aria-expanded="true"
                                        aria-controls="collapse{{$category->id}}">
                                        <h3> {{$category->name}} <span class="badge badge-primary">Total de productos:
                                                {{count($category->products->where('estado', 'PRECOCIDO'))}}</span></h3>
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse{{$category->id}}" class="collapse"
                                aria-labelledby="heading{{$category->id}}" data-parent="#accordionCategories2">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mt-1">
                                            <thead class="text-white" style="background: #FF5100;">
                                                <tr>
                                                    <th class="table-th text-white text-center">SKU</th>
                                                    <th class="table-th text-white text-center">NAME</th>
                                                    <th class="table-th text-white text-center">SABOR</th>
                                                    <th class="table-th text-white text-center">PRICE</th>
                                                    <th class="table-th text-white text-center">STOCK</th>
                                                    <th class="table-th text-white text-center">MIN.STOCK</th>
                                                    
                                                    <th class="table-th text-white text-center">IN WOOCOMERCE</th>
                                                    <th class="table-th text-white text-center">Whith KEY</th>
                                                    <th class="table-th text-white text-center">ACTIONS</th>
                                                    <th class="table-th text-white text-center">QR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data as $product)
                                                @if($product->category_id == $category->id && $product->estado ==
                                                'PRECOCIDO')

                                                <tr>
                                                    <td>
                                                        <h6 class="text-center">{{$product->barcode}}</h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="text-center">{{$product->name}}</h6>
                                                    </td>

                                                    <td>
                                                        <h6 class="text-center">{{$product->sabor->nombre}}</h6>
                                                    </td>

                                                    <td>
                                                        <h6 class="text-center">${{$product->price}}</h6>
                                                    </td>
                                                    <td>
                                                        <h6
                                                            class="text-center {{$product->stock <= $product->alerts ? 'text-danger' : '' }}">
                                                            {{$product->stock}}
                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="text-center">{{$product->alerts}}</h6>
                                                    </td>

                                                    
                                                    <td>
                                                        <h6 class="text-center">
                                                            {{ strtoupper($product->EstaEnWoocomerce) }}</h6>

                                                    </td>
                                                    <td>
                                                        <h6 class="text-center">{{($product->TieneKey) }}</h6>

                                                    </td>


                                                    <td class="text-center">
                                                        @role('Admin')
                                                        <a href="javascript:void(0)"
                                                            wire:click.prevent="Edit({{$product->id}})"
                                                            class="btn btn-dark mtmobile" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                         @if ($product->visible === 'no')
    <a href="javascript:void(0)"
        wire:click.prevent="novisible({{$product->id}})"
        class="btn btn-warning mb-2 mr-2 btn-rounded" title="Publicar">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c-7 0-11 8-11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
        </svg>
    </a>
@else
    <a href="javascript:void(0)"
        wire:click.prevent="visible({{$product->id}})"
        class="btn btn-warning mb-2 mr-2 btn-rounded" title="Ocultar">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        </svg>
    </a>
@endif
                                                        <a href="javascript:void(0)" title="Delete Product"
                                                            onclick="Confirm('{{$product->id}}')"
                                                            class="btn btn-dark mtmobile" title="Lotes">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                        @endcan
                                                        @role('Admin|Employee')
                                                        @if ($product->TieneKey == 'SI')
                                                        <button type="button" title="Add Cart"
                                                            wire:click.prevent="ScanCode('{{$product->barcode}}')"
                                                            class="btn btn-dark">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </button>
                                                        @endcan
                                                        @if(strtoupper($product->EstaEnWoocomerce) != 'SI')
                                                        <a class="btn btn-dark" title="Create in Woocomerce"
                                                            wire:click.prevent="CrearProWoo('{{ $product->id }}')">
                                                            <i class="fas fa-upload"></i>
                                                        </a>
                                                        @endif
                                                        @if ($product->TieneKey == 'NO')
                                                        <a class="btn btn-dark" title="Generate Key product"
                                                            wire:click.prevent="GenerateKey('{{ $product->id }}')">
                                                            <i class="fas fa-key"></i>
                                                        </a>
                                                        @endif
                                                        @endcan
                                                    </td>
                                                    @if ($product->TieneKey == 'SI')
                                                    <td>
                                                        <a class="btn btn-dark"
                                                            href="{{ url('detail/pdf' . '/' . $product->id ) }}"
                                                            title="print" target="_blank" style="background:#f39022;">
                                                            <i class="fas fa-print"></i></a>
                                                    </td>
                                                    @endif
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @include('livewire.products.form')
    </div>
    <script>
    $(document).ready(function() {
        // Controlar la apertura y cierre del acordeón 1
        $('#accordionCategories1 .collapse').on('show.bs.collapse', function() {
            $('#accordionCategories1 .collapse.show').collapse('hide');
        });

        // Controlar la apertura y cierre del acordeón 2
        $('#accordionCategories2 .collapse').on('show.bs.collapse', function() {
            $('#accordionCategories2 .collapse.show').collapse('hide');
        });
    });
    </script>
    <script>
    function Confirm(id) {

        swal({
            title: 'CHECK',
            text: '¿CONFIRM DELETE THIS REG?',
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
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('product-added', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('product-updated', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('product-deleted', msg => {
            // noty
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
        $('#theModal').on('hidden.bs.modal', function(e) {
            $('.er').css('display', 'none')
        })
        $('#theModal').on('shown.bs.modal', function(e) {
            $('.product-name').focus()
        })
    });

    function Confirm(id) {
        swal({
            title: 'CONFIRM',
            text: 'CONFIRM DELETE THIS REG?',
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
    </script>
    <script>
  /*  window.addEventListener('DOMContentLoaded', (event) => {
        @if($productsOutOfStock -> isNotEmpty())
        // Función para mostrar la alerta
        function mostrarAlerta() {
            swal({
                title: 'WARNING',
                text: 'SOME PRODUCTS ARE OUT STOCK, PLEASE CHECK.',
                type: 'warning',
                confirmButtonColor: '#3B3F5C',
                confirmButtonText: 'Ok',
                showCancelButton: false,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {}
            });
        }
        setInterval(mostrarAlerta, 3 * 60 * 1000);
        @endif
    });*/
    </script>
</div>