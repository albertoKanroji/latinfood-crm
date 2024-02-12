<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<div wire:ignore.self class="modal fade" id="modalDetails" tabindex="-1" role="dialog" style="backdrop-filter: blur(10px);">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white">
        	<b>Sale Detail </b>
        </h5>
        <h6 class="text-center text-warning" wire:loading>PLEASE WAIT</h6>
      </div>
      <div class="modal-body">

        <div class="table-responsive">
          <table class="table table-bordered table striped mt-1">
            <thead class="text-white" style="background: #FF5100">
              <tr>
               <th class="table-th text-white text-center">FOLIO</th>
                 <th class="table-th text-white text-center">SKU</th>
                <th class="table-th text-white text-center">PRODUCT</th>
                <th class="table-th text-white text-center">PRICE</th>
                <th class="table-th text-white text-center">QTY</th>
                <th class="table-th text-white text-center">AMOUNT</th>
              </tr>
            </thead>
            <tbody>
              @foreach($details as $d)
              <tr>
                <td class='text-center'><h6>{{$d->id}}</h6></td>
                 <td class='text-center'>
                <h6>{{$d->barcode}}</h6>
            </td>
                <td class='text-center'><h6>{{$d->product}}</h6></td>
                <td class='text-center'><h6>{{number_format($d->price,2)}}</h6></td>
                <td class='text-center'><h6>{{number_format($d->quantity,0)}}</h6></td>
                <td class='text-center'><h6>{{number_format($d->price * $d->quantity,2)}}</h6></td>               
                
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3"><h5 class="text-center font-weight-bold">TOTALS</h5></td>
                <td><h5 class="text-center">{{$countDetails}}</h5></td>
                <td><h5 class="text-center">
                  ${{number_format($sumDetails,2)}}
                </h5></td>
              </tr>
            </tfoot>
          </table>         
        </div>

        


      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-dark close-btn text-info" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>