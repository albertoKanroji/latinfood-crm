<div wire:ignore.self class="modal fade" id="Edit" tabindex="-1" role="dialog" style="backdrop-filter: blur(10px);">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>Shop Cart #{{$saleId}} Edit</b>
                </h5>

                <h6 class="text-center text-warning" wire:loading>PLEASE WAIT</h6>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="updateSale">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #FF5100">
                            <tr>
                                <th class="table-th text-white text-center">FOLIO</th>
                                <th class="table-th text-white text-center">SKU</th>
                                <th class="table-th text-white text-center">PRODUCT</th>
                                <th class="table-th text-white text-center">QTY</th>
                                <th class="table-th text-white text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $key => $d)
                            <tr>
                                @php
                                $productData = json_decode($d->product, true);
                                @endphp
                                <td class='text-center'>
                                    <h6>{{$productData['id'] ?? 'N/A'}}</h6>
                                </td>
                                <td class='text-center'>
                                    <h6>{{$productData['barcode'] ?? 'N/A'}}</h6>
                                </td>
                                <td class='text-center'>
                                    <h6>{{$productData['name'] ?? 'N/A'}}</h6>
                                </td>
                                <td class='text-center'>
                                    <input type="number" wire:model="quantities.{{$key}}" class="form-control">
                                </td>
                                <td class='text-center'>
                                    <button type="button" onclick="removeProduct({{$key}})"
                                        class="btn btn-danger btn-sm">Remove</button>
                                </td>
                            </tr>
                            @endforeach

                            <!-- Agregar nueva fila para productos -->
                            @if ($addProduct)
                            <tr>
                                <td class='text-center'>
                                    <button type="button" wire:click.prevent="addProductRow"
                                        class="btn btn-success btn-sm">Add</button>
                                </td>
                                <td class='text-center'>
                                    <select wire:model="newProducts.sku" class="form-control" id="selectSku">
                                        <option value="">Seleccione SKU</option>
                                        @foreach ($prod as $product)
                                        <option value="{{ $product->barcode }}">{{ $product->barcode }}
                                            {{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class='text-center'>
                                    <input type="text" wire:model="newProducts.name" class="form-control"
                                        id="productName">
                                </td>
                                <td class='text-center'>
                                    <input type="number" wire:model="newProducts.items" class="form-control">
                                </td>
                                <td class='text-center'>
                                    <button type="button" wire:click.prevent="removeNewProduct"
                                        class="btn btn-danger btn-sm">Remove</button>
                                </td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                    <button type="button" wire:click.prevent="toggleAddProduct" class="btn btn-success">Add
                        Product</button>
                    <button type="submit" wire:click.prevent="updateSale" class="btn btn-primary">Update Sale</button>

                </form>
            </div>
            <div class="modal-footer">
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
    <script>
    function removeProduct(key) {
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


                // Emitir el evento 'CargarPedido' con el ID del pedido
                window.livewire.emit('removeProduct', key);

            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('hidden.bs.modal', msg => {
            $('.er').css('display', 'none')
        });

    })
    </script>
</div>