-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 04, 2026 at 03:25 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pondasikita_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `admin_id` int DEFAULT NULL,
  `toko_id` int DEFAULT NULL,
  `status` enum('open','closed','pending_admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'open',
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int UNSIGNED NOT NULL,
  `province_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `province_id`, `name`) VALUES
(1, 1, 'KOTA JAKARTA PUSAT'),
(2, 1, 'KOTA JAKARTA UTARA'),
(3, 1, 'KOTA JAKARTA BARAT'),
(4, 1, 'KOTA JAKARTA SELATAN'),
(5, 1, 'KOTA JAKARTA TIMUR'),
(6, 2, 'KOTA BOGOR'),
(7, 2, 'KOTA DEPOK'),
(8, 2, 'KOTA BEKASI'),
(9, 2, 'KABUPATEN BOGOR'),
(10, 2, 'KABUPATEN BEKASI'),
(11, 2, 'KABUPATEN KARAWANG'),
(12, 3, 'KOTA TANGERANG'),
(13, 3, 'KOTA TANGERANG SELATAN'),
(14, 3, 'KABUPATEN TANGERANG'),
(15, 1, 'KAB. ADM. KEPULAUAN SERIBU'),
(21, 2, 'KABUPATEN SUBANG');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int UNSIGNED NOT NULL,
  `city_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `city_id`, `name`) VALUES
(1, 1, 'Gambir'),
(2, 1, 'Sawah Besar'),
(3, 1, 'Kemayoran'),
(4, 1, 'Senen'),
(5, 1, 'Cempaka Putih'),
(6, 1, 'Menteng'),
(7, 1, 'Tanah Abang'),
(8, 1, 'Johar Baru'),
(9, 2, 'Penjaringan'),
(10, 2, 'Tanjung Priok'),
(11, 2, 'Koja'),
(12, 2, 'Kelapa Gading'),
(13, 2, 'Pademangan'),
(14, 2, 'Cilincing'),
(15, 3, 'Cengkareng'),
(16, 3, 'Grogol Petamburan'),
(17, 3, 'Taman Sari'),
(18, 3, 'Tambora'),
(19, 3, 'Kebon Jeruk'),
(20, 3, 'Kalideres'),
(21, 3, 'Palmerah'),
(22, 3, 'Kembangan'),
(23, 4, 'Tebet'),
(24, 4, 'Setiabudi'),
(25, 4, 'Mampang Prapatan'),
(26, 4, 'Pasar Minggu'),
(27, 4, 'Kebayoran Lama'),
(28, 4, 'Cilandak'),
(29, 4, 'Kebayoran Baru'),
(30, 4, 'Pancoran'),
(31, 4, 'Jagakarsa'),
(32, 4, 'Pesanggrahan'),
(33, 5, 'Matraman'),
(34, 5, 'Pulo Gadung'),
(35, 5, 'Jatinegara'),
(36, 5, 'Duren Sawit'),
(37, 5, 'Kramat Jati'),
(38, 5, 'Makasar'),
(39, 5, 'Pasar Rebo'),
(40, 5, 'Ciracas'),
(41, 5, 'Cipayung'),
(42, 5, 'Cakung'),
(43, 15, 'Kepulauan Seribu Utara'),
(44, 15, 'Kepulauan Seribu Selatan'),
(45, 6, 'Bogor Selatan'),
(46, 6, 'Bogor Timur'),
(47, 6, 'Bogor Utara'),
(48, 6, 'Bogor Tengah'),
(49, 6, 'Bogor Barat'),
(50, 6, 'Tanah Sareal'),
(51, 7, 'Pancoran Mas'),
(52, 7, 'Cimanggis'),
(53, 7, 'Sawangan'),
(54, 7, 'Limo'),
(55, 7, 'Sukmajaya'),
(56, 7, 'Beji'),
(57, 7, 'Cipayung'),
(58, 7, 'Cilodong'),
(59, 7, 'Cinere'),
(60, 7, 'Tapos'),
(61, 7, 'Bojongsari'),
(62, 8, 'Bekasi Timur'),
(63, 8, 'Bekasi Barat'),
(64, 8, 'Bekasi Utara'),
(65, 8, 'Bekasi Selatan'),
(66, 8, 'Rawalumbu'),
(67, 8, 'Medan Satria'),
(68, 8, 'Bantar Gebang'),
(69, 8, 'Pondok Gede'),
(70, 8, 'Jatiasih'),
(71, 8, 'Jatisampurna'),
(72, 8, 'Mustika Jaya'),
(73, 8, 'Pondok Melati'),
(74, 9, 'Cibinong'),
(75, 9, 'Gunung Putri'),
(76, 9, 'Citeureup'),
(77, 9, 'Sukaraja'),
(78, 9, 'Babakan Madang'),
(79, 9, 'Jonggol'),
(80, 9, 'Cileungsi'),
(81, 9, 'Cariu'),
(82, 9, 'Sukamakmur'),
(83, 9, 'Parung'),
(84, 9, 'Gunung Sindur'),
(85, 9, 'Kemang'),
(86, 9, 'Bojonggede'),
(87, 9, 'Leuwiliang'),
(88, 9, 'Ciampea'),
(89, 9, 'Cibungbulang'),
(90, 9, 'Pamijahan'),
(91, 9, 'Rumpin'),
(92, 9, 'Jasinga'),
(93, 9, 'Parung Panjang'),
(94, 9, 'Nanggung'),
(95, 9, 'Cigudeg'),
(96, 9, 'Tenjo'),
(97, 9, 'Ciawi'),
(98, 9, 'Cisarua'),
(99, 9, 'Megamendung'),
(100, 9, 'Caringin'),
(101, 9, 'Cijeruk'),
(102, 9, 'Ciomas'),
(103, 9, 'Dramaga'),
(104, 9, 'Tamansari'),
(105, 9, 'Klapanunggal'),
(106, 9, 'Ciseeng'),
(107, 9, 'Rancabungur'),
(108, 9, 'Tajurhalang'),
(109, 9, 'Cigombong'),
(110, 9, 'Leuwisadeng'),
(111, 9, 'Tenjolaya'),
(112, 9, 'Tanjungsari'),
(113, 9, 'Sukajaya'),
(114, 10, 'Cikarang Pusat'),
(115, 10, 'Cikarang Selatan'),
(116, 10, 'Cikarang Utara'),
(117, 10, 'Cikarang Barat'),
(118, 10, 'Cibitung'),
(119, 10, 'Setu'),
(120, 10, 'Tambun Selatan'),
(121, 10, 'Tambun Utara'),
(122, 10, 'Cibarusah'),
(123, 10, 'Serang Baru'),
(124, 10, 'Karangbahagia'),
(125, 10, 'Pebayuran'),
(126, 10, 'Sukakarya'),
(127, 10, 'Sukatani'),
(128, 10, 'Sukawangi'),
(129, 10, 'Tambelang'),
(130, 10, 'Babelan'),
(131, 10, 'Tarumajaya'),
(132, 10, 'Muara Gembong'),
(133, 10, 'Cabangbungin'),
(134, 10, 'Kedungwaringin'),
(135, 10, 'Bojongmangu'),
(136, 11, 'Karawang Barat'),
(137, 11, 'Karawang Timur'),
(138, 11, 'Klari'),
(139, 11, 'Rengasdengklok'),
(140, 11, 'Kutawaluya'),
(141, 11, 'Batujaya'),
(142, 11, 'Telukjambe Timur'),
(143, 11, 'Telukjambe Barat'),
(144, 11, 'Cikampek'),
(145, 11, 'Jatisari'),
(146, 11, 'Cilamaya Wetan'),
(147, 11, 'Cilamaya Kulon'),
(148, 11, 'Lemahabang'),
(149, 11, 'Rawamerta'),
(150, 11, 'Tempuran'),
(151, 11, 'Tirtajaya'),
(152, 11, 'Pedes'),
(153, 11, 'Cibuaya'),
(154, 11, 'Pakisjaya'),
(155, 11, 'Tirtamulya'),
(156, 11, 'Cilebar'),
(157, 11, 'Jayakerta'),
(158, 11, 'Majalaya'),
(159, 11, 'Banyusari'),
(160, 11, 'Kotabaru'),
(161, 11, 'Ciampel'),
(162, 11, 'Pangkalan'),
(163, 11, 'Tegalwaru'),
(164, 11, 'Purwasari'),
(165, 11, 'Telagasari'),
(166, 12, 'Tangerang'),
(167, 12, 'Batuceper'),
(168, 12, 'Benda'),
(169, 12, 'Cibodas'),
(170, 12, 'Ciledug'),
(171, 12, 'Cipondoh'),
(172, 12, 'Jatiuwung'),
(173, 12, 'Karangtengah'),
(174, 12, 'Karawaci'),
(175, 12, 'Larangan'),
(176, 12, 'Neglasari'),
(177, 12, 'Periuk'),
(178, 12, 'Pinang'),
(179, 13, 'Serpong'),
(180, 13, 'Serpong Utara'),
(181, 13, 'Pondok Aren'),
(182, 13, 'Ciputat'),
(183, 13, 'Ciputat Timur'),
(184, 13, 'Pamulang'),
(185, 13, 'Setu'),
(186, 14, 'Balaraja'),
(187, 14, 'Cikupa'),
(188, 14, 'Cisauk'),
(189, 14, 'Cisoka'),
(190, 14, 'Curug'),
(191, 14, 'Gunung Kaler'),
(192, 14, 'Jambe'),
(193, 14, 'Jayanti'),
(194, 14, 'Kelapa Dua'),
(195, 14, 'Kemiri'),
(196, 14, 'Kresek'),
(197, 14, 'Kronjo'),
(198, 14, 'Legok'),
(199, 14, 'Mauk'),
(200, 14, 'Mekar Baru'),
(201, 14, 'Pagedangan'),
(202, 14, 'Pakuhaji'),
(203, 14, 'Panongan'),
(204, 14, 'Pasar Kemis'),
(205, 14, 'Rajeg'),
(206, 14, 'Sepatan'),
(207, 14, 'Sepatan Timur'),
(208, 14, 'Sindang Jaya'),
(209, 14, 'Solear'),
(210, 14, 'Sukadiri'),
(211, 14, 'Sukamulya'),
(212, 14, 'Teluknaga'),
(213, 14, 'Tigaraksa'),
(214, 14, 'Kosambi'),
(215, 21, 'Pagaden'),
(216, 21, 'Cinangsi'),
(217, 21, 'Pagaden'),
(218, 21, 'Soklat');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `chat_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `message_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `name`) VALUES
(1, 'DKI JAKARTA'),
(2, 'JAWA BARAT'),
(3, 'BANTEN');

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang`
--

CREATE TABLE `tb_barang` (
  `id` int NOT NULL,
  `toko_id` int NOT NULL,
  `kategori_id` int NOT NULL,
  `kode_barang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_barang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `merk_barang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `gambar_utama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL,
  `tipe_diskon` enum('NOMINAL','PERSEN') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nilai_diskon` decimal(15,2) DEFAULT NULL,
  `diskon_mulai` datetime DEFAULT NULL,
  `diskon_berakhir` datetime DEFAULT NULL,
  `satuan_unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pcs',
  `stok` int NOT NULL DEFAULT '0',
  `stok_di_pesan` int NOT NULL DEFAULT '0',
  `berat_kg` decimal(10,2) NOT NULL DEFAULT '1.00',
  `status_moderasi` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `alasan_penolakan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_barang`
--

INSERT INTO `tb_barang` (`id`, `toko_id`, `kategori_id`, `kode_barang`, `nama_barang`, `merk_barang`, `deskripsi`, `gambar_utama`, `harga`, `tipe_diskon`, `nilai_diskon`, `diskon_mulai`, `diskon_berakhir`, `satuan_unit`, `stok`, `stok_di_pesan`, `berat_kg`, `status_moderasi`, `alasan_penolakan`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'Genteng merah', 'gg', 'aku sih bagus ini cocok banget lah pokoknya bisa ini mah gaskennnn', '1772380837.png', 20000.00, 'PERSEN', 50.00, '2026-03-01 23:22:00', '2026-03-02 23:23:00', 'pcs', 99, 0, 0.60, 'approved', NULL, 1, '2026-03-01 09:00:37', '2026-03-01 09:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang_variasi`
--

CREATE TABLE `tb_barang_variasi` (
  `id` int NOT NULL,
  `barang_id` int NOT NULL,
  `nama_variasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kode_sku` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `harga_tambahan` decimal(15,2) DEFAULT '0.00',
  `stok` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_biaya_pengiriman`
--

CREATE TABLE `tb_biaya_pengiriman` (
  `id` int NOT NULL,
  `zona_id` int NOT NULL,
  `tipe_biaya` enum('per_km','flat') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'flat',
  `biaya` decimal(15,2) NOT NULL,
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_transaksi`
--

CREATE TABLE `tb_detail_transaksi` (
  `id` int NOT NULL,
  `transaksi_id` int NOT NULL,
  `toko_id` int NOT NULL,
  `barang_id` int DEFAULT NULL,
  `nama_barang_saat_transaksi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `harga_saat_transaksi` decimal(15,2) NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `metode_pengiriman` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'DIKIRIM',
  `kurir_terpilih` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `biaya_pengiriman_item` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status_pesanan_item` enum('diproses','siap_kirim','dikirim','sampai_tujuan','dibatalkan','pengajuan_pengembalian','pengembalian_disetujui','pengembalian_ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'diproses',
  `resi_pengiriman` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catatan_pembeli` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `catatan_penjual` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_flash_sale_events`
--

CREATE TABLE `tb_flash_sale_events` (
  `id` int NOT NULL,
  `nama_event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `banner_event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_berakhir` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_flash_sale_produk`
--

CREATE TABLE `tb_flash_sale_produk` (
  `id` int NOT NULL,
  `event_id` int NOT NULL,
  `toko_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `harga_flash_sale` decimal(15,2) NOT NULL,
  `stok_flash_sale` int NOT NULL,
  `status_moderasi` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_gambar_barang`
--

CREATE TABLE `tb_gambar_barang` (
  `id` int NOT NULL,
  `barang_id` int NOT NULL,
  `nama_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_utama` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id` int NOT NULL,
  `nama_kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `icon_class` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kategori`
--

INSERT INTO `tb_kategori` (`id`, `nama_kategori`, `deskripsi`, `icon_class`, `parent_id`) VALUES
(1, 'Bahan Bangunan Dasar', 'Material utama untuk konstruksi', 'fas fa-warehouse', NULL),
(2, 'Semen', 'Berbagai jenis semen untuk konstruksi', 'fas fa-layer-group', 1),
(3, 'Semen Portland', 'Semen jenis umum', 'fas fa-industry', 2),
(4, 'Semen Putih', 'Semen untuk finishing dan dekorasi', 'fas fa-fill-drip', 2),
(5, 'Pasir', 'Pasir untuk campuran bangunan', 'fas fa-wind', 1),
(6, 'Batu', 'Berbagai jenis batu untuk konstruksi', 'fas fa-cubes', 1),
(7, 'Besi & Baja', 'Material besi dan baja untuk struktur', 'fas fa-bars', NULL),
(8, 'Besi Beton', 'Besi untuk tulangan beton', 'fas fa-grip-lines-vertical', 7),
(9, 'Baja Ringan', 'Baja untuk rangka atap', 'fas fa-bars', 7),
(10, 'Cat & Pelapis', 'Produk untuk finishing dan perlindungan permukaan', 'fas fa-paint-roller', NULL),
(11, 'Cat Tembok', 'Cat untuk dinding interior dan eksterior', 'fas fa-paint-roller', 10),
(12, 'Cat Kayu & Besi', 'Cat untuk permukaan kayu dan besi', 'fas fa-paint-roller', 10),
(13, 'Keramik & Granit', 'Material penutup lantai dan dinding', 'fas fa-th-large', NULL),
(14, 'Keramik Lantai', 'Keramik untuk lantai', 'fas fa-th-large', 13),
(15, 'Keramik Dinding', 'Keramik untuk dinding', 'fas fa-th-large', 13),
(16, 'Granit', 'Material granit untuk lantai dan dinding', 'fas fa-th-large', 13),
(17, 'Pipa & Perlengkapan Air', 'Sistem perpipaan dan sanitasi', 'fas fa-faucet', NULL),
(18, 'Pipa PVC', 'Pipa plastik PVC', 'fas fa-faucet', 17),
(19, 'Pipa Besi', 'Pipa dari bahan besi', 'fas fa-faucet', 17),
(20, 'Perlengkapan Sanitasi', 'Kloset, wastafel, shower', 'fas fa-bath', 17);

-- --------------------------------------------------------

--
-- Table structure for table `tb_keranjang`
--

CREATE TABLE `tb_keranjang` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_komisi`
--

CREATE TABLE `tb_komisi` (
  `id` int NOT NULL,
  `detail_transaksi_id` int NOT NULL,
  `jumlah_penjualan` decimal(15,2) NOT NULL,
  `persentase_komisi` decimal(5,2) NOT NULL,
  `jumlah_komisi` decimal(15,2) NOT NULL,
  `status` enum('unpaid','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_komplain`
--

CREATE TABLE `tb_komplain` (
  `id` int NOT NULL,
  `transaksi_id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'Pembeli yang komplain',
  `toko_id` int NOT NULL COMMENT 'Toko yang dikomplain',
  `jenis_komplain` enum('barang_rusak','barang_kurang','tidak_sesuai','lainnya') COLLATE utf8mb4_general_ci NOT NULL,
  `alasan_komplain` text COLLATE utf8mb4_general_ci NOT NULL,
  `bukti_foto_1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bukti_foto_2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bukti_video` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_komplain` enum('investigasi','menunggu_tanggapan_toko','refund_pembeli','teruskan_dana_toko','selesai') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'investigasi',
  `keputusan_admin` text COLLATE utf8mb4_general_ci COMMENT 'Catatan admin saat mengambil keputusan',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kurir_toko`
--

CREATE TABLE `tb_kurir_toko` (
  `id` int NOT NULL,
  `toko_id` int NOT NULL,
  `nama_kurir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `estimasi_waktu` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `biaya` decimal(15,2) NOT NULL,
  `tipe_kurir` enum('TOKO','PIHAK_KETIGA') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'PIHAK_KETIGA',
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_payouts`
--

CREATE TABLE `tb_payouts` (
  `id` int NOT NULL,
  `toko_id` int NOT NULL,
  `jumlah_payout` decimal(15,2) NOT NULL,
  `status` enum('pending','completed','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `tanggal_request` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_proses` datetime DEFAULT NULL,
  `catatan_admin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengaturan`
--

CREATE TABLE `tb_pengaturan` (
  `setting_nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `setting_nilai` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pengaturan`
--

INSERT INTO `tb_pengaturan` (`setting_nama`, `setting_nilai`) VALUES
('aktifkan_fitur_live_chat', '1'),
('alamat_pusat', 'Jl. Jenderal Sudirman Kav. 52-53, Jakarta Selatan, DKI Jakarta 12190'),
('bank_rekening_platform', 'BCA'),
('deskripsi_website', 'Platform Jual Beli Bahan Bangunan Terlengkap se-Indonesia'),
('durasi_preorder_maks_hari', '30'),
('email_kontak', 'kontak@pondasikita.com'),
('google_maps_api_key', ''),
('link_facebook', 'https://facebook.com/pondasikita'),
('link_instagram', 'https://instagram.com/pondasikita'),
('link_kebijakan_privasi', '/halaman/kebijakan-privasi'),
('link_syarat_ketentuan', '/halaman/syarat-ketentuan'),
('link_youtube', 'https://youtube.com/pondasikita'),
('maks_berat_pesanan_kg', '50'),
('maks_foto_produk', '8'),
('midtrans_client_key', ''),
('midtrans_server_key', ''),
('nama_rekening_platform', 'PT Pondasi Kita Indonesia'),
('nama_website', 'Pondasikita'),
('nomor_rekening_platform', '8881234567'),
('persentase_komisi', '5'),
('prefix_invoice', 'PNDSK'),
('rajaongkir_active_couriers', ''),
('rajaongkir_api_key', ''),
('rajaongkir_last_sync', NULL),
('telepon_kontak', '081234567890');

-- --------------------------------------------------------

--
-- Table structure for table `tb_review_produk`
--

CREATE TABLE `tb_review_produk` (
  `id` int NOT NULL,
  `detail_transaksi_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `ulasan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `gambar_ulasan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stok_histori`
--

CREATE TABLE `tb_stok_histori` (
  `id` bigint NOT NULL,
  `barang_id` int NOT NULL,
  `variasi_id` int DEFAULT NULL,
  `jumlah` int NOT NULL,
  `tipe_pergerakan` enum('initial','sale','sale_return','adjustment','stock_in') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `referensi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_toko`
--

CREATE TABLE `tb_toko` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `nama_toko` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi_toko` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `logo_toko` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `banner_toko` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_toko` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `province_id` int UNSIGNED DEFAULT NULL,
  `city_id` int UNSIGNED DEFAULT NULL,
  `district_id` int UNSIGNED DEFAULT NULL,
  `kode_pos` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telepon_toko` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `status` enum('pending','active','suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `status_operasional` enum('Buka','Tutup') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Buka',
  `rekening_bank` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nomor_rekening` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `atas_nama_rekening` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tier_toko` enum('regular','power_merchant','official_store') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'regular',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_toko`
--

INSERT INTO `tb_toko` (`id`, `user_id`, `nama_toko`, `slug`, `deskripsi_toko`, `logo_toko`, `banner_toko`, `alamat_toko`, `province_id`, `city_id`, `district_id`, `kode_pos`, `telepon_toko`, `latitude`, `longitude`, `status`, `status_operasional`, `rekening_bank`, `nomor_rekening`, `atas_nama_rekening`, `tier_toko`, `created_at`, `updated_at`) VALUES
(1, 1, 'QISAStoree666', 'qisastoree666', NULL, NULL, NULL, 'Pagaden\r\nbabakan', 2, 21, 215, NULL, '085156677227', NULL, NULL, 'active', 'Buka', NULL, NULL, NULL, 'regular', '2026-02-18 04:15:43', '2026-02-18 04:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `tb_toko_dekorasi`
--

CREATE TABLE `tb_toko_dekorasi` (
  `id` int NOT NULL,
  `toko_id` int NOT NULL,
  `tipe_komponen` enum('BANNER','PRODUK_UNGGULAN','TEKS_GAMBAR') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `konten_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_toko_jam_operasional`
--

CREATE TABLE `tb_toko_jam_operasional` (
  `id` int NOT NULL,
  `toko_id` int NOT NULL,
  `hari` tinyint(1) NOT NULL,
  `is_buka` tinyint(1) NOT NULL DEFAULT '0',
  `jam_buka` time DEFAULT NULL,
  `jam_tutup` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_toko_pengaturan`
--

CREATE TABLE `tb_toko_pengaturan` (
  `toko_id` int NOT NULL,
  `notif_email_pesanan` tinyint(1) NOT NULL DEFAULT '1',
  `notif_email_chat` tinyint(1) NOT NULL DEFAULT '1',
  `notif_email_produk` tinyint(1) NOT NULL DEFAULT '1',
  `notif_email_promo` tinyint(1) NOT NULL DEFAULT '1',
  `chat_terima_otomatis` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_toko_review`
--

CREATE TABLE `tb_toko_review` (
  `id` int NOT NULL,
  `toko_id` int NOT NULL,
  `user_id` int NOT NULL,
  `transaksi_id` int NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `ulasan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `balasan_penjual` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id` int NOT NULL,
  `kode_invoice` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sumber_transaksi` enum('ONLINE','OFFLINE') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'ONLINE',
  `user_id` int NOT NULL,
  `total_harga_produk` decimal(15,2) NOT NULL,
  `total_diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_final` decimal(15,2) NOT NULL,
  `tipe_pembayaran` enum('LUNAS','DP') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'LUNAS',
  `jumlah_dp` decimal(15,2) DEFAULT '0.00',
  `sisa_tagihan` decimal(15,2) DEFAULT '0.00',
  `metode_pembayaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_pembayaran` enum('pending','paid','dp_paid','failed','expired','cancelled') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `status_pesanan_global` enum('menunggu_pembayaran','diproses','dikirim','selesai','dibatalkan','komplain') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'menunggu_pembayaran',
  `payment_deadline` datetime DEFAULT NULL,
  `shipping_label_alamat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shipping_nama_penerima` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shipping_telepon_penerima` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shipping_alamat_lengkap` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `shipping_kecamatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shipping_kota_kabupaten` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shipping_provinsi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shipping_kode_pos` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `voucher_digunakan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `biaya_pengiriman` decimal(15,2) DEFAULT '0.00',
  `tipe_pengambilan` enum('pengiriman','ambil_di_toko') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pengiriman',
  `snap_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_service_fee` decimal(15,2) DEFAULT '0.00',
  `customer_handling_fee` decimal(15,2) DEFAULT '0.00',
  `midtrans_fee` decimal(15,2) DEFAULT '0.00',
  `tanggal_transaksi` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_telepon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `profile_picture_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('online','offline','typing') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'offline',
  `last_activity_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `level` enum('admin','seller','customer','bot') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `admin_role` enum('super','finance','cs') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `is_banned` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `reset_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `status_online` enum('online','offline') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `username`, `password`, `google_id`, `nama`, `email`, `no_telepon`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `profile_picture_url`, `status`, `last_activity_at`, `level`, `admin_role`, `is_verified`, `is_banned`, `created_at`, `updated_at`, `reset_token`, `reset_token_expires_at`, `status_online`) VALUES
(1, 'QISAAA', '$2y$12$9QcPenKKl9IhyAdwilkAau9grUjuT15Vstogi2RIgIXfLRZfDozWW', NULL, 'Prabu Alam Tian Try Suherman', 'prabualamtian@gmail.com', '085156677227', NULL, NULL, NULL, NULL, 'offline', '2026-02-18 11:15:43', 'seller', NULL, 1, 0, '2026-02-18 04:15:43', '2026-03-01 03:56:49', NULL, NULL, 'offline'),
(5, 'superadmin', '$2y$12$mw4TqQo9UShVw69GF4V2dOhLidAgWcKoT.GoW5VBjsUXDuCLdEwaW', NULL, 'Bos Super Admin', 'super@pondasikita.com', NULL, NULL, NULL, NULL, NULL, 'offline', '2026-03-01 10:29:48', 'admin', 'super', 1, 0, '2026-03-01 10:29:48', '2026-03-01 03:31:11', NULL, NULL, 'offline'),
(6, 'adminfinance', '$2y$12$0u/uKUVEjH6Fw2dXOpfD/ezGKtCWjX8CwYBsVCje6yb7/TQP7qm2y', NULL, 'Siska Finance', 'finance@pondasikita.com', NULL, NULL, NULL, NULL, NULL, 'offline', '2026-03-01 10:29:48', 'admin', 'finance', 1, 0, '2026-03-01 10:29:48', '2026-03-01 03:32:08', NULL, NULL, 'offline'),
(7, 'admincs', '$2y$12$qzHpP57wpkEdaoXL9nl8pujf6/UbNX0Maext4WG9VZxwlPXw7MevC', NULL, 'Doni CS', 'cs@pondasikita.com', NULL, NULL, NULL, NULL, NULL, 'offline', '2026-03-01 10:29:48', 'admin', 'cs', 1, 0, '2026-03-01 10:29:48', '2026-03-01 03:34:34', NULL, NULL, 'offline');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user_alamat`
--

CREATE TABLE `tb_user_alamat` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `label_alamat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_penerima` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telepon_penerima` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_lengkap` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `province_id` int UNSIGNED DEFAULT NULL,
  `city_id` int UNSIGNED DEFAULT NULL,
  `district_id` int UNSIGNED DEFAULT NULL,
  `kode_pos` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_utama` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_zona_pengiriman`
--

CREATE TABLE `tb_zona_pengiriman` (
  `id` int NOT NULL,
  `nama_zona` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_zona_pengiriman`
--

INSERT INTO `tb_zona_pengiriman` (`id`, `nama_zona`, `deskripsi`, `created_at`) VALUES
(1, 'Jabodetabek', 'Mencakup Jakarta, Bogor, Depok, Tangerang, Bekasi', '2025-07-09 06:59:57'),
(2, 'Bandung Raya', 'Mencakup Kota Bandung, Kabupaten Bandung, Cimahi', '2025-07-09 06:59:57'),
(3, 'Surabaya Metropolitan', 'Mencakup Surabaya, Sidoarjo, Gresik', '2025-07-09 06:59:57'),
(4, 'Jogja Raya', 'Mencakup Yogyakarta, Sleman, Bantul, Kulon Progo, Gunungkidul', '2025-07-11 03:00:20'),
(5, 'Solo Raya', 'Mencakup Kota Solo, Karanganyar, Sukoharjo, Boyolali, Klaten, Sragen', '2025-07-11 03:00:20'),
(6, 'Semarang Raya', 'Mencakup Kota Semarang, Kabupaten Semarang, Kendal, Demak, Ungaran', '2025-07-11 03:00:20'),
(7, 'Medan Metropolitan', 'Mencakup Medan, Binjai, Deli Serdang', '2025-07-11 03:00:20'),
(8, 'Makassar Raya', 'Mencakup Makassar, Gowa, Maros, Takalar', '2025-07-11 03:00:20'),
(9, 'Bali', 'Mencakup seluruh wilayah di Provinsi Bali', '2025-07-11 03:00:20'),
(10, 'Balikpapan-Samarinda', 'Mencakup Balikpapan, Samarinda, dan sekitarnya', '2025-07-11 03:00:20'),
(11, 'Batam dan Kepulauan Riau', 'Mencakup Batam, Tanjungpinang, dan kabupaten di Kepri', '2025-07-11 03:00:20'),
(12, 'Papua', 'Mencakup Jayapura, Timika, dan wilayah lainnya di Papua', '2025-07-11 03:00:20'),
(13, 'Maluku', 'Mencakup Ambon, Tual, dan daerah lain di Maluku', '2025-07-11 03:00:20'),
(14, 'Banjarmasin Raya', 'Mencakup Banjarmasin, Banjarbaru, dan Kabupaten Banjar', '2025-07-11 03:00:20'),
(15, 'Pontianak Raya', 'Mencakup Pontianak, Singkawang, dan sekitarnya', '2025-07-11 03:00:20'),
(16, 'Padang Raya', 'Mencakup Kota Padang, Bukittinggi, Payakumbuh, dan sekitarnya', '2025-07-11 03:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int NOT NULL,
  `toko_id` int DEFAULT NULL,
  `kode_voucher` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tipe_diskon` enum('RUPIAH','PERSEN') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_diskon` decimal(15,2) NOT NULL,
  `maks_diskon` decimal(15,2) DEFAULT NULL,
  `min_pembelian` decimal(15,2) DEFAULT '0.00',
  `kuota` int NOT NULL,
  `kuota_terpakai` int DEFAULT '0',
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_berakhir` datetime NOT NULL,
  `status` enum('AKTIF','TIDAK_AKTIF','HABIS') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'AKTIF'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `toko_id` (`toko_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_kode_barang_toko` (`toko_id`,`kode_barang`),
  ADD KEY `fk_barang_toko` (`toko_id`),
  ADD KEY `fk_barang_kategori` (`kategori_id`);

--
-- Indexes for table `tb_barang_variasi`
--
ALTER TABLE `tb_barang_variasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_id` (`barang_id`);

--
-- Indexes for table `tb_biaya_pengiriman`
--
ALTER TABLE `tb_biaya_pengiriman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zona_id` (`zona_id`);

--
-- Indexes for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`),
  ADD KEY `toko_id` (`toko_id`);

--
-- Indexes for table `tb_flash_sale_events`
--
ALTER TABLE `tb_flash_sale_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_flash_sale_produk`
--
ALTER TABLE `tb_flash_sale_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_fs_event` (`event_id`),
  ADD KEY `fk_fs_toko` (`toko_id`),
  ADD KEY `fk_fs_barang` (`barang_id`);

--
-- Indexes for table `tb_gambar_barang`
--
ALTER TABLE `tb_gambar_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_id` (`barang_id`);

--
-- Indexes for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kategori_parent` (`parent_id`);

--
-- Indexes for table `tb_keranjang`
--
ALTER TABLE `tb_keranjang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `barang_id` (`barang_id`);

--
-- Indexes for table `tb_komisi`
--
ALTER TABLE `tb_komisi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_transaksi_id` (`detail_transaksi_id`);

--
-- Indexes for table `tb_komplain`
--
ALTER TABLE `tb_komplain`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_komplain_transaksi` (`transaksi_id`),
  ADD KEY `fk_komplain_user` (`user_id`),
  ADD KEY `fk_komplain_toko` (`toko_id`);

--
-- Indexes for table `tb_kurir_toko`
--
ALTER TABLE `tb_kurir_toko`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kurir_toko` (`toko_id`);

--
-- Indexes for table `tb_payouts`
--
ALTER TABLE `tb_payouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `toko_id` (`toko_id`);

--
-- Indexes for table `tb_pengaturan`
--
ALTER TABLE `tb_pengaturan`
  ADD PRIMARY KEY (`setting_nama`);

--
-- Indexes for table `tb_review_produk`
--
ALTER TABLE `tb_review_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_transaksi_id` (`detail_transaksi_id`);

--
-- Indexes for table `tb_stok_histori`
--
ALTER TABLE `tb_stok_histori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_id` (`barang_id`);

--
-- Indexes for table `tb_toko`
--
ALTER TABLE `tb_toko`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_toko_user` (`user_id`),
  ADD KEY `fk_toko_province` (`province_id`),
  ADD KEY `fk_toko_city` (`city_id`),
  ADD KEY `fk_toko_district` (`district_id`);

--
-- Indexes for table `tb_toko_dekorasi`
--
ALTER TABLE `tb_toko_dekorasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dekorasi_toko` (`toko_id`);

--
-- Indexes for table `tb_toko_jam_operasional`
--
ALTER TABLE `tb_toko_jam_operasional`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `toko_hari_unik` (`toko_id`,`hari`);

--
-- Indexes for table `tb_toko_pengaturan`
--
ALTER TABLE `tb_toko_pengaturan`
  ADD PRIMARY KEY (`toko_id`);

--
-- Indexes for table `tb_toko_review`
--
ALTER TABLE `tb_toko_review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_review_toko_id` (`toko_id`),
  ADD KEY `fk_review_user_id` (`user_id`),
  ADD KEY `fk_review_transaksi_id` (`transaksi_id`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_invoice` (`kode_invoice`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tb_user_alamat`
--
ALTER TABLE `tb_user_alamat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_alamat_user` (`user_id`);

--
-- Indexes for table `tb_zona_pengiriman`
--
ALTER TABLE `tb_zona_pengiriman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_voucher` (`kode_voucher`),
  ADD KEY `fk_voucher_toko` (`toko_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_barang`
--
ALTER TABLE `tb_barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_barang_variasi`
--
ALTER TABLE `tb_barang_variasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_biaya_pengiriman`
--
ALTER TABLE `tb_biaya_pengiriman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_flash_sale_events`
--
ALTER TABLE `tb_flash_sale_events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_flash_sale_produk`
--
ALTER TABLE `tb_flash_sale_produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_gambar_barang`
--
ALTER TABLE `tb_gambar_barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_keranjang`
--
ALTER TABLE `tb_keranjang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_komisi`
--
ALTER TABLE `tb_komisi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_komplain`
--
ALTER TABLE `tb_komplain`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_kurir_toko`
--
ALTER TABLE `tb_kurir_toko`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_payouts`
--
ALTER TABLE `tb_payouts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_review_produk`
--
ALTER TABLE `tb_review_produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_stok_histori`
--
ALTER TABLE `tb_stok_histori`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_toko`
--
ALTER TABLE `tb_toko`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tb_toko_dekorasi`
--
ALTER TABLE `tb_toko_dekorasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_toko_jam_operasional`
--
ALTER TABLE `tb_toko_jam_operasional`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_toko_review`
--
ALTER TABLE `tb_toko_review`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_user_alamat`
--
ALTER TABLE `tb_user_alamat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_zona_pengiriman`
--
ALTER TABLE `tb_zona_pengiriman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `fk_chat_customer` FOREIGN KEY (`customer_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chat_toko` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `fk_cities_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `fk_districts_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_message_chat` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `tb_kategori` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_barang_toko` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_barang_variasi`
--
ALTER TABLE `tb_barang_variasi`
  ADD CONSTRAINT `fk_variasi_barang` FOREIGN KEY (`barang_id`) REFERENCES `tb_barang` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_biaya_pengiriman`
--
ALTER TABLE `tb_biaya_pengiriman`
  ADD CONSTRAINT `fk_biaya_zona` FOREIGN KEY (`zona_id`) REFERENCES `tb_zona_pengiriman` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD CONSTRAINT `fk_detail_transaksi_toko` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_detail_transaksi_utama` FOREIGN KEY (`transaksi_id`) REFERENCES `tb_transaksi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_flash_sale_produk`
--
ALTER TABLE `tb_flash_sale_produk`
  ADD CONSTRAINT `fk_fs_barang` FOREIGN KEY (`barang_id`) REFERENCES `tb_barang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fs_event` FOREIGN KEY (`event_id`) REFERENCES `tb_flash_sale_events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fs_toko` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_gambar_barang`
--
ALTER TABLE `tb_gambar_barang`
  ADD CONSTRAINT `fk_gambar_barang` FOREIGN KEY (`barang_id`) REFERENCES `tb_barang` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD CONSTRAINT `fk_kategori_parent` FOREIGN KEY (`parent_id`) REFERENCES `tb_kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_keranjang`
--
ALTER TABLE `tb_keranjang`
  ADD CONSTRAINT `fk_keranjang_barang` FOREIGN KEY (`barang_id`) REFERENCES `tb_barang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_keranjang_user` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_komisi`
--
ALTER TABLE `tb_komisi`
  ADD CONSTRAINT `fk_komisi_detail` FOREIGN KEY (`detail_transaksi_id`) REFERENCES `tb_detail_transaksi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_komplain`
--
ALTER TABLE `tb_komplain`
  ADD CONSTRAINT `fk_komplain_toko` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_komplain_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `tb_transaksi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_komplain_user` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_kurir_toko`
--
ALTER TABLE `tb_kurir_toko`
  ADD CONSTRAINT `fk_kurir_toko` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_payouts`
--
ALTER TABLE `tb_payouts`
  ADD CONSTRAINT `fk_payout_toko` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_review_produk`
--
ALTER TABLE `tb_review_produk`
  ADD CONSTRAINT `fk_review_detail` FOREIGN KEY (`detail_transaksi_id`) REFERENCES `tb_detail_transaksi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_toko`
--
ALTER TABLE `tb_toko`
  ADD CONSTRAINT `fk_toko_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_toko_district` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_toko_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_toko_user` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_toko_dekorasi`
--
ALTER TABLE `tb_toko_dekorasi`
  ADD CONSTRAINT `fk_dekorasi_toko` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_toko_jam_operasional`
--
ALTER TABLE `tb_toko_jam_operasional`
  ADD CONSTRAINT `fk_jam_toko` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_toko_pengaturan`
--
ALTER TABLE `tb_toko_pengaturan`
  ADD CONSTRAINT `fk_pengaturan_toko` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_toko_review`
--
ALTER TABLE `tb_toko_review`
  ADD CONSTRAINT `fk_review_toko_id` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_transaksi_id` FOREIGN KEY (`transaksi_id`) REFERENCES `tb_transaksi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_user_id` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_user_alamat`
--
ALTER TABLE `tb_user_alamat`
  ADD CONSTRAINT `fk_alamat_user` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `fk_voucher_toko` FOREIGN KEY (`toko_id`) REFERENCES `tb_toko` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
