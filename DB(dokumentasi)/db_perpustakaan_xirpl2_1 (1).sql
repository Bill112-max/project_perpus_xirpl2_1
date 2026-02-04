-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 05:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpustakaan_xirpl2_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_buku`
--

CREATE TABLE `tbl_buku` (
  `id_buku` varchar(10) NOT NULL,
  `judul_buku` varchar(200) NOT NULL,
  `sinopsis` varchar(300) NOT NULL,
  `jumlah_halaman` int(11) NOT NULL,
  `jumlah_buku` int(11) NOT NULL,
  `id_kategori` varchar(10) NOT NULL,
  `id_penerbit` varchar(10) NOT NULL,
  `tahun_terbit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_buku`
--

INSERT INTO `tbl_buku` (`id_buku`, `judul_buku`, `sinopsis`, `jumlah_halaman`, `jumlah_buku`, `id_kategori`, `id_penerbit`, `tahun_terbit`) VALUES
('B_0001', 'Dasar dasar TKJ', 'Pembelajaran TKJ kelas X', 250, 50, '0008', 'PN_N_001', 2025),
('B_0002', 'Cara kerja saham', 'Mempelajari mengenai saham', 129, 50, '0010', 'PN_N_002', 2020),
('B_0003', 'Dasar dasar pemrograman', 'pembelajaran pemrograman web', 155, 98, '0008', 'PN_N_001', 2024),
('B_0004', 'Raja Saurus', 'Cerita fiksi mengenai raja saurus sebagai raja dinosaurus', 50, 100, '0005', 'PN_N_005', 2019),
('B_0005', 'SuraBaya', 'asal ususl daerah surabaya', 50, 100, '0005', 'PN_N_006', 2005),
('B_0006', 'Kamus Bahasa Inggris 5000', '-', 500, 35, '0012', 'PN_N_010', 2005),
('B_0007', 'Teknik Mesin dan industri', '-', 250, 25, '0001', 'PN_N_006', 2024),
('B_0008', 'Dasar dasar teknik mesin', 'mempelajari dasar dasar mesin motor', 145, 25, '0001', 'PN_N_007', 2024),
('B_0009', 'Kamus Bahasa Inggris 20000', '--', 750, 10, '0012', 'PN_N_009', 2024),
('B_0010', 'Kamus bahasa bali ', 'kamus berbahasa bali ', 550, 25, '0012', 'PN_N_010', 2025),
('B_0011', 'Teori Desain vol1', 'teori teori desai untuk DKV kelas 10 dan umum ', 120, 100, '0008', 'PN_N_004', 2026),
('B_0012', 'Dilan&Nico', 'kisah cinta nico terhadap dilan yang ternyata......', 250, 100, '0003', 'PN_N_005', 2026),
('B_0100', 'Target pasar ', 'Mengajarkan ,mengenai mencari target pasar yang pas untuk suatu produk ', 120, 85, '0010', 'PN_N_002', 2026);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_history`
--

CREATE TABLE `tbl_history` (
  `id_history` int(20) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `id_buku` varchar(60) NOT NULL,
  `jumlah_pinjam` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `admin` varchar(50) NOT NULL,
  `waktu` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_history`
--

INSERT INTO `tbl_history` (`id_history`, `id_peminjaman`, `id`, `id_buku`, `jumlah_pinjam`, `status`, `admin`, `waktu`) VALUES
(27, 50, 2, 'B_0001', '', 'disetujui', 'Billy', '2026-02-04 09:30:18'),
(28, 50, 2, 'B_0001', '', 'disetujui', 'Billy', '2026-02-04 09:32:27'),
(29, 50, 2, 'B_0001', '', 'disetujui', 'Billy', '2026-02-04 09:34:15'),
(30, 50, 2, 'B_0001', '', 'disetujui', 'Billy', '2026-02-04 09:34:16'),
(31, 50, 2, 'B_0001', '', 'disetujui', 'Billy', '2026-02-04 09:34:24'),
(32, 50, 2, 'B_0001', '', 'disetujui', 'Billy', '2026-02-04 09:35:48'),
(33, 50, 2, 'B_0001', '', 'menunggu pengembalia', 'JKO', '2026-02-04 09:36:28'),
(34, 50, 2, 'B_0001', '', 'dikembalikan', 'Billy', '2026-02-04 09:39:50'),
(35, 51, 2, 'B_0002', '', 'disetujui', 'Billy', '2026-02-04 09:58:44'),
(36, 51, 2, 'B_0002', '', 'menunggu pengembalia', 'JKO', '2026-02-04 09:58:55'),
(37, 51, 2, 'B_0002', '', 'dikembalikan', 'Billy', '2026-02-04 09:59:13'),
(38, 58, 2, 'B_0002', '5', 'disetujui', 'Billy', '2026-02-04 11:08:19'),
(39, 58, 2, 'B_0002', '5', 'menunggu pengembalia', 'JKO', '2026-02-04 11:08:36'),
(40, 58, 2, 'B_0002', '5', 'dikembalikan', 'Billy', '2026-02-04 11:08:46'),
(41, 59, 2, 'B_0012', '5', 'disetujui', 'Billy', '2026-02-04 12:03:43'),
(42, 59, 2, 'B_0012', '5', 'menunggu pengembalia', 'JKO', '2026-02-04 12:04:21'),
(43, 59, 2, 'B_0012', '5', 'dikembalikan', 'Billy', '2026-02-04 12:04:56'),
(44, 60, 2, 'B_0003', '2', 'disetujui', 'Billy', '2026-02-04 12:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kategori`
--

CREATE TABLE `tbl_kategori` (
  `id_kategori` varchar(10) NOT NULL,
  `kategori` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_kategori`
--

INSERT INTO `tbl_kategori` (`id_kategori`, `kategori`) VALUES
('0001', 'Mesin'),
('0002', 'Nonfiksi'),
('0003', 'Romansa'),
('0004', 'Misteri'),
('0005', 'Fantasi'),
('0006', 'Sains Fiksi'),
('0007', 'Horor'),
('0008', 'Pendidikan'),
('0009', 'Motivasi'),
('0010', 'Bisnis & Ekonomi'),
('0012', 'Kamus');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_peminjaman`
--

CREATE TABLE `tbl_peminjaman` (
  `id_peminjaman` int(50) NOT NULL,
  `id` int(60) NOT NULL,
  `id_buku` varchar(60) NOT NULL,
  `jumlah_pinjam` varchar(50) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `status` enum('pending','disetujui','ditolak','Sudah dikembalikan','menunggu pengembalian') NOT NULL DEFAULT 'pending',
  `persetujuan_dari` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_peminjaman`
--

INSERT INTO `tbl_peminjaman` (`id_peminjaman`, `id`, `id_buku`, `jumlah_pinjam`, `tanggal_pinjam`, `tanggal_kembali`, `status`, `persetujuan_dari`) VALUES
(58, 2, 'B_0002', '5', '2025-10-12', '2026-02-04', '', ''),
(59, 2, 'B_0012', '5', '2026-02-04', '2026-02-04', '', ''),
(60, 2, 'B_0003', '2', '2025-02-20', '2025-12-20', 'disetujui', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penerbit`
--

CREATE TABLE `tbl_penerbit` (
  `id_penerbit` varchar(10) NOT NULL,
  `nama_penerbit` varchar(200) NOT NULL,
  `no_tlp_penerbit` varchar(18) NOT NULL,
  `nama_sales` varchar(200) NOT NULL,
  `no_tlp_sales` varchar(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_penerbit`
--

INSERT INTO `tbl_penerbit` (`id_penerbit`, `nama_penerbit`, `no_tlp_penerbit`, `nama_sales`, `no_tlp_sales`) VALUES
('PN_N_001', 'Bentang Pustaka', '08559563214', 'Andrew', '0889995674'),
('PN_N_002', 'Erlangga', '0829655541', 'JOKO', '0889995674'),
('PN_N_004', 'GagasMedia', '0875631521728', 'Davide', '082155565932'),
('PN_N_005', 'Homer', '02123254587896', 'JOKO', '089965878796'),
('PN_N_006', 'Yamaha', '08559563214', 'Permadi', '1111111111111112'),
('PN_N_007', 'Honda', '08559563214', 'Andre', '1211111111111'),
('PN_N_008', 'BintangPustaka', '08559563214', 'Davide', '02587896369878963'),
('PN_N_009', 'Queen', '08559563214', 'Andrew', '1211111111111'),
('PN_N_010', 'Andrew', '08559563214', 'Permadi', '0889995674');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengembalian`
--

CREATE TABLE `tbl_pengembalian` (
  `id_pengembalian` int(50) NOT NULL,
  `id_peminjaman` int(50) NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `denda` decimal(50,0) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `no_tlp` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(50) NOT NULL,
  `akses` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `nama`, `no_tlp`, `email`, `username`, `password`, `akses`) VALUES
(1, 'billy', '0215666986', '0wbi@gmail.com', 'Billy', '1234', 'admin'),
(2, 'Jarah', '03E', 'GOenawan@gmail.com', 'JKO', '1234', 'anggota'),
(3, 'Andre', '014555', '0mail@gmail.com', 'andre', '1234', 'anggota');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_buku`
--
ALTER TABLE `tbl_buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `Penerbit` (`id_penerbit`),
  ADD KEY `kategori` (`id_kategori`);

--
-- Indexes for table `tbl_history`
--
ALTER TABLE `tbl_history`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `tbl_peminjaman`
--
ALTER TABLE `tbl_peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `id_users` (`id`);

--
-- Indexes for table `tbl_penerbit`
--
ALTER TABLE `tbl_penerbit`
  ADD PRIMARY KEY (`id_penerbit`);

--
-- Indexes for table `tbl_pengembalian`
--
ALTER TABLE `tbl_pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_history`
--
ALTER TABLE `tbl_history`
  MODIFY `id_history` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbl_peminjaman`
--
ALTER TABLE `tbl_peminjaman`
  MODIFY `id_peminjaman` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `tbl_pengembalian`
--
ALTER TABLE `tbl_pengembalian`
  MODIFY `id_pengembalian` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_buku`
--
ALTER TABLE `tbl_buku`
  ADD CONSTRAINT `Penerbit` FOREIGN KEY (`id_penerbit`) REFERENCES `tbl_penerbit` (`id_penerbit`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `kategori` FOREIGN KEY (`id_kategori`) REFERENCES `tbl_kategori` (`id_kategori`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_history`
--
ALTER TABLE `tbl_history`
  ADD CONSTRAINT `id` FOREIGN KEY (`id`) REFERENCES `tbl_users` (`id`);

--
-- Constraints for table `tbl_peminjaman`
--
ALTER TABLE `tbl_peminjaman`
  ADD CONSTRAINT `id_buku` FOREIGN KEY (`id_buku`) REFERENCES `tbl_buku` (`id_buku`),
  ADD CONSTRAINT `id_users` FOREIGN KEY (`id`) REFERENCES `tbl_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
