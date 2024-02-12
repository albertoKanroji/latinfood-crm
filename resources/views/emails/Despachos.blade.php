<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Preparation Notification</title>
    <style>
        body {
            background-color: #F39022;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
        }

        .logo {
            width: 150px;
            height: auto;
        }


        .notification {
            text-align: center;
            margin-bottom: 40px;
        }

        .notification h1 {
            font-size: 24px;
            color: #F39022;
            margin-bottom: 10px;
        }

        .notification p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .details {
            margin-bottom: 40px;
        }

        .details h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #F39022;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .details th,
        .details td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .footer {
            text-align: center;
            color: #777;
        }

        .footer p {
            font-size: 14px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img class="logo" src="https://firebasestorage.googleapis.com/v0/b/latin-food-8635c.appspot.com/o/splash%2FlogoAnimadoNaranjaLoop.gif?alt=media&token=0f2cb2ee-718b-492c-8448-359705b01923" width="400" height="341" autoplay="true" loop="true">
        </div>

        <div class="notification">
            <h1>Product Preparation in Progress!</h1>
            <p>Dear <span style="color: #F39022; font-weight: bold;">{{ $cliente->name }}</span>,</p>
            <p>We want to inform the preparation of your products has started.</p>
            <p>They will soon be ready to be dispatched and shipped to your address.</p>
        </div>

        <div class="details">
            <h2>Order Details:</h2>
            <p>Order Number: <span style="color: #F39022;">{{ $sale->id }}</span></p>
            <p>Dispatch Date: <span style="color: #F39022;">{{ $envio->created_at }}</span></p>

            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalGasto = 0;
                    @endphp
                    @foreach ($detallesPedido as $detalle)
                    <tr>
                        <td>{{ $detalle->product }}</td>
                        <td>{{ $detalle->quantity }}</td>
                        <td>${{ $detalle->price }}</td>
                    </tr>
                    @php
                    $totalGasto += $detalle->price*$detalle->quantity;
                    @endphp
                    @endforeach
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: bold;">Total Expense:</td>
                        <td style="color: #F39022; font-weight: bold;">${{ $totalGasto }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>If you have any questions or need further information, please feel free to contact us.</p>
        </div>
    </div>
</body>

</html>