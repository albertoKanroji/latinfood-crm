
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
     
          </table>         
        </div>

        


      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-dark close-btn text-info" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>

