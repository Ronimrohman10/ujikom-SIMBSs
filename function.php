<?php
// FILE: function.php

// Pastikan session sudah dimulai di file-file utama (index, buku, kategori, dll.)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- BAGIAN I: FUNGSI OTENTIKASI (REGISTER & LOGIN) ---

/**
 * Memeriksa apakah pengguna sudah login
 */
function checkLogin() {
    if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
        // Jika belum login, arahkan ke halaman login
        header("Location: login.php");
        exit();
    }
}

/**
 * Proses registrasi pengguna baru
 */
function registerUser(PDO $pdo, $username, $email, $password) {
    if (strlen($password) < 8) {
        return ['status' => 'error', 'message' => 'Password harus mengandung minimal 8 karakter.'];
    }

    try {
        // Cek apakah username atau email sudah terdaftar
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM user WHERE username = :username OR email = :email");
        $stmt_check->execute([':username' => $username, ':email' => $email]);
        if ($stmt_check->fetchColumn() > 0) {
            return ['status' => 'error', 'message' => 'Username atau email sudah terdaftar, gunakan yang lain.'];
        }

        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO user (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute([':username' => $username, ':email' => $email, ':password' => $hashed_password]);
        
        return ['status' => 'success', 'message' => 'Registrasi berhasil. Silakan Login.'];
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Gagal registrasi: ' . $e->getMessage()];
    }
}

/**
 * Proses login pengguna
 */
function loginUser(PDO $pdo, $username, $password) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Login berhasil
                $_SESSION['is_login'] = true;
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                return ['status' => 'success', 'message' => 'Login berhasil.'];
            } else {
                return ['status' => 'error', 'message' => 'Password salah.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Username salah.'];
        }
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Gagal login: ' . $e->getMessage()];
    }
}

// --- BAGIAN II: FUNGSI DASHBOARD & STATISTIK BARU ---

/**
 * Mengambil total jumlah buku (mendukung pencarian)
 */
function hitungTotalBuku(PDO $pdo, $keyword = null) {
    try {
        $sql = "SELECT COUNT(id_buku) FROM buku b 
                LEFT JOIN kategori k ON b.id_kategori = k.id_kategori";
        $params = [];
        
        if ($keyword) {
            $sql .= " WHERE b.judul LIKE :keyword OR b.penulis LIKE :keyword OR k.nama_kategori LIKE :keyword";
            $params[':keyword'] = '%' . $keyword . '%';
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

/**
 * Mengambil total jumlah kategori
 */
function hitungTotalKategori(PDO $pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(id_kategori) FROM kategori");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}


// --- BAGIAN III: FUNGSI MANAJEMEN KATEGORI ---

/**
 * Mengambil data kategori (mendukung pencarian)
 */
function ambilDataKategori(PDO $pdo, $keyword = null) {
    try {
        $sql = "SELECT * FROM kategori";
        $params = [];
        
        if ($keyword) {
            $sql .= " WHERE nama_kategori LIKE :keyword";
            $params[':keyword'] = '%' . $keyword . '%';
        }
        
        $sql .= " ORDER BY tanggal_input DESC"; 
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Menambahkan kategori baru
 */
function tambahKategori(PDO $pdo, $nama_kategori) {
    try {
        $stmt = $pdo->prepare("INSERT INTO kategori (nama_kategori) VALUES (:nama)");
        $stmt->execute([':nama' => $nama_kategori]);
        return ['status' => 'success', 'message' => 'Kategori berhasil ditambahkan!'];
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Gagal menambahkan kategori.'];
    }
}

/**
 * Mengambil kategori berdasarkan ID
 */
function getKategoriById(PDO $pdo, $id_kategori) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM kategori WHERE id_kategori = :id");
        $stmt->execute([':id' => $id_kategori]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Memperbarui data kategori
 */
function updateKategori(PDO $pdo, $id_kategori, $nama_kategori) {
    try {
        $stmt = $pdo->prepare("UPDATE kategori SET nama_kategori = :nama WHERE id_kategori = :id");
        $stmt->execute([':nama' => $nama_kategori, ':id' => $id_kategori]);
        return ['status' => 'success', 'message' => 'Kategori berhasil diubah!'];
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Gagal mengubah kategori.'];
    }
}

/**
 * Menghapus kategori
 */
function hapusKategori(PDO $pdo, $id_kategori) {
    try {
        $stmt = $pdo->prepare("DELETE FROM kategori WHERE id_kategori = :id");
        $stmt->execute([':id' => $id_kategori]);
        return ['status' => 'success', 'message' => 'Kategori berhasil dihapus!'];
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Gagal menghapus kategori. Pastikan tidak ada buku yang menggunakan kategori ini.'];
    }
}

// --- BAGIAN IV: FUNGSI MANAJEMEN BUKU ---

/**
 * Mengambil data buku dengan paginasi (mendukung pencarian)
 */
function ambilDataBukuPaginasi(PDO $pdo, $limit, $offset, $keyword = null) {
    try {
        $sql = "SELECT b.*, k.nama_kategori FROM buku b 
                JOIN kategori k ON b.id_kategori = k.id_kategori";
        $params = [];
        
        if ($keyword) {
            $sql .= " WHERE b.judul LIKE :keyword OR b.penulis LIKE :keyword OR k.nama_kategori LIKE :keyword";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        // Sorting wajib: tanggal_input terbaru
        $sql .= " ORDER BY b.tanggal_input DESC LIMIT :limit OFFSET :offset";
        
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        $stmt = $pdo->prepare($sql);
        
        // Bind parameter harus dilakukan secara terpisah untuk limit dan offset karena tipenya INTEGER
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        if ($keyword) {
             $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Error handling
        return [];
    }
}

/**
 * Mengambil buku berdasarkan ID
 */
function getBukuById(PDO $pdo, $id_buku) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM buku WHERE id_buku = :id");
        $stmt->execute([':id' => $id_buku]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Memproses file gambar
 */
function prosesGambar($file, $old_filename = null) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        // Jika tidak ada file yang diupload (pada saat update, ini wajar)
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['status' => 'no_file', 'filename' => $old_filename];
        }
        return ['status' => 'error', 'message' => 'Upload error: ' . $file['error']];
    }
    
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowed_types)) {
        return ['status' => 'error', 'message' => 'Format file tidak didukung.'];
    }
    if ($file['size'] > $max_size) {
        return ['status' => 'error', 'message' => 'Ukuran file terlalu besar (maks 5MB).'];
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid('cover_') . '.' . $extension;
    $upload_dir = 'assets/img/cover/';
    $target_path = $upload_dir . $new_filename;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Hapus gambar lama jika ini adalah operasi update
        if ($old_filename && file_exists($upload_dir . $old_filename)) {
            unlink($upload_dir . $old_filename);
        }
        return ['status' => 'success', 'filename' => $new_filename];
    } else {
        return ['status' => 'error', 'message' => 'Gagal memindahkan file.'];
    }
}

/**
 * Menambahkan data buku baru
 */
function tambahBuku(PDO $pdo, $data, $file_gambar) {
    $judul = $data['judul'];
    $id_kategori = $data['id_kategori'];
    $penulis = $data['penulis'];
    $penerbit = $data['penerbit'];
    $tahun = $data['tahun'];
    $gambar_filename = null;

    // Proses Gambar
    $gambar_result = prosesGambar($file_gambar);
    if ($gambar_result['status'] === 'error') {
        // Jika ada error pada upload gambar, kembalikan error
        return $gambar_result; 
    } elseif ($gambar_result['status'] === 'success') {
        $gambar_filename = $gambar_result['filename'];
    }

    try {
        $sql = "INSERT INTO buku (id_kategori, judul, penulis, penerbit, tahun, gambar) 
                VALUES (:id_kategori, :judul, :penulis, :penerbit, :tahun, :gambar)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_kategori' => $id_kategori,
            ':judul' => $judul,
            ':penulis' => $penulis,
            ':penerbit' => $penerbit,
            ':tahun' => $tahun,
            ':gambar' => $gambar_filename
        ]);
        return ['status' => 'success', 'message' => 'Data buku berhasil ditambahkan!'];
    } catch (PDOException $e) {
        // Hapus file yang sudah terlanjur terupload jika insert ke DB gagal
        if ($gambar_filename && file_exists('assets/img/cover/' . $gambar_filename)) {
            unlink('assets/img/cover/' . $gambar_filename);
        }
        return ['status' => 'error', 'message' => 'Gagal menambahkan buku: ' . $e->getMessage()];
    }
}

/**
 * Memperbarui data buku
 */
function updateBuku(PDO $pdo, $data, $file_gambar, $old_filename) {
    $id_buku = $data['id_buku'];
    $judul = $data['judul'];
    $id_kategori = $data['id_kategori'];
    $penulis = $data['penulis'];
    $penerbit = $data['penerbit'];
    $tahun = $data['tahun'];
    $gambar_filename = $old_filename;

    // Proses Gambar
    $gambar_result = prosesGambar($file_gambar, $old_filename);

    if ($gambar_result['status'] === 'error') {
        return $gambar_result; 
    } elseif ($gambar_result['status'] === 'success') {
        $gambar_filename = $gambar_result['filename']; // Gambar baru berhasil diupload
    }
    // Jika 'no_file', $gambar_filename tetap $old_filename, jadi tidak ada yang diubah

    try {
        $sql = "UPDATE buku SET id_kategori = :id_kategori, judul = :judul, 
                penulis = :penulis, penerbit = :penerbit, tahun = :tahun, gambar = :gambar 
                WHERE id_buku = :id_buku";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_kategori' => $id_kategori,
            ':judul' => $judul,
            ':penulis' => $penulis,
            ':penerbit' => $penerbit,
            ':tahun' => $tahun,
            ':gambar' => $gambar_filename,
            ':id_buku' => $id_buku
        ]);
        return ['status' => 'success', 'message' => 'Data buku berhasil diubah!'];
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Gagal mengubah buku: ' . $e->getMessage()];
    }
}

/**
 * Menghapus data buku dan file gambarnya
 */
function hapusBuku(PDO $pdo, $id_buku) {
    try {
        // 1. Ambil nama file gambar
        $buku = getBukuById($pdo, $id_buku);
        if (!$buku) {
             return ['status' => 'error', 'message' => 'Data buku tidak ditemukan.'];
        }
        $gambar_filename = $buku['gambar'];
        
        // 2. Hapus data dari database
        $stmt = $pdo->prepare("DELETE FROM buku WHERE id_buku = :id");
        $stmt->execute([':id' => $id_buku]);
        
        // 3. Hapus file gambar dari server
        if ($gambar_filename) {
            $file_path = 'assets/img/cover/' . $gambar_filename;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        return ['status' => 'success', 'message' => 'Buku dan file gambar berhasil dihapus!'];
    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Gagal menghapus buku: ' . $e->getMessage()];
    }
}



// --- FUNGSI AUTENTIKASI ---
// ... (fungsi loginUser, registerUser, checkLogin)

/**
 * Menghapus sesi login pengguna.
 * @return array
 */
function logoutUser() {
    // Hapus semua variabel sesi
    $_SESSION = array(); 
    
    // Hancurkan sesi
    session_destroy();
    
    return ['status' => 'success', 'message' => 'Anda berhasil logout.'];
}
