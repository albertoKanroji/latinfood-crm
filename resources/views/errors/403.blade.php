<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Error 403 - You don't have permission to access this resource. | Please contact softwarelab@oyarcegroup.com</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap');

    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #ecf0f3;
      color: #222;
      margin: 0;
      padding: 0;
    }

    .error-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 80px 40px;
      text-align: center;
      background-color: #fff;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    }

    .error-title {
      font-size: 60px;
      font-weight: 600;
      margin-bottom: 30px;
    }

    .error-message {
      font-size: 28px;
      font-weight: 500;
      margin-bottom: 40px;
    }

    .error-contact {
      font-size: 24px;
      font-weight: 500;
    }

    .error-contact a {
      color: #3498db;
      text-decoration: none;
      transition: color 0.2s ease-in-out;
    }

    .error-contact a:hover {
      color: #2980b9;
    }

    .error-back {
      display: inline-block;
      margin-top: 60px;
      padding: 15px 40px;
      font-size: 20px;
      font-weight: 500;
      color: #fff;
      background-color: #3498db;
      border: none;
      border-radius: 5px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
      transition: background-color 0.2s ease-in-out;
      text-decoration: none;
    }

    .error-back:hover {
      background-color: #2980b9;
    }

    /* Animaciones */
    .error-title,
    .error-message,
    .error-contact {
      opacity: 0;
      transform: translateY(50px);
      transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
    }

    .error-title {
      transition-delay: 0.1s;
    }

    .error-message {
      transition-delay: 0.2s;
    }

    .error-contact {
      transition-delay: 0.3s;
    }

    .error-title.show,
    .error-message.show,
    .error-contact.show {
      opacity: 1;
      transform: translateY(0);
    }

    @media only screen and (max-width: 768px) {
      .error-container {
        padding: 60px 20px;
      }

      .error-title {
        font-size: 40px;
        margin-bottom: 20px;
      }

      .error-message {
        font-size: 22px;
        margin-bottom: 30px;
      }

      .error-contact {
        font-size: 20px;
      }

      .error-back {
        margin-top: 40px;
        padding: 10px 30px;
        font-size: 18px;
      }
    }
  </style>
</head>
<body>
  <div class="error-container">
    <h1 class="error-title">Oops! 403 - Forbidden</h1>
    <p class="error-message">Apologies, you don't have permission to access this resource.</p>
    <p class="error-contact">Please contact K&D Latin Food, Inc Technology Department:<br>
      <br>
      <a href="mailto:Softwarelab@oyarcegroup.com?subject=403 - Forbidden&body=Describa el Error">Softwarelab@oyarcegroup.com</a>
    </p>
    <a href="{{url()->previous()}}" class="error-back">Back</a>
  </div>
  <script>
    // Agrega la clase "show" a los elementos para animar su aparición
    document.querySelectorAll('.error-title, .error-message, .error-contact').forEach(element => {
      element.classList.add('show');
    });

    var horaActual = new Date().toLocaleTimeString();
    var link = document.getElementsByTagName("a")[0];
    link.href += horaActual;
  </script>
</body>
</html>
