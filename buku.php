<?php
// FILE: buku.php
session_start();
require_once 'koneksi.php';    
require_once 'function.php'; 
checkLogin(); 

$username_login = $_SESSION['username'];
$message = null; 

// --- PENGATURAN PAGINASI & PENCARIAN ---
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$batas = 10; // Batas data per halaman
$halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($halaman_aktif - 1) * $batas;

// Hitung total buku (termasuk filter keyword)
$total_buku = hitungTotalBuku($pdo, $keyword); 
$jumlah_halaman = ceil($total_buku / $batas);

// Ambil data buku yang akan ditampilkan
$data_buku = ambilDataBukuPaginasi($pdo, $batas, $offset, $keyword); 

// --- LOGIKA HAPUS DATA (DELETE) ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_buku = $_GET['id'];
    $result = hapusBuku($pdo, $id_buku);
    // Redirect setelah operasi DELETE
    header("Location: buku.php?msg=" . urlencode($result['message']) . "&status=" . $result['status']);
    exit();
}

// --- LOGIKA MENAMPILKAN PESAN DARI REDIRECT ---
if (isset($_GET['msg'])) {
    $message = ['status' => $_GET['status'], 'message' => urldecode($_GET['msg'])];
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SIMBS | Data Buku</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="preload" href="dist/css/adminlte.css" as="style" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
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
    <style>
      .cover-img { max-width: 60px; height: auto; border-radius: 4px; }
      .text-ellipsis {
          max-width: 250px;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
      }
    </style>
    </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      
      <?php // Jika Anda menggunakan include 'navbar_sidebar.php'; ganti kode Navbar/Sidebar di bawah dengan include ?>
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="index.php" class="nav-link">Dashboard SIMBS</a>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img
                  src="dist/assets/img/user2-160x160.jpg"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline"><?= htmlspecialchars($username_login); ?></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-header text-bg-primary">
                  <img
                    src="dist/assets/img/user2-160x160.jpg"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    <?= htmlspecialchars($username_login); ?>
                    <small>User SIMBS</small>
                  </p>
                </li>
                <li class="user-footer">
                  <a href="logout.php" class="btn btn-default btn-flat float-end">Sign out</a>
                </li>
              </ul>
            </li>
            </ul>
          </div>
      </nav>
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href="index.php" class="brand-link">
            <img
              src="dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <span class="brand-text fw-light">S.I.M.B.S.</span>
          </a>
        </div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              data-accordion="false"
            >
              <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                  <i class="nav-icon bi bi-list"></i>
                  <p>
                    Data Master
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="buku.php" class="nav-link active">
                      <i class="nav-icon bi bi-book"></i>
                      <p>Data Buku</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="kategori.php" class="nav-link">
                      <i class="nav-icon bi bi-tags"></i>
                      <p>Data Kategori</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-header">AUTENTIKASI</li>
              <li class="nav-item">
                <a href="logout.php" class="nav-link">
                  <i class="nav-icon bi bi-box-arrow-right"></i>
                  <p>Sign Out</p>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </aside>
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-3">Data Buku</h3>
                <a href="tambah_buku.php">
                  <button class="btn-sm btn btn-primary">Tambah Data</button>
                </a>
              </div>
              <div class="col-sm-6 d-flex flex-column align-items-end">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Data Buku</li>
                </ol>
                <form class="mt-2" method="GET" action="buku.php">
                  <div class="input-group">
                    <input 
                      type="text" 
                      class="form-control" 
                      name="keyword" 
                      placeholder="Cari judul/penulis..."
                      value="<?= htmlspecialchars($keyword); ?>"
                    >
                    <button class="btn btn-primary" type="submit">
                      <i class="bi bi-search"></i> Cari
                    </button>
                    <?php if ($keyword): ?>
                        <a href="buku.php" class="btn btn-secondary">Reset</a>
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
                            <th>No.</th>
                            <th>ID Buku</th>
                            <th>Sampul</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $no = $offset + 1; ?>
                            <?php if (!empty($data_buku)): ?>
                                <?php foreach ($data_buku as $buku): ?>
                                  <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($buku['id_buku']); ?></td>
                                    <td>
                                        <?php if ($buku['gambar']): ?>
                                            <img src="assets/img/cover/<?= htmlspecialchars($buku['gambar']); ?>" alt="Cover" class="cover-img">
                                        <?php else: ?>
                                            <i class="bi bi-card-image text-secondary"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-ellipsis" title="<?= htmlspecialchars($buku['judul']); ?>"><?= htmlspecialchars($buku['judul']); ?></td>
                                    <td><?= htmlspecialchars($buku['penulis']); ?></td>
                                    <td><?= htmlspecialchars($buku['penerbit']); ?></td>
                                    <td><?= htmlspecialchars($buku['tahun']); ?></td>
                                    <td><?= htmlspecialchars($buku['nama_kategori']); ?></td>
                                    <td>
                                        <a href="edit_buku.php?id=<?= $buku['id_buku']; ?>" class="btn btn-sm btn-warning me-1">Ubah</a>
                                        <a href="buku.php?action=delete&id=<?= $buku['id_buku']; ?>" 
                                            onclick="return confirm('Yakin hapus buku: <?= addslashes($buku['judul']); ?>?')"
                                            class="btn btn-sm btn-danger">Hapus</a>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <?php if ($keyword): ?>
                                            Data buku dengan kata kunci "<?= htmlspecialchars($keyword); ?>" tidak ditemukan.
                                        <?php else: ?>
                                            Tidak ada data buku saat ini.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        </table>
                        
                        <nav aria-label="Page navigation example" class="mt-4">
                          <ul class="pagination justify-content-center">
                            
                            <?php if ($halaman_aktif > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="buku.php?halaman=<?= $halaman_aktif - 1; ?>&keyword=<?= urlencode($keyword); ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for($i = 1; $i <= $jumlah_halaman; $i++): ?>
                                <li class="page-item <?= ($i == $halaman_aktif) ? 'active' : ''; ?>">
                                    <a class="page-link" href="buku.php?halaman=<?= $i; ?>&keyword=<?= urlencode($keyword); ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($halaman_aktif < $jumlah_halaman): ?>
                                <li class="page-item">
                                    <a class="page-link" href="buku.php?halaman=<?= $halaman_aktif + 1; ?>&keyword=<?= urlencode($keyword); ?>">Next</a>
                                </li>
                            <?php endif; ?>
                          </ul>
                          <p class="text-center text-muted">Total data: <?= $total_buku; ?> | Halaman <?= $halaman_aktif; ?> dari <?= $jumlah_halaman; ?></p>
                        </nav>
                        </div>
                </div>
              </div>
              </div>
            </div>
          </div>
        </main>
      <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Bismillah Tugas Promnet</div>
        <strong>
          Copyright &copy; 2025&nbsp;
        </strong>
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
