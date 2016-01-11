-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.5.45 - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- Дамп данных таблицы catalogmaterial.migration: ~11 rows (приблизительно)
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` (`version`, `apply_time`) VALUES
	('m000000_000000_base', 1452510447),
	('m140209_132017_init', 1452510455),
	('m140403_174025_create_account_table', 1452510458),
	('m140504_113157_update_tables', 1452510462),
	('m140504_130429_create_token_table', 1452510463),
	('m140830_171933_fix_ip_field', 1452510463),
	('m140830_172703_change_account_table_name', 1452510463),
	('m141222_110026_update_ip_field', 1452510464),
	('m141222_135246_alter_username_length', 1452510464),
	('m150614_103145_update_social_account_table', 1452510466),
	('m150623_212711_fix_username_notnull', 1452510466);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;

-- Дамп данных таблицы catalogmaterial.profile: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
INSERT INTO `profile` (`user_id`, `name`, `public_email`, `gravatar_email`, `gravatar_id`, `location`, `website`, `bio`) VALUES
	(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;

-- Дамп данных таблицы catalogmaterial.social_account: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `social_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_account` ENABLE KEYS */;

-- Дамп данных таблицы catalogmaterial.token: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
/*!40000 ALTER TABLE `token` ENABLE KEYS */;

-- Дамп данных таблицы catalogmaterial.user: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `username`, `email`, `password_hash`, `auth_key`, `confirmed_at`, `unconfirmed_email`, `blocked_at`, `registration_ip`, `created_at`, `updated_at`, `flags`) VALUES
	(1, 'admin', 'dox07@mail.ru', '$2y$10$QRybwOjJ9ZIH1IMEJBkH3eNFhdfYkvBJNYzTy96vlh0pZ9cAvSyWi', 'tu6hXzWoGSBlYl984ONWuE-zgUYCnRAf', 1452511051, NULL, NULL, NULL, 1452511051, 1452511051, 0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
