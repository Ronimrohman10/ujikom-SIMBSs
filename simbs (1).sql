-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Nov 2025 pada 14.51
-- Versi server: 10.4.6-MariaDB
-- Versi PHP: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simbs`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `tahun` year(4) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id_buku`, `id_kategori`, `judul`, `penulis`, `penerbit`, `tahun`, `gambar`, `tanggal_input`) VALUES
(1, 1, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '2005', 'cover_6929a284e75bf.jpg', '2025-11-20 10:00:00'),
(2, 1, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', '1980', 'cover_6929a2683a460.jpg', '2025-11-25 15:30:00'),
(3, 2, 'Sapiens: Sejarah Singkat Umat Manusia', 'Yuval Noah Harari', 'Gramedia', '2011', 'cover_6929a22834e9a.jpg', '2025-11-28 08:00:00'),
(4, 3, 'Pengantar Sistem Informasi', 'Jogiyanto', 'Andi Offset', '2000', 'cover_6929a2a644498.jpg', '2025-10-10 11:00:00'),
(5, 1, 'Cantik Itu Luka', 'Eka Kurniawan', 'Gramedia', '2002', 'cover_6929a240275f8.jpg', '2025-11-27 12:00:00'),
(6, 2, 'Filosofi Teras', 'Henry Manampiring', 'Kompas', '2019', 'cover_6929a28f56541.jpg', '2025-11-01 09:00:00'),
(7, 3, 'Belajar MySQL Dasar', 'Abdul Kadir', 'Informatika', '2022', 'cover_6929a2300b12c.jpg', '2025-11-28 07:00:00'),
(8, 1, 'Anak Semua Bangsa', 'Pramoedya Ananta Toer', 'Hasta Mitra', '1981', 'cover_6929a259062ca.jpg', '2025-11-25 16:00:00'),
(9, 2, 'Seni Hidup Minimalis', 'Fumio Sasaki', 'Gramedia', '2015', 'cover_6929a29ae80e4.jpg', '2025-10-15 14:00:00'),
(10, 3, 'Pemrograman Web dengan PHP', 'Adam Saputra ', 'MediaKom', '2023', 'cover_6929a24a93e50.jpg', '2025-11-26 10:00:00'),
(11, 1, 'Perahu Kertas', 'Dee Lestari', 'Bentang Pustaka', '2009', 'cover_6929a2ae5a4e8.jpg', '2025-09-05 17:00:00'),
(12, 2, 'Atomic Habits', 'James Clear', 'Gramedia', '2018', 'cover_6929a21b5c44d.jpg', '2025-11-28 09:00:00'),
(13, 1, 'Negeri 5 Menara', 'Ahmad Fuadi', 'Gramedia', '2009', 'cover_6929a27105977.jpg', '2025-11-22 13:00:00'),
(14, 3, 'Jaringan Komputer Dasar', 'Iwan Setiawan', 'Andi Offset', '2021', 'cover_6929a27c0c5b8.jpg', '2025-11-21 11:00:00'),
(15, 1, 'Dilan 1990', 'Pidi Baiq', 'Pastel Books', '2014', 'cover_6929a238458ea.jpg', '2025-11-27 15:00:00'),
(16, 5, 'One piece', 'Eiichiro Oda', 'Shueisha', '1997', 'cover_6929a35cbe904.jpg', '2025-11-28 20:27:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `tanggal_input` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `tanggal_input`) VALUES
(1, 'Fiksi', '2025-11-28 19:13:53'),
(2, 'Non-Fiksi', '2025-11-28 19:13:53'),
(3, 'Komputer & Teknologi', '2025-11-28 19:13:53'),
(4, 'Agama', '2025-11-28 20:03:48'),
(5, 'Comic jepang', '2025-11-28 20:25:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`) VALUES
(1, 'roni', 'ronimrohman10@gmail.com', '$2y$10$lkHxlYiGtjbEGsZ3BJb1R.VA6L/ciM4gBg/KYiDDYX1uuBhIXi9.S');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `fk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
