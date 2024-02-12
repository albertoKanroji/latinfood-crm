<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Historial de compras</title>
    <style type="text/css">
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            font-size: 24px;
            font-weight: bold;
            color: #f90;
            margin-bottom: 20px;
        }

        .card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .customer-profile {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .customer-profile img {
            border-radius: 50%;
            height: 100px;
            width: 100px;
        }

        .customer-details h5 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .customer-details ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .customer-details li {
            margin-bottom: 10px;
        }

        .customer-details li strong {
            font-weight: bold;
            margin-right: 5px;
        }

        .alert-info {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f90;
            color: #fff;
            font-weight: bold;
        }

        .table td {
            word-break: break-word;
            max-width: 200px;
        }

        .total-gastado {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #f90;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">K&D LatinFood</div>
        <div class="card">
            <div class="customer-profile">
                <img src="{{ asset('storage/customers/' . $cliente->image ) }}" alt="imagen de ejemplo">
                <div class="customer-details">
                    <h5>Client Data:</h5>
                    <ul>
                        <li><strong>Name:</strong> {{ $cliente->name }}</li>
                        <li><strong>Last Name:</strong> {{ $cliente->last_name }}</li>
                        <li><strong>Mother's Last Name:</strong> {{ $cliente->last_name2 }}</li>
                        <li><strong>Email:</strong> {{ $cliente->email }}</li>
                        <li><strong>Number Phone:</strong> {{ $cliente->phone }}</li>
                        <li><strong>Address:</strong> {{ $cliente->address }}</li>
                        <li><strong>Balance:</strong> ${{ $cliente->saldo }}</li>
                    </ul>
                </div>
            </div>

            @if ($ventas->isEmpty())
            <div class="alert alert-info">No ha realizado ninguna compra.</div>
            @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Items</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalGastado = 0;
                    @endphp
                    @foreach ($ventas as $venta)
                    @foreach ($venta->salesDetails as $detalle)
                    <tr>
                        <td>{{ $detalle->created_at }}</td>
                        <td>{{ $detalle->product->barcode }}</td>
                        <td>{{ $detalle->product->name }}</td>
                        <td>${{ $detalle->product->price }}</td>
                        <td>{{ $detalle->product->category->name }}</td>
                        <td>{{ $detalle->quantity }}</td>
                        <td>${{ $detalle->price * $detalle->quantity }}</td>
                    </tr>
                    @php
                    $totalGastado += $detalle->price * $detalle->quantity;
                    @endphp
                    @endforeach
                    @endforeach
                </tbody>
            </table>
            <div class="total-gastado">Total Spent: ${{ $totalGastado }} USD</div>
            @endif
        </div>
    </div>
</body>

</html>