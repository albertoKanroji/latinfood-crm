<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Detalle del Lote {{$data->CodigoBarras}} ,{{$data->Cantidad_Articulos}} Unidades</title>

	<!-- cargar a través de la url del sistema -->
	<!--
		<link rel="stylesheet" href="{{ asset('css/custom_pdf.css') }}">
		<link rel="stylesheet" href="{{ asset('css/custom_page.css') }}">
	-->
	<!-- ruta física relativa OS -->


	<style type="text/css">
		body {
			/*color: #ff5100;*/
			font-family: 'Montserrat', sans-serif;
		}

		.contenedor {
			display: flex;
			font-size: 25px;
		}

		.derecha {
			width: 50%;
			float: right;

		}

		ul {
			list-style-type: none;
		}

		.izquierda {
			width: 50%;
			float: left;
			padding-top: 50px;
			padding-left: 150px;
		}
	</style>

</head>

<body>
	<div class="contenedor">
		<div class="izquierda">
			<img src="data:image/png;base64, {!! base64_encode($qr) !!} ">

		</div>
		<div class="derecha">
			<ul>
				<li>Qty: {{$prod->stock}} </li>
				<li>Exp: {{ \Carbon\Carbon::parse( $data->Fecha_Vencimiento)->format('M-d-y')}}</li>
				<li>SKU:{{$prod->barcode}} </li>
				<li>Name:{{$prod->name}} </li>
				<br>
				<br>
				<li>{{$hora}}</li>
			</ul>

		</div>

	</div>
</body>

</html>