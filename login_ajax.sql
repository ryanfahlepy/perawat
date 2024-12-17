-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2022 at 02:27 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login_ajax`
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
(3, 1, 'Dashboard', '/admin/dasboard', 'fas fa-th', 1, 1, '2021-10-11 05:46:37', '2022-01-16 07:00:24'),
(4, 3, 'Menu Supervisor', '/supervisor/home', 'fas fa-atom', 2, 1, '2021-10-11 05:46:37', '2021-10-11 05:46:37'),
(5, 1, 'Kelola User', '/admin/manuser', 'fas fa-users', 4, 1, '0000-00-00 00:00:00', '2022-01-05 18:12:32'),
(6, 2, 'Menu Employee', '/employee/home', 'fas fa-chart-line', 3, 1, '0000-00-00 00:00:00', '2022-01-05 18:12:53'),
(7, 4, 'Menu Manager', '/manager/home', 'fas fa-chalkboard-teacher', 1, 1, '0000-00-00 00:00:00', '2022-01-05 18:13:31'),
(10, 2, 'Reset Password', '/admin/manuser/hal_resset_psswrd', 'fas fa-unlock-alt', 2, 1, '0000-00-00 00:00:00', '2022-01-05 18:13:47'),
(11, 1, 'Master Level', '/admin/manlevel', 'fas fa-th', 3, 1, '0000-00-00 00:00:00', '2022-01-05 18:14:15'),
(17, 10, 'Menu Silver', '/user/silver', 'fas fa-th', 1, 0, '0000-00-00 00:00:00', '2022-01-16 07:21:42'),
(19, 4, 'Menu Manager Umum', '/manager/umum', 'fas fa-chalkboard-teacher', 1, 0, '0000-00-00 00:00:00', '2022-01-16 07:21:36'),
(20, 6, 'Menu Gold', '/user/gold', 'fas fa-th', 1, 0, '0000-00-00 00:00:00', '2022-01-05 18:15:22');

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
(1, '1634699752_5bd5d5ab1cd9f98ad81d.png', 'Windarto', 'windarto@yahoo.com', '$2y$10$Wu5z3xXDSYkdExdfo3leK.UBenxmmWGaKrMdOyINYjltkEMZj/sE6', 3, 'Aktif', '2021-10-11 05:34:20', '2022-01-16 07:09:08'),
(2, '1634700242_8950da3412edb03f9e74.png', 'Sugeng Widodo', 'sugeng@gmail.com', '$2y$10$5tJr9HaBbz3gzZITwjBwLOGWFk9U5lUWbqY1FxNcz2j4upQe.NXpa', 3, 'Aktif', '0000-00-00 00:00:00', '2022-01-05 18:02:31'),
(3, 'avatar.png', 'Admin', 'admin', '$2y$10$sh3cNNtYlWd4L1nsQTuvxeX31YSNUuzgF6Sw5NnSa32uOJavSxETG', 1, 'Aktif', '2021-10-11 11:20:21', '0000-00-00 00:00:00'),
(4, '1634471005_5cb52b803d3008d869be.png', 'Wicaksono', 'wicaksono@gmail.com', '$2y$10$YNJ4CMQmWCWq35nfYMK13OWCeGbfVTqfPdyBcv8Oxyx0PO39Xp.EO', 4, 'Aktif', '0000-00-00 00:00:00', '2022-01-05 18:02:44'),
(5, '1634471107_d6d8433a4963a3edb9c1.png', 'Efendi', 'efendi@hotmail.com', '$2y$10$NwIpcNPgLCOJ0W9zAtLWVOnlqDTIjTtLJooL4O4b6POsEWD2yvhre', 2, 'Aktif', '0000-00-00 00:00:00', '2022-01-05 18:02:58'),
(6, '1634484324_096e5919e5653b08e41d.png', 'Agus Waluyo', 'aguswaluyo@gmail.com', '$2y$10$WOXTHi3nrQq0TthZdVViOO5/.Er5RGAczP.NK0DyVQbGlclmYXMh.', 2, 'Nonaktif', '0000-00-00 00:00:00', '2022-01-05 18:03:15'),
(7, '1634699706_44fb7c4f7b8cfd270c26.png', 'Budi Nugroho', 'budi@gmail.com', '$2y$10$kT7fEf4XBfRFuQth4B4Y9.nNW8bSFv7EcIdyu9wSBnRFA4Id2K2QS', 4, 'Nonaktif', '0000-00-00 00:00:00', '2022-01-05 18:03:30'),
(14, '1640499815_46cc316b98febc38d964.png', 'Hadi Permana', 'hadipermana@gmail.com', '$2y$10$froM0aIrzSApK6kfmud/oeNXDk3zm2bF717CqeTKzUe8mQ28NZEIq', 3, 'Aktif', '0000-00-00 00:00:00', '2022-01-05 18:03:41'),
(23, 'avatar.png', 'Sumarsari Catur', 'catur@gmail.com', '$2y$10$YWJLMp1RDHK13C1NUalJg.4Sc85aytWnojfwEGdPW29kYOKYiJxS.', 11, 'Nonaktif', '0000-00-00 00:00:00', '2022-01-05 18:03:56'),
(24, 'avatar.png', 'Suyono', 'suyono@gmail.com', '$2y$10$rsXvEFYgajP4sjcpePb68uiJdd7BDmBNuU.xqJsc.Mt.rN7b8fDHK', 11, 'Nonaktif', '0000-00-00 00:00:00', '2022-01-05 18:04:10'),
(25, 'avatar.png', 'Chamim', 'chamim@yahoo.com', '$2y$10$h4VsEIXbFC8ftOct6O7SnOYCZS1lfKIvA9Kgu8sDHOThzeVfnVl/q', 11, 'Nonaktif', '0000-00-00 00:00:00', '2022-01-05 18:04:34'),
(26, 'avatar.png', 'Riski Wijaya', 'riski@gmail.com', '$2y$10$SH9YmBG1F68Ce0DJ4UQDiuAPFZDlrAxRGx06xajBGFb6Fhr8KK6Y.', 11, 'Nonaktif', '0000-00-00 00:00:00', '2022-01-05 18:04:59'),
(27, 'avatar.png', 'Handoko', 'handoko@yahoo.com', '$2y$10$cYC525lAKyiRNeFVD.zjyOlinYc8pg2FEilfePPNzD3r/UNX4YYj2', 11, 'Nonaktif', '0000-00-00 00:00:00', '2022-01-05 18:05:19'),
(28, 'avatar.png', 'Agung Purwanto', 'agung@gmail.com', '$2y$10$HpjCHgW4..uHkKfXkW9Jcu6//ZGb5ZB0GRjFzHyRxrdxTgLZ5VfjS', 11, 'Nonaktif', '0000-00-00 00:00:00', '2022-01-05 18:05:37'),
(31, '1641090477_9d2c62fb27bc62638157.png', 'Edi Winarko', 'edi@gmail.com', '$2y$10$0pIAOgAHqPsklMehz/61T.DYCvxxjqShcKrYARQWsLULWXrkWDKu.', 11, 'Aktif', '0000-00-00 00:00:00', '2022-01-05 18:05:57'),
(32, '1641107560_ea3aa06178687606aae7.png', 'Dyah Herawati', 'diah@gmail.com', '$2y$10$OHGfUufRTsNbPQmDhXGKWOeCMz3R4L7pA9.wUn/3hl9H.zXyDvnSS', 6, 'Aktif', '0000-00-00 00:00:00', '2022-01-05 18:06:13'),
(34, 'avatar.png', 'Arif Afandi', 'arif@gmail.com', '$2y$10$7VZavmO5j0O8gK1nF0Yt0ewNBZztPwJfq3.Ik8d./0CHzZuSYnOTy', 2, 'Aktif', '0000-00-00 00:00:00', '2022-01-05 18:06:28'),
(35, 'avatar.png', 'Berlian Santoso', 'berlian@gmail.com', '$2y$10$mZWKB5mp3HOPu77QQrUCHOn5PorBAmVmDLZa00Vuw1j/fFhqmA.Aq', 11, 'Aktif', '0000-00-00 00:00:00', '2022-01-05 18:06:49'),
(36, 'avatar.png', 'Sapto Wiyono', 'wiyono@gmail.com', '$2y$10$VzUweiWmS587vKP2c1cOa.mCPuCbcUcaJ2i0vivsekZh5fCrv4r6q', 10, 'Nonaktif', '0000-00-00 00:00:00', '2022-01-05 18:07:06'),
(37, 'avatar.png', 'Wahyu Fani Sejahtera', 'wahyu_fani@gmail.com', '$2y$10$BEmVWWNJZKcP49wCnwAoQ.mw.QUOZiR27rc5ERAHfAXfJGXiSPNsG', 10, 'Nonaktif', '0000-00-00 00:00:00', '2022-01-05 18:00:55'),
(38, '1642338773_41557f3b2e224b6cfcb3.png', 'Agung Widodo', 'agungw@gmail.com', '$2y$10$Kne1ueNfZEod9ywipDRL..OYMaTv2Dvl87jMwWmaIpwgvsHdjzIGu', 10, 'Nonaktif', '2022-01-16 07:12:53', '2022-01-16 07:14:09');

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
(2, 'Employee', 'Hanya punya akses level employe'),
(3, 'Supervisor', 'Punya akses level supervisor dan employee '),
(4, 'Manager', 'Punya akses level manager, supervisor dan employee'),
(6, 'Gold', 'Hanya punya akses level Gold'),
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
(5, 2, 2, '2021-10-09 18:34:17', '2021-10-09 18:34:17'),
(6, 3, 3, '2021-10-09 18:34:17', '2021-10-09 18:34:17'),
(7, 3, 2, '2021-10-09 18:34:52', '2021-10-09 18:34:52'),
(8, 4, 4, '2021-10-09 18:34:52', '2021-10-09 18:34:52'),
(9, 4, 3, '2021-10-09 18:35:16', '2021-10-09 18:35:16'),
(10, 4, 2, '2021-10-09 18:35:16', '2021-10-09 18:35:16'),
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
-- AUTO_INCREMENT for table `tbmenu`
--
ALTER TABLE `tbmenu`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbuser`
--
ALTER TABLE `tbuser`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
