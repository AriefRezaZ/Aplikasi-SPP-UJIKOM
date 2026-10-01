-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2026 at 11:37 AM
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
-- Database: `aplikasispp`
--

-- --------------------------------------------------------

--
-- Table structure for table `cek_pembayaran`
--

CREATE TABLE `cek_pembayaran` (
  `entryno` int(11) NOT NULL,
  `nisn` varchar(10) NOT NULL,
  `tgl_terakhir_bayar` date NOT NULL,
  `tgl_sekarang` date NOT NULL,
  `status_pembayaran` enum('Belum Lunas','Sudah Lunas') NOT NULL,
  `jumlah_bulan` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_telp` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cek_pembayaran`
--

INSERT INTO `cek_pembayaran` (`entryno`, `nisn`, `tgl_terakhir_bayar`, `tgl_sekarang`, `status_pembayaran`, `jumlah_bulan`, `nama`, `no_telp`) VALUES
(1, '20260001', '2026-10-01', '2026-10-01', 'Sudah Lunas', '1', 'Dinda', '085557588485'),
(2, '20260001', '0000-00-00', '2026-10-01', 'Belum Lunas', '1', 'Dinda', '085557588485');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id_kelas` varchar(11) NOT NULL,
  `nama_kelas` varchar(10) NOT NULL,
  `komp_keahlian` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kelas`
--

INSERT INTO `tb_kelas` (`id_kelas`, `nama_kelas`, `komp_keahlian`) VALUES
('TPLE001', 'KELAS 1', 'Logika Informatika'),
('TPLE002', 'KELAS 2', 'Komputer Grafik'),
('TPLE003', 'KELAS 3', 'Pemograman Java'),
('TPLE004', 'KELAS 4', 'Sistem Penunjang Keputusan'),
('TPLE005', 'KELAS 5', 'Mobile Programming');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pembayaran`
--

CREATE TABLE `tb_pembayaran` (
  `id_pembayaran` varchar(11) NOT NULL,
  `status` enum('Belum Lunas','Sudah Lunas') NOT NULL,
  `nisn` varchar(10) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `tgl_terakhir_bayar` date NOT NULL,
  `batas_pembayaran` date NOT NULL,
  `jumlah_bulan` varchar(10) NOT NULL,
  `id_spp` varchar(40) NOT NULL,
  `kembalian` varchar(100) NOT NULL,
  `nominal_bayar` varchar(100) NOT NULL,
  `jumlah_bayar` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pembayaran`
--

INSERT INTO `tb_pembayaran` (`id_pembayaran`, `status`, `nisn`, `tgl_bayar`, `tgl_terakhir_bayar`, `batas_pembayaran`, `jumlah_bulan`, `id_spp`, `kembalian`, `nominal_bayar`, `jumlah_bayar`) VALUES
('00001', 'Sudah Lunas', '20260001', '2026-10-01', '2026-10-01', '2026-10-08', '1', '001', '0', '100000', '100000'),
('00002', 'Belum Lunas', '20260001', '2026-10-01', '0000-00-00', '2026-10-10', '1', '001', '', '500000', ''),
('00003', 'Sudah Lunas', '20260002', '2026-09-30', '2026-09-30', '2026-10-03', '2', '002', '50000', '850000', '900000');

-- --------------------------------------------------------

--
-- Table structure for table `tb_petugas`
--

CREATE TABLE `tb_petugas` (
  `id_petugas` varchar(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(30) NOT NULL,
  `nama_petugas` varchar(35) NOT NULL,
  `level` enum('admin','petugas','siswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_petugas`
--

INSERT INTO `tb_petugas` (`id_petugas`, `username`, `password`, `nama_petugas`, `level`) VALUES
('ID0001', 'admin', 'admin', 'administrator', 'admin'),
('ID0011', 'petugas1', 'petugas1', 'petugas 1', 'petugas'),
('ID0012', 'petugas2', 'petugas2', 'petugas 2', 'petugas'),
('ID0013', 'siswa1', 'siswa1', 'Ringgo', 'siswa'),
('ID0014', 'siswa2', 'siswa2', 'Dinda', 'siswa'),
('ID0021', 'AriefReza', 'AriefReza', 'Arief Reza Z', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `nisn` varchar(10) NOT NULL,
  `nis` varchar(8) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `id_kelas` varchar(11) NOT NULL,
  `nama_kelas` varchar(10) NOT NULL,
  `alamat` text NOT NULL,
  `no_telp` varchar(13) NOT NULL,
  `id_spp` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_siswa`
--

INSERT INTO `tb_siswa` (`nisn`, `nis`, `nama`, `id_kelas`, `nama_kelas`, `alamat`, `no_telp`, `id_spp`) VALUES
('20260001', '0001', 'Dinda ', 'TPLE001', 'KELAS 1', 'Jl. Kemandoran gang 5', '085557588485', '001'),
('20260002', '0002', 'Anggoro', 'TPLE002', 'KELAS 2', 'Jl Kemandoran Gang 7', '088485932210', '002');

-- --------------------------------------------------------

--
-- Table structure for table `tb_spp`
--

CREATE TABLE `tb_spp` (
  `id_spp` varchar(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nominal` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cek_pembayaran`
--
ALTER TABLE `cek_pembayaran`
  ADD PRIMARY KEY (`entryno`),
  ADD KEY `nisn` (`nisn`);

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `nisn` (`nisn`),
  ADD KEY `id_spp` (`id_spp`);

--
-- Indexes for table `tb_petugas`
--
ALTER TABLE `tb_petugas`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`nisn`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_spp` (`id_spp`);

--
-- Indexes for table `tb_spp`
--
ALTER TABLE `tb_spp`
  ADD PRIMARY KEY (`id_spp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cek_pembayaran`
--
ALTER TABLE `cek_pembayaran`
  MODIFY `entryno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cek_pembayaran`
--
ALTER TABLE `cek_pembayaran`
  ADD CONSTRAINT `cek_pembayaran_ibfk_1` FOREIGN KEY (`nisn`) REFERENCES `tb_siswa` (`nisn`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD CONSTRAINT `tb_pembayaran_ibfk_2` FOREIGN KEY (`id_spp`) REFERENCES `tb_siswa` (`id_spp`),
  ADD CONSTRAINT `tb_pembayaran_ibfk_3` FOREIGN KEY (`nisn`) REFERENCES `tb_siswa` (`nisn`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD CONSTRAINT `tb_siswa_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
