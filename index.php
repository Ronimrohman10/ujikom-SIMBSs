<?php
// FILE: index.php
session_start();
require_once 'koneksi.php';    
require_once 'function.php';
checkLogin(); 

$username_login = $_SESSION['username'];

// Ambil data ringkasan untuk dashboard
$total_buku = hitungTotalBuku($pdo);
$total_kategori = hitungTotalKategori($pdo);

// Ambil 5 data buku terbaru untuk list
$sql_terbaru = "SELECT b.judul, k.nama_kategori 
                FROM buku b JOIN kategori k ON b.id_kategori = k.id_kategori
                ORDER BY b.tanggal_input DESC 
                LIMIT 5";
$stmt_terbaru = $pdo->query($sql_terbaru);
$buku_terbaru = $stmt_terbaru->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SIMBS | Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="preload" href="dist/css/adminlte.css" as="style" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
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
    </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
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
                    <a href="buku.php" class="nav-link">
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
                <h3 class="mb-3">Dashboard</h3>
              </div>
              <div class="col-sm-6 d-flex flex-column align-items-end">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-6 col-6">
                <div class="small-box text-bg-info">
                  <div class="inner">
                    <h3><?= $total_buku; ?></h3>
                    <p>Total Buku Terdaftar</p>
                  </div>
                  <div class="icon">
                    <i class="bi bi-book"></i>
                  </div>
                  <a href="buku.php" class="small-box-footer">
                    Detail <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
              <div class="col-lg-6 col-6">
                <div class="small-box text-bg-success">
                  <div class="inner">
                    <h3><?= $total_kategori; ?></h3>
                    <p>Total Kategori Aktif</p>
                  </div>
                  <div class="icon">
                    <i class="bi bi-tags"></i>
                  </div>
                  <a href="kategori.php" class="small-box-footer">
                    Detail <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">5 Buku Terbaru</h3>
                  </div>
                  <div class="card-body">
                    <?php if (count($buku_terbaru) > 0): ?>
                      <ul>
                        <?php foreach ($buku_terbaru as $buku): ?>
                          <li>
                            **<?= htmlspecialchars($buku['judul']); ?>** <span class="badge text-bg-secondary"><?= htmlspecialchars($buku['nama_kategori']); ?></span>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    <?php else: ?>
                      <p>Belum ada data buku yang dimasukkan.</p>
                    <?php endif; ?>
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
