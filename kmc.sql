-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 16, 2026 at 02:36 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kmc`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_classifications`
--

CREATE TABLE `ai_classifications` (
  `id` bigint UNSIGNED NOT NULL,
  `notification_id` bigint UNSIGNED NOT NULL,
  `suggested_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suggested_sub_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suggested_opds` json DEFAULT NULL,
  `priority` enum('Rendah','Sedang','Tinggi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `confidence` decimal(5,2) NOT NULL,
  `reasoning` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ai_classifications`
--

INSERT INTO `ai_classifications` (`id`, `notification_id`, `suggested_category`, `suggested_sub_category`, `suggested_opds`, `priority`, `confidence`, `reasoning`, `created_at`, `updated_at`) VALUES
(1, 1, 'Layanan PLN', 'Listrik', '[\"PLN\"]', 'Rendah', 92.00, 'Keluhan mati lampu menunjukkan masalah listrik.', '2026-06-23 10:27:05', '2026-06-23 10:27:05'),
(2, 2, 'Layanan PLN', 'Listrik', '[\"PLN\"]', 'Rendah', 95.00, 'Kabel menjuntai di jalan menimbulkan bahaya keselamatan.', '2026-06-23 10:27:18', '2026-06-23 10:27:18'),
(3, 3, 'Layanan PLN', 'Listrik', '[\"PLN\"]', 'Rendah', 96.00, 'Listrik padam selama 3 hari menimbulkan bahaya dan belum ditangani.', '2026-06-23 10:27:32', '2026-06-23 10:27:32'),
(4, 4, 'Layanan PLN', 'Listrik', '[\"PLN\"]', 'Rendah', 96.00, 'Laporan padam listrik di jalan Awan', '2026-06-23 10:27:47', '2026-06-23 10:27:47'),
(5, 5, 'Infrastruktur dan Pekerjaan Umum', 'Lampu Jalan', '[\"Dinas Perhubungan\"]', 'Tinggi', 96.00, 'Lampu merah mati membahayakan pengguna jalan', '2026-06-23 10:28:12', '2026-06-23 10:28:12'),
(6, 6, 'Lingkungan Hidup dan Kehutanan', 'Pohon', '[\"BPBD\"]', 'Tinggi', 96.00, 'Pohon tumbang menghalangi jalan dan berpotensi bahaya keselamatan.', '2026-06-23 10:28:56', '2026-06-23 10:28:56'),
(7, 7, 'Layanan PLN', 'Listrik', '[\"PLN\"]', 'Rendah', 0.00, 'AI gagal menghasilkan klasifikasi yang valid atau sedang terjadi gangguan sistem.', '2026-06-23 10:33:21', '2026-06-23 10:33:21'),
(11, 11, 'Layanan PLN', 'Listrik', '[\"PLN\"]', 'Rendah', 96.00, 'Listrik padam selama 3 hari di area publik', '2026-06-24 04:20:08', '2026-06-24 04:20:08'),
(12, 12, 'Layanan PDAM', 'Air Bersih', '[\"PDAM Ketapang\"]', 'Sedang', 96.00, 'Air PDAM tidak mengalir selama beberapa hari.', '2026-06-24 04:20:17', '2026-06-24 04:20:17'),
(13, 13, 'Lingkungan Hidup dan Kehutanan', 'Sampah', '[\"Dinas Lingkungan Hidup\"]', 'Rendah', 92.00, 'Laporan bau menyengat biasanya terkait sampah atau limbah di sekitar rumah.', '2026-06-24 04:27:56', '2026-06-24 04:27:56'),
(14, 14, 'Lingkungan Hidup dan Kehutanan', 'Pohon', '[\"BPBD\"]', 'Rendah', 94.00, 'Pohon menghalangi jalan di sekitar rumah.', '2026-06-24 04:53:03', '2026-06-24 04:53:03'),
(16, 16, 'Infrastruktur dan Pekerjaan Umum', 'Lampu Jalan', '[\"Dinas Perhubungan\"]', 'Rendah', 92.00, 'Lampu merah (lampu lalu lintas) tidak berfungsi', '2026-06-24 05:13:49', '2026-06-24 05:13:49'),
(42, 62, 'Layanan PDAM', 'Air Bersih', '[\"PDAM Ketapang\"]', 'Sedang', 98.00, 'Keluhan air PDAM tidak mengalir selama dua hari di wilayah Agus Salim.', '2026-07-05 12:00:39', '2026-07-05 12:00:39'),
(44, 64, 'Layanan PDAM', 'Air Bersih', '[\"PDAM Ketapang\"]', 'Sedang', 98.00, 'Keluhan air tidak mengalir selama 3 hari di kawasan Jalan Agus Salim yang menyulitkan warga.', '2026-07-06 14:41:05', '2026-07-06 14:41:05'),
(54, 77, 'Layanan PDAM', 'Air Bersih', '[\"PDAM Ketapang\"]', 'Sedang', 98.00, 'Keluhan air PDAM tidak mengalir selama dua hari di wilayah Agus Salim.', '2026-07-08 12:46:21', '2026-07-08 12:46:21'),
(55, 78, 'Layanan PLN', 'Listrik', '[\"PLN\"]', 'Rendah', 98.00, 'Keluhan mengenai listrik padam dan tidak stabil (mati hidup) di Jalan Agus Salim yang melibatkan PLN.', '2026-07-08 18:21:16', '2026-07-08 18:21:16');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Administrasi Kependudukan', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(2, 'Perizinan dan Investasi', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(3, 'Infrastruktur dan Pekerjaan Umum', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(4, 'Pendidikan', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(5, 'Kesehatan', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(6, 'Sosial dan Kesejahteraan Masyarakat', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(7, 'Kepegawaian / SDM Aparatur', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(8, 'Keuangan dan Pajak Daerah', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(9, 'Pertanian, Perikanan, dan Peternakan', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(10, 'Perdagangan, UMKM, dan Koperasi', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(11, 'Komunikasi dan Informatika', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(12, 'Pariwisata, Kebudayaan, dan Olahraga', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(13, 'Lingkungan Hidup dan Kehutanan', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(14, 'Ketentraman dan Ketertiban Umum', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(15, 'Hukum dan Perundang-undangan', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(16, 'Bencana dan Penanggulangan Darurat', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(17, 'Pengaduan Pelayanan Publik', '2026-06-22 21:03:35', '2026-06-22 21:03:35'),
(18, 'Lain-lain / Umum', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(19, 'Layanan PDAM', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(20, 'Layanan PLN', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(21, 'Pertanyaan', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(22, 'Bank Kalbar', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(23, 'Perumahan', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(24, 'Fasilitas Umum', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(25, 'Pertanahan', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(26, 'Makan Bergizi Gratis', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(27, 'Administrasi', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(28, 'Sengketa Tanah', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(29, 'Perumahan dan Lingkungan Hidup', '2026-07-04 23:30:19', '2026-07-04 23:30:19'),
(30, 'Laporan', '2026-07-04 23:30:19', '2026-07-04 23:30:19');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint UNSIGNED NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint UNSIGNED DEFAULT NULL,
  `opd_id` bigint UNSIGNED DEFAULT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confidence` int DEFAULT NULL,
  `ai_processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_comment_mentions`
--

CREATE TABLE `facebook_comment_mentions` (
  `id` bigint UNSIGNED NOT NULL,
  `notification_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_message` text COLLATE utf8mb4_unicode_ci,
  `comment_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facebook_comment_mentions`
--

INSERT INTO `facebook_comment_mentions` (`id`, `notification_text`, `comment_message`, `comment_link`, `comment_id`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'MNZ Gaming mentioned you in a comment.\n1minggu', 'Simadu KMC kabel menjuntai 12.43', 'https://web.facebook.com/reel/1299131388910742/?comment_id=1585543499572060', '1585543499572060', 0, '2026-06-23 03:32:38', '2026-06-23 03:32:38'),
(2, 'Achmad Bagus Aprianto mentioned you in a comment.\n1minggu', 'Simadu KMC tes posting 22.24', 'https://web.facebook.com/reel/1550872906492296/?comment_id=4561488577413838', '4561488577413838', 0, '2026-06-23 03:33:22', '2026-06-23 03:33:22'),
(3, '', 'Simadu KMC min di jalan agus salim mati lampu tros am th ngape pln nin kontan mati idup', 'https://web.facebook.com/TempoMedia/posts/pfbid02tJnmHePhrV4RLbemsmxW8p2WFKs4vhfCUotGX9KAyGA8i9728ubPnm8eWV8PxdTal?comment_id=878531741517957', '878531741517957', 0, '2026-07-08 18:20:32', '2026-07-08 18:20:32');

-- --------------------------------------------------------

--
-- Table structure for table `facebook_post_mentions`
--

CREATE TABLE `facebook_post_mentions` (
  `id` bigint UNSIGNED NOT NULL,
  `post_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_message` text COLLATE utf8mb4_unicode_ci,
  `sender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facebook_post_mentions`
--

INSERT INTO `facebook_post_mentions` (`id`, `post_link`, `notification_text`, `post_message`, `sender`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-36748caf39f7', 'MenthaZ Gaming menyebut Anda dalam postingan.', 'Simadu KMC MATI LAMPU MIN', 'MenthaZ Gaming', 0, '2026-06-23 03:26:33', '2026-06-23 03:26:33'),
(2, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-1d674616c0b1', 'MenthaZ Gaming menyebut Anda dalam postingan.', 'Simadu KMC kebakaran 12.42', 'MenthaZ Gaming', 0, '2026-06-23 03:27:07', '2026-06-23 03:27:07'),
(3, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-332b70fd9840', 'MNZ Gaming menyebut Anda dalam postingan.', 'Simadu KMC kabel menjuntai di jalan', 'MNZ Gaming', 0, '2026-06-23 03:27:07', '2026-06-23 03:27:07'),
(4, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-5ea1fbe548f7', 'Achmad Bagus Aprianto menyebut Anda dalam postingan.', 'tes post 14.31 Simadu KMC', 'Achmad Bagus Aprianto', 0, '2026-06-23 03:27:19', '2026-06-23 03:27:19'),
(5, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-d1b255dcf430', 'MenthaZ Gaming menyebut Anda dalam postingan.', 'padam listrik min di jalan merdeka dah 3 hari Simadu KMC', 'MenthaZ Gaming', 0, '2026-06-23 03:27:19', '2026-06-23 03:27:19'),
(6, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-a68b2f0c05b5', 'MNZ Gaming menyebut Anda dalam postingan.', 'Simadu KMC padam listrik di gg awan', 'MNZ Gaming', 0, '2026-06-23 03:27:34', '2026-06-23 03:27:34'),
(7, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-df56e145f4ba', 'Achmad Bagus Aprianto menyebut Anda dalam postingan.', 'Lampu merah sering mati, sangat membahayakan pengguna jalan. Mohon segera diperbaiki. Simadu KMC', 'Achmad Bagus Aprianto', 0, '2026-06-23 03:27:48', '2026-06-23 03:27:48'),
(8, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-218f538d732e', 'MenthaZ Gaming menyebut Anda dalam postingan.', 'Simadu KMC min ade pohon tumbang di jalan suprapto', 'MenthaZ Gaming', 0, '2026-06-23 03:28:13', '2026-06-23 03:28:13'),
(35, 'https://web.facebook.com/permalink.php?story_fbid=pfbid0KWZsKrm1b81qAM3AxBqWrh6nfAxqCBmTN4S44VovTdfEcMXU35FSCZubTUvCQLppl&id=100072209896233&notif_id=1782317516349920&notif_t=mention&ref=notif#post-0126b20001e9', 'MNZ Gaming menyebut Anda dalam postingan.', 'Simadu KMC min aik pdam dak jalan dah 2 hari di agus salim', 'MNZ Gaming', 0, '2026-07-05 12:00:10', '2026-07-05 12:00:10'),
(36, 'https://web.facebook.com/permalink.php?story_fbid=pfbid02PBraDPR6cr5XkhuNWhWBvrCG2VsguorhhVhCDmzvED8eU3kxd7GHMNH4gz7WNPEel&id=100072209896233&notif_id=1782317516349920&notif_t=mention&ref=notif#post-0126b20001e9', 'MNZ Gaming menyebut Anda dalam postingan.', 'Simadu KMC min aik pdam dak jalan dah 2 hari di agus salim', 'MNZ Gaming', 0, '2026-07-08 12:45:37', '2026-07-08 12:45:37');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_mentions`
--

CREATE TABLE `instagram_mentions` (
  `id` bigint UNSIGNED NOT NULL,
  `sender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notification_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_message` text COLLATE utf8mb4_unicode_ci,
  `post_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instagram_mentions`
--

INSERT INTO `instagram_mentions` (`id`, `sender`, `notification_text`, `post_message`, `post_link`, `message_type`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'Wolfrene', 'Pesan DM dari Wolfrene (Tab: General/Umum)', 'min ada bau menyengat dri belakang rumah saye', 'https://www.instagram.com/direct/t/17849415041802396', 'dm', 0, '2026-06-23 11:39:45', '2026-06-23 11:39:45'),
(2, 'Ryan', 'Pesan DM dari Ryan (Tab: General/Umum)', 'min mati lampu dah 3 hari di jalan merdeka', 'https://www.instagram.com/direct/t/106654980737016', 'dm', 0, '2026-06-23 11:40:05', '2026-06-23 11:40:05'),
(3, 'Bguss', 'Pesan DM dari Bguss (Tab: General/Umum)', 'min aik pdam di btn tanjung pura dah 3 hari dak jaln', 'https://www.instagram.com/direct/t/109260797134227', 'dm', 0, '2026-06-23 11:40:19', '2026-06-23 11:40:19'),
(5, 'Ryan', 'Pesan DM dari Ryan (Tab: General/Umum)', 'min mati lampu dah 3 hari di jalan merdeka', 'https://www.instagram.com/direct/t/106654980737016#c31b1b4ad17166d421d80535503a1626', 'dm', 0, '2026-06-23 21:19:57', '2026-06-23 21:19:57'),
(6, 'Bguss', 'Pesan DM dari Bguss (Tab: General/Umum)', 'min aik pdam di btn tanjung pura dah 3 hari dak jaln', 'https://www.instagram.com/direct/t/109260797134227#266ff727eb9e76c0fa9945d0064224ef', 'dm', 0, '2026-06-23 21:20:08', '2026-06-23 21:20:08'),
(7, 'Wolfrene', 'Pesan DM dari Wolfrene (Tab: General/Umum)', 'min ada bau menyengat dri belakang rumah saye', 'https://www.instagram.com/direct/t/17849415041802396#b9e5d2f263165c5a47a43f2824e1cf02', 'dm', 0, '2026-06-23 21:27:42', '2026-06-23 21:27:42'),
(8, 'Ryan', 'Pesan DM dari Ryan (Tab: General/Umum)', 'baik akan segera di tindaklanjuti', 'https://www.instagram.com/direct/t/106654980737016#16b952a99ce9daeea538ddd574b8c8df', 'dm', 0, '2026-06-23 21:27:56', '2026-06-23 21:27:56'),
(9, 'Bguss', 'Pesan DM dari Bguss (Tab: General/Umum)', 'akan segera di proses', 'https://www.instagram.com/direct/t/109260797134227#1e9de3383a432dc547a65257c6b3e4c6', 'dm', 0, '2026-06-23 21:28:02', '2026-06-23 21:28:02'),
(10, 'Wolfrene', 'Pesan DM dari Wolfrene (Tab: General/Umum)', 'min ade pokok ngadang jalan, di samping rumah saye', 'https://www.instagram.com/direct/t/17849415041802396#793aadbe78d7e6dd4a6416be53d86340', 'dm', 0, '2026-06-23 21:52:53', '2026-06-23 21:52:53'),
(12, 'Bguss', 'Pesan DM dari Bguss (Tab: General/Umum)', 'min lampu merah di jalan mt haryono nin kontan mati jak am, tolong di benarkan', 'https://www.instagram.com/direct/t/109260797134227#a2e59d98bc0c449c64e2079555e0b511', 'dm', 0, '2026-06-23 22:13:37', '2026-06-23 22:13:37');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_01_072809_create_complaints_table', 1),
(5, '2026_06_01_073417_create_notifications_table', 1),
(6, '2026_06_05_063846_create_messages_table', 1),
(7, '2026_06_08_043437_create_facebook_mentions_table', 1),
(8, '2026_06_13_025753_create_facebook_comment_mentions_table', 1),
(9, '2026_06_13_041947_add_comment_message_to_facebook_comment_mentions_table', 1),
(10, '2026_06_14_033119_create_facebook_post_mentions_table', 1),
(11, '2026_06_14_063209_add_permalink_to_notifications_table', 1),
(12, '2026_06_14_074914_add_sender_to_notifications_table', 1),
(13, '2026_06_14_110522_create_opds_table', 1),
(14, '2026_06_14_110542_create_categories_table', 1),
(15, '2026_06_14_110557_create_sub_categories_table', 1),
(16, '2026_06_14_110753_add_ai_columns_to_complaints_table', 1),
(17, '2026_06_14_123840_create_a_i_classifications_table', 1),
(18, '2026_06_14_145629_modify_ai_classifications_table', 1),
(19, '2026_06_14_154105_add_relations_to_sub_categories_table', 1),
(20, '2026_06_16_043949_add_comment_message_to_notifications_table', 1),
(21, '2026_06_18_165157_create_tickets_table', 1),
(22, '2026_06_20_000000_fill_notifications_sender', 1),
(23, '2026_06_22_000000_add_unique_permalink_to_notifications_table', 1),
(24, '2026_06_22_164448_add_profile_photo_to_users_table', 1),
(25, '2026_06_23_000001_add_ticket_system_columns', 1),
(26, '2026_06_23_000002_add_attachment_to_ticket_logs_and_responses_tables', 1),
(27, '2026_06_23_183220_create_instagram_mentions_table', 2),
(28, '2026_06_24_000001_add_proses_disposisi_status', 3),
(29, '2026_07_06_110343_add_duplicate_detection_to_notifications_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_message` text COLLATE utf8mb4_unicode_ci,
  `permalink` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `duplicate_of_id` bigint UNSIGNED DEFAULT NULL,
  `duplicate_similarity` double DEFAULT NULL,
  `duplicate_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `comment_message`, `permalink`, `is_read`, `duplicate_of_id`, `duplicate_similarity`, `duplicate_status`, `created_at`, `updated_at`, `sender`) VALUES
(1, 'Facebook Mention', 'Simadu KMC MATI LAMPU MIN', NULL, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-36748caf39f7', 1, NULL, NULL, NULL, '2026-06-23 10:26:45', '2026-06-23 10:27:07', 'MenthaZ Gaming'),
(2, 'Facebook Mention', 'Simadu KMC kabel menjuntai di jalan', NULL, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-332b70fd9840', 1, NULL, NULL, NULL, '2026-06-23 10:27:08', '2026-06-23 10:27:19', 'MNZ Gaming'),
(3, 'Facebook Mention', 'padam listrik min di jalan merdeka dah 3 hari Simadu KMC', NULL, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-d1b255dcf430', 1, NULL, NULL, NULL, '2026-06-23 10:27:24', '2026-06-26 06:04:53', 'MenthaZ Gaming'),
(4, 'Facebook Mention', 'Simadu KMC padam listrik di gg awan', NULL, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-a68b2f0c05b5', 1, NULL, NULL, NULL, '2026-06-23 10:27:39', '2026-06-26 04:24:42', 'MNZ Gaming'),
(5, 'Facebook Mention', 'Lampu merah sering mati, sangat membahayakan pengguna jalan. Mohon segera diperbaiki. Simadu KMC', NULL, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-df56e145f4ba', 1, NULL, NULL, NULL, '2026-06-23 10:28:01', '2026-06-26 06:02:41', 'Achmad Bagus Aprianto'),
(6, 'Facebook Mention', 'Simadu KMC min ade pohon tumbang di jalan suprapto', NULL, 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-218f538d732e', 1, NULL, NULL, NULL, '2026-06-23 10:28:43', '2026-07-03 07:28:17', 'MenthaZ Gaming'),
(7, 'Facebook Comment Mention', 'MNZ Gaming mentioned you in a comment.\n1minggu', 'Simadu KMC kabel menjuntai 12.43', 'https://web.facebook.com/reel/1299131388910742/?comment_id=1585543499572060', 1, NULL, NULL, NULL, '2026-06-23 10:32:44', '2026-06-26 08:59:05', 'MNZ Gaming'),
(11, 'Instagram DM', 'min mati lampu dah 3 hari di jalan merdeka', NULL, 'https://www.instagram.com/direct/t/106654980737016#c31b1b4ad17166d421d80535503a1626', 1, NULL, NULL, NULL, '2026-06-24 04:20:02', '2026-06-24 04:20:08', 'Ryan'),
(12, 'Instagram DM', 'min aik pdam di btn tanjung pura dah 3 hari dak jaln', NULL, 'https://www.instagram.com/direct/t/109260797134227#266ff727eb9e76c0fa9945d0064224ef', 1, NULL, NULL, NULL, '2026-06-24 04:20:12', '2026-06-24 04:20:17', 'Bguss'),
(13, 'Instagram DM', 'min ada bau menyengat dri belakang rumah saye', NULL, 'https://www.instagram.com/direct/t/17849415041802396#b9e5d2f263165c5a47a43f2824e1cf02', 1, NULL, NULL, NULL, '2026-06-24 04:27:47', '2026-06-24 04:27:56', 'Wolfrene'),
(14, 'Instagram DM', 'min ade pokok ngadang jalan, di samping rumah saye', NULL, 'https://www.instagram.com/direct/t/17849415041802396#793aadbe78d7e6dd4a6416be53d86340', 1, NULL, NULL, NULL, '2026-06-24 04:52:56', '2026-06-24 04:53:03', 'Wolfrene'),
(16, 'Instagram DM', 'min lampu merah di jalan mt haryono nin kontan mati jak am, tolong di benarkan', NULL, 'https://www.instagram.com/direct/t/109260797134227#a2e59d98bc0c449c64e2079555e0b511', 1, NULL, NULL, NULL, '2026-06-24 05:13:44', '2026-07-10 06:18:15', 'Bguss'),
(62, 'Facebook Mention', 'Simadu KMC min aik pdam dak jalan dah 2 hari di agus salim', NULL, 'https://web.facebook.com/permalink.php?story_fbid=pfbid0KWZsKrm1b81qAM3AxBqWrh6nfAxqCBmTN4S44VovTdfEcMXU35FSCZubTUvCQLppl&id=100072209896233&notif_id=1782317516349920&notif_t=mention&ref=notif#post-0126b20001e9', 1, NULL, NULL, NULL, '2026-07-05 12:00:21', '2026-07-05 12:00:25', 'MNZ Gaming'),
(64, 'Facebook Mention', 'Simadu KMC min tolong aik di kawasan jalan agus salim udah 3 hari dak ngalir min, warga susah', NULL, 'https://web.facebook.com/simadu-kmc/posts/duplikat-demo-1783348836', 1, 62, 95, 'dikonfirmasi_duplikat', '2026-07-06 14:40:36', '2026-07-07 06:52:37', 'Faridah Agus Salim'),
(77, 'Facebook Mention', 'Simadu KMC min aik pdam dak jalan dah 2 hari di agus salim', NULL, 'https://web.facebook.com/permalink.php?story_fbid=pfbid02PBraDPR6cr5XkhuNWhWBvrCG2VsguorhhVhCDmzvED8eU3kxd7GHMNH4gz7WNPEel&id=100072209896233&notif_id=1782317516349920&notif_t=mention&ref=notif#post-0126b20001e9', 0, NULL, NULL, NULL, '2026-07-08 12:45:53', '2026-07-08 12:45:53', 'MNZ Gaming'),
(78, 'Facebook Comment Mention', '', 'Simadu KMC min di jalan agus salim mati lampu tros am th ngape pln nin kontan mati idup', 'https://web.facebook.com/TempoMedia/posts/pfbid02tJnmHePhrV4RLbemsmxW8p2WFKs4vhfCUotGX9KAyGA8i9728ubPnm8eWV8PxdTal?comment_id=878531741517957', 1, NULL, NULL, NULL, '2026-07-08 18:20:48', '2026-07-08 18:21:02', 'Achmad Bagus Aprianto');

-- --------------------------------------------------------

--
-- Table structure for table `opds`
--

CREATE TABLE `opds` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `opds`
--

INSERT INTO `opds` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Sekretariat DPRD', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(2, 'Bappeda', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(3, 'BPKAD', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(4, 'BKPSDM', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(5, 'Disdukcapil', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(6, 'Dinas Pendidikan', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(7, 'Dinas PUTR', '2026-06-22 21:03:36', '2026-06-26 00:32:14'),
(8, 'Dinas Ketahanan Pangan, Kelautan, dan Perikanan', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(9, 'Dinas Kesehatan', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(10, 'Dinas Sosial', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(11, 'Dinas Pertanian, Peternakan dan Perkebunan', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(12, 'Dinas Arsip dan Perpustakaan', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(13, 'Dinas Pemuda dan Olahraga', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(14, 'Dinas Perindustrian, Perdagangan, Koperasi dan UKM', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(15, 'Dinas Kebudayaan dan Pariwisata', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(16, 'Dinas PMD', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(17, 'DPMPTSP', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(18, 'Dinas Tenaga Kerja dan Transmigrasi', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(19, 'Dinas Perhubungan', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(20, 'Dinas Komunikasi dan Informatika', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(21, 'Satpol PP', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(22, 'BPBD', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(23, 'Kesbangpol', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(24, 'RSUD Agoesdjam', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(25, 'PDAM Ketapang', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(26, 'BPN', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(27, 'Bank Kalbar', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(28, 'PLN', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(29, 'Polres Ketapang', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(30, 'PKK', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(31, 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(32, 'Dinas Lingkungan Hidup', '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(34, 'Dinas Perkim', '2026-07-04 23:30:19', '2026-07-04 23:30:19'),
(35, 'Inspektorat', '2026-07-04 23:30:19', '2026-07-04 23:30:19'),
(36, 'Bagian Ekonomi Pembangunan', '2026-07-04 23:30:19', '2026-07-04 23:30:19'),
(37, 'LPTQ', '2026-07-04 23:30:19', '2026-07-04 23:30:19');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7BkIFpzL5HAusfnnGTrLDSsAJ7NrjX7IiEvQSYB9', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI3cEc4VjdGcVNUVlc0YVJoYmk5MmlFVDFldDFRVlEzMUhTWlFaRXVTIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL25vdGlmaWNhdGlvbnMifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9vcGQiLCJyb3V0ZSI6ImFkbWluLm9wZC5pbmRleCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1784165726);

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `opd_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `opd_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 3, 7, 'Jalan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(2, 3, 19, 'Lampu Jalan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(3, 3, 7, 'Jembatan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(4, 3, 7, 'Drainase', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(5, 13, 32, 'Sampah', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(6, 16, 22, 'Banjir', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(7, 19, 25, 'Air Bersih', '2026-06-22 21:03:36', '2026-07-03 03:52:35'),
(8, 20, 28, 'Listrik', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(9, 1, 5, 'KTP', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(10, 1, 5, 'KK', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(11, 1, 5, 'Akta Kelahiran', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(12, 1, 5, 'Akta Kematian', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(13, 4, 6, 'Fasilitas Pendidikan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(14, 4, 6, 'Guru', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(15, 4, 6, 'Sekolah', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(16, 5, 9, 'Fasilitas Kesehatan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(17, 5, 9, 'Puskesmas', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(18, 5, 24, 'Rumah Sakit', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(19, 5, 9, 'BPJS', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(20, 6, 10, 'Bantuan Sosial', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(21, 6, 10, 'Orang Terlantar', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(22, 10, 14, 'UMKM', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(23, 10, 14, 'Koperasi', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(24, 10, 14, 'Pasar', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(25, 9, 11, 'Irigasi', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(26, 9, 8, 'Perikanan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(27, 9, 11, 'Peternakan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(28, 9, 11, 'Perkebunan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(29, 11, 20, 'Internet', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(30, 11, 20, 'Blank Spot', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(31, 11, 20, 'Aplikasi Pemerintah', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(32, 11, 20, 'Website Pemerintah', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(33, 2, 17, 'Perizinan Usaha', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(34, 8, 3, 'Pajak', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(35, 8, 3, 'Retribusi', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(36, 3, 19, 'Transportasi Umum', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(37, 3, 19, 'Parkir', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(38, 16, 22, 'Kebakaran', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(39, 16, 22, 'Tanah Longsor', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(40, 16, 22, 'Kebakaran Hutan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(41, 14, 21, 'Ketertiban Umum', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(42, 14, 21, 'Keamanan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(43, 14, 21, 'Tempat Ibadah', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(44, 14, 21, 'Ruang Publik', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(45, 24, 7, 'Taman Kota', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(46, 25, 26, 'Pertanahan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(47, 28, 26, 'Sengketa Tanah', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(48, 7, 18, 'PHK', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(49, 7, 18, 'Pelatihan Kerja', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(50, 8, 3, 'Pendapatan / Gaji', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(51, 12, 15, 'Pariwisata', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(52, 12, 15, 'Kebudayaan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(53, 12, 13, 'Olahraga', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(54, 14, 21, 'Hewan Liar', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(55, 7, 4, 'Kepegawaian', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(56, 16, 24, 'Nomor Darurat', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(57, 6, 10, 'KDRT', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(58, 6, 10, 'Kekerasan Anak', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(59, 6, 31, 'Kekerasan Perempuan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(60, 22, 27, 'ATM', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(61, 17, NULL, 'Keluhan Masyarakat', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(62, 17, NULL, 'Aduan Masyarakat', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(63, 18, NULL, 'Monitoring Berita', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(64, 13, 22, 'Pohon', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(65, 9, 8, 'Nelayan', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(66, 26, 9, 'Makan Bergizi Gratis', '2026-06-22 21:03:36', '2026-06-24 07:08:51'),
(67, 3, 19, 'Lampu Lalu Lintas', '2026-06-23 22:15:12', '2026-06-24 07:08:51'),
(68, 15, 23, 'Hukum dan Perundang-undangan', '2026-06-24 07:20:17', '2026-06-24 07:20:17'),
(69, 19, 25, 'Layanan PDAM', '2026-06-24 07:20:17', '2026-06-24 07:20:17'),
(70, 21, NULL, 'Pertanyaan', '2026-06-24 07:20:17', '2026-06-24 07:20:17'),
(71, 23, NULL, 'Perumahan', '2026-06-24 07:20:17', '2026-06-24 07:20:17'),
(72, 27, NULL, 'Administrasi', '2026-06-24 07:20:17', '2026-06-24 07:20:17'),
(73, 13, NULL, 'Pencemaran Air', '2026-07-04 23:16:37', '2026-07-04 23:16:37'),
(74, 13, 32, 'Pencemaran Lingkungan', '2026-07-04 23:17:02', '2026-07-04 23:17:02'),
(75, 6, 10, 'ODGJ', '2026-07-04 23:17:02', '2026-07-04 23:17:02'),
(76, 6, 18, 'Ketenagakerjaan', '2026-07-04 23:30:19', '2026-07-04 23:30:19'),
(77, 29, 34, 'Jalan Gang', '2026-07-04 23:30:19', '2026-07-04 23:30:19'),
(79, 3, 7, 'Perijinan', '2026-07-04 23:30:46', '2026-07-04 23:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `notification_id` bigint UNSIGNED DEFAULT NULL,
  `ticket_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticket_time` datetime NOT NULL,
  `platform` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reporter_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reporter_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opd_related` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complaint` text COLLATE utf8mb4_unicode_ci,
  `status` enum('diterima','diteruskan','dibaca','diproses','dijawab','selesai','eskalasi','proses_disposisi') COLLATE utf8mb4_unicode_ci DEFAULT 'diterima',
  `assigned_opd_id` bigint UNSIGNED DEFAULT NULL,
  `priority` enum('rendah','sedang','tinggi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sedang',
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sla_deadline` datetime DEFAULT NULL,
  `escalated_at` datetime DEFAULT NULL,
  `escalation_count` int NOT NULL DEFAULT '0',
  `read_at` datetime DEFAULT NULL,
  `responded_at` datetime DEFAULT NULL,
  `ai_confidence` decimal(5,2) DEFAULT NULL,
  `ai_reasoning` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `notification_id`, `ticket_number`, `ticket_time`, `platform`, `reporter_name`, `reporter_link`, `category`, `sub_category`, `opd_related`, `complaint`, `status`, `assigned_opd_id`, `priority`, `tracking_number`, `sla_deadline`, `escalated_at`, `escalation_count`, `read_at`, `responded_at`, `ai_confidence`, `ai_reasoning`, `created_at`, `updated_at`) VALUES
(1, 1, 'KMC-20260623-0001', '2026-06-24 00:27:05', 'Facebook', 'MenthaZ Gaming', 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-36748caf39f7', 'Layanan PLN', 'Listrik', 'PLN', 'Simadu KMC MATI LAMPU MIN', 'diteruskan', 28, 'rendah', 'KMC-20260623-0001', '2026-06-25 00:27:05', NULL, 0, NULL, NULL, 92.00, 'Keluhan mati lampu menunjukkan masalah listrik.', '2026-06-23 17:27:05', '2026-06-23 17:27:05'),
(2, 2, 'KMC-20260623-0002', '2026-06-24 00:27:18', 'Facebook', 'MNZ Gaming', 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-332b70fd9840', 'Layanan PLN', 'Listrik', 'PLN', 'Simadu KMC kabel menjuntai di jalan', 'dijawab', 28, 'rendah', 'KMC-20260623-0002', '2026-06-25 00:27:18', NULL, 0, NULL, NULL, 95.00, 'Kabel menjuntai di jalan menimbulkan bahaya keselamatan.', '2026-06-23 17:27:18', '2026-06-23 18:36:11'),
(3, 3, 'KMC-20260623-0003', '2026-06-24 00:27:32', 'Facebook', 'MenthaZ Gaming', 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-d1b255dcf430', 'Layanan PLN', 'Listrik', 'PLN', 'padam listrik min di jalan R Suprapto dah 3 hari Simadu KMC', 'selesai', 28, 'rendah', 'KMC-20260623-0003', '2026-06-25 00:27:32', NULL, 0, NULL, NULL, 96.00, 'Listrik padam selama 3 hari menimbulkan bahaya dan belum ditangani.', '2026-06-23 17:27:32', '2026-06-26 11:12:54'),
(4, 4, 'KMC-20260623-0004', '2026-06-24 00:27:47', 'Facebook', 'MNZ Gaming', 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-a68b2f0c05b5', 'Layanan PLN', 'Listrik', 'PLN', 'Simadu KMC padam listrik di gg awan', 'diproses', 28, 'rendah', 'KMC-20260623-0004', '2026-06-25 00:27:47', NULL, 0, NULL, NULL, 96.00, 'Laporan padam listrik di jalan Awan', '2026-06-23 17:27:47', '2026-06-23 18:06:43'),
(5, 5, 'KMC-20260623-0005', '2026-06-24 00:28:12', 'Facebook', 'Achmad Bagus Aprianto', 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-df56e145f4ba', 'Infrastruktur dan Pekerjaan Umum', 'Lampu Jalan', 'Dinas Perhubungan', 'Lampu merah sering mati, sangat membahayakan pengguna jalan. Mohon segera diperbaiki. Simadu KMC', 'selesai', 19, 'tinggi', 'KMC-20260623-0005', '2026-06-25 00:28:12', NULL, 0, NULL, NULL, 96.00, 'Lampu merah mati membahayakan pengguna jalan', '2026-06-23 17:28:12', '2026-06-23 17:39:04'),
(6, 6, 'KMC-20260623-0006', '2026-06-24 00:28:56', 'Facebook', 'MenthaZ Gaming', 'https://web.facebook.com/mentions/1781585732734215/?notif_id=1781585732734215&notif_t=mention&ref=notif#post-218f538d732e', 'Lingkungan Hidup dan Kehutanan', 'Pohon', 'BPBD', 'Simadu KMC min ade pohon tumbang di jalan suprapto', 'diteruskan', 22, 'tinggi', 'KMC-20260623-0006', '2026-06-25 00:28:56', NULL, 0, NULL, NULL, 96.00, 'Pohon tumbang menghalangi jalan dan berpotensi bahaya keselamatan.', '2026-06-23 17:28:56', '2026-06-23 17:28:56'),
(7, 7, 'KMC-20260623-0007', '2026-06-24 00:33:21', 'Facebook', 'MNZ Gaming', 'https://web.facebook.com/reel/1299131388910742/?comment_id=1585543499572060', 'Layanan PLN', 'Listrik', 'PLN', 'Simadu KMC kabel menjuntai 12.43', 'diproses', 28, 'rendah', 'KMC-20260623-0007', '2026-06-25 00:33:21', NULL, 0, NULL, NULL, 0.00, 'AI gagal menghasilkan klasifikasi yang valid atau sedang terjadi gangguan sistem.', '2026-06-23 17:33:21', '2026-06-26 11:21:41'),
(11, 11, 'KMC-20260624-0001', '2026-06-24 18:20:08', 'Instagram', 'Ryan', 'https://www.instagram.com/direct/t/106654980737016#c31b1b4ad17166d421d80535503a1626', 'Layanan PLN', 'Listrik', 'PLN', 'min mati lampu dah 3 hari di jalan merdeka', 'diproses', 28, 'rendah', 'KMC-20260624-0001', '2026-06-25 18:20:08', NULL, 0, NULL, NULL, 96.00, 'Listrik padam selama 3 hari di area publik', '2026-06-24 11:20:08', '2026-06-26 15:05:47'),
(12, 12, 'KMC-20260624-0002', '2026-06-24 18:20:17', 'Instagram', 'Bguss', 'https://www.instagram.com/direct/t/109260797134227#266ff727eb9e76c0fa9945d0064224ef', 'Layanan PDAM', 'Air Bersih', 'PDAM Ketapang', 'min aik pdam di btn tanjung pura dah 3 hari dak jaln', 'selesai', 25, 'sedang', 'KMC-20260624-0002', '2026-06-25 18:20:17', NULL, 0, NULL, NULL, 96.00, 'Air PDAM tidak mengalir selama beberapa hari.', '2026-06-24 11:20:17', '2026-06-26 10:36:12'),
(13, 13, 'KMC-20260624-0003', '2026-06-24 18:27:56', 'Instagram', 'Wolfrene', 'https://www.instagram.com/direct/t/17849415041802396#b9e5d2f263165c5a47a43f2824e1cf02', 'Lingkungan Hidup dan Kehutanan', 'Sampah', 'Dinas Lingkungan Hidup', 'min ada bau menyengat dri belakang rumah saye', 'selesai', 32, 'rendah', 'KMC-20260624-0003', '2026-06-25 18:27:56', NULL, 0, NULL, NULL, 92.00, 'Laporan bau menyengat biasanya terkait sampah atau limbah di sekitar rumah.', '2026-06-24 11:27:56', '2026-06-26 10:55:53'),
(14, 14, 'KMC-20260624-0004', '2026-06-24 18:53:03', 'Instagram', 'Wolfrene', 'https://www.instagram.com/direct/t/17849415041802396#793aadbe78d7e6dd4a6416be53d86340', 'Lingkungan Hidup dan Kehutanan', 'Pohon', 'BPBD', 'min ade pokok ngadang jalan, di samping rumah saye', 'selesai', 22, 'rendah', 'KMC-20260624-0004', '2026-06-25 18:53:03', NULL, 0, NULL, NULL, 94.00, 'Pohon menghalangi jalan di sekitar rumah.', '2026-06-24 11:53:03', '2026-06-26 11:14:38'),
(16, 16, 'KMC-20260624-0005', '2026-06-24 19:13:49', 'Instagram', 'Bguss', 'https://www.instagram.com/direct/t/109260797134227#a2e59d98bc0c449c64e2079555e0b511', 'Infrastruktur dan Pekerjaan Umum', 'Lampu Lalu Lintas', 'Dinas Perhubungan', 'min lampu merah di jalan mt haryono nin kontan mati jak am, tolong di benarkan', 'diteruskan', 19, 'rendah', 'KMC-20260624-0005', '2026-06-25 19:13:49', NULL, 0, NULL, NULL, 92.00, 'Lampu merah (lampu lalu lintas) tidak berfungsi', '2026-06-24 12:13:49', '2026-06-24 12:15:12'),
(46, 62, 'KMC-20260705-0001', '2026-07-05 19:00:39', 'Facebook', 'MNZ Gaming', 'https://web.facebook.com/permalink.php?story_fbid=pfbid0KWZsKrm1b81qAM3AxBqWrh6nfAxqCBmTN4S44VovTdfEcMXU35FSCZubTUvCQLppl&id=100072209896233&notif_id=1782317516349920&notif_t=mention&ref=notif#post-0126b20001e9', 'Layanan PDAM', 'Air Bersih', 'PDAM Ketapang', 'Simadu KMC min aik pdam dak jalan dah 2 hari di agus salim', 'diteruskan', 25, 'sedang', 'KMC-20260705-0001', '2026-07-06 19:00:39', NULL, 0, NULL, NULL, 98.00, 'Keluhan air PDAM tidak mengalir selama dua hari di wilayah Agus Salim.', '2026-07-05 12:00:39', '2026-07-05 12:00:39'),
(47, 78, 'KMC-20260709-0001', '2026-07-09 01:22:27', 'Facebook', 'Achmad Bagus Aprianto', 'https://web.facebook.com/TempoMedia/posts/pfbid02tJnmHePhrV4RLbemsmxW8p2WFKs4vhfCUotGX9KAyGA8i9728ubPnm8eWV8PxdTal?comment_id=878531741517957', 'Layanan PLN', 'Listrik', 'PLN', 'Simadu KMC min di jalan agus salim mati lampu tros am th ngape pln nin kontan mati idup', 'diteruskan', 28, 'rendah', 'KMC-20260709-0001', '2026-07-10 01:22:27', NULL, 0, NULL, NULL, 98.00, 'Keluhan mengenai listrik padam dan tidak stabil (mati hidup) di Jalan Agus Salim yang melibatkan PLN.', '2026-07-08 18:22:27', '2026-07-08 18:22:27');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_responses`
--

CREATE TABLE `ticket_responses` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_responses`
--

INSERT INTO `ticket_responses` (`id`, `ticket_id`, `user_id`, `message`, `attachment`, `created_at`, `updated_at`) VALUES
(2, 3, 29, 'sedang di laporkan dan menunggu arahan dari atasan', NULL, '2026-06-23 04:34:05', '2026-06-23 04:34:05'),
(3, 2, 29, 'akan di proses', 'attachments/rnJ6ONx55vLCtmxtVdhZgF2IDrFM0JXbGhVulVsX.jpg', '2026-06-23 04:36:11', '2026-06-23 04:36:11'),
(4, 13, 33, 'Baik Laporan sudah kami terima, akan segera di tindaklanjuti', NULL, '2026-06-25 20:47:31', '2026-06-25 20:47:31');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_status_logs`
--

CREATE TABLE `ticket_status_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `from_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_status_logs`
--

INSERT INTO `ticket_status_logs` (`id`, `ticket_id`, `from_status`, `to_status`, `changed_by`, `note`, `attachment`, `created_at`) VALUES
(1, 1, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Facebook', NULL, '2026-06-23 10:27:05'),
(2, 1, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: PLN', NULL, '2026-06-23 10:27:05'),
(3, 2, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Facebook', NULL, '2026-06-23 10:27:18'),
(4, 2, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: PLN', NULL, '2026-06-23 10:27:18'),
(5, 3, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Facebook', NULL, '2026-06-23 10:27:32'),
(6, 3, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: PLN', NULL, '2026-06-23 10:27:32'),
(7, 4, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Facebook', NULL, '2026-06-23 10:27:47'),
(8, 4, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: PLN', NULL, '2026-06-23 10:27:47'),
(9, 5, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Facebook', NULL, '2026-06-23 10:28:12'),
(10, 5, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: Dinas Perhubungan', NULL, '2026-06-23 10:28:12'),
(11, 6, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Facebook', NULL, '2026-06-23 10:28:56'),
(12, 6, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: BPBD', NULL, '2026-06-23 10:28:56'),
(13, 7, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Facebook', NULL, '2026-06-23 10:33:21'),
(14, 7, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: PLN', NULL, '2026-06-23 10:33:21'),
(15, 5, 'diteruskan', 'diterima', 20, 'Status diubah oleh OPD', NULL, '2026-06-23 10:38:23'),
(16, 5, 'diterima', 'diproses', 20, 'Status diubah oleh OPD', NULL, '2026-06-23 10:38:40'),
(17, 5, 'diproses', 'eskalasi', 20, 'Status diubah oleh OPD', NULL, '2026-06-23 10:38:53'),
(18, 5, 'eskalasi', 'selesai', 20, 'Status diubah oleh OPD', NULL, '2026-06-23 10:39:04'),
(19, 5, 'selesai', 'selesai', 20, 'Status diubah oleh OPD', 'attachments/VukVabkxAoK7Mm4yuxTHWQywHl4PJMXGpM3OumUb.jpg', '2026-06-23 10:40:00'),
(20, 7, 'diteruskan', 'diterima', 29, 'Status diubah oleh OPD', NULL, '2026-06-23 11:06:30'),
(21, 4, 'diteruskan', 'diproses', 29, 'Status diubah oleh OPD', NULL, '2026-06-23 11:06:43'),
(22, 3, 'diteruskan', 'dijawab', 29, 'OPD memberikan tanggapan', NULL, '2026-06-23 11:34:05'),
(23, 2, 'diteruskan', 'dijawab', 29, 'OPD memberikan tanggapan', NULL, '2026-06-23 11:36:11'),
(30, 11, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Instagram DM', NULL, '2026-06-24 04:20:08'),
(31, 11, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: PLN', NULL, '2026-06-24 04:20:08'),
(32, 12, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Instagram DM', NULL, '2026-06-24 04:20:17'),
(33, 12, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: PDAM Ketapang', NULL, '2026-06-24 04:20:17'),
(34, 13, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Instagram DM', NULL, '2026-06-24 04:27:56'),
(35, 13, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: Dinas Lingkungan Hidup', NULL, '2026-06-24 04:27:56'),
(36, 14, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Instagram DM', NULL, '2026-06-24 04:53:03'),
(37, 14, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: BPBD', NULL, '2026-06-24 04:53:03'),
(40, 16, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Instagram DM', NULL, '2026-06-24 05:13:49'),
(41, 16, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: Dinas Perhubungan', NULL, '2026-06-24 05:13:49'),
(85, 12, 'diteruskan', 'diterima', 26, 'Status diperbarui oleh OPD', NULL, '2026-06-26 03:34:50'),
(86, 12, 'diterima', 'diproses', 26, 'Sedang di perbaiki', 'attachments/QMmnblaO0LsU8jvqDxidX9j7cA8kVvaVP2pcdZk3.jpg', '2026-06-26 03:35:37'),
(87, 12, 'diproses', 'selesai', 26, 'sudah diperbaiki terima kasih', 'attachments/olTgeuyU30Wk0tSYZg7gajwLPrGGCFTRtbHF6bqQ.jpg', '2026-06-26 03:36:12'),
(88, 11, 'diteruskan', 'diterima', 29, 'Status diperbarui oleh OPD', NULL, '2026-06-26 03:43:30'),
(89, 13, 'diteruskan', 'diterima', 33, 'Status diperbarui oleh OPD', NULL, '2026-06-26 03:45:56'),
(90, 13, 'diterima', 'dijawab', 33, 'OPD memberikan tanggapan', NULL, '2026-06-26 03:47:31'),
(91, 13, 'dijawab', 'diproses', 33, 'sedang di telusuri penyebabnya', 'attachments/Ii23Nfd0766g9g6QPmKlTm3dgQxY4fIZiDejE5xw.jpg', '2026-06-26 03:54:51'),
(92, 13, 'diproses', 'selesai', 33, 'sudah ditemukan penyebabnya, karena ada bangkai hewan. dan sudah dibersihkan. Terimakasih', 'attachments/1rolW6a1ta8czcYoDfanRB0Gwx3h676FsRGEJSgM.jpg', '2026-06-26 03:55:53'),
(93, 3, 'dijawab', 'selesai', 29, 'Status diperbarui oleh OPD', NULL, '2026-06-26 04:12:54'),
(94, 14, 'diteruskan', 'diterima', NULL, 'Status otomatis disesuaikan oleh sistem', NULL, '2026-06-26 04:14:38'),
(95, 14, 'diterima', 'diproses', NULL, 'Status otomatis disesuaikan oleh sistem', NULL, '2026-06-26 04:14:38'),
(96, 14, 'diproses', 'selesai', 23, 'Status diperbarui oleh OPD', NULL, '2026-06-26 04:14:38'),
(97, 7, 'diterima', 'diproses', 29, 'Status diperbarui oleh OPD', 'attachments/w9e4C85xoKow3apsaJLZEDzEFNn6ulN5TlQSzAuT.jpg', '2026-06-26 04:21:41'),
(98, 11, 'diterima', 'diproses', 29, 'sedang di cek ke lapangan', 'attachments/OEMZmjfMrdbLQXX0dMSc9OloY69oCXPYInNDw6xs.jpg', '2026-06-26 08:05:47'),
(114, 46, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Facebook', NULL, '2026-07-05 12:00:39'),
(115, 46, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: PDAM Ketapang', NULL, '2026-07-05 12:00:39'),
(116, 47, NULL, 'diterima', NULL, 'Tiket otomatis dibuat dari notifikasi Facebook', NULL, '2026-07-08 18:22:27'),
(117, 47, 'diterima', 'diteruskan', NULL, 'Diteruskan ke OPD: PLN', NULL, '2026-07-08 18:22:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','opd') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'opd',
  `opd_id` bigint UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `role`, `opd_id`, `email_verified_at`, `password`, `profile_photo`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'Admin', 'admin@kmc.go.id', 'admin', NULL, NULL, '$2y$12$peJA2QdGHAvDBim9TTLmlOGgk06oAiWLPUcJfsG6kstI6N60LM8pi', 'profiles/t5ZQJY6i1Ws3alXD3qxgZIhWt1tk6PvLgrUL67lQ.png', 'vwwnGZ2dv7pAw5C2pAV0r13iEAzZJdYKAs44dewfKa0ut3nKITQx7ceQods7', '2026-06-22 21:03:36', '2026-06-24 00:04:28'),
(2, 'Sekretariat DPRD', 'sekretariat-dprd', 'sekretariat-dprd@kmc.go.id', 'opd', 1, NULL, '$2y$12$UWHbf4B1EnRdw1XWqxvhzuB0Is4XKUTEHuu3yn94Gil5Q76ubfG8y', NULL, NULL, '2026-06-22 21:03:36', '2026-06-22 21:03:36'),
(3, 'Bappeda', 'bappeda', 'bappeda@kmc.go.id', 'opd', 2, NULL, '$2y$12$0iuedCOypFU3bpKdtCe3oeAYnJa.rwEt89UcxhMgEazI0LQOOGG2G', NULL, NULL, '2026-06-22 21:03:37', '2026-06-22 21:03:37'),
(4, 'BPKAD', 'bpkad', 'bpkad@kmc.go.id', 'opd', 3, NULL, '$2y$12$kCBZPJtBHRiX6cubi6MYjeUNDzmsCjS45Iop/bnBuSxehIezWFIgW', NULL, NULL, '2026-06-22 21:03:37', '2026-06-22 21:03:37'),
(5, 'BKPSDM', 'bkpsdm', 'bkpsdm@kmc.go.id', 'opd', 4, NULL, '$2y$12$11DP4n6IabhIJbArOIwdtOfZm76n6T74ueGOfW1e.tMn8kmKZlYdC', NULL, NULL, '2026-06-22 21:03:37', '2026-06-22 21:03:37'),
(6, 'Disdukcapil', 'disdukcapil', 'disdukcapil@kmc.go.id', 'opd', 5, NULL, '$2y$12$A5RShH93WP1uMHgnw.0NAePFD1t.fvV9u6UZ0WB2r5DtCvLMh9piC', NULL, NULL, '2026-06-22 21:03:37', '2026-06-22 21:03:37'),
(7, 'Dinas Pendidikan', 'dinas-pendidikan', 'dinas-pendidikan@kmc.go.id', 'opd', 6, NULL, '$2y$12$QXUVl18nn1TQbfWZpR3G3.zKbhn4ZL6cWh5mAOHUnkYrf2YzIsE.C', NULL, NULL, '2026-06-22 21:03:38', '2026-06-22 21:03:38'),
(8, 'Dinas PUTR', 'dinas-putr', 'dinas-putr@kmc.go.id', 'opd', 7, NULL, '$2y$12$TJz8aHPluWa5A25vWzWaX.uG6KKG8klVei1B.IO9if2BbD8xnwtIW', NULL, NULL, '2026-06-22 21:03:38', '2026-06-26 00:32:14'),
(9, 'Dinas Ketahanan Pangan, Kelautan, dan Perikanan', 'dinas-ketahanan-pangan-kelautan-dan-perikanan', 'dinas-ketahanan-pangan-kelautan-dan-perikanan@kmc.go.id', 'opd', 8, NULL, '$2y$12$fOHvSE4rhehEqyBs7oWMWedJvcuYo6xssVFfff4ImWUWR7c/slY4K', NULL, NULL, '2026-06-22 21:03:38', '2026-06-22 21:03:38'),
(10, 'Dinas Kesehatan', 'dinas-kesehatan', 'dinas-kesehatan@kmc.go.id', 'opd', 9, NULL, '$2y$12$XZn4aIiDacC9sgAHn1RogeWlZAmzLvsZHKKQ4eE8SWakNNKcTB2oy', NULL, NULL, '2026-06-22 21:03:39', '2026-06-22 21:03:39'),
(11, 'Dinas Sosial', 'dinas-sosial', 'dinas-sosial@kmc.go.id', 'opd', 10, NULL, '$2y$12$8ByYeRZ36vfJDKAPvUc2SO8CPi5nLLUf8588JR6qyxVgIRGgisaji', NULL, NULL, '2026-06-22 21:03:39', '2026-06-22 21:03:39'),
(12, 'Dinas Pertanian, Peternakan dan Perkebunan', 'dinas-pertanian-peternakan-dan-perkebunan', 'dinas-pertanian-peternakan-dan-perkebunan@kmc.go.id', 'opd', 11, NULL, '$2y$12$/0LhUPEMwEjhGI82pNmenuFWQuA09iSdfUkMgAEL1fSsJy.N9C7k6', NULL, NULL, '2026-06-22 21:03:39', '2026-06-22 21:03:39'),
(13, 'Dinas Arsip dan Perpustakaan', 'dinas-arsip-dan-perpustakaan', 'dinas-arsip-dan-perpustakaan@kmc.go.id', 'opd', 12, NULL, '$2y$12$1kmv2NDcQeZWcVbT4woGc.3F81t0tIZduQy5OhkwHeSUjvbbtlbuq', NULL, NULL, '2026-06-22 21:03:39', '2026-06-22 21:03:39'),
(14, 'Dinas Pemuda dan Olahraga', 'dinas-pemuda-dan-olahraga', 'dinas-pemuda-dan-olahraga@kmc.go.id', 'opd', 13, NULL, '$2y$12$Y96fvy16EjqDAZsR9KCEWuWcTiWBfE9XZjK1lQV4UmuByJiKt8lNq', NULL, NULL, '2026-06-22 21:03:40', '2026-06-22 21:03:40'),
(15, 'Dinas Perindustrian, Perdagangan, Koperasi dan UKM', 'dinas-perindustrian-perdagangan-koperasi-dan-ukm', 'dinas-perindustrian-perdagangan-koperasi-dan-ukm@kmc.go.id', 'opd', 14, NULL, '$2y$12$DEnHlb7O9Qi740SAAUWGxOgAmnJh2C79WzyMBReoxIGa7qP3RVV8G', NULL, NULL, '2026-06-22 21:03:40', '2026-06-22 21:03:40'),
(16, 'Dinas Kebudayaan dan Pariwisata', 'dinas-kebudayaan-dan-pariwisata', 'dinas-kebudayaan-dan-pariwisata@kmc.go.id', 'opd', 15, NULL, '$2y$12$cCkjTfGLgmU2avj4UJ64BOxvJdlbMPQ6v9Jan8TVeFccmDUAV8.xW', NULL, NULL, '2026-06-22 21:03:40', '2026-06-22 21:03:40'),
(17, 'Dinas PMD', 'dinas-pmd', 'dinas-pmd@kmc.go.id', 'opd', 16, NULL, '$2y$12$s9qvoFFPrvS4nOEdfwWgh.0Y81AO0Ys2tKEdLqcC8WTpD8vP6cBgK', NULL, NULL, '2026-06-22 21:03:41', '2026-06-22 21:03:41'),
(18, 'DPMPTSP', 'dpmptsp', 'dpmptsp@kmc.go.id', 'opd', 17, NULL, '$2y$12$uZ5wWBuuGAxneeA1HmouLeLScOVIkwux6QSWXXTQqFrS6DcM9s2G2', NULL, NULL, '2026-06-22 21:03:41', '2026-06-22 21:03:41'),
(19, 'Dinas Tenaga Kerja dan Transmigrasi', 'dinas-tenaga-kerja-dan-transmigrasi', 'dinas-tenaga-kerja-dan-transmigrasi@kmc.go.id', 'opd', 18, NULL, '$2y$12$1cpUNAjAdFPetf1ZkmDOGeSOhnGj6secxXdEQWQBZWiqQWCgVYsSK', NULL, NULL, '2026-06-22 21:03:41', '2026-06-22 21:03:41'),
(20, 'Dinas Perhubungan', 'dinas-perhubungan', 'dinas-perhubungan@kmc.go.id', 'opd', 19, NULL, '$2y$12$J4m6wHvLovx9MFLX8Tl96OdmVIWA4dMoe/F05XQTFTpbrgZPPe45m', NULL, NULL, '2026-06-22 21:03:41', '2026-06-22 21:03:41'),
(21, 'Dinas Komunikasi dan Informatika', 'dinas-komunikasi-dan-informatika', 'dinas-komunikasi-dan-informatika@kmc.go.id', 'opd', 20, NULL, '$2y$12$OKpwKWAWUeZhsbdRc7uUt.6mYBSsEHv04V8zrJc4rglBHAv6iUbXW', NULL, NULL, '2026-06-22 21:03:42', '2026-06-22 21:03:42'),
(22, 'Satpol PP', 'satpol-pp', 'satpol-pp@kmc.go.id', 'opd', 21, NULL, '$2y$12$DbKpoCa18ppY8kBYXcdhjOuiZ.KcMGIPyfMHNpV6CHiHifEDH6EJO', NULL, NULL, '2026-06-22 21:03:42', '2026-06-22 21:03:42'),
(23, 'BPBD', 'bpbd', 'bpbd@kmc.go.id', 'opd', 22, NULL, '$2y$12$AQFgVOGRec/RNTlh2BB86u41KqXXO5fUR5Pe/BIkfcId5E/4kS9F2', NULL, NULL, '2026-06-22 21:03:42', '2026-06-22 21:03:42'),
(24, 'Kesbangpol', 'kesbangpol', 'kesbangpol@kmc.go.id', 'opd', 23, NULL, '$2y$12$Or0c7KY6tkIpobqot6KH.e24n2zf2aZBZaA1.4k0I47dmDavq0y0y', NULL, NULL, '2026-06-22 21:03:42', '2026-06-22 21:03:42'),
(25, 'RSUD Agoesdjam', 'rsud-agoesdjam', 'rsud-agoesdjam@kmc.go.id', 'opd', 24, NULL, '$2y$12$bjArNskGofxNkxH.XEedPO/0z8HG4jb3tuycum1dyEkw9qkfe2nF6', NULL, NULL, '2026-06-22 21:03:43', '2026-06-22 21:03:43'),
(26, 'PDAM Ketapang', 'pdam-ketapang', 'pdam-ketapang@kmc.go.id', 'opd', 25, NULL, '$2y$12$fBvFNT/BCFAb2NrJmuhqjOc6SaR/9opTt7vdmmkDkmg7Kh6jP6jkC', NULL, NULL, '2026-06-22 21:03:43', '2026-06-22 21:03:43'),
(27, 'BPN', 'bpn', 'bpn@kmc.go.id', 'opd', 26, NULL, '$2y$12$BuyW8E/3i31KMc7.xbdpl.JOLR1MtlsyiHfrJM8dq1R09vDpfhSHu', NULL, NULL, '2026-06-22 21:03:43', '2026-06-22 21:03:43'),
(28, 'Bank Kalbar', 'bank-kalbar', 'bank-kalbar@kmc.go.id', 'opd', 27, NULL, '$2y$12$XPi8lKj1hF/f6mmVbCl8M.fAqCExK/t9uZWaAPsuWDYhDQgO/fqMa', NULL, NULL, '2026-06-22 21:03:44', '2026-06-22 21:03:44'),
(29, 'PLN', 'pln', 'pln@kmc.go.id', 'opd', 28, NULL, '$2y$12$1US2o66rnAfJJ10p/Oxl5ONaHbDl1S1BnfgGe2K97hbxVPcAqifCO', NULL, NULL, '2026-06-22 21:03:44', '2026-06-22 21:03:44'),
(30, 'Polres Ketapang', 'polres-ketapang', 'polres-ketapang@kmc.go.id', 'opd', 29, NULL, '$2y$12$UX9T4Q7XHiV36ghBN7mE4OJpoiCzA7CxrUu2YmE13BF5iMRmCZyNy', NULL, NULL, '2026-06-22 21:03:44', '2026-06-22 21:03:44'),
(31, 'PKK', 'pkk', 'pkk@kmc.go.id', 'opd', 30, NULL, '$2y$12$q66T74Vl.h.j5KeME/e5p.INkituemS8AWLy0zDD7mHBsXRDXZCQC', NULL, NULL, '2026-06-22 21:03:44', '2026-06-22 21:03:44'),
(32, 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak', 'dinas-pemberdayaan-perempuan-dan-perlindungan-anak', 'dinas-pemberdayaan-perempuan-dan-perlindungan-anak@kmc.go.id', 'opd', 31, NULL, '$2y$12$4NBuMUfoJiKMf/Iq6.Wh2uYzQD2vBay3un/WK.t0AJipxbGuU/BMa', NULL, NULL, '2026-06-22 21:03:45', '2026-06-22 21:03:45'),
(33, 'Dinas Lingkungan Hidup', 'dinas-lh', 'dinas-lingkungan-hidup@kmc.go.id', 'opd', 32, NULL, '$2y$12$rIBU2.Gt.SUP3Uxl59uiMORUyNga52Ltmdtw7nk8ifmz8D2J3dpwC', NULL, NULL, '2026-06-22 21:03:45', '2026-06-25 20:44:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_classifications`
--
ALTER TABLE `ai_classifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ai_classifications_notification_id_foreign` (`notification_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_category_id_foreign` (`category_id`),
  ADD KEY `complaints_sub_category_id_foreign` (`sub_category_id`),
  ADD KEY `complaints_opd_id_foreign` (`opd_id`);

--
-- Indexes for table `facebook_comment_mentions`
--
ALTER TABLE `facebook_comment_mentions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facebook_comment_mentions_comment_link_unique` (`comment_link`);

--
-- Indexes for table `facebook_post_mentions`
--
ALTER TABLE `facebook_post_mentions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facebook_post_mentions_post_link_unique` (`post_link`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `instagram_mentions`
--
ALTER TABLE `instagram_mentions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instagram_mentions_post_link_unique` (`post_link`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notifications_permalink_unique` (`permalink`),
  ADD KEY `notifications_duplicate_of_id_foreign` (`duplicate_of_id`),
  ADD KEY `notifications_duplicate_status_index` (`duplicate_status`);

--
-- Indexes for table `opds`
--
ALTER TABLE `opds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `opds_name_unique` (`name`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sub_categories_name_unique` (`name`),
  ADD KEY `sub_categories_category_id_foreign` (`category_id`),
  ADD KEY `sub_categories_opd_id_foreign` (`opd_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tickets_ticket_number_unique` (`ticket_number`),
  ADD UNIQUE KEY `tickets_tracking_number_unique` (`tracking_number`),
  ADD KEY `tickets_notification_id_foreign` (`notification_id`),
  ADD KEY `tickets_assigned_opd_id_foreign` (`assigned_opd_id`);

--
-- Indexes for table `ticket_responses`
--
ALTER TABLE `ticket_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_responses_ticket_id_foreign` (`ticket_id`),
  ADD KEY `ticket_responses_user_id_foreign` (`user_id`);

--
-- Indexes for table `ticket_status_logs`
--
ALTER TABLE `ticket_status_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_status_logs_ticket_id_foreign` (`ticket_id`),
  ADD KEY `ticket_status_logs_changed_by_foreign` (`changed_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_opd_id_foreign` (`opd_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_classifications`
--
ALTER TABLE `ai_classifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facebook_comment_mentions`
--
ALTER TABLE `facebook_comment_mentions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `facebook_post_mentions`
--
ALTER TABLE `facebook_post_mentions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instagram_mentions`
--
ALTER TABLE `instagram_mentions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `opds`
--
ALTER TABLE `opds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `ticket_responses`
--
ALTER TABLE `ticket_responses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ticket_status_logs`
--
ALTER TABLE `ticket_status_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_classifications`
--
ALTER TABLE `ai_classifications`
  ADD CONSTRAINT `ai_classifications_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `complaints_opd_id_foreign` FOREIGN KEY (`opd_id`) REFERENCES `opds` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `complaints_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_duplicate_of_id_foreign` FOREIGN KEY (`duplicate_of_id`) REFERENCES `notifications` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sub_categories_opd_id_foreign` FOREIGN KEY (`opd_id`) REFERENCES `opds` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_assigned_opd_id_foreign` FOREIGN KEY (`assigned_opd_id`) REFERENCES `opds` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ticket_responses`
--
ALTER TABLE `ticket_responses`
  ADD CONSTRAINT `ticket_responses_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_responses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_status_logs`
--
ALTER TABLE `ticket_status_logs`
  ADD CONSTRAINT `ticket_status_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ticket_status_logs_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_opd_id_foreign` FOREIGN KEY (`opd_id`) REFERENCES `opds` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
