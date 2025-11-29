<?php
// FILE: kategori.php
session_start();
require_once 'koneksi.php';    
require_once 'function.php';  
checkLogin(); 

$username_login = $_SESSION['username'];
$message = null; 
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : ''; 

// --- BAGIAN LOGIKA CRUD ---

// 1. Logika Hapus Data (DELETE)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_kategori = $_GET['id'];
    $result = hapusKategori($pdo, $id_kategori);
    // Redirect setelah operasi DELETE
    header("Location: kategori.php?msg=" . urlencode($result['message']) . "&status=" . $result['status']);
    exit();
}

// 2. Logika Tambah Data (CREATE)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_kategori'])) {
    $nama_kategori = trim($_POST['nama_kategori']);
    if (!empty($nama_kategori)) {
        $result = tambahKategori($pdo, $nama_kategori);
        // Redirect setelah operasi CREATE
        header("Location: kategori.php?msg=" . urlencode($result['message']) . "&status=" . $result['status']);
        exit();
    } else {
        $message = ['status' => 'error', 'message' => 'Nama kategori tidak boleh kosong.'];
    }
}

// 3. Logika Menampilkan Pesan Setelah Redirect
if (isset($_GET['msg'])) {
    $message = ['status' => $_GET['status'], 'message' => urldecode($_GET['msg'])];
}

// --- PENGAMBILAN DATA UNTUK TAMPILAN ---
$data_kategori = ambilDataKategori($pdo, $keyword); 
?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SIMBS | Data Kategori</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="preload" href="dist/css/adminlte.css" as="style" />
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
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <?php include 'navbar_sidebar.php'; // Pastikan file ini ada! ?>
      
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-3">Data Kategori</h3>
                <form method="POST" class="d-inline-flex align-items-center">
                    <input type="text" name="nama_kategori" class="form-control form-control-sm" placeholder="Nama Kategori Baru" required style="width: 200px;">
                    <button type="submit" name="tambah_kategori" class="btn-sm btn btn-success ms-2">
                        <i class="bi bi-plus-circle"></i> Tambah Kategori
                    </button>
                </form>
              </div>
              <div class="col-sm-6 d-flex flex-column align-items-end">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Data Kategori</li>
                </ol>
                <form class="mt-2" method="GET" action="kategori.php">
                  <div class="input-group">
                    <input 
                      type="text" 
                      class="form-control" 
                      name="keyword" 
                      placeholder="Cari kategori..."
                      value="<?= htmlspecialchars($keyword); ?>"
                    >
                    <button class="btn btn-primary" type="submit">
                      <i class="bi bi-search"></i> Cari
                    </button>
                    <?php if ($keyword): ?>
                        <a href="kategori.php" class="btn btn-secondary">Reset</a>
                    <?php endif; ?>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <?php if ($message): ?>
                        <div class="alert alert-<?= ($message['status'] == 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message['message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                        <thead>
                          <tr>
                            <th style="width: 50px;">No.</th>
                            <th style="width: 100px;">ID Kategori</th>
                            <th>Nama Kategori</th>
                            <th style="width: 200px;">Tanggal Input</th>
                            <th style="width: 150px;">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php if (!empty($data_kategori)): ?>
                                <?php foreach ($data_kategori as $kategori): ?>
                                  <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($kategori['id_kategori']); ?></td>
                                    <td><?= htmlspecialchars($kategori['nama_kategori']); ?></td>
                                    <td><?= htmlspecialchars($kategori['tanggal_input']); ?></td>
                                    <td>
                                        <a href="edit_kategori.php?id=<?= $kategori['id_kategori']; ?>" class="btn btn-sm btn-warning">Ubah</a>
                                        <a href="kategori.php?action=delete&id=<?= $kategori['id_kategori']; ?>" 
                                           onclick="return confirm('Yakin ingin menghapus kategori <?= addslashes($kategori['nama_kategori']); ?>?')"
                                           class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <?php if ($keyword): ?>
                                            Data kategori dengan kata kunci "<?= htmlspecialchars($keyword); ?>" tidak ditemukan.
                                        <?php else: ?>
                                            Tidak ada data kategori.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        </table>
                    </div>
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
