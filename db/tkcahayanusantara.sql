-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 18, 2024 at 05:00 PM
-- Server version: 8.0.30
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tkcahayanusantara`
--

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int NOT NULL,
  `nama` varchar(33) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `agama` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email_orangtua` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telpon` varchar(13) COLLATE utf8mb4_general_ci NOT NULL,
  `status_pendaftaran` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `alamat`, `email_orangtua`, `no_telpon`, `status_pendaftaran`) VALUES
(1, 'radja', '2024-06-14', 'L', 'Kristen', 'Tangerang', 'radja@wk', '081awdawd', 'belum diterima'),
(2, 'testing', '2024-06-01', 'Laki-laki', 'testing', 'testing', 'maulbowo@gmail.com', '09109212', 'belum diterima'),
(3, 'maul', '2024-06-15', 'perempuan', 'islam', 'tangerang', 'test@gmail.com', '08121', 'belum diterima'),
(4, 'maul', '2024-06-15', 'perempuan', 'islam', 'tangerang', 'test@gmail.com', '08121', 'belum diterima');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `id_siswa` int DEFAULT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `jumlah_bayar` decimal(10,2) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_siswa`, `tanggal_bayar`, `jumlah_bayar`, `bukti_pembayaran`) VALUES
(1, 1, '2024-06-16', 123123.00, 'Screenshot 2024-06-15 191614.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `level` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `level`) VALUES
(1, 'maul', 'd773599f3267284ba63921979999445e', 'siswa'),
(2, 'radja', '42afcd328885ec205cb656b53194e816', 'admin'),
(5, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(6, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pembayaran`
-- (See below for the actual view)
--
CREATE TABLE `view_pembayaran` (
`agama` varchar(20)
,`alamat` varchar(50)
,`bukti_pembayaran` varchar(255)
,`email_orangtua` varchar(50)
,`id_siswa` int
,`id_transaksi` int
,`jenis_kelamin` varchar(10)
,`jumlah_bayar` decimal(10,2)
,`nama` varchar(33)
,`no_telpon` varchar(13)
,`status_pendaftaran` varchar(20)
,`tanggal_bayar` date
,`tanggal_lahir` date
);

-- --------------------------------------------------------

--
-- Structure for view `view_pembayaran`
--
DROP TABLE IF EXISTS `view_pembayaran`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pembayaran`  AS SELECT `t`.`id_transaksi` AS `id_transaksi`, `s`.`id_siswa` AS `id_siswa`, `s`.`nama` AS `nama`, `s`.`tanggal_lahir` AS `tanggal_lahir`, `s`.`jenis_kelamin` AS `jenis_kelamin`, `s`.`agama` AS `agama`, `s`.`alamat` AS `alamat`, `s`.`email_orangtua` AS `email_orangtua`, `s`.`no_telpon` AS `no_telpon`, `t`.`tanggal_bayar` AS `tanggal_bayar`, `t`.`jumlah_bayar` AS `jumlah_bayar`, `t`.`bukti_pembayaran` AS `bukti_pembayaran`, `s`.`status_pendaftaran` AS `status_pendaftaran` FROM (`siswa` `s` join `transaksi` `t` on((`s`.`id_siswa` = `t`.`id_siswa`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
