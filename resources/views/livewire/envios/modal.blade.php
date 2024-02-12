<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog" style="backdrop-filter: blur(10px);">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content" >
      <div class="modal-header " style="background: #ff5100;" >
        <h5 class="modal-title text-white">
        	<b>{{$componentName}}</b> 
        </h5>
        <h6 class="text-center text-warning" wire:loading>PLEASE WAIT</h6>
      </div>
      <div class="modal-body">
<div class="col-sm-12 col-md-4">
	<div class="form-group">
		<label >QR</label>
		<input type="text"  class="form-control" placeholder="barcode" >
		
	</div>
</div>
<video id="preview"></video>
 </div>
      <div class="modal-footer">
        
        <button type="button"  class="btn btn-dark close-btn text-info" data-dismiss="modal">Close</button>

      
        <div class="btn-group btn-group-toggle mb-5" data-toggle="buttons">
          <label class="btn btn-primary active">
            <input type="radio" name="options" value="1" autocomplete="off" checked> Front Camera
          </label>
          <label class="btn btn-secondary">
            <input type="radio" name="options" value="2" autocomplete="off"> Back Camera
          </label>
        </div>
       


      </div>
    </div>
  </div>
</div>


