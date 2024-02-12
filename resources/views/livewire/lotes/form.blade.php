
     
<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog" style="backdrop-filter: blur(10px);">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content" >
      <div class="modal-header " style="background: #ff5100;" >
        <h5 class="modal-title text-white">
            <b>{{$componentName}}</b> | {{ $selected_id > 0 ? 'Edit' : 'Create' }}
        </h5>
       <h6 class="text-center text-warning" wire:loading>
  <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
    <circle cx="12" cy="12" r="10"></circle>
    <polyline points="12 6 12 12 16 14"></polyline>
  </svg>
  PLEASE WAIT
</h6>

      </div>
      <div class="modal-body">



          <div class="row">
            <div class="col-sm-12 col-md-4">
                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" wire:model.lazy="User" class="form-control" placeholder="{{$user}}" value="{{$user}}" readonly>
                    @error('User') <span class="text-danger er">{{ $message}}</span>@enderror
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="form-group">
                    <label>Fecha de Vencimiento</label>
                    <input type="date" wire:model.lazy="Fecha_Vencimiento" class="form-control" readonly>
                    @error('Fecha_Vencimiento') <span class="text-danger er">{{ $message}}</span>@enderror
                </div>
            </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<div class="col-sm-12 col-md-4">   
  <div class="form-group">
    <label>Sabor</label>
    <div class="select2-container">
      <select name="flavor" wire:model="flavor" class="form-control select2">
        <option value="Elegir" disabled>Elegir</option>
        @foreach($insumo as $sabor)
          <option value="{{ $sabor->id }}">{{ $sabor->nombre }}</option>
        @endforeach
      </select>
    </div>
    @error('flavor') <span class="text-danger er">{{ $message}}</span>@enderror
  </div>
</div>
<script type="text/javascript">
      $(document).ready(function() {
    $('.select2').select2();
  });
</script>

<div class="col-sm-12 col-md-4">   
  <div class="form-group">
    <label>Lote</label>
    <select name="lot" wire:model="lot" class="form-control">
            <option value="Elegir" disabled>Elegir</option>
      @foreach($lotes_insumo as $lote)
        <option value="{{ $lote->id }}" data-flavor-id="{{ $lote->idSabor }}">{{ $lote->CodigoBarras }} - {{ $lote->Fecha_Vencimiento }}</option>
      @endforeach
    </select>
    @error('lot') <span class="text-danger er">{{ $message}}</span>@enderror
  </div>
</div>





</div>
    <div id="subform-table">
          <h5 class="title text-center">
                <b></b>  Final Product
            </h5>
          <div class="row">
       

          <div class="form-group">
                        <label for="producto">Producto:</label>
                        <select wire:model="producto" class="form-control">
                               <option value="Elegir" disabled>Elegir</option>
      @foreach($prod as $prod)
        <option value="{{ $prod->id }}">{{ $prod->barcode }}-{{ $prod->name }}</option>
      @endforeach
                        </select>
                    </div>
                    <div class="form-group ml-3">
                        <label for="cantidad">Cantidad:</label>
                        <input wire:model="cantidad" type="number" class="form-control" min="1">
                    </div>
                </div>
            </div>
            
               
              

           
            @if ($productos)
            <table class="table">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody> 
        @foreach ($productos as $index => $producto)
            <tr>
                <td>{{ $producto['producto'] }}</td>
                <td>{{ $producto['cantidad'] }}</td>
                <td>
                    <button wire:click="eliminarProducto({{ $index }})" class="btn btn-danger">Eliminar</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif


    </div>
    </div>
           


 </div>
      <div class="modal-footer">
         <button wire:click.prevent="agregarProducto" class="btn btn-primary">Agregar</button>
     <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info" data-dismiss="modal">
  <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
    <circle cx="12" cy="12" r="10"></circle>
    <line x1="15" y1="9" x2="9" y2="15"></line>
    <line x1="9" y1="9" x2="15" y2="15"></line>
  </svg>
  Close
</button>


        @if($selected_id < 1)
       <button type="button"  onclick="guardarLotes()" class="btn btn-dark close-modal">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder">
    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
  </svg>
  Save
</button>

        @else
        <button type="button" wire:click.prevent="Update()" class="btn btn-dark close-modal">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
    <polyline points="22 4 12 14.01 9 11.01"></polyline>
  </svg>
  Update
</button>

        @endif


      </div>
    </div>
  


  
