<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Estilos CSS para el correo electrónico */
        body {
            font-family: Arial, sans-serif;
            background-color: #F8F8F8;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 150px;
            height: auto;
        }

        h1 {
            color: #F39022;
            font-size: 24px;
            margin-top: 30px;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            margin-bottom: 10px;
            color: #333333;
        }

        .text-center {
            text-align: center;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #F39022;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }

        .button:hover {
            background-color: #E17C00;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img class="logo" src="https://firebasestorage.googleapis.com/v0/b/latin-food-8635c.appspot.com/o/splash%2FlogoAnimadoNaranjaLoop.gif?alt=media&token=0f2cb2ee-718b-492c-8448-359705b01923" width="400" height="341" autoplay="true" loop="true">

        </div>
        <h1>Your Order Has Been Delivered!</h1>
        <p>Dear {{ $sale->customer->name }},</p>
        <p>We're delighted to inform you that your order has been successfully delivered. We hope you're satisfied with your purchase. Here are the details:</p>
        <p>Order Number: {{ $sale->id }}</p>
        <p>Delivery Date: {{ $sale->created_at }}</p>
        <p>If you have any questions or feedback regarding your order, please feel free to reach out to us. We value your satisfaction and would love to hear from you.</p>
        <div class="text-center">
            <a href="#" class="button">Leave a Review</a>
        </div>
        <div class="footer">
            <p>Thank you for choosing K&D LatinFood!</p>
        </div>
    </div>
</body>

</html>