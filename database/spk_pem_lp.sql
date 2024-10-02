-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Okt 2024 pada 04.59
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
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama_alternatif`, `alamat`, `latitude`, `longitude`, `gambar`) VALUES
(71, 'Bukit Cinta', 'RMQ7+2W6, Jl. Prof. Dr. Herman Johanes, Penfui, Kec. Kupang Tengah, Kabupaten Kupang, Nusa Tenggara Tim.', '-10.15239467422181', '123.66531600041164', 'bukit_cinta.jpg'),
(72, 'Bukit Humon_Teletubies Lelogama', '8X38+C5V, Unnamed Road, Lelogama, Amfoang Sel., Nusa Tenggara Tim., Lelogama, Kec. Amfoang Sel., Kabupaten Kupang, Nusa Tenggara Tim.', '-9.696149742645233', '123.96539981128755', 'teletubies.JPG'),
(73, 'Taman Nostalgia', 'RJR8+749, Unnamed Road, Oebufu, Oebobo, Kupang City, East Nusa Tenggara 85228', '-10.159139588445775', '123.61537169824815', 'tamnos.jpg'),
(74, 'Pantai Batu Nona', 'VM85+7RP Pantai Nunsui, Oesapa, Lasiana, Kelapa Lima, Kupang City, East Nusa Tenggara', '-10.13399437531897', '123.65977131412339', 'batu_nona.jpg'),
(75, 'Pantai Tablolong', 'Tablolong, Kec. Kupang Bar., Kabupaten Kupang, Nusa Tenggara Tim.', '-10.317648084569043', '123.47524089721787', 'Pantai Tablolong.jpg'),
(76, 'Pantai Oesain', 'JPVR+9Q6, Unnamed Road, Erbaun, Amarasi Bar., Nusa Tenggara Tim., Erbaun, Amarasi Barat, Kupang Regency, East Nusa Tenggara', '-10.356365398345398', '123.74187629805868', 'Pantai Oesain.JPG'),
(77, 'Embung Oelomin', 'PJXM+RGX, Oelomin, Kec. Maulafa, Kabupaten Kupang, Nusa Tenggara Tim.', '-10.250241115332338', '123.63387815626729', 'Embung Oelomin.jpg'),
(78, 'Pantai Koepan', 'Jl. Ikan Tongkol, Lahilai Bissi Kopan, Kec. Kota Lama, Kota Kupang, Nusa Tenggara Tim.', '-10.16110126732182', '123.57682697358145', 'Pantai Koepan.jpg'),
(79, 'Bendungan Raknamo', 'Jl jurusan Bendungan, Raknamo, Kec. Amabi Oefeto, Kabupaten Kupang, Nusa Tenggara Tim.', '-10.119112685751425', '123.93217902292075', 'Bendungan Raknamo.jpg'),
(80, 'Bukit Fatubraon', 'Buraen, Kec. Amarasi Sel., Kabupaten Kupang, Nusa Tenggara Tim.', '-10.308819154490156', '123.87415102895896', 'Bukit Fatubraon.JPG');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bobot_kriteria`
--

CREATE TABLE `bobot_kriteria` (
  `id_bobot` int(11) NOT NULL,
  `C1` float NOT NULL,
  `C2` float NOT NULL,
  `C3` float NOT NULL,
  `C4` float NOT NULL,
  `C5` float NOT NULL,
  `f_id_user` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `bobot_kriteria`
--

INSERT INTO `bobot_kriteria` (`id_bobot`, `C1`, `C2`, `C3`, `C4`, `C5`, `f_id_user`) VALUES
(14, 0.1, 0.2, 0.2, 0.2, 0.3, 5),
(15, 1, 0, 0, 0, 0, 12);

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
(117, 71, 'C1', 31),
(118, 71, 'C2', 34),
(119, 71, 'C3', 39),
(120, 71, 'C4', 43),
(121, 72, 'C1', 33),
(122, 72, 'C2', 36),
(123, 72, 'C3', 39),
(124, 72, 'C4', 43),
(125, 73, 'C1', 32),
(126, 73, 'C2', 34),
(127, 73, 'C3', 39),
(128, 73, 'C4', 44),
(129, 74, 'C1', 31),
(130, 74, 'C2', 35),
(131, 74, 'C3', 39),
(132, 74, 'C4', 42),
(133, 75, 'C1', 33),
(134, 75, 'C2', 35),
(135, 75, 'C3', 40),
(136, 75, 'C4', 42),
(137, 76, 'C1', 33),
(138, 76, 'C2', 34),
(139, 76, 'C3', 39),
(140, 76, 'C4', 42),
(141, 77, 'C1', 33),
(142, 77, 'C2', 34),
(143, 77, 'C3', 40),
(144, 77, 'C4', 45),
(145, 78, 'C1', 29),
(146, 78, 'C2', 34),
(147, 78, 'C3', 39),
(148, 78, 'C4', 42),
(149, 79, 'C1', 30),
(150, 79, 'C2', 34),
(151, 79, 'C3', 39),
(152, 79, 'C4', 45),
(153, 80, 'C1', 32),
(154, 80, 'C2', 34),
(155, 80, 'C3', 41),
(156, 80, 'C4', 43);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` char(2) NOT NULL,
  `nama_kriteria` varchar(50) NOT NULL,
  `jenis_kriteria` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `jenis_kriteria`) VALUES
('C1', 'Jarak lokasi dari titik pengguna ', 'Cost'),
('C2', 'Biaya sewa lokasi', 'Cost'),
('C3', 'Akses menuju lokasi', 'Benefit'),
('C4', 'Tema', 'Benefit');

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
(41, 'Cukup', 'Sulit', 1, 'C3'),
(42, 'Tema', 'Pantai', 3, 'C4'),
(43, 'Tema', 'Pegunungan', 3, 'C4'),
(44, 'Tema', 'Taman', 3, 'C4'),
(45, 'Tema', 'Danau', 3, 'C4');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_tampung`
--

CREATE TABLE `tabel_tampung` (
  `id` int(11) NOT NULL,
  `prio1` varchar(50) NOT NULL,
  `prio2` varchar(50) NOT NULL,
  `prio3` varchar(50) NOT NULL,
  `prio4` varchar(50) NOT NULL,
  `prio5` varchar(50) NOT NULL,
  `f_id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indeks untuk tabel `bobot_kriteria`
--
ALTER TABLE `bobot_kriteria`
  ADD PRIMARY KEY (`id_bobot`),
  ADD KEY `f_id_user` (`f_id_user`);

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
-- Indeks untuk tabel `tabel_tampung`
--
ALTER TABLE `tabel_tampung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `f_id_user` (`f_id_user`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT untuk tabel `bobot_kriteria`
--
ALTER TABLE `bobot_kriteria`
  MODIFY `id_bobot` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `kecocokan_alt_kriteria`
--
ALTER TABLE `kecocokan_alt_kriteria`
  MODIFY `id_alt_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_sub_kriteria` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bobot_kriteria`
--
ALTER TABLE `bobot_kriteria`
  ADD CONSTRAINT `bobot_kriteria_ibfk_1` FOREIGN KEY (`f_id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Ketidakleluasaan untuk tabel `tabel_tampung`
--
ALTER TABLE `tabel_tampung`
  ADD CONSTRAINT `tabel_tampung_ibfk_1` FOREIGN KEY (`f_id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
