<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<div>
      <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js" rel="nofollow"></script>
      <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <div wire:ignore.self class="modal fade" id="modalScan" tabindex="-1" role="dialog" aria-labelledby="modalScanLabel" aria-hidden="true" data-venta-id="{{ $ventaId }}" style="backdrop-filter: blur(10px);">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalScanLabel">Venta # {{ $ventaId }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <video id="preview"></video>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" title="validate" id="validateButton" disabled>VALIDAR</button>
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
<style>
#preview{
   width:500px;
   height: 500px;
   margin:0px auto;
}
</style>
 
 
    <script type="text/javascript">
        document.addEventListener('livewire:load', function () {
            Livewire.on('abrirModal', function () {
                $('#modalScan').modal('show');
                console.log('iniciando funcion qr')
               
            });

        });
    </script>
     

  <script type="text/javascript">


$(document).ready(function()
{

          var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
    scanner.addListener('scan',function(content){
        alert(content);
        //window.location.href=content;
    });
    Instascan.Camera.getCameras().then(function (cameras){
        if(cameras.length>0){
            scanner.start(cameras[0]);
            $('[name="options"]').on('change',function(){
                if($(this).val()==1){
                    if(cameras[0]!=""){
                        scanner.start(cameras[0]);
                    }else{
                        alert('No Front camera found!');
                    }
                }else if($(this).val()==2){
                    if(cameras[1]!=""){
                        scanner.start(cameras[1]);
                    }else{
                        alert('No Back camera found!');
                    }
                }
            });
        }else{
            console.error('No cameras found.');
            alert('No cameras found.');
        }
    }).catch(function(e){
        console.error(e);
        alert(e);
    });
});

  </script>

</div>