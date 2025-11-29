<?php
// FILE: register.php
session_start();
require_once 'koneksi.php';    
require_once 'function.php'; 

$message = null; 
$username = '';
$email = '';

// Logika pemrosesan form Register
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // BARIS 18: Lokasi Error Anda
    // Cek panjang password (Min 8 Karakter)
    if (strlen($password) < 8) {
        $message = ['status' => 'error', 'message' => 'password harus mengandung minimal 8 karakter'];
    } else {
        // Panggil fungsi registerUser (melakukan duplikasi check dan insert DB)
        $result = registerUser($pdo, $username, $email, $password);
        
        if ($result['status'] == 'success') {
            // Jika berhasil, arahkan ke halaman login
            header("Location: login.php?msg=" . urlencode("Registrasi berhasil. Silakan Login.") . "&status=success");
            exit();
        } else {
            // Jika gagal (duplikasi username/email)
            $message = ['status' => 'error', 'message' => 'username atau email sudah terdaftar, gunakan yang lain'];
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SIMBS | Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      crossorigin="anonymous" media="print" onload="this.media='all'"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="dist/css/adminlte.min.css" /> 
  </head>
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>SIMBS</b> Register</a>
      </div>
      <div class="card card-outline card-primary">
        <div class="card-header">
          <p class="login-box-msg">Daftar Akun Baru</p>
        </div>
        <div class="card-body">
          
          <?php if ($message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <form action="register.php" method="post">
            <div class="input-group mb-3">
              <input type="text" name="username" class="form-control" placeholder="Username" required value="<?= htmlspecialchars($username); ?>">
              <div class="input-group-text">
                <i class="bi bi-person"></i>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="email" name="email" class="form-control" placeholder="Email" required value="<?= htmlspecialchars($email); ?>">
              <div class="input-group-text">
                <i class="bi bi-envelope"></i>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control" placeholder="Password (Min 8 Karakter)" required>
              <div class="input-group-text">
                <i class="bi bi-lock-fill"></i>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
              </div>
            </div>
          </form>

          <p class="mt-3 mb-1">
            <a href="login.php">Sudah punya akun? Login</a>
          </p>
        </div>
      </div>
    </div>
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
