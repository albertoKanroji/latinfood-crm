<div wire:ignore.self class="modal fade" id="modalDetails" tabindex="-1" role="dialog"
    style="backdrop-filter: blur(10px);">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>Shop Cart #{{$saleId}}</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>PLEASE WAIT</h6>
            </div>
            <div class="modal-body">


                <div class="table-responsive">
                    <table class="table table-bordered table striped mt-1">
                        <thead class="text-white" style="background: #FF5100">
                            <tr>

                                <th class="table-th text-white text-center">SKU</th>
                                <th class="table-th text-white text-center">PRODUCT</th>

                                <th class="table-th text-white text-center">QTY</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $d)
                            <tr>
                               
                                <td class='text-center'>
                                    <h6>{{$d->barcode}}</h6>
                                </td>
                                <td class='text-center'>
                                    <h6>{{$d->product}}</h6>
                                </td>
                                <td class='text-center'>
                                    <h6>{{number_format($d->quantity,0)}}</h6>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="3">
                                    <h5 class="text-center font-weight-bold">ITEMS TOTAL</h5>
                                </td>
                                <td>
                                    <h5 class="text-center">{{$countDetails}}</h5>
                                </td>
                                <td>
                                    <h5 class="text-center">

                                    </h5>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>




            </div>
            <div class="modal-footer">
                <a style="background: #FF5100" href="javascript:void(0)" onclick="Editar({{$saleId}})"
                    class="btn btn-warning mb-2 mr-2 btn-rounded" title=" Load Delivery">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                    <span style="color: white;">Edit Delivery</span>
                </a>

                <a style="background: #FF5100" href="javascript:void(0)" onclick="Cargar({{$saleId}})"
                    class="btn btn-warning mb-2 mr-2 btn-rounded" title=" Load Delivery">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-package">
                        <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                        </path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <span style="color: white;">Load Delivery</span>
                </a>





                <button type="button" class="btn btn-dark close-btn text-info" data-dismiss="modal">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                    </svg>
                    Close
                </button>



            </div>
        </div>
    </div>
</div>

<script>
function Cargar(saleId) {
    swal({
        title: 'CONFIRM LOAD DELIVERY',
        text: 'THIS ACTION CANT RETURN',
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        cancelButtonColor: '#fff',
        confirmButtonColor: '#3B3F5C',
        confirmButtonText: 'Confirm'
    }).then(function(result) {
        if (result.value) {
            // Realizar la acción aquí
            // ...

            // Emitir el evento 'CargarPedido' con el ID del pedido
            window.livewire.emit('CargarPedido', saleId);

            // Mostrar el mensaje de éxito
            swal({
                title: 'Sucess',
                text: 'The delivery was update.',
                type: 'success',
                confirmButtonColor: '#3B3F5C',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}

function Editar(saleId) {
    swal({
        title: 'CONFIRM CHANGE DELIVERY?',
        text: 'THIS ACTION CANT RETURN',
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        cancelButtonColor: '#fff',
        confirmButtonColor: '#3B3F5C',
        confirmButtonText: 'Confirm'
    }).then(function(result) {
        if (result.value) {
            // Realizar la acción aquí
            // ...

            // Emitir el evento 'CargarPedido' con el ID del pedido
            window.livewire.emit('EditarPedido', saleId);

            // Mostrar el mensaje de éxito

        }
    });
}

document.addEventListener('DOMContentLoaded', function() {



    window.livewire.on('show-modal', msg => {
        $('#modalFirma').modal('show')
    });


    window.livewire.on('show-modal', msg => {
        $('#modalDetails').modal('show')
    });
    window.livewire.on('hidden.bs.modal', msg => {
        $('.er').css('display', 'none')
    });




})
</script>