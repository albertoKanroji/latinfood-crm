<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white">
        	<b>{{$componentName}}</b> | {{ $selected_id > 0 ? 'EDITAR' : 'CREAR' }}
        </h5>
        <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col-sm-12">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <span class="fas fa-edit">

                  </span>
                </span>
              </div>
              <input type="text" wire:model.lazy="permissionName" class="form-control" placeholder="ej: Category_Index" maxlength="255">
            </div>
            @error('permissionName') <span class="text-danger er">{{ $message }}</span> @enderror
          </div>
        </div>


      </div>
      <div class="modal-footer">

        <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info" data-dismiss="modal">Close</button>

        @if($selected_id < 1)
        <button type="button" wire:click.prevent="CreatePermission()" class="btn btn-dark close-modal" >Save</button>
        @else
        <button type="button" wire:click.prevent="UpdatePermission()" class="btn btn-dark close-modal" >Update</button>
        @endif


      </div>
    </div>
  </div>
</div>