<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us- CineBook</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: url('assets/img/background.jpg') no-repeat bottom center;
      background-size: cover;
      position: relative;
    }

    /* Dark overlay */
    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background-color: rgba(0,0,0,0.6);
      z-index: 0;
    }

    /* Center container */
    .login-container {
      position: relative;
      z-index: 10;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* Login card */
    .login-card {
      backdrop-filter: blur(10px);
      background-color: rgba(0,0,0,0.6);
      border-radius: 1rem;
      padding: 1rem;
      width: 90%;
      max-width: 400px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.5);
      color: white;
    }


    .btn-login {
      background-color: #e50914;
      border: none;
      transition: background 0.3s;
      width: 100%;
    }

    .btn-login:hover {
      background-color: #f6121d;
    }

    a {
      color: #fff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<!-- Centered Login -->
<div class="login-container flex flex-col">
    <div class="mt-5 login-card text-center">
        <nav class="flex justify-between items-center px-6 py-4 relative z-10">
            <span>
                <img src="assets/img/logo1.png" alt="Logo" width="70">
            </span>
        </nav>

        <h2 class="fw-bold mb-4 mt-3">About us</h2>
        <pre>|| Created with <span class="text-red-500">♥</span> By Sumit Basak ||</pre>

        <p>
            Email: abc@gmail.com <br>
            Phone: 1234567890<br>
            || Enjoy seamless booking and latest releases ||
        </p>
        <button id="backB" class="mt-2 btn btn-login font-stretch-150%" name="backButton">Go Back</button>
    </div>
</div>

<script>
    document.getElementById("backB").addEventListener("click", function () {
        window.history.back();
    });
</script>

  <!-- Bootstrap JS -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>