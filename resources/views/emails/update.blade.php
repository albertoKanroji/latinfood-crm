<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Notification: Account Modification</title>
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
        <h1>Notification: Account Update</h1>
        <p>Dear {{ $name }},</p>

        <p>We will inform some updates have made on your account, you can see details:</p>

        <ul>
            <li><strong>Name:</strong> {{ $name }}</li>
            <li><strong>Last Name:</strong> {{ $last_name }}</li>
            <li><strong>Phone:</strong> {{ $phone }}</li>
            <li><strong>Address:</strong> {{ $address }}</li>

        </ul>

        <p>If you dont recognized this updates, you can take the next actions:</p>

        <ol>
            <li>Login to your account and see the updates.</li>
            <li>Change your password inmediately and report to KyD latin Food Tech Area.</li>
            <li>Contact Us for more support.</li>
        </ol>

        <p>Plese take advise that we take seriously your information and all politicy privacy.</p>

        <p>If you have some questions please contac our team softwarelab@oyarcegroup.com</p>

        <p>Regards,<br>
            K&D Latin Food Technology Team.</p>
    </div>

    <div class="footer">
        This Message was send to {{ $email }}. if you don't made any changes please contact us inmmediately at softwarelabw@oyarcegroup.com
    </div>
</body>

</html>