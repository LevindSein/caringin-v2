-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Mar 2021 pada 13.29
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pasar`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tagihan`
--

CREATE TABLE `penghapusan` (
  `id` int(11) NOT NULL,
  `no_faktur` varchar(20) DEFAULT NULL,
  `nama` varchar(30) DEFAULT NULL,
  `blok` varchar(10) DEFAULT NULL,
  `kd_kontrol` varchar(20) DEFAULT NULL,
  `bln_pakai` varchar(10) DEFAULT NULL,
  `tgl_tagihan` date DEFAULT NULL,
  `bln_tagihan` varchar(10) DEFAULT NULL,
  `thn_tagihan` varchar(10) DEFAULT NULL,
  `tgl_expired` date DEFAULT NULL,
  `stt_lunas` tinyint(1) DEFAULT NULL,
  `stt_bayar` tinyint(1) DEFAULT NULL,
  `stt_prabayar` tinyint(1) NOT NULL DEFAULT 0,
  `awal_airbersih` int(11) DEFAULT NULL,
  `akhir_airbersih` int(11) DEFAULT NULL,
  `pakai_airbersih` int(11) DEFAULT NULL,
  `byr_airbersih` int(11) DEFAULT NULL,
  `pemeliharaan_airbersih` int(11) DEFAULT NULL,
  `beban_airbersih` int(11) DEFAULT NULL,
  `arkot_airbersih` int(11) DEFAULT NULL,
  `sub_airbersih` int(11) NOT NULL DEFAULT 0,
  `dis_airbersih` int(11) NOT NULL DEFAULT 0,
  `ttl_airbersih` int(11) NOT NULL DEFAULT 0,
  `rea_airbersih` int(11) NOT NULL DEFAULT 0,
  `sel_airbersih` int(11) NOT NULL DEFAULT 0,
  `den_airbersih` int(11) NOT NULL DEFAULT 0,
  `daya_listrik` int(11) DEFAULT NULL,
  `awal_listrik` int(11) DEFAULT NULL,
  `akhir_listrik` int(11) DEFAULT NULL,
  `pakai_listrik` int(11) DEFAULT NULL,
  `byr_listrik` int(11) DEFAULT NULL,
  `rekmin_listrik` int(11) DEFAULT NULL,
  `blok1_listrik` int(11) DEFAULT NULL,
  `blok2_listrik` int(11) DEFAULT NULL,
  `beban_listrik` int(11) DEFAULT NULL,
  `bpju_listrik` int(11) DEFAULT NULL,
  `sub_listrik` int(11) NOT NULL DEFAULT 0,
  `dis_listrik` int(11) NOT NULL DEFAULT 0,
  `ttl_listrik` int(11) NOT NULL DEFAULT 0,
  `rea_listrik` int(11) NOT NULL DEFAULT 0,
  `sel_listrik` int(11) NOT NULL DEFAULT 0,
  `den_listrik` int(11) NOT NULL DEFAULT 0,
  `jml_alamat` int(11) DEFAULT NULL,
  `no_alamat` text DEFAULT NULL,
  `sub_keamananipk` int(11) NOT NULL DEFAULT 0,
  `dis_keamananipk` int(11) NOT NULL DEFAULT 0,
  `ttl_keamananipk` int(11) NOT NULL DEFAULT 0,
  `ttl_keamanan` int(11) DEFAULT 0,
  `ttl_ipk` int(11) DEFAULT 0,
  `rea_keamananipk` int(11) NOT NULL DEFAULT 0,
  `sel_keamananipk` int(11) NOT NULL DEFAULT 0,
  `sub_kebersihan` int(11) NOT NULL DEFAULT 0,
  `dis_kebersihan` int(11) NOT NULL DEFAULT 0,
  `ttl_kebersihan` int(11) NOT NULL DEFAULT 0,
  `rea_kebersihan` int(11) NOT NULL DEFAULT 0,
  `sel_kebersihan` int(11) NOT NULL DEFAULT 0,
  `ttl_airkotor` int(11) NOT NULL DEFAULT 0,
  `rea_airkotor` int(11) NOT NULL DEFAULT 0,
  `sel_airkotor` int(11) NOT NULL DEFAULT 0,
  `ttl_lain` int(11) NOT NULL DEFAULT 0,
  `rea_lain` int(11) NOT NULL DEFAULT 0,
  `sel_lain` int(11) NOT NULL DEFAULT 0,
  `sub_tagihan` int(11) NOT NULL DEFAULT 0,
  `dis_tagihan` int(11) NOT NULL DEFAULT 0,
  `ttl_tagihan` int(11) NOT NULL DEFAULT 0,
  `rea_tagihan` int(11) NOT NULL DEFAULT 0,
  `sel_tagihan` int(11) NOT NULL DEFAULT 0,
  `den_tagihan` int(11) NOT NULL DEFAULT 0,
  `stt_denda` int(2) NOT NULL DEFAULT 0,
  `stt_kebersihan` tinyint(1) DEFAULT NULL,
  `stt_keamananipk` tinyint(1) DEFAULT NULL,
  `stt_listrik` tinyint(1) DEFAULT NULL,
  `stt_airbersih` tinyint(1) DEFAULT NULL,
  `stt_airkotor` tinyint(1) DEFAULT NULL,
  `stt_lain` tinyint(1) DEFAULT NULL,
  `ket` text DEFAULT NULL,
  `via_tambah` varchar(30) DEFAULT NULL,
  `stt_publish` tinyint(1) NOT NULL DEFAULT 0,
  `via_publish` varchar(30) DEFAULT NULL,
  `warna_airbersih` tinyint(1) NOT NULL DEFAULT 0,
  `warna_listrik` tinyint(1) NOT NULL DEFAULT 0,
  `review` tinyint(1) NOT NULL DEFAULT 1,
  `reviewer` varchar(30) DEFAULT NULL,
  `lok_tempat` varchar(50) DEFAULT NULL,
  `tgl_hapus` date DEFAULT NULL,
  `via_hapus` varchar(30) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tagihan`
--
ALTER TABLE `penghapusan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tagihan`
--
ALTER TABLE `penghapusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
