<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detalles de la compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #f39022;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f39022;
            color: #ffffff;
        }

        tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        .total {
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
            text-align: right;
            color: #f39022;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #777777;
        }

        .footer p {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sale Details</h2>

        <p>Mr.Mss {{ $customer->name }},</p>

        <p>Thank you for purchase, next you will find the shop:</p>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Ammount</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ $item->price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Total: ${{ $sale->total }}</p>

        <p>Thank you for buy us.</p>

        <div class="footer">
            <p>Best Regards,</p>
            <p>Sales represent K/D Latin Food Inc</p>
        </div>
    </div>
</body>
</html>
