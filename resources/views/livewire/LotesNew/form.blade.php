@include('common.modalHead')


<div class="row">
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Usuario</label>
            <input type="text" wire:model.lazy="User" class="form-control" placeholder="{{$User}}" value="{{$User}}"
                readonly>
            @error('User') <span class="text-danger er">{{ $message}}</span>@enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Fecha de Vencimiento</label>
            <input type="date" wire:model.lazy="Fecha_Vencimiento" placeholder="{{$Fecha_Vencimiento}}"
                class="form-control" readonly>
            @error('Fecha_Vencimiento') <span class="text-danger er">{{ $message}}</span>@enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Sabor</label>
            <select name="Sabor" wire:model="Sabor" class="form-control" data-live-search="true">
                <option value="Elegir" disabled>Elegir</option>
                @foreach($sabor as $sabor)
                <option value="{{ $sabor->id }}">{{ $sabor->nombre }}</option>
                @endforeach
            </select>
            @error('Sabor') <span class="text-danger er">{{ $message}}</span>@enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Lote</label>
            <select name="insumo" wire:model="LoteInsumo" class="form-control">
                <option value="Elegir" disabled>Elegir</option>
                @foreach($insumo as $lote)
                <option value="{{ $lote->CodigoBarras }}">{{ $lote->CodigoBarras }} - {{ $lote->Fecha_Vencimiento }} -
                    Stock: {{ $lote->Cantidad_Articulos }}</option>
                @endforeach
            </select>
            @error('LoteInsumo') <span class="text-danger er">{{ $message}}</span>@enderror
        </div>
    </div>





</div>
<div id="subform-table">
    <h5 class="title text-center">
        <b></b> Final Product
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
                        @if($subform)
                        @foreach($subform as $index => $item)
                        <tr wire:key="subform-{{ $index }}">
                            <td class="text-center">
                                <select name="product[]" wire:model="subform.{{ $index }}.BAR" class="form-control">
                                    <option value="Elegir" disabled>Elegir</option>
                                    @foreach($product as $producto)
                                    <option value="{{ $producto->id }}">{{ $producto->barcode }} - {{ $producto->name }}
                                        - Stock: {{ $producto->stock }}</option>
                                    @endforeach
                                </select>
                                @error("subform.{$index}.BAR") <span
                                    class="text-danger er">{{ $message}}</span>@enderror
                            </td>
                            <td class="text-center">
                                <input type="text" name="cantidad[]" wire:model="subform.{{ $index }}.CANT"
                                    class="form-control">
                                @error("subform.{$index}.CANT") <span
                                    class="text-danger er">{{ $message}}</span>@enderror
                            </td>
                            <td class="text-center">
                                <button class="btn btn-danger mb-2 mr-2 btn-rounded"
                                    wire:click.prevent="removeItem({{ $index }})">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>

                </table>
            </div>
            <button class="btn btn-primary mb-2 mr-2 btn-rounded" wire:click.prevent="addItem">Añadir más</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:load', function() {
    Livewire.on('tableRendered', function() {
        // Encontrar el contenedor de la tabla del subformulario dentro de la modal
        var subformTable = document.querySelector(' #subform-table');

        // Forzar una actualización de Livewire solo para la tabla del subformulario
        Livewire.find(subformTable.getAttribute('wire:id')).call('render');
    });
});
</script>



@include('common.modalFooter')