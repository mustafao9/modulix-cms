-- Modulix-CMS Clean Release Schema
-- Version: v1.0.4
-- Generated: 2026-09-15 21:12:06

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `audit_loglari`;
CREATE TABLE `audit_loglari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kullanici_id` int(11) DEFAULT 0,
  `kullanici_adi` varchar(50) DEFAULT NULL,
  `islem` varchar(100) DEFAULT NULL,
  `detay` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `tarih` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `ayarlar`;
CREATE TABLE `ayarlar` (
  `anahtar` varchar(100) NOT NULL,
  `deger` text DEFAULT NULL,
  PRIMARY KEY (`anahtar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `blog_yazilari`;
CREATE TABLE `blog_yazilari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `baslik` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icerik` text NOT NULL,
  `tarih` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `guvenlik_engellenen_ipler`;
CREATE TABLE `guvenlik_engellenen_ipler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `sebep` text NOT NULL,
  `tarih` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `guvenlik_saldirilari`;
CREATE TABLE `guvenlik_saldirilari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `istek_yolu` text NOT NULL,
  `saldiri_turu` varchar(255) NOT NULL,
  `tarih` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `ip_engelleri`;
CREATE TABLE `ip_engelleri` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `sebep` varchar(255) DEFAULT NULL,
  `tarih` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `kategoriler`;
CREATE TABLE `kategoriler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `baslik` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `dil` varchar(5) DEFAULT 'tr',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `kullanicilar`;
CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kullanici_adi` varchar(50) NOT NULL,
  `eposta` varchar(100) NOT NULL,
  `sifre` varchar(255) DEFAULT NULL,
  `google_id` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `rol` enum('super_admin','admin','editor','uye') DEFAULT 'uye',
  `hesap_durumu` enum('aktif','pasif','yasakli') DEFAULT 'aktif',
  `eposta_dogrulandi` tinyint(1) DEFAULT 0,
  `dogrulama_tokeni` varchar(100) DEFAULT NULL,
  `sifre_sifirlama_tokeni` varchar(100) DEFAULT NULL,
  `sifre_sifirlama_tarih` datetime DEFAULT NULL,
  `basarisiz_giris_sayisi` int(11) DEFAULT 0,
  `son_giris_tarihi` datetime DEFAULT NULL,
  `kayit_tarihi` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kullanici_adi` (`kullanici_adi`),
  UNIQUE KEY `eposta` (`eposta`),
  UNIQUE KEY `google_id` (`google_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `medya`;
CREATE TABLE `medya` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dosya_adi` varchar(255) NOT NULL,
  `dosya_yolu` varchar(255) NOT NULL,
  `dosya_tipi` varchar(50) DEFAULT NULL,
  `dosya_boyutu` varchar(20) DEFAULT NULL,
  `yukleme_tarihi` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `menuler`;
CREATE TABLE `menuler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `baslik` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sira` int(11) DEFAULT 0,
  `dil` varchar(5) DEFAULT 'tr',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `saldiri_loglari`;
CREATE TABLE `saldiri_loglari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) DEFAULT NULL,
  `istek_yolu` text DEFAULT NULL,
  `saldiri_turu` varchar(100) DEFAULT NULL,
  `tarih` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `sayfalar`;
CREATE TABLE `sayfalar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `baslik` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icerik` longtext DEFAULT NULL,
  `durum` varchar(20) DEFAULT 'yayinda',
  `dil` varchar(5) DEFAULT 'tr',
  `olusturma_tarihi` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `sistem_ayarlar`;
CREATE TABLE `sistem_ayarlar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anahtar` varchar(100) NOT NULL,
  `deger` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `anahtar` (`anahtar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sistem_loglari`;
CREATE TABLE `sistem_loglari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kullanici_adi` varchar(50) DEFAULT NULL,
  `islem` text NOT NULL,
  `ip_adresi` varchar(45) DEFAULT NULL,
  `tarih` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `yazilar`;
CREATE TABLE `yazilar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) DEFAULT 0,
  `baslik` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `ozet` text DEFAULT NULL,
  `icerik` longtext DEFAULT NULL,
  `resim` varchar(255) DEFAULT NULL,
  `durum` varchar(20) DEFAULT 'yayinda',
  `dil` varchar(5) DEFAULT 'tr',
  `okunma` int(11) DEFAULT 0,
  `olusturma_tarihi` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
