</div>
    <div id="ff">
          <h5 class="title text-center">
                <b></b>  Final Product
            </h5>
          <div class="row">
        <!-- ... -->
        <div class="col-sm-12">
            <!-- ... -->
            <div class="table-responsive" wire:key="subform-table">
                <table class="table table-bordered table-striped mt-1">
                    <thead class="text-white" style="background: #FF5100">
                        <tr>
                            <th class="table-th text-white text-center">SKU</th>
                            <th class="table-th text-white text-center">Cantidad de Artículos</th>
                            <th class="table-th text-white text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
@foreach($subform as $index => $item)
<tr wire:key="subform-{{ $index }}">
    <td class="text-center">
        <input type="text" wire:model="subform.{{ $index }}.BAR" class="form-control">
    </td>
    <td class="text-center">
        <input type="text" wire:model="subform.{{ $index }}.CANT" class="form-control">
    </td>
    <td class="text-center">
        <button class="btn btn-danger" wire:click.prevent="removeItem({{ $index }})">
            Eliminar
        </button>
    </td>
</tr>
@endforeach



                    </tbody>
                </table>
            </div>
            <button class="btn btn-primary" wire:click.prevent="addItem">Añadir más</button>
        </div>
    </div>
    </div>
           
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('tableRendered', function () {
            // Encontrar el contenedor de la tabla del subformulario dentro de la modal
            var subformTable = document.querySelector(' #ff');

            // Forzar una actualización de Livewire solo para la tabla del subformulario
            Livewire.find(subformTable.getAttribute('wire:ids')).call('render');
        });
    });
</script>
