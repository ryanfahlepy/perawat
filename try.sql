-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 04:51 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `try`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2021-10-09-155551', 'App\\Database\\Migrations\\CreateTbUser', 'default', 'App', 1633796883, 1),
(2, '2021-10-09-155657', 'App\\Database\\Migrations\\CreateTbUserLevel', 'default', 'App', 1633796883, 1),
(3, '2021-10-09-155919', 'App\\Database\\Migrations\\CreateTbUserLevelAkses', 'default', 'App', 1633796884, 1),
(4, '2021-10-09-161214', 'App\\Database\\Migrations\\CreateTbMenu', 'default', 'App', 1633796884, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ref_cara_pbj`
--

CREATE TABLE `ref_cara_pbj` (
  `id` int(1) NOT NULL,
  `cara_pbj` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ref_cara_pbj`
--

INSERT INTO `ref_cara_pbj` (`id`, `cara_pbj`) VALUES
(1, 'SWAKELOLA'),
(2, 'PENYEDIA');

-- --------------------------------------------------------

--
-- Table structure for table `ref_cara_swakelola`
--

CREATE TABLE `ref_cara_swakelola` (
  `id` int(11) NOT NULL,
  `cara_swakelola` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ref_cara_swakelola`
--

INSERT INTO `ref_cara_swakelola` (`id`, `cara_swakelola`) VALUES
(1, 'PENYEDIA DALAM SWAKELOLA'),
(2, 'SWAKELOLA MURNI');

-- --------------------------------------------------------

--
-- Table structure for table `ref_divisi`
--

CREATE TABLE `ref_divisi` (
  `id` int(11) NOT NULL,
  `nama_divisi` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ref_divisi`
--

INSERT INTO `ref_divisi` (`id`, `nama_divisi`) VALUES
(1, 'Bangsis'),
(2, 'Harsis'),
(3, 'Duknis'),
(4, 'LPSE');

-- --------------------------------------------------------

--
-- Table structure for table `ref_jenis_pbj`
--

CREATE TABLE `ref_jenis_pbj` (
  `id` int(1) NOT NULL,
  `jenis_pbj` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ref_jenis_pbj`
--

INSERT INTO `ref_jenis_pbj` (`id`, `jenis_pbj`) VALUES
(1, 'BARANG'),
(2, 'PEKERJAAN KONSTRUKSI'),
(3, 'JASA KONSULTANSI'),
(4, 'JASA LAINNYA');

-- --------------------------------------------------------

--
-- Table structure for table `ref_kontrak`
--

CREATE TABLE `ref_kontrak` (
  `id` int(1) NOT NULL,
  `kontrak` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ref_kontrak`
--

INSERT INTO `ref_kontrak` (`id`, `kontrak`) VALUES
(1, 'LUMSUM'),
(2, 'HARGA SATUAN'),
(3, 'GABUNGAN LUMSUM DAN HARGA SATUAN'),
(4, 'KONTRAK PAYUNG'),
(5, 'PUTAR KUNCI'),
(6, 'BIAYA PLUS IMBALAN'),
(7, 'WAKTU PENUGASAN');

-- --------------------------------------------------------

--
-- Table structure for table `ref_metode_pemilihan`
--

CREATE TABLE `ref_metode_pemilihan` (
  `id` int(1) NOT NULL,
  `metode_pemilihan` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ref_metode_pemilihan`
--

INSERT INTO `ref_metode_pemilihan` (`id`, `metode_pemilihan`) VALUES
(1, 'E-PURCHASING'),
(2, 'PENGADAAN LANGSUNG'),
(3, 'PENUNJUKAN LANGSUNG'),
(4, 'TENDER CEPAT'),
(5, 'TENDER'),
(6, 'SELEKSI'),
(7, 'TIPE 1 (KLPD SENDIRI)'),
(8, 'TIPE 2 (KLPD LAIN DAN PTN)'),
(9, 'TIPE 3 (ORGANISASI DAN PTS)'),
(10, 'TIPE 4 (MASYARAKAT)');

-- --------------------------------------------------------

--
-- Table structure for table `ref_tipe_swakelola`
--

CREATE TABLE `ref_tipe_swakelola` (
  `id` int(11) NOT NULL,
  `tipe_swakelola` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ref_tipe_swakelola`
--

INSERT INTO `ref_tipe_swakelola` (`id`, `tipe_swakelola`) VALUES
(1, 'TIPE 1 (KLPD SENDIRI)'),
(2, 'TIPE 2 (KLPD LAIN DAN PTN)'),
(3, 'TIPE 3 (ORGANISASI DAN PTS)'),
(4, 'TIPE 4 (MASYARAKAT)');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_akses`
--

CREATE TABLE `tabel_akses` (
  `id_akses` int(11) NOT NULL,
  `id_profil` int(11) NOT NULL,
  `id_navigasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tabel_akses`
--

INSERT INTO `tabel_akses` (`id_akses`, `id_profil`, `id_navigasi`) VALUES
(22, 1, 2),
(24, 1, 4),
(30, 1, 3),
(43, 1, 27),
(44, 1, 28),
(46, 1, 29),
(47, 1, 30),
(50, 1, 1),
(52, 1, 5),
(55, 1, 31),
(56, 1, 32),
(57, 1, 33),
(58, 1, 34),
(59, 1, 36),
(60, 1, 35),
(61, 4, 31),
(63, 4, 1),
(64, 5, 1),
(65, 2, 1),
(66, 2, 31),
(67, 2, 32),
(68, 2, 34),
(69, 2, 35),
(70, 3, 1),
(71, 3, 31),
(72, 3, 32),
(73, 3, 35),
(74, 3, 34),
(75, 4, 32),
(76, 4, 34),
(77, 4, 35),
(78, 6, 1),
(79, 6, 32),
(80, 7, 1),
(81, 7, 34),
(82, 8, 1),
(83, 8, 35),
(84, 5, 31);

-- --------------------------------------------------------

--
-- Table structure for table `tabel_config`
--

CREATE TABLE `tabel_config` (
  `id_config` int(11) NOT NULL,
  `brand` varchar(128) NOT NULL,
  `copyright` varchar(128) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `background` varchar(255) NOT NULL,
  `login_title` varchar(128) NOT NULL,
  `sidebar` varchar(128) NOT NULL,
  `navbar` varchar(128) NOT NULL,
  `brandlogo` varchar(128) NOT NULL,
  `brandcolor` varchar(128) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `author` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_config`
--

INSERT INTO `tabel_config` (`id_config`, `brand`, `copyright`, `logo`, `background`, `login_title`, `sidebar`, `navbar`, `brandlogo`, `brandcolor`, `keywords`, `description`, `author`) VALUES
(1, 'Disinfolahtal', 'Ryan', 'b0c1d930666267cc7e448e108ae48d01.png', '1c2815130a4e7496080281c557736442.jpeg', 'Sistem Pengadaan Barang dan Jasa Disinfolahtal', 'sidebar-light-lightblue', 'navbar-dark navbar-lightblue', 'navbar-lightblue', 'text-white', 'CI3, Bootsrap 4, Admin LTE3', 'Template Admin LTE 3  || Bootsrap 4 || CodeIgniter 3', 'Ryan');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_ep`
--

CREATE TABLE `tabel_ep` (
  `id_dokumen` int(10) NOT NULL,
  `dokumen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tabel_ep`
--

INSERT INTO `tabel_ep` (`id_dokumen`, `dokumen`) VALUES
(1, 'Sprin Pejabat Pembuat Komitmen'),
(2, 'Pakta Integritas Pejabat Pengadaan'),
(3, 'Dokumen Persiapan E-Purchasinggg'),
(4, 'Sprin Pejabat Pengadaan'),
(5, 'BA Negosiasi Harga'),
(6, 'Kontrak'),
(7, 'Faktur'),
(8, 'Surat Jalan'),
(9, 'Sugrat Perintah Kerja'),
(10, 'Pakta Integritas Pejabat Penerima'),
(11, 'Surat Pengantar Barang/Surat Jalan'),
(12, 'Surat Tanda Penerimaan Barang'),
(13, 'BA Serah Terima Hasil Pekerjaan'),
(14, 'Faktur Penjualan'),
(15, 'Kwitansi'),
(16, 'Permohonan Pembayaran'),
(17, 'Kwitansi Pembayaran Langsung'),
(18, 'BA Pembayaran'),
(19, 'SPTJM'),
(20, 'SPP Satker'),
(21, 'KU-17');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_file`
--

CREATE TABLE `tabel_file` (
  `id` int(11) NOT NULL,
  `fase_pengadaan` varchar(11) NOT NULL,
  `ref_divisi_id` int(11) DEFAULT NULL,
  `ref_pengadaan_id` int(11) NOT NULL,
  `label_file` varchar(256) NOT NULL,
  `nama_file` varchar(256) NOT NULL,
  `waktu_upload` datetime DEFAULT NULL,
  `waktu_delete` datetime DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  `read_by` varchar(3) DEFAULT NULL,
  `pengunggah` varchar(15) DEFAULT NULL,
  `penghapus` varchar(11) DEFAULT NULL,
  `read_delete_by` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tabel_file`
--

INSERT INTO `tabel_file` (`id`, `fase_pengadaan`, `ref_divisi_id`, `ref_pengadaan_id`, `label_file`, `nama_file`, `waktu_upload`, `waktu_delete`, `status`, `read_by`, `pengunggah`, `penghapus`, `read_delete_by`) VALUES
(1, 'perencanaan', 1, 51, 'Apresiasi', 'perencanaan-51-Apresiasi-20092024-161137.pdf', '2024-09-20 16:11:37', NULL, 'uploaded', NULL, '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tabel_icon`
--

CREATE TABLE `tabel_icon` (
  `id_icon` int(11) NOT NULL,
  `icon` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_icon`
--

INSERT INTO `tabel_icon` (`id_icon`, `icon`) VALUES
(66, '	fab fa-accessible-icon'),
(67, '	fas fa-american-sign-language-interpreting'),
(295, '	fas fa-hourglass-half'),
(282, '	fas fa-pencil-alt'),
(18, 'fa fa-server'),
(260, 'fab fa-bluetooth	'),
(261, 'fab fa-bluetooth-b	'),
(139, 'fab fa-youtube	'),
(158, 'far fa-address-book	'),
(160, 'far fa-address-card	'),
(96, 'far fa-arrow-alt-circle-down	'),
(98, 'far fa-arrow-alt-circle-left	'),
(100, 'far fa-arrow-alt-circle-right	'),
(102, 'far fa-arrow-alt-circle-up	'),
(78, 'far fa-bell	'),
(80, 'far fa-bell-slash	'),
(141, 'far fa-building	'),
(22, 'far fa-building'),
(168, 'far fa-calendar	'),
(170, 'far fa-calendar-alt	'),
(174, 'far fa-chart-bar	'),
(1, 'far fa-circle'),
(179, 'far fa-clipboard	'),
(299, 'far fa-clock'),
(217, 'far fa-comment	'),
(219, 'far fa-comment-dots	'),
(222, 'far fa-comments	'),
(181, 'far fa-copy	'),
(183, 'far fa-copyright	'),
(317, 'far fa-credit-card	'),
(186, 'far fa-edit	'),
(189, 'far fa-envelope	'),
(190, 'far fa-envelope-open	'),
(59, 'far fa-eye'),
(61, 'far fa-eye-slash'),
(194, 'far fa-file	'),
(196, 'far fa-file-alt	'),
(116, 'far fa-file-audio	'),
(245, 'far fa-file-code	'),
(118, 'far fa-file-video	'),
(198, 'far fa-folder	'),
(251, 'far fa-folder-open	'),
(224, 'far fa-frown	'),
(240, 'far fa-handshake	'),
(267, 'far fa-hdd'),
(146, 'far fa-hospital	'),
(25, 'far fa-hospital'),
(293, 'far fa-hourglass'),
(64, 'far fa-image'),
(65, 'far fa-images'),
(352, 'far fa-keyboard'),
(312, 'far fa-lightbulb	'),
(212, 'far fa-map	'),
(226, 'far fa-meh	'),
(289, 'far fa-money-bill-alt'),
(347, 'far fa-plus-square	'),
(73, 'far fa-question-circle	'),
(228, 'far fa-smile	'),
(329, 'far fa-user-circle	'),
(257, 'far fa-window-close	'),
(258, 'far fa-window-minimize	'),
(157, 'fas fa-address-book	'),
(159, 'fas fa-address-card	'),
(36, 'fas fa-ambulance'),
(87, 'fas fa-angle-double-down	'),
(88, 'fas fa-angle-double-left	'),
(89, 'fas fa-angle-double-right	'),
(90, 'fas fa-angle-double-up	'),
(91, 'fas fa-angle-down	'),
(92, 'fas fa-angle-left	'),
(93, 'fas fa-angle-right	'),
(94, 'fas fa-angle-up	'),
(161, 'fas fa-archive	'),
(95, 'fas fa-arrow-alt-circle-down	'),
(97, 'fas fa-arrow-alt-circle-left	'),
(99, 'fas fa-arrow-alt-circle-right	'),
(101, 'fas fa-arrow-alt-circle-up	'),
(103, 'fas fa-arrow-circle-down	'),
(104, 'fas fa-arrow-circle-left	'),
(105, 'fas fa-arrow-circle-right	'),
(106, 'fas fa-arrow-circle-up	'),
(107, 'fas fa-arrow-down	'),
(108, 'fas fa-arrow-left	'),
(109, 'fas fa-arrow-right	'),
(110, 'fas fa-arrow-up	'),
(111, 'fas fa-arrows-alt	'),
(68, 'fas fa-assistive-listening-systems	'),
(112, 'fas fa-backward	'),
(162, 'fas fa-balance-scale	'),
(233, 'fas fa-barcode	'),
(306, 'fas fa-battery-empty	'),
(307, 'fas fa-battery-full	'),
(308, 'fas fa-battery-half	'),
(309, 'fas fa-battery-quarter	'),
(310, 'fas fa-battery-three-quarters	'),
(77, 'fas fa-bell	'),
(79, 'fas fa-bell-slash	'),
(163, 'fas fa-birthday-cake	'),
(69, 'fas fa-blind	'),
(164, 'fas fa-book	'),
(301, 'fas fa-book-open'),
(302, 'fas fa-book-reader'),
(165, 'fas fa-briefcase	'),
(140, 'fas fa-building	'),
(21, 'fas fa-building'),
(166, 'fas fa-bullhorn	'),
(167, 'fas fa-calendar	'),
(169, 'fas fa-calendar-alt	'),
(52, 'fas fa-camera'),
(37, 'fas fa-capsules'),
(53, 'fas fa-cart-arrow-down'),
(54, 'fas fa-cart-plus'),
(171, 'fas fa-certificate	'),
(303, 'fas fa-chalkboard'),
(300, 'fas fa-chalkboard-teacher'),
(172, 'fas fa-chart-area	'),
(173, 'fas fa-chart-bar	'),
(175, 'fas fa-chart-line	'),
(176, 'fas fa-chart-pie	'),
(320, 'fas fa-child	'),
(142, 'fas fa-church	'),
(177, 'fas fa-city	'),
(38, 'fas fa-clinic-medical'),
(178, 'fas fa-clipboard	'),
(298, 'fas fa-clock'),
(232, 'fas fa-code	'),
(243, 'fas fa-coffee	'),
(3, 'fas fa-cogs'),
(313, 'fas fa-coins	'),
(216, 'fas fa-comment	'),
(314, 'fas fa-comment-dollar	'),
(33, 'fas fa-comment-dollar'),
(218, 'fas fa-comment-dots	'),
(220, 'fas fa-comment-slash	'),
(221, 'fas fa-comments	'),
(315, 'fas fa-comments-dollar	'),
(180, 'fas fa-copy	'),
(182, 'fas fa-copyright	'),
(316, 'fas fa-credit-card	'),
(184, 'fas fa-cut	'),
(262, 'fas fa-database'),
(263, 'fas fa-desktop'),
(234, 'fas fa-dollar-sign	'),
(235, 'fas fa-donate	'),
(264, 'fas fa-download'),
(185, 'fas fa-edit	'),
(188, 'fas fa-envelope	'),
(187, 'fas fa-envelope-open	'),
(34, 'fas fa-envelope-open-text'),
(191, 'fas fa-eraser	'),
(265, 'fas fa-ethernet'),
(81, 'fas fa-exclamation	'),
(82, 'fas fa-exclamation-circle	'),
(83, 'fas fa-exclamation-triangle	'),
(58, 'fas fa-eye'),
(60, 'fas fa-eye-slash'),
(113, 'fas fa-fast-backward	'),
(114, 'fas fa-fast-forward	'),
(192, 'fas fa-fax	'),
(321, 'fas fa-female	'),
(193, 'fas fa-file	'),
(195, 'fas fa-file-alt	'),
(16, 'fas fa-file-archive'),
(115, 'fas fa-file-audio	'),
(15, 'fas fa-file-audio'),
(244, 'fas fa-file-code	'),
(14, 'fas fa-file-code'),
(17, 'fas fa-file-download'),
(13, 'fas fa-file-excel'),
(12, 'fas fa-file-image'),
(318, 'fas fa-file-invoice	'),
(319, 'fas fa-file-invoice-dollar	'),
(39, 'fas fa-file-medical'),
(40, 'fas fa-file-medical-alt'),
(11, 'fas fa-file-pdf'),
(10, 'fas fa-file-powerpoint'),
(117, 'fas fa-file-video	'),
(9, 'fas fa-file-video'),
(8, 'fas fa-file-word'),
(119, 'fas fa-film	'),
(63, 'fas fa-film'),
(246, 'fas fa-filter	'),
(210, 'fas fa-fire	'),
(247, 'fas fa-fire-extinguisher	'),
(197, 'fas fa-folder	'),
(250, 'fas fa-folder-open	'),
(120, 'fas fa-forward	'),
(223, 'fas fa-frown	'),
(2, 'fas fa-fw fa-tachometer-alt'),
(5, 'fas fa-fw fa-user'),
(4, 'fas fa-fw fa-users'),
(143, 'fas fa-gopuram	'),
(231, 'fas fa-graduation-cap	'),
(236, 'fas fa-hand-holding-heart	'),
(237, 'fas fa-hand-holding-usd	'),
(238, 'fas fa-hands-helping	'),
(239, 'fas fa-handshake	'),
(57, 'fas fa-handshake'),
(266, 'fas fa-hdd'),
(121, 'fas fa-headphones	'),
(268, 'fas fa-headphones'),
(41, 'fas fa-heart'),
(144, 'fas fa-home	'),
(145, 'fas fa-hospital	'),
(42, 'fas fa-hospital'),
(147, 'fas fa-hospital-alt	'),
(44, 'fas fa-hospital-alt'),
(292, 'fas fa-hourglass'),
(294, 'fas fa-hourglass-end'),
(296, 'fas fa-hourglass-start'),
(148, 'fas fa-house-damage	'),
(27, 'fas fa-house-damage'),
(149, 'fas fa-industry	'),
(28, 'fas fa-industry'),
(349, 'fas fa-keyboard'),
(200, 'fas fa-landmark	'),
(269, 'fas fa-laptop'),
(311, 'fas fa-lightbulb	'),
(70, 'fas fa-low-vision	'),
(35, 'fas fa-mail-bulk'),
(322, 'fas fa-male	'),
(211, 'fas fa-map	'),
(213, 'fas fa-map-marked	'),
(214, 'fas fa-map-marked-alt	'),
(202, 'fas fa-marker	'),
(225, 'fas fa-meh	'),
(122, 'fas fa-microphone	'),
(123, 'fas fa-microphone-alt	'),
(124, 'fas fa-microphone-alt-slash	'),
(125, 'fas fa-microphone-slash	'),
(270, 'fas fa-mobile'),
(271, 'fas fa-mobile-alt'),
(290, 'fas fa-money-check'),
(55, 'fas fa-money-check-alt'),
(150, 'fas fa-mosque	'),
(29, 'fas fa-mosque'),
(126, 'fas fa-music	'),
(45, 'fas fa-notes-medical'),
(281, 'fas fa-paint-roller'),
(127, 'fas fa-pause	'),
(128, 'fas fa-pause-circle	'),
(201, 'fas fa-pen	'),
(203, 'fas fa-percent	'),
(204, 'fas fa-phone	'),
(71, 'fas fa-phone-volume	'),
(46, 'fas fa-pills'),
(151, 'fas fa-place-of-worship	'),
(129, 'fas fa-play	'),
(272, 'fas fa-plug'),
(344, 'fas fa-plus	'),
(345, 'fas fa-plus-circle	'),
(346, 'fas fa-plus-square	'),
(273, 'fas fa-power-off'),
(47, 'fas fa-prescription-bottle-alt'),
(274, 'fas fa-print'),
(48, 'fas fa-procedures'),
(254, 'fas fa-project-diagram	'),
(255, 'fas fa-qrcode	'),
(72, 'fas fa-question-circle	'),
(84, 'fas fa-radiation	'),
(85, 'fas fa-radiation-alt	'),
(130, 'fas fa-random	'),
(323, 'fas fa-restroom	'),
(283, 'fas fa-ruler'),
(152, 'fas fa-school	'),
(284, 'fas fa-screwdriver'),
(275, 'fas fa-server'),
(253, 'fas fa-shield-alt	'),
(242, 'fas fa-shoe-prints	'),
(56, 'fas fa-shopping-cart'),
(343, 'fas fa-sign-in-alt	'),
(74, 'fas fa-sign-language	'),
(252, 'fas fa-sitemap	'),
(86, 'fas fa-skull-crossbones	'),
(227, 'fas fa-smile	'),
(131, 'fas fa-stop	'),
(297, 'fas fa-stopwatch'),
(153, 'fas fa-synagogue	'),
(132, 'fas fa-sync-alt	'),
(49, 'fas fa-syringe'),
(277, 'fas fa-tablet'),
(276, 'fas fa-tablet-alt'),
(205, 'fas fa-tag	'),
(206, 'fas fa-tags	'),
(207, 'fas fa-tasks	'),
(7, 'fas fa-th-list'),
(208, 'fas fa-thumbtack	'),
(285, 'fas fa-toolbox'),
(286, 'fas fa-tools'),
(154, 'fas fa-torii-gate	'),
(31, 'fas fa-torii-gate'),
(215, 'fas fa-tree	'),
(287, 'fas fa-truck-pickup'),
(241, 'fas fa-tshirt	'),
(278, 'fas fa-tv'),
(133, 'fas fa-undo-alt	'),
(155, 'fas fa-university	'),
(30, 'fas fa-university'),
(279, 'fas fa-upload'),
(324, 'fas fa-user-alt	'),
(325, 'fas fa-user-alt-slash	'),
(326, 'fas fa-user-astronaut	'),
(327, 'fas fa-user-check	'),
(328, 'fas fa-user-circle	'),
(330, 'fas fa-user-clock	'),
(331, 'fas fa-user-cog	'),
(332, 'fas fa-user-edit	'),
(333, 'fas fa-user-friends	'),
(305, 'fas fa-user-graduate'),
(334, 'fas fa-user-injured	'),
(335, 'fas fa-user-lock	'),
(50, 'fas fa-user-md'),
(336, 'fas fa-user-minus	'),
(51, 'fas fa-user-nurse'),
(337, 'fas fa-user-plus	'),
(338, 'fas fa-user-secret	'),
(339, 'fas fa-user-shield	'),
(340, 'fas fa-user-slash	'),
(341, 'fas fa-user-tag	'),
(342, 'fas fa-user-times	'),
(6, 'fas fa-users-cog'),
(134, 'fas fa-video	'),
(230, 'fas fa-video-slash	'),
(156, 'fas fa-vihara	'),
(135, 'fas fa-volume-down	'),
(136, 'fas fa-volume-mute	'),
(137, 'fas fa-volume-off	'),
(138, 'fas fa-volume-up	'),
(209, 'fas fa-wallet'),
(76, 'fas fa-wheelchair	'),
(348, 'fas fa-wifi	'),
(256, 'fas fa-window-close	'),
(259, 'fas fa-window-minimize	'),
(288, 'fas fa-wrench');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_navigasi`
--

CREATE TABLE `tabel_navigasi` (
  `id_navigasi` int(11) NOT NULL,
  `navigasi` varchar(128) NOT NULL,
  `heading` varchar(128) NOT NULL,
  `url` varchar(128) NOT NULL,
  `icon` varchar(128) NOT NULL,
  `dropdown` int(11) NOT NULL,
  `urutan` int(11) NOT NULL,
  `aktif` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tabel_navigasi`
--

INSERT INTO `tabel_navigasi` (`id_navigasi`, `navigasi`, `heading`, `url`, `icon`, `dropdown`, `urutan`, `aktif`) VALUES
(1, 'Dashboard', '', 'dashboard', 'fas fa-fw fa-tachometer-alt', 0, 1, 'Yes'),
(2, 'Sistem', '', '#', 'fas fa-cogs', 0, 99, 'Yes'),
(3, 'Icons', '', 'icon', 'fas fa-qrcode	', 2, 1, 'Yes'),
(4, 'Config', '', 'config', 'fas fa-tools', 2, 2, 'Yes'),
(5, 'User Management', '', '#', 'fas fa-fw fa-users', 0, 100, 'Yes'),
(27, 'Navigasi', '', 'navigasi', 'fas fa-random	', 5, 1, 'Yes'),
(28, 'Profil', '', 'profil', 'fas fa-user-tag	', 5, 2, 'Yes'),
(29, 'User', '', 'user', 'fas fa-user-edit	', 5, 100, 'Yes'),
(31, 'Bangsis', 'Subdis', 'subdisbangsis', 'fas fa-user-alt', 0, 4, 'Yes'),
(32, 'Harsis', '', 'subdisharsis', 'fas fa-user-alt', 0, 5, 'Yes'),
(34, 'Duknis', '', 'subdisduknis', 'fas fa-user-alt', 0, 6, 'Yes'),
(35, 'LPSE', '', 'subdislpse', 'fas fa-user-alt', 0, 7, 'Yes'),
(36, 'Template Pengadaan', 'Master Data', 'template', 'fas fa-server', 0, 98, 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_pengadaan`
--

CREATE TABLE `tabel_pengadaan` (
  `id` int(5) NOT NULL,
  `nama_pengadaan` varchar(255) DEFAULT NULL,
  `ref_divisi_id` int(5) DEFAULT NULL,
  `ref_jenis_pbj_id` int(11) DEFAULT NULL,
  `ref_cara_pbj_id` int(11) DEFAULT NULL,
  `ref_metode_pemilihan_id` int(11) DEFAULT NULL,
  `ref_cara_swakelola_id` int(11) DEFAULT NULL,
  `ref_kontrak_id` int(11) DEFAULT NULL,
  `pa` varchar(255) DEFAULT NULL,
  `kpa` varchar(255) DEFAULT NULL,
  `ppk` varchar(255) DEFAULT NULL,
  `pejabat_pengadaan` varchar(255) DEFAULT NULL,
  `pokja_pengadaan` varchar(255) DEFAULT NULL,
  `agen_pengadaan` varchar(255) DEFAULT NULL,
  `penyelenggara_swakelola` varchar(255) DEFAULT NULL,
  `penyedia` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_berakhir` date DEFAULT NULL,
  `tanggal_berakhir_percepatan` date DEFAULT NULL,
  `persen_waktu_perencanaan` int(3) DEFAULT NULL,
  `persen_waktu_pelaksanaan` int(3) DEFAULT NULL,
  `persen_waktu_pengakhiran` int(3) DEFAULT NULL,
  `dokumen_perencanaan_terupload` int(11) DEFAULT 0,
  `total_dokumen_perencanaan` int(11) DEFAULT 0,
  `dokumen_pelaksanaan_terupload` int(11) DEFAULT 0,
  `total_dokumen_pelaksanaan` int(11) DEFAULT 0,
  `dokumen_pengakhiran_terupload` int(11) DEFAULT 0,
  `total_dokumen_pengakhiran` int(11) DEFAULT 0,
  `progress` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tabel_pengadaan`
--

INSERT INTO `tabel_pengadaan` (`id`, `nama_pengadaan`, `ref_divisi_id`, `ref_jenis_pbj_id`, `ref_cara_pbj_id`, `ref_metode_pemilihan_id`, `ref_cara_swakelola_id`, `ref_kontrak_id`, `pa`, `kpa`, `ppk`, `pejabat_pengadaan`, `pokja_pengadaan`, `agen_pengadaan`, `penyelenggara_swakelola`, `penyedia`, `tanggal_mulai`, `tanggal_berakhir`, `tanggal_berakhir_percepatan`, `persen_waktu_perencanaan`, `persen_waktu_pelaksanaan`, `persen_waktu_pengakhiran`, `dokumen_perencanaan_terupload`, `total_dokumen_perencanaan`, `dokumen_pelaksanaan_terupload`, `total_dokumen_pelaksanaan`, `dokumen_pengakhiran_terupload`, `total_dokumen_pengakhiran`, `progress`) VALUES
(2, 'LAPTOP ASUS', 1, 1, 2, 1, 0, 2, 'Kadis', 'Sekdis', 'Kasubdisbangsis', 'Paur Programmer', 'Bangsis', 'Ryan', 'PNS', 'PT MAKMUR (PEMENANG),PT SEJAHTERA', '2024-02-03', '2024-12-03', '2024-08-03', 30, 60, 10, 4, 4, 1, 1, 2, 2, NULL),
(3, 'LOMBA 17 AGUSTUS 2025', 2, 1, 2, 5, NULL, 1, 'Kadis', 'Sekdis', 'Kasubdis', 'PA Programmer', 'Bangsis', 'Mabesal', 'Ryan', 'PT Adil,PT Abadi', '2025-04-04', '2026-04-04', '2025-12-04', 20, 60, 20, 0, 0, 0, 0, 0, 0, NULL),
(4, 'SERVICE AC', 4, 4, 2, 1, NULL, 1, 'Kadis', 'Sekdis', 'Kasubdis', 'Ryan', 'Bangsis', 'Ucup', 'Dani', '', '2025-04-04', '2026-04-04', '2025-12-04', 20, 70, 10, 0, 0, 0, 0, 0, 0, NULL),
(5, 'SERVER', 3, 1, 2, 5, NULL, 3, 'Kasal', 'Kadis', 'Kasubdis', 'PNS', 'Bangsis', 'Ryan', 'Rio', 'PT SEJAHTERA,PT MAKMUR,PT ADIL', '2024-04-10', '2025-04-10', '2024-12-01', 20, 60, 20, 0, 0, 0, 0, 0, 0, NULL),
(6, 'HP DISINDOLAHTAL', 2, 1, 1, 1, NULL, 1, 'Ryan', 'Ryan', 'Ryan', 'Ryan', 'Ryan', 'Ryan', 'Ryan', 'Ryan', '2024-08-01', '2025-08-01', '2024-12-01', 20, 50, 30, 0, 0, 0, 0, 0, 0, NULL),
(7, 'PEMBANGUNAN GEDUNG KANTOR', 2, 3, 1, 2, NULL, 1, 'Kepala Dinas', 'Wakil Kepala Dinas', 'Kabag Umum', 'Pengadaan 1', 'Tim Gedung', 'Agus', 'Dinas Pekerjaan Umum', 'PT KONSTRUKSI', '2024-01-15', '2025-01-15', '2024-12-15', 25, 65, 10, 0, 0, 0, 0, 0, 0, NULL),
(8, 'PENGADAAN KOMPUTER', 3, 2, 2, 3, NULL, 2, 'Kepala IT', 'Wakil IT', 'Koordinator IT', 'Pengadaan 2', 'Tim IT', 'Fajar', 'Biro IT', 'PT TEKNOKOM', '2024-03-01', '2024-12-01', '2024-09-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(9, 'PEMELIHARAAN JARINGAN', 3, 1, 3, 4, NULL, 3, 'Kepala Divisi', 'Wakil Divisi', 'Kasubdiv', 'Pengadaan 3', 'Tim Jaringan', 'Budi', 'Biro Jaringan', 'PT JARINGAN', '2023-07-10', '2024-07-10', '2024-04-10', 20, 60, 20, 0, 0, 0, 0, 0, 0, NULL),
(10, 'PENGADAAN MOBIL DINAS', 1, 4, 2, 3, 0, 1, 'Kepala Biro', 'Wakil Biro', 'Kabag', 'Pengadaan 4', 'Tim Mobil', 'Cahyadi', 'Biro Umum', 'PT OTOMOTIF', '2025-02-20', '2025-08-20', '2025-05-20', 35, 45, 20, 10, 10, 12, 12, 8, 8, NULL),
(11, 'PENGADAAN ALAT TULIS KANTOR', 2, 3, 1, 2, NULL, 1, 'Kepala Divisi', 'Wakil Divisi', 'Kabag Umum', 'Pengadaan 5', 'Tim ATK', 'Agus', 'Biro Umum', 'PT ATK SEJAHTERA', '2024-05-10', '2024-09-10', '2024-07-10', 40, 40, 20, 0, 0, 0, 0, 0, 0, NULL),
(12, 'RENOVASI RUMAH DINAS', 4, 2, 3, 3, NULL, 2, 'Kepala Biro', 'Wakil Biro', 'Kasubdiv', 'Pengadaan 6', 'Tim Renovasi', 'Fajar', 'Dinas PU', 'PT RENOVASI MAJU', '2023-11-01', '2024-11-01', '2024-07-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(13, 'PEMBANGUNAN JEMBATAN', 2, 4, 2, 4, NULL, 1, 'Kepala Dinas', 'Wakil Kepala Dinas', 'Kabag', 'Pengadaan 7', 'Tim Jembatan', 'Budi', 'Dinas PU', 'PT JEMBATAN JAYA', '2025-03-15', '2026-03-15', '2025-12-15', 25, 60, 15, 0, 0, 0, 0, 0, 0, NULL),
(14, 'PENGADAAN SERVER DATA', 1, 2, 1, 3, NULL, 3, 'Kepala IT', 'Wakil IT', 'Koordinator IT', 'Pengadaan 8', 'Tim Server', 'Cahyadi', 'Biro IT', 'PT DATA TEKNOLOGI', '2024-08-01', '2025-08-01', '2025-02-01', 20, 55, 25, 0, 0, 0, 0, 0, 0, NULL),
(15, 'PENGADAAN SOFTWARE AKUNTANSI', 3, 1, 2, 5, NULL, 2, 'Kepala Biro', 'Wakil Biro', 'Kabag Umum', 'Pengadaan 9', 'Tim Akuntansi', 'Fajar', 'Biro Keuangan', 'PT SOFTWARE JAYA', '2024-02-01', '2024-10-01', '2024-08-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(16, 'PEMELIHARAAN GEDUNG', 2, 4, 1, 4, NULL, 1, 'Kepala Divisi', 'Wakil Divisi', 'Kasubdiv', 'Pengadaan 10', 'Tim Gedung', 'Agus', 'Biro Umum', 'PT GEDUNG PERKASA', '2023-12-01', '2024-12-01', '2024-08-01', 35, 45, 20, 0, 0, 0, 0, 0, 0, NULL),
(17, 'KURSI', 1, 1, 1, 7, 2, 1, 'Kadis', 'Ryan', 'Ryan', 'Rya', 'Ryan', 'Ryan', 'Ryan', 'PT SEJAHTERA,PT KARYA', '2024-09-01', '2025-09-01', '2025-05-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(18, 'Meja Kerja', 1, 2, 2, 1, 0, 1, 'Kepala Bidang', 'Wakil Kepala Bidang', 'Koordinator', 'Tim Pengadaan Meja', 'Tim Meja Kerja', 'Rina', 'Dinas Perlengkapan', 'PT MEJA MANDIRI', '2024-06-01', '2025-06-01', '2024-12-01', 25, 55, 20, 0, 0, 1, 1, 2, 2, NULL),
(19, 'AC', 2, 3, 4, 1, NULL, 2, 'Manager', 'Asisten Manager', 'Supervisor', 'Tim Pengadaan AC', 'Tim AC', 'Budi', 'Dinas Perawatan', 'PT AC SEJATI', '2024-07-01', '2025-07-01', '2025-03-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(20, 'Monitor', 3, 1, 2, 5, NULL, 1, 'Kepala Teknologi', 'Wakil Kepala Teknologi', 'Staf IT', 'Tim Monitor', 'Tim Teknologi', 'Andi', 'Biro IT', 'PT MONITOR CEMERLANG', '2024-08-01', '2025-08-01', '2025-04-01', 20, 60, 20, 0, 0, 0, 0, 0, 0, NULL),
(21, 'Service Bulanan', 4, 2, 3, 4, NULL, 2, 'Koordinator', 'Asisten Koordinator', 'Staf Teknik', 'Tim Servis Bulanan', 'Tim Bulanan', 'Santi', 'Dinas Teknik', 'PT SERVIS MAJU', '2024-09-01', '2025-09-01', '2025-05-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(22, 'PERBAIKAN GEDUNG', 1, 4, 2, 2, 0, 1, 'KEPALA DIVISI', 'WAKIL KEPALA DIVISI', 'KASUBDIV', 'TIM PERBAIKAN GEDUNG', 'TIM GEDUNG', 'FANI', 'DINAS BANGUNAN', 'PT PERBAIKAN MAJU', '2024-10-01', '2025-10-01', '2025-06-01', 30, 50, 20, 1, 10, 0, 0, 0, 0, NULL),
(23, 'Pengadaan Printer', 2, 1, 2, 5, NULL, 2, 'Kepala Operasional', 'Wakil Kepala Operasional', 'Staf Administrasi', 'Tim Pengadaan Printer', 'Tim Printer', 'Joko', 'Biro Perlengkapan', 'PT PRINTER ANDAL', '2024-11-01', '2025-11-01', '2025-07-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(24, 'Kursi Kantor', 3, 2, 3, 4, NULL, 1, 'Manager', 'Asisten Manager', 'Staf Pengadaan', 'Tim Kursi Kantor', 'Tim Kantor', 'Maya', 'Dinas Umum', 'PT KURSI AMAN', '2025-01-01', '2026-01-01', '2025-08-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(25, 'Lemari Arsip', 4, 3, 4, 1, NULL, 2, 'Kepala Bagian', 'Wakil Kepala Bagian', 'Kasubbag', 'Tim Lemari Arsip', 'Tim Arsip', 'Rina', 'Biro Umum', 'PT LEMARI MAJU', '2025-02-01', '2026-02-01', '2025-09-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(27, 'Perawatan Jaringan', 2, 2, 3, 4, NULL, 2, 'Kepala IT', 'Wakil Kepala IT', 'Staf Teknik', 'Tim Perawatan Jaringan', 'Tim Jaringan', 'Toni', 'Biro IT', 'PT JARINGAN SEJAHTERA', '2025-04-01', '2026-04-01', '2026-01-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(28, 'Kamera Keamanan', 3, 3, 4, 1, NULL, 1, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf Pengamanan', 'Tim Kamera Keamanan', 'Tim Keamanan', 'Nina', 'Dinas Keamanan', 'PT KAMERA CEMERLANG', '2025-05-01', '2026-05-01', '2026-02-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(29, 'Laptop', 4, 1, 2, 5, NULL, 2, 'Manager', 'Asisten Manager', 'Staf IT', 'Tim Pengadaan Laptop', 'Tim Laptop', 'Fajar', 'Biro Teknologi', 'PT LAPTOP MAJU', '2025-06-01', '2026-06-01', '2026-03-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(31, 'Pengadaan Alat Olahraga', 2, 3, 4, 1, NULL, 2, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf Olahraga', 'Tim Alat Olahraga', 'Tim Olahraga', 'Eka', 'Dinas Olahraga', 'PT ALAT SEHAT', '2025-08-01', '2026-08-01', '2026-05-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(32, 'Perawatan Mobil', 3, 1, 2, 5, NULL, 1, 'Kepala Bagian', 'Wakil Kepala Bagian', 'Staf Teknik', 'Tim Perawatan Mobil', 'Tim Mobil', 'Rina', 'Dinas Perawatan', 'PT MOBIL PERKASA', '2025-09-01', '2026-09-01', '2026-06-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(33, 'Kegiatan Pelatihan', 4, 2, 3, 4, NULL, 2, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf Pengembangan', 'Tim Pelatihan', 'Tim Pelatihan', 'Agus', 'Dinas Pelatihan', 'PT PELATIHAN ANDAL', '2025-10-01', '2026-10-01', '2026-07-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(35, 'Perbaikan Perangkat Keras', 2, 1, 2, 5, NULL, 2, 'Manager', 'Asisten Manager', 'Staf Teknik', 'Tim Perbaikan Perangkat', 'Tim Teknik', 'Lina', 'Biro Teknik', 'PT PERANGKAT MAJU', '2025-12-01', '2026-12-01', '2026-09-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(36, 'Pengadaan Peralatan Dapur', 3, 2, 3, 4, NULL, 1, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf Pengadaan', 'Tim Dapur', 'Tim Dapur', 'Rina', 'Dinas Dapur', 'PT PERALATAN DAPUR', '2026-01-01', '2027-01-01', '2026-10-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(37, 'Pembangunan Ruang Baru', 4, 3, 4, 1, NULL, 2, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf Bangunan', 'Tim Ruang Baru', 'Tim Bangunan', 'Eka', 'Dinas Pembangunan', 'PT RUANG BARU', '2026-02-01', '2027-02-01', '2026-11-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(39, 'Pembuatan Laporan Tahunan', 2, 2, 3, 4, NULL, 2, 'Manager', 'Asisten Manager', 'Staf Administrasi', 'Tim Laporan Tahunan', 'Tim Administrasi', 'Budi', 'Biro Administrasi', 'PT LAPORAN CEMERLANG', '2026-04-01', '2027-04-01', '2027-02-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(40, 'Pengadaan Software', 3, 3, 4, 1, NULL, 1, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf IT', 'Tim Software', 'Tim Teknologi', 'Nina', 'Dinas Teknologi', 'PT SOFTWARE MAJU', '2026-05-01', '2027-05-01', '2027-03-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(41, 'Perawatan Sistem', 4, 1, 2, 5, NULL, 2, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf IT', 'Tim Perawatan Sistem', 'Tim IT', 'Lina', 'Biro Sistem', 'PT SISTEM SEHAT', '2026-06-01', '2027-06-01', '2027-04-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(43, 'Pembuatan Video Pelatihan', 2, 3, 4, 1, NULL, 2, 'Manager', 'Asisten Manager', 'Staf Media', 'Tim Video Pelatihan', 'Tim Media', 'Joko', 'Biro Media', 'PT VIDEO CEMERLANG', '2026-08-01', '2027-08-01', '2027-06-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(44, 'Perawatan Gedung', 3, 1, 2, 5, NULL, 1, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf Gedung', 'Tim Perawatan Gedung', 'Tim Gedung', 'Toni', 'Dinas Gedung', 'PT GUDANG SEJAHTERA', '2026-09-01', '2027-09-01', '2027-07-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(45, 'Pengadaan Alat Tulis Kantor', 4, 2, 3, 4, NULL, 2, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf Administrasi', 'Tim Alat Tulis', 'Tim Tulis', 'Dina', 'Biro Tulis', 'PT ALAT TULIS MAJU', '2026-10-01', '2027-10-01', '2027-08-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(46, 'Pengadaan Meja Rapat', 2, 3, 2, 4, 0, 1, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf Rapat', 'Tim Meja Rapat', 'Tim Rapat', 'Budi', 'Dinas Rapat', 'PT MEJA CEMERLANG', '2026-11-01', '2027-11-01', '2027-09-01', 30, 50, 20, 0, 0, 0, 0, 0, 0, NULL),
(47, 'PEMBANGUNAN GEDUNG PARKIR', 2, 1, 2, 1, 0, 2, 'MANAGER', 'ASISTEN MANAGER', 'STAF TEKNIK', 'TIM PARKIR', 'TIM GEDUNG PARKIR', 'NINA', 'BIRO TEKNIK', 'PT PARKIR MAJU', '2027-01-01', '2028-01-01', '2027-10-01', 25, 55, 20, 0, 0, 0, 0, 0, 0, NULL),
(48, 'Pengadaan Sistem Keamanan', 3, 2, 2, 4, 0, 1, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf Keamanan', 'Tim Keamanan', 'Tim Sistem Keamanan', 'Lina', 'Dinas Keamanan', 'PT SISTEM KEAMANAN', '2027-02-01', '2028-02-01', '2027-11-01', 30, 50, 20, 1, 10, 0, 0, 0, 0, NULL),
(49, 'Pembuatan Dokumentasi', 4, 3, 2, 1, 0, 2, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf Dokumentasi', 'Tim Dokumentasi', 'Tim Dokumentasi', 'Fani', 'Dinas Dokumentasi', 'PT DOKUMENTASI SEJATI', '2027-03-01', '2028-03-01', '2028-01-01', 25, 55, 20, 1, 5, 0, 0, 0, 0, NULL),
(50, 'Pengadaan Komputer', 2, 1, 2, 5, 0, 1, 'Kepala Divisi', 'Wakil Kepala Divisi', 'Staf IT', 'Tim Komputer', 'Tim Teknologi', 'Toni', 'Biro IT', 'PT KOMPUTER MAJU', '2027-04-01', '2028-04-01', '2028-02-01', 30, 50, 20, 2, 10, 1, 12, 1, 8, NULL),
(51, 'KURSI KERJA', 1, 1, 2, 4, 0, 1, 'RYAN', 'RYAN', 'RIO', 'RYAN', 'RYAN', 'RYAN', 'RYAN', 'PT RYAN SENTOSA', '2024-09-11', '2024-12-11', '2024-11-11', 30, 30, 40, 1, 10, 0, 0, 0, 0, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tabel_pl`
--

CREATE TABLE `tabel_pl` (
  `id_dokumen` int(10) NOT NULL,
  `dokumen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tabel_pl`
--

INSERT INTO `tabel_pl` (`id_dokumen`, `dokumen`) VALUES
(1, 'Sprin Pejabat Pembuat Komitmenn'),
(2, 'Pakta Integritas Pejabat Pengadaan'),
(3, 'Spesifikasi Teknis'),
(4, 'Referensi Harga'),
(5, 'HPSS'),
(6, 'Gambar'),
(7, 'Uraian Singkat Pekerjaan'),
(8, 'Sprin Sebagai Pokja'),
(9, 'Syarat Kualifikasi'),
(10, 'Dokumen Kualifikasi Administrasi'),
(11, 'Dokumen Kualifikasi Teknis'),
(12, 'Dokumen Kualifikasi Harga'),
(13, 'BA Review Dokumen Pemilihan'),
(14, 'Jadwal Tender'),
(15, 'Pengumuman Tender'),
(16, 'BA Pemberian Penjelasan'),
(17, 'BA Pembukaan Penawaran'),
(18, 'BA Hasil Evaluasi'),
(19, 'BA Hasil Negosiasi'),
(20, 'BA Hasil Reverse Auction'),
(21, 'BA Penetapan Pemenang'),
(22, 'Pengumuman Pemenang'),
(23, 'BA Sanggah'),
(24, 'SPPBJ'),
(25, 'Kontrak'),
(26, 'Adendum Kontrak'),
(27, 'Sugrat Perintah Kerja'),
(28, 'Pakta Integritas Pejabat Penerima'),
(29, 'Surat Pengantar Barang/Surat Jalan'),
(30, 'Surat Tanda Penerimaan Barang'),
(31, 'BA Serah Terima Hasil Pekerjaan'),
(32, 'Faktur Penjualan'),
(33, 'Kwitansi'),
(34, 'Permohonan Pembayaran'),
(35, 'Kwitansi Pembayaran Langsung');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_profil`
--

CREATE TABLE `tabel_profil` (
  `id_profil` int(11) NOT NULL,
  `profil` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tabel_profil`
--

INSERT INTO `tabel_profil` (`id_profil`, `profil`) VALUES
(1, 'Superadmin'),
(2, 'Kadisinfolahtal'),
(3, 'Sekdisinfolahtal'),
(4, 'Kabagren'),
(5, 'Kasubdis Bangsis'),
(6, 'Kasubdis Harsis'),
(7, 'Kasubdis Duknis'),
(8, 'Kasubdis LPSE');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_tender`
--

CREATE TABLE `tabel_tender` (
  `id_dokumen` int(10) NOT NULL,
  `dokumen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tabel_tender`
--

INSERT INTO `tabel_tender` (`id_dokumen`, `dokumen`) VALUES
(1, 'Sprin Pejabat Pembuat Komitmen'),
(2, 'Pakta Integritas Pejabat Pengadaannnn'),
(3, 'Spesifikasi Teknis'),
(4, 'Referensi HargaA'),
(5, 'HPS'),
(6, 'Gambar'),
(7, 'Uraian Singkat Pekerjaan'),
(8, 'Sprin Sebagai Pokja'),
(9, 'Syarat Kualifikasi'),
(10, 'Dokumen Kualifikasi Administrasi'),
(11, 'Dokumen Kualifikasi Teknis'),
(12, 'Dokumen Kualifikasi Harga'),
(13, 'BA Review Dokumen Pemilihan'),
(14, 'Jadwal Tenderr'),
(15, 'Pengumuman Tenderr'),
(16, 'BA Pemberian Penjelasan'),
(17, 'BA Pembukaan Penawaran'),
(18, 'BA Hasil Evaluasi'),
(19, 'BA Hasil Negosiasi'),
(20, 'BA Hasil Reverse Auction'),
(21, 'BA Penetapan Pemenang'),
(22, 'Pengumuman Pemenang'),
(23, 'BA Sanggah'),
(24, 'SPPBJ'),
(25, 'Kontrak'),
(26, 'Adendum Kontrak'),
(27, 'Sugrat Perintah Kerja'),
(28, 'Pakta Integritas Pejabat Penerima'),
(29, 'Surat Pengantar Barang/Surat Jalan'),
(30, 'Surat Tanda Penerimaan Barang'),
(31, 'BA Serah Terima Hasil Pekerjaan'),
(32, 'Faktur Penjualan'),
(33, 'Kwitansi'),
(34, 'Permohonan Pembayaran'),
(35, 'Kwitansi Pembayaran Langsung'),
(36, 'BA Pembayaran'),
(37, 'SPTJM'),
(38, 'SPP Satker'),
(39, 'KU-17');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_user`
--

CREATE TABLE `tabel_user` (
  `id_user` int(11) NOT NULL,
  `id_profil` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_user` varchar(128) NOT NULL,
  `telpon` varchar(50) NOT NULL,
  `aktif` varchar(3) NOT NULL,
  `foto` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tabel_user`
--

INSERT INTO `tabel_user` (`id_user`, `id_profil`, `username`, `password`, `nama_user`, `telpon`, `aktif`, `foto`) VALUES
(1, 1, 'superadmin', '$2y$10$6lNBtShRNVoj7V7JFXkpAul8zAmWf9McH3q8A356P5RTbSivD80Ta', 'Super Admin', '08114180521', 'Yes', 'b12c7977316d001f563b0aa3d17aef11.png'),
(2, 2, 'kadisinfolahtal', '$2y$10$OLdcbsJ7tbrmcFWkJArJG.zTLVk7FMi3mtjwef1mHXzg4BxTBmuzC', 'Kadisinfolahtal', '088888888888', 'Yes', ''),
(3, 3, 'sekdisinfolahtal', '$2y$10$93xthvML4OwWmkx3nfGaROc2ONf.irRB1yn4nB61h4.wG0TW9bBIG', 'Sekdisinfolahtal', '0123456789', 'Yes', ''),
(4, 4, 'kabagren', '$2y$10$5/jr/fTJsFHdcK91D90iteZk.5FpKz.HX7Xiv1Lajy8.HZKI.4bmy', 'Kabagren', '0123456789', 'Yes', ''),
(5, 5, 'kasubdisbangsis', '$2y$10$Kh4WxU6ur1afMyQNTtc6juEbRAfrQ6ZzmUWzWcVYeQdQltguxXOwO', 'Kasubdis Bangsis', '0123456789', 'Yes', ''),
(6, 6, 'kasubdisharsis', '$2y$10$S90fwnPRhbdrlYYj8gnYWuY6bkrXKLhwVci0FphHgdmQ8iyVhJx9e', 'Kasubdis Harsis', '0123456789', 'Yes', ''),
(7, 7, 'kasubdisduknis', '$2y$10$D1/y5hhCAAiqVP1ygY6DJeZlB5ulZTdpqbRbynKYDISNDCzqPtEO.', 'Kasubdis Duknis', '0123456789', 'Yes', ''),
(8, 8, 'kasubdislpse', '$2y$10$ntmunWDv09ugKldpdup1nuBH4HTrfrJ0eCvtzb8Jw8UWd39yGpEvS', 'Kasubdis LPSE', '0123456789', 'Yes', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbmenu`
--

CREATE TABLE `tbmenu` (
  `id` int(5) UNSIGNED NOT NULL,
  `user_level_id` int(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `url` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `urutan` int(5) NOT NULL,
  `aktif` int(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbmenu`
--

INSERT INTO `tbmenu` (`id`, `user_level_id`, `nama`, `url`, `icon`, `urutan`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 2, 'Profile', '/user/profile', 'fas fa-user', 1, 1, '2021-10-11 03:47:07', '2021-10-11 03:47:07'),
(2, 1, 'Kelola Menu', '/admin/manmenu', 'fas fa-edit', 2, 1, '2021-10-11 03:49:34', '2021-10-11 03:49:34'),
(3, 1, 'Dashboard', '/admin/dashboard', 'fas fa-th', 1, 1, '2021-10-11 05:46:37', '2022-01-16 07:00:24'),
(4, 3, 'Menu Ketua Pokja', '/ketuapokja/home', 'fas fa-chalkboard-teacher', 6, 1, '2021-10-11 05:46:37', '2024-12-14 04:38:07'),
(5, 1, 'Kelola User', '/admin/manuser', 'fas fa-users', 4, 1, '0000-00-00 00:00:00', '2024-12-13 07:43:24'),
(6, 4, 'Menu Pokja', '/pokja/home', 'fas fa-chalkboard-teacher', 8, 1, '0000-00-00 00:00:00', '2025-01-05 21:46:37'),
(7, 2, 'Menu PPK', '/ppk/home', 'fas fa-chalkboard-teacher', 5, 1, '0000-00-00 00:00:00', '2025-01-05 21:50:25'),
(10, 1, 'Reset Password', '/admin/manuser/hal_resset_psswrd', 'fas fa-unlock-alt', 2, 1, '0000-00-00 00:00:00', '2022-01-05 18:13:47'),
(11, 1, 'Master Level', '/admin/manlevel', 'fas fa-list', 3, 1, '0000-00-00 00:00:00', '2022-01-05 18:14:15'),
(17, 10, 'Menu Silver', '/user/silver', 'fas fa-chalkboard-teacher', 10, 1, '0000-00-00 00:00:00', '2024-12-14 04:33:43'),
(20, 6, 'Menu LPSE', '/lpse/home', 'fas fa-chalkboard-teacher', 9, 1, '0000-00-00 00:00:00', '2024-12-17 19:41:00'),
(22, 2, 'Dashboard PPK', '/ppk/dashboardppk', 'fas fa-th', 1, 1, '2024-12-14 09:17:52', '2025-01-05 21:50:12'),
(23, 3, 'Dashboard Ketua Pokja', '/ketuapokja/dashboardketuapokja', 'fas fa-th', 2, 1, '2024-12-14 09:29:21', '2024-12-14 09:31:14'),
(24, 4, 'Dashboard Pokja', '/pokja/dashboardpokja', 'fas fa-th', 3, 1, '2024-12-14 09:29:58', '2025-01-05 21:47:20'),
(25, 2, 'DIPA', '/ppk/dipa', 'fas fa-book', 6, 1, '2024-12-14 10:08:08', '2024-12-17 04:20:44'),
(26, 6, 'Template Pengadaan', '/lpse/templatedokumen', 'fas fa-layer-group', 7, 1, '2024-12-17 04:23:04', '2024-12-17 19:45:28'),
(27, 6, 'Dashboard LPSE', '/lpse/dashboardlpse', 'fas fa-th', 11, 1, '2024-12-17 19:42:09', '2024-12-17 19:42:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbuser`
--

CREATE TABLE `tbuser` (
  `id` int(5) UNSIGNED NOT NULL,
  `photo` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level_user` int(5) UNSIGNED NOT NULL,
  `status` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbuser`
--

INSERT INTO `tbuser` (`id`, `photo`, `nama`, `username`, `password`, `level_user`, `status`, `created_at`, `updated_at`) VALUES
(3, 'avatar.png', 'Admin', 'admin', '$2y$10$sh3cNNtYlWd4L1nsQTuvxeX31YSNUuzgF6Sw5NnSa32uOJavSxETG', 1, 'Aktif', '2021-10-11 11:20:21', '0000-00-00 00:00:00'),
(41, '1734188967_91cecde72c64e9dd4374.png', 'Kadisinfolahtal', 'kadisinfolahtal', '$2y$10$By4LPuB3aMmQ3S0zLVv6f.DGbSWUaAHZxFR1Pc8/tx3lD7EU0gQV6', 2, 'Aktif', '2024-12-13 09:22:08', '2024-12-14 09:09:27'),
(42, 'avatar.png', 'Sekdisinfolahtal', 'sekdisinfolahtal', '$2y$10$/Jqv5YC4CKELDMHTwgyyjOlLmVLWGp50PY1YDGoi0tmMdTN5p.uCu', 3, 'Aktif', '2024-12-13 09:24:19', '2024-12-13 09:24:36'),
(43, '1734188980_8a45ce9d0fff90a6e38d.jpeg', 'Kasubdisbangsis', 'kasubdisbangsis', '$2y$10$zX5rE28A2U3zDdA8FwO8Qer2608KgZLYhH61nTtHcakwKYBTuFsZq', 4, 'Aktif', '2024-12-13 19:59:50', '2024-12-14 09:09:40'),
(44, '1734173164_9eb9b579da2b5ddd01f3.png', 'Ryan', 'ryan', '$2y$10$0WdaOBz7P0o1extBPnsszO87fp30bMwckoIdq0GXOVw9iwG4FO0dm', 6, 'Aktif', '2024-12-14 04:46:04', '2024-12-14 04:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `tbuser_level`
--

CREATE TABLE `tbuser_level` (
  `id` int(5) UNSIGNED NOT NULL,
  `nama_level` varchar(50) NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbuser_level`
--

INSERT INTO `tbuser_level` (`id`, `nama_level`, `keterangan`) VALUES
(1, 'Admin', 'Punya akses penuh'),
(2, 'PPK', 'Punya akses level ppk, ketua pokja dan pokja'),
(3, 'Ketua Pokja', 'Punya akses level ketua pokja dan pokja'),
(4, 'Pokja', 'Hanya punya akses level pokja'),
(6, 'LPSE', 'Hanya punya akses level LPSE'),
(10, 'Silver', 'Hanya punya akses level Silver'),
(11, 'Basic', 'Hanya punya akses level Basic');

-- --------------------------------------------------------

--
-- Table structure for table `tbuser_level_akses`
--

CREATE TABLE `tbuser_level_akses` (
  `id` int(5) UNSIGNED NOT NULL,
  `user_level_id` int(5) UNSIGNED NOT NULL,
  `menu_akses_id` int(5) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbuser_level_akses`
--

INSERT INTO `tbuser_level_akses` (`id`, `user_level_id`, `menu_akses_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2021-10-09 18:32:31', '2021-10-09 18:32:31'),
(2, 1, 2, '2021-10-09 18:32:31', '2021-10-09 18:32:31'),
(3, 1, 3, '2021-10-09 18:33:29', '2021-10-09 18:33:29'),
(4, 1, 4, '2021-10-09 18:33:29', '2021-10-09 18:33:29'),
(5, 4, 4, '2021-10-09 18:34:17', '2021-10-09 18:34:17'),
(6, 3, 3, '2021-10-09 18:34:17', '2021-10-09 18:34:17'),
(7, 3, 2, '2021-10-09 18:34:52', '2021-10-09 18:34:52'),
(8, 2, 4, '2021-10-09 18:34:52', '2021-10-09 18:34:52'),
(9, 2, 3, '2021-10-09 18:35:16', '2021-10-09 18:35:16'),
(10, 2, 2, '2021-10-09 18:35:16', '2021-10-09 18:35:16'),
(11, 6, 6, '2022-01-02 13:29:03', '2022-01-02 13:29:03'),
(12, 10, 10, '2022-01-02 13:29:03', '2022-01-02 13:29:03'),
(13, 11, 11, '2022-01-02 13:29:28', '2022-01-02 13:29:28'),
(14, 1, 6, '2022-01-02 13:34:38', '2022-01-02 13:34:38'),
(15, 1, 10, '2022-01-02 13:34:38', '2022-01-02 13:34:38'),
(16, 1, 11, '2022-01-02 13:35:01', '2022-01-02 13:35:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_cara_pbj`
--
ALTER TABLE `ref_cara_pbj`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_cara_swakelola`
--
ALTER TABLE `ref_cara_swakelola`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_divisi`
--
ALTER TABLE `ref_divisi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_jenis_pbj`
--
ALTER TABLE `ref_jenis_pbj`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_kontrak`
--
ALTER TABLE `ref_kontrak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_metode_pemilihan`
--
ALTER TABLE `ref_metode_pemilihan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_tipe_swakelola`
--
ALTER TABLE `ref_tipe_swakelola`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tabel_akses`
--
ALTER TABLE `tabel_akses`
  ADD PRIMARY KEY (`id_akses`);

--
-- Indexes for table `tabel_config`
--
ALTER TABLE `tabel_config`
  ADD PRIMARY KEY (`id_config`);

--
-- Indexes for table `tabel_ep`
--
ALTER TABLE `tabel_ep`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indexes for table `tabel_file`
--
ALTER TABLE `tabel_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tabel_icon`
--
ALTER TABLE `tabel_icon`
  ADD PRIMARY KEY (`id_icon`),
  ADD UNIQUE KEY `icon` (`icon`);

--
-- Indexes for table `tabel_navigasi`
--
ALTER TABLE `tabel_navigasi`
  ADD PRIMARY KEY (`id_navigasi`),
  ADD KEY `id_icon` (`icon`);

--
-- Indexes for table `tabel_pengadaan`
--
ALTER TABLE `tabel_pengadaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tabel_pl`
--
ALTER TABLE `tabel_pl`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indexes for table `tabel_profil`
--
ALTER TABLE `tabel_profil`
  ADD PRIMARY KEY (`id_profil`);

--
-- Indexes for table `tabel_tender`
--
ALTER TABLE `tabel_tender`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indexes for table `tabel_user`
--
ALTER TABLE `tabel_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_profil` (`id_profil`);

--
-- Indexes for table `tbmenu`
--
ALTER TABLE `tbmenu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbuser`
--
ALTER TABLE `tbuser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbuser_level`
--
ALTER TABLE `tbuser_level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbuser_level_akses`
--
ALTER TABLE `tbuser_level_akses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ref_cara_pbj`
--
ALTER TABLE `ref_cara_pbj`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ref_cara_swakelola`
--
ALTER TABLE `ref_cara_swakelola`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ref_divisi`
--
ALTER TABLE `ref_divisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ref_jenis_pbj`
--
ALTER TABLE `ref_jenis_pbj`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ref_kontrak`
--
ALTER TABLE `ref_kontrak`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ref_metode_pemilihan`
--
ALTER TABLE `ref_metode_pemilihan`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ref_tipe_swakelola`
--
ALTER TABLE `ref_tipe_swakelola`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tabel_akses`
--
ALTER TABLE `tabel_akses`
  MODIFY `id_akses` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `tabel_config`
--
ALTER TABLE `tabel_config`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tabel_ep`
--
ALTER TABLE `tabel_ep`
  MODIFY `id_dokumen` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tabel_file`
--
ALTER TABLE `tabel_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tabel_icon`
--
ALTER TABLE `tabel_icon`
  MODIFY `id_icon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=353;

--
-- AUTO_INCREMENT for table `tabel_navigasi`
--
ALTER TABLE `tabel_navigasi`
  MODIFY `id_navigasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tabel_pengadaan`
--
ALTER TABLE `tabel_pengadaan`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `tabel_pl`
--
ALTER TABLE `tabel_pl`
  MODIFY `id_dokumen` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tabel_profil`
--
ALTER TABLE `tabel_profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tabel_tender`
--
ALTER TABLE `tabel_tender`
  MODIFY `id_dokumen` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tabel_user`
--
ALTER TABLE `tabel_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbmenu`
--
ALTER TABLE `tbmenu`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbuser`
--
ALTER TABLE `tbuser`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbuser_level`
--
ALTER TABLE `tbuser_level`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbuser_level_akses`
--
ALTER TABLE `tbuser_level_akses`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tabel_user`
--
ALTER TABLE `tabel_user`
  ADD CONSTRAINT `tabel_user_ibfk_1` FOREIGN KEY (`id_profil`) REFERENCES `tabel_profil` (`id_profil`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
