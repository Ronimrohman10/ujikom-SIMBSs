<?php
// FILE: tambah_buku.php
session_start();
require_once 'koneksi.php';    
require_once 'function.php';
checkLogin(); 

$username_login = $_SESSION['username'];
$message = null; 
$data_kategori = ambilDataKategori($pdo); // Ambil kategori untuk dropdown

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = tambahBuku($pdo, $_POST, $_FILES['gambar']);
    
    if ($result['status'] == 'success') {
        header("Location: buku.php?msg=" . urlencode($result['message']) . "&status=" . $result['status']);
        exit();
    }
    
    $message = $result;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SIMBS | Tambah Buku</title>
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
                <h3 class="mb-3">Tambah Data Buku</h3>
              </div>
              <div class="col-sm-6 d-flex flex-column align-items-end">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item"><a href="buku.php">Data Buku</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-8">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Form Tambah Buku Baru</h3>
                  </div>
                  <form method="POST" action="tambah_buku.php" enctype="multipart/form-data">
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-<?= ($message['status'] == 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message['message']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($_POST['judul'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="id_kategori" class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($data_kategori as $kategori): ?>
                                    <option value="<?= htmlspecialchars($kategori['id_kategori']); ?>">
                                        <?= htmlspecialchars($kategori['nama_kategori']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="penulis" class="form-label">Penulis</label>
                                <input type="text" name="penulis" class="form-control" required value="<?= htmlspecialchars($_POST['penulis'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="penerbit" class="form-label">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control" required value="<?= htmlspecialchars($_POST['penerbit'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun Terbit</label>
                            <input type="number" name="tahun" class="form-control" min="1900" max="<?= date('Y') + 1; ?>" required value="<?= htmlspecialchars($_POST['tahun'] ?? date('Y')); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="gambar" class="form-label">Sampul Buku (Gambar)</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Hanya file JPG, JPEG, PNG, GIF. Maks 5MB.</small>
                        </div>

                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Simpan Data Buku</button>
                      <a href="buku.php" class="btn btn-secondary">Batal</a>
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
