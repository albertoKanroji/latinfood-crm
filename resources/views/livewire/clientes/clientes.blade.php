
<div>
  
  <script type="text/javascript">
    function Borrar(clienteId) {
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
                window.livewire.emit('deleteRow', clienteId);
                swal.close();
            }
        });
    }

    document.addEventListener('livewire:load', function () {
        window.livewire.on('cliente-has-sales', function (message) {
            swal({
                title: 'Error',
                text: message,
                type: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
    });
</script>

<div>
  <style type="text/css">
    .izquierda {
      width: 50%;
      float: left;
    }
  .large-text {
        font-size: 1.2rem; /* Ajusta el tamaño de fuente según tu preferencia */
    }
    .derecha {
      width: 50%;
      float: right;
    }
  </style>

  <div class="row sales layout-top-spacing">

    <div class="col-sm-12 ">
      <div class="widget widget-chart-one">
        <div class="widget-heading">
          <h4 class="card-title"><b>Costumers</b> | List</b></h4>
          <ul class="tabs tab-pills">

            <li><a href="javascript:void(0);" class="btn btn-primary mb-2 mr-2 btn-rounded" data-toggle="modal" data-target="#theModal">Add New costumer</a></li>
          </ul>
        </div>
        @include('common.searchbox')
        <div class="widget-content">


          <div id="accordion">
            @foreach($data as $cliente)
            <div class="card">

              <div class="card-header" id="heading{{$cliente->id}}">
               
<div class="d-flex align-items-center">
  <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$cliente->id}}" aria-expanded="true" aria-controls="collapse{{$cliente->id}}">
    <div>
      <h3>{{$cliente->id}}. {{$cliente->name}} {{$cliente->last_name}}</h3>
    </div>
  </button>
  <div class="ml-auto">
    <div class="text-right">
      <p class="mb-0">Actions</p>
      <a href="{{ url('historial/pdf' . '/' . $cliente->id ) }}" class="btn btn-warning mb-2 mr-2 btn-rounded" title="Print History" style="background:#f39022;" target="_blank">
       <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
      </a>
      <a href="javascript:void(0)" onclick="Borrar('{{$cliente->id}}')" class="btn btn-danger mb-2 mr-2 btn-rounded" title="Delete">
                                      <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
      </a>
      <a href="javascript:void(0)" wire:click.prevent="Edit({{$cliente->id}})"  class="btn btn-warning mb-2 mr-2 btn-rounded" title="Edit">
                                       <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
      </a>
    </div>
  </div>
</div>



              </div>
              <div class="contenedor">
                <div class="izquierda">

                  <div id="collapse{{$cliente->id}}" class="collapse" aria-labelledby="heading{{$cliente->id}}" data-parent="#accordion">
                    <div class="card-body">
                        
                    <style>
    .customer-profile {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .customer-profile img {
        border-radius: 50%;
        height: 100px;
        width: 100px;
    }

    .customer-details {
        text-align: center;
    }

    .customer-details p {
        font-size: 19px;
        font-weight: bold;
    }

    hr {
        border-top: 1px solid #ccc;
    }
</style>

<div class="customer-profile">
    <img src="{{ asset('storage/customers/' . $cliente->image ) }}" alt="imagen de ejemplo">
    <div class="customer-details">
        <p>Costumer Details:</p>
        <hr>
        <p><strong>Full Name: </strong>{{$cliente->name}} {{$cliente->last_name}} {{$cliente->last_name2}}</p>
        <p><strong>Email: </strong> {{$cliente->email}}</p>
        <p><strong>Balance: $</strong>{{$cliente->saldo}}</p>
        <p><strong>Address:</strong> {{$cliente->address}}</p>
        <p><strong>Number Phone:</strong> {{$cliente->phone}}</p>
    </div>
</div>

                    </div>
                  <td>

</td>

                  </div>

                </div>
                <style type="text/css">
                 p {
        font-size: 19px;
        font-weight: bold;
    }
                </style>

 

                <div id="collapse{{$cliente->id}}" class="collapse derecha" aria-labelledby="heading{{$cliente->id}}" data-parent="#accordion">
                    <br>
           <p class="text-center">Purchase Details</p>     
<hr>
<div class="accordion" id="purchaseAccordion">
    <div class="card">
        <div class="card-header" id="purchaseHeading">
            <h4 class="card-title">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#purchaseCollapse" aria-expanded="false" aria-controls="purchaseCollapse">
                    <h3 class="text-center">More Info:</h3>  <div class="d-flex align-items-center">
  <p class="mb-0">Total Sales</p>
  <span class="badge badge-success ml-2">{{ $cliente->sale->count() }}</span>
</div>

                </button>
            </h4>
        </div>

        <div id="purchaseCollapse" class="collapse" aria-labelledby="purchaseHeading" data-parent="#purchaseAccordion">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #FF5100">
                            <tr>
                                <th class="table-th text-center text-white">Sale ID</th>
                                <th class="table-th text-center text-white">Total Spent</th>
                                <th class="table-th text-center text-white">ITEMS</th>
                                <th class="table-th text-center text-white">STATUS</th>
                                <th class="table-th text-center text-white">DETAIL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cliente->sale as $venta)
                                <tr>
                                    <td class="text-center">
                                        <h6> {{$venta->id}}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6> $ {{ $venta->total}}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6> {{$venta->items}}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6> {{$venta->status}}</h6>
                                    </td>
                                    <td class="text-center">
                                        <button wire:click.prevent="getDetails({{$venta->id}})" class="btn btn-dark btn-sm">
                                            <i class="fas fa-list"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                 
                </div>
            </div>
        </div>
    </div>
</div>





                </div>
                <div style="clear: both;"></div>
              </div>
            </div><br>
            @endforeach
            @include('livewire.clientes.form')
            @include('livewire.clientes.sales-detail')
            @include('livewire.clientes.info')
            <script>
              document.addEventListener('DOMContentLoaded', function() {

                window.livewire.on('cliente-added', msg => {
                  $('#theModal').modal('hide') //agregar lote
                });

                window.livewire.on('cliente-edit', msg => {
                  $('#theModal').modal('hide') //editar lote
                });
                window.livewire.on('cliente-delete', msg => {
                  $('#theModal').modal('hide') //eliminar lote
                });

                window.livewire.on('modal-show', msg => {
                  $('#theModal').modal('show')
                });
                window.livewire.on('show-modal', msg => {
                  $('#modalDetails').modal('show')
                });
                window.livewire.on('modal-hide', msg => {
                  $('#theModal').modal('hide')
                });


                window.livewire.on('hidden.bs.modal', msg => {
                  $('.er').css('display', 'none')
                });


     



              })
            </script>
          </div>






        </div>
      </div>
    </div>

  </div>





</div>
</div>