<div>
    <div class="row sales layout-top-spacing">

        <div class="col-sm-12">
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h4 class="card-title">
                        <b>Drivers | Direct With K&D Latin Food</b>
                    </h4>
                    <ul class="tabs tab-pills">
                        <li>
                            <a href="javascript:void(0)" class="btn btn-primary mb-2 mr-2 btn-rounded"
                                data-toggle="modal" data-target="#theModal">Add New Driver</a>
                        </li>
                    </ul>
                </div>


                <div class="widget-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table striped mt-1">
                            <thead class="text-white" style="background: #ff5100">
                                <tr>
                                    <th class="table-th text-white text-center">No.</th>
                                    <th class="table-th text-white text-center">Name</th>
                                    <th class="table-th text-white text-center">Last Name</th>
                                    <th class="table-th text-white text-center">Company</th>
                                    <th class="table-th text-white text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($data as $op)
                                    @if($op->de_Planta =='SI')
                                    <th class="table-th text-center">{{$op->id}}</th>
                                    <th class="table-th text-center">{{$op->nombre}}</th>
                                    <th class="table-th text-center">{{$op->apellido}}</th>
                                    <th class="table-th text-center">{{$op->compañia}}</th>

                                    <td class="text-center">
                                        <a href="javascript:void(0)" wire:click.prevent="Edit({{$op->id}})"
                                            class="btn btn-warning mb-2 mr-2 btn-rounded" title="Edit">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                                stroke-width="2" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round" class="css-i6dzq1">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z">
                                                </path>
                                            </svg>
                                        </a>

                                        <a href="javascript:void(0)" onclick="Confirm('{{$op->id}}')"
                                            class="btn btn-warning mb-2 mr-2 btn-rounded" title="Delete">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                                stroke-width="2" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round" class="css-i6dzq1">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                </path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                            @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('livewire.transportes.form')
    </div>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('trans-added', msg => {
            $('#theModal').modal('hide') //agregar lote
        });

        window.livewire.on('trans-edit', msg => {
            $('#theModal').modal('hide') //editar lote
        });
        window.livewire.on('trans-delete', msg => {
            $('#theModal').modal('hide') //eliminar lote
        });

        window.livewire.on('modal-show', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('modal-hide', msg => {
            $('#theModal').modal('hide')
        });
    });

    function Confirm(id) {

        swal({
            title: 'DO YOU CONFIRM DELETING THE RECORD? ',
            text: 'THIS ACTION CANNOT BE REVERTED',
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
    };
    document.addEventListener('livewire:load', function() {
        window.livewire.on('operario-has-envios', function(message) {
            swal({
                title: 'Error',
                text: message,
                type: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
    });
    </script>


    <div class="row sales layout-top-spacing">

        <div class="col-sm-12">
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h4 class="card-title">
                        <b>Drivers | External</b>
                    </h4>
                    <ul class="tabs tab-pills">

                    </ul>
                </div>


                <div class="widget-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table striped mt-1">
                            <thead class="text-white" style="background: #ff5100">
                                <tr>
                                    <th class="table-th text-white text-center">No.</th>
                                    <th class="table-th text-white text-center">Name</th>
                                    <th class="table-th text-white text-center">Last Name</th>
                                    <th class="table-th text-white text-center">Company</th>
                                    <th class="table-th text-white text-center">Hire Date</th>
                                    <th class="table-th text-white text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($data as $op)
                                    @if($op->de_Planta =='NO')
                                    <th class="table-th text-center">{{$op->id}}</th>
                                    <th class="table-th text-center">{{$op->nombre}}</th>
                                    <th class="table-th text-center">{{$op->apellido}}</th>
                                    <th class="table-th text-center">{{$op->compañia}}</th>
                                    <th class="table-th text-center">{{$op->created_at}}</th>


                                    <td class="text-center">
                                        <a href="javascript:void(0)" wire:click.prevent="Edit({{$op->id}})"
                                            class="btn btn-warning mb-2 mr-2 btn-rounded" title="Edit">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                                stroke-width="2" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round" class="css-i6dzq1">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z">
                                                </path>
                                            </svg>
                                        </a>

                                        <a href="javascript:void(0)" onclick="Confirm('{{$op->id}}')"
                                            class="btn btn-warning mb-2 mr-2 btn-rounded" title="Delete">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                                stroke-width="2" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round" class="css-i6dzq1">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                </path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                            @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('trans-added', msg => {
            $('#theModal').modal('hide') //agregar lote
        });

        window.livewire.on('trans-edit', msg => {
            $('#theModal').modal('hide') //editar lote
        });
        window.livewire.on('trans-delete', msg => {
            $('#theModal').modal('hide') //eliminar lote
        });

        window.livewire.on('modal-show', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('modal-hide', msg => {
            $('#theModal').modal('hide')
        });

        function Confirm(id) {
            swal({
                title: 'DO YOU CONFIRM DELETING THE RECORD?',
                text: 'THIS ACTION CANNOT BE REVERTED',
                type: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Close',
                cancelButtonColor: '#fff',
                confirmButtonColor: '#3B3F5C',
                confirmButtonText: 'Accept'
            }).then(function(result) {
                if (result.value) {
                    window.livewire.emit('deleteRow', id);

                    swal({
                        title: 'Success',
                        text: 'The record has been deleted successfully.',
                        type: 'success',
                        confirmButtonColor: '#3B3F5C',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
    </script>
</div>