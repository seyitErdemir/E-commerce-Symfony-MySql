-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 10 Ağu 2022, 15:53:21
-- Sunucu sürümü: 5.7.31
-- PHP Sürümü: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `db-symfony2`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `basket`
--

DROP TABLE IF EXISTS `basket`;
CREATE TABLE IF NOT EXISTS `basket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total_price` decimal(10,2) NOT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `status` tinyint(1) NOT NULL,
  `check_money` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2246507BA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `children_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_64C19C13D3D2749` (`children_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `category`
--

INSERT INTO `category` (`id`, `name`, `active`, `created_at`, `updated_at`, `children_id`) VALUES
(9, 'Kadın', 0, '2022-08-10 16:21:02', NULL, NULL),
(10, 'Erkek', 0, '2022-08-10 16:21:13', NULL, NULL),
(11, 'Spor', 0, '2022-08-10 16:21:32', NULL, 10),
(12, 'Ayakkabı', 1, '2022-08-10 16:22:16', NULL, 11),
(13, 'Kıyafet', 0, '2022-08-10 16:22:30', NULL, 9),
(14, 'Çanta', 0, '2022-08-10 16:22:47', NULL, 9),
(15, 'Terlik', 0, '2022-08-10 16:23:21', NULL, 11),
(16, 'Çocuk', 0, '2022-08-10 16:36:24', NULL, NULL),
(17, 'Çocuk Ayakkabı', 0, '2022-08-10 16:36:52', NULL, 16),
(18, 'Çocuk Kıyafet', 0, '2022-08-10 16:37:07', NULL, 16);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Tablo döküm verisi `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220803165450', '2022-08-03 18:55:59', 384),
('DoctrineMigrations\\Version20220803191616', '2022-08-03 21:18:19', 179),
('DoctrineMigrations\\Version20220803194500', '2022-08-03 21:45:15', 181),
('DoctrineMigrations\\Version20220806223302', '2022-08-07 00:33:24', 280),
('DoctrineMigrations\\Version20220806224240', '2022-08-07 00:42:48', 251),
('DoctrineMigrations\\Version20220806224408', '2022-08-07 00:44:12', 54),
('DoctrineMigrations\\Version20220807125855', '2022-08-07 15:01:20', 212),
('DoctrineMigrations\\Version20220807130515', '2022-08-07 15:05:33', 215),
('DoctrineMigrations\\Version20220807131434', '2022-08-07 15:15:26', 218),
('DoctrineMigrations\\Version20220807131801', '2022-08-07 15:18:14', 161),
('DoctrineMigrations\\Version20220807132009', '2022-08-07 15:20:14', 210),
('DoctrineMigrations\\Version20220807143324', '2022-08-07 16:33:29', 154),
('DoctrineMigrations\\Version20220807143930', '2022-08-07 16:39:36', 161),
('DoctrineMigrations\\Version20220807150251', '2022-08-07 17:02:55', 210),
('DoctrineMigrations\\Version20220807151539', '2022-08-07 17:15:49', 221);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `price`, `image`, `active`, `created_at`, `updated_at`) VALUES
(6, 'Ayakkabı', '<div>Ayakkabı</div>', '15.00', '21BAU00EACTU_001_1_small[1].jpg', 1, '2022-08-10 16:24:30', '2022-08-10 16:25:05'),
(7, 'SAND TERLİK', '<h1>SAND TERLİK</h1><div><br></div>', '10.00', '900141-8997-sand-terlik-637846645110003438[1].jpg', 1, '2022-08-10 16:26:06', '2022-08-10 16:26:09'),
(8, 'Çanta', '<div>Çanta</div>', '25.00', 'kisa-zincir-detayli-capraz-bej-kadin-canta-4[1].jpg', 1, '2022-08-10 16:26:56', NULL),
(9, 'Elbise', '<div>Elbise</div>', '30.00', '11141243338802[1].jpg', 1, '2022-08-10 16:28:38', NULL),
(10, 'Çanta 2', '<div>Çanta 2</div>', '40.00', '3481215_bej-kadin-canta-176451[1].jpg', 1, '2022-08-10 16:31:27', NULL),
(11, 'Terlik 2', '<div>Terlik 2</div>', '20.00', '01[1].jpg', 1, '2022-08-10 16:32:38', '2022-08-10 16:33:04'),
(12, 'Terlik 3', '<div>Terlik 3</div>', '12.00', '9959-z2-35-39-terlik-7974-98-K[1].jpg', 1, '2022-08-10 16:33:40', NULL),
(13, 'Kıyafet', '<div>Kıyafet</div>', '100.00', 'images[1].bin', 1, '2022-08-10 16:35:00', NULL),
(14, 'Kıyafet 2', '<div>Kıyafet 2</div>', '17.00', 'h54452-classics-natural-dye-kapusonlu-ust-637846725533982007[1].jpg', 1, '2022-08-10 16:36:05', NULL),
(15, 'Çocuk Ayakkabı', '<div>Çocuk Ayakkabı</div>', '18.00', 'remind-cocuk-sneaker-cocuk-ayakkabi__0860388733490695[1].jpg', 1, '2022-08-10 16:37:52', NULL),
(17, 'Çocuk Kıyafet', '<div>Çocuk Kıyafet</div>', '60.00', 'kiz-cocuk-lila-mickey-mouse-esofman-ta-0f2bdf[1].jpg', 1, '2022-08-10 16:40:05', NULL),
(18, 'Çocuk Kıyafet 2', '<div>Çocuk Kıyafet 2</div>', '72.00', 'kiz-cocuk-abiye-7[1].png', 1, '2022-08-10 16:40:47', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_category`
--

DROP TABLE IF EXISTS `product_category`;
CREATE TABLE IF NOT EXISTS `product_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`),
  KEY `IDX_CDFC73564584665A` (`product_id`),
  KEY `IDX_CDFC735612469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
(6, 12),
(7, 9),
(7, 10),
(7, 15),
(8, 9),
(8, 14),
(9, 9),
(9, 13),
(10, 9),
(10, 14),
(11, 9),
(11, 15),
(12, 10),
(12, 15),
(13, 10),
(13, 11),
(13, 13),
(14, 10),
(14, 13),
(15, 16),
(15, 17),
(17, 13),
(17, 16),
(17, 18),
(18, 13),
(18, 16),
(18, 18);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `lastname`, `name`) VALUES
(11, 'admin@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$PRkmSMSoZk2ErP.D2uT0/eIBLZSLwEF5b61v.D./2jRbMG9BwEWxm', 'admin', 'admin'),
(12, 'test@gmail.com', '[]', '$2y$13$cZFJ/Hxf.d.WPExXMdA7Eu.W9xx.IebZR8cqYzQDNfFVqYB8QZou2', 'test', 'test');

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `basket`
--
ALTER TABLE `basket`
  ADD CONSTRAINT `FK_2246507BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Tablo kısıtlamaları `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_64C19C13D3D2749` FOREIGN KEY (`children_id`) REFERENCES `category` (`id`);

--
-- Tablo kısıtlamaları `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `FK_CDFC735612469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CDFC73564584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
