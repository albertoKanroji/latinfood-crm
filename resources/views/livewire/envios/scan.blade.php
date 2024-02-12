<div class="modal fade" id="modalScan" tabindex="-1" role="dialog" style="backdrop-filter: blur(10px);">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>Scan QR </b>


                </h5>
                <h6 class="text-center text-warning" wire:loading>Please Wait</h6>
            </div>
            <div class="modal-body text-center">
                <video id="preview"></video>
                <div class="loading-overlay">
                    <div class="loading-spinner"></div>
                </div>
            </div>
            <button class="btn btn-success custom-input hidden-button" title="validate" id="CargarQR">Load
                Barcode</button>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="modal-footer justify-content-center">
                <div class="btn-group btn-group-toggle mb-5" data-toggle="buttons">
                    <label class="btn btn-primary active">
                        <input type="radio" name="options" value="1" autocomplete="off" checked> Front Camera
                    </label>
                    <label class="btn btn-secondary">
                        <input type="radio" name="options" value="2" autocomplete="off"> Back Camera
                    </label>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <input class="form-control custom-input" type="text" id="scanResult" disabled>
                <button class="btn btn-success hidden-button" title="validate" id="validateButton"
                    disabled>Check</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark close-btn text-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
.custom-input {
    max-width: 200px;
    /* Ajusta el valor según tu preferencia */
}

.hidden-button {
    display: none;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-left-color: #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>




<script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js" rel="nofollow"></script>


<script type="text/javascript">
var ventaId;

$(document).ready(function() {
    $('.open-scan-btn').on('click', function() {
        ventaId = $(this).data('sale-id');
        console.log(ventaId);

        // Abre el modal para ingresar la firma
        $('#modalScan').modal('show');

    });

    var scanner; // Variable para almacenar la instancia del escáner

    $('#modalScan').on('shown.bs.modal', function() {
        // Muestra la animación de carga
        $('#modalScan .modal-body').append(
            '<div class="loading-overlay"><div class="loading-spinner"></div></div>');

        scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            scanPeriod: 5,
            mirror: true
        });
        scanner.addListener('scan', function(content) {
            // Muestra el contenido escaneado en el input
            $('#scanResult').val(content);

            // Ejecuta la acción de validación
            $('#validateButton').trigger('click');
        });

        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
                $('[name="options"]').on('change', function() {
                    if ($(this).val() == 1) {
                        if (cameras[0] != "") {
                            scanner.start(cameras[0]);
                        } else {
                            alert('No Front camera found!');
                        }
                    } else if ($(this).val() == 2) {
                        if (cameras[1] != "") {
                            scanner.start(cameras[1]);
                        } else {
                            alert('No Back camera found!');
                        }
                    }
                });
            } else {
                console.error('No cameras found.');
                alert('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
            alert(e);
        });
    });

    $('#CargarQR').on('click', function() {
        swal({
            title: 'Success',
            text: 'Barcodes Load Success',
            type: 'success',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        });
        $('#validateButton').prop('disabled', false); // Habilitar el botón de validación
        $('#scanResult').prop('disabled', false); // Habilitar el input
    });

    $('#modalScan').on('hidden.bs.modal', function() {
        // Detiene la captura de video y limpia los recursos relacionados con la cámara
        $('#scanResult').val('');
        $('#validateButton').prop('disabled', false);
        scanner.stop();
    });

    $('#preview').on('canplay', function() {
        // Oculta la animación de carga cuando la cámara está activa y la imagen de vista previa es visible
        $('.loading-overlay').remove();
    });

    $('#validateButton').on('click', function() {
        var scanResult = $('#scanResult').val(); // Obtener el contenido escaneado del input
        console.log(scanResult);

        fetch('/intranet/public/Busc/' + scanResult + '/' + ventaId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    qrCode: scanResult,
                    ventaId: ventaId
                })
            })
            .then(function(response) {
                if (response.ok) {
                    // Si la respuesta es exitosa, mostrar el resultado por consola
                    return response.json();
                } else {
                    // Si hay un error, mostrar el mensaje de error por consola
                    throw new Error('Error en la petición AJAX');
                }
            })
            .then(function(data) {
                swal(data.message, "success").then(function() {
                    // Verificar si se insertaron todos los códigos de barras y se actualizó el estado de la compra
                    if (data.message === "All Codebars are inserted") {
                        // Mostrar el swal de carga antes de actualizar el estado de la compra
                        swal({
                            title: "Loading...",
                            text: "Update Delivery Status...",
                            buttons: false,
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                            timer: 2000 // Duración en milisegundos
                        });

                        // Actualizar el estado de la compra y enviar el correo electrónico
                        fetch('/intranet/public/update-actual/' + ventaId, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content'),
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    ventaId: ventaId
                                })
                            })
                            .then(function(response) {
                                if (response.ok) {
                                    // Si la actualización es exitosa, refrescar la página después de 2 segundos
                                    setTimeout(function() {
                                        location
                                    .reload(); // Refrescar la página
                                    }, 2000);
                                } else {
                                    throw new Error(
                                        'Error en la petición AJAX de actualización de estado de la compra'
                                        );
                                }
                            })
                            .catch(function(error) {
                                console.error(error);
                            });
                    }
                });
            })
            .catch(function(error) {
                // Error en la petición
                console.error(error);
            });
    });

    $('#scanResult').val('');
});
</script>