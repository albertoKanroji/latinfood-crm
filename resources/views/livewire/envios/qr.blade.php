<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<div wire:ignore.self class="modal fade" id="modalQR" tabindex="-1" role="dialog"  style="backdrop-filter: blur(10px);" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white">
          <b>Scan QR</b>
        </h5>
        <h6 class="text-center text-warning" wire:loading>Please Wait</h6>
      </div>
      <div class="modal-body">
         @if ($envio)
        <!-- Mostrar los atributos de Envio -->
        <p>ID de Venta: {{ $envio->id_sale }}</p>
      
        <!-- Otros atributos de Envio que desees mostrar -->
    @else
        <!-- Manejo del caso en que no se encuentre ningún Envio -->
        <p>Nobody reg for this sale.</p>
    @endif

    <div><p>Lotes a scanear: </p>
        @if ($saleDetails)
            @foreach ($saleDetails as $detail)
                @php
                    $lot = App\Models\Lotes::find($detail->lot_id);
                @endphp
               
                @if ($lot)
                    {{ $lot->CodigoBarras }}<br>
                @else
                    <p>Código de Barras: No encontrado</p>
                @endif
                <!-- Otros atributos de los detalles de venta que desees mostrar -->
            @endforeach
        @else
            <p>No se encontraron detalles de venta para esta venta.</p>
        @endif
    </div>
       
    </div>
     
      <div class="modal-footer">
        <div class="btn-group btn-group-toggle mb-5" data-toggle="buttons">
  
</div>
        <button type="button" class="btn btn-dark close-btn text-info" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="checkButton">Check</button>
      </div>
    </div>
  </div>

</div>
