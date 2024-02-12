<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bienvenido a KD LatinFood</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            margin: 0;
            margin-bottom: 10px;
        }

        p {
            margin: 0;
            margin-bottom: 20px;
        }

        .highlight {
            color: #ff6600;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome, {{ $name }} {{ $last_name }} {{ $last_name2 }}</h1>
        <p>Thank you for Sign up on our App, next you will find the details about your account:</p>

        <ul>
            <li><strong>Name:</strong> {{ $name }} {{ $last_name }} {{ $last_name2 }}</li>
            <li><strong>Email:</strong> {{ $email }}</li>
            <li><strong>Phone:</strong> {{ $phone }}</li>
            <li><strong>Address:</strong> {{ $address }}</li>
            <li><strong>Available Wallet:</strong>$ {{ $saldo }} USD</li>
        </ul>

        <p>it's a pleasure take your on our community, if you have some questions please contact us!.</p>

        <p>Regards,<br>
            K&D Latin Food Technology Team</p>
    </div>

    <div class="footer">
        This Email was send to{{ $email }}. If you don't wanna receive more Emails, please contact us at softwarelab@oyarcegroup.com
    </div>
</body>

</html>