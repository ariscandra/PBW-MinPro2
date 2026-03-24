CREATE DATABASE IF NOT EXISTS `minpro_portfolio`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `minpro_portfolio`;

DROP TABLE IF EXISTS `sertifikat`;
DROP TABLE IF EXISTS `pengalaman`;
DROP TABLE IF EXISTS `skills`;
DROP TABLE IF EXISTS `profil`;

CREATE TABLE `profil` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_lengkap` VARCHAR(120) NOT NULL,
  `tagline` TEXT NOT NULL,
  `deskripsi_singkat` TEXT NOT NULL,
  `deskripsi_diri` TEXT NOT NULL,
  `github_url` VARCHAR(255) NOT NULL,
  `hero_image` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `skills` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `persen` TINYINT UNSIGNED NOT NULL,
  `urutan` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  CONSTRAINT `chk_skills_persen` CHECK (`persen` BETWEEN 0 AND 100)
) ENGINE=InnoDB;

CREATE TABLE `pengalaman` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `deskripsi` TEXT NOT NULL,
  `urutan` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `sertifikat` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(150) NOT NULL,
  `penerbit` VARCHAR(150) NOT NULL,
  `tahun` VARCHAR(10) NOT NULL,
  `gambar` VARCHAR(255) NOT NULL,
  `urutan` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `profil`
(`nama_lengkap`, `tagline`, `deskripsi_singkat`, `deskripsi_diri`, `github_url`, `hero_image`, `is_active`)
VALUES
(
  'Aris Candra Muzaffar',
  'وَالْعِلْمُ فِي الْكِبَرِ كَالنَّقْشِ عَلَى الْمَاءِ الْعِلْمُ فِي الصِّغَرِ كَالنَّقْشِ عَلَى الْحَجَرِ',
  'Manusia ini lahir dari negeri sebelah, tumbuh di sini, jelajahi jawa guna cari bekal agama. Sekarang, he''s just tryna get by, day by day.',
  'I describe myself as an unimaginative creature with severely lacking motivation trying to learn more about the world.',
  'https://github.com/ariscandra',
  'assets/gua.png',
  1
);

INSERT INTO `skills` (`nama`, `persen`, `urutan`, `is_active`) VALUES
('Survival', 5, 1, 1),
('UI/UX Design', 10, 2, 1),
('Leadership', 75, 3, 1),
('Adaptation', 100, 4, 1);

INSERT INTO `pengalaman` (`deskripsi`, `urutan`, `is_active`) VALUES
('Kepala Departemen Publikasi Komunitas Ilmiah Santri (KIS) Pondok Pesantren Al-Islam — 2018/2019', 1, 1),
('Sekretaris Bidang Keagamaan Organisasi Pengurus Madrasah Al-Islam (OPMI) Pondok Pesantren Al-Islam — 2019/2020', 2, 1),
('Ketua Muhadhoroh Ar-Rahman Pondok Pesantren Al-Islam — 2019/2020', 3, 1),
('Ketua Panitia Program Kerja Bedah Buku Bersama Kak Akbar Trio Mashuri KIS PP Al-Islam — 2019', 4, 1),
('Staff Departemen COMINFO Information System Association — 2025', 5, 1);

INSERT INTO `sertifikat` (`nama`, `penerbit`, `tahun`, `gambar`, `urutan`, `is_active`) VALUES
('Gaya Balatro', 'Portfolio', '2025', 'assets/serti/gaya_balatro.png', 1, 1),
('Gaya DC', 'Portfolio', '2025', 'assets/serti/gaya_dc.png', 2, 1),
('Gaya Stardew', 'Portfolio', '2025', 'assets/serti/gaya_stardew.png', 3, 1),
('Gaya Balatro', 'Portfolio', '2025', 'assets/serti/gaya_balatro.png', 4, 1),
('Gaya DC', 'Portfolio', '2025', 'assets/serti/gaya_dc.png', 5, 1),
('Gaya Stardew', 'Portfolio', '2025', 'assets/serti/gaya_stardew.png', 6, 1);
