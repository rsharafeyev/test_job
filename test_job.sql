-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 07 2020 г., 20:13
-- Версия сервера: 5.1.61
-- Версия PHP: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `test_job`
--

DELIMITER $$
--
-- Процедуры
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `EnableSubscriberService`(IN `aSubscriberServiceId` INT(8))
    NO SQL
BEGIN
	DECLARE aSubscriberId, aServiceId, aRadCheckId INT(8) DEFAULT 0;
	DECLARE aEnabled, aCause TINYINT DEFAULT 0;
	SELECT `subscriber_id` INTO aSubscriberId FROM `subscribers_services` WHERE `id` = aSubscriberServiceId LIMIT 1;
	SELECT `service_id` INTO aServiceId FROM `subscribers_services` WHERE `id` = aSubscriberServiceId LIMIT 1;
	SELECT `radcheck_id` INTO aRadCheckId FROM `subscribers_services` WHERE `id` = aSubscriberServiceId LIMIT 1;
	SELECT `enabled` INTO aEnabled FROM `subscribers_services` WHERE `id` = aSubscriberServiceId LIMIT 1;
	SELECT `cause` INTO aCause FROM `subscribers_services` WHERE `id` = aSubscriberServiceId LIMIT 1;


	IF aSubscriberId > 0 AND aServiceId > 0 AND aEnabled = 0 THEN
		UPDATE `subscribers_services` SET `enabled` = 1, `cause` = 0 WHERE `id` = aSubscriberServiceId LIMIT 1;
		UPDATE `radius`.`radcheck` SET `enabled` = 1, `cause` = 0 WHERE `id` = aRadCheckId LIMIT 1;
		INSERT INTO `subscribers_services_log` (`date_start`, `date_end`, `subscriber_id`, `service_id`, `subscribers_services_id`) VALUES (UNIX_TIMESTAMP(NOW()), 0, aSubscriberId, aServiceId, aSubscriberServiceId);
	END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `access`
--

CREATE TABLE IF NOT EXISTS `access` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user_id` int(6) NOT NULL,
  `mod_id` int(2) NOT NULL,
  `access` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Дамп данных таблицы `access`
--

INSERT INTO `access` (`id`, `user_id`, `mod_id`, `access`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 2),
(36, 22, 2, 1),
(35, 22, 1, 1),
(34, 21, 2, 1),
(33, 21, 1, 1),
(32, 20, 2, 1),
(31, 20, 1, 1),
(30, 19, 2, 1),
(29, 19, 1, 1),
(28, 18, 2, 1),
(27, 18, 1, 1),
(26, 17, 2, 1),
(25, 17, 1, 1),
(24, 16, 2, 1),
(23, 16, 1, 1),
(22, 15, 2, 1),
(21, 15, 1, 1),
(37, 23, 1, 1),
(38, 23, 2, 1),
(39, 24, 1, 1),
(40, 24, 2, 1),
(41, 25, 1, 1),
(42, 25, 2, 1),
(43, 26, 1, 1),
(44, 26, 2, 1),
(45, 27, 1, 1),
(46, 27, 2, 1),
(47, 28, 1, 1),
(48, 28, 2, 1),
(49, 29, 1, 1),
(50, 29, 2, 1),
(51, 30, 1, 1),
(52, 30, 2, 1),
(53, 31, 1, 1),
(54, 31, 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `name_short` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `name_full` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `default` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `languages`
--

INSERT INTO `languages` (`id`, `name_short`, `name_full`, `default`) VALUES
(1, 'РУС', 'Русский', 1),
(2, 'ENG', 'English', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `icon` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'media/img/icon-default.png',
  `order_id` int(2) NOT NULL DEFAULT '0',
  `access_auto_add` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `modules`
--

INSERT INTO `modules` (`id`, `name`, `filename`, `public`, `icon`, `order_id`, `access_auto_add`) VALUES
(1, '<:1001:>', 'status.php', 0, 'media/img/icon-status.png', 1, 1),
(2, '<:1002:>', 'settings.php', 0, 'media/img/icon-settings.png', 9999, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `sess_id` varchar(32) CHARACTER SET utf8 NOT NULL,
  `sess_add` int(10) NOT NULL,
  `sess_act` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `shield_act` int(10) NOT NULL,
  `shield_counter` tinyint(1) NOT NULL,
  `shield_blocked` int(10) NOT NULL,
  `captcha` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`sess_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`sess_id`, `sess_add`, `sess_act`, `user_id`, `shield_act`, `shield_counter`, `shield_blocked`, `captcha`) VALUES
('e38f249c5ef604092b2775c73485781d', 1578403786, 1578405826, 1, 1578403786, 1, 1578403786, 'wuotgx');

-- --------------------------------------------------------

--
-- Структура таблицы `strings`
--

CREATE TABLE IF NOT EXISTS `strings` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `str_id` int(6) NOT NULL,
  `lang_id` tinyint(1) NOT NULL,
  `string` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=121 ;

--
-- Дамп данных таблицы `strings`
--

INSERT INTO `strings` (`id`, `str_id`, `lang_id`, `string`) VALUES
(1, 1, 1, 'Войти'),
(2, 1, 2, 'Login'),
(3, 2, 1, 'Создать аккаунт'),
(4, 2, 2, 'Create an account'),
(5, 3, 1, 'Добро пожаловать в'),
(6, 3, 2, 'Welcome to'),
(7, 4, 1, 'Для получения доступа к системе, необходимо авторизироваться, используя свои имя пользователя и пароль'),
(8, 4, 2, 'For access to the system, you must login using your username and password'),
(9, 5, 1, 'Имя пользователя'),
(10, 5, 2, 'Username'),
(11, 6, 1, 'Пароль'),
(12, 6, 2, 'Password'),
(13, 7, 1, 'Создайте аккаунт'),
(14, 7, 2, 'Create an account for'),
(15, 8, 1, 'Для создания нового аккаунта, необходимо заполнить все поля.'),
(16, 8, 2, 'To create a new account, you must fill all fields.'),
(17, 9, 1, 'Имя'),
(18, 9, 2, 'First name'),
(19, 10, 1, 'Фамилия'),
(20, 10, 2, 'Last name'),
(21, 11, 1, 'Фото профиля'),
(22, 11, 2, 'Profile photo'),
(23, 12, 1, 'e-mail'),
(24, 12, 2, 'e-mail'),
(25, 13, 1, 'Подтвердить'),
(26, 13, 2, 'Confirm'),
(27, 14, 1, 'Защитный код'),
(28, 14, 2, 'Security code'),
(29, 15, 1, 'Обновить защитный код'),
(30, 15, 2, 'Refresh security code'),
(31, 16, 1, 'В поле <b>Имя пользователя</b> допускаются латинские буквы, числа, а также "_" и "-". Количество символов от 3 до 16.'),
(32, 16, 2, 'In field <b>Username</b> allowed only: latin letters, numbers,  "-" and "_". Length: 3-16 symbols.'),
(33, 17, 1, 'В поле <b>Имя</b> допускаются латинские и русские буквы, числа, а также "_" и "-". Количество символов от 2 до 32.'),
(34, 17, 2, 'In field <b>First name</b> allowed only: latin and russian letters, numbers,  "_" and "-". Length: 2 - 32 symbols.'),
(35, 18, 1, 'В поле <b>Фамилия</b> допускаются латинские и русские буквы, числа, а также "_" и "-". Количество символов от 2 до 32.'),
(36, 18, 2, 'In field <b>Last name</b> allowed only: latin and russian letters, numbers,  "_" and "-". Length: 2 - 32 symbols.'),
(37, 19, 1, 'E-mail указан не верно.'),
(38, 19, 2, 'Email is not valid.'),
(39, 20, 1, 'В поле <b>Пароль</b> допускаются латинские буквы в нижнем и в верхнем регистре, числа, а также спецсимволы. Количество символов от 6 до 32.'),
(40, 20, 2, 'In field <b>Password</b> allowed only: latin letters in lower and upper case, numbers, and also special characters. Length: 6 - 32.'),
(41, 21, 1, 'В поле <b>Защитный код</b> допускаются латинские буквы и числа. Количество символов 6.'),
(42, 21, 2, 'In field <b>Captcha</b> allowed latin letters and numbers. Number of symbols 6.'),
(43, 22, 1, 'Отмена'),
(44, 22, 2, 'Cancel'),
(45, 23, 1, 'Вход в систему'),
(46, 23, 2, 'Login to the system'),
(47, 24, 1, 'Создать новый аккаунт'),
(48, 24, 2, 'Create new account'),
(49, 25, 1, 'Доступ запрещен, поскольку Вы ввели неверное имя пользователя или пароль.'),
(50, 25, 2, 'Access is denied because you entered an invalid username or password.'),
(51, 26, 1, 'Это имя пользователя уже занято. Попробуйте другое.'),
(52, 26, 2, 'This username is already used. Try another username.'),
(53, 27, 1, 'Проверьте правильность своих имени и фамилии.'),
(54, 27, 2, 'Check the correctness of your first and last name.'),
(55, 28, 1, '<b>Пароли</b> не совпадают. Повторите попытку.'),
(56, 28, 2, '<b>Passwords</b> do not match. Try again.'),
(57, 29, 1, 'Введен не верный защитный код. Повторите попытку.'),
(58, 29, 2, 'Incorrect captcha entered. Try again.'),
(59, 30, 1, 'Размер фото не должен превышать'),
(60, 30, 2, 'Size of the photo should not exceed'),
(61, 31, 1, 'Неверный формат фото. Допустимы: JPG, GIF, PNG'),
(62, 31, 2, 'Invalid photo format. Allowed: JPG, GIF, PNG'),
(63, 32, 1, 'Ваш аккаунт успешно создан, теперь вы можете авторизироваться.'),
(64, 32, 2, 'Your account has been successfully created, now you can log in.'),
(65, 33, 1, 'Произошла непредвиденная ошибка, обратитесь к системному администратору.'),
(66, 33, 2, 'An unexpected error has occurred, contact your system administrator.'),
(67, 34, 1, 'Ваш профайл успешно обновлен.'),
(68, 34, 2, 'Your profile has been updated successfully.'),
(69, 35, 1, 'Введен не верный текущий пароль.'),
(70, 35, 2, 'Invalid current password.'),
(71, 36, 1, 'Пароль успешно изменен.'),
(72, 36, 2, 'Password changed successfully.'),
(73, 37, 1, 'Выход'),
(74, 37, 2, 'Logout'),
(75, 38, 1, 'Аккаунт'),
(76, 38, 2, 'Account'),
(77, 39, 1, 'Здравствуйте!'),
(78, 39, 2, 'Hello!'),
(79, 40, 1, 'Вы вошли как'),
(80, 40, 2, 'You are logged as'),
(81, 1001, 1, 'Статус'),
(82, 1001, 2, 'Status'),
(83, 1002, 1, 'Настройки'),
(84, 1002, 2, 'Settings'),
(85, 41, 1, 'Язык интерфейса'),
(86, 41, 2, 'Interface language'),
(87, 42, 1, 'Разработчик'),
(88, 42, 2, 'Developer'),
(89, 43, 1, 'Шарафеев Ринат'),
(90, 43, 2, 'Sharafeyev Rinat'),
(91, 44, 1, 'Тел. (сот.)'),
(92, 44, 2, 'Phone (mob.)'),
(93, 45, 1, 'Разработка WEB-Сайтов, Автоматизированных систем управления, Баз данных и другого программного обеспечения.'),
(94, 45, 2, 'Development of WEB-Sites, Automated control systems, Databases and other software.'),
(95, 46, 1, 'Закрыть'),
(96, 46, 2, 'Close'),
(97, 47, 1, 'Для навигации по системе используйте главное меню.'),
(98, 47, 2, 'To navigate the system, use the main menu.'),
(99, 48, 1, 'Профиль'),
(100, 48, 2, 'Profile'),
(101, 49, 1, 'Редактирование профиля.'),
(102, 49, 2, 'Editing a profile'),
(103, 50, 1, 'невозможно изменить'),
(104, 50, 2, 'impossible to change'),
(105, 51, 1, 'Применить'),
(106, 51, 2, 'Apply'),
(107, 52, 1, 'Изменить пароль.'),
(108, 52, 2, 'Change Password.'),
(109, 53, 1, 'Текущий пароль'),
(110, 53, 2, 'Current password'),
(111, 54, 1, 'Новый пароль'),
(112, 54, 2, 'New password'),
(113, 55, 1, 'В поле <b>Текущий пароль</b> допускаются латинские буквы в нижнем и в верхнем регистре, числа, а также спецсимволы. Количество символов от 6 до 32.'),
(114, 55, 2, 'In field <b>Current password</b> allowed only: latin letters in lower and upper case, numbers, and also special characters. Length: 6 - 32.'),
(115, 56, 1, 'В поле <b>Новый пароль</b> допускаются латинские буквы в нижнем и в верхнем регистре, числа, а также спецсимволы. Количество символов от 6 до 32.'),
(116, 56, 2, 'In field <b>New password</b> allowed only: latin letters in lower and upper case, numbers, and also special characters. Length: 6 - 32.'),
(117, 57, 1, 'Значение полей <b>${name}</b> и <b>${name2}</b> не равны.'),
(118, 57, 2, 'Values of the fields <b>${name}</b> and <b>${name2}</b> not equal.'),
(119, 58, 1, 'Показать фото'),
(120, 58, 2, 'Show photo');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `firstName` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `global_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `firstName`, `lastName`, `photo`, `global_admin`) VALUES
(1, 'admin', 'edc290bc0c0acfdc22374b2d155bd7d3', 'r.sharafeyev@gmail.com', 'Rinat', 'Sharafeyev', '374ae8be7f40b869d8c3d49cafa75215.jpg', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
