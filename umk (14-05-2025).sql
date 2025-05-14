-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 8.0.30 - MySQL Community Server - GPL
-- OS Server:                    Win64
-- HeidiSQL Versi:               12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Membuang struktur basisdata untuk umk
CREATE DATABASE IF NOT EXISTS `umk` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `umk`;

-- membuang struktur untuk table umk.activity_log
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.activity_log: ~0 rows (lebih kurang)
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
	(1, 'Access', 'Ade Erlangga logged in', NULL, 'Login', NULL, 'App\\Models\\User', 1, '{"ip": "127.0.0.1", "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36"}', NULL, '2025-05-14 03:39:45', '2025-05-14 03:39:45'),
	(2, 'Resource', 'Pengajuan U M K Created by Ade Erlangga', 'App\\Models\\PengajuanUMK', 'Created', 1, 'App\\Models\\User', 1, '{"id": 1, "created_at": "2025-05-14 10:56:58", "updated_at": "2025-05-14 10:56:58", "nomor_pengajuan": "SP2UMKU-00001/K1.01/0525", "total_pengajuan": "10000000.00", "tanggal_pengajuan": "2025-05-14"}', NULL, '2025-05-14 03:56:58', '2025-05-14 03:56:58'),
	(3, 'Resource', 'Pengajuan U M K Created by Ade Erlangga', 'App\\Models\\PengajuanUMK', 'Created', 1, 'App\\Models\\User', 1, '{"id": 1, "created_at": "2025-05-14 10:56:58", "updated_at": "2025-05-14 10:56:58", "nomor_pengajuan": "SP2UMKU-00001/K1.01/0525", "total_pengajuan": "10000000.00", "tanggal_pengajuan": "2025-05-14"}', NULL, '2025-05-14 03:56:58', '2025-05-14 03:56:58'),
	(4, 'Resource', 'Transaksi U M K Updated by Ade Erlangga', 'App\\Models\\TransaksiUMK', 'Updated', 12, 'App\\Models\\User', 1, '{"nominal": "60000", "updated_at": "2025-05-14 14:26:54"}', NULL, '2025-05-14 07:26:55', '2025-05-14 07:26:55');

-- membuang struktur untuk table umk.akun_masters
CREATE TABLE IF NOT EXISTS `akun_masters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `akun_bpr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_akun` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=281 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.akun_masters: ~0 rows (lebih kurang)
INSERT INTO `akun_masters` (`id`, `akun_bpr`, `nama_akun`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '0200-100.000001', 'Kas', NULL, NULL, NULL),
	(2, '0200-180.100001', 'Bunga Yadit - Kredit', NULL, NULL, NULL),
	(3, '0200-180.100002', 'Bunga Yadit - Antar Bank Aktiva', NULL, NULL, NULL),
	(4, '0200-120.000001', 'Giro Mandiri', NULL, NULL, NULL),
	(5, '0200-120.000002', 'Giro BRI', NULL, NULL, NULL),
	(6, '0200-140.000001', 'PPAP Antar Bank Aktiva', NULL, NULL, NULL),
	(7, '0200-130.000000', 'Kredit Yang Diberikan', NULL, NULL, NULL),
	(8, '0200-140.000002', 'PPAP Kredit yang diberikan', NULL, NULL, NULL),
	(9, '0200-165.000000', 'Inventaris', NULL, NULL, NULL),
	(10, '0200-166.000002', 'Akumulasi Penyusutan Inventaris Kantor', NULL, NULL, NULL),
	(11, '0200-166.000001', 'Akumulasi Penyusutan Kendaraan Kantor', NULL, NULL, NULL),
	(12, '0200-180.300001', 'Beban Yang Ditangguhkan', NULL, NULL, NULL),
	(13, '0200-180.400001', 'Biaya Dibayar Dimuka - Sewa', NULL, NULL, NULL),
	(14, '0200-180.400002', 'Biaya Dibayar Dimuka - Asuransi', NULL, NULL, NULL),
	(15, '0200-180.400003', 'Biaya Dibayar Dimuka - Gaji', NULL, NULL, NULL),
	(16, '0200-180.900001', 'Uang Muka Kerja', NULL, NULL, NULL),
	(17, '0200-180.900002', 'Asset Lainnya', NULL, NULL, NULL),
	(18, '0200-170.000001', 'Antar Kantor Aktiva', NULL, NULL, NULL),
	(19, '0200-200.100001', 'Titipan Pajak - PPh Psl 4 (2)', NULL, NULL, NULL),
	(20, '0200-200.100002', 'Titipan Pajak - PPh Psl 23', NULL, NULL, NULL),
	(21, '0200-200.100003', 'Titipan Pajak - PPh Psl 21', NULL, NULL, NULL),
	(22, '0200-200.200001', 'Titipan Sembako', NULL, NULL, NULL),
	(23, '0200-200.200002', 'Titipan Iptw', NULL, NULL, NULL),
	(24, '0200-200.200003', 'Bunga Deposito Jatuh Tempo', NULL, NULL, NULL),
	(25, '0200-200.300001', 'Kewajiban Dep.Jt', NULL, NULL, NULL),
	(26, '0200-200.300002', 'Titipan Pensiun PNS', NULL, NULL, NULL),
	(27, '0200-200.300003', 'Titipan Pensiun DP TASPEN', NULL, NULL, NULL),
	(28, '0200-200.300004', 'Titipan Pensiun PT KAI - PLN', NULL, NULL, NULL),
	(29, '0200-200.300005', 'Titipan Dana Kehormatan Veteran', NULL, NULL, NULL),
	(30, '0200-200.300006', 'Titipan Premi Asuransi Kredit Nasabah', NULL, NULL, NULL),
	(31, '0200-200.300007', 'Titipan Angsuran Nasabah', NULL, NULL, NULL),
	(32, '0200-270.400001', 'Bunga Kredit Diterima Dimuka', NULL, NULL, NULL),
	(33, '0200-200.900001', 'Biaya Yang Masih Harus Dibayar', NULL, NULL, NULL),
	(34, '0200-200.900002', 'Kewajiban Segera Lainnya', NULL, NULL, NULL),
	(35, '0200-200.900102', 'Kewajiban Segera Lainnya', NULL, NULL, NULL),
	(36, '0200-270.100001', 'Bunga Deposito Yang Harus Dibayar', NULL, NULL, NULL),
	(37, '0200-210.000000', 'Tabungan Umum', NULL, NULL, NULL),
	(38, '0200-211.000000', 'Tabungan Pensiun', NULL, NULL, NULL),
	(39, '0200-220.000000', 'Deposito', NULL, NULL, NULL),
	(40, '0200-240.000000', 'Antar Bank Pasiva', NULL, NULL, NULL),
	(41, '0200-270.900001', 'Kewajiban Imbalan Kerja - Jangka Pendek', NULL, NULL, NULL),
	(42, '0200-270.900002', 'Kewajiban Imbalan Kerja - Pasca Kerja', NULL, NULL, NULL),
	(43, '0200-260.000001', 'Antar Kantor Pasiva', NULL, NULL, NULL),
	(44, '0200-302.000001', 'Laba Tahun Lalu', NULL, NULL, NULL),
	(45, '0200-303.000001', 'Rugi Tahun Lalu', NULL, NULL, NULL),
	(46, '0200-307.112001', 'Pendapatan Bunga Giro dari Bank Lain', NULL, NULL, NULL),
	(47, '0200-307.120001', 'Pendapatan Bunga - Kredit Kepada Bukan Bank', NULL, NULL, NULL),
	(48, '0200-307.131001', 'Pendapatan Bunga - Provisi', NULL, NULL, NULL),
	(49, '0200-307.149001', 'Pendapatan Administrasi Kredit', NULL, NULL, NULL),
	(50, '0200-307.149002', 'Pendapatan Administrasi Tabungan', NULL, NULL, NULL),
	(51, '0200-307.149003', 'Denda', NULL, NULL, NULL),
	(52, '0200-307.149004', 'Pendapatan Kredit Hapus Buku', NULL, NULL, NULL),
	(53, '0200-307.149005', 'Pendapatan Pemulihan PPAP', NULL, NULL, NULL),
	(54, '0200-307.149006', 'Pendapatan Lain - lain ', NULL, NULL, NULL),
	(55, '0200-307.291001', 'Keuntungan Penjualan Aktiva Tetap', NULL, NULL, NULL),
	(56, '0200-307.292001', 'Pendapatan Bunga Antar Kantor', NULL, NULL, NULL),
	(57, '0200-307.294001', 'Pendapatan Non Operasional Lainnya', NULL, NULL, NULL),
	(58, '0200-308.166001', 'Beban Bunga Tabungan - Kepada Bank Lain', NULL, NULL, NULL),
	(59, '0200-308.167001', 'Beban Bunga Deposito - Kepada Bank Lain', NULL, NULL, NULL),
	(60, '0200-308.171001', 'Beban Bunga Tabungan - Bukan Bank', NULL, NULL, NULL),
	(61, '0200-308.172001', 'Beban Bunga Deposito- Bukan Bank', NULL, NULL, NULL),
	(62, '0200-308.241001', 'Beban PPAP - Antar Bank Aktiva', NULL, NULL, NULL),
	(63, '0200-308.241002', 'Beban PPAP - Kredit Yang Diberikan', NULL, NULL, NULL),
	(64, '0200-308.243001', 'Beban Penyusutan Inventaris', NULL, NULL, NULL),
	(65, '0200-308.243002', 'Beban Penyusutan Kendaraan', NULL, NULL, NULL),
	(66, '0200-308.243003', 'Beban Amortisasi Aset Tidak Berwujud', NULL, NULL, NULL),
	(67, '0200-308.245001', 'Beban Yang Ditangguhkan', NULL, NULL, NULL),
	(68, '0200-308.208001', 'Beban Promosi dan Marketing', NULL, NULL, NULL),
	(69, '0200-308.208001', 'Promosi & Edu - Iklan & Promosi', NULL, NULL, NULL),
	(70, '0200-308.201001', 'Beban Honor Direksi', NULL, NULL, NULL),
	(71, '0200-308.201002', 'Beban Gaji Pokok Karyawan', NULL, NULL, NULL),
	(72, '0200-308.201003', 'Beban Honor Karyawan', NULL, NULL, NULL),
	(73, '0200-308.201004', 'Beban Tunjangan Kemahalan Umum', NULL, NULL, NULL),
	(74, '0200-308.201005', 'Beban Tunjangan Perusahaan', NULL, NULL, NULL),
	(75, '0200-308.201006', 'Beban Tunjangan Jabatan', NULL, NULL, NULL),
	(76, '0200-308.201007', 'Beban Tunjangan Prestasi', NULL, NULL, NULL),
	(77, '0200-308.201008', 'Beban Tunjangan Cuti', NULL, NULL, NULL),
	(78, '0200-308.201009', 'Beban Tunjangan Hari Raya', NULL, NULL, NULL),
	(79, '0200-308.201010', 'Beban Tunjangan PPh 21 Karyawan', NULL, NULL, NULL),
	(80, '0200-308.201011', 'Beban Pakaian Seragam', NULL, NULL, NULL),
	(81, '0200-308.201012', 'Beban Penghargaan Masa Bhakti', NULL, NULL, NULL),
	(82, '0200-308.201013', 'Beban Bonus', NULL, NULL, NULL),
	(83, '0200-308.202001', 'Beban Honor Dekom', NULL, NULL, NULL),
	(84, '0200-308.202002', 'Beban Tunjangan PPh21 Dekom', NULL, NULL, NULL),
	(85, '0200-308.209001', 'Beban Lembur', NULL, NULL, NULL),
	(86, '0200-308.209002', 'Beban Bantuan Rawat Jalan', NULL, NULL, NULL),
	(87, '0200-308.206101', 'Beban Pendidikan dan Pelatihan', NULL, NULL, NULL),
	(88, '0200-308.210001', 'Beban Sewa Kantor', NULL, NULL, NULL),
	(89, '0200-308.210002', 'Beban Sewa Inventaris', NULL, NULL, NULL),
	(90, '0200-308.190001', 'Beban Imbalan Kerja - Premi Rawat Inap', NULL, NULL, NULL),
	(91, '0200-308.190002', 'Beban Imbalan Kerja - Premi Pensiun', NULL, NULL, NULL),
	(92, '0200-308.190003', 'Beban Imbalan Kerja - Premi Jamsostek', NULL, NULL, NULL),
	(93, '0200-308.190004', 'Beban Asuransi Kerugian - Gedung & Inventaris', NULL, NULL, NULL),
	(94, '0200-308.190005', 'Beban Asuransi Kerugian - Kendaraan', NULL, NULL, NULL),
	(95, '0200-308.230103', 'Beban Perawatan Kendaraan', NULL, NULL, NULL),
	(96, '0200-308.230103', 'Beban Perawatan Inventaris', NULL, NULL, NULL),
	(97, '0200-308.230103', 'Beban Perawatan Gedung', NULL, NULL, NULL),
	(98, '0200-308.250001', 'Beban Jasa Kontrak Pihak Ketiga', NULL, NULL, NULL),
	(99, '0200-308.250007', 'Beban Pemotongan Angsuran', NULL, NULL, NULL),
	(100, '0200-308.250009', 'Beban Jasa Akuntan Publik', NULL, NULL, NULL),
	(101, '0200-308.250014', 'Beban Lainnya', NULL, NULL, NULL),
	(102, '0200-308.220001', 'Beban Pajak Bumi dan Bangunan', NULL, NULL, NULL),
	(103, '0200-308.220102', 'Beban Pajak Kendaraan Bermotor', NULL, NULL, NULL),
	(104, '0200-308.269101', 'Beban Relasi', NULL, NULL, NULL),
	(105, '0200-308.269102', 'Beban Kerohanian dan Olah Raga', NULL, NULL, NULL),
	(106, '0200-308.269103', 'Beban Pembinaan Nasabah', NULL, NULL, NULL),
	(107, '0200-308.301001', 'Beban Kerugian Penjualan Aset', NULL, NULL, NULL),
	(108, '0200-308.302001', 'Beban Bunga Antar Kantor', NULL, NULL, NULL),
	(109, '2.160.1', 'Tabungan pihak ketiga', NULL, NULL, NULL),
	(110, '2.160.1.01', 'Biaya Bunga - Tabungan Umum', NULL, NULL, NULL),
	(111, '2.160.1.02', 'Biaya Bunga - Tabungan Pensiun', NULL, NULL, NULL),
	(112, '2.160.1.03', 'Biaya Bunga - TabunganKu', NULL, NULL, NULL),
	(113, '2.160.1.04', 'Biaya Bunga - Tabungan Kredit Umum', NULL, NULL, NULL),
	(114, '2.160.1.05', 'Biaya Bunga - Tabungan Kredit Pasar', NULL, NULL, NULL),
	(115, '2.160.1.06', 'Biaya Bunga - Tabungan Kredit Desa', NULL, NULL, NULL),
	(116, '2.160.1.07', 'Biaya Bunga - Tabungan Kredit Koperasi', NULL, NULL, NULL),
	(117, '2.160.1.08', 'Biaya Bunga - Tabungan Kredit PKM', NULL, NULL, NULL),
	(118, '2.160.1.09', 'Biaya Bunga - Tabungan Kredit BKP', NULL, NULL, NULL),
	(119, '2.160.1.10', 'Biaya Bunga - Tabungan Kredit P2K', NULL, NULL, NULL),
	(120, '2.160.1.11', 'Biaya Bunga - Tabungan ABP', NULL, NULL, NULL),
	(121, '2.160.2', 'Deposito pihak ketiga', NULL, NULL, NULL),
	(122, '2.160.3', 'Pinjaman yang diterima pihak ketiga', NULL, NULL, NULL),
	(123, '2.160.3.3', 'Biaya Bunga - Lainnya', NULL, NULL, NULL),
	(124, '2.160.4', 'Lainnya pihak ketiga', NULL, NULL, NULL),
	(125, '2.160.4.1', 'Biaya Bunga - Premi LPS', NULL, NULL, NULL),
	(126, '2.160.4.3', 'Biaya Bunga - Biaya Bunga phk ketiga lainnya', NULL, NULL, NULL),
	(127, '2.160.5', 'Tabungan bank lain', NULL, NULL, NULL),
	(128, '2.160.6', 'Deposito bank lain', NULL, NULL, NULL),
	(129, '2.160.7', 'Pinjaman yang Diterima', NULL, NULL, NULL),
	(130, '2.160.8', 'Lainnya', NULL, NULL, NULL),
	(131, '2.160.9', 'Koreksi Atas Pendapatan Bunga', NULL, NULL, NULL),
	(132, '2.160.9.1', 'Koreksi Pend Bunga', NULL, NULL, NULL),
	(133, '2.160.9.2', 'Koreksi Pend Provisi & Adm', NULL, NULL, NULL),
	(134, '2.170', 'Biaya Transaksi', NULL, NULL, NULL),
	(135, '2.170.1', 'Biaya transaksi dari bank lain', NULL, NULL, NULL),
	(136, '2.170.1.1', 'Biaya Transaksi - Tabungan bank lain', NULL, NULL, NULL),
	(137, '2.170.1.2', 'Biaya Transaksi - Deposito bank lain', NULL, NULL, NULL),
	(138, '2.170.1.3', 'Biaya Transaksi - Pinjaman yang diterima', NULL, NULL, NULL),
	(139, '2.170.2', 'Biaya transaksi pihak ketiga', NULL, NULL, NULL),
	(140, '2.170.2.1', 'Biaya Transaksi - Tabungan pihak ketiga', NULL, NULL, NULL),
	(141, '2.170.2.2', 'Biaya Transaksi - Deposito pihak ketiga', NULL, NULL, NULL),
	(142, '2.170.2.3', 'Biaya Transaksi - Kredit pihak ketiga', NULL, NULL, NULL),
	(143, '2.182', 'Transaksi PVA', NULL, NULL, NULL),
	(144, '2.190', 'Premi Asuransi', NULL, NULL, NULL),
	(145, '2.190.01', 'Premi Asuransi - DPLK dan THT', NULL, NULL, NULL),
	(146, '2.190.02', 'Premi Asuransi - Cash In Transit (CIT)', NULL, NULL, NULL),
	(147, '2.190.05', 'Premi Asuransi - Jamsostek BPJS', NULL, NULL, NULL),
	(148, '2.190.07', 'Premi Asuransi - Kebakaran', NULL, NULL, NULL),
	(149, '2.190.08', 'Premi Asuransi - Kendaraan', NULL, NULL, NULL),
	(150, '2.190.11', 'Premi Asuransi - Penjaminan LPS', NULL, NULL, NULL),
	(151, '2.190.12', 'Premi Asuransi - Kerugian', NULL, NULL, NULL),
	(152, '2.190.13', 'Premi Rawat Inap', NULL, NULL, NULL),
	(153, '2.190.14', 'Premi Pensiun', NULL, NULL, NULL),
	(154, '2.190.15', 'Premi Asuransi - BPJS Kesehatan', NULL, NULL, NULL),
	(155, '2.190.99', 'Premi Asuransi - Lainnya', NULL, NULL, NULL),
	(156, '2.200', 'Biaya Tenaga Kerja (BTK)', NULL, NULL, NULL),
	(157, '2.201', 'BTK - Gaji, Upah dan Honorarium', NULL, NULL, NULL),
	(158, '2.201.03', 'BTK - Gaji Pokok', NULL, NULL, NULL),
	(159, '2.201.06', 'BTK - Tunjangan Jabatan / Fungsional', NULL, NULL, NULL),
	(160, '2.201.09', 'BTK - Tunjangan Perbaikan Penghasilan', NULL, NULL, NULL),
	(161, '2.201.16', 'BTK - Tunjangan Prestasi', NULL, NULL, NULL),
	(162, '2.201.17', 'BTK - Tunjangan Kinerja Individu Karyawan', NULL, NULL, NULL),
	(163, '2.201.19', 'BTK - Pembulatan', NULL, NULL, NULL),
	(164, '2.201.22', 'BTK - Tunjangan Hari Raya', NULL, NULL, NULL),
	(165, '2.201.23', 'BTK - Honorarium', NULL, NULL, NULL),
	(166, '2.201.24', 'BTK - Tunjangan Kemahalan', NULL, NULL, NULL),
	(167, '2.201.26', 'BTK - Tunjangan Perusahaan', NULL, NULL, NULL),
	(168, '2.201.27', 'BTK - Tunjangan Cuti', NULL, NULL, NULL),
	(169, '2.201.28', 'BTK - Tunjangan Cuti Besar', NULL, NULL, NULL),
	(170, '2.201.29', 'BTK - Tunjangan Pakaian Seragam', NULL, NULL, NULL),
	(171, '2.201.30', 'BTK - Tunjangan PPh 21 Direksi Karyawan', NULL, NULL, NULL),
	(172, '2.201.31', 'BTK - Tunjangan Penghargaan Masa Bakti', NULL, NULL, NULL),
	(173, '2.201.32', 'BTK - Honor Direksi', NULL, NULL, NULL),
	(174, '2.201.99', 'BTK - Tunjangan Lainnya', NULL, NULL, NULL),
	(175, '2.202', 'BTK - Honorarium', NULL, NULL, NULL),
	(176, '2.202.01', 'BTK - Honor Dewan Pengawas', NULL, NULL, NULL),
	(177, '2.202.02', 'BTK - Tunjangan PPh 21 Dekom', NULL, NULL, NULL),
	(178, '2.203', 'BTK - Lainnya', NULL, NULL, NULL),
	(179, '2.203.02', 'BTK - Uang Lembur', NULL, NULL, NULL),
	(180, '2.203.03', 'BTK - Uang Transport', NULL, NULL, NULL),
	(181, '2.203.05', 'BTK - Tunjangan Insentif kredit pensiun', NULL, NULL, NULL),
	(182, '2.203.06', 'BTK - Insentif Penagihan', NULL, NULL, NULL),
	(183, '2.203.11', 'BTK - Prestasi Kerja', NULL, NULL, NULL),
	(184, '2.203.12', 'BTK - Dana Pensiun', NULL, NULL, NULL),
	(185, '2.203.15', 'BTK - Perjalanan Dinas Pegawai', NULL, NULL, NULL),
	(186, '2.203.16', 'BTK - Tunjangan Rawat Jalan', NULL, NULL, NULL),
	(187, '2.203.17', 'BTK - Imbalan Jasa Pasca Kerja', NULL, NULL, NULL),
	(188, '2.203.18', 'BTK - Penyisihan Cadangan Tantiem & Jasa Produksi', NULL, NULL, NULL),
	(189, '2.203.99', 'BTK - Lain-lain', NULL, NULL, NULL),
	(190, '2.206', 'Biaya Pendidikan', NULL, NULL, NULL),
	(191, '2.206.01', 'Pendidikan - Kursus dan Seminar', NULL, NULL, NULL),
	(192, '2.206.99', 'Pendidikan - Lainnya', NULL, NULL, NULL),
	(193, '2.207', 'Biaya Penelitian dan Pengembangan', NULL, NULL, NULL),
	(194, '2.207.01', 'P & P - Pembukaan Cabang Baru', NULL, NULL, NULL),
	(195, '2.207.02', 'P & P - Teknologi Informasi', NULL, NULL, NULL),
	(196, '2.207.03', 'P & P - Pengembangan Produk Baru', NULL, NULL, NULL),
	(197, '2.207.99', 'P & P - Lainnya', NULL, NULL, NULL),
	(198, '2.208', 'Biaya Promosi dan Edukasi', NULL, NULL, NULL),
	(199, '2.208.01', 'Promosi & Edu - Iklan & Promosi', NULL, NULL, NULL),
	(200, '2.208.02', 'Promosi & Edu - Insentif Deposito', NULL, NULL, NULL),
	(201, '2.208.03', 'Promosi & Edu - Pemasaran', NULL, NULL, NULL),
	(202, '2.208.99', 'Promosi & Edu - Lainnya', NULL, NULL, NULL),
	(203, '2.210', 'Sewa', NULL, NULL, NULL),
	(204, '2.210.01', 'Sewa - Kantor', NULL, NULL, NULL),
	(205, '2.210.99', 'Sewa - Lainnya', NULL, NULL, NULL),
	(206, '2.220', 'Pajak-Pajak (tdk termasuk pajak penghasilan)', NULL, NULL, NULL),
	(207, '2.220.01', 'Pajak - Kendaraan Bermotor', NULL, NULL, NULL),
	(208, '2.220.02', 'Pajak - Bumi dan Bangunan', NULL, NULL, NULL),
	(209, '2.220.99', 'Pajak - lainnya', NULL, NULL, NULL),
	(210, '2.230', 'Pemeliharaan dan Perbaikan', NULL, NULL, NULL),
	(211, '2.230.02', 'Pemeliharaan - Gedung Kantor', NULL, NULL, NULL),
	(212, '2.230.08', 'Pemeliharaan - Kendaraan Dinas', NULL, NULL, NULL),
	(213, '2.230.11', 'Pemeliharaan - Inventaris', NULL, NULL, NULL),
	(214, '2.230.99', 'Pemeliharaan - Lainnya', NULL, NULL, NULL),
	(215, '2.240', 'Penyusutan / Penghapusan', NULL, NULL, NULL),
	(216, '2.241', 'Aktiva Produktif', NULL, NULL, NULL),
	(217, '2.241.1', 'PPAP Kredit Yang Diberikan', NULL, NULL, NULL),
	(218, '2.241.2', 'PPAP Rekening ABA', NULL, NULL, NULL),
	(219, '2.241.3', 'Restrukturisasi Kredit', NULL, NULL, NULL),
	(220, '2.243', 'Aktiva Tetap dan Inventaris', NULL, NULL, NULL),
	(221, '2.243.1', 'Penyusutan Gedung', NULL, NULL, NULL),
	(222, '2.243.2', 'Penyusutan Inventaris', NULL, NULL, NULL),
	(223, '2.243.2.1', 'Inventaris Golongan I', NULL, NULL, NULL),
	(224, '2.243.2.2', 'Inventaris Golongan II', NULL, NULL, NULL),
	(225, '2.243.2.3', 'Inventaris Golongan III', NULL, NULL, NULL),
	(226, '2.243.2.4', 'Aktiva Tidak Berwujud', NULL, NULL, NULL),
	(227, '2.245', ' Biaya Yang Ditangguhkan', NULL, NULL, NULL),
	(228, '2.245.1', 'Kerugian Aktiva Produktif', NULL, NULL, NULL),
	(229, '2.245.2', 'Kerugian Aktiva Tetap', NULL, NULL, NULL),
	(230, '2.250', 'Barang dan Jasa', NULL, NULL, NULL),
	(231, '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', NULL, NULL, NULL),
	(232, '2.250.02', 'Barang dan Jasa - Listrik', NULL, NULL, NULL),
	(233, '2.250.03', 'Barang dan Jasa - Air / PDAM', NULL, NULL, NULL),
	(234, '2.250.04', 'Barang dan Jasa - Telepon / Telegram / Telex', NULL, NULL, NULL),
	(235, '2.250.05', 'Barang dan Jasa - Materai dan Perangko', NULL, NULL, NULL),
	(236, '2.250.06', 'Barang dan Jasa - Alat Tulis Kantor (ATK)', NULL, NULL, NULL),
	(237, '2.250.07', 'Barang dan Jasa - Percetakan', NULL, NULL, NULL),
	(238, '2.250.08', 'Barang dan Jasa - Koran dan Majalah Buku', NULL, NULL, NULL),
	(239, '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', NULL, NULL, NULL),
	(240, '2.250.11', 'Barang dan Jasa - Jasa Konsultan & Akuntan Publik', NULL, NULL, NULL),
	(241, '2.250.12', 'Barang dan Jasa - Pakaian Dinas', NULL, NULL, NULL),
	(242, '2.250.15', 'Barang dan Jasa - Makan dan minum', NULL, NULL, NULL),
	(243, '2.250.19', 'Barang dan Jasa - Foto Copy', NULL, NULL, NULL),
	(244, '2.250.23', 'Barang dan Jasa - Penggantian Pulsa HP', NULL, NULL, NULL),
	(245, '2.250.24', 'Barang dan Jasa - Peralatan dan Perlengkapan Kantor', NULL, NULL, NULL),
	(246, '2.250.26', 'Barang dan Jasa - Keamanan dan Kebersihan', NULL, NULL, NULL),
	(247, '2.250.34', 'Barang dan Jasa - BBM Kendaraan Roda 2', NULL, NULL, NULL),
	(248, '2.250.36', 'Barang dan Jasa - Pos dan Pengiriman', NULL, NULL, NULL),
	(249, '2.250.38', 'Barang dan Jasa - Kontrak Pihak Ketiga', NULL, NULL, NULL),
	(250, '2.250.39', 'Barang dan Jasa - Penagihan Kredit', NULL, NULL, NULL),
	(251, '2.250.40', 'Barang dan Jasa - Kompensasi DAPEM', NULL, NULL, NULL),
	(252, '2.250.41', 'Barang dan Jasa - Jaringan VPN', NULL, NULL, NULL),
	(253, '2.250.42', 'Barang dan Jasa - Konsultasi Ahli', NULL, NULL, NULL),
	(254, '2.250.99', 'Barang dan Jasa - Lainnya', NULL, NULL, NULL),
	(255, '2.269', 'Biaya Operasional Lain', NULL, NULL, NULL),
	(256, '2.269.01', 'Biaya Ops. Lain - Administrasi ABA', NULL, NULL, NULL),
	(257, '2.269.03', 'Biaya Ops. Lain - Promosi', NULL, NULL, NULL),
	(258, '2.269.14', 'Biaya Ops. Lain - By. Pungutan Ojk', NULL, NULL, NULL),
	(259, '2.269.15', 'Biaya Ops. Lain - By. Notaris', NULL, NULL, NULL),
	(260, '2.269.18', 'Biaya Ops. Lain - Parkir Toll Karcis', NULL, NULL, NULL),
	(261, '2.269.21', 'Biaya Ops. Lain - Partisipasi dan Kegiatan', NULL, NULL, NULL),
	(262, '2.269.22', 'Biaya Ops. Lain - Pembinaan Nasabah', NULL, NULL, NULL),
	(263, '2.269.23', 'Biaya Ops Lain - Kerohanian dan Olah Raga', NULL, NULL, NULL),
	(264, '2.269.99', 'Biaya Ops. Lain - Lainnya', NULL, NULL, NULL),
	(265, '2.300', 'Beban Non Operasional', NULL, NULL, NULL),
	(266, '2.301', 'Kerugian Penjualan Aktiva Tetap', NULL, NULL, NULL),
	(267, '2.302', 'Bunga Antar Kantor', NULL, NULL, NULL),
	(268, '2.302.1', 'Bunga AK Murni', NULL, NULL, NULL),
	(269, '2.302.2', 'Bunga AK Pinjaman', NULL, NULL, NULL),
	(270, '2.303', 'Selisih Kurs', NULL, NULL, NULL),
	(271, '2.304', 'Beban Non Ops. Lainnya', NULL, NULL, NULL),
	(272, '2.304.03', 'Beban Non Ops. Lain - Sumbangan', NULL, NULL, NULL),
	(273, '2.304.04', 'Beban Non Ops. Lain - Koreksi Kas', NULL, NULL, NULL),
	(274, '2.304.11', 'Beban Non Ops. Lain - Beban Relasi', NULL, NULL, NULL),
	(275, '2.304.91', 'Beban Non Ops Lain - Rugi Penjualan AYDA', NULL, NULL, NULL),
	(276, '2.304.92', 'Beban Non Ops Lain - Penurunan nilai agunan', NULL, NULL, NULL),
	(277, '2.304.93', 'Beban Non Ops Lain - Penurunan nilai AYDA', NULL, NULL, NULL),
	(278, '2.304.99', 'Beban Non Ops. Lain - Lainnya', NULL, NULL, NULL),
	(279, '1.180.40.05', 'Dibayar di muka - Pembelian Barang', NULL, NULL, NULL),
	(280, '1.270.99.20', 'Pasiva Lainnya - Cadangan Dana Pendidikan', NULL, NULL, NULL);

-- membuang struktur untuk table umk.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.cache: ~2 rows (lebih kurang)
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('umk_inventaris_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:5;', 1747193987),
	('umk_inventaris_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1747193987;', 1747193987);

-- membuang struktur untuk table umk.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.cache_locks: ~0 rows (lebih kurang)

-- membuang struktur untuk table umk.data_kendaraan
CREATE TABLE IF NOT EXISTS `data_kendaraan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jenis_kendaraan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `merk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_rangka` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_registrasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_bpkb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kantor_cabang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jadwal_pajak` date DEFAULT NULL,
  `perusahaan_asuransi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asuransi_mulai` date DEFAULT NULL,
  `asuransi_akhir` date DEFAULT NULL,
  `deleted_at` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.data_kendaraan: ~0 rows (lebih kurang)
INSERT INTO `data_kendaraan` (`id`, `jenis_kendaraan`, `merk`, `type`, `no_rangka`, `no_registrasi`, `no_bpkb`, `kantor_cabang`, `jadwal_pajak`, `perusahaan_asuransi`, `asuransi_mulai`, `asuransi_akhir`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(1, 'Mobil', 'TOYOTA', 'W101RE-LBVFJ 1.5 Q CVT TSS', 'W101RE-LBVFJ 1.5 Q CVT TSS', 'B 2921 KZV', 'S- 03608866', 'Cikarang', '2025-06-15', 'Asuransi Staco Mandiri', '2024-07-05', '2025-07-05', NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(2, 'Mobil', 'TOYOTA', 'W101RE-LBMFJ 1.5.G CVT', 'MHKAB1BY1PK078041', 'B 2364 KIY', 'U- 06026501', 'KPO', '2026-01-15', 'Jasa Raharja Putera', '2025-01-23', '2026-01-23', NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(3, 'Mobil', 'TOYOTA', 'MAGA10R-BRXM8D 2.0 V CVT', 'MHAAAAA7P0020260', 'B 2669 KIV', 'U- 03985641', 'KPM (Pak Iwan)', '2026-12-05', 'Jasa Raharja Putera', '2024-12-12', '2025-12-12', NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(4, 'Mobil', 'HONDA', 'BR-V DG3 1.5L PRE-HS CVT', 'MHR063880NJ301494', 'B 2584 KZR', 'S- 02392608', 'KPM (Pak Suryo)', '2025-03-09', 'Jasa Raharja Putera', '2024-10-25', '2025-10-25', NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(5, 'Mobil', 'HONDA', 'BR-V DG3 1.5L PPE-HS CVT', 'MHRD63880PJ301420', 'B 2653 KIY', 'U- 06031810', 'KPM (Bu Indri)', '2026-01-15', 'Jasa Raharja Putera', '2025-01-23', '2026-01-23', NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(6, 'Mobil', 'TOYOTA', 'RUSH 1.5 S A/T (F800RE-G0GFJ)', 'MHKE8FB3JPK092953', 'B 2903 KIY', 'U- 06049867', 'KPM (GMO)', '2026-01-17', 'Jasa Raharja Putera', '2025-01-23', '2026-01-23', NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(7, 'Mobil', 'TOYOTA', 'RUSH 1.5 S A/T (F800RE-GQGFJ)', 'MHKE8FB3JPK092106', 'B 2674 KIY', 'U- 06049854', 'KPM (GMB)', '2026-01-17', 'Jasa Raharja Putera', '2025-01-23', '2026-01-23', NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(8, 'Mobil', 'TOYOTA', 'W101RE-LBMFJ 1.5 G CVT', 'MHKAB1BY2PK074497', 'B 2400 KIV', 'U- 03985644', 'BOGOR', '2026-12-05', 'Jasa Raharja Putera', '2024-12-12', '2025-12-12', NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(9, 'Mobil', 'TOYOTA', 'AVANZA 1.5 VELOZ A/T', 'MHKM5FB4JMK032492', 'B 2761 KZH', 'Q- 07918286', 'JAKTIM', '2025-07-27', NULL, NULL, NULL, NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(10, 'Mobil', 'TOYOTA', 'AVANZA 1.3 G M/T', 'MHKM5EA3JKL166668', 'B 1041 KGA', 'Q- 01681694', 'DEPOK', '2025-10-23', NULL, NULL, NULL, NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(11, 'Mobil', 'TOYOTA', 'W101RE-LBMFJ 1.5 G CVT', 'MHKAB1BY6NK013439', 'B 2583 KZT', 'S- 03419064', 'Karawang', NULL, NULL, NULL, NULL, NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(12, 'Mobil', 'TOYOTA', 'W101RE-LBMFJ 1.5 G CVT', 'MHKAB1BY0RK109475', 'B 2217 KRN', 'Belum jadi', 'Tangerang', NULL, NULL, NULL, NULL, NULL, '2025-04-15 21:13:00', '2025-04-15 21:13:00'),
	(13, 'Motor', 'HONDA', 'C1MO2N42L1 A/T', 'MH1JMB114PK089995', 'B 5931 KCU', 'U- 00146113', 'Cikarang', '2025-07-25', 'Jasa Raharja Putera', '2024-09-09', '2025-09-09', NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28'),
	(14, 'Motor', 'HONDA', 'AFK12U21C08', 'MH1JBP116LK803977', 'B 4124 KSS', 'Q- 02734187', 'JAKTIM', '2024-11-03', NULL, NULL, NULL, NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28'),
	(15, 'Motor', 'HONDA', 'AFP12W21C08 M/T', 'MH1JBM12186K003880', 'B 3355 KZM', 'M- 07514911', 'KPO', '2026-04-13', 'Jasa Raharja Putera', '2022-09-22', '2024-09-20', NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28'),
	(16, 'Motor', 'HONDA', 'AFX12U21C08 M/T', 'MH1JBP113LK800082', 'B 4980 KTA', 'Q- 06249440', 'KPO', '2025-01-06', NULL, NULL, NULL, NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28'),
	(17, 'Motor', 'HONDA', 'AFX12U21C08 M/T', 'MH1JBP119JK689051', 'B 4780 KLO', 'O- 06065821', 'KPO', '2026-02-10', 'Jasa Raharja Putera', '2025-01-15', '2026-01-15', NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28'),
	(18, 'Motor', 'HONDA', 'NF125 TR', 'MH1JB9129BK651132', 'B 3544 KBI', 'I- 00058619', 'JAKTIM', '2026-06-13', NULL, NULL, NULL, NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28'),
	(19, 'Motor', 'HONDA', 'D1B02N26L2', 'MH1JFZ138KK277920', 'B 4978 KOL', 'P- 05978442', 'DEPOK', '2025-09-13', 'Jasa Raharja Putera', '2022-09-20', '2023-09-20', NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28'),
	(20, 'Motor', 'HONDA', 'H1B02N42LO A/T', 'MH1JM9134PK163524', 'B 5090 KDB', 'U- 00726099', 'KPM', '2025-08-19', NULL, NULL, NULL, NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28'),
	(21, 'Motor', 'HONDA', 'AFP12W21C08 M/T', 'MH1JBM118FK084735', 'T 5642 ND', 'M- 03421292', 'KARAWANG', NULL, NULL, NULL, NULL, NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28'),
	(22, 'Motor', 'YAMAHA', '2SX', 'MH3SE9010FJI53266', 'B 3305 DM', 'M-03048092', 'BOGOR', '2025-11-26', NULL, NULL, NULL, NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28'),
	(23, 'Motor', 'HONDA', 'NF100 SLD', 'MH1H842176K109136', 'B 6036 KJL', 'H-08324411', 'BOGOR', '2025-11-26', NULL, NULL, NULL, NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28'),
	(24, 'Motor', 'HONDA', 'A1F02N36M1 A/T', 'MH1JM4117MK800628', 'B 4507 KVX', 'R-02610619', 'BOGOR', '2026-01-03', NULL, NULL, NULL, NULL, '2025-04-15 21:13:28', '2025-04-15 21:13:28');

-- membuang struktur untuk table umk.exports
CREATE TABLE IF NOT EXISTS `exports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exporter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `processed_rows` int unsigned NOT NULL DEFAULT '0',
  `total_rows` int unsigned NOT NULL,
  `successful_rows` int unsigned NOT NULL DEFAULT '0',
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exports_user_id_foreign` (`user_id`),
  CONSTRAINT `exports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.exports: ~0 rows (lebih kurang)

-- membuang struktur untuk table umk.failed_import_rows
CREATE TABLE IF NOT EXISTS `failed_import_rows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `data` json NOT NULL,
  `import_id` bigint unsigned NOT NULL,
  `validation_error` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `failed_import_rows_import_id_foreign` (`import_id`),
  CONSTRAINT `failed_import_rows_import_id_foreign` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.failed_import_rows: ~0 rows (lebih kurang)

-- membuang struktur untuk table umk.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.failed_jobs: ~0 rows (lebih kurang)

-- membuang struktur untuk table umk.imports
CREATE TABLE IF NOT EXISTS `imports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `importer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `processed_rows` int unsigned NOT NULL DEFAULT '0',
  `total_rows` int unsigned NOT NULL,
  `successful_rows` int unsigned NOT NULL DEFAULT '0',
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imports_user_id_foreign` (`user_id`),
  CONSTRAINT `imports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.imports: ~0 rows (lebih kurang)

-- membuang struktur untuk table umk.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.jobs: ~0 rows (lebih kurang)

-- membuang struktur untuk table umk.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.job_batches: ~0 rows (lebih kurang)

-- membuang struktur untuk table umk.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.migrations: ~1 rows (lebih kurang)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_03_06_023253_create_notifications_table', 1),
	(5, '2025_03_06_030503_create_activity_log_table', 1),
	(6, '2025_03_06_030504_add_event_column_to_activity_log_table', 1),
	(7, '2025_03_06_030505_add_batch_uuid_column_to_activity_log_table', 1),
	(8, '2025_03_06_035319_create_pengajuanumk_table', 1),
	(9, '2025_03_06_062802_create_pengajuan_details_table', 1),
	(10, '2025_03_06_063625_create_akun_masters_table', 1),
	(11, '2025_03_09_155820_create_data_kendaraans_table', 1),
	(12, '2025_03_09_161440_create_imports_table', 1),
	(13, '2025_03_09_161441_create_exports_table', 1),
	(14, '2025_03_09_161442_create_failed_import_rows_table', 1),
	(15, '2025_03_10_111848_create_transaksi_u_m_k_s_table', 1);

-- membuang struktur untuk table umk.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.notifications: ~0 rows (lebih kurang)

-- membuang struktur untuk table umk.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.password_reset_tokens: ~0 rows (lebih kurang)

-- membuang struktur untuk table umk.pengajuanumk
CREATE TABLE IF NOT EXISTS `pengajuanumk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomor_pengajuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `total_pengajuan` decimal(15,2) NOT NULL DEFAULT '10000000.00',
  `status` enum('acc','waiting','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengajuanumk_nomor_pengajuan_unique` (`nomor_pengajuan`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.pengajuanumk: ~21 rows (lebih kurang)
INSERT INTO `pengajuanumk` (`id`, `nomor_pengajuan`, `tanggal_pengajuan`, `total_pengajuan`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'SP2UMKU-00001/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', '2025-05-14 03:56:58', '2025-05-14 03:56:58'),
	(2, 'SP2UMKU-00002/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(3, 'SP2UMKU-00003/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(4, 'SP2UMKU-00004/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(5, 'SP2UMKU-00005/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(6, 'SP2UMKU-00006/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(7, 'SP2UMKU-00007/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(8, 'SP2UMKU-00008/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(9, 'SP2UMKU-00009/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(10, 'SP2UMKU-00010/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(11, 'SP2UMKU-00011/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(12, 'SP2UMKU-00012/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(13, 'SP2UMKU-00013/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(14, 'SP2UMKU-00014/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(15, 'SP2UMKU-00015/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(16, 'SP2UMKU-00016/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(17, 'SP2UMKU-00017/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(18, 'SP2UMKU-00018/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(19, 'SP2UMKU-00019/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(20, 'SP2UMKU-00020/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL),
	(21, 'SP2UMKU-00021/K1.01/0525', '2025-05-14', 10000000.00, 'waiting', NULL, NULL);

-- membuang struktur untuk table umk.pengajuan_details
CREATE TABLE IF NOT EXISTS `pengajuan_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomor_pengajuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_akun` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_akun` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nomor_pengajuan` (`nomor_pengajuan`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.pengajuan_details: ~17 rows (lebih kurang)
INSERT INTO `pengajuan_details` (`id`, `nomor_pengajuan`, `kode_akun`, `nama_akun`, `jumlah`, `keterangan`, `created_at`, `updated_at`) VALUES
	(1, '21', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', 3500000.00, NULL, '2025-05-14 03:56:58', '2025-05-14 03:56:58'),
	(2, '21', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', 3000000.00, NULL, '2025-05-14 03:56:58', '2025-05-14 03:56:58'),
	(3, '21', '2.250.06', 'Barang dan Jasa - Alat Tulis Kantor (ATK)', 500000.00, NULL, '2025-05-14 03:56:58', '2025-05-14 03:56:58'),
	(4, '21', '2.250.05', 'Barang dan Jasa - Materai dan Perangko', 500000.00, NULL, '2025-05-14 03:56:58', '2025-05-14 03:56:58'),
	(5, '21', '2.250.24', 'Barang dan Jasa - Peralatan dan Perlengkapan Kantor', 500000.00, NULL, '2025-05-14 03:56:58', '2025-05-14 03:56:58'),
	(6, '21', '2.304.03', 'Beban Non Ops. Lain - Sumbangan', 1500000.00, NULL, '2025-05-14 03:56:58', '2025-05-14 03:56:58'),
	(7, '21', '2.230.08', 'Pemeliharaan - Kendaraan Dinas', 200000.00, NULL, '2025-05-14 03:56:58', '2025-05-14 03:56:58'),
	(8, '21', '2.250.19', 'Barang dan Jasa - Foto Copy', 300000.00, NULL, '2025-05-14 03:56:58', '2025-05-14 03:56:58'),
	(9, '18', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', 3000000.00, NULL, '2025-05-14 04:50:57', '2025-05-14 04:50:57'),
	(10, '18', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', 2000000.00, NULL, '2025-05-14 04:50:57', '2025-05-14 04:50:57'),
	(11, '18', '2.250.06', 'Barang dan Jasa - Alat Tulis Kantor (ATK)', 500000.00, NULL, '2025-05-14 04:50:57', '2025-05-14 04:50:57'),
	(12, '18', '2.250.05', 'Barang dan Jasa - Materai dan Perangko', 1000000.00, NULL, '2025-05-14 04:50:57', '2025-05-14 04:50:57'),
	(13, '18', '2.250.24', 'Barang dan Jasa - Peralatan dan Perlengkapan Kantor', 600000.00, NULL, '2025-05-14 04:50:57', '2025-05-14 04:50:57'),
	(14, '18', '2.230.08', 'Pemeliharaan - Kendaraan Dinas', 600000.00, NULL, '2025-05-14 04:50:57', '2025-05-14 04:50:57'),
	(15, '18', '2.250.19', 'Barang dan Jasa - Foto Copy', 300000.00, NULL, '2025-05-14 04:50:57', '2025-05-14 04:50:57'),
	(16, '18', '2.304.03', 'Beban Non Ops. Lain - Sumbangan', 1500000.00, NULL, '2025-05-14 04:50:57', '2025-05-14 04:50:57'),
	(17, '18', '2.230.11', 'Pemeliharaan - Inventaris', 500000.00, NULL, '2025-05-14 04:50:57', '2025-05-14 04:50:57');

-- membuang struktur untuk table umk.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.sessions: ~1 rows (lebih kurang)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('n3Rb57aGXNKmnsatBXYr7nlQ7nbx6n3EnSMt0zzp', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibm82OUw4MGt1VHRleUs2b2tTcFZQU0l0ckVnQzhEWEhIQ0hrSHl6WSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1747193955),
	('TiWZouCtIJKwhyFLELkMRoYIhfWzwf3yN0kPxV2x', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiWnZYMjk4MnhVY1FyWHh0eEtaclNxN2ZmcGNnMm9FZmRCU2JLVlpDSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi90cmFuc2Frc2ktdS1tLWtzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJDd1Nm9PalRweDllc2pRQ2xxOUtnR2U3TXFoL2xaTWxnVWozSTN5LzlxaEhxTUtwMWhvRVllIjtzOjg6ImZpbGFtZW50IjthOjA6e319', 1747208670);

-- membuang struktur untuk table umk.transaksiumk
CREATE TABLE IF NOT EXISTS `transaksiumk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `no_pengajuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `akun_bpr` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_akun` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satuan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nominal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.transaksiumk: ~47 rows (lebih kurang)
INSERT INTO `transaksiumk` (`id`, `no_pengajuan`, `akun_bpr`, `nama_akun`, `tanggal`, `keterangan`, `qty`, `satuan`, `nominal`, `created_at`, `updated_at`) VALUES
	(10, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-01', 'Parkir Mobil B 2674 KIY (GMB)', NULL, NULL, '41000.00', '2025-05-05 02:07:24', '2025-05-05 02:07:24'),
	(11, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Reimburse Transport (Grab, toll) Perdin SKAI', NULL, NULL, '391000.00', '2025-05-05 02:08:03', '2025-05-05 02:08:03'),
	(12, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Reimburse Grab Tugas Luar Sekdir', NULL, NULL, '60000', '2025-05-05 02:08:41', '2025-05-14 07:26:54'),
	(13, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Bensin Mobil B 2669 KIV (BOD : Pak Iwan)', NULL, NULL, '350000.00', '2025-05-05 02:09:35', '2025-05-05 02:09:35'),
	(14, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Emoney Mobil B 2669 KIV (BOD : Pak Iwan)', NULL, NULL, '250000.00', '2025-05-05 02:10:07', '2025-05-05 02:10:07'),
	(15, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Emoney Mobil B 2653 KIY (BOD : Bu Indri)', NULL, NULL, '300000.00', '2025-05-05 02:10:55', '2025-05-05 02:10:55'),
	(16, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Bensin Mobil B 2653 KIY (BOD : Bu Indri)\n', NULL, NULL, '300000.00', '2025-05-05 02:11:40', '2025-05-05 02:11:40'),
	(17, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Parkir Mobil B 2653 KIY (BOD : Bu Indri)', NULL, NULL, '13000.00', '2025-05-05 02:16:44', '2025-05-05 02:16:44'),
	(18, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Charging BYD (Tugas Luar Pak Ato)', NULL, NULL, '95835.00', '2025-05-05 02:17:47', '2025-05-05 02:17:47'),
	(19, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Bensin Mobil B 2584 KZR (BOD : Pak Suryo)', NULL, NULL, '230000.00', '2025-05-05 02:18:17', '2025-05-05 02:18:17'),
	(20, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Emoney Mobil B 2584 KZR (BOD : Pak Suryo)', NULL, NULL, '131600.00', '2025-05-05 02:19:05', '2025-05-05 02:19:05'),
	(21, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Emoney Tugas luar Pak Agung (Operasional)', NULL, NULL, '205000.00', '2025-05-05 02:19:40', '2025-05-05 02:19:40'),
	(22, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Makan siang Survei GMO, BM, GA : Hendrik', NULL, NULL, '381700.00', '2025-05-05 02:21:32', '2025-05-05 02:21:32'),
	(23, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Snack Ruang Rapat', NULL, NULL, '102300.00', '2025-05-05 02:28:36', '2025-05-05 02:28:36'),
	(24, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Makan Siang Metting Bersama Jalin', NULL, NULL, '407550.00', '2025-05-05 02:29:05', '2025-05-05 02:29:05'),
	(25, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Le Mini 2 Box', NULL, NULL, '80000.00', '2025-05-05 02:29:36', '2025-05-05 02:29:36'),
	(26, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Le Galon 15 & Le Mini 4', NULL, NULL, '435000.00', '2025-05-05 02:35:32', '2025-05-05 02:35:32'),
	(27, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Snack Rapat BPR Baturaja', NULL, NULL, '54000.00', '2025-05-05 02:36:01', '2025-05-05 02:36:01'),
	(28, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Reimburse Kopi PE Legal, RBC, SKAI, dan MANRISK', NULL, NULL, '65000.00', '2025-05-05 02:38:08', '2025-05-05 02:38:08'),
	(29, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Reimburse makan tugas luar Driver: pak mitra & Pak Ato', NULL, NULL, '73000.00', '2025-05-05 02:39:38', '2025-05-05 02:39:38'),
	(30, 'SP2UMKU-00018/K1.01/0525', '2.250.10', 'Barang dan Jasa - Perjalanan Dinas', '2025-05-05', 'Makan siang Tamu Bu Dwi', NULL, NULL, '24200.00', '2025-05-05 02:40:08', '2025-05-05 02:40:08'),
	(31, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Permen untuk ruang rapat', NULL, NULL, '43700.00', '2025-05-05 02:40:39', '2025-05-05 02:40:39'),
	(32, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Lontong + Gorengan untuk Pak Iwan dan Ragap', NULL, NULL, '28000.00', '2025-05-05 02:41:58', '2025-05-05 02:41:58'),
	(33, 'SP2UMKU-00018/K1.01/0525', '2.160.7', 'Pinjaman yang Diterima', '2025-05-05', 'Makan malam tugas luar Pak Ato (Antar Pak Suyo ke Bogor)', NULL, NULL, '85800.00', '2025-05-05 02:42:30', '2025-05-05 02:42:30'),
	(34, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Snack Ruang Rapat', NULL, NULL, '84000.00', '2025-05-05 02:42:59', '2025-05-05 02:42:59'),
	(35, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Buah untuk Ragap', NULL, NULL, '357006.00', '2025-05-05 02:43:32', '2025-05-05 02:43:32'),
	(36, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Gorengan Ragap', NULL, NULL, '50000.00', '2025-05-05 02:43:56', '2025-05-05 02:43:56'),
	(37, 'SP2UMKU-00018/K1.01/0525', '2.250.01', 'Barang dan Jasa - Jamuan Tamu dan Dinas', '2025-05-05', 'Kerupuk untuk Ragap', NULL, NULL, '38000.00', '2025-05-05 02:44:31', '2025-05-05 02:44:31'),
	(38, 'SP2UMKU-00018/K1.01/0525', '2.250.24', 'Barang dan Jasa - Peralatan dan Perlengkapan Kantor', '2025-05-05', 'Reimburse Artificial Leaves, dll keperluan Shooting Bisnis', NULL, NULL, '164000.00', '2025-05-05 02:45:07', '2025-05-05 02:45:07'),
	(39, 'SP2UMKU-00018/K1.01/0525', '2.250.24', 'Barang dan Jasa - Peralatan dan Perlengkapan Kantor', '2025-05-05', 'Plastik Sampah', NULL, NULL, '10000.00', '2025-05-05 02:45:40', '2025-05-05 02:45:40'),
	(40, 'SP2UMKU-00018/K1.01/0525', '2.250.24', 'Barang dan Jasa - Peralatan dan Perlengkapan Kantor', '2025-05-05', 'Batrerai untuk Jam dinding Operasional', NULL, NULL, '21900.00', '2025-05-05 02:46:38', '2025-05-05 02:46:38'),
	(41, 'SP2UMKU-00018/K1.01/0525', '2.250.24', 'Barang dan Jasa - Peralatan dan Perlengkapan Kantor', '2025-05-05', 'Sapu Lantai Dragon untuk Gedung Operasional', NULL, NULL, '25000.00', '2025-05-05 02:47:20', '2025-05-05 02:47:20'),
	(42, 'SP2UMKU-00018/K1.01/0525', '2.250.24', 'Barang dan Jasa - Peralatan dan Perlengkapan Kantor', '2025-05-05', 'Kertas Kado BU Harini', NULL, NULL, '15000.00', '2025-05-05 02:47:56', '2025-05-05 02:47:56'),
	(43, 'SP2UMKU-00018/K1.01/0525', '2.250.24', 'Barang dan Jasa - Peralatan dan Perlengkapan Kantor', '2025-05-05', 'Teplak Meja dan Kanebo', NULL, NULL, '85000.00', '2025-05-05 02:48:24', '2025-05-05 02:48:24'),
	(44, 'SP2UMKU-00018/K1.01/0525', '2.250.24', 'Barang dan Jasa - Peralatan dan Perlengkapan Kantor', '2025-05-05', 'Kapur Barus untuk Gedung Operasional', NULL, NULL, '12900.00', '2025-05-05 02:48:44', '2025-05-05 02:48:44'),
	(45, 'SP2UMKU-00018/K1.01/0525', '2.250.05', 'Barang dan Jasa - Materai dan Perangko', '2025-05-05', 'Kirim Dokumen Accounting 6 Dokumen', NULL, NULL, '131000.00', '2025-05-05 02:49:09', '2025-05-05 02:49:09'),
	(46, 'SP2UMKU-00018/K1.01/0525', '2.250.05', 'Barang dan Jasa - Materai dan Perangko', '2025-05-05', 'Kirim Dokumen Accounting 8 Dokumen', NULL, NULL, '198000.00', '2025-05-05 02:49:34', '2025-05-05 02:49:34'),
	(47, 'SP2UMKU-00018/K1.01/0525', '2.250.05', 'Barang dan Jasa - Materai dan Perangko', '2025-05-05', 'Kirim Dokumen Bisnis 1', NULL, NULL, '10000.00', '2025-05-05 02:49:54', '2025-05-05 02:49:54'),
	(48, 'SP2UMKU-00018/K1.01/0525', '2.250.05', 'Barang dan Jasa - Materai dan Perangko', '2025-05-05', 'Kirim Dokumen Manrisk 1', NULL, NULL, '18000.00', '2025-05-05 02:50:23', '2025-05-05 02:50:23'),
	(49, 'SP2UMKU-00018/K1.01/0525', '2.250.05', 'Barang dan Jasa - Materai dan Perangko', '2025-05-05', 'Kirim Dokumen 3\n', NULL, NULL, '40000.00', '2025-05-05 02:50:45', '2025-05-05 02:50:45'),
	(50, 'SP2UMKU-00018/K1.01/0525', '2.250.05', 'Barang dan Jasa - Materai dan Perangko', '2025-05-05', 'Materai 2 untuk Pak Maskum', NULL, NULL, '23000.00', '2025-05-05 02:51:06', '2025-05-05 02:51:06'),
	(51, 'SP2UMKU-00018/K1.01/0525', '2.304.03', 'Beban Non Ops. Lain - Sumbangan', '2025-05-05', 'Bunga Meja ukuran Medium Ultah Pak Rony', NULL, NULL, '500000.00', '2025-05-05 02:51:28', '2025-05-05 02:51:28'),
	(52, 'SP2UMKU-00018/K1.01/0525', '2.304.03', 'Beban Non Ops. Lain - Sumbangan', '2025-05-05', 'Bunga Meja ukuran Medium Ultah Bu Hanna', NULL, NULL, '500000.00', '2025-05-05 02:52:00', '2025-05-05 02:52:00'),
	(53, 'SP2UMKU-00018/K1.01/0525', '2.304.03', 'Beban Non Ops. Lain - Sumbangan', '2025-05-05', 'Reimburse SekDir untuk Souvenir Bu Harini', NULL, NULL, '2504000.00', '2025-05-05 02:52:23', '2025-05-05 02:52:23'),
	(54, 'SP2UMKU-00018/K1.01/0525', '2.250.19', 'Barang dan Jasa - Foto Copy', '2025-05-05', 'Jilid Dokumen MANRISK', NULL, NULL, '5000.00', '2025-05-05 02:52:50', '2025-05-05 02:52:50'),
	(55, 'SP2UMKU-00018/K1.01/0525', '2.250.26', 'Barang dan Jasa - Keamanan dan Kebersihan', '2025-05-05', 'Pembersihan Lumpur Parking Area', NULL, NULL, '150000.00', '2025-05-05 02:53:16', '2025-05-05 02:53:16'),
	(56, 'SP2UMKU-00018/K1.01/0525', '2.250.04', 'Barang dan Jasa - Telepon / Telegram / Telex', '2025-05-05', 'Zoom Premiun untuk SDM', NULL, NULL, '172324.00', '2025-05-05 02:53:50', '2025-05-05 02:53:50');

-- membuang struktur untuk table umk.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `roles` enum('user','admin','general_manager','manager_keuangan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel umk.users: ~1 rows (lebih kurang)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `roles`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Ade Erlangga', 'erlangga@bankdptaspen.co.id', NULL, 'user', '$2y$12$7u6oOjTpx9esjQClq9KgGe7Mqh/lZMlgUj3I3y/9qhHqMKp1hoEYe', NULL, '2025-05-14 03:35:11', '2025-05-14 03:35:11');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
