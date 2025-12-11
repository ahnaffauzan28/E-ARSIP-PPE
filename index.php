<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>E-ARSIP Login</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/sign-in.css">

    <style>
      /* ===== BACKGROUND + OVERLAY ===== */
      body {
        background: url("assets/Home-ESG.jpg") no-repeat center center fixed;
        background-size: cover;
        position: relative;
      }

      body::before {
        content: "";
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5); /* lebih pudar */
        z-index: -1;
      }

      /* ===== CARD LOGIN (glass look) ===== */
      .login-card {
        background: rgba(255,255,255,0.08);
        border-radius: 18px;
        padding: 40px 35px;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.18);
        box-shadow: 0 8px 25px rgba(0,0,0,0.25);
      }

      /* ===== INPUT FIELD ===== */
      .form-control {
        background: rgba(255,255,255,0.15) !important;
        border: 1px solid rgba(255,255,255,0.25);
        color: #fff !important;
      }

      .form-control:focus {
        border-color: #ff9100;
        box-shadow: 0 0 6px #ff9100;
        background: rgba(255,255,255,0.25) !important;
      }

      label {
        color: #fff !important;
        font-weight: 500;
      }

      /* ===== BUTTON LOGIN ===== */
      .btn-pkt {
        background: linear-gradient(90deg, #004aad, #ff8c00);
        border: none;
        color: white;
        font-weight: bold;
        letter-spacing: 0.5px;
        transition: 0.3s;
      }

      .btn-pkt:hover {
        opacity: 0.9;
        transform: translateY(-2px);
      }

      /* ===== LINE DEKORASI ===== */
      .line-pkt {
        width: 120px;
        height: 4px;
        background: #ff8c00;
        margin: 10px auto 25px;
        border-radius: 20px;
      }

      /* ===== FOOTER ===== */
      footer p {
        color: white;
        opacity: 0.75;
      }
    </style>
  </head>
  

  <body class="d-flex align-items-center py-4">

    <main class="form-signin w-100 m-auto" style="max-width: 420px;">
      <form method="post" action="ceklogin.php" class="login-card">

        <img 
          src="assets/PKT.png"
          class="d-block mx-auto mb-4"
          style="max-width: 250px; width: 100%; height:auto;"
        />

        <h1 class="h5 fw-bold text-center text-white">
          Selamat Datang di E-ARSIP|PPE
        </h1>
        <div class="line-pkt"></div>

        <div class="form-floating mb-3">
          <input name="username" type="text" class="form-control" id="username" placeholder="username">
          <label for="username">Username</label>
        </div>

        <div class="form-floating mb-3">
          <input name="password" type="password" class="form-control" id="Password" placeholder="Password">
          <label for="Password">Password</label>
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault">
          <label class="form-check-label" for="checkDefault">Remember me</label>
        </div>

        <button class="btn btn-pkt w-100 py-2" type="submit">Sign In</button>
      </form>

      <footer class="footer mt-4 text-center fixed-bottom pb-3">
        <p>&copy; 2025–<?=date('Y')?> Mahnafz with "Ngodingpintar"</p>
      </footer>
    </main>

    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>