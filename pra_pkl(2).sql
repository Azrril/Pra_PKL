-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2025 at 02:41 PM
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
-- Database: `pra_pkl`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'ahdza', '9381ae632d94198a684fbbf1e015db95'),
(2, 'azrril', '5a49448306d9684b81902cf776a6a092');

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id_brand` int(11) NOT NULL,
  `nama_brand` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id_brand`, `nama_brand`, `gambar`) VALUES
(1, 'Bolt', 'uy'),
(2, 'Wiskas', 'yuhu'),
(3, 'Hills', ''),
(4, 'friskies', '');

-- --------------------------------------------------------

--
-- Table structure for table `metode_pembayaran`
--

CREATE TABLE `metode_pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `metode` varchar(225) NOT NULL,
  `nomor_akun` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `metode_pembayaran`
--

INSERT INTO `metode_pembayaran` (`id_pembayaran`, `metode`, `nomor_akun`) VALUES
(1, 'BCA', 546110976),
(2, 'BNI', 134887669),
(5, 'MANDIRI', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_pengiriman` int(11) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `status` enum('pending','di kemas','di kirim','di terima') NOT NULL,
  `nomor_telp` int(11) NOT NULL,
  `total_akhir` int(11) NOT NULL,
  `bukti_pembayaran` varchar(225) NOT NULL,
  `id_metode_bayar` int(11) NOT NULL,
  `Qty` int(11) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `nama_lengkap`, `id_produk`, `id_user`, `id_pengiriman`, `alamat`, `status`, `nomor_telp`, `total_akhir`, `bukti_pembayaran`, `id_metode_bayar`, `Qty`, `tanggal`) VALUES
(76, 'hffyfy', 3, 10, 1, 'gemuru', 'di kemas', 77766, 56000, '1749467999_Screenshot 2024-05-24 090548.png', 1, 2, '0000-00-00 00:00:00'),
(77, 'budi', 3, 10, 1, 'gemuruh', 'pending', 2147483647, 37000, '1749470943_Screenshot 2024-05-24 090548.png', 1, 1, '0000-00-00 00:00:00'),
(78, 'tegar', 1, 11, 1, 'gemuruh', 'pending', 2147483647, 468000, '1749472036_Screenshot 2024-10-17 155911.png', 5, 1, '0000-00-00 00:00:00');

--
-- Triggers `pembayaran`
--
DELIMITER $$
CREATE TRIGGER `kurangi stok` AFTER INSERT ON `pembayaran` FOR EACH ROW BEGIN
    UPDATE produk 
    SET stok = stok - NEW.Qty
    WHERE id_produk = NEW.id_produk;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pengiriman`
--

CREATE TABLE `pengiriman` (
  `id_pengiriman` int(11) NOT NULL,
  `nama_jasa` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengiriman`
--

INSERT INTO `pengiriman` (`id_pengiriman`, `nama_jasa`, `harga`) VALUES
(1, 'J&T', 18000),
(2, 'JNE', 18000),
(6, 'J&T Express', 20000),
(7, 'J&T Express', 20000);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `gambar` varchar(225) NOT NULL,
  `id_brand` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_admin`, `nama`, `harga`, `gambar`, `id_brand`, `deskripsi`, `stok`) VALUES
(1, 1, 'Bolt Cat Food 200kg', 450000, 'bolt/gambar1.jpg', 1, 'uyu', 10),
(2, 1, 'Bolt Cat Salmon Food 20Kg', 450000, 'bolt/gambar2.jpg', 1, 'uhuy', 2),
(3, 2, 'Bolt Cat Food 800gr', 19000, 'bolt/gambar3.jpg', 1, 'uhuy', 75),
(4, 2, 'Bolt Kitten Food 20Kg', 450000, 'bolt/gambar4.png', 1, 'makanan kucing', 2),
(5, 2, 'Bolt Tuna Donut Cat Food 1Kg', 30000, 'bolt/gambar5.png', 1, 'makanan kucing', 5),
(6, 2, 'Bolt Cat Salmon Repack 800gr', 18500, 'bolt/gambar6.png', 1, 'uhuy', 5),
(7, 2, 'Bolt Cat Food 800gr Repack', 18500, 'bolt/gambar7.jpg', 1, 'puu', 7),
(8, 1, 'Bolt Cat Food Plus 5Kg', 50000, 'bolt/gambar8.png', 1, 'pee', 6),
(9, 2, 'Bolt Cat Mother Kitten Food 500gr', 16000, 'bolt/gambar9.png', 1, 'pee', 8),
(10, 1, 'Whiskas Adult 1+ 950gr Cat Food', 55000, 'whiskas/gambar1.png', 2, 'uhuy', 8),
(11, 2, 'Whiskas\r\n Adult 1+ 1,2Kg Cat Food', 80000, 'whiskas/gambar2.png', 2, '', 8),
(12, 2, 'Whiskas Adult 1+ 85gr Cat Snack', 12500, 'whiskas/gambar3.png', 2, 'pee', -1),
(13, 2, 'Whiskas Adult 1+ 80gr Cat Food Ocean Fish', 13000, 'whiskas/gambar4.png', 2, '', 4),
(14, 1, 'Whiskas Adult 1+ 1Kg Urinary Healt', 65000, 'whiskas/gambar5.png', 2, 'pe', 4),
(15, 1, 'Whiskas Adult 1+ 9,1Kg Cat Food', 200000, 'whiskas/gambar6.png', 2, 'pee', 9),
(16, 1, 'Whiskas Adult 1+ 50gr Cat Snack', 8000, 'whiskas/gambar7.png', 2, 'pe', 5),
(17, 2, 'Whiskas Adult 1+ Kaleng', 30000, 'whiskas/gambar8.png', 2, 'pee', 2),
(18, 2, 'Whiskas Junior 2-12bln 80gr Cat Food', 13000, 'whiskas/gambar9.png', 2, 'pee', 4),
(19, 2, 'Purina Friskies Chef\'s Blend - 1,5Kg', 95000, 'frieskes/gambar1.png', 4, '', 8),
(20, 2, 'Purina Friskies Tender and Crunchy Combo 1,24Kg', 90000, 'frieskes/gambar2.png', 4, '', 22),
(21, 2, 'Purina Friskies Junior 5 Promises 300gr', 25000, 'frieskes/gambar3.png', 4, '', -8),
(22, 2, 'Purina Friskies Sherds Kaleng Souce', 30000, 'frieskes/gambar4.png', 4, '', 21),
(23, 2, 'Purina Friskies Meaty Bits 24 kaleng 3,74Kg Cat Snack', 130000, 'frieskes/gambar5.png', 4, '', 9),
(24, 1, 'Purina Friskies Tourbilions de Souce 1,24kg', 90000, 'frieskes/gambar6.png', 4, '', 9),
(25, 2, 'Purina Friskies Poultry Platter Kaleng', 18000, 'frieskes/gambar7.png', 4, 'kuy beli\r\n', 5),
(26, 1, 'Purina Friskies All Star Faves 43,9gr', 25000, 'frieskes/gambar8.png', 4, '', 3),
(27, 2, 'Purina Friskies Ocean Whitefish and Tuna 368gr', 27000, 'frieskes/gambar9.png', 4, '', 4),
(28, 1, 'Hills Cat Digestive Care i/d 4Kg', 200000, 'hills/gambar1.png', 3, 'produk enak untuk kucing', 0),
(29, 2, 'Hills Cat Digestive/Weight Management w/d 4Kg', 200000, 'hills/gambar2.png', 3, '', 0),
(30, 1, 'Hills Cat Food Sensitivities z/d 4Kg', 200000, 'hills/gambar3.png', 3, '', 3),
(31, 2, 'Hills Cat Urinary Care s/d 4Kg', 200000, 'hills/gambar4.png', 3, '', 5),
(32, 1, 'Hills Cat Prescription Diet - kidney Care k/d 4Kg', 215000, 'hills/gambar5.png', 3, '', 3),
(33, 1, 'Hills Cat Urinary Care Kaleng c/d Multicare 156gr', 20000, 'hills/gambar6.png', 3, '', 6),
(34, 1, 'Hills Science Diet - Adult 1-6 Kaleng 156gr', 20000, 'hills/gambar7.png', 3, '', 7),
(35, 1, 'Hills Healty - Kitten Kaleng 156gr', 20000, 'hills/gambar8.png', 3, '', 8),
(36, 1, 'Hills Cat Kidney Care Kaleng k/d Multicare 156gr', 20000, 'hills/gambar9.png', 3, 'kucing imutsss', 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`) VALUES
(10, 'alif', 'alif@gmail.com', 'f553aa35c5fac63fc6e1c81f3b45b58c'),
(11, 'tegar', 'tegar123@gmail.com', 'd219e623bd90d0074c0904e425faf881');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `password` (`password`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id_brand`);

--
-- Indexes for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_pengiriman` (`id_pengiriman`),
  ADD KEY `id_metode_bayar` (`id_metode_bayar`);

--
-- Indexes for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`id_pengiriman`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_kategori` (`id_brand`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `nama_user` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id_brand` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `pengiriman`
--
ALTER TABLE `pengiriman`
  MODIFY `id_pengiriman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_pengiriman`) REFERENCES `pengiriman` (`id_pengiriman`),
  ADD CONSTRAINT `pembayaran_ibfk_3` FOREIGN KEY (`id_metode_bayar`) REFERENCES `metode_pembayaran` (`id_pembayaran`),
  ADD CONSTRAINT `pembayaran_ibfk_4` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_brand`) REFERENCES `brand` (`id_brand`) ON DELETE CASCADE,
  ADD CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
