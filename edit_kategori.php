<?php
// FILE: edit_kategori.php
session_start();
require_once 'koneksi.php';    
require_once 'function.php';
checkLogin(); 

$username_login = $_SESSION['username'];
$message = null; 

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: kategori.php");
    exit();
}

$id_kategori = $_GET['id'];
$kategori = getKategoriById($pdo, $id_kategori);

if (!$kategori) {
    header("Location: kategori.php?msg=" . urlencode('Data kategori tidak ditemukan!') . "&status=error");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_kategori_baru = $_POST['nama_kategori'];
    $result = updateKategori($pdo, $id_kategori, $nama_kategori_baru);
    
    if ($result['status'] == 'success') {
        header("Location: kategori.php?msg=" . urlencode($result['message']) . "&status=" . $result['status']);
        exit();
    }
    
    $message = $result;
    $kategori = getKategoriById($pdo, $id_kategori); 
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SIMBS | Ubah Kategori</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="preload" href="dist/css/adminlte.css" as="style" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      crossorigin="anonymous" media="print" onload="this.media='all'"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />
    </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <?php include 'navbar_sidebar.php'; // Asumsi Anda membuat file include untuk Navbar/Sidebar ?>

      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-3">Ubah Data Kategori</h3>
              </div>
              <div class="col-sm-6 d-flex flex-column align-items-end">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item"><a href="kategori.php">Data Kategori</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Ubah Data</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-6">
                <div class="card card-warning">
                  <div class="card-header">
                    <h3 class="card-title">Form Ubah Kategori</h3>
                  </div>
                  <form method="POST" action="edit_kategori.php?id=<?= $id_kategori; ?>">
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-<?= ($message['status'] == 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message['message']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <input type="hidden" name="id_kategori" value="<?= htmlspecialchars($kategori['id_kategori']); ?>">

                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control" 
                                   value="<?= htmlspecialchars($kategori['nama_kategori']); ?>" required>
                        </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                      <a href="kategori.php" class="btn btn-secondary">Batal</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
      <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Bismillah Tugas Promnet</div>
        <strong>Copyright &copy; 2025&nbsp;</strong>
      </footer>
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
    <script src="dist/js/adminlte.js"></script>
  </body>
</html>
