-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Jul 2025 pada 04.57
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_pem_lp`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(5) NOT NULL,
  `nama_alternatif` varchar(50) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `longitude` varchar(100) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tema_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama_alternatif`, `alamat`, `latitude`, `longitude`, `gambar`, `tema_id`) VALUES
(71, 'A9', 'RMQ7+2W6, Jl. Prof. Dr. Herman Johanes, Penfui, Kec. Kupang Tengah, Kabupaten Kupang, Nusa Tenggara Tim.', '-10.15239467422181', '123.66531600041164', '9fdae91062f4ea105a7ccff5c9190215.jpg', 3),
(72, 'A8', '8X38+C5V, Unnamed Road, Lelogama, Amfoang Sel., Nusa Tenggara Tim., Lelogama, Kec. Amfoang Sel., Kabupaten Kupang, Nusa Tenggara Tim.', '-9.696149742645233', '123.96539981128755', 'd260a15564278cf1c5692d7fde2d6cc9.JPG', 4),
(73, 'A7', 'RJR8+749, Unnamed Road, Oebufu, Oebobo, Kupang City, East Nusa Tenggara 85228', '-10.159139588445775', '123.61537169824815', '97dda75f3beaf5c70380bf2f4abacaec.jpg', 3),
(74, 'A6', 'VM85+7RP Pantai Nunsui, Oesapa, Lasiana, Kelapa Lima, Kupang City, East Nusa Tenggara', '-10.13399437531897', '123.65977131412339', '993b760b9f52a61fd05d6bedfa241c1e.jpg', 4),
(75, 'A5', 'Tablolong, Kec. Kupang Bar., Kabupaten Kupang, Nusa Tenggara Tim.', '-10.317648084569043', '123.47524089721787', 'b8de5bcba3281948aa7455535ca93fa1.jpg', 4),
(76, 'A4', 'JPVR+9Q6, Unnamed Road, Erbaun, Amarasi Bar., Nusa Tenggara Tim., Erbaun, Amarasi Barat, Kupang Regency, East Nusa Tenggara', '-10.356365398345398', '123.74187629805868', 'c862d8670e58d45d2e27238c3c0d7f9e.JPG', 3),
(77, 'A3', 'PJXM+RGX, Oelomin, Kec. Maulafa, Kabupaten Kupang, Nusa Tenggara Tim.', '-10.250241115332338', '123.63387815626729', 'e9e01fbd192b2b8d8d60f4e092e28186.jpg', 4),
(78, 'A2', 'Jl. Ikan Tongkol, Lahilai Bissi Kopan, Kec. Kota Lama, Kota Kupang, Nusa Tenggara Tim.', '-10.16110126732182', '123.57682697358145', '6a99d4c3ba0f85d09d910882cee48c06.jpg', 5),
(79, 'A1', 'Jl jurusan Bendungan, Raknamo, Kec. Amabi Oefeto, Kabupaten Kupang, Nusa Tenggara Tim.', '-10.119112685751425', '123.93217902292075', '7dd79a50a90ec2fcdafc93c9be8f975a.jpg', 2),
(80, 'A0', 'Buraen, Kec. Amarasi Sel., Kabupaten Kupang, Nusa Tenggara Tim.', '-10.308819154490156', '123.87415102895896', '37a569cdb42b93c83bcbe6368fb8c232.JPG', 5),
(83, 'A10', '-', '-9.865149346683376', '124.30736676601288', '883916d19be1305cc9755462805c6e20.png', 4),
(84, 'A11', '-', '-9.566116506971282', '124.30736676601288', '6f8e08cf60b3cb9c2e1a6886bba679dc.png', 3),
(85, 'A12', '-', '-9.566116506971282', '124.41281113776452', '965d7051afb1c22733bc8a8190345bf2.png', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kecocokan_alt_kriteria`
--

CREATE TABLE `kecocokan_alt_kriteria` (
  `id_alt_kriteria` int(11) NOT NULL,
  `f_id_alternatif` int(5) NOT NULL,
  `f_id_kriteria` char(2) NOT NULL,
  `f_id_sub_kriteria` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kecocokan_alt_kriteria`
--

INSERT INTO `kecocokan_alt_kriteria` (`id_alt_kriteria`, `f_id_alternatif`, `f_id_kriteria`, `f_id_sub_kriteria`) VALUES
(117, 71, 'C1', 29),
(118, 71, 'C2', 38),
(119, 71, 'C3', 41),
(121, 72, 'C1', 30),
(122, 72, 'C2', 34),
(123, 72, 'C3', 40),
(125, 73, 'C1', 30),
(126, 73, 'C2', 38),
(127, 73, 'C3', 40),
(129, 74, 'C1', 31),
(130, 74, 'C2', 34),
(131, 74, 'C3', 40),
(133, 75, 'C1', 32),
(134, 75, 'C2', 38),
(135, 75, 'C3', 41),
(137, 76, 'C1', 32),
(138, 76, 'C2', 38),
(139, 76, 'C3', 41),
(141, 77, 'C1', 32),
(142, 77, 'C2', 37),
(143, 77, 'C3', 41),
(145, 78, 'C1', 33),
(146, 78, 'C2', 38),
(147, 78, 'C3', 40),
(149, 79, 'C1', 33),
(150, 79, 'C2', 37),
(151, 79, 'C3', 40),
(153, 80, 'C1', 33),
(154, 80, 'C2', 38),
(155, 80, 'C3', 40),
(165, 83, 'C1', 29),
(166, 83, 'C2', 38),
(167, 83, 'C3', 41),
(169, 84, 'C1', 29),
(170, 84, 'C2', 38),
(171, 84, 'C3', 41),
(173, 85, 'C1', 29),
(174, 85, 'C2', 38),
(175, 85, 'C3', 40);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` char(2) NOT NULL,
  `nama_kriteria` varchar(50) NOT NULL,
  `jenis_kriteria` varchar(30) NOT NULL,
  `bobot_kriteria` double(2,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `jenis_kriteria`, `bobot_kriteria`) VALUES
('C1', 'Jarak lokasi dari titik pengguna ', 'Cost', 0.41),
('C2', 'Biaya sewa lokasi', 'Cost', 0.30),
('C3', 'Akses menuju lokasi', 'Benefit', 0.20);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id_sub_kriteria` int(5) NOT NULL,
  `nama_sub_kriteria` varchar(150) NOT NULL,
  `spesifikasi` varchar(30) NOT NULL,
  `bobot_sub_kriteria` int(11) NOT NULL,
  `f_id_kriteria` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_sub_kriteria`, `nama_sub_kriteria`, `spesifikasi`, `bobot_sub_kriteria`, `f_id_kriteria`) VALUES
(29, 'Sangat dekat', '≤ 5 Km', 5, 'C1'),
(30, 'Dekat', '> 5 -  ≤ 15 Km', 4, 'C1'),
(31, 'Sedang', '> 15 - ≤ 25 Km', 3, 'C1'),
(32, 'Jauh', '> 25 - ≤ 35 Km', 2, 'C1'),
(33, 'Sangat jauh', '> 35 Km', 1, 'C1'),
(34, 'Sangat murah', 'Rp. ≤ 5.000', 5, 'C2'),
(35, 'Murah', '> Rp. 5.000 - ≤ Rp. 15.000', 4, 'C2'),
(36, 'Sedang', '> Rp. 15.000 - ≤ Rp. 25.000', 3, 'C2'),
(37, 'Mahal', '> Rp. 25.000 - ≤ Rp. 35.000', 2, 'C2'),
(38, 'Sangat mahal', '> Rp.35.000', 1, 'C2'),
(39, 'Sangat baik', 'Mudah', 3, 'C3'),
(40, 'Baik', 'Sedang', 2, 'C3'),
(41, 'Cukup', 'Sulit', 1, 'C3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tema`
--

CREATE TABLE `tema` (
  `id_tema` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tema`
--

INSERT INTO `tema` (`id_tema`, `nama`) VALUES
(2, 'Pantai'),
(3, 'Pegunungan'),
(4, 'Taman'),
(5, 'Danau');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(5) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `role`) VALUES
(5, 'yupi', '$2y$10$l5/1sEvffJfq58XYecARruHjepF3LE.2jfOVQ015j9oAtv1nYrxbm', 1),
(6, 'admin', '$2y$10$vKlD7o2zW7D0NyeRZ9gIOuq/H5cD/hjZgmjZ20.8.yRE9FHaJKqkq', 0),
(10, 'yupi', '$2y$10$vvyy19qet8e/qr08WmtliuceWtRELCjlb8tCvTMrnfdjLccBFNNgK', 1),
(11, 'yupi', '$2y$10$wgxBRnHjRKqaYmsnD70zW.Y753mRWyfYlRpLvArvdLSOi4EBfJqG6', 1),
(12, 'yupiw', '$2y$10$Azdj9.7xiIl8db2R6HDjNePvv9gc9w1qpF4Ezl2POJHw/FWqICo/W', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_access_logs`
--

CREATE TABLE `user_access_logs` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `access_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user_access_logs`
--

INSERT INTO `user_access_logs` (`id`, `ip_address`, `browser`, `access_time`) VALUES
(1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2024-11-21 15:08:39'),
(2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:09:11'),
(3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2024-11-21 15:20:57'),
(4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:30:13'),
(5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:30:50'),
(6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:32:26'),
(7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:34:51'),
(8, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:34:53'),
(9, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:34:57'),
(10, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:35:18'),
(11, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:35:29'),
(12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:35:30'),
(13, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:35:33'),
(14, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:35:35'),
(15, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:35:41'),
(16, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:35:54'),
(17, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:36:25'),
(18, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:36:30'),
(19, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-21 15:37:14'),
(20, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2024-11-26 01:43:11'),
(21, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2024-11-26 01:43:18'),
(22, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', '2024-12-16 03:01:37'),
(23, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', '2025-03-06 02:37:29'),
(24, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', '2025-03-06 02:47:41'),
(25, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', '2025-05-10 05:29:42'),
(26, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', '2025-05-10 05:30:56');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`),
  ADD KEY `tema_id` (`tema_id`);

--
-- Indeks untuk tabel `kecocokan_alt_kriteria`
--
ALTER TABLE `kecocokan_alt_kriteria`
  ADD PRIMARY KEY (`id_alt_kriteria`),
  ADD KEY `f_id_alternatif` (`f_id_alternatif`,`f_id_kriteria`,`f_id_sub_kriteria`),
  ADD KEY `f_id_kriteria` (`f_id_kriteria`),
  ADD KEY `kecocokan_alt_kriteria_ibfk_2` (`f_id_sub_kriteria`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indeks untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_sub_kriteria`),
  ADD KEY `f_id_kriteria` (`f_id_kriteria`);

--
-- Indeks untuk tabel `tema`
--
ALTER TABLE `tema`
  ADD PRIMARY KEY (`id_tema`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indeks untuk tabel `user_access_logs`
--
ALTER TABLE `user_access_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT untuk tabel `kecocokan_alt_kriteria`
--
ALTER TABLE `kecocokan_alt_kriteria`
  MODIFY `id_alt_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_sub_kriteria` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `tema`
--
ALTER TABLE `tema`
  MODIFY `id_tema` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `user_access_logs`
--
ALTER TABLE `user_access_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD CONSTRAINT `alternatif_ibfk_1` FOREIGN KEY (`tema_id`) REFERENCES `tema` (`id_tema`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kecocokan_alt_kriteria`
--
ALTER TABLE `kecocokan_alt_kriteria`
  ADD CONSTRAINT `kecocokan_alt_kriteria_ibfk_1` FOREIGN KEY (`f_id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kecocokan_alt_kriteria_ibfk_2` FOREIGN KEY (`f_id_sub_kriteria`) REFERENCES `sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kecocokan_alt_kriteria_ibfk_4` FOREIGN KEY (`f_id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD CONSTRAINT `sub_kriteria_ibfk_1` FOREIGN KEY (`f_id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
