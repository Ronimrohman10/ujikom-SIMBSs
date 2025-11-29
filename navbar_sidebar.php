<?php
// FILE: navbar_sidebar.php
// Pastikan variabel $username_login sudah di set di file pemanggil (misal: kategori.php)
if (!isset($username_login)) {
    // Jika tidak diset, ambil dari session (default behavior)
    $username_login = $_SESSION['username'] ?? 'User Default'; 
}
?>
      
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
              <a href="buku.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'buku.php' || basename($_SERVER['PHP_SELF']) == 'tambah_buku.php' || basename($_SERVER['PHP_SELF']) == 'edit_buku.php') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-book"></i>
                <p>Data Buku</p>
              </a>
            </li>
            <li class="nav-item">
               <a href="kategori.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'kategori.php' || basename($_SERVER['PHP_SELF']) == 'edit_kategori.php') ? 'active' : ''; ?>">
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
