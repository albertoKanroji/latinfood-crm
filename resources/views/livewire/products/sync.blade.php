<!DOCTYPE html>
<html>
<head>
    <title>Sincronización de productos</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <h1>Sincronización de productos</h1>

    <p>Productos totales: {{ $totalProducts }}</p>
    <p>Productos subidos exitosamente: {{ $successCount }}</p>

    <div id="progress-bar" style="width: 0%; background-color: lightblue; height: 20px;"></div>

    <script>
        $(document).ready(function() {
            var totalProducts = {{ $totalProducts }};
            var successCount = {{ $successCount }};
            var progressBar = $('#progress-bar');

            // Actualizar la barra de progreso
            function updateProgressBar() {
                var progress = (successCount / totalProducts) * 100;
                progressBar.width(progress + '%');

                if (successCount === totalProducts) {
                    alert('¡Sincronización completada!');
                }
            }

            // Llamar a la función para actualizar la barra de progreso inicialmente
            updateProgressBar();

            // Llamada AJAX para sincronizar los productos
            $.ajax({
                url: '{{ route('products.sync') }}',
                method: 'POST',
                beforeSend: function() {
                    // Mostrar mensaje de carga
                    alert('Cargando productos...');
                },
                success: function(data) {
                    // Actualizar la variable de éxito y la barra de progreso
                    successCount = data.successCount;
                    updateProgressBar();
                },
                error: function() {
                    alert('Ocurrió un error durante la sincronización.');
                },
                complete: function() {
                    // Ocultar mensaje de carga
                    alert('Sincronización completada.');
                }
            });
        });
    </script>
</body>
</html>



