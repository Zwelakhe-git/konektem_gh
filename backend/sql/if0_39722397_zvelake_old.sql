-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: sql101.infinityfree.com
-- Время создания: Май 18 2026 г., 16:02
-- Версия сервера: 11.4.10-MariaDB
-- Версия PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `if0_39722397_zvelake`
--

-- --------------------------------------------------------
-- DROP DATABASE `if0_39722397_zvelake`;
-- CREATE DATABASE `if0_39722397_zvelake`;
-- GRANT ALL ON `if0_39722397_zvelake`.* TO 'if0_39722397'@'%';
--
-- Структура таблицы `albums`
--

CREATE TABLE `albums` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `songs_count` int(11) DEFAULT NULL,
  `release_year` date NOT NULL,
  `owner` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `downloads` int(11) DEFAULT 0,
  `likes` int(11) DEFAULT 0,
  `shares` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `albums`
--

INSERT INTO `albums` (`id`, `name`, `image_url`, `songs_count`, `release_year`, `owner`, `user_id`, `description`, `downloads`, `likes`, `shares`) VALUES
(11, 'my album', '/konektem/config/../uploads/zvelake_album_myalbum/69d2965326642.png', 1, '2026-04-05', 'zvelake', 49, 'album', 2, 1, 1),
(14, 'Vwa Yo Pa Tande Yo', '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45879c.jpg', 4, '2026-04-07', 'masekozw', 49, '« Vwa Yo Pa Tande Yo » constitue la troisième production musicale de la Fondation Frère\r\nLuckson Zòn Pa Fò Moun.\r\nL\'album comprend douze titres originaux, interprétés par des enfants et adolescents issus de milieux vulnérables :\r\nJob, Diaman, Delson, Jonaider, Ketty, Fritzline, Lovemika, Ti Pasté, Ferlando, Ti Batè, BenBen et\r\nBòs Tayè.\r\n\r\nLeurs voix expriment la résilience, la foi et l\'espérance d\'une génération qui refuse de se résigner.', 48, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `api_keys`
--

CREATE TABLE `api_keys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `key_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `date_end` timestamp GENERATED ALWAYS AS (`created_at` + interval 1 month) STORED,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `status` enum('expired','active','cancelled') GENERATED ALWAYS AS (case when `date_end` < TIMESTAMP/*WITH LOCAL TIME ZONE*/'2026-04-12 11:07:13' then 'expired' when `cancelled_at` is not null then 'cancelled' else 'active' end) VIRTUAL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `permissions` set('read','write','delete','insert','update','create') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Artists`
--

CREATE TABLE `Artists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Artists`
--

INSERT INTO `Artists` (`id`, `name`) VALUES
(1, 'Akon'),
(2, 'B.I.G'),
(3, 'Eminem'),
(4, 'The Weekend'),
(5, 'Baky'),
(6, 'Loreen'),
(7, 'konekte m production'),
(8, 'Drake'),
(9, 'Job zòn Pa Fè Moun'),
(10, 'Sabine Lira'),
(11, 'Revelasyon'),
(12, 'Salomon Lira'),
(13, 'Salina Charles & Jean Edner Tézil'),
(14, 'Eglise Roc Solide'),
(15, 'Hillsong Worship'),
(16, 'Daphnée Pierre'),
(17, 'Sherline & Garby'),
(18, 'ValÃ©us Sisters'),
(19, 'APÃ”TRE ROBENSON JOACHIM feat PSALMISTE JABOIN PETERSON'),
(20, 'Clervo Lovenson'),
(21, 'Lovenson Clerveau'),
(22, 'mbm'),
(23, 'Beethoven Chenet'),
(24, 'Pasteur Gregory T'),
(25, 'unknown'),
(26, 'Olam Band'),
(27, 'Zòn Pa Fè Moun Band');

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE `books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `pdfUrl` varchar(255) DEFAULT NULL,
  `owner` varchar(100) DEFAULT 'admin@konektem.net',
  `cover_image` bigint(10) UNSIGNED DEFAULT NULL,
  `linked_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `likes` INT DEFAULT 0,
  `shares` INT DEFAULT 0,
  `views` INT DEFAULT 0,
  `description` TEXT,
  `genre` VARCHAR(100),
) ;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `pdfUrl`, `owner`, `cover_image`, `linked_images`, `description`, `likes`, `shares`, `views`, `genre`) VALUES
(1, 'MES SECRETS POUR UNE VIE RICHE ET EPANOUIE', 'SONY LAMARRE JOSEPH', NULL, NULL, 'admin@konektem.net', 241, NULL, '<p>r&eacute;dig&eacute; pour devenir un ouvrage de r&eacute;f&eacute;rence pour toutes celles et ceux qui aspirent au bien-&ecirc;tre par la mise en valeur de leurs dons et de leurs talents mais qui sont sceptiques quant &agrave; la mat&eacute;rialisation de leurs r&ecirc;ves. L\'auteur a pu se frayer des voies comme tant de personnes ayant des histoires aussi touchantes et inspirantes que la sienne. Il est aussi possible pour beaucoup d&rsquo;autres. Il suffit de savoir ce qu&rsquo;on veut r&eacute;aliser vraiment et faire montre de volont&eacute; et de d&eacute;termination pour y parvenir.</p>', 2, 1, 0, 'fantasy'),
(2, 'hnh', 'hhg', NULL, NULL, 'admin@konektem.net', 248, NULL, '<p>gfhkjg</p>', 2, 2, 0, 'Science'),
(4, 'C Notes for Professionals', 'StackOverflow Community', NULL, NULL, 'admin@konektem.net', 275, NULL, '<p>The&nbsp;<em>C Notes for Professionals</em> book is compiled from &nbsp;Stack Overflow Documentation, the content is written by the beautiful people at Stack Overflow.</p>', 0, 0, 0, 'Science'),
(5, 'ReactNative Notes for Professionals', 'StackOverflow Community', NULL, NULL, 'admin@konektem.net', 276, NULL, '<p>The&nbsp;<em>React Native Notes for Professionals</em> book is compiled from StackOverflow documentation, the content is written by the beautiful people at Stack Overflow.</p>', 0, 0, 0, 'Science'),
(6, 'Redis in Action', 'Josiah L. Carlson', NULL, '/admin/controllers/../uploads/documents/69aaff323c85f.pdf', 'admin@konektem.net', 277, NULL, '<p>This book covers the use of Redis, an in-memory database/data structure server, originally written by Salvatore Sanfilippo, but recently patched through the open source<br>process</p>', 0, 0, 0, 'Science'),
(8, 'The Mark Of Athena', 'Rick Riodan', NULL, '/admin/config/../../admin/uploads/documents/69ab1a45ec81c.pdf', 'admin@konektem.net', 279, NULL, '<p>The Heroes of Olympus, Book Three</p>', 0, 0, 0, 'Fiction');

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id`, `first_name`, `last_name`, `email`, `phone`, `created_at`) VALUES
(4, 'Zvelake', 'maseko', 'maseko.z@edu.spbstu.ru', '+7 (282) 143-51-0', '2025-12-07 20:34:43'),
(5, 'Zvelake', 'maseko', 'zweklakhe.mzwet@gmail.com', '7282143510', '2025-12-10 15:58:15'),
(6, 'jorby', 'Art', 'Jeanjoberne@gmail.com', '', '2025-12-10 18:15:51'),
(7, 'Joberne', 'Jean', 'zwelakhe.mzwet@gmail.com', '+77282143510', '2026-01-31 17:41:08'),
(8, 'ghtth', 'rythryn', 'konektemtv@gmail.com', 'ryhwhrhr', '2026-03-05 17:08:36'),
(9, 'Sheller', 'Valmiro', 'traderrellehs1@gmail.com', '9522653283', '2026-03-05 17:13:27'),
(10, 'Fast ', 'Services ', 'sbfastservices@gmail.com', '34641171', '2026-03-06 13:23:00');

-- --------------------------------------------------------

--
-- Структура таблицы `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `content_json` longtext DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Структура таблицы `document_categories`
--

CREATE TABLE `document_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `document_category_relations`
--

CREATE TABLE `document_category_relations` (
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `document_images`
--

CREATE TABLE `document_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `image_id` bigint(20) UNSIGNED NOT NULL,
  `order_no` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `eventDate` date DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `eventImage` bigint(20) UNSIGNED DEFAULT NULL,
  `owner` varchar(255) NOT NULL DEFAULT 'admin@konektem.net',
  `likes` int(10) DEFAULT 0,
  `shares` int(10) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `eventDate`, `location`, `price`, `eventImage`, `owner`, `likes`, `shares`) VALUES
(4, 'DETANT KRETYEN', 'Randevou chak vandredi nan Dèlma 75 pou w koze ak atis prefere w yo, tande istwa la vi yo, motivasyon ak pasyon yo.', '2025-12-24', 'DELMAS 75', 0, 37, 'admin@konektem.net', 2, 3),
(7, 'STAND UP FOR LYNDA JOSEPH', 'Dans un élan de solidarité qui dépasse les murs du sanctuaire, l’Église Rendez-Vous Christ, en collaboration avec Media Levanjil, Konektem, À l’Unisson et Lophane Project, convie le public à un concert exceptionnel le 14 décembre 2025. Proposé au prix symbolique de 1 000 gourdes, cet événement vise à soutenir Lynda Joseph à travers une soirée où musique, foi et compassion s’uniront pour porter un message d’espérance.\r\nLes participants seront également invités à renforcer cet acte de générosité par des dons additionnels, afin de contribuer encore davantage à cette noble cause.', '2025-12-14', 'Rendez-Vous Christ', 2, 128, 'admin@konektem.net', 0, 0),
(8, 'JEZI OOO - NESLIN DESTILHOMME GONAIVES', '<p>Nan vil Gonayiv, yon gwo randevou adorasyon tanmen pare pou make fen ane a. Jou k ap samdi 6 desanm 2025 lan, a 4ï¿½ nan lapremidi, nan Pak Vincent. @neslindestilhomme ap tann nou tout nan yon bouyon adorasyon, ki se Konsï¿½ ï¿½ï¿½Jezi Ohï¿½ï¿½ a. Yon Aktivite ki rasanble plizyï¿½ atis ak gwoup kretyen, tankou Rod Ume Dieujuste, @joyclerfderisier, @loutchina_music, Zï¿½n Pa Fï¿½ Moun Band ak Wency Le Slameur. Caribbean Worshippers envite piblik la, jï¿½n kou granmoun, fanmi kou zanmi, pou pa rate randevou sa a. Si w ap chï¿½che yon kote pou w rekonekte ak Bondye, renouvle lafwa w epi amize w nan prezans Bondye, Konsï¿½ ï¿½ï¿½Jezi Ohï¿½ï¿½, nan Gonayiv se kote w dwe ye a</p>', '2025-12-06', 'GONAIVES', 0, 131, 'admin@konektem.net', 0, 1),
(9, 'SIMIANE RUACH TOUR', 'SIMIANE Ruach Tour. live performance: Jean Jean, Esther Desi, Transfomasyon Band.\r\n\r\n \r\nRéservez Vos billets maintenant sur eventbrite 👇👇\r\nhttps://www.eventbrite.com/e/1966354383895?aff=oddtdtcreator', '2025-12-07', 'NEW JERUSALEM EVANGELICAL BAPTIST CHURCH', 0, 134, 'admin@konektem.net', 0, 0),
(10, '6e Ã©dtion Caribbean Worshippers', '<div class=\"xdj266r x14z9mp xat24cr x1lziwak x1vvkbs x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t1f/2/16/27a1.png\" alt=\"âž¡ï¸\" width=\"16\" height=\"16\"></span> Ce grand rendez-vous spirituel, organis&eacute; par le psalmiste Neslin Destilhomme, connu pour mobiliser des milliers de fid&egrave;les &agrave; chacune de ses initiatives, r&eacute;unira cette ann&eacute;e des adorateurs et des leaders chr&eacute;tiens venus d&rsquo;Ha&iuml;ti, des &Eacute;tats-Unis et du Canada.<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">Cette rencontre spirituelle vise &agrave; cr&eacute;er un espace de louange et de pri&egrave;re favorisant une connexion profonde &agrave; la pr&eacute;sence de Dieu et la croissance spirituelle des participants. La 6áµ‰ &eacute;dition, organis&eacute;e autour du th&egrave;me &laquo; BENI BENI N&Egrave;T &raquo;, annonce une programmation de haut niveau, selon les pr&eacute;cisions de <span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/profile.php?id=100063886113458&amp;__cft__[0]=AZZ9Mi5EY7jV6JhwKdvRWBCP7k2OfAsaKoJMZcqwH5LmR8zYphbZSwYw3yXYdRNrxPxDrQt3-dSNdrUfYKjnASY-XamECrDNSh6UH7EHGW4MooM1fsHkqRyHSbZhQTEOTSKqiKClzeD8QfwO6oVpcVikCRbwuptfY6s5XI932MDEQcTLB_-wgMGaI2GEfWE885A&amp;__tn__=-]K-R\"><span class=\"xt0psk2\"><span class=\"xjp7ctv\">Neslin Destilhomme</span></span></a></span></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">L&rsquo;&eacute;v&eacute;nement rassemblera un panel d&rsquo;adorateurs et de leaders spirituels engag&eacute;s &agrave; conduire le peuple dans un moment intense de communion et d&rsquo;&eacute;dification spirituelle.<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">KONEKTEM s&rsquo;engage &agrave; vous informer de chaque &eacute;tape et de tous les d&eacute;tails de ce grand rendez-vous spirituel. Restez connect&eacute;s.<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t4b/2/16/1f4cc.png\" alt=\"ðŸ“Œ\" width=\"16\" height=\"16\"></span> Informations et dons</div>\r\n<div dir=\"auto\">Zelle : 954 336 9155</div>\r\n<div dir=\"auto\">PayPal : <a href=\"mailto:neslindestilhome87@gmail.com\">neslindestilhome87@gmail.com</a><br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t63/2/16/270d.png\" alt=\"âœï¸\" width=\"16\" height=\"16\"></span> R&eacute;daction : Vanauscheca P. Bouzy<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/hashtag/konektem?__eep__=6&amp;__cft__[0]=AZZ9Mi5EY7jV6JhwKdvRWBCP7k2OfAsaKoJMZcqwH5LmR8zYphbZSwYw3yXYdRNrxPxDrQt3-dSNdrUfYKjnASY-XamECrDNSh6UH7EHGW4MooM1fsHkqRyHSbZhQTEOTSKqiKClzeD8QfwO6oVpcVikCRbwuptfY6s5XI932MDEQcTLB_-wgMGaI2GEfWE885A&amp;__tn__=*NK-R\">#konektem</a></span></div>\r\n<div dir=\"auto\"><span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/hashtag/toutkotenenp%C3%B2tkil%C3%A8?__eep__=6&amp;__cft__[0]=AZZ9Mi5EY7jV6JhwKdvRWBCP7k2OfAsaKoJMZcqwH5LmR8zYphbZSwYw3yXYdRNrxPxDrQt3-dSNdrUfYKjnASY-XamECrDNSh6UH7EHGW4MooM1fsHkqRyHSbZhQTEOTSKqiKClzeD8QfwO6oVpcVikCRbwuptfY6s5XI932MDEQcTLB_-wgMGaI2GEfWE885A&amp;__tn__=*NK-R\">#toutkotenenp&ograve;tkil&egrave;</a></span></div>\r\n</div>', '2026-03-14', 'Nassau, Bahamas', 0, 208, 'admin@konektem.net', 0, 0),
(11, 'worship Room 2e Ã©dition', '<div class=\"xdj266r x14z9mp xat24cr x1lziwak x1vvkbs x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t1f/2/16/27a1.png\" alt=\"âž¡ï¸\" width=\"16\" height=\"16\"></span> Apr&egrave;s une &eacute;dition de 2025 marqu&eacute;e par un v&eacute;ritable succ&egrave;s et une forte mobilisation du public, Worship Room s&rsquo;appr&ecirc;te &agrave; faire son grand retour avec une nouvelle &eacute;dition port&eacute;e par l&rsquo;unit&eacute;, la foi, la reconnaissance et l&rsquo;adoration. <br><br>La deuxi&egrave;me &eacute;dition se tiendra le dimanche 5 avril 2026, &agrave; l&rsquo;H&ocirc;tel Karibe, P&eacute;tion-Ville, autour du th&egrave;me biblique inspir&eacute; de Matthieu 28:6 : &laquo; Il n&rsquo;est point ici ; car Il est ressuscit&eacute;, comme Il l&rsquo;avait dit. &raquo;<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">L&rsquo;an dernier, &agrave; l&rsquo;occasion de la f&ecirc;te de P&acirc;ques, l&rsquo;&eacute;quipe du Worship Room avait propos&eacute; un programme simple, mais pr&eacute;par&eacute; avec soin. Dans une salle soigneusement d&eacute;cor&eacute;e et comble, adorateurs, artistes, pasteurs, invit&eacute;s et fid&egrave;les avaient v&eacute;cu un moment de communion profonde, marqu&eacute; par une pr&eacute;sence divine ressentie intens&eacute;ment.<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">Cette ann&eacute;e encore, les organisateurs annoncent une rencontre fid&egrave;le &agrave; l&rsquo;ADN de Worship Room : authentique, intense et spirituellement transformatrice. Les autres d&eacute;tails du programme seront communiqu&eacute;s ult&eacute;rieurement.<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">KONEKTEM assurera la couverture exclusive de cet &eacute;v&eacute;nement et vous tiendra inform&eacute;s de son &eacute;volution en temps r&eacute;el. <br><br>Restez connect&eacute;s !<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t63/2/16/270d.png\" alt=\"âœï¸\" width=\"16\" height=\"16\"></span> R&eacute;daction : Vanauscheca P. Bouzy<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/hashtag/konektem?__eep__=6&amp;__cft__[0]=AZZm_vAePuBYZ6OOfa0sdcT27F4sBNF990pyQAt6KrcpI-WAxxFQm2eJSEC9K9BuqS4sYPGMlOiZUnuPc82yfHgFeTrhmsPu2EZm6jq8PYs9rVZei_oD83RjXuE07_nvfeyd66BSrvDwJ0BIc50t2l8xcIDTHTp8tZD5HT0JJL2ycfV24LhnRO75bkg1IFVPmlA&amp;__tn__=*NK-R\">#KONEKTEM</a></span></div>\r\n<div dir=\"auto\"><span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/hashtag/toutkotenenp%C3%B2tkil%C3%A8?__eep__=6&amp;__cft__[0]=AZZm_vAePuBYZ6OOfa0sdcT27F4sBNF990pyQAt6KrcpI-WAxxFQm2eJSEC9K9BuqS4sYPGMlOiZUnuPc82yfHgFeTrhmsPu2EZm6jq8PYs9rVZei_oD83RjXuE07_nvfeyd66BSrvDwJ0BIc50t2l8xcIDTHTp8tZD5HT0JJL2ycfV24LhnRO75bkg1IFVPmlA&amp;__tn__=*NK-R\">#ToutKoteNenp&ograve;tKil&egrave;</a></span></div>\r\n</div>', '2026-04-05', 'Karibe HÃ´tel', 0, 210, 'admin@konektem.net', 0, 0),
(12, 'Samedi de la GrÃ¢ce', '<div class=\"xdj266r x14z9mp xat24cr x1lziwak x1vvkbs x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t1f/2/16/27a1.png\" alt=\"âž¡ï¸\" width=\"16\" height=\"16\"></span>D&eacute;j&agrave; tr&egrave;s attendu, ce rendez-vous figure parmi les &eacute;v&eacute;nements majeurs du mois de f&eacute;vrier au sein de la communaut&eacute; &eacute;vang&eacute;lique ha&iuml;tienne.</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">Cette deuxi&egrave;me &eacute;dition entend poursuivre les m&ecirc;mes objectifs : valoriser les talents artistiques chr&eacute;tiens, encourager l&rsquo;expression culturelle positive et diffuser un message fort de paix, d&rsquo;unit&eacute; et d&rsquo;esp&eacute;rance, dans un contexte social o&ugrave; les rep&egrave;res demeurent essentiels.</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">Le programme vise &eacute;galement &agrave; mettre en lumi&egrave;re les talents artistiques chr&eacute;tiens, tout en promouvant un message de paix, d&rsquo;unit&eacute; et d&rsquo;esp&eacute;rance, dans un contexte social marqu&eacute; par de nombreux d&eacute;fis.</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">La premi&egrave;re &eacute;dition de &laquo; Samedi de la Gr&acirc;ce &raquo; avait mobilis&eacute; des centaines de milliers de participants, en pr&eacute;sentiel comme en ligne, autour de moments de louange, d&rsquo;adoration et d&rsquo;action de gr&acirc;ce, r&eacute;unissant fid&egrave;les, organisateurs et partenaires dans un m&ecirc;me &eacute;lan spirituel.</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tf0/2/16/1f449.png\" alt=\"ðŸ‘‰\" width=\"16\" height=\"16\"></span> Plus de d&eacute;tails seront communiqu&eacute;s prochainement.</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">Pour le sponsoring et les partenariats :</div>\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tec/2/16/1f4de.png\" alt=\"ðŸ“ž\" width=\"16\" height=\"16\"></span> +509 38 48 70 85 | 31 22 33 37 | 37 55 84 26</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t63/2/16/270d.png\" alt=\"âœï¸\" width=\"16\" height=\"16\"></span> R&eacute;daction : Vanauscheca P. Bouzy</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/hashtag/konektem?__eep__=6&amp;__cft__[0]=AZbqAYqCCQiggpzcnz7g5RIVGuw7Q6O5hXz2j4J3mBSVZ4PXuRX8AQWnWVBxhWqoOK0Z_S35n3i44kDEepy9MXWjEchG9j3eSXog1kqaqCYu6XThaJxMDRTtfVNhOdotV8lMG8ioorGlQLv17bJ6m2ZFTFkJAg1EIfjJlTQ4mMYoewJAF6RsQA1g_D-wdEDLzwI&amp;__tn__=*NK-R\">#Konektem</a></span></div>\r\n<div dir=\"auto\"><span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/hashtag/toutkotenenpotkil%C3%A8?__eep__=6&amp;__cft__[0]=AZbqAYqCCQiggpzcnz7g5RIVGuw7Q6O5hXz2j4J3mBSVZ4PXuRX8AQWnWVBxhWqoOK0Z_S35n3i44kDEepy9MXWjEchG9j3eSXog1kqaqCYu6XThaJxMDRTtfVNhOdotV8lMG8ioorGlQLv17bJ6m2ZFTFkJAg1EIfjJlTQ4mMYoewJAF6RsQA1g_D-wdEDLzwI&amp;__tn__=*NK-R\">#ToutKotenenpotKil&egrave;</a></span></div>\r\n</div>', '2026-02-21', 'Henfrasa, Delmas 33, Port-au-Prince HaÃ¯ti', 0, 212, 'admin@konektem.net', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `example`
--

CREATE TABLE `example` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(10) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Images`
--

CREATE TABLE `Images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Images`
--

INSERT INTO `Images` (`id`, `location`, `mime_type`) VALUES
(6, '/media/../media/images/img_68a347d16ed789.43623933_eminem-inner-war-vevo.jpg', 'image/png'),
(7, '/media/../media/images/img_68b3259fb0c965.19402829_wayne-popItOff.jpg', 'image/png'),
(8, '/media/images/services/photo_5084623735641279373_x.jpg', NULL),
(9, '/media/images/services/photo_5084623735641279375_x.jpg', NULL),
(10, '/media/images/services/photo_5084623735641279374_x.jpg', NULL),
(11, '/media/images/services/photo_5084623735641279381_x.jpg', NULL),
(12, '/media/images/services/photo_5084623735641279376_y.jpg', NULL),
(13, '/media/images/services/photo_5116125860468558650_x.jpg', NULL),
(31, '/admin/controllers/../uploads/images/68f57ba9d701e.jpg', 'image/jpeg'),
(32, '/admin/controllers/../uploads/images/68f57e265793f.jpg', 'image/jpeg'),
(37, '/admin/controllers/../uploads/images/68f58b62e8e37.jpg', 'image/jpeg'),
(40, '/admin/controllers/../uploads/images/68f58bd3ce1b4.jpg', 'image/jpeg'),
(41, '/admin/controllers/../uploads/images/68f58d72ad078.jpg', 'image/jpeg'),
(42, '/admin/controllers/../uploads/images/68f58d977252f.jpg', 'image/jpeg'),
(43, '/admin/controllers/../uploads/images/68f58db5ba79e.jpg', 'image/jpeg'),
(44, '/admin/controllers/../uploads/images/68f58dc0f0f17.jpg', 'image/jpeg'),
(45, '/admin/controllers/../uploads/images/68f58dcf33216.jpg', 'image/jpeg'),
(46, '/admin/controllers/../uploads/images/68fa8d4b0cb2d.jpg', 'image/jpeg'),
(53, '/admin/controllers/../uploads/images/68fbfd61ad3fd.jpg', 'image/jpeg'),
(54, '/admin/controllers/../uploads/images/68fbfedf3ec45.jpg', 'image/jpeg'),
(55, '/admin/controllers/../uploads/images/68fc044561dfb.jpg', 'image/jpeg'),
(56, '/admin/controllers/../uploads/images/68fc052d3894f.jpg', 'image/jpeg'),
(57, '/admin/controllers/../uploads/images/68fc05de56d42.jpg', 'image/jpeg'),
(58, '/admin/controllers/../uploads/images/68fc0655a4a8c.jpg', 'image/jpeg'),
(59, '/admin/controllers/../uploads/images/68fc06d212459.jpg', 'image/jpeg'),
(60, '/admin/controllers/../uploads/images/68fc078198298.jpeg', 'image/jpeg'),
(61, '/admin/controllers/../uploads/images/68fc07e2b2123.jpg', 'image/jpeg'),
(62, '/admin/controllers/../uploads/images/68fc083c70064.jpg', 'image/jpeg'),
(70, '/admin/controllers/../uploads/images/68fd98aa07ac4.png', 'image/png'),
(73, '/admin/controllers/../uploads/images/69014401d3712.avif', 'image/avif'),
(74, '/admin/controllers/../uploads/images/6901459b8a0a6.avif', 'image/avif'),
(79, '/admin/controllers/../uploads/images/69026e5831437.webp', 'image/webp'),
(83, '/admin/controllers/../uploads/images/6902d96c4db06.jpeg', 'image/jpeg'),
(84, '/admin/controllers/../uploads/images/69052f1d6bc1e.jpeg', 'image/jpeg'),
(85, '/admin/controllers/../uploads/images/69082eeebe2de.jpeg', 'image/jpeg'),
(86, '/admin/controllers/../uploads/partners/690a3d9945be5.jpg', 'image/jpeg'),
(87, '/admin/controllers/../uploads/partners/690a47bdd740b.jpg', 'image/jpeg'),
(88, '/admin/controllers/../uploads/partners/690a47d4e26e7.jpg', 'image/jpeg'),
(96, '/admin/controllers/../uploads/images/690d3d52e334a.jpg', 'image/jpeg'),
(97, '/admin/controllers/../uploads/images/690d41b68c2a5.jpg', 'image/jpeg'),
(98, '/admin/controllers/../uploads/images/690d42d855eea.jpg', 'image/jpeg'),
(113, '/admin/controllers/../uploads/partners/691e1ce1b84ec.png', 'image/png'),
(114, '/admin/controllers/../uploads/images/692084bdbdc1c.jpeg', 'image/jpeg'),
(115, '/admin/controllers/../uploads/images/6920b08dcb2e4.jpeg', 'image/jpeg'),
(116, '/admin/controllers/../uploads/images/692121f7dbf74.jpeg', 'image/jpeg'),
(117, '/admin/controllers/../uploads/partners/692311df8b03a.png', 'image/png'),
(118, '/admin/controllers/../uploads/partners/69231251267ca.png', 'image/png'),
(119, '/admin/controllers/../uploads/partners/6923127964c88.avif', 'image/avif'),
(121, '/admin/controllers/../uploads/images/692351a071e04.jpg', 'image/jpeg'),
(123, '/admin/controllers/../uploads/images/69237171e079b.jpg', 'image/jpeg'),
(125, '/admin/controllers/../uploads/images/692371eddab4c.jpg', 'image/jpeg'),
(126, '125', NULL),
(127, '/admin/controllers/../uploads/images/6923720254a9a.jpg', 'image/jpeg'),
(128, '/admin/controllers/../uploads/images/692372192a2ca.jpg', 'image/jpeg'),
(129, '/admin/controllers/../uploads/images/69237304c1878.jpg', 'image/jpeg'),
(130, '129', NULL),
(131, '/admin/controllers/../uploads/images/69237315a8e1b.jpg', 'image/jpeg'),
(132, '/admin/controllers/../uploads/images/692373b60bbdd.jpg', 'image/jpeg'),
(133, '132', NULL),
(134, '/admin/controllers/../uploads/images/692373c4c0d96.jpg', 'image/jpeg'),
(135, '/admin/controllers/../uploads/images/692374e9b588f.jpeg', 'image/jpeg'),
(137, '/admin/controllers/../uploads/images/69237e1b5f34c.jpg', 'image/jpeg'),
(139, '/admin/controllers/../uploads/images/6923800094b19.jpg', 'image/jpeg'),
(140, '/admin/controllers/../uploads/images/6924e8ddc1158.jpeg', 'image/jpeg'),
(141, '/admin/controllers/../uploads/images/6924e8e02b0d8.jpeg', 'image/jpeg'),
(142, '/admin/controllers/../uploads/images/6924ecb8e0a3b.jpeg', 'image/jpeg'),
(144, '/admin/controllers/../uploads/images/69256a142ac17.JPG', 'image/jpeg'),
(145, '/admin/controllers/../uploads/images/69256a28affed.JPG', 'image/jpeg'),
(146, '/admin/controllers/../uploads/images/69256a3a07df2.JPG', 'image/jpeg'),
(147, '/admin/controllers/../uploads/images/69256a46d7fc1.JPG', 'image/jpeg'),
(148, '/admin/controllers/../uploads/images/69256a6c1ee12.JPG', 'image/jpeg'),
(149, '/admin/controllers/../uploads/images/69256ac06d264.jpg', 'image/jpeg'),
(150, '/admin/controllers/../uploads/images/69256b1c410bc.jpg', 'image/jpeg'),
(151, '/admin/controllers/../uploads/images/692774e89898b.jpeg', 'image/jpeg'),
(152, '/admin/controllers/../uploads/images/692777f395f86.jpeg', 'image/jpeg'),
(153, '/admin/controllers/../uploads/images/69285d99020b4.jpeg', 'image/jpeg'),
(157, '/admin/controllers/../uploads/images/692a56c74c904.avif', 'image/avif'),
(158, '/admin/controllers/../uploads/images/692a581a2938b.jpg', 'image/jpeg'),
(159, '/admin/controllers/../uploads/images/692a5881ca58f.jpg', 'image/jpeg'),
(160, '/admin/controllers/../uploads/images/692a591776853.webp', 'image/webp'),
(161, '/admin/controllers/../uploads/images/692a5a8d8d517.webp', 'image/webp'),
(162, '/admin/controllers/../uploads/images/692a5b713629d.jpg', 'image/jpeg'),
(163, '/admin/controllers/../uploads/images/692a5c0caa4b8.webp', 'image/webp'),
(164, '/admin/controllers/../uploads/images/692a5ca7b772e.jpg', 'image/jpeg'),
(167, '/admin/controllers/../uploads/images/692e5a3ee4a5f.jpeg', 'image/jpeg'),
(169, '/admin/controllers/../uploads/images/69470881298c0.png', 'image/png'),
(174, '/admin/controllers/../uploads/images/694d739144402.jpeg', 'image/jpeg'),
(176, '69504711a45f3.jpg', 'image/jpeg'),
(177, '6950474443256.png', 'image/png'),
(178, '695048640cb46.jpg', 'image/jpeg'),
(179, '69504e491f1ac.jpg', 'image/jpeg'),
(180, '69504e5ae976c.png', 'image/png'),
(181, '695055f372062.jpg', 'image/jpeg'),
(182, '/admin/uploads/images/69516dc844ca9.jpg', 'image/jpeg'),
(183, '/admin/uploads/images/69543ab855b65.jpg', 'image/jpeg'),
(184, '/admin/uploads/images/69543bc27b2ea.jpg', 'image/jpeg'),
(185, '/admin/uploads/images/695442565a330.jpg', 'image/jpeg'),
(186, '/admin/uploads/images/6954435cb1906.jpg', 'image/jpeg'),
(187, '/admin/uploads/images/69544374b2aa5.jpg', 'image/jpeg'),
(188, '/admin/uploads/images/695443db872ad.jpg', 'image/jpeg'),
(189, '/admin/controllers/../uploads/images/6954aeaf829f2.jpeg', 'image/jpeg'),
(190, '/admin/uploads/images/6954f41be7758.png', 'image/png'),
(191, '/admin/uploads/images/695521ba6a0f2.jpeg', 'image/jpeg'),
(192, '/admin/uploads/images/695521babd6e5.jpeg', 'image/jpeg'),
(193, '/admin/uploads/images/695521bb4f0ec.jpeg', 'image/jpeg'),
(194, '/admin/uploads/images/695521bb560b7.jpeg', 'image/jpeg'),
(195, '/admin/uploads/images/695642039c096.jpg', 'image/jpeg'),
(196, '/admin/uploads/images/69564210dbf16.jpg', 'image/jpeg'),
(197, '/admin/uploads/images/695642145bfaa.jpg', 'image/jpeg'),
(198, '/admin/uploads/images/6957e3f66d235.jpg', 'image/jpeg'),
(199, '/admin/controllers/../uploads/images/695ab9d32083e.jpeg', 'image/jpeg'),
(200, '/admin/controllers/../uploads/images/695dcab75f48d.jpg', 'image/jpeg'),
(202, '/admin/uploads/images/695dd81861bd0.jpeg', 'image/jpeg'),
(203, '/admin/controllers/../uploads/images/695dd992c830e.jpeg', 'image/jpeg'),
(204, '/admin/controllers/../uploads/images/69613e86e2572.jpeg', 'image/jpeg'),
(205, '/admin/controllers/../uploads/images/6962b80d86ebe.png', 'image/png'),
(206, '/admin/uploads/images/696cdb6f012c2.JPG', 'image/jpeg'),
(207, '/admin/controllers/../uploads/images/696cdbca8731e.jpg', 'image/jpeg'),
(208, '/admin/controllers/../uploads/images/696ce3ad2f9b0.jpg', 'image/jpeg'),
(209, '208', NULL),
(210, '/admin/controllers/../uploads/images/696ce45c7e8ee.jpg', 'image/jpeg'),
(211, '210', NULL),
(212, '/admin/controllers/../uploads/images/696ce52ee6f57.JPG', 'image/jpeg'),
(213, '212', NULL),
(214, '/admin/uploads/images/696eaae6a09a4.png', 'image/png'),
(215, '/admin/uploads/images/696eaaee4c04b.png', 'image/png'),
(217, '/admin/uploads/images/696eac6f70f30.jpg', 'image/jpeg'),
(219, '/admin/uploads/images/696eb19d71761.png', 'image/png'),
(220, '/admin/uploads/images/696eb7cd83b20.png', 'image/png'),
(221, '/admin/uploads/images/696eb863d22c9.jpg', 'image/jpeg'),
(222, '/admin/uploads/images/696eb8eab10c9.png', 'image/png'),
(223, '/admin/uploads/images/696ebc6b7a696.jpg', 'image/jpeg'),
(224, '/admin/controllers/../uploads/images/69740d00e2580.png', 'image/png'),
(225, '224', NULL),
(227, '226', NULL),
(229, '/admin/controllers/../uploads/images/69743e42ef062.jpeg', 'image/jpeg'),
(230, '/admin/controllers/../uploads/images/6974472a70af7.jpeg', 'image/jpeg'),
(232, '/admin/controllers/../uploads/images/6977fc491e61e.jpeg', 'image/jpeg'),
(233, '/admin/controllers/../uploads/images/697a9e3789fd9.jpeg', 'image/jpeg'),
(234, '/admin/controllers/../uploads/images/697aa4faebd56.jpeg', 'image/jpeg'),
(235, '/admin/controllers/../uploads/images/697aa917eb62f.jpeg', 'image/jpeg'),
(238, '/admin/controllers/../uploads/images/697ba523ba5c8.jpg', 'image/jpeg'),
(239, '/admin/controllers/../uploads/images/697ba60e0b679.jpg', 'image/jpeg'),
(240, '/admin/controllers/../uploads/images/697ba76c319cd.jpg', 'image/jpeg'),
(241, '/admin/controllers/../uploads/images/697ba7cf3f7e1.jpg', 'image/jpeg'),
(248, '/admin/controllers/../uploads/images/697e339317a0a.jpg', 'image/jpeg'),
(250, '/admin/controllers/../uploads/images/698022ecaf0da.jpeg', 'image/jpeg'),
(251, '/admin/controllers/../uploads/images/6983fa8ad1531.jpeg', 'image/jpeg'),
(252, '/admin/controllers/../uploads/images/698639748c9d7.jpeg', 'image/jpeg'),
(253, '/admin/controllers/../uploads/images/69863e411035a.jpeg', 'image/jpeg'),
(254, '/admin/controllers/../uploads/images/698d1a664f2c1.jpeg', 'image/jpeg'),
(255, '/admin/controllers/../uploads/images/6991fe59b3626.jpeg', 'image/jpeg'),
(256, '/admin/controllers/../uploads/images/6991febda6324.jpeg', 'image/jpeg'),
(257, '/admin/controllers/../uploads/images/6991ff4b95fd5.jpeg', 'image/jpeg'),
(258, '/admin/controllers/../uploads/images/699200b0695c6.jpeg', 'image/jpeg'),
(259, '/admin/controllers/../uploads/images/699202712343f.png', 'image/png'),
(260, '/admin/controllers/../uploads/images/6992c030aab29.jpeg', 'image/jpeg'),
(261, '/admin/controllers/../uploads/images/699332e329948.avif', 'image/avif'),
(262, '/admin/controllers/../uploads/images/69934bb2b66a0.jpg', 'image/jpeg'),
(263, '/admin/controllers/../uploads/images/69934c9876a01.avif', 'image/avif'),
(264, '/admin/controllers/../uploads/images/69934fb15a230.jpg', 'image/jpeg'),
(265, '/admin/controllers/../uploads/images/699376606f99b.jpeg', 'image/jpeg'),
(268, '/admin/controllers/../uploads/images/6993f4426a3a1.png', 'image/png'),
(269, '/admin/controllers/../uploads/images/699478a75e239.jpeg', 'image/jpeg'),
(270, '/admin/uploads/images/699b6f0230f6a.jpeg', 'image/jpeg'),
(271, '/admin/controllers/../uploads/images/69a3a1582eadd.jpeg', 'image/jpeg'),
(272, '/admin/controllers/../uploads/images/69a3a20279670.jpeg', 'image/jpeg'),
(273, '/admin/controllers/../uploads/images/69a5044d53526.jpeg', 'image/jpeg'),
(275, '/admin/controllers/../uploads/images/69a59e166e716.png', 'image/png'),
(276, '/admin/controllers/../uploads/images/69a59ed3c0b59.png', 'image/png'),
(277, '/admin/controllers/../uploads/images/69a5a1611159e.png', 'image/png'),
(279, '/admin/config/../../admin/uploads/images/69ab1a45ebfbb.png', 'image/png'),
(280, '/admin/controllers/../uploads/images/69b0396869e11.jpeg', 'image/jpeg'),
(281, '/admin/controllers/../uploads/images/69cddd1e6afd2.jpeg', 'image/jpeg'),
(282, '/admin/uploads/images/69ce09a4ab349.png', 'image/png'),
(283, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d19969c39d4.jpg', 'image/jpeg'),
(284, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d19bf5ca349.jpg', 'image/jpeg'),
(285, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1a572b6423.jpg', 'image/jpeg'),
(286, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1a90add8b4.png', 'image/png'),
(287, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1a90ae0381.png', 'image/png'),
(288, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1aa2242cb0.png', 'image/png'),
(289, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1aa224450b.png', 'image/png'),
(290, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1ab6b9b1a6.png', 'image/png'),
(291, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1ab6b9d05b.png', 'image/png'),
(292, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1ac57ceb38.png', 'image/png'),
(293, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1ac57cfef1.png', 'image/png'),
(294, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1acfa62cd1.png', 'image/png'),
(295, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1acfa6422f.png', 'image/png'),
(296, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1b0452da0e.png', 'image/png'),
(297, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1b0452f915.png', 'image/png'),
(298, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1b838e1a12.jpg', 'image/jpeg'),
(299, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d1b838e37b9.png', 'image/png'),
(300, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d20d111cb4d.jpg', 'image/jpeg'),
(301, '/home/vol1_6/infinityfree.com/if0_39722397/htdocs/konektem/config/../uploads/images69d20d111df51.png', 'image/png'),
(302, '/konektem/config/../uploads/images69d239e14a105.png', 'image/png'),
(303, '/konektem/config/../uploads/images69d239e14c4c4.png', 'image/png'),
(304, '/konektem/config/../uploads/images69d23deb9e413.jpg', 'image/jpeg'),
(305, '/konektem/config/../uploads/images69d23deba033d.png', 'image/png'),
(306, '/konektem/config/../uploads/images69d247997a278.png', 'image/png'),
(307, '/konektem/config/../uploads/images69d247997c84d.png', 'image/png'),
(308, '/konektem/config/../uploads/zvelake_album_my album69d26da439ef7.png', 'image/png'),
(309, '/konektem/config/../uploads/zvelake_album_my album69d26da43c153.png', 'image/png'),
(310, '/konektem/config/../uploads/zvelake_album_my album69d270c073cc7.png', 'image/png'),
(311, '/konektem/config/../uploads/zvelake_album_my album69d270c074e8a.png', 'image/png'),
(312, '/konektem/config/../uploads/zvelake_album_myalbum69d28a2cbba99.jpg', 'image/jpeg'),
(313, '/konektem/config/../uploads/zvelake_album_myalbum69d28a2cbd46f.png', 'image/png'),
(314, '/konektem/config/../uploads/zvelake_album_myalbum/69d2965326642.png', 'image/png'),
(315, '/konektem/config/../uploads/zvelake_album_myalbum/69d2965327c99.png', 'image/png'),
(316, '/konektem/config/../uploads/zvelake_album_myotheralbum/69d299ae5e305.png', 'image/png'),
(317, '/konektem/config/../uploads/zvelake_album_myotheralbum/69d299ae5f90e.png', 'image/png'),
(318, '/konektem/config/../uploads/zvelake_album_myotheralbum/69d299ae6045b.png', 'image/png'),
(319, '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d40be0576de.jpg', 'image/jpeg'),
(320, '/admin/controllers/../uploads/images/69d4f65f754b2.jpeg', 'image/jpeg'),
(321, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45879c.jpg', 'image/jpeg'),
(322, '/admin/controllers/../uploads/images/69f513ecdf843.jpeg', 'image/jpeg'),
(323, '/admin/controllers/../uploads/images/6a06481f92c8e.jpeg', 'image/jpeg');

-- --------------------------------------------------------

--
-- Структура таблицы `interview`
--

CREATE TABLE `interview` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image_id` bigint(20) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `shares` int(11) DEFAULT 0,
  `likes` int(11) DEFAULT 0,
  `personName` varchar(100) DEFAULT NULL,
  `personTitle` varchar(100) DEFAULT NULL,
  `interviewLength` varchar(50) DEFAULT NULL,
  `interviewDate` date DEFAULT NULL,
  `video_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `interview`
--

INSERT INTO `interview` (`id`, `title`, `description`, `created_at`, `updated_at`, `image_id`, `views`, `shares`, `likes`, `personName`, `personTitle`, `interviewLength`, `interviewDate`, `video_id`) VALUES
(4, '“Plan Enmi an Echwe\": Une Mélodie Bénie d\'Espoir et de Foi.', 'Dans l\'étendue enrichissante de la musique évangélique haïtienne, une collaboration inspirée a pris forme, portant le nom significatif de \"Plan Enmi an Echwe\". Les talentueux artistes Jean Enock Louis et Sherline Dalberis largement reconnus dans le secteur évangélique, se sont unis pour créer une composition captivante, dédiée à inspirer et encourager les chrétiens à maintenir une foi inébranlable.\r\n\r\n\r\nÉvoluant au Canada tout en restant profondément liés à leur terre natale, Haïti, Jean Enock Louis et Sherline Dalberis, respectivement père et mère de famille, partagent leur talent et leur dévotion à travers un répertoire de musique évangélique très riche. La rencontre providentielle entre ces deux artistes a donné naissance à \"Plan Enmi an Echwe\".\r\n\r\n\r\nL\'histoire de cette collaboration exceptionnelle a pris racine et a pris vie grâce à une suggestion inspirée du maestro Samuel Joseph Pierre Paul, résidant au Canada. Témoin d\'une performance exceptionnelle de Sherline lors d\'un concert et conscient de la voix unique de Jean Enock Louis, l\'idée a germé et s\'est concrétisée.\r\n\r\n\r\nSelon les dires de Jean Enock Louis, compositeur de la chanson, son inspiration émane du Saint-Esprit, soulignant ainsi la direction divine dans la composition musicale. Sherline Dalberis, également imprégnée de la foi chrétienne, a joué un rôle essentiel en contribuant avec ses idées et ses encouragements.\r\n\r\n\"Plan Enmi an Echwe\" n\'est pas simplement une chanson, mais un hymne d\'espoir, porteur d\'un message profondément enraciné dans la foi, nous déclare Jean Enock Louis, ancien membre de la chorale Teknon Vox Mass Choir. Cette chanson évoque la protection divine face aux adversités. Sherline Dalberis, ancienne vedette du gospel Hoshama, aspire à encourager les croyants à rester confiants, conscients de la victoire divine sur le mal.\r\n\r\n\r\nMalgré les défis rencontrés par Enock et Sherline, la décision courageuse de créer un vidéoclip pour \"Plan Enmi an Echwe\" a été prise, démontrant leur engagement envers les mélomanes de la musique évangélique. L\'équipe derrière le clip vidéo a surmonté les obstacles de l\'hiver canadien avec dévouement, capturant l\'essence de la chanson. Jean Enock Louis et Sherline Dalberis privilégient la simplicité visuelle, permettant au message de la chanson de rayonner à travers chaque image. L\'énergie dégagée par les deux psalmistes dans la vidéo traduit l\'essence même du message qui compose la musique.\r\n\r\nEn dehors des projecteurs et de la scène, la production exceptionnelle de cette musique fortifiante est le fruit du travail d\'experts chevronnés. Le séquençage est réalisé avec habileté par Milot Joseph, accompagné d\'une chorale exceptionnelle composée de Sabine Lira, Marie Marthe Sima et Milot Joseph lui-même. Le studio d\'enregistrement responsable de cette création remarquable est affilié à Milot Music.\r\n\r\n\r\nLa collaboration entre Jean Enock Louis et Sherline Dalberis dans \"Plan Enmi an Echwe\" témoigne de leur engagement à créer des œuvres musicales évangéliques inspirantes et encourageantes pour les moments cruciaux.\r\n\r\n\r\nDes projets futurs sont envisagés, mais pour l\'instant, “Plan Enmi an Echwe” est disponible sur toutes les plateformes de streaming, notamment sur les chaînes YouTube de Jean Enock Louis et Sherline Dalberis.\r\n\r\n\r\nDans leur répertoire musical, Jean Enock Louis a enregistré trois (3) albums, tandis que Sherline Dalberis, avec deux (2) albums, voit chaque mélodie qu\'ils créent devenir une source d\'inspiration et de réconfort à travers le monde.\r\n\r\n\r\nKonekte M vous encourage à affermir votre foi à travers cette mélodie bénie d\'espoir et de foi.\r\n\r\nRédaction: Vanauscheca Bouzy\r\n\r\n#konektem\r\n#toutkotenenpotkile', '2025-11-29 02:13:27', '2026-04-26 12:37:37', 157, 0, 1, 2, NULL, NULL, NULL, NULL, NULL),
(9, 'Bishop Gregory Toussaint lance la marche internationale « Souf pou Ayiti » après 110 000+ signatures.', 'Après avoir lancé une pétition qui a recueilli 110 000 signatures en moins d\'une semaine, Bishop Gregory Toussaint a lancé une marche qui a recueilli plus de 50 000 inscriptions dans ce même laps de temps.\r\n\r\nLa pétition «Souf Pou Ayiti » a atteint son objectif en l\'espace de quelques jours. Les organisateurs pensaient qu’elle prendrait 40 jours pour obtenir 100 000 signatures, mais c\'était sans compter l\'engouement suscité par cette initiative qui a eu un fort soutien en Haïti et dans la diaspora haïtienne. Résultat: jusqu\'à date, plus de 113 000 personnes ont déjà signé.\r\n\r\n\r\nPour capitaliser sur cette dynamique, Bishop Gregory Toussaint a proposé l\'organisation d\'une grande marche pour mobiliser les Haïtiens du monde entier sur la situation d\'Haïti.\r\n\r\n\r\n «En fait, indique le pasteur titulaire du Tabernacle de Gloire, j’ai reçu des directives de la part de Dieu pour une telle initiative, et plusieurs autres personnes me l’ont confirmé».\r\n\r\n\r\nCette marche, qui aura une ampleur internationale, aura plusieurs objectifs, selon Bishop Toussaint. Le premier objectif, indique-t-il, est d’inciter les citoyens ordinaires, les Amos, comme il les appelle en référence au prophète juif de la Bible, à prendre leur responsabilité dans la vie de la nation haïtienne.\r\n\r\n\r\n«Ce sont les citoyens ordinaires qui d’habitude apportent les changements, pas les élites qui, eux, préfèrent le statu quo. Le Mouvement des droits civiques aux USA a commencé par Rosa Parks, une couturière, une chrétienne», explique Gregory Toussaint.\r\n\r\n\r\n«Après l’arrestation de Parks, poursuit-il, les pasteurs américains se sont soulevés et c’est ainsi que le mouvement des droits civiques allait commencer avec l’église. Quand l’église combine la prière avec des actions, rien ne peut lui résister. L’église a été le fer de lance pour briser le système de ségrégation aux USA. \r\n Je crois que ce que l’église américaine a fait aux USA, l’église haïtienne peut le faire en Haïti».\r\n\r\n\r\nLe second objectif de la marche est de communiquer un message à la communauté internationale. «Le message est que nous sommes concernés par ce qui se passe en Haïti et que nous voulons faire partie de la solution», déclare le PDG de Radio Shekinah.\r\n\r\n\r\nIl a poursuivi en soulignant le rôle que la diaspora peut jouer notamment quand il s’agit d’exercer la pression sur la communauté internationale. «Haïti ne peut pas faire pression sur les Etats-Unis, le Canada et la France, mais la diaspora haïtienne le peut. Par exemple, aux USA, une grande partie de la diaspora haïtienne peut voter dans les élections américaines. Il y aura des élections en 2024. C’est le bon moment pour demander aux autorités américaines de mieux traiter Haïti car notre vote peut être décisif dans leurs élections».\r\n\r\n\r\n Troisièmement, la marche du 9 juillet vise à demander à identifier ceux qui financent les gangs en Haïti et à les pénaliser à travers le projet de loi S.396 du Sénat et le projet de loi HR 1684 de la Chambre des représentants, intitulé \"Haiti Criminal Collusion Transparency Act of 2023\". La marche appellera enfin au maintien du programme humanitaire de migration conditionnelle (Humanitarian Parole). \r\n\r\n\r\nRappelons que ce programme est remis en cause par plusieurs États américains qui ont intenté une action en justice contre l\'administration Biden, affirmant que le processus de migration conditionnelle est illégal.\r\n\r\n\r\nUn procès était précédemment prévu pour le 13 juin 2023 pour déterminer le sort du «Humanitarian Parole». Cependant une nouvelle date, le 24 août 2023, a été proposée pour ledit procès en attendant d\'être confirmée par une décision judiciaire.\r\n\r\n\r\n«La situation en Haïti est comparable à celle de l’Ukraine, donc si le programme est maintenu pour l’Ukraine, il ne fait aucun sens qu’il s\'arrête pour Haïti», a souligné Pasteur Toussaint.\r\n\r\n\r\nÀ ce jour, selon l\'ambassade américaine en Haïti, 39 000 Haïtiens ont été examinés et approuvés pour voyager dans le cadre du programme depuis le 5 janvier jusqu\'à la fin du mois d\'avril. \r\n\r\n\r\nLes organisateurs de la marche soutiennent que maintenir ce programme permettra de sauver des vies en offrant une voie légale et sûre aux Haïtiens cherchant refuge aux États-Unis.\r\n\r\n\r\nCette marche internationale se déroulera dans au moins plusieurs pays (Haïti, USA, Canada, République Dominicaine, Chili, France) et de nombreuses villes du monde (Miami, Orlando, Boston, New York, Atlanta, Philadelphie, New Jersey, Montréal, Paris, Santo-Domingo, Santiago) pour témoigner de l\'engagement de la communauté haïtienne mondiale à trouver une issue à la crise.\r\n\r\n\r\n«Le jour de l\'événement, qui sera un dimanche, nous allons marcher dans les rues après le service dominical avec des chaussures de sport», annonce Pasteur Toussaint.\r\n\r\n\r\n«Aux USA, poursuit-il, on marchera dans tous les États américains, principalement la Floride, New York, New Jersey, Massachusetts, où se trouve une forte population haïtienne. Pour les endroits où la population est moins importante, on leur demandera de se tenir devant un bâtiment gouvernemental. On marchera dans des villes comme Miami, Orlando, New York, Philadelphie, Paris, Montréal, Santo-Domingo, etc».\r\n\r\n\r\nEn Haïti, la marche se tiendra à Port-au-Prince, la capitale, à Grand-Goâve, Cap-Haïtien, Léogâne, Petit-Goâve, Jérémie, Les Cayes, Hinche, Jacmel, Gonaïves, Miragoâne, Mirebalais, Fort-Liberté et Saint-Marc.\r\n\r\n\r\nAussi, plus de 180 pasteurs d\'Haïti et de la diaspora se joindront à cette initiative. Parmi lesquels on peut citer: Samuel Robuste (Jacksonville), Eddy Gervais (Miami), Max Moïse Sauld (Haïti), Samuel Nicolas (New York), Phil Mercidieu (Fort Myers), Wilner Cayo (Canada), Wilner Prudent (Miami), Mullery Jean-Pierre (New York), Carlos Pierre (Miami), Malory Laurent (New York), Daniel Chery (Montréal), Caleb Barthélus (Montréal), Emmanuel Dessalines (Montréal), André Muscadin (Haïti), Delly Benson (Haïti).\r\n\r\n\r\nEn tout, 200 organisations, 350 pasteurs et 50 000 personnes se sont engagés à participer à la marche. Pour rappel, le ministère Shekinah avait lancé la pétition « Souf pou Ayiti » le 2 juin dernier, préoccupé par la nouvelle conjoncture haïtienne qui force les citoyens haïtiens à laisser leur quartier voire leur pays. Depuis juillet 2021, la situation en Haïti s\'est détériorée, marquée par une augmentation des enlèvements, des agressions, des violences sexuelles et des extorsions perpétrées par des gangs criminels, au point où les Nations unies ont comparé le niveau d\'insécurité du pays à celui d\'une zone en guerre.\r\n\r\n\r\nLa pétition « Souf pou Ayiti » avait été accueillie avec un grand enthousiasme par les Haïtiens de partout. Elle visait à aider les compatriotes en Haïti et ceux qui sont récemment entrés aux Etats-Unis par le biais du « Humanitarian Parole », soit le programme humanitaire de migration conditionnelle, lancé en janvier dernier par le président Joe Biden.\r\n\r\n\r\nLa marche se déroulera durant l\'événement «40 Jours de Jeûne» organisé par le ministère Shekinah dirigé par Bishop Gregory Toussaint.\r\n\r\nGregory Toussaint, PDG, entrepreneur, philanthrope, auteur de best-sellers et orateur haïtiano-américain, est le pasteur titulaire de Tabernacle de Gloire. Cette église, composée de 47 campus, compte 25 000 membres actifs locaux et 50 000 membres en ligne. \r\n\r\n\r\nEn tant que PDG et fondateur de Shekinah.fm, Gregory a accumulé plus de 4 millions d\'abonnés sur les médias sociaux et compte en moyenne 4 millions de vues par semaine. Ses émissions de radio touchent plus de 5 millions de foyers en Haïti. \r\n\r\n\r\nSon émission de télévision, \"Bishop G Live\", diffusée sur des réseaux en Amérique, en Europe, en Afrique et au Moyen-Orient, touche un nombre impressionnant de 500 millions de foyers dans le monde. Parlant couramment l\'anglais, le français, l\'espagnol et le créole haïtien, Gregory est diplômé en affaires (BS), en droit (LL.M) et en théologie (Th.M., D.E.A). Il est marié depuis 20 ans et a deux fils. Il réside avec sa famille à Miami, en Floride.\r\n\r\n\r\nRedaction: Jonel Juste', '2025-11-29 02:33:21', '2026-01-23 23:37:40', 162, 0, 2, 1, NULL, NULL, NULL, NULL, NULL),
(5, 'Et si le S.O.S Tour de Rosena J. Orys devenait l’une des plus grandes initiatives musicales haïtiennes de l’année ?', 'Née le 27 avril 1992 dans une famille chrétienne, Rosena Jocelin a grandi avec un rêve aussi lumineux que sa voix : devenir une chanteuse de cœur, capable de toucher les âmes, guérir les blessures et élever les esprits. Dès l’âge de 11 ans, elle foule les scènes haïtiennes à travers diverses chorales telles que Hallelujah Gospel et Jimla Gospel.\r\n\r\n\r\nMais c’est en 2016 que le public découvre une Rosena en solo, vibrante et habitée, grâce au titre marquant « Ou fè m ap viv ». Depuis lors, elle n’a cessé de gravir les échelons, mêlant foi et vécu collectif pour résonner au cœur d’un peuple haïtien blessé, souvent abandonné, mais toujours debout. En 2025, Rosena frappe un grand coup.\r\n\r\n\r\nS.O.S : une chanson, un cri, une vague d’espoir.\r\n\r\nLe 26 avril 2025, sur un beat afro entraînant, Rosena lance « S.O.S ». En moins d’une semaine, le titre envahit réseaux sociaux, rues, églises et plateformes de streaming. Deux mois plus tard, il cumule plus de 3,1 millions de vues sur YouTube, un exploit rare dans le milieu évangélique haïtien.\r\n\r\n\r\nMais ce succès fulgurant ne repose pas uniquement sur sa musicalité : S.O.S est un cri collectif, un appel spirituel et social. Alors qu’Haïti est plongée dans l’insécurité, la peur et les déplacements forcés, Rosena élève un S.O.S vers Dieu, un appel auquel le peuple répond massivement. Entre challenges, reprises, vidéos de prières, enfants et adultes, la chanson franchit toutes les barrières religieuses et sociales. Elle devient un hymne, une mélodie partagée avec émotion, mains levées ou pieds dansants.\r\n\r\n\r\nLe S.O.S Tour : un cri d’adoration pour Haïti et au-delà.\r\n\r\nLe 24 juillet 2025, dans une salle comble de l’hôtel El Rancho, Rosena J. Orys annonce officiellement le lancement du S.O.S Tour, une tournée d’adoration qui traversera Haïti et plusieurs pays étrangers. Elle est entourée de ses collaborateurs Lophane Laurent, Barbara Cassamajor, Penter Orys et Audner Martin, tous mobilisés pour porter cette grande initiative.\r\n\r\n\r\nOrganisée par l’Agence Alexandre, Lophane Project et Acordia Shipping, cette tournée vise à élever une voix musicale et spirituelle forte pour Haïti.\r\n\r\n« La musique est un cri d’alerte face aux souffrances du peuple haïtien. Ce cri, nous voulons qu’il touche tous les cœurs, croyants ou non, enfants comme adultes », déclare Rosena avec émotion.\r\n\r\n\r\nAu-delà de la musique, le S.O.S Tour se veut aussi un projet social engagé, tourné vers les enfants, les femmes et les familles vulnérables. Rosena annonce également un prochain album, qualifié de « prophétique », destiné à insuffler foi, espérance et engagement à une Haïti en quête de renouveau.\r\n\r\n\r\nUn concert inaugural vibrant au Palais Municipal de Delmas.\r\n\r\nLe dimanche 27 juillet 2025, le Palais Municipal de Delmas vibre au rythme du S.O.S Tour. Le public, venu en grand nombre, chante, danse et prie avec ferveur tout au long de la soirée. Rosena, accompagnée d’une équipe musicale solide et d’artistes invités tels que Barbara Cassamajor, Joy Clerf Derisier et Wiliadel Denervil, offre une performance riche en émotions, mêlant louanges profondes et rythmes entraînants.\r\n\r\n\r\nParmi les moments forts : la puissante interprétation de « Je bénirai l’Éternel » par Barbara Cassamajor, les chorégraphies spirituelles du groupe Adassa Dance, et un hommage touchant à Rosena par l’économiste Etzer Émile, qui lui offre un tableau en reconnaissance de son engagement. Le concert s’achève par une prestation magistrale de Rosena accompagnée d’un saxophoniste, emportant le public dans une adoration intense.\r\n\r\n\r\nUne tournée qui se vit aussi par la compassion : visite au CERMICOL.\r\n\r\nLe lundi 28 juillet, après son triomphe à Delmas, Rosena et son équipe, en collaboration avec le Flamboyant Restaurant, visitent le Centre de Rééducation Pénitentiaire des Mineurs en Conflit avec la Loi (CERMICOL). Cette action réunit plusieurs figures engagées du milieu évangélique, telles que Barbara Cassamajor, Joy Clerf Derisier, Wiliadel Denervil et David Morinvil.\r\n\r\n\r\nLe CERMICOL héberge actuellement environ 630 détenus, dont 96 mineurs, 174 femmes et jeunes filles, et 360 hommes adultes.\r\n\r\nAu programme : mini-concert, temps de prière, évangélisation et repas partagé avec les détenus.\r\n\r\n« Tout le monde mérite une part d’amour. Peu importe les erreurs commises, Dieu est toujours prêt à pardonner », déclare Rosena avec émotion.\r\n\r\n\r\nPour l’équipe, cette visite fut un moment fort, une invitation profonde à réfléchir sur la véritable signification de la liberté.\r\n\r\n\r\nCap-Haïtien a chanté sous la pluie !\r\n\r\nCe dimanche 3 août 2025, malgré la pluie, les Capois se sont rassemblés en masse au Complexe Versailles pour vivre la deuxième étape du S.O.S Tour. Barbara met le feu dès les premières notes avec Louwe Louwe, Loutchina fait lever les mouchoirs avec L ap beni w, pendant que Wiliadel et Joy embrasent la salle avec leurs titres phares.\r\n\r\n\r\nRosena J. Orys, très attendue, est accueillie avec ferveur. Son passage sur scène transforme l’atmosphère en un véritable lieu d’adoration. Chaque chanson résonne comme un cri de foi, et le public, transporté, chante, prie, adore. Un concert marqué par la pluie… mais surtout par la présence palpable de Dieu.\r\n\r\n\r\nUne musique évangélique qui dérange… mais qui libère\r\n\r\nToute onde puissante crée des remous. Lorsque le célèbre influenceur UncleKendjy publie un challenge autour de S.O.S, une vague de critiques surgit dans le milieu protestant :\r\n\r\n« Rosena pa dwe poste videyo sa yo. Pa gen limyè ak tenèb ansanm. »\r\n\r\nCe débat théologique soulève une question essentielle : jusqu’où la musique chrétienne peut-elle aller pour toucher ceux qui sont “dehors” ?\r\n\r\nRosena répond avec foi et assurance :\r\n\r\n« Se pa mwen ki chwazi mizik sa, se BONDYE ki mete l nan kè mwen. Se pou li sèvi ak li, jan li vle, lè li vle, ak moun li vle. »\r\n\r\nUn réveil en marche : une tournée pour les oubliés, les blessés, les affamés de sens\r\n\r\nLe S.O.S Tour ne s’adresse pas seulement aux chrétiens. Il parle à ceux que la société rejette, à ceux que les églises oublient, à ceux qui ne trouvent pas Dieu dans les discours, mais qui espèrent encore le sentir dans un chant, une vibration, une parole qui les comprend.\r\n\r\nAlors, doit-on voir dans S.O.S et sa tournée une révolution musicale ou une simple mode passagère ?\r\n\r\n\r\nEt si c’était le début d’un réveil spirituel, porté par une femme, une voix, un peuple en détresse ?\r\n\r\nEt si, enfin, la musique évangélique haïtienne faisait tomber ses murs, pour entrer dans les rues, les foyers… et dans les douleurs réelles du peuple ?\r\n\r\n\r\n\r\nRedaksyon:\r\n\r\nRonalson Bryan Blanfort\r\n\r\n\r\n\r\n#Sos\r\n\r\n#RosenaOrys\r\n\r\n#konektem\r\n\r\n#toutkotenenpotkilè', '2025-11-29 02:19:06', '2026-01-01 23:31:46', 158, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
(6, 'Samedi de la grâce: Le milieu en parle.', 'Les préparatifs de Samedi de la Grâce avancent avec passion et ferveur. Les tee-shirts officiels de l’événement sont déjà disponibles et la demande dépasse toutes les prévisions. Plusieurs églises, associations et groupes de jeunes portent fièrement ces couleurs, affirmant leur engagement avec enthousiasme. Les artistes confirmés continuent de mobiliser le public sur les réseaux sociaux et d’attiser l’excitation autour de ce grand rendez-vous spirituel.\r\n\r\n\r\nSur les réseaux, l’impatience se lit dans chaque commentaire, chaque réaction. Une jeune participante confie : « Je suis impatiente de vivre ce moment ! À chaque fois que je vois un nouveau tee-shirt ou un artiste annoncé, mon cœur s’emballe. » Un responsable d’église ajoute : « Malgré tout ce qui se passe dans le pays, Samedi de la Grâce est comme une lumière dans l’obscurité. Je suis fier de voir cette mobilisation. » Des témoignages qui traduisent l’importance et l’attachement du public à cette rencontre.\r\n\r\n\r\nMalgré les défis — pluie persistante, climat d’insécurité et autres obstacles — l’équipe organisatrice reste debout. Avec dévouement, détermination et foi, elle travaille sans relâche pour garantir la réussite de l’événement. Une autre voix témoigne : « Chaque affiche, chaque annonce me donne de l’espoir. On attend tous ce moment pour chanter, prier et sentir la présence de Dieu ensemble. » Ces paroles sincères révèlent la soif spirituelle d’une jeunesse avide d’espérance.\r\n\r\n\r\nSamedi de la Grâce dépasse ainsi le cadre d’un simple événement : c’est un acte collectif de foi, une déclaration d’espérance dans un contexte troublé. Alors que beaucoup vivent dans l’inquiétude et l’incertitude, cette journée s’annonce comme un souffle spirituel, un rappel que la grâce de Dieu reste accessible à tous, malgré les tempêtes.\r\n\r\n\r\nVous aussi, ne manquez pas ce rendez-vous. Enfilez votre tee-shirt, invitez vos amis et venez vivre un moment de louange, de prière et d’adoration dans une atmosphère unique. Participez pour dire : « J’y étais, j’ai senti la présence de Dieu, et j’ai ajouté ma voix à ce grand cri d’espérance. »\r\n\r\n\r\n\r\nRédaction: Ronalson BLANFORT\r\n\r\n\r\n\r\n#Samedidelagrace \r\n\r\n#konektem\r\n\r\n#toutkotenenpòtkilè\r\n\r\n', '2025-11-29 02:20:49', '2026-03-01 17:58:23', 159, 0, 1, 0, NULL, NULL, NULL, NULL, NULL),
(7, 'Caribbean Worshipers : yon randevou adorasyon moun Bahamas pa negosye.', 'Adore BonDye ak sove nanm se de (2) gwo objektif « Caribbean Worshipers » vize chak ane, yon konkennchenn evènman ki reyini kominote karayibeyèn nan, sou lidèchip Neslin Destilhomme. Pou yon katriyèm fwa, anfoul, moun Bahamas pare pou reponn prezan nan randevou sila.  Ekip Konekte M nan tanmen yon antrevi ak vizyonè inisyativ sila a, Neslin Destilhomme ki te pataje avèk nou motivasyon l ki pèmèt li reyalize aktivite sa a.\r\n\r\n\r\n\r\nJonathan Dorziaire, jounalis : Kisa « Caribbean Worshipers ye » epi ki kote lide a soti ?\r\n\r\n\r\nNeslin Destilhomme : Caribbean Worshipers se avan tou, yon aksyon de gras mwen ak madanm mwen vle ofri Letènèl pou montre L kijan nou rekonesan anvè tout sa L fè pou nou. Jeneralman aksyon de gras souvan gen fòm reyini moun chante, temwanye epi bay manje. Men nan ka pa nou pi bèl fason se te mete sou pye yon evènman k ap reyini tout kominote karayibeyèn nan, nan yon sèl espas, pandan n ap fè lwanj pou BonDye ansanm, epi chache nanm pou Sovè nou Senyè Jezi. Se sa ki fè, an Mas 2021, nou te deside lanse evènman sila sou non « Caribbean Worshipers».\r\n\r\nJD: Aprè twa (3) edisyon  èske w kwè « Caribbean Worshipers » jwenn yon plas nan kominote karayibeyèn nan ?\r\n\r\n\r\nND: Nou ka di wi gras a Dye, parapò ak jan moun yo toujou deplase an foul. Chak ane ekip la oblije nan chanje espas, paske espas yo toujou twò piti, ayisyen tankou bayameyen toujou debake an mas. Se pa sèlman afliyans moun ki patisipe nan aktivite a men se sitou «line up» la ki reflete lide pou mete talan karayibeyen yo anavan. Plizyè adoratè ak adoratris nan Bahamas ak lòt zile nan Karayib la toujou reponn prezan nan « Caribbean Worshipers ». Chak mwa Mas tout Bahamas, depi katran (4 an), konnen « Caribbean Worshipers » se yon moman adorasyon yo pa dwe rate.\r\n\r\nJD: 29 Mas 2025 se dat pou katriyèm edisyon Caraibean Worshipers, kisa k ap diferan parapò ak lòt edisyon avan yo?\r\n\r\n\r\nND: Tout edisyon Caraibean Worshipers yo toujou wololoy men edisyon sila pral san parèy. Dabò nou òganize katriyèm nan, nan youn nan pi gwo espas nan Bahamas: BFM Diplomat Center, ki gen kapasite pou pran plis pase 2 000 plas. Ane a n ap gen plis moun k ap vin adore parapò ak lòt ane yo avan. Men n ap egalman gen plis moun nan delegasyon k ap soti tribòpabò pou vin adore ak nou tankou Ayiti, Kanada, Etazini, Repiblik Dominikèn. Ane a tou, nou gen yon «line up» ki chaje jèn adoratè, adoratris tankou Loutchina Decius, Joy, Atis Fondation Frè Luckson Zòn Pa Fè Moun (FFLZPFM) ak lòt ankò. Ane sa a Nou ajoute yon moman kominyon fratènèl, aprè adorasyon n ap gen pou n manje ansanm.\r\n\r\nJD: Lè n konsidere vwayaj, ebèjman ak lòt obligasyon nou genyen pou n byen akeyi tout delegasyon nou yo, kisa ki eksplike « Caribbean Worshipers » gratis ?\r\n\r\n\r\nND: Caribbean Worshipers se pa sèlman yon aksyon degras, li se egalman yon evènman pou chache nanm pou BonDye se sa ki fè tout edisyon nou yo toujou gratis. Yon aksyon de gras ki vin tounen vizyon, ebyen BonDye fè pwovizyon. Pandan de (2) edisyon se grès kochon ki te kuit li. Men ane sa a, BonDye solisite plizyè moun toupatou pou kontribiye pou « Caribbean Worshipers » posib. N ap gen plis pase yon trantèn moun k ap nan delegasyon k ap soti Ayiti, Repiblik Dominikèn, Etazini, Kanada pou adore. BonDye ki bay vizyon an gentan fè pwovizyon pou tout bagay.\r\n\r\n\r\nNeslin Destilhomme, ki entèprete « Jezi ooo » ki soti an oktòb 2024, se pami mizik pandan fen lane 2024 la ak kòmansman ane 2025 lan ki beni plizyè nanm, pwomèt yon moman san parèy nan adorasyon ak louwanj. Vizyonè a rete kwè moman sa a ap pote fui, li rete kwè ap gen vi k ap transfòme, moun ap vin jwenn Jezi, paske Sentespri BonDye pral aji nan BFM Diplomat Center, samdi 29 mas la.\r\n\r\n\r\n\r\nRedaksyon: Jonathan Dorziaire\r\n\r\n\r\n\r\n#konektem\r\n\r\n#toutkotenenpòtkilè', '2025-11-29 02:23:19', '2026-03-01 17:58:57', 160, 0, 1, 0, NULL, NULL, NULL, NULL, NULL),
(8, 'Amazing Grace, au-delà d\'un spectacle à une dose de grâce.', 'Ce samedi 5 avril 2025, El Rancho Convention Center s’est mué en un véritable sanctuaire d’adoration. Pour sa deuxième édition, le concert Amazing Grace a uni voix, instruments et cœurs vers une même direction, l\'adoration. Plus qu’un simple spectacle, Amazing Grace s\'est illustré en une parenthèse sacrée, une immersion dans la grâce, la communion et la foi partagée. Derrière cette réussite, une équipe réunie promoteurs et maestro: Joslord Elmilus, Kervens Pierre, maestro David Morinvil et Real Louis. Quatre passionnés, portés par une vision commune : faire de la louange un langage universel, et de la scène un lieu où souffle la faveur divine.\r\n\r\nDès les premières minutes du concert, DJ Wonder, l’une des rares femmes à exceller dans cet univers, a joué un rôle central. Alors que le public était encore dispersé dans l’espace du El Rancho, elle a su, par des sélections musicales judicieuses et une énergie contagieuse, rassembler les participant.e.s et créer une atmosphère propice à la célébration. Par ses transitions soignées et son sens du rythme, elle a introduit avec finesse l\'évènement, maintenant une dynamique constante entre les artistes. DJ Wonder a ainsi brillamment assuré l’ambiance, tout en imprimant sa touche personnelle à cette soirée.\r\n\r\n\r\nDes performances habitées d’onction et d’énergie\r\n\r\n\r\nLa soirée s’est ouverte sur une atmosphère empreinte de spiritualité avec le groupe Belance, qui a insufflé une énergie rafraîchissante et un esprit d’adoration sincère. Carl Emile et son band malgré des défis techniques ont ensuite enchaîné avec une prestation dynamique et profonde, instaurant une ambiance soutenue de louange dès les premières minutes.\r\n\r\nLes Élus, tout de blanc vêtus, ont captivé l’audience avec une fusion audacieuse de gospel, de sonorités Lakay et de modernité. Leur interprétation de \"Avanse\" a donné le ton, suivie de \"Vin Selebre\", une création mêlant de sonorités indiennes et trap, qui a littéralement électrisé la salle. Dans cette même veine d’originalité. Lynda Joseph, avec sa voix angélique, a profondément ému avec \"Nan Bondye mwen kwè\" et \"Li pap janm lage m\", deux chants porteurs d’espérance.\r\n\r\nBarbara Cassamajor a enflammé la scène avec \"Mon âme bénit l’Éternel\", sur un rythme afro entraînant, entourée de danseurs. Elle a ensuite touché le public avec \"Pwomès pa m\", plongeant l’assemblée dans une adoration douce et sincère. Inspiré de Jean 5:17. Stanley Georges a livré une performance puissante. L’un des moments les plus marquants fut lorsqu’il a invité le public à allumer les flashs de leurs téléphones, symbolisant la lumière chassant les ténèbres d’Haïti. Il a ensuite entonné \"Mwen te deside pou m suiv Lesenyè, m pap tounen\", avant de conclure par \"Glwa Ou\", un chant empreint de foi et de puissance prophétique.\r\n\r\nAutre passage fort : JoyClerf. Avec \"Wi li fè l\", il  a conduit l’auditoire dans une adoration intense. L’émotion était d’autant plus forte que plusieurs des titres interprétés ont été composés par son père, Jean Claude Derisier “Zoom”, conférant une belle touche d’héritage et d’authenticité. Le groupe Holy Music a ensuite pris le relais avec une énergie maîtrisée et une belle cohésion scénique.\r\n\r\nAstharmonie a élevé davantage l’atmosphère dès \"M ap pwospere\", avant de déclencher une vague de louange dansante sur \"I Believe\" de Jonathan Nelson. Ensuite, la chorale DEG, habillée de cuir noir et de rouge grenat, a fait sensation avec \"Mèsi\", en collaboration avec Rutshelle Guillaume. Leur animation impeccable sur \"M a va wè li\" a enflammé la salle, confirmant leur signature musicale alliant innovation et performance scénique audacieuse.\r\n\r\n\r\nTaliana Lindor a profondément touché l’audience avec \"L’Éternel p ap renouvle kontra ankò\" et \"klere chimen\" du groupe Zetwal, offrant un moment d’intimité spirituelle.\r\n\r\nLoutchina a enchaîné avec un bouquet vibrant : \"À Dieu soit la gloire\", \"Jezi wo\" et \"L ap beni w\", entraînant l’assemblée dans une louange joyeuse et dansante. \r\n\r\nEnfin, Wiliadel a clôturé la soirée avec intensité. Son entrée solennelle, sous les flashs du public, a marqué les esprits. Avec \"Mwen poukont mwen\", \"M pa prale jan m vini an san m pa beni\" et \"Sòlda leve pye w\", elle a offert un final à la fois puissant, touchant et engagé.\r\n\r\nLa grâce au cœur de la tempête\r\n\r\n\r\nMalgré quelques retards et défis techniques (retours son défaillants…), l’équipe artistique est restée solide.  Et dans un contexte socio-politique fragile, Amazing Grace a été bien plus qu’un concert : un acte de foi collectif. Les flashs levés pour Haïti, les prières silencieuses, les voix unies dans l’espérance… tout rappelait que la faveur de Dieu transcende les circonstances. À El Rancho, un vent de paix, de force et de foi s’est levé – et il portait un nom : Amazing Grace.\r\n\r\n\r\nRedaksyon:\r\n\r\nRonalson Bryan Blanfort\r\n\r\n\r\n#konektem\r\n\r\n#toutkotenenpotkilè', '2025-11-29 02:29:33', '2026-01-01 23:21:03', 161, 0, 0, 1, NULL, NULL, NULL, NULL, NULL),
(10, 'Melomania Group : \"A Night of Worship with Sinach\"', 'Depuis sa fondation en 2015 par Jonathan Laguerre, Melomania Group sï¿½est imposï¿½ comme une rï¿½fï¿½rence incontournable dans lï¿½ï¿½vï¿½nementiel ï¿½vangï¿½lique. Dï¿½abord basï¿½e en Rï¿½publique Dominicaine, lï¿½organisation a ï¿½tendu ses activitï¿½s aux ï¿½tats-Unis il y a seulement deux ans, tout en conservant sa mission premiï¿½re : crï¿½er des expï¿½riences dï¿½adoration puissantes et fï¿½dï¿½ratrices. En 10 ans, Melomania Group a portï¿½ sur scï¿½ne des artistes chrï¿½tiens de renom, ï¿½difiï¿½ des milliers de croyants et offert ï¿½ la communautï¿½ internationale des moments uniques de communion dans la louange.\r\n\r\nUn parcours jalonnï¿½ de rï¿½alisations mï¿½morables\r\n\r\nDe \"Haitianos En Alabanza\" en 2019, rassemblant Fre Gabe, Salil Lirah et Thamar Joseph ï¿½ Santo Domingo, ï¿½ la vente signature de lï¿½album de Loutchina Dï¿½cius en aoï¿½t 2023, Melomania Group a multipliï¿½ les ï¿½vï¿½nements marquants.\r\n\r\nLa tournï¿½e dominicaine de Rosena J. Orys en 2021, les Christmas Concerts en 2022 et les spectacles de 2024 et 2025 aux ï¿½tats-Unis avec Rosena Orys, Berhley Figaro, Spencer Brutus, Deborah Henristal, Tamare et Palmyre Sï¿½raphin illustrent leur capacitï¿½ ï¿½ rï¿½unir des talents variï¿½s autour dï¿½un seul but : exalter le nom de Dieu.\r\n\r\nUne nouvelle soirï¿½e dï¿½adoration internationale\r\n\r\nLe prochain grand rendez-vous de Melomania Group aura lieu le 5 octobre 2025 au Reggie Lewis Track and Athletic Center, ï¿½ Boston, Massachusetts (USA). Intitulï¿½ \"A Night of Worship with Sinach\", lï¿½ï¿½vï¿½nement mettra ï¿½ lï¿½honneur la cï¿½lï¿½bre artiste nigï¿½rienne, dont le ministï¿½re a dï¿½jï¿½ touchï¿½ des millions de vies ï¿½ travers le monde. Aux cï¿½tï¿½s de Sinach, le public vivra une expï¿½rience unique avec Jean Jean (Canada) et FRE Gabe (USA), dans une ambiance oï¿½ la diversitï¿½ culturelle deviendra un langage commun de louange.\r\n\r\nLes billets sont dï¿½jï¿½ disponibles sur eventbrite.com au prix de 44,52 USD jusquï¿½au 31 aoï¿½t, ainsi que des pass VIP ï¿½ 129,89 USD, distribuï¿½s dans certaines ï¿½glises de la rï¿½gion. Plus de 4 000 participants sont attendus pour cette nuit mï¿½morable.\r\n\r\n?? ?? Get your tickets now on eventbrite: https://www.eventbrite.com/e/night-of-worship-with-sinach-tickets-1507766603499?aff=oddtdtcreator\r\n\r\n\r\nUn impact spirituel et communautaire attendu\r\n\r\nPour Melomania Group, cette soirï¿½e est bien plus quï¿½un concert : cï¿½est un acte de foi. Avec une dï¿½cennie dï¿½expï¿½rience et deux annï¿½es dï¿½implantation rï¿½ussie aux ï¿½tats-Unis, lï¿½organisation veut prouver quï¿½ï¿½ travers la louange et lï¿½adoration, de grandes choses peuvent ï¿½tre accomplies pour la gloire de Dieu.\r\n\r\nLï¿½ï¿½vï¿½nement rassemblera Haï¿½tiens, Africains, Amï¿½ricains et Canadiens dans un mï¿½me ï¿½lan spirituel. Il pourrait aussi avoir une portï¿½e sociale, puisque les organisateurs envisagent de reverser une partie des recettes ï¿½ une ï¿½uvre humanitaire en Haï¿½ti.\r\n\r\n\r\nFoi, unitï¿½ et persï¿½vï¿½rance\r\n\r\nFranï¿½ois Ducheine, Event Manager de Melomania Group, se souvient encore du jour oï¿½, en 2017, lors dï¿½un concert de Delly Benson, il a senti lï¿½Esprit de Dieu le saisir au milieu de lï¿½assemblï¿½e, un moment qui a marquï¿½ son engagement dans ce ministï¿½re.\r\n\r\nAujourdï¿½hui, il voit dans cette Nuit dï¿½adoration une rï¿½ponse spirituelle aux dï¿½fis du temps :\r\n\r\n\r\n_ï¿½ Haï¿½ti traverse des moments difficiles, et beaucoup dï¿½Haï¿½tiens aux ï¿½tats-Unis sont ï¿½prouvï¿½s. Mais au-delï¿½ des agitations, seul Dieu, ï¿½ travers la louange, peut changer les cï¿½urs et donner la paix. ï¿½_\r\n\r\n\r\nInvitation ï¿½ vivre un moment unique\r\n\r\nMelomania Group vous donne rendez-vous pour \"A Night of Worship with Sinach\" le 5 octobre 2025 ï¿½ Boston. Venez et voyez combien le Seigneur est bon : une nuit oï¿½ les frontiï¿½res sï¿½effacent, oï¿½ les voix sï¿½unissent et oï¿½ chaque note ï¿½lï¿½ve lï¿½ï¿½me vers le ciel.\r\n\r\n\r\n\r\nRï¿½daction: Ronalson Bryan Blanfort\r\n\r\n\r\n\r\n#konektem\r\n\r\n#toutkotenenpï¿½tkilï¿½', '2025-11-29 02:35:56', '2026-02-13 22:39:38', 163, 0, 1, 2, 'unknown guest', NULL, NULL, '2026-01-06', NULL),
(11, 'Sherline Dalberis & Garby Mesidor, un chant qui parle au cÅ“ur de ceux qui doutent, espÃ¨rent ou nâ€™osent plus prier.', '<p data-start=\"143\" data-end=\"404\">Il y a des chants que l&rsquo;on &eacute;coute. D&rsquo;autres que l&rsquo;on chante. Et puis, il y a ceux que l&rsquo;on vit.<br data-start=\"238\" data-end=\"241\"><strong data-start=\"241\" data-end=\"253\">SEZON SA</strong>, c&rsquo;est une pri&egrave;re mise en m&eacute;lodie. Une r&eacute;ponse &agrave; ceux qui traversent une saison de fatigue int&eacute;rieure, de silences pesants, de &laquo; Dieu, o&ugrave; es-tu ? &raquo;.</p>\r\n<p data-start=\"406\" data-end=\"589\">Avec ce morceau, Sherline Dalberis et Garby Mesidor ne cherchent pas &agrave; s&eacute;duire. Ils tendent la main. &Agrave; ceux qui vacillent. &Agrave; ceux qui peinent &agrave; croire que le ciel les entend encore.</p>\r\n<p data-start=\"591\" data-end=\"1006\">Un chant n&eacute; dans l&rsquo;attente, nourri par la foi.<br data-start=\"637\" data-end=\"640\">L&rsquo;histoire commence par une inspiration. Un murmure de l&rsquo;Esprit, comme le d&eacute;crit Garby Mesidor. De ce souffle int&eacute;rieur est n&eacute;e une chanson. Il l&rsquo;a port&eacute;e, travaill&eacute;e, puis tendue &agrave; Sherline. Elle a &eacute;cout&eacute;, et elle a su.<br data-start=\"860\" data-end=\"863\">&laquo; D&egrave;s la premi&egrave;re &eacute;coute, j&rsquo;ai compris que ce chant avait une mission &raquo;, confie-t-elle. Il ne s&rsquo;agissait plus de performance, mais de v&eacute;rit&eacute;.</p>\r\n<p data-start=\"1008\" data-end=\"1354\">Quand la musique devient miroir d&rsquo;&acirc;me.<br data-start=\"1046\" data-end=\"1049\">Ce n&rsquo;est pas une chanson &eacute;crite pour le plaisir des mots. C&rsquo;est une chanson qui raconte leurs saisons : les leurs, et celles de tant d&rsquo;autres. Des temps de d&eacute;sert, d&rsquo;attente interminable, de pri&egrave;res sans r&eacute;ponse. Mais aussi des temps de perc&eacute;e, de rel&egrave;vement, de foi qui rena&icirc;t quand tout semblait fini.</p>\r\n<p data-start=\"1356\" data-end=\"1475\"><strong data-start=\"1356\" data-end=\"1368\">SEZON SA</strong>, c&rsquo;est le cri discret de ceux qui n&rsquo;ont plus de voix, mais qui gardent, malgr&eacute; tout, une flamme allum&eacute;e.</p>\r\n<p data-start=\"1477\" data-end=\"1720\">Deux temporalit&eacute;s, une seule esp&eacute;rance.<br data-start=\"1516\" data-end=\"1519\">Dans ce morceau, deux temps se rencontrent :<br data-start=\"1563\" data-end=\"1566\">&mdash; le temps humain, celui o&ugrave; chaque jour semble un combat ;<br data-start=\"1624\" data-end=\"1627\">&mdash; et le temps de Dieu, celui qu&rsquo;on ne comprend pas toujours, mais qui tombe toujours juste.</p>\r\n<p data-start=\"1722\" data-end=\"1887\">Au milieu de cette tension, la voix de Sherline et celle de Garby s&rsquo;&eacute;l&egrave;vent pour dire :<br data-start=\"1809\" data-end=\"1812\">&laquo; Le ciel a d&eacute;j&agrave; d&eacute;cid&eacute; en ta faveur, m&ecirc;me si tu ne le vois pas encore. &raquo;</p>\r\n<p data-start=\"1889\" data-end=\"2265\">Un clip sobre, habit&eacute;, centr&eacute; sur l&rsquo;essentiel.<br data-start=\"1935\" data-end=\"1938\">Capt&eacute; par l&rsquo;&oelig;il aiguis&eacute; de Milot Joseph et R&eacute;mi Hermoso, et port&eacute; par une r&eacute;alisation sobre et puissante sign&eacute;e <strong data-start=\"2050\" data-end=\"2062\">KONEKTEM</strong>, le clip de <strong data-start=\"2075\" data-end=\"2087\">SEZON SA</strong> frappe d&rsquo;embl&eacute;e par sa sinc&eacute;rit&eacute; brute. Pas d&rsquo;effets superflus. Aucun d&eacute;cor ostentatoire. Le langage de l&rsquo;image se veut d&eacute;pouill&eacute;, presque nu, pour mieux laisser parler l&rsquo;&acirc;me.</p>\r\n<p data-start=\"2267\" data-end=\"2404\">Chaque regard, chaque silence devient un plan fort, charg&eacute; de tension ou d&rsquo;apaisement. Les mots, eux, tombent comme des coups de gr&acirc;ce.</p>\r\n<p data-start=\"2406\" data-end=\"2685\">Un chant qui veille sur ceux qui n&rsquo;ont plus la force de prier.<br data-start=\"2468\" data-end=\"2471\">Pour Garby Mesidor, la louange n&rsquo;est pas un style, c&rsquo;est une mission. Et pour Sherline Dalberis, ce chant est un pont. Entre le ciel et ceux qui n&rsquo;arrivent plus &agrave; prier. Entre Dieu et ceux qui se croient oubli&eacute;s.</p>\r\n<p data-start=\"2687\" data-end=\"2925\"><strong data-start=\"2687\" data-end=\"2699\">SEZON SA</strong>, c&rsquo;est une voix lev&eacute;e au nom de toute une g&eacute;n&eacute;ration qui cherche des r&eacute;ponses. Une g&eacute;n&eacute;ration qui, peut-&ecirc;tre, n&rsquo;a pas besoin de nouveaux discours, mais d&rsquo;une simple v&eacute;rit&eacute; chant&eacute;e avec foi : Dieu n&rsquo;a jamais quitt&eacute; la pi&egrave;ce.</p>\r\n<p data-start=\"2927\" data-end=\"3090\">Une chanson, une semence.<br data-start=\"2952\" data-end=\"2955\">La sortie officielle du clip est pour tr&egrave;s bient&ocirc;t. Et si cette chanson n&rsquo;avait qu&rsquo;un seul but : raviver une foi, m&ecirc;me toute petite ?</p>\r\n<p data-start=\"3092\" data-end=\"3236\">Comme le dit Sherline, en toute simplicit&eacute; :<br data-start=\"3136\" data-end=\"3139\">&laquo; Si cette chanson peut toucher ne serait-ce qu&rsquo;une seule vie, elle aura accompli sa mission. &raquo;</p>\r\n<p data-start=\"3238\" data-end=\"3331\">Alors pr&eacute;parez vos c&oelig;urs. <strong data-start=\"3264\" data-end=\"3276\">SEZON SA</strong> arrive. Et cette saison pourrait bien &ecirc;tre la v&ocirc;tre.</p>\r\n<p data-start=\"3333\" data-end=\"3406\"><strong data-start=\"3333\" data-end=\"3372\">R&eacute;daction : Ronalson Bryan Blanfort</strong><br data-start=\"3372\" data-end=\"3375\">#konektem <br>#ToutKoteNenp&ograve;tKil&egrave;</p>', '2025-11-29 02:38:31', '2026-02-13 22:38:43', 164, 0, 0, 2, 'Sherline Dalberis & Garby Mesidor', NULL, NULL, '2025-11-28', NULL),
(13, 'Samantha Doizin, la voix dâ€™une foi qui traverse les gÃ©nÃ©rations.', '<p class=\"p1\">&nbsp;</p>\r\n<p class=\"p1\">Dans l&rsquo;univers en constante &eacute;volution de la musique &eacute;vang&eacute;lique contemporaine, certaines voix &eacute;mergent non seulement par leur talent, mais par la force du message qu&rsquo;elles portent. <strong>Samantha Doizin</strong> fait partie de ces artistes dont le parcours incarne &agrave; la fois la foi, la pers&eacute;v&eacute;rance et une vision claire de l&rsquo;avenir.</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\">N&eacute;e le <strong>11 d&eacute;cembre 1998 &agrave; Delmas 19, en Ha&iuml;ti</strong>, Samantha Doizin est la <strong>troisi&egrave;me enfant</strong> d&rsquo;une famille chr&eacute;tienne.</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\">Cet environnement spirituel a profond&eacute;ment influenc&eacute; son identit&eacute;, ses valeurs et son appel artistique. Aujourd&rsquo;hui <strong>mari&eacute;e</strong>, elle poursuit son chemin avec la conviction que chaque &eacute;tape de la vie prend tout son sens lorsqu&rsquo;elle est align&eacute;e avec la volont&eacute; divine.</p>\r\n<p class=\"p1\">Son parcours acad&eacute;mique refl&egrave;te une formation solide et diversifi&eacute;e.</p>\r\n<p class=\"p2\" style=\"text-align: center;\"><img src=\"/admin/uploads/images/696cdb6f012c2.JPG\" alt=\"\" width=\"180\" height=\"225\"></p>\r\n<p class=\"p1\">Elle a effectu&eacute; ses <strong>&eacute;tudes primaires</strong> &agrave; l&rsquo;<strong>Institution Mixte J&eacute;rusalem</strong> (Delmas 31), au <strong>Centre p&eacute;dagogique des Fr&egrave;res Unis</strong>, puis &agrave; <strong>La Conqu&ecirc;te du Savoir</strong>. Pour le <strong>secondaire</strong>, elle a fr&eacute;quent&eacute; le <strong>Lyc&eacute;e du Cent Cinquantenaire</strong>, le <strong>Lyc&eacute;e des Jeunes Filles</strong> ainsi que le <strong>Lyc&eacute;e National de P&eacute;tion-Ville</strong>.</p>\r\n<p class=\"p1\"><br>Anim&eacute;e par un esprit polyvalent, Samantha a ensuite &eacute;tudi&eacute; en <strong>assistanat administratif et marketing</strong> &agrave; l&rsquo;USB et a &eacute;t&eacute; <strong>&eacute;tudiante en sciences juridiques</strong> &agrave; la FDSE.</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\">En parall&egrave;le, elle s&rsquo;est affirm&eacute;e comme <strong>artiste</strong>, <strong>businesswoman</strong>, <strong>event planner</strong> et <strong>MC d&rsquo;&eacute;v&eacute;nements</strong>, multipliant les exp&eacute;riences professionnelles avec rigueur et ambition.</p>\r\n<p class=\"p1\">La musique entre tr&egrave;s t&ocirc;t dans sa vie. Si ses premiers pas se font d&egrave;s l&rsquo;enfance, c&rsquo;est autour de <strong>l&rsquo;&acirc;ge de 10 ans</strong> que sa foi et son engagement artistique prennent une dimension plus profonde. Apr&egrave;s avoir &eacute;volu&eacute; dans plusieurs groupes, elle fait aujourd&rsquo;hui partie de <strong>deux formations musicales actives</strong>. Parmi ses influences majeures figurent l&rsquo;artiste <strong>Jorvin Keyz</strong>, ainsi que les chorales <strong>Healing Gospel</strong> et <strong>Gospel Kreyol</strong>, qui nourrissent sa sensibilit&eacute; musicale et spirituelle.</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\">Sa carri&egrave;re musicale professionnelle d&eacute;bute en <strong>2018</strong>, &agrave; la suite d&rsquo;un <strong>test pressing</strong> d&eacute;terminant. Depuis, Samantha Doizin a sign&eacute; plusieurs titres remarqu&eacute;s, notamment <em>&Agrave; jamais</em>, <em>M&rsquo;paka pa louwe&rsquo;w</em> et <em>Tout pour moi</em>.</p>\r\n<p class=\"p1\"><br>Le <strong>6 janvier dernier</strong>, elle a d&eacute;voil&eacute; <strong>&laquo; Buisson Ardent &raquo;</strong>, une &oelig;uvre forte et profond&eacute;ment spirituelle. Bien plus qu&rsquo;un simple single, ce titre s&rsquo;inscrit comme <strong>l&rsquo;un des morceaux cl&eacute;s de l&rsquo;album actuellement en pr&eacute;paration, pr&eacute;vu pour 2026</strong>.</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\">&Agrave; travers sa musique, Samantha Doizin transmet un message clair et inspirant : pendant que l&rsquo;on attend, il faut agir. Chacun est une &eacute;toile n&eacute;e pour briller, et le moment venu, <strong>aucun obstacle ne pourra emp&ecirc;cher cette lumi&egrave;re de rayonner</strong>. Elle rappelle avec force une v&eacute;rit&eacute; essentielle qui guide sa vie et sa carri&egrave;re : <strong>le temps de Dieu est parfait</strong>.</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\">Son public est invit&eacute; &agrave; d&eacute;couvrir <em>Buisson Ardent</em> et &agrave; se laisser porter par ce message d&rsquo;esp&eacute;rance et de foi via sa cha&icirc;ne officielle :<br><a title=\"Samantha Doizin, la voix d&rsquo;une foi qui traverse les g&eacute;n&eacute;rations.\" href=\"https://youtube.com/@samanthadoizinofficial?si=UaMiK5SnPDZBY6GN\"><span class=\"s1\">&eth;&Yuml;&lsquo;&permil;</span> <span class=\"s2\">https://youtube.com/@samanthadoizinofficial?si=UaMiK5SnPDZBY6GN</span></a></p>\r\n<p class=\"p1\">Samantha Doizin continue d&rsquo;avancer avec une certitude : <strong>sans public, il n&rsquo;y a pas d&rsquo;artistes</strong>. Et avec une foi in&eacute;branlable, elle poursuit sa mission &mdash; connecter, inspirer et &eacute;lever les c&oelig;urs &agrave; travers la musique.</p>', '2026-01-18 13:10:34', '2026-02-13 22:38:40', 207, 0, 0, 1, 'Samantha Doizin', NULL, NULL, '2026-01-18', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `livestream`
--

CREATE TABLE `livestream` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stream_date` datetime NOT NULL,
  `stream_title` mediumtext NOT NULL,
  `stream_key` varchar(50) NOT NULL,
  `connected` bigint(20) UNSIGNED DEFAULT NULL,
  `owner` varchar(255) NOT NULL DEFAULT 'admin@konektem.net'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `livestream`
--

INSERT INTO `livestream` (`id`, `stream_date`, `stream_title`, `stream_key`, `connected`, `owner`) VALUES
(3, '2025-11-15 21:30:00', 'Stream test', 'ywYWI7xADZDdAW69', NULL, 'konektem'),
(4, '2026-03-07 13:00:00', 'Test', 'd29109ed555dba9c4ba9', NULL, 'konektem');

-- --------------------------------------------------------

--
-- Структура таблицы `mainpagecontent`
--

CREATE TABLE `mainpagecontent` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `newsSlide` bigint(20) UNSIGNED DEFAULT NULL,
  `fadeNews` bigint(20) UNSIGNED DEFAULT NULL,
  `music` bigint(20) UNSIGNED DEFAULT NULL,
  `events` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `mainpagecontent`
--

INSERT INTO `mainpagecontent` (`id`, `newsSlide`, `fadeNews`, `music`, `events`) VALUES
(5, 32, 14, 102, 8),
(6, 29, 59, 24, NULL),
(14, 33, 5, 17, NULL),
(15, 34, 60, 23, NULL),
(16, 27, 61, 20, NULL),
(17, 28, NULL, 22, NULL),
(18, 24, NULL, 18, NULL),
(19, 25, NULL, 40, NULL),
(20, 21, NULL, 43, NULL),
(21, 22, NULL, NULL, NULL),
(22, 23, NULL, NULL, NULL),
(23, 19, NULL, NULL, NULL),
(24, 35, NULL, 21, NULL),
(25, 36, 16, 38, NULL),
(26, 37, 15, 32, NULL),
(27, 38, 18, NULL, NULL),
(28, 39, NULL, NULL, NULL),
(37, 40, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `music`
--

CREATE TABLE `music` (
  `artist_id` bigint(20) UNSIGNED DEFAULT NULL,
  `id` bigint(20) UNSIGNED NOT NULL,
  `track_name` varchar(100) NOT NULL,
  `track_img_id` bigint(20) UNSIGNED DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `downloads` int(11) DEFAULT 0,
  `plays` int(11) DEFAULT 0,
  `shares` int(11) DEFAULT 0,
  `order_no` int(11) DEFAULT 0,
  `owner` varchar(255) NOT NULL DEFAULT 'admin@konektem.net',
  `genre` varchar(255) DEFAULT NULL,
  `album_id` bigint(20) UNSIGNED DEFAULT NULL,
  `audio_url` varchar(255) DEFAULT NULL,
  `track_number` int(11) DEFAULT NULL,
  `image_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `music`
--

INSERT INTO `music` (`artist_id`, `id`, `track_name`, `track_img_id`, `location`, `mime_type`, `likes`, `downloads`, `plays`, `shares`, `order_no`, `owner`, `genre`, `album_id`, `audio_url`, `track_number`, `image_id`) VALUES
(9, 17, 'PaPa Kenbe m', 96, '/admin/controllers/../uploads/music/690d3d530791a.mp3', 'audio/mpeg', 7, 7, 39, 4, 2, 'admin@konektem.net', 'Afro Beats', NULL, NULL, NULL, NULL),
(10, 18, 'Abba', 97, '/admin/controllers/../uploads/music/690d41b68d2fd.mp3', 'audio/mpeg', 7, 16, 77, 2, 1, 'admin@konektem.net', 'Worship', NULL, NULL, NULL, NULL),
(11, 19, 'Se nan ou Senyè mwen kapab viv', 98, '/admin/controllers/../uploads/music/690d42d856d84.mp3', 'audio/mpeg', 6, 3, 22, 0, 6, 'admin@konektem.net', 'Jazz', NULL, NULL, NULL, NULL),
(12, 20, 'Pi Bon Zanmi', 135, '/admin/controllers/../uploads/music/692374e9b6b5a.mp3', 'audio/mpeg', 4, 8, 29, 2, 4, 'admin@konektem.net', 'gospel', NULL, NULL, NULL, NULL),
(14, 22, 'Sa se lèm respire', 137, '/admin/controllers/../uploads/music/69237e1b60754.mp3', 'audio/mpeg', 4, 4, 29, 4, 3, 'admin@konektem.net', 'Worship praisr', NULL, NULL, NULL, NULL),
(16, 24, 'Un Ami Sûr', 139, '/admin/controllers/../uploads/music/692380009583a.mp3', 'audio/mpeg', 6, 14, 57, 6, 5, 'admin@konektem.net', 'Pop', NULL, NULL, NULL, NULL),
(5, 32, 'Hyg', 174, '/admin/controllers/../uploads/music/694d7391454dd.mp3', 'video/mp4', 1, 1, 1, 1, 32, 'Jorbyart', NULL, NULL, NULL, NULL, NULL),
(17, 34, 'Sezon sa', 189, '/admin/controllers/../uploads/music/6954aeaf83e17.mp3', 'audio/mpeg', 1, 0, 2, 0, 34, 'JoFlungBouzy', NULL, NULL, NULL, NULL, NULL),
(18, 35, 'KÃ²mandan ', 199, '/admin/controllers/../uploads/music/695ab9d3220c5.mp3', 'audio/mpeg', 1, 2, 0, 0, 0, 'admin@konektem.net', 'gospel', NULL, NULL, NULL, NULL),
(18, 36, 'KÃ²mandan ', 203, '/admin/controllers/../uploads/music/695dd992c94a0.mp3', 'audio/mpeg', 0, 0, 0, 0, 36, 'AbdullahMode', NULL, NULL, NULL, NULL, NULL),
(19, 37, 'M PAKA ECHWE', 204, '/admin/controllers/../uploads/music/69613e86e425f.mp3', 'audio/mpeg', 0, 0, 0, 0, 37, 'AbdullahMode', NULL, NULL, NULL, NULL, NULL),
(2, 38, 'beautiful', 205, '/admin/controllers/../uploads/music/6962b80d8824c.mp3', 'audio/mpeg', 0, 0, 0, 0, 38, 'zwelakhemaseko02_konektem', NULL, NULL, NULL, NULL, NULL),
(1, 39, 'Rap God', 264, '/admin/controllers/../uploads/music/69934fb15ad08.mp3', 'audio/mpeg', 1, 0, 0, 0, 39, 'masekozw', 'Rap', NULL, NULL, NULL, NULL),
(20, 40, 'Mwen pa pou kont mwen', 265, '/admin/controllers/../uploads/music/699376607044c.mp3', 'audio/mpeg', 1, 0, 0, 0, 1, 'admin@konektem.net', 'Worship', NULL, NULL, NULL, NULL),
(23, 43, 'Siwo myèl', 268, '/admin/controllers/../uploads/music/6993f4426adef.mp3', 'audio/mpeg', 2, 2, 0, 0, 1, 'admin@konektem.net', 'Konpa', NULL, NULL, NULL, NULL),
(24, 44, 'Wa sonje nou', 269, '/admin/controllers/../uploads/music/699478a75f1e1.mp3', 'audio/mpeg', 1, 0, 0, 0, 44, 'MINISTREVALEURDELHOMMETV', 'Worswip', NULL, NULL, NULL, NULL),
(2, 52, 'Sa se lèm respire', NULL, NULL, 'audio/mpeg', 0, 0, 0, 0, 0, 'zvelake', 'rap', 11, '/konektem/config/../uploads/zvelake_album_myalbum/69d2965327946.mp3', 1, 315),
(26, 58, 'Kanpe goumen', 320, '/admin/controllers/../uploads/music/69d4f65f7648d.mp3', 'audio/mpeg', 0, 0, 0, 0, 58, 'wewe234rtqwe4', 'Compas', NULL, NULL, NULL, NULL),
(25, 59, '4- WI NOU KAPAB .mp3', NULL, '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebc731.mp3', 'audio/mpeg', 0, 0, 0, 0, 59, 'masekozw', 'Gospel', NULL, NULL, NULL, NULL),
(25, 60, '5- KOTE M TE YE A TE LWEN .mp3', NULL, '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebd29b.mp3', 'audio/mpeg', 0, 0, 0, 0, 60, 'masekozw', 'Gospel', NULL, NULL, 5, NULL),
(25, 61, '6- SORRY MANMAN .mp3', NULL, '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebde8b.mp3', 'audio/mpeg', 0, 0, 0, 0, 61, 'masekozw', 'Gospel', NULL, NULL, 6, NULL),
(25, 62, '7- SAW AP TANN MASTER .mp3', NULL, '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebe80c.mp3', 'audio/mpeg', 0, 0, 0, 0, 62, 'masekozw', 'Gospel', NULL, NULL, 7, NULL),
(27, 63, '1- VWA YO PA TANDE YO .mp3', NULL, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45ac2c.mp3', 'audio/mpeg', 0, 0, 0, 0, 0, 'masekozw', 'Evanjelik', 14, NULL, 1, NULL),
(27, 64, '2- GADON GRAS .mp3', NULL, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45b546.mp3', 'audio/mpeg', 0, 0, 0, 0, 0, 'masekozw', 'Evanjelik', 14, NULL, 2, NULL),
(27, 65, '3- PAPA KENBE M .mp3', NULL, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45bd29.mp3', 'audio/mpeg', 0, 0, 0, 0, 0, 'masekozw', 'Evanjelik', 14, NULL, 3, NULL),
(27, 66, '4- WI NOU KAPAB .mp3', NULL, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45c505.mp3', 'audio/mpeg', 0, 0, 0, 0, 0, 'masekozw', 'Evanjelik', 14, NULL, 4, NULL),
(27, 67, '5- KOTE M TE YE A TE LWEN .mp3', NULL, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d415e.mp3', 'audio/mpeg', 0, 0, 0, 0, 67, 'masekozw', 'Evanjelik', 14, NULL, 5, NULL),
(27, 68, '6- SORRY MANMAN .mp3', NULL, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d4c5c.mp3', 'audio/mpeg', 0, 0, 0, 0, 68, 'masekozw', 'Evanjelik', 14, NULL, 6, NULL),
(27, 69, '7- SAW AP TANN MASTER .mp3', NULL, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d5877.mp3', 'audio/mpeg', 0, 0, 0, 0, 69, 'masekozw', 'Evanjelik', 14, NULL, 7, NULL),
(27, 70, '8- MIRAK .mp3', NULL, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d618b.mp3', 'audio/mpeg', 0, 0, 0, 0, 70, 'masekozw', 'Evanjelik', 14, NULL, 8, NULL),
(27, 71, '9- PRIYE .mp3', NULL, '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d292098d.mp3', 'audio/mpeg', 0, 0, 0, 0, 71, 'Jorby', 'Evanjelik', 14, NULL, 9, NULL),
(27, 72, '10- MEN DYAMAN .mp3', NULL, '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d2921bb6.mp3', 'audio/mpeg', 0, 0, 0, 0, 72, 'Jorby', 'Evanjelik', 14, NULL, 10, NULL),
(27, 73, '11- EWO .mp3', NULL, '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d29228ff.mp3', 'audio/mpeg', 0, 0, 0, 0, 73, 'Jorby', 'Evanjelik', 14, NULL, 11, NULL),
(27, 74, '12- POZE W .mp3', NULL, '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d29235cc.mp3', 'audio/mpeg', 0, 0, 0, 0, 74, 'Jorby', 'Evanjelik', 14, NULL, 12, NULL),
(27, 75, 'INTRO .mp3', NULL, '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50dd5ed893.mp3', 'audio/mpeg', 0, 0, 0, 0, 75, 'Jorby', 'Evanjelik', 14, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `newsImg` bigint(20) UNSIGNED DEFAULT NULL,
  `newsTitle` varchar(150) DEFAULT NULL,
  `newsDate` date DEFAULT NULL,
  `newsCategory` varchar(200) DEFAULT NULL,
  `newsHeadline` text NOT NULL,
  `fullContent` mediumtext DEFAULT NULL,
  `reads` int(11) NOT NULL DEFAULT 0,
  `likes` int(11) NOT NULL DEFAULT 0,
  `shares` int(11) NOT NULL DEFAULT 0,
  `featured_image_ids` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `newsImg`, `newsTitle`, `newsDate`, `newsCategory`, `newsHeadline`, `fullContent`, `reads`, `likes`, `shares`, `featured_image_ids`) VALUES
(5, 54, 'SPBPU - Peter The Great University With African Leaders To Launch Educational Programs in Africa', '2025-09-08', 'sports', 'Perhaps, it was about the cooperation of the Peter the Great St. Petersburg Polytechnic University (SPbPU) with African countries in the field of education.', 'In January 2025, it was reported that at the 12th meeting of the mixed intergovernmental Russian-Algerian commission, agreements on scientific exchanges and joint educational programs were signed between SPbPU and a number of Algerian universities.\r\n\r\nSPbPU is the coordinator of the consortium of Russian and African universities and research organizations \"Russian-African Network University\" (RAFU). The consortium implements, in particular, short-term educational programs for African citizens.                                                                                                                                        ', 1, 1, 1, NULL),
(14, 70, 'Tanpèt Twopikal Melissa menase divès Peyi nan Karayib la.', '2025-10-26', 'sports', 'Sant meteyowolojik Etazini ak Ewòp yo ap swiv ak anpil atansyon yon sistèm presyon ki ba k ap devlope nan lanmè Karayib la. Fenomèn sa a, yo rele \"Invest 98L\", t a kapab vin tounen \"tanpèt twopikal Melissa\" nan kèk èdtan, dapre \"National Hurricane Center (NHC)\".', 'Sant meteyowolojik Etazini ak Ewòp yo ap swiv ak anpil atansyon yon sistèm presyon ki ba k ap devlope nan lanmè Karayib la. Fenomèn sa a, yo rele \"Invest 98L\", t a kapab vin tounen \"tanpèt twopikal Melissa\" nan kèk èdtan, dapre \"National Hurricane Center (NHC)\".\r\n\r\n\r\nPou kounye a, gwo nyaj ak loraj ap ogmante pandan sistèm nan ap deplase direksyon nòdwès. NHC estime gen 100 % chans pou li devlope an siklòn, sa ki vle di risk la rive nan pi wo nivo. Menm si sant tanpèt la poko byen fòme, espesyalis yo avèti  kondisyon tanperati lanmè a ak van yo favorab anpil pou li vin pi fò byen vit.\r\n\r\n\r\nAyiti nan zòn ki anba menas Daprè meteyowològ ameriken Jeff Berardelli (WFLA / CBS News), li avanse pou fè konnen : Ayiti, Repiblik Dominikèn ak Bahamas rete nan zòn ki plis ekspoze si sistèm nan chanje direksyon epi monte pi pre direksyon nò nan jou k ap vini yo. Otorite nan rejyon an deja ankouraje popilasyon an pou yo rete vijilan, swiv bilten meteyo yo, epi pare plan ijans yo. Si tanpèt la fòme vre, li ka pote anpil lapli, van fò, ak risk inondasyon sou plizyè peyi nan Karayib la.\r\n\r\n\r\n#esansyèl\r\n\r\n#meteyo\r\n\r\n#konektem\r\n\r\n#toutkotenenpotkilè                                ', 0, 0, 0, NULL),
(15, 73, ' Kyria Salina Romusca ap reprezante Ayiti nan Miss Tourism World 2025 nan peyi Lachin.', '2025-10-28', 'sports', 'Kyria Salina Romusca, ki kouwonnen \"Miss Tourism World Haïti 2025\" depi 22 dawout, yon inisyativ Organisation Miss Haïti Caraïbes, li pral pote drapo', 'Miss Tourism World se yon platfòm entènasyonal ki rasanble plizyè peyi pou fè pwomosyon touris ak divèsite kiltirèl. Ane sa a, jèn modèl ayisyèn sila a, ki soti Latibonit, ap vin yon anbasadè pou Ayiti, nan objektif pou fè mond lan dekouvri richès atistik, mizikal, gastronomik ak ospitalite pèp ayisyen an.\r\n\r\n\r\nPou Kyria, patisipasyon sa a se plis pase yon kouwòn. Etidyan nan odontoloji ak kominikasyon, li mete lafwa li kòm pilye nan pakou l, pandan l ap pwone anmenm tan: konesans jeneral, ekspresyon sou sèn, bote fizik ak fòs espirityèl li. Li deklare : « Reprezante Ayiti se pote fyète, bèlte ak rezistans yon pèp ki merite klere pi plis toujou. »\r\n\r\n\r\n#esansyèl\r\n\r\n#aktyalite\r\n\r\n#konektem\r\n\r\n#toutkotenenpotkilè                ', 0, 1, 1, NULL),
(16, 74, 'ULCC fè bilan sou deklarasyon patrimwàn ajan piblik yo.', '2025-10-28', 'sports', 'Jodi premye septanm 2025 lan, L’Unité de Lutte Contre la Corruption (ULCC), fè konnen li konstate gwo avanse k ap fèt nan deklarasyon patrimwàn ajan piblik yo, jan sa mande dapre lwa 12 fevriye 2008 la. Nan yon nòt ki pibliye, enstitisyon an rapòte tout manm Konsèy Prezidansyèl Transisyon (KPT) deja ranpli obligasyon yo. Mete sou sa, 90% minis ak sekretè Deta deja depoze deklarasyon yo, malgre toujou gen 2 minis ak 2 sekretè Deta ki poko konfòme yo, malgre rapèl ki te fèt yo.', 'Pou rive nan rezilta sa yo, ULCC mete plizyè estrateji sou pye: jounen deklarasyon espesyal, piblikasyon yon gid pratik, kanpay sansibilizasyon,  epi voye 120 dosye nan lajistis pou defo deklarasyon. Enstitisyon an salye tou kontribisyon òganizasyon sosyete sivil yo nan pwosesis sila.\r\n\r\n\r\nRechèch ki te fèt an 2022 sou lwa deklarasyon patrimwàn nan pèmèt yo lanse plizyè refòm avèk sipò patnè teknik ak finansye, espesyalman pou ranfòse sistèm SYDEP III. Nan twa dènye ane yo, kantite deklarasyon yo ogmante pa 535%, yon pwogrè ULCC atribiye ak aksyon prevantif ak represif li yo.\r\n\r\n\r\nMalgre tout efò sa yo, gen toujou gwo fonksyonè ki evite respekte lwa a. Sa montre sistèm nan toujou gen limit li, e batay pou transparans total nan sektè piblik la rete yon defi.\r\n\r\n\r\n#esansyèl\r\n\r\n#aktyalite\r\n\r\n#konektem\r\n\r\n#toutkotenenpotkilè\r\n\r\nAktyalite        ', 0, 1, 4, NULL),
(18, 83, 'Melissa en Haïti : 24 morts, 18 disparus et des blessés.', '2025-10-29', 'sports', 'Haïti enregistre un lourd bilan provoqué par le passage de l’ouragan Melissa.', 'Haïti enregistre un lourd bilan provoqué par le passage de l’ouragan Melissa. Selon le bilan provisoire établi a la date du 29 octobre 2025 par les responsables de la protection civile on dénombre 24 décès, 18 personnes portées disparues et des blessés.\r\nC\'est la région de Petit-Goâve qui déplore le bilan le plus lourd. Des inondations provoquées par la rivière La Digue ont entraîné 20 morts et 18 disparus.         ', 0, 1, 9, NULL),
(19, 84, 'Palmarès Mizik/Videyo Evanjelik – Oktòb 2025', '2025-10-31', 'sports', 'Chak mwa, nan ane a, make ak bèl zèv atistik ki pote enspirasyon nan mond mizik evanjelik la. \r\nPalmarès Mizik/Videyo Evanjelik – oktòb 2025 ap prepare pou pibliye Premye novanm 2025.', 'Menm jan a chak edisyon, seleksyon sa pral mete an valè pwojè ki make milye evanjelik la, ak mizik ki pote mesaj favè, kouraj, lafwa.... ki itilize nan chak moman adorasyon. Se yon moman kote talan, lafwa ak kreyativite kontinye rankontre pou glorifye non Bondye epi enspire jenerasyon aktyèl la.\r\n\r\nKONEKTEM envite tout moun rete branche, pou dekouvri nouvo palmarès la, pandan pral gade videyo yo, tande ak pataje  mizik yo, epi soutni chak atis k ap sèvi ak mizik pou pote limyè ak espwa nan kè chak moun.\r\n\r\n👉 Randevou 1e oktòb 2025 — Palmarès la ap pare, pa rate l!        ', 0, 0, 0, NULL),
(20, 85, 'Toune pwomosyonè albòm \"Vwa Yo Pa tande yo\"', '2025-11-03', 'entertainment', 'Pandan n ap tann dat 16 novanm 2025 lan, toune « Vwa yo pa tande yo » kontinye avanse sou wout li.', 'Dimanch 2 novanm 2025 lan, gwoup Zòn Pa Fè Moun Band, vizite Tabernacle El Elyon ak Legliz Batis Delmas Béthel.\r\n\r\nVizit sa yo antre nan kad preparasyon pou lansman twazyèm albòm timoun yo, yon pwojè ki pote yon mesaj ki chaje ak espwa : fè tande vwa moun yo pa tande yo, epi reba w espwa ak sila yo ki dekouraje « Vwa Yo Pa Tande Yo ».\r\n\r\nKat pou konsè a deja disponib nan tout pwen vant ofisyèl yo.\r\n\r\nChak moun ki achte yon kat ap patisipe dirèkteman nan sipòte Fondasyon Fr. Luckson Zòn Pa Fè Moun, ki kontinye misyon li pou fòme, akonpaye ak ankouraje timoun yo atravè edikasyon, mizik ak lafwa.                ', 0, 0, 0, NULL),
(21, 114, 'Fatima Bosch : Miss Univers 2025', '2025-11-21', 'sports', 'Nan lannwit 20 pou rive 21 novanm 2025, Fatima Bosch, reprezantan Peyi Meksik, eli Miss Univers 2025 pandan 74yèm edisyon konkou a ki te fèt nan Impact Arena, Pak Kret, Thailand.', 'Nan lannwit 20 pou rive 21 novanm 2025, Fatima Bosch, reprezantan Peyi Meksik, eli Miss Univers 2025 pandan 74yèm edisyon konkou a ki te fèt nan Impact Arena, Pak Kret, Thailand. Li ranplase Victoria Kjær Theilvig (Denmark) e vin katriyèm Meksikèn ki genyen tit sa a.\r\n\r\nAvèk 25 lane, Bosch se modèl, kreyatè mòd dirab, ak aktivis sosyal. Viktwa li vini apre yon kontwovès piblik ak òganizatè konkou a, kote li te reponn avèk dinite, mete aksan sou respè ak fòs fanm.\r\n\r\nEdisyon 2025 lan te make tou pa eliminasyon Melissa Queenie Sapini, reprezantan Ayiti, anvan lis Top 30 la. Viktwa Bosch simbolize rezistans, diyite, ak detèminasyon nan yon konkou mondyal.                ', 0, 0, 0, NULL),
(22, 115, ' #OuvèPeyiA : Yon kanpay Grenadye yo lanse.', '2025-11-21', 'sports', 'Jou ki te madi 18 Novanm nan, apre gwo viktwa Grenadye yo, ki kalifye ayiti pou mondyal 2026 la, yon inisyativ te lanse \"Hashtag #OuvèPeyiA\". Kanpay sila te pran nesans li nan bis ki t ap mennen jwè seleksyon nasyonal Ayiti yo pou rive nan otèl la.', 'Jou ki te madi 18 Novanm nan, apre gwo viktwa Grenadye yo, ki kalifye Ayiti pou mondyal 2026 la, yon inisyativ te lanse \"Hashtag #OuvèPeyiA\". Kanpay sila te pran nesans li nan bis ki t ap mennen jwè seleksyon nasyonal Ayiti a pou rive nan otèl la. Sou ensistans defansè Ricardo Adé, tout jwè yo te mete vwa yo ansanm pou repete menm mesaj la : « #OuvèPeyiA ». Plis pase yon senp slogan, ekspresyon sa a se kri kè yon gwoup jèn gason ki sot bay tout enèji yo ak tout nanm yo pou fè Ayiti kalifye pou Mondyal 2026 la, aprè 52 lane san prezans nou nan pi gwo konpetisyon foutbòl mondyal la.\r\n\r\nNazon, Danley, Adé… pou n site kèk nan non sa yo sèlman, ensiste sou nesesite pou tout aktè nan sosyete a pran responsabilite yo pou retabli yon klima lapè nan peyi a. Pou yo, viktwa sa a dwe sèvi kòm yon apèl pou konsyans kolektif. “Nou bezwen vin nan peyi nou. Nou bezwen jwe nan Stad Sylvio Cator. Tout moun ki gen responsablite nan peyi a, pran responsablite nou. Ouvè wout yo, ouvè ayopò yo, kite moun yo viv,” se mesaj klè Grenadye yo voye bay tout otorite ak tout moun ki gen enfliyans sou sitiyasyon aktyèl la.\r\n\r\nJodi a, kalifikasyon sa a pa senpman yon rezilta espòtif ; li tounen yon senbòl espwa pou tout yon pèp k ap lite chak jou pou l respire, pou l sikile, pou l viv ak diyite. Hashtag #OuvèPeyiA vin tounen vwa jèn yo, vwa espò a, vwa nasyon an, ki mande yon bagay senp men esansyèl : Lapè, louvri peyi a, epi kite lavi reprann.        ', 0, 0, 0, NULL),
(23, 116, 'Deedson Louicius demanti rimè sou foto ak Lionel Messi an.', '2025-11-21', 'sports', 'Depi kèk tan, yon enfòmasyon ki t ap sikile sou rezo sosyal yo fè kwè jwè entènasyonal ayisyen Deedson Louicius se t a', '➡️ Depi kèk tan, yon enfòmasyon ki t ap sikile sou rezo sosyal yo fè kwè jwè entènasyonal ayisyen Deedson Louicius se t a timoun ki parèt sou foto Lionel Messi te pran lè li te vizite Ayiti an 2010. \r\n\r\nDeedson Louicius konfime pèsonèlman bay media Haiti-Tempo se yon rimè ki pa gen okenn rapò ak li. Li fè konnen istwa sa a pa konsène l ditou, li mande pou yo envite simaye enfòmasyon ki pa verifye.        ', 0, 0, 0, NULL),
(24, 140, 'Gesny Pierre Louis chanpyon nan konkou patinaj nan peyi Ekwatè.', '2025-11-24', 'sports', 'Pandan konpetisyon patinaj entènasyonal ki te fèt nan peyi Ekwatè, Ayiti te manke pèdi posiblite  pou l te monte sou podium nan, akoz yon vis ki te retire nan paten Gesny Pierre Louis.', 'Pandan konpetisyon patinaj entènasyonal ki te fèt nan peyi Ekwatè, Ayiti te manke pèdi posiblite  pou l te monte sou podium nan, akoz yon vis ki te retire nan paten Gesny Pierre Louis. Se te yon moman tansyon ak laperèz pou piblik la, men detèminasyon, konsantrasyon ak lanmou pou drapo a pa t kite l abandone. Malgre gwo difikilte sila yo, Gesny kontinye pèfòmans, yon jès kouraj ki te leve tout sal la kanpe pou aplodi l.\r\n\r\nSe konsa, kont tout atant, Ayiti rive pran premye plas la nan konpetisyon an, epi ranpòte meday lò a, drapo ble e wouj la flote byen wo sou tè Ekwatè. Viktwa sa a se pa sèlman yon siksè espòtif, men tou yon prèv klè pèp ayisyen an pa janm bay legen, menm lè sitiyasyon an sanble pèdi. Gesny Pierre Louis vin tounen yon senbòl fyète, kouraj ak rezistans pou tout yon jenerasyon.\r\n\r\nNan deklarasyon li, atlèt la eksprime gwo fyète l dèske li te kapab reprezante Ayiti nan gwo bout konpetisyon entènasyonal sa a, ki konte kòm yon etap enpòtan pou evalyasyon Mondyal la ki pral fèt nan Singapour, soti premye pou rive 4 desanm 2025. Li remèsye Viajespam ansanm ak Anbasad Ayiti nan Chili ki te rann vwayaj la posib, san bliye  inlinefreestyleecuador, quitopatina, @roxxy.1512 pou foto yo, ak tout patnè ki pa janm sispann kwè nan talan ayisyen an.        ', 0, 0, 0, NULL),
(25, 141, 'Gesny Pierre Louis chanpyon nan konkou patinaj nan peyi Ekwatè.', '2025-11-24', 'sports', 'Pandan konpetisyon patinaj entènasyonal ki te fèt nan peyi Ekwatè, Ayiti te manke pèdi posiblite  pou l te monte sou podium nan, akoz yon vis ki te retire nan paten Gesny Pierre Louis.', 'Pandan konpetisyon patinaj entènasyonal ki te fèt nan peyi Ekwatè, Ayiti te manke pèdi posiblite  pou l te monte sou podium nan, akoz yon vis ki te retire nan paten Gesny Pierre Louis. Se te yon moman tansyon ak laperèz pou piblik la, men detèminasyon, konsantrasyon ak lanmou pou drapo a pa t kite l abandone. Malgre gwo difikilte sila yo, Gesny kontinye pèfòmans, yon jès kouraj ki te leve tout sal la kanpe pou aplodi l.\r\n\r\nSe konsa, kont tout atant, Ayiti rive pran premye plas la nan konpetisyon an, epi ranpòte meday lò a, drapo ble e wouj la flote byen wo sou tè Ekwatè. Viktwa sa a se pa sèlman yon siksè espòtif, men tou yon prèv klè pèp ayisyen an pa janm bay legen, menm lè sitiyasyon an sanble pèdi. Gesny Pierre Louis vin tounen yon senbòl fyète, kouraj ak rezistans pou tout yon jenerasyon.\r\n\r\nNan deklarasyon li, atlèt la eksprime gwo fyète l dèske li te kapab reprezante Ayiti nan gwo bout konpetisyon entènasyonal sa a, ki konte kòm yon etap enpòtan pou evalyasyon Mondyal la ki pral fèt nan Singapour, soti premye pou rive 4 desanm 2025. Li remèsye Viajespam ansanm ak Anbasad Ayiti nan Chili ki te rann vwayaj la posib, san bliye  inlinefreestyleecuador, quitopatina, @roxxy.1512 pou foto yo, ak tout patnè ki pa janm sispann kwè nan talan ayisyen an.        ', 0, 0, 0, NULL),
(26, 142, 'DCPJ Lanse Yon sit entènèt pou idantifye Bandi ak Evade prizon.', '2025-11-24', 'technology', 'Direksyon Santral Polis Jidisyè a (DCPJ) anonse, jodi lendi 24 novanm nan , lansman ofisyèl sit entènèt li, kote popilasyon an ka jwenn enfòmasyon sou bandi ame ak moun ki chape prizon, depi 2004 pou rive jounen jodi a.', 'Direksyon Santral Polis Jidisyè a (DCPJ) anonse, jodi lendi 24 novanm nan , lansman ofisyèl sit entènèt li, kote popilasyon an ka jwenn enfòmasyon sou bandi ame ak moun ki chape prizon, depi 2004 pou rive jounen jodi a. Inisyativ sa a antre nan kad jefò lapolis ap mennen pou l konbat ensekirite a k ap vale teren nan plizyè zòn nan peyi a.\r\n\r\nNan yon kontèks kote gang yo ap fè lalwa nan plizyè rejyon, Lapolis deside konte sou teknoloji pou l pi byen enfòme sitwayen yo. Dapre otorite polisye yo, plizyè non moun y ap chèche, pami yo gen moun ki konsidere kòm danjere, disponib kounye a sou platfòm nan. Konsa, si yon moun antre non yon sispèk nan bwat rechèch la, li ka jwenn enfòmasyon ki konsène moun sa a, dapre done ki anrejistre yo.\r\n\r\nAtravè demach sa a, DCPJ vle modènize metòd rechèch li yo epi ofri popilasyon an yon zouti serye pou idantifye moun ki enplike nan aktivite kriminèl, dapre sa lapolis fè konnen. Anplis, sit la pèmèt tou pou moun konsilte enfòmasyon ki gen rapò ak kazye jidisyè, yon dokiman enpòtan nan plizyè pwosedi administratif, sitou pou dosye vwayaj.\r\n\r\nPou anpil sitwayen sou rezo sosyal yo, lansman sit sa a se yon bon avansman nan batay kont ensekirite a. Antretan, lapolis repete li pral itilize tout mwayen nesesè pou fasilite arestasyon moun ki enplike nan zak kriminèl epi retabli plis sekirite nan peyi a.\r\n', 0, 0, 0, NULL),
(27, 151, 'FAES distribiye 1500 kit Alimantè bay Fanmi ki nan Nesesite yo nan Maïssade.', '2025-11-26', 'sports', 'Nan kòmansman semèn sa a, Fon Asistans Ekonomik ak Sosyal (FAES) te renouvle angajman li bò kote popilasyon an, espesyalman nan depatman Sant, kote li te fè yon gwo operasyon distribisyon nan komin Maïssade.', 'Nan kòmansman semèn sa a, Fon Asistans Ekonomik ak Sosyal (FAES) te renouvle angajman li bò kote popilasyon an, espesyalman nan depatman Sant, kote li te fè yon gwo operasyon distribisyon nan komin Maïssade. Anviwon 1 500 kit alimantè te remèt bay moun ki deplase ansanm ak fanmi ki nan gwo bezwen yo. Sou direksyon Jean Sadrack Jean François, Direktè Lit kont Povrete nan FAES, yon delegasyon te deplase sou teren pou suiv operasyon an, tande plenyen ak bezwen sitwayen yo, epi asire èd la rive jwenn moun ki gen plis ijans yo.\r\n\r\nInisyativ sa a pa t sèlman yon kesyon distribisyon  manje. Li te vin tounen yon mesaj solidarite ak prezans, nan yon kontèks kote anpil fanmi ap goumen chak jou pou kenbe diyite yo fas ak lavi chè. FAES, ak soutyen Leta ak patnè li yo, vle montre li pa lage sila nan sosyete a ki pi fèb yo, men l ap kontinye apiye yo pou yo ka jwenn plis estabilite ak espwa pou lavni.\r\n\r\nAksyon sa a konfime ankò volonte enstitisyon an pou l rete toupre popilasyon an, aji ak disiplin, konpasyon ak respè pou lavi moun, nenpòt kote bezwen imanitè nesesite.        ', 0, 0, 0, NULL),
(28, 152, 'USCIS Anonse yo koupe TPS pou Ayisyen.', '2025-11-26', 'sports', 'Nan yon desizyon ki  pibliye jodi 26 novanm 2025 lan, DHS anonse  estati pwoteksyon tanporè (Temporary Protected Status, TPS) pou Ayiti ap pran fen, paske dapre yo peyi a “pa satisfè ankò kondisyon eksepsyonèl ki te jistifye TPS la”. ', 'Nan yon desizyon ki  pibliye jodi 26 novanm 2025 lan, DHS anonse  estati pwoteksyon tanporè (Temporary Protected Status, TPS) pou Ayiti ap pran fen, paske dapre yo peyi a “pa satisfè ankò kondisyon eksepsyonèl ki te jistifye TPS la”. \r\n\r\nDesizyon sa a, si pa gen chanjman oswa baz legal altènatif, ka riske mete plizyè milye Ayisyen Ozetazini an danje, jiska prepare pou yo kite peyi a. \r\n\r\nDHS fè konnen, pèmèt Ayisyen rete tanporèman Ozetazini “pa  nan enterè nasyonal ameriken.” Sekretè DHS la, Kristi Noem, prezante revokasyon sa a kòm yon mezi final. \r\n\r\nPou anpil moun, sa kapab vle di pèdi travay, risk depòtasyon, separasyon familyal, ak nesesite pou yo chache lòt mwayen legal pou rete, sa ki pa toujou fasil. Sitiyasyon an kreye presyon emosyonèl ak legal sou kominote Ayisyèn nan nan diaspora a.        ', 0, 0, 0, NULL),
(29, 153, 'Natcom remèt  Grenadye yo  yon chèk 13milyon goud. ', '2025-11-27', 'sports', 'Yï¿½ mï¿½kredi 26 novanm lan, nan yon seremoni natcom te ï¿½ganize , prezidan komite nï¿½malizasyon Federasyon an, Monique Andrï¿½, ansanm ak sekretï¿½ jeneral Patrick Massï¿½nat ak Yvon Sï¿½vï¿½re, te resevwa yon chï¿½k 13 milyon goud pou ekip nasyonal la. ', '<p>Y&egrave; m&egrave;kredi 26 novanm lan, nan yon seremoni natcom te &ograve;ganize , prezidan komite n&ograve;malizasyon Federasyon an, Monique Andr&egrave;, ansanm ak sekret&egrave; jeneral Patrick Mass&iuml;&iquest;&frac12;nat ak Yvon S&iuml;&iquest;&frac12;v&iuml;&iquest;&frac12;re, te resevwa yon ch&egrave;k 13 milyon goud pou ekip nasyonal la. Aksyon sa a te f&egrave;t k&ograve;m yon fason pou mete chapo ba devan eksplwa Grenadye yo reyalize nan dat 18 novanm lan, epi ofri sip&ograve; konkr&egrave; pou ekip la. Donasyon sa vini apre yon pery&ograve;d kote ekip la montre det&egrave;minasyon l sou teren an; j&egrave;s Natcom lan make siy konfyans li nan kapasite Grenadye yo, menm jan l mete konfyans li nan travay komite n&ograve;malizasyon an. Federasyon an di li apresye sip&ograve; sa anpil, yo konsidere li k&ograve;m yon pouse moral pou tout jw&egrave;, antren&egrave; ak ekip teknik la. Nan non federasyon an ak tout aksyon&egrave; yo, yon gwo m&egrave;si ale bay Natcom pou angajman finansye ak moral li. Av&egrave;k sip&ograve; sa a, Grenadye yo espere kontinye leve drapo peyi a pi wo toujou, pandan wout la pou Mondyal 2026 la.</p>', 0, 0, 0, NULL),
(32, 167, 'PalmarÃ¨s Mizik / Videyo Evanjelik - Novanm 2025', '2025-12-01', 'entertainment', 'Mwa novanm 2025 lan kite yon anprent espesyal nan mizik evanjelik ayisyÃ¨n nan. Ant mizik ki selebre fidelite Bondye, videyo ki pote temwayaj pÃ¨sonÃ¨l, ak pwojÃ¨ ki ranfÃ²se misyon evanjelik la, atis yo montre ankÃ² milye a vivan, kreyatif, ak pwofon nan mesaj y ap pote yo.', '<p>Mwa novanm 2025 lan kite yon anprent espesyal nan mizik evanjelik ayisyï¿½n nan. Ant mizik ki selebre fidelite Bondye, videyo ki pote temwayaj pï¿½sonï¿½l, ak pwojï¿½ ki ranfï¿½se misyon evanjelik la, atis yo montre ankï¿½ milye a vivan, kreyatif, ak pwofon nan mesaj y ap pote yo. Palmarï¿½s mwa sa a rasanble tout mizik ki make peryï¿½d la, kï¿½manse nan rap gospel rive nan lwanj tradisyonï¿½l, ak kolaborasyon ki bay nouvo enï¿½ji epi nouvo dimansyon nan pawï¿½l levanjil la. Men prensipal pwojï¿½ ki make mwa Novanm 2025 lan: 1. Fre Gabe &amp; Ed Dagodseed ï¿½ ï¿½PAPA M son Kingï¿½ Sï¿½ti: 3 novanm 2025 Rapï¿½ evanjelik ayisyen Frï¿½ Gabe kontinye ap eksplore mizik kï¿½m zouti espirityï¿½l epi mesaj sosyal parapï¿½ ak nouvo single li ï¿½PAPA M son Kingï¿½, ansanm ak kolaboratï¿½ li Ed Dagodseed. Mizik sa, ki soti anba label Inspiration Divine Studio ak pwodiksyon ReD Vision Plus, fï¿½ pati albï¿½m k ap vini an \"IDAW\" epi pote yon melanj ant rap, adorasyon, ak refleksyon sou pouvwa ak otorite Bondye nan lavi kwayan yo. Nan pawï¿½l yo, mizik la raple kwayan yo Jezi se sï¿½l veritab King, ke legliz la se yon espas pou repantans ak gerizon, kote fidï¿½l yo dwe mete konfyans yo nan pouvwa Bondye pou yo ka konbat tantasyon ak peche. Frï¿½ Gabe ak Ed Dagodseed sï¿½vi ak rap kï¿½m zouti koreksyon ak motivasyon, epi entegre yon langaj modï¿½n pandan y ap pote mesaj ki toujou an liy ak levanjil. ?? https://youtu.be/LM5RYja6Yzc?si=Xf1FCyq4-xa3YUh6 2. Jean Enock Louis ï¿½ ï¿½Gen Plis Toujouï¿½ Sï¿½ti: 5 novanm 2025 Jean Enock Louis lanse nouvo single li ï¿½Gen Plis Toujouï¿½, yon mizik ki pote mesaj pwofetik pou moun k ap chï¿½che direksyon ak benediksyon nan lavi yo. Mizik la mete aksan sou gras an abondans , onksyon, ak benediksyon Bondye, pandan li raple lavi etï¿½nï¿½l deja garanti pou moun ki mache nan prensip bon nouvï¿½l Kris la. Ekri ak konpoze pa Jean Enock Louis, ajoute ak yon videyo ofisyï¿½l ki pote siyati Desperaldo Beatz, ï¿½Gen Plis Toujouï¿½ envite Pï¿½p Bondye a louvri kï¿½ yo, elaji anviwï¿½nman espirityï¿½l yo, epi mete konfyans nan pwomï¿½s Bondye k ap akonpli nan lavi yo. ?? https://youtu.be/vSp3zp7CVIA?si=MtRjqU5kYHsjDMvt 3. Rachel C Poyeau feat. Spencer Brutus ï¿½ ï¿½VIKTWAï¿½ Sï¿½ti: 8 novanm 2025 Rachel C Poyeau prezante nouvo single li ï¿½VIKTWAï¿½, an kolaborasyon ak Spencer Brutus, yon atis k ap kite tras li nan mizik evanjelik. Mizik sa pote yon mesaj fï¿½ sou viktwa ak sipï¿½ Bondye nan lavi pitit li, pandan mizik la reflete eksperyans pï¿½sonï¿½l atis yo ki fï¿½ viktwa devan atak espirityï¿½l nan moman difisil. Viktwa se pa sï¿½lman yon temwayaj, men tou yon rapï¿½l ak Bondye bï¿½ kote nou, viktwa se yon reyalite pou tout moun, kï¿½lkeswa obstak yo. Pawï¿½l yo ankouraje nou pou mete konfyans nan pwoteksyon ak prezans Bondye, pou nou ka travï¿½se defi yo ak kouraj. ?? https://youtu.be/i4HKtGt0l6g?si=rlSqfZ-BtSIplbmq 4. James Alcindor feat. Lynda Joseph ï¿½ ï¿½Bonte Ou Pa Gen Rivalï¿½ Sï¿½ti: 14 novanm 2025 Bonte Ou Pa Gen Rivalï¿½ se nouvo mizik adorasyon James Alcindor lanse an kolaborasyon ak Lynda Joseph, yon atis ki renome pou kalite vokal li ak prezans espirityï¿½l lakay li. Se yon mizik ki leve konsyans sou jan bonte Bondye depase limit, depase konparezon, ak depase tout sa yon moun ka imajine. Li mete aksan sou fï¿½s ak fidelite Bondye ki pwoteje, klere, epi ki kenbe pï¿½p li djougan menm nan moman difisil yo. Mizik sa raple prezans Bondye se sous estabilite, limyï¿½, ak direksyon. Se li ki bay moun kapasite pou yo rete djanm epi kontinye mache avï¿½k konfyans. Bonte Ou Pa Gen Rivalï¿½ se yon mizik ki kapab sï¿½vi pou meditasyon, moman priyï¿½, oswa adorasyon. ?? https://youtu.be/lBQdN50Z7io?si=trszc5_QEqpxDgn6 5. Carmessita Rï¿½silien ï¿½ ï¿½ANYENï¿½ Sï¿½ti: 15 novanm 2025 Carmessita Rï¿½silien ofri piblik la ï¿½ANYENï¿½, yon mizik ki pote yon mesaj fï¿½ sou direksyon Bondye ak pouvwa men l nan lavi chak moun. ï¿½ANYENï¿½ se yon pwoklamasyon klï¿½: pyï¿½s baryï¿½ pa ka bloke sa Bondye sere pou yon moun, ni detounen chemen li trase. Mizik la raple lavni, desten, ak direksyon lavi yon moun rete anrasinen nan men Bondye, kote pa gen dout, pa gen laperï¿½z... se yon rapï¿½l desten nou pa depann de obstak yo, men de Bondye ki trase chimen nou. ?? https://youtu.be/pyMO6pZ5S7Y?si=W24QeKuducht6kHM 6. Pasteur Claudy Jean Louis ï¿½ ï¿½Se Sezon Pa mï¿½ Dat sï¿½ti: 16 novanm 2025 Pasteur Claudy Jean Louis prezante piblik la nouvo mizik li ï¿½Se Sezon Pamï¿½, yon mizik ki soti 16 novanm 2025 ak yon videyo ofisyï¿½l ki disponib sou youtube. Li plase tï¿½t li nan mouvman mizik evanjelik ayisyen an kï¿½m yon pyï¿½s ki mete aksan sou temwayaj, pï¿½severans ak konfyans nan entï¿½vansyon Bondye. Nan pawï¿½l yo, pastï¿½ Claudy devlope lide sezon espirityï¿½l la kï¿½m yon etap avansman okenn fï¿½s pa ka anpeche. Li fï¿½ referans ak pasaj biblik ki rakonte liberasyon pï¿½p Izrayï¿½l la, pou ilistre kijan obstak yo, menm lï¿½ yo parï¿½t kï¿½m gwo baryï¿½, pa anpeche manifestasyon pouvwa Bondye. ?? https://youtu.be/DHK0ZQ3KIGo?si=FYBRpXSELgItOduk 7. Frï¿½ Mendy ï¿½ ï¿½Pa Anpeche m Louweï¿½ sï¿½ti: 20 novanm 2025 Frï¿½ Mendy mete yon nouvo ton nan mizik evanjelik la ak ï¿½Pa Anpechem Louweï¿½, yon mizik ki mezire fï¿½s yon temwayaj pï¿½sonï¿½l ak pouvwa rekonesans devan Bondye. Videyo liriks la, ki parï¿½t 20 novanm 2025. Nan mizik la, Frï¿½ Mendy adrese yon reyalite anpil moun viv nan mitan legliz: jijman vizyï¿½l, prejije, ak move entï¿½pretasyon. ï¿½Si w wï¿½ demen m anlï¿½ nan tanp lanï¿½ mwen pa t a vle w panse se show-off map fï¿½ montre byen pozisyon atis la: louwanj pa fï¿½t pou enpresyone, men pou temwaye. Avï¿½k ï¿½Pa Anpeche m Louweï¿½, Frï¿½ Mendy pote yon mizik ki depase louwanj tradisyonï¿½l pou l antre nan teritwa temwayaj pï¿½sonï¿½l. ?? https://youtu.be/0kaMzp2bWJg?si=VGGd19GERyhMRUHO 8. Tom Karencini Jecrois feat. Deborah Henristal sï¿½ti: 25 Novanm 2025 Apre siksï¿½ mizik ï¿½Pi Bon Zanmi Mwen (Ou Fidï¿½l nan Pwomï¿½s Ou)ï¿½, ki te parï¿½t an novanm 2024, atis evanjelik Tom Karencini Jecrois retounen an 2025 ak yon nouvo prezantasyon mizik la: yon remix an kolaborasyon ak Deborah Henristal. Nouvo vï¿½syon sa a, ki disponib depi novanm 2025, pote yon pwojï¿½ modï¿½n ak fï¿½s vokal enpekab, pandan li rete fidï¿½l ak fondasyon espirityï¿½l mizik orijinal la. Mizik la prezante yon temwayaj pwofon kote atis la rekonï¿½t feblï¿½s li antanke moun, erï¿½ li yo, ak distans li konn pran ak Bondye; men malgre tout bagay, Bondye rete fidï¿½l. Remix 2025 la pote yon nouvo enï¿½ji: yon vibrasyon pi jï¿½n, epi yon melanj vokal ki fï¿½ kolaborasyon jwenn kalifikasyon ekstrawï¿½dinï¿½. ï¿½Ou Fidï¿½l nan Pwomï¿½s Ouï¿½ rete yon mizik sou fidelite Bondye, sou lanmou k ap grandi chak jou pi plis, ak sou rekonesans pou yon Zanmi ki pa janm lage li. ?? https://youtu.be/fxRMYq9rzJQ?si=UBpqRV3PlRZsFDPN 9. Bless Louis ï¿½ ï¿½Ensemble Louons le Seigneur / Comment ne pas te louerï¿½ (Cover) sï¿½ti: 26 novanm 2025 Nan okazyon anivï¿½sï¿½ li, 26 novanm 2025, Bless Louis ofri piblik la yon medley louwanj atravï¿½ yon chant tradisyonï¿½l ï¿½Ensemble louons le Seigneur il est vivantï¿½. Videyo a mete aksan sou adorasyon ak rekonesans pou Bondye, pandan li bay piblik la yon adaptasyon tou nï¿½f epi pï¿½sonï¿½l nan yon chant ki deja renome nan kominote kretyï¿½n nan. ï¿½Seigneur, il est vivant !ï¿½ pote chalï¿½ ak emosyon ki fasil pou konekte ak tout jenerasyon. Medley sa a pa sï¿½lman selebre anivï¿½sï¿½ jï¿½n atis la, men li vin tounen yon zouti pou rasanble fanmi, legliz, ak kominote nan yon moman adorasyon. ?? https://youtu.be/_64DUorcmAU? Palmarï¿½s Mizik/Videyo Evanjelik ï¿½ Novanm 2025 lan montre avidï¿½y enï¿½ji, kreyativite ak pwofondï¿½ ki kontinye defini mizik evanjelik ayisyï¿½n nan. Chak mizik pote mesaj pwï¿½p pa li, viktwa, koreksyon, rekonesans, adorasyon, direksyon, ak espwa. Se yon palmarï¿½s ki make avansman sektï¿½ a ak angajman atis yo pou sï¿½vi kominote a. Mizik yo disponib, mesaj yo klï¿½, epi atis yo pare pou kontinye enspire. Redaksyon: Vanauscheca Bouzy &amp; Ronalson Blanfort Konsepsyon Grafik: Jo-Flung Bouzy &amp; Abdullah Mode #palmarï¿½smizikvideyoevanjeliknov2025 #konektem #toutkotenenpotkilï¿½</p>', 0, 0, 0, '2111,90'),
(36, 229, 'Des pavÃ©s de la rue aux lumiÃ¨res de la Chine :  Diamant brille ! Fr Luckson assure.', '2026-01-23', 'entertainment', 'Dans les rues poussiÃ©reuses et animÃ©es des montagnes de Pilboro, un enfant au sourire Ã©clatant attirait dÃ©jÃ  les regards â€” pas parce quâ€™il avait des richesses ou une vie facile, mais parce que son regard semblait contenir une lumiÃ¨re que nul ne pouvait ignorer.', '<p class=\"p1\"><span class=\"s1\">Dans les rues poussi&eacute;reuses et anim&eacute;es des montagnes de Pilboro, un enfant au sourire &eacute;clatant attirait d&eacute;j&agrave; les regards, pas parce qu&rsquo;il avait des richesses ou une vie facile, mais parce que son regard semblait contenir une lumi&egrave;re que nul ne pouvait ignorer.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ce gar&ccedil;on, que le photographe Vladimy Josu&eacute; Destin&eacute; a immortalis&eacute; dans un clich&eacute; devenu viral sur les r&eacute;seaux sociaux, n&rsquo;&eacute;tait encore qu&rsquo;un enfant des rues, livr&eacute; &agrave; luiâ€‘m&ecirc;me dans un monde souvent cruel pour les plus vuln&eacute;rables. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Cette image a &eacute;veill&eacute; les consciences et d&eacute;clench&eacute; une r&eacute;action qui allait changer une vie. C&rsquo;est ainsi qu&rsquo;est entr&eacute; en sc&egrave;ne la Fondation Fr&egrave; Luckson Z&ograve;n Pa F&egrave; Moun, une organisation ha&iuml;tienne &agrave; but non lucratif n&eacute;e du credo &laquo;â€¯Z&ograve;n pa f&egrave; mounâ€¯&raquo;, fond&eacute;e par Luckson Jean, un philanthrope profond&eacute;ment engag&eacute; &agrave; venir en aide aux enfants sans domicile fixe et &agrave; lutter contre l&rsquo;exclusion sociale. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">La mission de cette fondation est claire : offrir un refuge s&ucirc;r, une &eacute;ducation, un accompagnement moral et des opportunit&eacute;s nouvelles &agrave; ceux qui ont &eacute;t&eacute; oubli&eacute;s par la soci&eacute;t&eacute;. Elle ne se limite pas &agrave; r&eacute;cup&eacute;rer des enfants des rues : elle d&eacute;ploie des programmes &eacute;ducatifs, organise des activit&eacute;s sportives comme une &eacute;quipe de football pour renforcer l&rsquo;estime de soi, et cr&eacute;e des espaces de vie structur&eacute;s pour permettre &agrave; ces jeunes de grandir avec dignit&eacute; et ambition. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&laquo; Kounya mwen moute avyon plis ke kamyon&egrave;t &raquo;, a-t-il d&eacute;clar&eacute; dans un extrait d&rsquo;un single r&eacute;cemment partag&eacute; sur les r&eacute;seaux sociaux de la fondation. Devenue virale, cette d&eacute;claration annonce la sortie prochaine d&rsquo;un nouveau projet tr&egrave;s attendu.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Le parcours de Diamant est aujourd&rsquo;hui remarquable : chap&eacute; des griffes d&rsquo;un destin qui ne lui &eacute;tait pas destin&eacute;, il brille d&eacute;sormais d&rsquo;une lumi&egrave;re qui lui est propre et s&rsquo;impose aujourd&rsquo;hui comme une figure montante, dont la valeur artistique est per&ccedil;ue comme celle d&rsquo;un Diamant rare et incomparable.&nbsp;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">il n&rsquo;est plus d&eacute;fini par les pav&eacute;s o&ugrave; il errait, mais par les sc&egrave;nes sur lesquelles il brille. Artiste, symbole d&rsquo;espoir et d&eacute;sormais ambassadeur pour des causes sociales, il a sorti plusieurs musiques salu&eacute;es par le public et a m&ecirc;me eu l&rsquo;opportunit&eacute; de voyager, notamment aux Bahamas, pour participer &agrave; des &eacute;v&eacute;nements culturels et artistiques. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">En cette ann&eacute;e particuli&egrave;re, Diamant s&rsquo;appr&ecirc;te &agrave; vivre une nouvelle &eacute;tape exceptionnelle de sa vie : une visite en Chine, pr&eacute;vue durant cette saison dans le cadre d&rsquo;un programme sp&eacute;cial qui c&eacute;l&egrave;bre son talent et son parcours inspirant. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ce voyage repr&eacute;sente non seulement une &eacute;tape dans sa carri&egrave;re artistique, mais aussi une reconnaissance internationale d&rsquo;un jeune qui a refus&eacute; d&rsquo;&ecirc;tre d&eacute;fini par ses origines et qui a transform&eacute; son histoire en source d&rsquo;inspiration.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Redaksyon:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Jo-flung Bouzy</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p3\"><span class=\"s3\">#konektem</span></p>\r\n<p class=\"p3\"><span class=\"s3\">#toutkotenenp&ograve;tkil&egrave;</span></p>', 0, 0, 0, NULL),
(37, 230, 'KPT a Rete Djougan Anfas EntÃ¨diksyon Etazini ak Kanda ki opoze ak Desizyon Revokasyon Premye Minis Alix Didier Fils-AimÃ©.', '2026-01-23', 'politics', 'Jodi vandredi 23 janvye 2026 lan, Konseye Prezidan Leslie Voltaire ansanm ak Edgard Leblanc Fils te anime yon konferans pou laprÃ¨s, nan ViladakÃ¨y pou fÃ¨ pwen sou sitiyasyon politik peyi a ak fason KonsÃ¨y PrezidansyÃ¨l Tranzisyon (KPT) an ap jere dÃ¨nye jou manda li, ki gen pou bout nan dat 7 fevriye k ap vini la.', '<p class=\"p1\"><span class=\"s1\">Jodi vandredi 23 janvye 2026 lan, Konseye Prezidan Leslie Voltaire ansanm ak Edgard Leblanc Fils te anime yon konferans pou lapr&egrave;s, nan Viladak&egrave;y pou f&egrave; pwen sou sitiyasyon politik peyi a ak fason Kons&egrave;y Prezidansy&egrave;l Tranzisyon (KPT) an ap jere d&egrave;nye jou manda li, ki gen pou bout nan dat 7 fevriye k ap vini la. Nan diskou li a, Leslie Voltaire rekon&egrave;t malgre jef&ograve; KPT a pou soutni travay gouv&egrave;nman an, atant popilasyon an pa rive satisf&egrave; jan sa t a dwe f&egrave;t. Se nan kont&egrave;ks sa<span class=\"Apple-converted-space\">&nbsp; </span>Kons&egrave;y la pran desizyon pou revoke Premye minis Alix Didier Fils-Aim&eacute;, epi chwazi yon l&ograve;t Premye minis ki s&ograve;ti nan menm gouv&egrave;nman an, pou asire enterim&egrave; a pandan y ap ch&egrave;che pi bon f&ograve;mil pou jere pery&ograve;d tranzisyon politik la, apre depa KPT a.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Yon l&ograve;t b&ograve;, Konseye Prezidan Edgard Leblanc Fils f&egrave; konnen chanjman Premye minis lan vize p&egrave;m&egrave;t travay ki rete pou KPT a reyalize nan ti tan li genyen an f&egrave;t pi byen. Li anonse nouvo Ch&egrave;f gouv&egrave;nman an t a dwe rete nan fonksyon an pandan 30 jou, nan objektif pou fasilite tranzisyon politik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\"> Pandan konferans lan, Konseye Prezidan yo te reponn kesyon jounalis yo, tout pandan yo raple kominote ent&egrave;nasyonal la li pa gen pouvwa pou dikte desizyon ki dwe pran an Ayiti, nan ajoute se Ayisyen ki dwe pran rezolisyon pou Ayisyen, nan lide pou retabli estabilite nan peyi a.</span></p>', 0, 0, 0, NULL),
(38, 232, 'Konseye Prezidan  Laurent Saint-Cyr Reyafime Angajman Leta nan Sipòte FAD\'H.', '2026-01-26', 'politics', 'Jodi Lendi 26 janvye 2026 la, Prezidan Konsèy Prezidensyèl Transisyon an,  Laurent Saint-Cyr, ansanm ak Premye Minis Alix Didier Fils-Aimé ak Minis Defans lan, Jean Michel Moïse, te ale nan gran katye jeneral fòs lame peyi a, nan kad yon vizit ofisyèl ki te fèt.', '<p class=\"p1\"><span class=\"s1\">Jodi Lendi 26 janvye 2026 la, Prezidan Kons&egrave;y Prezidensy&egrave;l Transisyon an,<span class=\"Apple-converted-space\">&nbsp; </span>Laurent Saint-Cyr, ansanm ak Premye Minis Alix Didier Fils-Aim&eacute; ak Minis Defans lan, Jean Michel Mo&iuml;se, te ale nan gran katye jeneral f&ograve;s lame peyi a, nan kad yon vizit ofisy&egrave;l ki te f&egrave;t.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Vizit sa a te gen pou objektif pou reyafime angajman leta, nan bay F&ograve;s lame peyi a, tout sip&ograve; posib, sitou nan kad gwo travay an kolaborasyon ak Polis Nasyonal, y ap f&egrave; pou konbat gang krimin&egrave;l yo. Pandan rankont sila, Prezidan an te salye pwofesyonalis, disiplin, ak sans devwa s&ograve;lda yo, ki gen yon w&ograve;l esansy&egrave;l nan estabilite peyi a, pwoteksyon enfrastrikti yo, ak sip&ograve; pou enstitisyon leta yo. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pandan vizit la, li te ensiste sou enp&ograve;tans yon netralite b&ograve; kote f&ograve;s lame a, yon kondisyon ki esansy&egrave;l pou retabli konfyans p&egrave;p la ak kredibilite ent&egrave;nasyonal la nan enstitisyon milit&egrave; sila. Pou bout ak diskou l la, Prezidan Saint-Cyr eksprime konfyans li nan pwofesyonalis ak disiplin k&ograve;mandman ant&egrave;t la, pandan li reyafime sip&ograve; total leta pou F&ograve;s lame peyi a, nan kad misyon sekirite nasyonal yo. Li te ankouraje jef&ograve; yo pou retabli sekirite dirab, sa ki enp&ograve;tan pou &ograve;ganize eleksyon ki kredib ak pou amelyore byenn&egrave;t p&egrave;p ayisyen an.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">#esansy&egrave;l</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#politik</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#konektem</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#toutkotenenpotkil&egrave; </span></p>', 0, 0, 0, NULL),
(39, 233, 'PNH diplome 57 ajan nan teknik konba kont gang yo.', '2026-01-28', 'politics', 'Polis Nasyonal Ayiti (PNH), ak sipò International Narcotics and Law Enforcement Affairs (INL), te remèt sètifika bay 57 ajan, pami yo 29 polisye ki sòti nan plizyè inite…', '<p class=\"p1\"><span class=\"s1\">Polis Nasyonal Ayiti (PNH), ak sip&ograve; International Narcotics and Law Enforcement Affairs (INL), te rem&egrave;t s&egrave;tifika bay 57 ajan, pami yo 29 polisye ki s&ograve;ti nan plizy&egrave; inite espesyalize ak 28 teknisyen, apre yo fin konplete f&ograve;masyon espesyalize, 27 janvye 2026, nan Direksyon Jeneral PNH la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">F&ograve;masyon yo te pote sitou sou Combat Driving ak Tire Filling, av&egrave;k objektif pou ranf&ograve;se kapasite teknik ak operasyon&egrave;l lapolis la, pou li pi efikas nan repons kont gang ame yo ak l&ograve;t defi sekirite peyi a ap konfwonte.</span></p>', 0, 0, 0, NULL),
(40, 234, 'Dezyèm kowòt PAEF la lanse pou sipòte fanm antreprenè nan plizyè sektè.', '2026-01-28', 'politics', 'Ministè Kondisyon Fanm ak Dwa Fanm (MCFDF) anonse ouvèti dezyèm kowòt Pwogram Sipò pou Entreprenarya Fanm (PAEF), yon inisyativ ki soti nan Ministè Komès ak Endistri (MCI).', '<p class=\"p1\"><span class=\"s1\">Minist&egrave; Kondisyon Fanm ak Dwa Fanm (MCFDF) anonse ouv&egrave;ti dezy&egrave;m kow&ograve;t Pwogram Sip&ograve; pou Entreprenarya Fanm (PAEF), yon inisyativ ki soti nan Minist&egrave; Kom&egrave;s ak Endistri (MCI). Pwogram nan vize bay sip&ograve; teknik ak finansye pou antrepriz ki dirije pa fanm ak pwoj&egrave; inovatif k ap jenere revni, pou ranf&ograve;se otonomizasyon ekonomik fanm nan tout peyi a.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Fanm ki ka patisipe:</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Dirijan biznis ki egziste deja;</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Fanm ki pote pwoj&egrave; inovatif k ap jenere revni;</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Fanm ki angaje nan pwosesis ekspansyon oswa mod&egrave;nizasyon biznis yo;</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Biznis ki fizikman enstale nan peyi a;</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Fanm ki pa benefisye kounye a de yon l&ograve;t pwoj&egrave; MCI.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Sekt&egrave; priyorit&egrave;:</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Agrikilti, elvaj ak pwason, endistri pwodiksyon ak transf&ograve;masyon, fabrikasyon, atizana, resiklaj (transf&ograve;masyon dech&egrave;), touris, teknoloji, lojistik, ansanm ak nenp&ograve;t l&ograve;t sekt&egrave; ki konsidere k&ograve;m enp&ograve;tan.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pery&ograve;d ak fason depo dosye:</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Dosye yo ap aksepte soti 27 rive 30 janvye 2026, epi yo dwe soum&egrave;t eksklizivman sou ent&egrave;n&egrave;t, atrav&egrave; sit MCI: www.mci.gouv.ht/paef. Plan biznis yo disponib sou sit la tou.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Minist&egrave; ankouraje tout fanm antrepren&egrave; ki kalifye nan tout peyi a pou pwofite op&ograve;tinite sa a, ki f&egrave; pati ef&ograve; gouv&egrave;nman an pou ankouraje lid&egrave;chip ekonomik fanm ak egalite chans.</span></p>', 0, 0, 0, NULL),
(41, 235, 'Ayiti ak Meksik ranfòse koperasyon elektoral yo atravè yon pwotokòl akò.', '2026-01-28', 'politics', 'Konsèy Elektoral Pwovizwa (CEP) ak Enstiti Nasyonal Elektoral Meksik (INE) siyen, jodi mèkredi 28 janvye 2026 la, yon pwotokòl akò ki vize ranfòse koperasyon ant de (2) enstitisyon yo, nan domèn elektoral.', '<p class=\"p1\"><span class=\"s1\">Kons&egrave;y Elektoral Pwovizwa (CEP) ak Enstiti Nasyonal Elektoral Meksik (INE) siyen, jodi m&egrave;kredi 28 janvye 2026 la, yon pwotok&ograve;l ak&ograve; ki vize ranf&ograve;se koperasyon ant de (2) enstitisyon yo, nan dom&egrave;n elektoral.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ak&ograve; sila te siyen pa prezidan CEP a, Jacques Desrosiers, ansanm ak anbasad&egrave; Meksik la an Ayiti, Jos&eacute; de Jes&uacute;s Cisneros Ch&aacute;vez. Li prevwa kolaborasyon sa sou plizy&egrave; asp&egrave; kle, nan sist&egrave;m elektoral la, tankou f&ograve;masyon ak ranf&ograve;sman kapasite operat&egrave; elektoral yo, jesyon ak mizajou rejis elektoral la, ansanm ak itilizasyon teknoloji nan &ograve;ganizasyon eleksyon yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">De (2) pati yo angaje yo pou pataje eksperyans ak eksp&egrave;tiz yo, nan yon lespri resp&egrave; ak aprantisaj mity&egrave;l, nan objektif pou ranf&ograve;se demokrasi epi asire chak v&ograve;t konte nan pwosesis elektoral yo.</span></p>', 0, 0, 0, NULL);
INSERT INTO `news` (`id`, `newsImg`, `newsTitle`, `newsDate`, `newsCategory`, `newsHeadline`, `fullContent`, `reads`, `likes`, `shares`, `featured_image_ids`) VALUES
(51, 250, '🎶 Palmarès Mizik Evanjelik – Janvye 2026.', '2026-02-01', 'music & video', 'Janvye 2026 make yon nouvo paj nan mizik evanjelik ayisyen an. Depi premye jou ane a, plizyè atis chwazi louvri kalandriye mizikal la ak chante ki pote mesaj lafwa, esperans, adorasyon ak konfyans total nan Bondye. Premye palmarès mwa janvye a montre klèman yon tandans kote mizik evanjelik la vin tounen yon espas temwayaj, rezistans espirityèl ak rekonesans, nan yon kontèks sosyal ak pèsonèl ki mande ankourajman.', '<p class=\"p1\"><span class=\"s1\">Yon k&ograve;mansman ane ki make pa lafwa, adorasyon ak deklarasyon espirity&egrave;l</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Janvye 2026 make yon nouvo paj nan mizik evanjelik ayisyen an. Depi premye jou ane a, plizy&egrave; atis chwazi louvri kalandriye mizikal la ak chante ki pote mesaj lafwa, esperans, adorasyon ak konfyans total nan Bondye. Premye palmar&egrave;s mwa janvye a montre kl&egrave;man yon tandans kote mizik evanjelik la vin tounen yon espas temwayaj, rezistans espirity&egrave;l ak rekonesans, nan yon kont&egrave;ks sosyal ak p&egrave;son&egrave;l ki mande ankourajman.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Men palmar&egrave;s mizik evanjelik pou mwa janvye 2026, ak pwodiksyon ki make k&ograve;mansman ane a sou s&egrave;n gospel ayisyen an.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">1. Apotre Robenson Joachim &ndash; &ldquo;Mpaka Echwe&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 1 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k &ldquo;Mpaka Echwe&rdquo;, Apotre Robenson Joachim ouvri ane a sou yon deklarasyon f&ograve;: ech&egrave;k pa gen d&egrave;nye mo a. Videyo ofisy&egrave;l la, ki soti 1 janvye 2026, vin bay yon l&ograve;t dimansyon a mizik la ki te deja disponib sou platf&ograve;m dijital depi novanm 2025.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a ab&ograve;de reyalite konba lavi yo, blesi, reta ak difikilte, men li kenbe yon mesaj santral kl&egrave;: tout gwo desten pase pa konba. Paw&ograve;l tankou &laquo; Menm si mwen blese, m pap echwe &raquo; vin tounen poto mitan mizik la, pandan videyo a s&egrave;vi ak metaf&ograve; pou montre kijan tanp&egrave;t yo ka leve yon moun pi wo olye yo kraze li.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/h2UmzuzrYZM?si=zWBF4c5OVNsEE6WB</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">2. Josue Elisme &ndash; &ldquo;Pitit Wa&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 1 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan menm jou a, Josue Elisme prezante &ldquo;Pitit Wa&rdquo;, yon chan ki pwoklame idantite kwayan yo k&ograve;m pitit Bondye. Mizik la melanje adorasyon ak motivasyon, raple ke, k&egrave;lkeswa defi oswa enkyetid, Bondye rete prezan pou gide, kouvri ak pwoteje.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Pitit Wa&rdquo; vini k&ograve;m yon mesaj espirity&egrave;l espesyal pou k&ograve;mansman ane a, envite piblik la avanse ak lafwa, konfyans ak asirans ke plan Bondye pa janm an reta.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/7M7pRBliZHA?si=CGNv_XKt4m4JPch4</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">3. Fabienne Payoute Bernadin &ndash; &ldquo;Ou Pa Ka Bare M&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 4 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ak &ldquo;Ou Pa Ka Bare M&rdquo;, Fabienne Payoute Bernadin livre yon deklarasyon espirity&egrave;l sou viktwa ak delivrans. Mizik la pale dir&egrave;kteman ak tout moun ki santi yo bloke, atake oswa ralanti nan avansman lavi yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a raple ke l&egrave; Bondye louvri yon p&ograve;t, pa gen okenn f&ograve;s imen oswa espirity&egrave;l ki ka f&egrave;men li. Anrejistre nan Jino Defralien Studio ak videyo pa Gabrielle Pierre, mizik la pote espwa ak asirans ke delivrans Bondye depase tout bary&egrave;.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/yD_01IXZVrE?si=Js4ZUTllBQnRIP_o</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">4. Evangeliste Trofort feat. Jude Samuel &ndash; &ldquo;Ou M&egrave;veye&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 7 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Ou M&egrave;veye&rdquo; se yon chante adorasyon ki santre sou sakrifis Jezi Kris, kwa a ak rezir&egrave;ksyon an. Evangeliste Trofort ak Jude Samuel ofri yon meditasyon pwofon sou lanmou Kris la, ki depase tout konpreyansyon imen.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la mete aksan sou rekonesans, adorasyon ak admirasyon devan yon Jezi ki pote chay lemonn, ki bay delivrans ak lavi.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/4gV04nqFoMw?si=O1a2aia7PbF1iRVZ</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">5. Marc Adens Brianvil feat. Deborah Henristal &ndash; &ldquo;Li Kenbe&rsquo;m&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 10 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ak &ldquo;Li Kenbe&rsquo;m&rdquo;, Marc Adens Brianvil ak Deborah Henristal pwopoze yon chan temwayaj sou pwoteksyon Bondye. Mizik la trav&egrave;se tout sezon lavi a, soti janvye rive desanm<span class=\"Apple-converted-space\">&nbsp; </span>pou raple ke men Bondye pa janm lage.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Repetisyon &ldquo;Bondye kenbe mwen&rdquo; vin tounen yon pwoklamasyon lafwa, espesyalman nan mitan ensekirite, danje ak kriz sosyete a ap trav&egrave;se.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/cJfhr45NiGs?si=S8rKGIZIIkqNNavp</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">6. Barbara Cassamajor feat. Les &Eacute;lus Haiti &ndash; &ldquo;C&rsquo;est Dieu&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 11 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;C&rsquo;est Dieu&rdquo; se youn nan pwen f&ograve; alb&ograve;m Renouveau de Barbara Cassamajor. K&ograve;m 11y&egrave;m chan sou alb&ograve;m 12 tit la, mizik la vini tankou yon deklarasyon lafwa kl&egrave; sou destin, avni ak direksyon lavi.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k refren &laquo; Ah oui, c&rsquo;est Dieu &raquo;, chante a pwoklame Bondye k&ograve;m s&egrave;l referans. Pwodiksyon Shegger Beats, ansanm ak vokal Les &Eacute;lus Haiti, ranf&ograve;se dimansyon espirity&egrave;l mizik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan kad pwomosyon alb&ograve;m nan, yon kons&egrave;<span class=\"Apple-converted-space\">&nbsp; </span>ap f&egrave;t 1 mas 2026 nan Pal&egrave; Minisipal. Alb&ograve;m Renouveau disponib sou tout platf&ograve;m dijital yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/76ymg2XwdcI?si=0xXrTjrQAnJ_jy91</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">7. Emmanuel Beliard ft. Spencer Brutus &ndash; &ldquo;ALL&Eacute;LUIA!&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 11 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;ALL&Eacute;LUIA!&rdquo; se yon chan adorasyon ak viktwa espirity&egrave;l. Emmanuel Beliard ak Spencer Brutus envite piblik la leve vwa yo nan louanj, menm nan mitan konfizyon, dezolasyon ak chenn espirity&egrave;l.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la s&egrave;vi k&ograve;m yon r&egrave;l lafwa ki anonse delivrans, limy&egrave; ak lib&egrave;te atrav&egrave; adorasyon.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/E8kny1HlCxY?si=UTeUzZQDT7ATZ9FU</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">8. Rod Ume &ndash; &ldquo;M&Egrave;SI PAPA&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 17 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k &ldquo;M&Egrave;SI PAPA&rdquo;, Rod Ume ofri yon chan rekonesans kote li rem&egrave;sye Bondye pou swen, pwoteksyon ak prezans fid&egrave;l Li. Paw&ograve;l yo prezante Bondye k&ograve;m B&egrave;je ki pa janm abandone pitit li, menm anba atak ak difikilte.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la vin tounen yon chan delivrans, lapriy&egrave; ak adorasyon melanje.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/HJ_WSgVzhiE?si=bKwM4-ewFu_UU4GK</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">9. Fr&egrave; Mendy ft. Spencer Brutus &ndash; &ldquo;Mwen Depann de Ou&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 20 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Mwen Depann de Ou&rdquo; se yon priy&egrave; mizikal sou aband&ograve;n total. Fr&egrave; Mendy ak Spencer Brutus mete aksan sou relasyon dir&egrave;k ant l&ograve;m ak Bondye, san atifis, san pretansyon.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a raple ke f&ograve;s rey&egrave;l la k&ograve;manse l&egrave; yon moun rem&egrave;t tout kontw&ograve;l lavi li nan men Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/FeI_Ye8jak0?si=kHjF96owZAVz0-CR</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">10. Rosalinda Esmanga &ndash; &ldquo;Soufle Sou Mwen&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 25 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ak &ldquo;Soufle Sou Mwen&rdquo;, Rosalinda Esmanga pwopoze yon chan lapriy&egrave; kote li mande direksyon, pwoteksyon ak prezans Sentespri. T&egrave;ks la mete aksan sou renouv&egrave;lman entery&egrave; ak konfyans total nan Paw&ograve;l Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/lCbhXRp2rsE?si=aM6svqMG-pMAFATW</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">11. Mike-Lee Elminis &ndash; &ldquo;Men Lavi&rsquo;m Yahwey&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 31 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pou f&egrave;men mwa a, Mike-Lee Elminis prezante &ldquo;Men Lavi&rsquo;m Yahwey&rdquo;, yon priy&egrave; adorasyon sou aband&ograve;n total. Ot&egrave;, konpozit&egrave; ak ent&egrave;pr&egrave;t chan an, atis la depoze lavi li n&egrave;t nan men Bondye, k&ograve;m s&egrave;l gid ak direksyon.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a tradwi doul&egrave;, fatig entery&egrave;, men sitou konfyans ak espwa nan fidelite Yahwey.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/K6AbZXcElRg?si=cq4fUiJGdkLTJcWn</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Palmar&egrave;s mizik evanjelik janvye 2026 la montre yon k&ograve;mansman ane ki rich an mesaj espirity&egrave;l, temwayaj ak adorasyon. Atis yo chwazi ouvri ane a ak chante ki pale de rezistans, delivrans, aband&ograve;n total ak konfyans nan Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Premye palmar&egrave;s ane a pa s&egrave;lman reflete dinamis mizik gospel ayisyen an, men li konfime w&ograve;l mizik evanjelik la k&ograve;m yon sous espwa, f&ograve;s ak direksyon pou piblik la pandan tout ane 2026 la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Redaksyon:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Vanauscheca Bouzy</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Konsepsyon Grafik:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Jo-Flung Bouzy &amp; Abdullah Mode</span></p>', 0, 0, 0, NULL),
(52, 251, 'Meri Dèlma : Remiz sètifika pou 20 jèn apre 3 mwa fòmasyon.', '2026-02-04', 'education', 'Jodi mèkredi 4 fevriye 2026 la, Majistra Wilson Jeudy, ansanm ak Direksyon Jeni ak Sèvis Enfòmatik, te remèt sètifika bay 20 jèn stajyè, ki sot konplete avèk siksè yon fòmasyon twa (3) mwa, nan domèn jeni ak enfòmatik. Seremoni sila te fèt nan Meri Delmas a, kote jèn yo te resevwa rekonesans ofisyèl pou jefò ak disiplin yo pandan peryòd fòmasyon an.', '<p id=\"wl18h1\" class=\"_0JjuK _5dsPU\" data-pm-slice=\"1 1 []\">Jodi m&egrave;kredi 4 fevriye 2026 la, Majistra Wilson Jeudy, ansanm ak Direksyon Jeni ak S&egrave;vis Enf&ograve;matik, te rem&egrave;t s&egrave;tifika bay 20 j&egrave;n stajy&egrave;, ki sot konplete av&egrave;k siks&egrave; yon f&ograve;masyon twa (3) mwa, nan dom&egrave;n jeni ak enf&ograve;matik. Seremoni sila te f&egrave;t nan Meri Delmas a, kote j&egrave;n yo te resevwa rekonesans ofisy&egrave;l pou jef&ograve; ak disiplin yo pandan pery&ograve;d f&ograve;masyon an.</p>\r\n<p id=\"w47pk3\" class=\"_0JjuK _5dsPU\"></p>\r\n<p id=\"3unvn4\" class=\"_0JjuK _5dsPU\">Nan okazyon sa, plizy&egrave; patisipan pa t kache satisfaksyon yo ni rekonesans yo anv&egrave; Majistra a ak ekip f&ograve;mat&egrave; yo, yo konsidere f&ograve;masyon sa k&ograve;m yon etap enp&ograve;tan ki pral gen enpak dir&egrave;k sou lavi pwofesyon&egrave;l ak p&egrave;son&egrave;l yo. Yo salye yon inisyativ ki vize bay j&egrave;n yo zouti teknik pou pi bon entegrasyon yo sou mache travay la.</p>\r\n<p id=\"bisi76\" class=\"_0JjuK _5dsPU\"></p>\r\n<p id=\"la0kq7\" class=\"_0JjuK _5dsPU\">Nan mesaj sikonstans li, Majistra Wilson Jeudy te pataje k&egrave;k asp&egrave; nan eksperyans p&egrave;son&egrave;l li ak etap nan pakou li ki mennen li rive kote li ye jodi a. Li te ensiste sou enp&ograve;tans vizyon, disiplin ak det&egrave;minasyon, tout pandan li ankouraje j&egrave;n yo pran desten yo an men, souliye ke avni peyi a depann anpil de angajman ak preparasyon jenerasyon sila.</p>', 0, 0, 0, NULL),
(53, 253, 'Kyria Salina Romusca sacrée Miss Eco International Haiti 2026', '2026-02-06', 'politics', 'Kyria Salina Romusca a été couronnée Miss Eco Haiti 2026, un titre qui fait d’elle la représentante officielle d’Haïti au concours international Miss Eco International 2026.', '<p class=\"p1\"><span class=\"s1\">Kyria Salina Romusca a &eacute;t&eacute; couronn&eacute;e Miss Eco Haiti 2026, un titre qui fait d&rsquo;elle la repr&eacute;sentante officielle d&rsquo;Ha&iuml;ti au concours &nbsp;Miss Eco International 2026.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&Acirc;g&eacute;e de 25 ans, la jeune ambassadrice incarne une g&eacute;n&eacute;ration engag&eacute;e, alliant &eacute;l&eacute;gance, intelligence et responsabilit&eacute; sociale. D&eacute;j&agrave; connue du public, elle avait remport&eacute; en 2025 le titre de Miss Tourism World Haiti, marquant ainsi un parcours ascendant dans l&rsquo;univers des concours de beaut&eacute; &agrave; dimension internationale.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Gr&acirc;ce &agrave; ce nouveau sacre, Kyria Salina Romusca repr&eacute;sentera Ha&iuml;ti &agrave; Miss Eco International 2026, pr&eacute;vu du 13 au 24 mai prochain &agrave; Alexandrie, en &Eacute;gypte. Cet &eacute;v&eacute;nement r&eacute;unira des candidates de plus de 20 pays autour des th&eacute;matiques de la durabilit&eacute;, de la protection de l&rsquo;environnement et de l&rsquo;impact social positif.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&Eacute;tudiante en odontologie, Kyria d&eacute;veloppe &eacute;galement des comp&eacute;tences en communication et en relations humaines. Elle s&rsquo;engage activement en faveur de l&rsquo;&eacute;ducation, de l&rsquo;environnement et de l&rsquo;action humanitaire, tout en pla&ccedil;ant sa foi chr&eacute;tienne au c&oelig;ur de son engagement. &Agrave; travers ce titre, elle ambitionne de promouvoir la culture ha&iuml;tienne, la conscience &eacute;cologique et l&rsquo;espoir aupr&egrave;s des jeunes femmes d&rsquo;Ha&iuml;ti.</span></p>', 0, 0, 0, NULL),
(54, 254, 'LAFWA PI CHÈ, un morceau de Dieumet Maurice qui peint une réalité où l’amour excessif de l’argent finit par affaiblir notre foi en Dieu.', '2026-02-11', 'music & video', 'Souvent, l’amour incontrôlé de l’argent prend une place importante dans notre vie, au point de fragiliser notre foi. C’est une réalité qui touche de nombreux chrétiens aujourd’hui, où la recherche d’avantages matériels passe parfois avant la relation avec Dieu.', '<p class=\"p1\"><span class=\"s1\">Souvent, l&rsquo;amour incontr&ocirc;l&eacute; de l&rsquo;argent prend une place importante dans notre vie, au point de fragiliser notre foi. C&rsquo;est une r&eacute;alit&eacute; qui touche de nombreux chr&eacute;tiens aujourd&rsquo;hui, o&ugrave; la recherche d&rsquo;avantages mat&eacute;riels passe parfois avant la relation avec Dieu. C&rsquo;est autour de cette probl&eacute;matique que &laquo; LAFWA PI CH&Egrave; &raquo;, le nouveau titre de Dieumet Maurice, propose une r&eacute;flexion sensible mais bien r&eacute;elle. Sorti il y a environ deux (2) semaines, le morceau invite chaque chr&eacute;tien &agrave; r&eacute;fl&eacute;chir au v&eacute;ritable prix de la foi et au danger qui existe lorsque l&rsquo;argent devient le centre de tous nos int&eacute;r&ecirc;ts.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Apr&egrave;s plusieurs ann&eacute;es de silence dans sa carri&egrave;re musicale, dues &agrave; des difficult&eacute;s personnelles, Dieumet Maurice revient avec un message percutant. En seulement deux (2) minutes, il met le doigt sur un sujet d&eacute;licat, la relation que nous d&eacute;veloppons avec l&rsquo;argent, laquelle perturbe souvent notre relation avec Dieu. Les paroles du morceau sont explicites : &laquo; Lajan pa ka f&egrave; m mache, pou anyen lafwa m p ap janm boukante. &raquo; Une d&eacute;claration qui rappelle que la foi n&rsquo;a pas de prix et ne doit jamais devenir une monnaie d&rsquo;&eacute;change.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">La r&eacute;flexion ne se limite pas &agrave; la dimension personnelle. L&rsquo;artiste attire aussi l&rsquo;attention sur une r&eacute;alit&eacute; pr&eacute;sente dans certains espaces religieux, o&ugrave; l&rsquo;attachement excessif aux avantages mat&eacute;riels entra&icirc;ne parfois une perte de cr&eacute;dibilit&eacute; et de fid&eacute;lit&eacute;. Lorsqu&rsquo;il affirme &laquo; Gen legliz ki p&egrave;di fid&egrave;l &raquo;, il souligne le manque de transparence et les d&eacute;rives de priorit&eacute;s o&ugrave; la croissance spirituelle passe au second plan. Le morceau devient ainsi un appel &agrave; revenir &agrave; l&rsquo;essence m&ecirc;me de la foi.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Dieumet Maurice n&rsquo;est pas un novice dans le secteur. Il a commenc&eacute; la musique d&egrave;s son enfance, &agrave; travers la chorale d&rsquo;enfants de son &eacute;glise, avant de rejoindre des groupes a cappella, de se produire sur sc&egrave;ne puis en studio. Il a progressivement trac&eacute; son chemin, du groupe SALEM BAND &agrave; Apocalypse Time, une &eacute;tape marquante de la musique &eacute;vang&eacute;lique ha&iuml;tienne. Il a collabor&eacute; avec plusieurs artistes tels que Nicky Christ, Fr&egrave; Gabe, DANAJO, Aur&eacute;lien et Matthew Brouillet, tout en d&eacute;veloppant une carri&egrave;re solo comprenant plusieurs titres et un EP intitul&eacute; Initiation, sorti en 2023.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Aujourd&rsquo;hui, Dieumet Maurice annonce un nouveau d&eacute;part pour l&rsquo;ann&eacute;e 2026, avec plusieurs projets musicaux &agrave; venir. Avec &laquo; LAFWA PI CH&Egrave; &raquo;, il ne signe pas seulement son retour sur la sc&egrave;ne musicale ; il revient avec une mission, celle de rappeler que la foi demeure la plus grande richesse de chaque chr&eacute;tien, car aucun argent ne peut l&rsquo;acheter.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">D&eacute;couvrons ensemble ce projet et encourageons l&rsquo;artiste &eacute;vang&eacute;lique Dieumet Maurice en partageant et en soutenant son &oelig;uvre &agrave; travers ce lien :</span></p>\r\n<p class=\"p1\"><span class=\"s1\">https://youtu.be/V2wpRt4EgSA?si=o4NEILk3L-KeXJ27</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">R&eacute;daction ✍️: Ronalson Blanfort</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">#konektem </span></p>\r\n<p class=\"p1\"><span class=\"s1\">#ToutKoteNenp&ograve;tKil&egrave;</span></p>', 0, 0, 0, NULL),
(55, 271, 'Palmarès Mizik/Videyo Evanjelik Fevriye 2026, pote Mizik Lanmou pou Kè w ak Nanm ou.', '2026-02-28', 'politics', 'Apre yon mwa fevriye ki te chaje ak lanmou, adorasyon ak mesaj espirityèl, ekip redaksyon an anonse ke Palmarès Mizik/Videyo Evanjelik Fevriye 2026 la ap pibliye ofisyèlman dimanch k ap Premye Mas 2026 la. ', '<p class=\"p1\"><span class=\"s1\">Apre yon mwa fevriye ki te chaje ak lanmou, adorasyon ak mesaj espirity&egrave;l, ekip redaksyon an anonse ke Palmar&egrave;s Mizik/Videyo Evanjelik Fevriye 2026 la ap pibliye ofisy&egrave;lman dimanch k ap Premye Mas 2026 la. yon seleksyon ki vize mete an limy&egrave; pwodiksyon ki make dezy&egrave;m mwa ane a.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan yon pery&ograve;d ki souvan konsidere k&ograve;m mwa lanmou, atis evanjelik yo pa t rate okazyon sila pou prezante mizik ak videyo ki pote mesaj lanmou, konfyans, depandans espirity&egrave;l ak angajman. Soti 7 pou rive 28 fevriye 2026, plizy&egrave; lansman pwoj&egrave; te anime platf&ograve;m dijital yo ak kominote evanjelik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Seleksyon sa a reflete div&egrave;site ak dinamis mizik evanjelik ayisy&egrave;n<span class=\"Apple-converted-space\">&nbsp; </span>nan, kote adorasyon, lanmou, rekonesans ak deklarasyon lafwa pran plizy&egrave; f&ograve;m mizikal ak vizy&egrave;l. Plizy&egrave; nan videyo sa yo atire atansyon sou rezo sosyal ak platf&ograve;m dijital yo, swa akoz kalite pwodiksyon yo, oswa tou akoz pwofond&egrave; mesaj yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Palmar&egrave;s Fevriye 2026 la montre yon endistri ki pa s&egrave;lman ap pwodui, men k ap evolye. Estrikti mizikal yo ak pwofond&egrave; paw&ograve;l yo temwaye yon jenerasyon atis ki konprann w&ograve;l yo k&ograve;m minis mizik.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan mwa lanmou an, mizik evanjelik la pa limite t&egrave;t li, li chwazi pale sou angajman, fidelite ak relasyon ki bati sou prensip diven.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ekip redaksyon konektem nan salye jef&ograve; tout atis ak ekip teknik yo, ki patisipe nan pwodiksyon sa yo, pandan n ap envite piblik la dekouvri oswa dekouvri ank&ograve; videyo ki make mwa fevriye 2026 la, dimanch kap Premye Mas la.</span></p>', 0, 0, 0, NULL),
(56, 272, 'Palmarès Mizik/Videyo Evanjelik Fevriye 2026, pote Mizik Lanmou pou Kè w ak Nanm ou.', '2026-02-28', 'music & video', 'Apre yon mwa fevriye ki te chaje ak lanmou, adorasyon ak mesaj espirityèl, ekip redaksyon an anonse ke Palmarès Mizik/Videyo Evanjelik Fevriye 2026 la ap pibliye ofisyèlman dimanch k ap Premye Mas 2026 la.', '<p class=\"p1\"><span class=\"s1\">Apre yon mwa fevriye ki te chaje ak lanmou, adorasyon ak mesaj espirity&egrave;l, ekip redaksyon an anonse ke Palmar&egrave;s Mizik/Videyo Evanjelik Fevriye 2026 la ap pibliye ofisy&egrave;lman dimanch k ap Premye Mas 2026 la. yon seleksyon ki vize mete an limy&egrave; pwodiksyon ki make dezy&egrave;m mwa ane a.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan yon pery&ograve;d ki souvan konsidere k&ograve;m mwa lanmou, atis evanjelik yo pa t rate okazyon sila pou prezante mizik ak videyo ki pote mesaj lanmou, konfyans, depandans espirity&egrave;l ak angajman. Soti 7 pou rive 28 fevriye 2026, plizy&egrave; lansman pwoj&egrave; te anime platf&ograve;m dijital yo ak kominote evanjelik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Seleksyon sa a reflete div&egrave;site ak dinamis mizik evanjelik ayisy&egrave;n<span class=\"Apple-converted-space\">&nbsp; </span>nan, kote adorasyon, lanmou, rekonesans ak deklarasyon lafwa pran plizy&egrave; f&ograve;m mizikal ak vizy&egrave;l. Plizy&egrave; nan videyo sa yo atire atansyon sou rezo sosyal ak platf&ograve;m dijital yo, swa akoz kalite pwodiksyon yo, oswa tou akoz pwofond&egrave; mesaj yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Palmar&egrave;s Fevriye 2026 la montre yon endistri ki pa s&egrave;lman ap pwodui, men k ap evolye. Estrikti mizikal yo ak pwofond&egrave; paw&ograve;l yo temwaye yon jenerasyon atis ki konprann w&ograve;l yo k&ograve;m minis mizik.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan mwa lanmou an, mizik evanjelik la pa limite t&egrave;t li, li chwazi pale sou angajman, fidelite ak relasyon ki bati sou prensip diven.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ekip redaksyon konektem nan salye jef&ograve; tout atis ak ekip teknik yo, ki patisipe nan pwodiksyon sa yo, pandan n ap envite piblik la dekouvri oswa dekouvri ank&ograve; videyo ki make mwa fevriye 2026 la, dimanch kap Premye Mas la.</span></p>', 0, 0, 0, NULL);
INSERT INTO `news` (`id`, `newsImg`, `newsTitle`, `newsDate`, `newsCategory`, `newsHeadline`, `fullContent`, `reads`, `likes`, `shares`, `featured_image_ids`) VALUES
(57, 273, 'Palmarès Mizik Evanjelik – Fevriye 2026', '2026-03-01', 'music & video', 'Mwa fevriye 2026 la make pa yon seri lansman ki temwaye vitalite ak evolisyon mizik evanjelik ayisyen an. Nan yon kontèks kote kominote a ap fè fas ak anpil defi sosyal ak emosyonèl, plizyè atis chwazi sèvi ak mizik kòm yon zouti pou pote espwa, lanmou, temwayaj ak konsyans. Soti nan retou atis ki te pran tan silans, rive nan nouvo pwodiksyon ki adrese reyalite espirityèl ak sosyal peyi a, dezyèm mwa ane a konfime dinamis yon sektè ki pa sispann renouvle tèt li.', '<p class=\"p1\"><span class=\"s1\">Mwa fevriye 2026 la make pa yon seri lansman ki temwaye vitalite ak evolisyon mizik evanjelik ayisyen an. Nan yon kont&egrave;ks kote kominote a ap f&egrave; fas ak anpil defi sosyal ak emosyon&egrave;l, plizy&egrave; atis chwazi s&egrave;vi ak mizik k&ograve;m yon zouti pou pote espwa, lanmou, temwayaj ak konsyans. Soti nan retou atis ki te pran tan silans, rive nan nouvo pwodiksyon ki adrese reyalite espirity&egrave;l ak sosyal peyi a, dezy&egrave;m mwa ane a konfime dinamis yon sekt&egrave; ki pa sispann renouvle t&egrave;t li.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Palmar&egrave;s Mizik Evanjelik &ndash; Fevriye 2026 la mete an limy&egrave; div&egrave;site tematik ak stil ki make pery&ograve;d la: rekonesans pou lavi, deklarasyon idantite nan Kris la, angajman nan maryaj, ap&egrave;l pou lapriy&egrave;, mesaj angaje sou sitiyasyon sosyal, ak reafimasyon konfyans nan fidelite Bondye. Chak lansman vini ak yon demach atistik ki chita swa sou temwayaj p&egrave;son&egrave;l, swa sou yon refleksyon kolektif sou lafwa ak responsabilite.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\"><strong>1.</strong> <strong>Evelyne J.B Gesper make retou li ak &ldquo;Mwen la&rdquo; apre pr&egrave;ske dis lane silans</strong></span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 7 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Psalmis Evelyne J.B ouv&egrave; sezon an ak lansman single &ldquo;Mwen la&rdquo; ki soti 7 fevriye 2026, premye pwodiksyon li apr&egrave; pr&egrave;ske dis ane absans sou s&egrave;n mizik evanjelik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mwen la prezante k&ograve;m yon mizik ki pran rasin nan eksperyans p&egrave;son&egrave;l li, ki mete aksan sou yon verite: lef&egrave;t ke nou vivan jodi a se yon gras. Nan yon kont&egrave;ks kote ensekirite ak difikilte makonnen ak reyalite chak jou.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pou psalmis lan, mizik sa a depase kad yon senp pwodiksyon atistik. Li se premye paj yon nouvo chapit nan kary&egrave; li. Nan menm mouvman an, li anonse preparasyon alb&ograve;m &ldquo;Mwen la&rdquo;, yon pwoj&egrave; ki prevwa genyen anviwon dis tit. Dapre psalmis lan, alb&ograve;m nan ap chita sitou sou temwayaj, lafwa ak eksperyans p&egrave;son&egrave;l li pandan ane silans li yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pwoj&egrave; a, ki atann pou fen ane a, ta dwe genyen patisipasyon youn oubyen de atis nan sekt&egrave; evanjelik la, epi pwopoze yon melanj ant chante espirity&egrave;l ak mizik ki pote refleksyon sosyal.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k &ldquo;Mwen la&rdquo;, Evelyne J.B Gesper<span class=\"Apple-converted-space\">&nbsp; </span>reafime vokasyon li k&ograve;m yon vwa ki chwazi mete rekonesans, temwayaj ak konsyans nan sant mizik li.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/crPE_YlbDvU?si=q1bGHWwuwtfTzed_</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">2. Mwen pa pou kont mwen&rdquo; : Lovenson Clerveau relanse vwa li ak yon mesaj asirans</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 8 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Lovenson Clerveau relanse vwa li sou s&egrave;n mizik evanjelik la ak videyo &ldquo;MWEN PA POU KONT MWEN&rdquo; ki soti 8 fevriye 2026, yon pwoj&egrave; ki vini nan yon moman kle nan chemen atistik li. Apre plizy&egrave; ane kote li te f&egrave; yon pa d&egrave;y&egrave;, atis la chwazi retounen ak yon mizik ki chaje ak konviksyon ak eksperyans lavi.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la konstwi sou yon mesaj: kwayan pa janm pou kont li. Paw&ograve;l yo mete aksan sou prezans Bondye k&ograve;m gid ak pwoteksyon, menm l&egrave; reyalite a bay enpresyon kontr&egrave; a. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Refren an, ki repete &ldquo;Yahweh ak mwen&rdquo;, bay mizik la f&ograve;s li.</span></p>\r\n<p class=\"p1\"><span class=\"s1\">D&egrave;y&egrave; pwoj&egrave; sa a gen yon istwa p&egrave;son&egrave;l, defi sante ansanm ak nouvo responsablite familyal, se nan mitan etap sa yo mizik la pran nesans. Olye silans lan tounen yon febl&egrave;s, li s&egrave;vi k&ograve;m tan refleksyon ak matirite. &ldquo;Mwen pa pou kont mwen&rdquo; par&egrave;t konsa k&ograve;m rezilta yon sezon kote lafwa te vin pi konkr&egrave; pase paw&ograve;l.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Sou plan kreyatif, atis la siyen konpozisyon ak pwodiksyon an, sa ki montre volonte li pou kenbe kontw&ograve;l sou direksyon mizikal li.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k nouvo pwodiksyon sa a, Lovenson Clerveau reafime angajman li pou s&egrave;vi ak mizik k&ograve;m zouti pou ranf&ograve;se lafwa ak pote konsolasyon. &ldquo;Mwen pa pou kont mwen&rdquo; deja pozisyone t&egrave;t li k&ograve;m yon mizik konfyans pou sila yo k ap trav&egrave;se moman ens&egrave;ten, menm nan silans ak solitid, prezans Bondye rete fid&egrave;l.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/jlEyomS1va8?si=cQN6K_K1PowhCio_</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">3. Loutchina D&eacute;cius enspire odyans lan ak &ldquo;Moun Pa Konn Renmen Konsa&rdquo;</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 10 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Videyo ofisy&egrave;l &ldquo;Moun Pa Konn Renmen Konsa&rdquo;, ki</span></p>\r\n<p class=\"p1\"><span class=\"s1\">dire 4mn 19 s pibliye 10 fevriye 2026, make yon nouvo etap nan kary&egrave; Loutchina D&eacute;cius sou s&egrave;n mizik krey&ograve;l la. Chante sa a, ekri ak pwodui pa Genoldens Desulma, se yon melanj de emosyon, lanmou ak refleksyon sou rekonesans, kote mesaj espirity&egrave;l ak sansiblite imen rankontre nan yon fason natir&egrave;l.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan t&egrave;ks la, atis la eksprime b&egrave;lte lanmou ki depase tout atant: &ldquo;Moun pa konn renmen konsa&rdquo;, &ldquo;Ou konble m, f&egrave; m bliye tout pwobl&egrave;m mwen&rdquo;, ak &ldquo;Ou f&egrave; ti k&egrave; mwen kontan, sous bon&egrave; mwen&rdquo;. Paw&ograve;l sa yo montre jan lanmou, kit li ant moun oswa nan prezans Bondye, kap pote sekirite, lap&egrave; ak rekonf&ograve; entery&egrave;.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Sou plan vizy&egrave;l, kolaborasyon Alexis Eclesiaste ak Rich&eacute; Richenn pote mizik la nan yon dimansyon plis entim ak vivan. Videyo a mete aksan sou emosyon, ent&egrave;raksyon p&egrave;sonaj yo ak lajwa ki soti nan lanmou ak rekonesans, pandan refren an tounen yon pwen kote odyans lan fasil pou konekte ak mesaj la (Moun pa konn renmen konsa).</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Depi lansman li sou platf&ograve;m dijital yo, &ldquo;Moun Pa Konn Renmen Konsa&rdquo; resevwa yon repons tr&egrave; pozitif, sitou nan mitan j&egrave;n ki idantifye ak mesaj lanmou sens&egrave; ak sans rekonesans li pote. Mizik la montre jan gospel krey&ograve;l mod&egrave;n ka kontinye evolye, enk&ograve;pore emosyon imen san p&egrave;di pwofond&egrave; espirity&egrave;l li, e li etabli Loutchina D&eacute;cius k&ograve;m yon vwa enp&ograve;tan nan mizik ki melanje lanmou, lafwa ak temwayaj p&egrave;son&egrave;l.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/BxPNKzUY2ts?si=exszLctXY2p3qZTT</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">4. Celigny Dathus prezante &ldquo;Mwen Paka Konte&rdquo;, yon nouvo chan temwayaj ki make k&ograve;mansman ane 2026 la</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 12 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis evanjelik Celigny Dathus make k&ograve;mansman ane 2026 la ak lansman videyo ofisy&egrave;l &ldquo;Mwen Paka Konte&rdquo;, ki disponib depi 12 fevriye sou tout platf&ograve;m dijital yo. Single sa a, ki te deja pibliye nan fen 2025, vini k&ograve;m yon temwayaj mizikal ki chaje ak rekonesans, kote atis la ouv&egrave; k&egrave; li sou chemen lafwa li ak transf&ograve;masyon li viv.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan t&egrave;ks la, Celigny Dathus adopte yon ton entim ak refleksyon: &ldquo;Mwen sonje kote&rsquo;m te ye, mwen sonje kote&rsquo;w te pran&rsquo;m.&rdquo; Paw&ograve;l sa yo trase yon liy kl&egrave; ant yon pase ki make pa febl&egrave;s ak yon prezan ki chita sou gras. Metaf&ograve; &ldquo;yon mouton ki kite patiraj li&rdquo; a vin ranf&ograve;se dimansyon biblik mesaj la, pandan li ilistre eta yon moun ki te p&egrave;di direksyon li avan li jwenn delivrans.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Refren an,&ldquo;Mwen paka konte (tout miz&egrave; ou pase av&egrave;&rsquo;m)&rdquo;, pote nwayo emosyon&egrave;l mizik la. Deklarasyon tradui limit imen fas ak grand&egrave; sakrifis ak fidelite Bondye. Se yon rekon&egrave;sans ki pa kalkile, ki pa mezire, men ki soti nan konsyans yon lavi ki chanje.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan yon sekt&egrave; ki ap evolye, mizik sa a pozisyone li k&ograve;m youn nan pwodiksyon ki make k&ograve;mansman ane a, pandan li kenbe esans espirity&egrave;l ki defini estil atis la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/Wk4xwNi7R9U?si=WemImK0OPiDtUQ9h</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">5. Beethoven Chenet selebre lanmou ak lafwa nan &ldquo;Siwomy&egrave;l&rdquo;</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 12 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Beethoven Chenet make s&egrave;n mizik evanjelik ayisyen ak lansman videyo ofisy&egrave;l &ldquo;Siwomy&egrave;l&rdquo;, ki pibliye 12 fevriye 2026. Mizik sa a, yon konpa love ki dous ak melodi, pou s&egrave;vi k&ograve;m yon temwayaj pwofon sou lanmou, rekonesans ak resp&egrave; nan relasyon maryaj, pandan li rete solidman anrasinen nan lafwa kretyen.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a se yon dedikasyon p&egrave;son&egrave;l Beethoven Chenet f&egrave; pou madanm li, li dekri li k&ograve;m sous enspirasyon ak gid nan lavi li. Paw&ograve;l yo, tankou &ldquo;kite siwo a koule&rdquo;, prezante lanmou k&ograve;m yon eksperyans dous, sens&egrave;, val&egrave; espirity&egrave;l. Mizik la montre jan relasyon maryaj ka reflete lafwa, rekonesans, ak prezans Bondye nan lavi kwayan yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Sou plan espirity&egrave;l, &ldquo;Siwomy&egrave;l&rdquo; raple ke maryaj se yon ap&egrave;l sakre: onore ak cheri patn&egrave; ou se yon fason pou montre lafwa ak rekonesans pou Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Videyo a, pwodui pa Productions Koulekreyol bay mizik la yon prezantasyon ki fasil pou odyans lan konekte av&egrave;k li.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Siwomy&egrave;l&rdquo; tounen yon referans nan mizik krey&ograve;l mod&egrave;n kote lanmou, relasyon ak espirityalite rankontre.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/1oX3GjjCsMQ?si=0kBUNG-r_tN-ZXFQ</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">6. Althieu Jean Baptiste renouvle temwanyaj lafwa ak &ldquo;Ou F&egrave;&rsquo;l Vre&rdquo; (Acoustic Version)</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 14 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan yon ep&ograve;k kote anpil moun ap ch&egrave;che espwa, konsolasyon ak sans nan lavi yo, atis evanjelik Althieu Jean Baptiste retounen ak yon mesaj atrav&egrave; &ldquo;Ou F&egrave;&rsquo;l Vre (Acoustic Version)&rdquo;, yon kantik rekonesans ki mete aksan sou fidelite Bondye nan lavi moun. Videyo ofisy&egrave;l la te par&egrave;t 14 fevriye 2026, yon dat senbolik ki selebre lanmou, men fwa sa a, lanmou divin ki pa janm febli.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Ou F&egrave;&rsquo;l Vre&rdquo; soti nan alb&ograve;m atis la ki te lanse an 2020, men nouvo aranjman acoustic sa a bay mizik la yon l&ograve;t dimansyon espirity&egrave;l. Li raple kijan Bondye kontinye akonpli pwom&egrave;s Li yo, menm nan moman difisil yo. Se yon deklarasyon lafwa ki envite chak kwayan sonje mirak Bondye deja f&egrave; nan lavi yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atrav&egrave; pwoj&egrave; sa a, Althieu Jean Baptiste mete devan yon mesaj rekonesans ak adorasyon: Bondye toujou fid&egrave;l, e gras Li depase tout limit imen.</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Nan mizik evanjelik ayisyen an, &ldquo;Ou F&egrave;&rsquo;l Vre (Acoustic Version)&rdquo; par&egrave;t k&ograve;m yon z&egrave;v ki ini adorasyon, refleksyon ak espwa.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Se konsa, atrav&egrave; mizik sa a, Althieu Jean Baptiste kontinye s&egrave;vi k&ograve;m yon vwa ki pote limy&egrave;, raple mond lan ke d&egrave;y&egrave; chak viktwa, chak gerizon ak chak delivrans, gen men Bondye ki toujou ap aji.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/sNNcUbmHslI?si=TMjVVIp3E3_mQ9Yw</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">7. Sony Delly leve idantite wayal kwayan an nan &ldquo;Ou Defini&rsquo;m&rdquo;</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 15 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">15 fevriye 2026, Sony Delly mete disponib videyo ofisy&egrave;l &ldquo;Ou Defini&rsquo;m&rdquo;, yon pwodiksyon ki plonje dir&egrave;kteman nan kesyon idantite espirity&egrave;l ak pozisyon wayal kwayan an nan Kris la. Atrav&egrave; mizik sa a, atis la pwopoze yon deklarasyon lafwa solid, ki chita sou verite biblik kons&egrave;nan diyite, otorite ak eritaj Bondye rez&egrave;ve pou pitit Li yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan devlopman t&egrave;ks la, Sony Delly mete aksan sou transf&ograve;masyon entery&egrave; ki f&egrave;t l&egrave; yon moun rive konprann kiy&egrave;s li ye nan Bondye. Idantite sa a pa s&ograve;ti nan opinyon moun, ni nan limit sikonstans lavi a, men nan ap&egrave;l way&ograve;m Bondye a.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la pa limite li ak dimansyon otorite ak diyite s&egrave;lman, li mete tou aksan sou karakt&egrave; Bondye ki f&ograve;me ak matirite kwayan an. Lanmou Li bay sekirite, saj&egrave;s Li pote direksyon, epi pasyans Li modle karakt&egrave;. Se pa yon kesyon pouvwa senpman, men yon pwosesis transf&ograve;masyon entery&egrave; kote moun nan vin reflete imaj Bondye pi plis chak jou.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/tSEtX5SM8_Q?si=6eQf24WE7mhArVNi</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">8. Dieumet Maurice Repran S&egrave;n Mizik Evanjelik ak &ldquo;Ou Bon&rdquo;</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 15 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Apre plizy&egrave; ane silans ki te koze pa defi p&egrave;son&egrave;l ak chanjman enp&ograve;tan nan lavi li, Dieumet Maurice f&egrave; yon retou remakab sou s&egrave;n mizik evanjelik ayisyen an av&egrave;k de nouvo single ki make k&ograve;mansman ane 2026 la. Nan mwa janvye, li lage &ldquo;Lafwa Pi Ch&egrave;&rdquo;, yon mizik ki eksplore relasyon ant lafwa ak materyalis. Chante a raple kwayan yo ke lafwa pa gen pri e ke okenn avantaj matery&egrave;l pa dwe ranplase relasyon ak Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Swivan lansman sa a, 15 fevriye 2026, Dieumet prezante &ldquo;Ou Bon&rdquo; (Visualizer), yon mizik ki selebre lanmou ak fidelite Bondye. Mizik la envite odyans lan reflechi sou prezans inebranlabl Bondye nan lavi chak kwayan, pandan paw&ograve;l yo ranf&ograve;se rekonesans, konfyans ak adorasyon. Tit sa a vini tou pandan atis la ap prepare yon seri nouvo chante ak yon alb&ograve;m kap vini.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k &ldquo;Lafwa Pi Ch&egrave;&rdquo; ak &ldquo;Ou Bon&rdquo;, Dieumet Maurice pa s&egrave;lman retounen sou s&egrave;n nan, li etabli yon nouvo faz nan kary&egrave; li, kote mizik vin yon zouti pou ranf&ograve;se lafwa, ankouraje refleksyon espirity&egrave;l, epi raple tout kwayan ke Bondye rete fid&egrave;l, inebranlabl, e toujou prezan nan lavi yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/ypzaOi2xtZ4?si=Y-qLc-KXv277Is7x</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">9. Pastor Jean‑Claude D&eacute;risier lanse &ldquo;Di Yon Mo Seny&egrave; Souple&rdquo;</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 16 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">16 fevriye 2026, Pastor Jean‑Claude D&eacute;risier prezante videyo ofisy&egrave;l &ldquo;Di Yon Mo Seny&egrave; Souple&rdquo;, yon mizik ki selebre pouvwa lapriy&egrave; ak lafwa nan Bondye. Chante a raple kwayan yo ke yon s&egrave;l mo Bondye ka chanje lavi, leve moun nan difikilte, epi pote rekonf&ograve; ak diyite.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Paw&ograve;l yo pran inspirasyon nan istwa biblik tankou Josef, Est&egrave; ak Abraham, mete aksan sou kapasite Bondye pou reponn lapriy&egrave; epi akonpaye pitit Li yo nan tout etap lavi yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Di Yon Mo Seny&egrave; Souple&rdquo; se yon mesaj vivan ki konbine adorasyon ak temwayaj, ki ranf&ograve;se pozitifite ak konfyans nan Bondye pou tout odyans mizik evanjelik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/6ir9188wCIg?si=f-AXWoV_Pbr3aoZL</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">10. Ti Doudou&rdquo; : Fre Gabe ak Sr Gabe mete lanmou kretyen an an val&egrave; nan yon videyo</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 18 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">18 fevriye 2026, Fre Gabe prezante videyo ofisy&egrave;l pou mizik &ldquo;Ti Doudou&rdquo;, yon kolaborasyon ak Sr Gabe, ki s&ograve;ti nan alb&ograve;m Inspiration Divine. Chante sa a vini k&ograve;m yon omaj pou lanmou ki bati sou fondasyon Kris la, yon lanmou ki pa kraze malgre epr&egrave;v, soufrans ak difikilte lavi a pote.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan &ldquo;Ti Doudou&rdquo;, mesaj la kl&egrave;: l&egrave; JEZI se sant relasyon an, tanp&egrave;t pa ka detwi sa Bondye ini. Paw&ograve;l yo dekri reyalite plizy&egrave; koup kretyen ki f&egrave; fas ak moman difisil, men ki chwazi rete ini, padone, epi mache ansanm nan lafwa. Mizik la pote yon ton dous, men chaje ak espwa, yon envitasyon pou koup yo sonje rezon espirity&egrave;l ki f&egrave; yo te di &ldquo;wi&rdquo; devan Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Sou plan vizy&egrave;l, videyo a mete an avan yon istwa pwofon, ak patisipasyon JerryBed k&ograve;m figi prensipal. Reyalizasyon an pote siyati Fre Gabe li menm, ki asime plizy&egrave; w&ograve;l nan pwoj&egrave; a: direkt&egrave;, edit&egrave;, coloriste, enjeny&egrave; mix ak mastering. Travay sinematografik la siyen pa KN Visuals.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Fre Gabe ak Sr Gabe ofri kominote evanjelik la yon mesaj ki kl&egrave;: l&egrave; Jezi se fondasyon yon relasyon an, okenn difikilte pa ka f&egrave; li tonbe.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/U3x_YcZk3uU?si=XCt0YKJj0xYHk7wi</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">11. Mwen Deklare O Non de Jezi&rdquo; &ndash; Yon deklarasyon lafwa ki mete Sy&egrave;l la k&ograve;m objektif final</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 19 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan dat 19 fevriye 2026, Jean Claude D&eacute;risier(Zoom), ansanm ak Joy Clerf D&eacute;risier, prezante mizik &ldquo;Mwen Deklare O Non de Jezi&rdquo;, yon kantik ki chaje ak konviksyon espirity&egrave;l ak det&egrave;minasyon pou mache dwat sou chimen delivrans lan.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a bati sou yon b&egrave;l deklarasyon: &ldquo;Anbisyon mwen se pou m sove.&rdquo; Nan yon ep&ograve;k kote anpil moun ap kouri d&egrave;y&egrave; reyalizasyon matery&egrave;l, mizik la vini k&ograve;m yon repons kl&egrave; ke pi gwo objektif lavi a se antre nan way&ograve;m Bondye. Paw&ograve;l yo ensiste sou yon verite biblik: lajan, l&ograve;, dyaman, b&egrave;l kay ak tout on&egrave; ki sou lat&egrave; gen pou pase, s&egrave;l Paw&ograve;l Bondye ak glwa Li ki rete pou tout tan.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k &ldquo;Mwen Deklare O Non de Jezi&rdquo;, Jean Claude D&eacute;risier ak Joy Clerf D&eacute;risier ofri kominote evanjelik la yon kantik ki ankouraje det&egrave;minasyon, disiplin espirity&egrave;l ak konsyans sou sa ki vr&egrave;man gen val&egrave;. Se yon mizik ki raple ke, nan mitan bri mond lan, desizyon ki pi enp&ograve;tan an rete sa a: chwazi Sy&egrave;l la k&ograve;m destinasyon final.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/W6hCkh2kFiI?si=KCt-IIiZiw4F6B5D</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">12. Sou Paw&ograve;l Bondye, Astharmonie Chwazi Kanpe</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 20 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan yon moman kote anpil moun ap f&egrave; fas ak dout ak ens&egrave;titid, &ldquo;KANPE&rdquo; vini tankou yon souf ankourajman. Atrav&egrave; videyo ofisy&egrave;l sa a ki soti 20 fevriye 2026, gwoup Astharmonie mete devan yon mesaj enp&ograve;tan : Bondye rete fid&egrave;l, k&egrave;lkeswa sezon an.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la pote siyati konpozit&egrave; Genoldens Desulma (Dens Music Haiti) ansanm ak Alexis Fortun&eacute;, ki asire vwa prensipal la. Depi k&ograve;mansman mizik la, paw&ograve;l yo trase yon verite ki depase tan : menm si tout bagay chanje, Bondye pa chanje. Li se &ldquo;Dieu Isra&euml;l&rdquo; ki kenbe paw&ograve;l Li e ki reyalize sa Li pwom&egrave;t.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Refren an se k&egrave; mesaj la : Bondye pa janm pale pou Li pa aji. Li gen plan pou siks&egrave; pitit Li yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/w7nLTC2wQNs?si=Uk0GhANHaUD5ddCt</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">13. Les Elus ak David Deg Mete Lespri an Aksyon ak &ldquo;La Flamma&rdquo;</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\"> 🗓️ 22 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Gwoup Les Elus, ansanm ak David Deg, prezante videyo ofisy&egrave;l pou mizik &ldquo;La Flamma&rdquo; 22 fevriye 2026, yon single ki melanje en&egrave;ji, espirityalite, ak yon ap&egrave;l pou devouman nan way&ograve;m Bondye. Mizik la transm&egrave;t yon mesaj f&ograve; pou kwayan yo: pou briye ak manifeste prezans Bondye nan lavi yo, f&ograve;k yo dak&ograve; boule, sakrifye, epi ch&egrave;che way&ograve;m Bondye an premye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Paw&ograve;l yo ankouraje chak kwayan mete Jezi k&ograve;m mod&egrave;l yo epi donnen fwi Lespri a nan lavi yo. &ldquo;La Flamma&rdquo; ofri yon eksperyans espirity&egrave;l ki envite moun pran angajman, epi viv yon lavi kote prezans Bondye klere nan chak aksyon. Videyo a, ak melodi dinamik ak koregrafi ki pote vizyon mizik la f&egrave; single sa a tounen yon zouti f&ograve; pou ankourajman ak adorasyon nan kominote evanjelik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/J1TsEF9xaOw?si=0QEnMN_DAq05w0IK</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">14. Fr Gabe frape feat Deborah &ldquo;Viv Ansanm&rdquo;, yon mesaj dir&egrave;k pou Babekyou</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 24 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pandan l ap prepare pou vant siyati li ki pwograme pou 8 mas 2026 nan pal&egrave; minipal , Fr Gabe ansanm ak Deborah Henristal lanse videyo ofisy&egrave;l &ldquo;Viv Ansanm&rdquo;, yon nouvo pwodiksyon ki soti 24 fevriye 2026. Mizik sa a vini jis k&egrave;k jou apre clip &ldquo;Ti Doudou&rdquo;. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan &ldquo;Viv Ansanm&rdquo;, Fr Gabe adopte yon stil rap angaje pou l adrese dir&egrave;kteman Babekyou, nan yon t&egrave;ks ki chaje ak kesyonnman, fristrasyon ak ap&egrave;l pou konsyans. Paw&ograve;l yo pa pase pa kat chemen: yo pale de ensekirite, doul&egrave; manman k ap kriye, j&egrave;n san avni, politisyen ki akize de konplisite, epi yon sosyete ki sanble ap tonbe anba pw&ograve;p pwa li.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Fr Gabe mete anfas reyalite sosyal la ak r&egrave;v yon Ayiti kote jen&egrave;s la ta ka jwenn op&ograve;tinite, kote lek&ograve;l ak lopital ta fonksyone, kote zam pa ta ranplase liv. Li denonse sist&egrave;m nan, men li envite tou chak sitwayen gade t&egrave;t li nan glas, pran responsabilite li, epi ch&egrave;che yon l&ograve;t direksyon.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k videyo sa a, Fr Gabe montre yon l&ograve;t fas nan kreyasyon li: yon atis ki pa p&egrave; antre nan sij&egrave; sansib, ki melanje lafwa, angajman sosyal ak ekspresyon p&egrave;son&egrave;l. &ldquo;Viv Ansanm&rdquo; deja anonse k&ograve;m yon mizik ki pral f&egrave; pale, reflechi, epi pwovoke deba nan mitan piblik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/9-aDkbKktBA?si=jhLrRnrTvZg-bAir</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">15. Derson Alcide lanse &ldquo;Bondye Ou Bon&rdquo;, yon mizik rekonesans ak adorasyon</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 25 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Derson Alcide prezante yon mizik ki selebre lanmou, pwoteksyon ak fidelite Bondye. &ldquo;Bondye Ou Bon&rdquo;, ki s&ograve;ti 25 fevriye 2026, envite odyans lan reflechi sou prezans inebranlab Bondye nan lavi chak moun, lajounen kou lannwit.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a se yon temwayaj p&egrave;son&egrave;l kote atis la rem&egrave;sye Bondye pou padon, jistis ak fav&egrave;<span class=\"Apple-converted-space\">&nbsp; </span>li. </span></p>\r\n<p class=\"p1\"><span class=\"s1\"> &ldquo;Bondye Ou Bon&rdquo; mete aksan sou rekonesans ki s&ograve;ti nan k&egrave;, epi tounen yon zouti adorasyon ak ankourajman espirity&egrave;l pou tout moun k ap tande li.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/BZLXIG2CJEs?si=ZYVjknDlcX_PUU1l</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><strong><span class=\"s1\">16. Gospel Saphir Ministry reprann &ldquo;Omemma&rdquo; ak deklarasyon lafwa &ldquo;Bondye m nan ap travay&rdquo;</span></strong></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 28 fevriye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Gospel Saphir Ministry prezante &ldquo;Bondye m nan ap travay&rdquo;, yon cover enspire de &ldquo;Omemma&rdquo;, konpozisyon orijinal Chandler Moore ak Tim Godfrey. S&ograve;ti 28 fevriye 2026, mizik la pote yon mesaj ankourajman: pwosesis la poko fini, e Bondye toujou ap aji menm l&egrave; nou pa w&egrave; sa.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan adaptasyon sa a, Gospel Saphir Ministry mete aksan sou konfyans ak pasyans nan mitan epr&egrave;v.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Sou plan mizikal, aranjman vwa yo pote siyati Peterson Luc ak Stanley Shegger Aris, pandan sekans, mixaj ak mastering f&egrave;t pa Shegger Beats. Pwodiksyon ak realizasyon videyo a asire pa GSM Studio.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k cover sa a, Gospel Saphir Ministry pa s&egrave;lman reprann yon chan ent&egrave;nasyonal, yo adapte li nan yon kont&egrave;ks kote anpil moun bezwen sonje ke, malgre presyon ak ensekirite lavi a, Bondye pa janm abandone pitit Li. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Bondye m nan ap travay&rdquo; prezante k&ograve;m yon mizik ki ankouraje lafwa, epi ki envite chak moun rete f&egrave;m pandan Bondye ap kontinye f&egrave; travay Li.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/p0Wffv9DT5o?si=Z6HXvgE6tYF5fG2_</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ansanm, pwodiksyon sa yo trase yon tablo ki montre yon endistri k ap grandi, k ap pran risk kreyatif, epi k ap ch&egrave;che reponn a bezwen espirity&egrave;l yon jenerasyon k ap ch&egrave;che direksyon. Mizik evanjelik la pa limite t&egrave;t li ak adorasyon tradisyon&egrave;l s&egrave;lman, li antre nan sij&egrave; lanmou, kriz sosyal, idantite, maryaj ak angajman p&egrave;son&egrave;l.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Palmar&egrave;s Fevriye 2026 se yon temwanyaj sou mouvman, sou kreyativite ak sou konsyans yon sekt&egrave; ki konprann misyon li. Pandan ane 2026 la ap kontinye, pwodiksyon mwa fevriye yo deja poze yon baz solid, ki anonse yon sezon kote mizik ap rete yon vwa ki ankouraje, epi raple ke nan mitan tout bagay, lafwa kontinye rete pwen referans lan.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Redaksyon:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Vanauscheca Bouzy</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Konsepsyon Grafik:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Jo-Flung Bouzy &amp; Abdullah Mode</span></p>', 0, 0, 0, NULL),
(58, 280, 'Jo-Flung Bouzy pral kouvri, an eksklizivite, 6zyèm edisyon Caribbean Worshippers nan Peyi Bahamas pou konektem.', '2026-03-10', 'international', 'Nan yon moman kote inisyativ evanjelik yo ap pran plis anplè nan Karayib la, Zye plizyè milye fidèl ak obsèvatè fikse sou youn nan pi gwo rasanbleman espirityèl nan rejyon an,Caribbean Worshippers. Ane sa a make sizyèm edisyon evènman entènasyonal sa, ki pral dewoule ankò nan vil Nassau, nan Bahamas. Se yon edisyon ki deja pwomèt yon ansanm aktivite ki pral make listwa.', '<p class=\"p1\"><span class=\"s1\">Nan yon moman kote inisyativ evanjelik yo ap pran plis anpl&egrave; nan Karayib la, Zye plizy&egrave; milye fid&egrave;l ak obs&egrave;vat&egrave; fikse sou youn nan pi gwo rasanbleman espirity&egrave;l nan rejyon an,Caribbean Worshippers. Ane sa a make sizy&egrave;m edisyon ev&egrave;nman ent&egrave;nasyonal sa, ki pral dewoule ank&ograve; nan vil Nassau, nan Bahamas. Se yon edisyon ki deja pwom&egrave;t yon ansanm aktivite ki pral make listwa.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pou pa kite okenn ti detay pase, medya Konekte M pran angajman pou pote tout nouv&egrave;l yo, nan tan rey&egrave;l, pou piblik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan sans sa a, Media a deside deplase pami teknisyen ki pi devwe li yo, Jo-Flung Bouzy, ki pral sou plas pou kouvri aktivite a san manke yon l&ograve;sy&egrave;, nan objektif pou f&egrave; piblik la viv chak moman enp&ograve;tan yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Depi plizy&egrave; ane, Caribbean Worshippers etabli t&egrave;t li k&ograve;m yon gwo platf&ograve;m kote adorasyon, mesaj espirity&egrave;l ak inite nan mitan tout kretyen rankontre. Chak edisyon rasanble plizy&egrave; sant&egrave;n patisipan ki soti nan div&egrave;s peyi nan Karayib la ak l&ograve;t kote nan mond lan. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ane sa a, Neslin Destilhomme, vizyon&egrave; prensipal Caribbean association nan, anonse yon pwogram ki gen plis anpl&egrave; toujou, ak prezans plizy&egrave; p&egrave;sonalite enp&ograve;tan nan milye evanjelik la, lid&egrave; relijye, atis gospel ak orat&egrave; ent&egrave;nasyonal.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan mitan gwo preparasyon sa yo kap f&egrave;t, prezans Jo-Flung Bouzy sou teren an reprezante yon avantaj estratejik pou Konekte M. Konnen pou disiplin li, dinamis li ak sans pwofesyonalis li, teknisyen an gen misyon pou asire pa gen okenn moman enp&ograve;tan ki rate, depi ouv&egrave;ti seremoni yo, rive sou gwo moman adorasyon yo, ent&egrave;vyou ak envite espesyal yo, jiska gwo moman nan ev&egrave;nman an k ap domine nan Nassau pandan tout dire ev&egrave;nman an.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pou koulye a teknisyen an deja okap nan wout pou rantre Bahamas, jodi madi 10 Mas 2026 la.</span></p>\r\n<p class=\"p1\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Redaksyon ✍️: Ronalson Blanfort</span></p>', 0, 0, 0, NULL);
INSERT INTO `news` (`id`, `newsImg`, `newsTitle`, `newsDate`, `newsCategory`, `newsHeadline`, `fullContent`, `reads`, `likes`, `shares`, `featured_image_ids`) VALUES
(59, 281, 'Palmarès Mizik/Videyo Evanjelik – Mas 2026', '2026-04-01', 'music & video', 'Mwa mas 2026 la make pa yon richès pwodiksyon nan mizik evanjelik ayisyen an, kote plizyè atis kontinye sèvi ak talan yo pou pote mesaj ki pale ak lavi chak jou. Nan yon kontèks sosyal ki souvan chaje ak ensètitid, mizik gospel la kontinye jwe yon wòl enpòtan kòm yon sous espwa, direksyon ak ankourajman.\r\n\r\nPalmarès sa a mete an avan mizik ak videyo ki make mwa a, pa sèlman pou kalite pwodiksyon yo, men sitou pou kapasite yo genyen pou transmèt mesaj ki klè, vivan e ki fasil konekte ak odyans lan.', '<p class=\"p1\"><span class=\"s1\">Mwa mas 2026 la make pa yon rich&egrave;s pwodiksyon nan mizik evanjelik ayisyen an, kote plizy&egrave; atis kontinye s&egrave;vi ak talan yo pou pote mesaj ki pale ak lavi chak jou. Nan yon kont&egrave;ks sosyal ki souvan chaje ak ens&egrave;titid, mizik gospel la kontinye jwe yon w&ograve;l enp&ograve;tan k&ograve;m yon sous espwa, direksyon ak ankourajman.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Palmar&egrave;s sa a mete an avan mizik ak videyo ki make mwa a, pa s&egrave;lman pou kalite pwodiksyon yo, men sitou pou kapasite yo genyen pou transm&egrave;t mesaj ki kl&egrave;, vivan e ki fasil konekte ak odyans lan.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">1. &ldquo;A Zewo&rdquo; &ndash; Yon klasik ki reprann f&ograve;s li nan yon nouvo jenerasyon (2 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fr&egrave; Gabe &amp; Jean Ren&eacute; Charles</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Fr&egrave; Gabe (direksyon atistik, mix &amp; mastering)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Fr&egrave; Gabe pote yon nouvo lavi nan &ldquo;A Zewo&rdquo;, yon chante ki deja make anpil jenerasyon. Mizik la kontinye pote menm verite a: viktwa Kris la deja akonpli, e okenn sitiyasyon pa ka chanje sa.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nouvo v&egrave;syon an mete plis aksan sou aks&egrave; ak son mod&egrave;n, pandan li kenbe sans espirity&egrave;l orijinal la. Patisipasyon Jean Ren&eacute; Charles bay pwoj&egrave; a yon dimansyon espesyal, paske li kreye yon pon ant jenerasyon yo. Rezilta a se yon mizik ki pa s&egrave;lman sonnen byen, men ki f&egrave; moun reprann konfyans yo nan lafwa yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/NydtNzZEODc?si=8X_-0gIZ0_Sx0oU0</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">2. &ldquo;Semans Profetik&rdquo; &ndash; Yon mesaj sou pasyans ak konfyans (5 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe &amp; Holy Music</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan kolaborasyon ak Holy Music, Fre Gabe prezante yon mizik ki baze sou yon lide pwisan: paw&ograve;l Bondye pa janm p&egrave;di.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Semans Profetik&rdquo; mete aksan sou pwosesis la: sa Bondye di a pran tan pou li par&egrave;t, men li toujou rive. Mizik la pale ak moun ki poko w&egrave; rezilta yo, men ki bezwen kenbe lafwa yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/tgad9ssfoUE?si=J-aNb2P9zhDM-MGq</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">3. &ldquo;Map Rive&rdquo; &ndash; Lafwa ki refize bay legen (6 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Chrystelha</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Pierre Alan Gabriel(Fr&egrave; Gabe)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chrystelha pote yon mizik ki chaje ak det&egrave;minasyon. &ldquo;Map Rive&rdquo; pa inyore difikilte yo, men li mete aksan sou kapasite pou depase yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Paw&ograve;l yo montre yon moun ki konprann ke chemen an pa fasil, men ki refize rete bloke. En&egrave;ji vokal la ak ritm lan soutni mesaj la, sa ki f&egrave; mizik la tounen yon sous motivasyon pou moun k ap lite pou avanse.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/Gzfv-irU-Zw?si=70ld1zcghxTHrbYR</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">4. &ldquo;Gras Enfini&rdquo; &ndash; L&egrave; lanmou Bondye depase limit imen (8 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe &amp; Stanley Georges</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Fre Gabe ak Stanley Georges mete aksan sou yon reyalite enp&ograve;tan: gras Bondye pa depann de sa moun merite.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la devlope sou lide ke menm l&egrave; moun f&egrave; er&egrave;, Bondye toujou bay yon l&ograve;t chans. Ent&egrave;pretasyon an dous, sa ki bay mizik la yon dimansyon ki ka touche nenp&ograve;t moun.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/ZGsIBYrGsFU?si=m1LybVUIVzEMocG0</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">5. &ldquo;Pa F&egrave; Sa&rdquo; &ndash; Yon ap&egrave;l kl&egrave; pou chanje direksyon (9 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan mizik sa a, mesaj la dir&egrave;k e san konpwomi. Fre Gabe chwazi ab&ograve;de konp&ograve;tman ki ka detwi lavi espirity&egrave;l yon moun, epi li envite odyans lan reflechi sou chwa yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Se yon mizik ki pa ch&egrave;che f&egrave; moun santi yo al&egrave;z, men pito pouse yo pran konsyans. F&ograve;s li chita nan jan li di bagay yo kl&egrave;man.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/HaZdolLBfQM?si=2HN98HFvsebiPWqy</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">6. &ldquo;JESUS CHRIST&rdquo; &ndash; Mete Jezi nan sant lavi a<span class=\"Apple-converted-space\">&nbsp; </span>(9 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe &amp; Les Elus</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ak patisipasyon Les Elus, mizik sa a prezante Jezi k&ograve;m sous lavi, delivrans ak direksyon.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Estrikti chante a ak referans biblik yo bay li yon dimansyon ans&egrave;yman, pandan li rete yon mizik adorasyon. Li envite odyans lan retounen nan fondasyon lafwa yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/ndZNuEThoII?si=hFwp6FQaROgm1Uqx</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">7. &ldquo;Inspiration Divine&rdquo; &ndash; Yon refleksyon sou val&egrave; ak misyon moun<span class=\"Apple-converted-space\">&nbsp; </span>(10 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe &amp; Chrystelha</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan kolaborasyon ak Chrystelha, mizik sa a mete aksan sou idantite ak objektif lavi.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Li montre ke chak moun gen yon plas ak yon misyon, e li ankouraje moun pran konsyans de val&egrave; yo. Ton an kalm, men mesaj la f&ograve; e kl&egrave;.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/hhhd0CUEHkw?si=Pz4Z5H_LTr-97PiM</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">8. &ldquo;Tw&ograve;n Nan Gon Moun&rdquo; &ndash; Espwa nan mitan difikilte (10 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe &amp; Palmyre Seraphin</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: DonGad Beats, Fre Gabe, Dickson Guillaume</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Fre Gabe ak Palmyre Seraphin prezante yon mizik ki montre kijan Bondye ka chanje nenp&ograve;t sitiyasyon.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Paw&ograve;l yo mete aksan sou transf&ograve;masyon: soti nan doul&egrave; pou rive nan viktwa. Mizik la pote yon mesaj espwa pou moun k ap trav&egrave;se moman difisil.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/-at5-WGDHJE?si=jRvDGD3c8sOhNjFx</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">9. &ldquo;Le Roi de Gloire&rdquo; &ndash; Yon adorasyon ki rasanble<span class=\"Apple-converted-space\">&nbsp; </span>(11 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe &amp; Les Elus</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Kolaborasyon ak Les Elus a bay mizik sa a yon f&ograve;s espesyal.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Repete refren yo ak amoni vokal yo kreye yon santiman adorasyon ki fasil pou moun antre ladan l. Li mete aksan sou grand&egrave; Bondye san konplike mesaj la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/oBe1LuQjPuM?si=fQvt5plHJGVvyUwU</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">10. &ldquo;Sakrifi&shy;s Mwen&rdquo; &ndash; Yon relasyon p&egrave;son&egrave;l ak Bondye (11 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Psalmiste Sterlande Etienne</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Mission &Eacute;glise de Dieu de la Conqu&ecirc;te</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Sr Sterlande Etienne prezante yon mizik ki se yon priy&egrave;.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Paw&ograve;l yo montre yon moun ki deside bay t&egrave;t li n&egrave;t bay Bondye, pa s&egrave;lman nan paw&ograve;l, men nan lavi li. Mizik la tounen yon eksperyans pou kwayan k ap koute li.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/Kx150gXZqi8?si=mIGYxYtBfzUOYYW8</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">11. &ldquo;Al Di Yo&rdquo; &ndash; Yon mesaj ki mande aksyon (12 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe &amp; Mike Lee Elminis</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Fre Gabe ak Mike Lee Elminis pote yon mizik ki gen yon sans ijans.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Li envite kominote a pa kenbe lafwa pou t&egrave;t yo s&egrave;lman, men pataje li. Mizik la mete aksan sou preparasyon ak responsablite espirity&egrave;l.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/tsn4dpytO4M?si=hNfx5V1efz7CoFw8</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">12. &ldquo;Dife&rdquo; &ndash; Yon lafwa ki pa ka etenn<span class=\"Apple-converted-space\">&nbsp; </span>(14 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Dife&rdquo; prezante lafwa k&ograve;m yon f&ograve;s ki vivan e ki pa ka dispar&egrave;t.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la montre ke yon kretyen pa<span class=\"Apple-converted-space\">&nbsp; </span>kraze fasil, paske li pote prezans Bondye anndan li. Paw&ograve;l yo montre idantite Kretyen k&ograve;m moun ki kanpe solid, menm l&egrave; lavi pote defi.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/Srquhyg0wq4?si=TCxI7Smh6XDQDmZ7</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">13. &ldquo;Fe K&egrave;w Kontan&rdquo; &ndash; Kenbe lajwa a malgre tout bagay Jorvin Keyz (14 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Jorvin Keyz</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Jorvin Keyz mete aksan sou konfyans nan Bondye k&ograve;m sous k&egrave; kontan.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la raple ke menm nan moman difisil, gen rezon pou rete pozitif, paske Bondye toujou ap travay.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/aDoZwQsShfo?si=b_unxQXwL1K76_KB</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">14. &ldquo;Tan Pou&rsquo;m Temwanye&rdquo; &ndash; Pataje sa Bondye f&egrave; Carl Emile (15 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Carl Emile</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: C-Films, Jean Daniel Pierre</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Carl Emile prezante temwayaj k&ograve;m yon zouti pou ankouraje l&ograve;t moun.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la montre kijan Bondye reponn priy&egrave; epi chanje lavi. Li ankouraje kwayan yo pale de eksperyans yo pou bati lafwa l&ograve;t moun.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/6nD5fz5evNY?si=LOjVKfXzU1bSaMWN</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">15. &ldquo;Action de Gr&acirc;ce&rdquo; &ndash; Rekonesans apre epr&egrave;v Yvensonn Ayiti (15 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Yvensonn Ayiti</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Yvensonn Ayiti pote yon mizik ki baze sou yon istwa vivan.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Li montre kijan difikilte pa d&egrave;nye mo a, paske Bondye toujou gen kapasite pou retabli. Mizik la envite fanmi rete nan rekonesans.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/V6-RXeNUZ_E?si=kMofxNnUVI4JyTP_</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">16. &ldquo;Yaweh&rdquo; &ndash; Grand&egrave; Bondye san limit<span class=\"Apple-converted-space\">&nbsp; </span>(19 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: D-MUSIC</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">D-MUSIC mete aksan sou pouvwa Bondye sou tout kreyasyon. Mizik la s&egrave;vi ak paw&ograve;l ki f&ograve; pou montre ke pa gen okenn f&ograve;s ki ka konpare ak li.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽<span class=\"Apple-converted-space\">&nbsp; </span>https://youtu.be/0EqOsBuR7tc?si=rEm7toEvSlX8VTsz</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">17. &ldquo;Original&rdquo; &ndash; Asirans nan Jezi (21 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Jean Claude Derisier Zoom, Clavens Derisier, Dieudonn&eacute; Derisier T.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Jean Claude Derisier Zoom ak kolaborat&egrave; li yo pote yon mizik ki chaje ak konfyans. Paw&ograve;l yo montre ke ak Jezi, yon moun pa ka fini mal. Mizik la vin yon deklarasyon lafwa.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/lrh2wbPjPnI?si=Muc6P7UHIDSSEx8d</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">18. &ldquo;S&ograve;lda&rdquo; &ndash; Kanpe f&egrave;m nan lafwa<span class=\"Apple-converted-space\">&nbsp; </span>(22 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Koral DEG feat Oliver Math&eacute;o &amp; Ariana Lafond</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Desulma Genoldens, Shegger Beat, David Morinvil</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Koral DEG prezante kwayan yo k&ograve;m s&ograve;lda ki pa p&egrave; batay. Mizik la mete aksan sou lafwa k&ograve;m zam prensipal, e li ankouraje kominote a rete f&egrave;m malgre difikilte.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/47CaSK8FlKQ?si=iVfDoL_H70BCOm1n</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">19. &ldquo;Restore&rsquo;m&rdquo; &ndash; Yon lavi ki ka repare<span class=\"Apple-converted-space\">&nbsp; </span>(26 mas 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Emmanuel CENE</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Rodberry Jacques</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Psalmiste Emmanuel CENE ofri yon mizik ki santre sou gerizon ak renouv&egrave;lman.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Paw&ograve;l yo montre yon moun ki vin devan Bondye ak senserite pou mande chanjman. Mizik la pote yon mesaj espwa pou moun ki santi yo kraze.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/UdY480M_Zwo?si=F5Du_RoWEayZFiij</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Palmar&egrave;s mwa mas 2026 la montre yon mizik evanjelik ki pa s&egrave;lman ap evolye sou plan son, men ki kontinye kenbe f&ograve;s li nan mesaj li yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chak mizik pote yon pati nan lavi: gen ki ankouraje, gen ki korije, gen ki raple, men yo tout gen menm objektif ede moun rete konekte ak lafwa yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Redaksyon:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Vanauscheca Bouzy</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Konsepsyon Grafik:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Jo-Flung Bouzy &amp; Abdullah Mode</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">#palmar&egrave;smizikvideyo</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#evanjelik</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#mas2026 </span></p>\r\n<p class=\"p1\"><span class=\"s1\"><a href=\"https://www.konektem.net/blog/hashtags/konektem\">#konekte</a>m</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#toutkotenenpotkil&egrave;</span></p>', 0, 0, 0, NULL),
(60, 322, 'PALMARÈS MIZIK & VIDEYO EVANJELIK – AVRIL 2026', '2026-05-01', 'music & video', 'Mwa avril 2026 la make yon nouvo etap nan dinamik mizik evanjelik la, ak yon seri pwodiksyon ki kontinye montre richès ak divèsite sektè a. Atis yo, atravè diferan apwòch mizikal ak espirityèl, mete an avan mesaj ki santre sou lafwa, rekonesans, konfyans ak relasyon ak Bondye.', '<p class=\"p1\"><span class=\"s1\">Mwa avril 2026 la make yon nouvo etap nan dinamik mizik evanjelik la, ak yon seri pwodiksyon ki kontinye montre rich&egrave;s ak div&egrave;site sekt&egrave; a. Atis yo, atrav&egrave; diferan apw&ograve;ch mizikal ak espirity&egrave;l, mete an avan mesaj ki santre sou lafwa, rekonesans, konfyans ak relasyon ak Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Palmar&egrave;s sa a pa limite ak yon lis mizik ki soti pandan mwa a, li reflete yon mouvman kote mizik la s&egrave;vi k&ograve;m yon mwayen ekspresyon ak transmisyon espirity&egrave;l. Chak pwodiksyon prezante yon vizyon, yon eksperyans, ak yon fason pou konprann plas Bondye nan lavi w.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">SELEKSYON OFISY&Egrave;L LA</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">1. &ldquo;S&ograve;m 103&rdquo; &ndash; Charma Guillaume (2 avril 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;S&ograve;m 103&rdquo; pran enspirasyon li nan Liv S&ograve;m yo pou mete aksan sou rekonesans k&ograve;m yon eleman santral nan lavi espirity&egrave;l. Chante a montre kijan yon pasaj biblik ka entegre nan lavi chak jou, kote chak moman vin yon okazyon pou onore Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Apw&ograve;ch mizikal la p&egrave;m&egrave;t mesaj la rete aksesib, pandan li kons&egrave;ve referans biblik li yo. Se yon pwodiksyon ki ankouraje yon relasyon kontiny&egrave;l ak Bondye, san li pa depann de sikonstans.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏻 https://youtu.be/OCzj2oQEF60?si=J4tZHr6nRMOQhkGR</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">2. &ldquo;Pour Moi&rdquo; &ndash; Emmanuel Pierre ft. Spencer Brutus (3 avril 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k &ldquo;Pour Moi&rdquo;, atis yo mete aksan sou dimansyon p&egrave;son&egrave;l sakrifis Kris la. Mizik la devlope lide ke lanmou Bondye pa yon kons&egrave;p jeneral, men yon reyalite ki kons&egrave;ne chak moun endividy&egrave;lman.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ent&egrave;pretasyon an p&egrave;m&egrave;t odyans lan antre nan yon refleksyon sou val&egrave; ak plas yo genyen nan je Bondye, pandan li raple enp&ograve;tans gras la nan lavi kretyen an.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏻 https://youtu.be/pFkZB12BkJY?si=YSOOlU_dxvweRZgn</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">3. &ldquo;Rekonesans&rdquo; &ndash; N&eacute;issa Fran&ccedil;ois (6 avril 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Rekonesans&rdquo; prezante yon refleksyon sou limit langaj imen l&egrave; li ap eseye eksprime grand&egrave; Bondye. Adaptasyon an mete aksan sou yon adorasyon ki pa depann de kantite paw&ograve;l, men de senserite k&egrave; a.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Refren an, ki santre sou &ldquo;alelouya&rdquo;, montre yon apw&ograve;ch kote louwanj lan rete yon aksyon esansy&egrave;l, menm l&egrave; pa gen l&ograve;t f&ograve;m ekspresyon.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏻 https://youtu.be/61L5ZxcLIdg?si=VTP_WbP5a1yOoIE4</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">4. &ldquo;Papa Responsab&rdquo; &ndash; Joy Clerf Derisier ft. Mike-Lee Elminis (9 avril 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik sa a devlope yon tematik ki gen rap&ograve; ak fidelite Bondye nan moman difisil. Li mete an paral&egrave;l eksperyans aband&ograve;nman ak prezans yon Bondye ki rete konstan.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Atrav&egrave; narasyon li, chante a montre yon konpreyansyon kote Bondye par&egrave;t k&ograve;m yon Papa ki angaje, ki soutni, epi ki pa retire prezans li malgre sikonstans yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏻 https://youtu.be/QiYHuRmoexo?si=GlzPSKOYcIaoTgQL</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">5. &ldquo;Moun Pa Konnen Renmen Kon Sa (Remix)&rdquo; &ndash; Loutchina D&eacute;cius ft. Stanley Georges (14 avril 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Remix sa a prezante lanmou k&ograve;m yon eksperyans ki depase kad emosyon&egrave;l pou antre nan yon dimansyon espirity&egrave;l. Referans ak Adan ak &Egrave;v mete lanmou a nan yon kad espirity&egrave;l, kote li pa jis yon rankont&hellip; men yon plan.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Kolaborasyon ant de atis yo p&egrave;m&egrave;t yon ent&egrave;pretasyon ki mete an avan konplemantarite ak vizyon pataje sou lanmou k&ograve;m yon val&egrave; ki gen orijin diven.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏻 https://youtu.be/LawqpSyu9Lg?si=Vr5TI8s_Vy5UjI2Z</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">6. &ldquo;M Asire&rdquo; &ndash; Kadosh Music (19 avril 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;M Asire&rdquo; mete aksan sou konfyans nan pwom&egrave;s Bondye k&ograve;m fondasyon lafwa. Mizik la prezante difikilte yo k&ograve;m yon etap pasaj&egrave;, pandan li mete priyorite sou estabilite espirity&egrave;l.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Estrikti t&egrave;ks la montre yon apw&ograve;ch kote lafwa vin yon angajman ki depase reyaksyon imedya, pou li chita sou konviksyon ak konpreyansyon Paw&ograve;l Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏻 https://youtu.be/BO8FvqPbc3k?si=OiXxQLRxAiKNPhKx</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">7. &ldquo;F&egrave;m Kanpe&rdquo; &ndash; Kerlyne Liberus (24 avril 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;F&egrave;m Kanpe&rdquo; chita sou yon eksperyans p&egrave;son&egrave;l kote lafwa s&egrave;vi k&ograve;m pwen sip&ograve; nan yon kont&egrave;ks difisil. Mizik la prezante yon refleksyon sou kapasite moun pou kontinye avanse gras ak konfyans nan Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Apw&ograve;ch slow-worship la bay plas pou yon mesaj ki santre sou soutyen espirity&egrave;l ak direksyon nan moman ens&egrave;titid.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏻 https://youtu.be/Xtshwv8PbU8?si=okJ43EuXDoT_kmwn</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">8. &ldquo;Kanpe Av&egrave;&rsquo;m&rdquo; &ndash; Ishama Gospel ft. Loutchina D&eacute;cius (25 avril 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante sa a devlope yon lide ki santre sou bezwen prezans Bondye nan tout dimansyon lavi. Li mete aksan sou depandans espirity&egrave;l k&ograve;m yon eleman kle nan relasyon ak Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ent&egrave;pretasyon an rete aliyen ak mesaj la, pandan pwodiksyon an p&egrave;m&egrave;t paw&ograve;l yo rete nan sant eksperyans lan.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏻 https://youtu.be/KTlLXsmppxQ?si=X1rzDs6UvoHxVrAk</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">9. &ldquo;Ou Se Tout&rdquo; &ndash; Micka&euml;lle Maisonneuve (26 avril 2026)</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Enspire de S&ograve;m 16:2, &ldquo;Ou Se Tout&rdquo; prezante Bondye k&ograve;m sous prensipal lavi a. Mizik la mete aksan sou depandans total ak konfyans nan Bondye k&ograve;m baz relasyon espirity&egrave;l la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon an favorize yon apw&ograve;ch kote mesaj la rete kl&egrave;, pandan li mete an avan enp&ograve;tans Bondye nan tout asp&egrave; lavi moun.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏻 https://youtu.be/pdSXEZ7K9oM?si=l_MiNrXchvq6_Rjf</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Palmar&egrave;s mizik evanjelik avril 2026 la montre yon sekt&egrave; ki ap kontinye evolye nan fason li kominike mesaj li yo. Pwodiksyon yo demontre yon volonte pou mete aksan sou Paw&ograve;l Bondye, pandan yo adapte li ak reyalite lavi chak jou.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chak mizik reprezante yon kontribisyon nan dinamik sa a, kote atis yo s&egrave;vi ak kreyativite yo pou transm&egrave;t mesaj ki gen enp&ograve;tans pou kominote a.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan yon kont&egrave;ks kote mizik jwe yon w&ograve;l enp&ograve;tan nan lavi espirity&egrave;l anpil moun, palmar&egrave;s sa a rete yon pwen referans pou konprann direksyon ak tandans mizik gospel la pandan pery&ograve;d la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Redaksyon:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Vanauscheca Bouzy</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Konsepsyon Grafik:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Jo-Flung Bouzy &amp; Abdullah Mode</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">#palmar&egrave;smizikvideyo</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#evanjelik</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#avril2026 </span></p>\r\n<p class=\"p1\"><span class=\"s1\"><a href=\"https://www.konektem.net/blog/hashtags/konektem\">#konekte</a>m</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#toutkotenenpotkil&egrave;</span></p>', 0, 0, 0, NULL),
(61, 323, 'Gala Nightingale White Night, soirée d’hommage et de reconnaissance au personnel infirmier haïtien.', '2026-05-14', 'health', 'À l’occasion de la Journée internationale des infirmières, le projet Nightingale White Night, en collaboration avec Lophane Project et DGD, a organisé, ce mardi 12 mai 2026, une soirée de gala à l’Hotel Royal Oasis autour du thème : « Célébrons la grandeur de la profession infirmière dans le monde ».', '<p class=\"p1\"><span class=\"s1\">&Agrave; l&rsquo;occasion de la Journ&eacute;e internationale des infirmi&egrave;res, le projet Nightingale White Night, en collaboration avec Lophane Project et DGD, a organis&eacute;, ce mardi 12 mai 2026, une soir&eacute;e de gala &agrave; l&rsquo;Hotel Royal Oasis autour du th&egrave;me : &laquo; C&eacute;l&eacute;brons la grandeur de la profession infirmi&egrave;re dans le monde &raquo;. Cette initiative visait &agrave; mettre en valeur le travail des infirmi&egrave;res et infirmiers ha&iuml;tiens, encourager le leadership f&eacute;minin dans le domaine de la sant&eacute;, promouvoir l&rsquo;excellence ainsi que la formation professionnelle, tout en mobilisant des ressources pour contribuer au d&eacute;veloppement du secteur sanitaire en Ha&iuml;ti et dans la Cara&iuml;be.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Cette &eacute;dition du Gala Nightingale White Night a r&eacute;uni plusieurs personnalit&eacute;s du secteur m&eacute;dical, des &eacute;tudiants, des d&eacute;l&eacute;gations venues de diff&eacute;rents d&eacute;partements du pays ainsi que des repr&eacute;sentants d&rsquo;institutions sanitaires. La c&eacute;r&eacute;monie s&rsquo;est d&eacute;roul&eacute;e en pr&eacute;sence du ministre de la Sant&eacute; publique et de la Population, monsieur Sinal Bertrand, du directeur g&eacute;n&eacute;ral du MSPP Gabriel Timoth&eacute;e, de membres de la Direction centrale des soins infirmiers ainsi que de plusieurs invit&eacute;s venus soutenir cette initiative d&eacute;di&eacute;e &agrave; l&rsquo;excellence, au leadership et &agrave; l&rsquo;humanisme dans la profession infirmi&egrave;re.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">La soir&eacute;e a d&eacute;but&eacute; avec les propos d&rsquo;ouverture et de mise en contexte prononc&eacute;s par la pr&eacute;sidente du Gala Nightingale White Night, Madame Tayna Vilsaint, salu&eacute;e pour sa vision et son engagement dans la valorisation du personnel infirmier ha&iuml;tien. Plusieurs prestations artistiques ont ensuite rythm&eacute; l&rsquo;&eacute;v&eacute;nement, notamment des performances de danse assur&eacute;es par Adassah Dance et Dance Art Acad&eacute;mie, des prestations de slam avec Slamosophe ainsi qu&rsquo;un d&eacute;fil&eacute; pr&eacute;sent&eacute; au cours de la c&eacute;r&eacute;monie. Monsieur Laurent Lophane a &eacute;galement pris la parole afin de rappeler la port&eacute;e du projet et sa volont&eacute; de faire de Nightingale White Night une r&eacute;f&eacute;rence nationale et carib&eacute;enne pour la reconnaissance du secteur de la sant&eacute;. Des interventions ont aussi &eacute;t&eacute; r&eacute;alis&eacute;es par la PDG de DGD, Madame Djina Guillet Delatour, ainsi que par la directrice des soins infirmiers, Madame Carine R&eacute;veil Jean-Baptiste.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Parmi les moments marquants de cette soir&eacute;e figurait l&rsquo;hommage rendu &agrave; deux femmes au parcours inspirant : Miss Phalone Louis et CLERVILUS Geniflore. Ancienne jeune fille charg&eacute;e de nettoyer un h&ocirc;pital avant de devenir infirmi&egrave;re licenci&eacute;e, Miss Phalone Louis a particuli&egrave;rement &eacute;mu l&rsquo;assistance &agrave; travers son t&eacute;moignage de courage et de pers&eacute;v&eacute;rance. Les deux laur&eacute;ates ont re&ccedil;u des distinctions honorifiques en reconnaissance de leurs parcours et de leurs contributions au secteur infirmier.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Le gala a &eacute;galement b&eacute;n&eacute;fici&eacute; d&rsquo;une ouverture internationale gr&acirc;ce &agrave; la pr&eacute;sence de Rechanka Dorestil et Stevens Vilus, venus sp&eacute;cialement des &Eacute;tats-Unis pour participer &agrave; cette c&eacute;l&eacute;bration. Impressionn&eacute;s par la vision port&eacute;e par le comit&eacute; organisateur, ils ont exprim&eacute; leur volont&eacute; de devenir ambassadeurs du Gala Nightingale White Night dans plusieurs &Eacute;tats am&eacute;ricains afin de promouvoir cette initiative et le savoir-faire infirmier ha&iuml;tien &agrave; l&rsquo;&eacute;chelle internationale.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Le comit&eacute; organisateur, compos&eacute; notamment de Laurent Lophane, Tayna Vilsaint, Djina Guillet Delatour et Daniela Tingu&eacute;, a aussi profit&eacute; de cette &eacute;dition pour saluer la forte mobilisation des d&eacute;l&eacute;gations venues de l&rsquo;Artibonite et du Sud. Selon les responsables, cette participation t&eacute;moigne de l&rsquo;int&eacute;r&ecirc;t grandissant suscit&eacute; par cette initiative nationale et renforce l&rsquo;ambition d&rsquo;&eacute;tendre progressivement le Gala Nightingale White Night dans les dix d&eacute;partements du pays.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&Agrave; travers cette soir&eacute;e riche en &eacute;motions, en distinctions et en t&eacute;moignages inspirants, le Gala Nightingale White Night a confirm&eacute; sa volont&eacute; de promouvoir le m&eacute;rite, l&rsquo;excellence, l&rsquo;unit&eacute; et le leadership du personnel infirmier ha&iuml;tien, tout en contribuant au rayonnement d&rsquo;Ha&iuml;ti sur la sc&egrave;ne nationale et internationale.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">R&eacute;daction ✍️: Ronalson Blanfort </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">#konektem </span></p>\r\n<p class=\"p1\"><span class=\"s1\">#ToutKoteNenp&ograve;tKil&egrave;</span></p>', 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `order_date` timestamp NULL DEFAULT current_timestamp(),
  `payment_date` timestamp NULL DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_id` bigint(10) UNSIGNED NOT NULL,
  `owner` varchar(255) NOT NULL DEFAULT 'admin@konektem.net'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `total_amount`, `order_status`, `payment_status`, `order_date`, `payment_date`, `transaction_id`, `item_name`, `item_id`, `owner`) VALUES
(1, 4, '0.00', 'pending', 'pending', '2025-12-07 23:34:43', NULL, 'ORDER_6935e4e3bc6ac', 'service', 4, 'admin@konektem.net'),
(2, 4, '2.00', 'pending', 'paid', '2025-12-07 23:52:26', '2025-12-07 21:07:08', 'ORDER_6935e90a4219c', 'event', 7, 'admin@konektem.net'),
(3, 4, '2.00', 'pending', 'pending', '2025-12-08 00:04:21', NULL, 'ORDER_6935ebd56854e', 'event', 7, 'admin@konektem.net'),
(4, 5, '2.00', 'pending', 'pending', '2025-12-10 18:58:15', NULL, 'ORDER_6939989776ccf', 'event', 8, 'admin@konektem.net'),
(5, 5, '2.00', 'pending', 'pending', '2025-12-10 20:06:16', NULL, 'ORDER_6939a88831b8f', 'event', 8, 'admin@konektem.net'),
(6, 5, '2.00', 'pending', 'paid', '2025-12-10 20:09:52', '2025-12-10 18:13:30', 'ORDER_6939a96068bc6', 'event', 8, 'admin@konektem.net'),
(7, 6, '2.00', 'pending', 'paid', '2025-12-10 21:15:51', '2025-12-10 18:16:17', 'ORDER_6939b8d70c4c4', 'event', 4, 'admin@konektem.net'),
(8, 6, '0.00', 'pending', 'paid', '2025-12-10 21:19:14', '2025-12-10 18:19:37', 'ORDER_6939b9a21d8a8', 'service', 6, 'admin@konektem.net'),
(9, 5, '4.00', 'pending', 'paid', '2025-12-10 21:24:28', '2025-12-10 18:24:51', 'ORDER_6939badc89cd2', 'event', 7, 'admin@konektem.net'),
(10, 7, '2.00', 'pending', 'paid', '2026-01-31 20:41:08', '2026-01-31 17:41:30', 'ORDER_697e3eb47ad0c', 'event', 11, 'admin@konektem.net'),
(11, 8, '4.00', 'pending', 'paid', '2026-03-05 20:08:37', '2026-03-07 10:12:08', 'ORDER_69a9b895874ab', 'event', 7, 'admin@konektem.net'),
(12, 9, '2.00', 'pending', 'paid', '2026-03-05 20:13:28', '2026-03-05 17:13:49', 'ORDER_69a9b9b86c5eb', 'event', 4, 'admin@konektem.net'),
(13, 9, '2.00', 'pending', 'paid', '2026-03-05 20:16:33', '2026-03-05 17:16:54', 'ORDER_69a9ba7175fd3', 'event', 11, 'admin@konektem.net'),
(14, 10, '2.00', 'pending', 'paid', '2026-03-06 16:23:01', '2026-03-06 13:27:10', 'ORDER_69aad535914b7', 'event', 11, 'admin@konektem.net'),
(15, 8, '2.00', 'pending', 'paid', '2026-03-08 06:16:01', '2026-03-08 03:21:09', 'ORDER_69ace9f119cc3', 'event', 10, 'admin@konektem.net'),
(16, 8, '2.00', 'pending', 'pending', '2026-03-08 06:29:41', NULL, 'ORDER_69aced25672c3', 'event', 11, 'admin@konektem.net');

-- --------------------------------------------------------

--
-- Структура таблицы `partners`
--

CREATE TABLE `partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT 'konktem_partner',
  `image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `partners`
--

INSERT INTO `partners` (`id`, `name`, `image_id`, `created_at`) VALUES
(25, 'Lophane', 113, '2025-11-19 19:39:13'),
(26, 'md', 117, '2025-11-23 13:53:35'),
(27, 'Melomania', 118, '2025-11-23 13:55:29'),
(28, 'shekinah', 119, '2025-11-23 13:56:09');

-- --------------------------------------------------------

--
-- Структура таблицы `PremiumSubscribers`
--

CREATE TABLE `PremiumSubscribers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date_start` date DEFAULT curdate(),
  `date_end` date GENERATED ALWAYS AS (`date_start` + interval 1 week) STORED,
  `cancelled_at` date DEFAULT NULL,
  `subscription_status` enum('active','cancelled','expired') GENERATED ALWAYS AS (case when `cancelled_at` is not null then 'cancelled' when `date_end` < curdate() then 'expired' else 'active' end) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `PremiumSubscribers`
--

INSERT INTO `PremiumSubscribers` (`id`, `user_id`, `date_start`, `cancelled_at`) VALUES
(1, 16, '2026-01-16', NULL),
(2, 41, '2026-01-24', NULL),
(4, 49, '2026-01-24', NULL),
(6, 16, '2026-01-25', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `Services`
--

CREATE TABLE `Services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `serviceImg` bigint(20) UNSIGNED DEFAULT NULL,
  `price` int(10) NOT NULL DEFAULT 0,
  `owner` varchar(255) NOT NULL DEFAULT 'admin@konektem.net',
  `cancellations` int(11) DEFAULT 0,
  `orders` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Services`
--

INSERT INTO `Services` (`id`, `name`, `description`, `serviceImg`, `price`, `owner`, `cancellations`, `orders`) VALUES
(3, 'Maketin Dijital', 'marketing', 145, 0, 'admin@konektem.net', 1, 1),
(4, 'Grafik Dizay', 'graphic design', 149, 0, 'admin@konektem.net', 0, 0),
(5, 'Promosyon', 'promotions', 146, 0, 'admin@konektem.net', 0, 0),
(6, 'Video', 'video shoot', 150, 0, 'admin@konektem.net', 0, 0),
(7, 'Eveneman', 'Events', 121, 0, 'admin@konektem.net', 0, 0),
(8, 'Foto', 'photo shooting', 147, 0, 'admin@konektem.net', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` mediumtext DEFAULT NULL,
  `setting_type` varchar(20) DEFAULT 'text',
  `setting_group` varchar(50) DEFAULT 'general',
  `description` varchar(255) DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `description`, `is_public`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'copyright', 'Tout dwa yo rezève ©Konekte m 2025', 'text', 'footer', 'Tout kote Nenpot kile', 1, 1, '2025-12-05 12:25:53', '2025-12-13 19:25:28'),
(2, 'site_title', 'KONSÈNAN KONEKTE M', 'text', 'footer', 'Konekte m se yon Medya Anliy Ayisyen, ki nan domèn Enfomasyon ak Kominikasyon.', 1, 2, '2025-12-05 12:28:01', '2025-12-08 05:59:23'),
(3, 'phone', '+1 7282143510', 'number', 'contact', 'site telephone number', 1, 0, '2025-12-10 13:25:54', '2025-12-10 13:25:54'),
(4, 'site_email', 'konektemtv@gmail.com', 'email', 'email', '', 1, 0, '2025-12-10 13:27:03', '2025-12-10 13:27:03'),
(5, 'site_description', 'Konekte m se yon Medya Anliy Ayisyen, ki nan domèn Enfomasyon ak Kominikasyon.', 'text', 'footer', 'company purpose and goals', 1, 0, '2025-12-11 23:01:51', '2025-12-13 19:21:14'),
(6, 'instagram', 'https://www.instagram.com/konektem?igsh=MTBsdWQ0dXl4ZXY5Mg==', 'text', 'social', 'instagram link', 1, 0, '2025-12-11 23:03:32', '2025-12-11 23:03:32'),
(7, 'twitter', 'https://x.com/konektemtv?s=21\"', 'text', 'social', 'twitter link', 1, 0, '2025-12-11 23:05:57', '2026-04-15 11:16:35'),
(8, 'facebook', 'https://www.facebook.com/Konektempageofficielle?mibextid=LQQJ4d', 'text', 'social', 'facebook link', 1, 0, '2025-12-11 23:07:45', '2025-12-11 23:07:45'),
(9, 'subscription_invitation', 'Abone ak Bilten nouvel', 'text', 'footer', '', 1, 0, '2025-12-13 19:25:29', '2025-12-13 19:25:29');

-- --------------------------------------------------------

--
-- Структура таблицы `streamAccesskeys`
--

CREATE TABLE `streamAccesskeys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `access_key` varchar(50) DEFAULT NULL,
  `date_start` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_end` timestamp NOT NULL DEFAULT (current_timestamp() + interval 1 month)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `streamAccesskeys`
--

INSERT INTO `streamAccesskeys` (`id`, `access_key`, `date_start`, `date_end`) VALUES
(1, 'konektem_lovestream', '2025-10-18 13:31:56', '2025-11-18 14:31:56'),
(7, 'viewer_65814ccf50b0dd15eaa92ea832ff4979', '2025-10-25 23:39:45', '2025-11-26 00:39:45'),
(9, 'viewer_83394d9d887fd2e86ae627ac87192765', '2025-10-26 00:59:17', '2025-11-26 01:59:17'),
(10, 'viewer_0895e19dfb003927c5762c55073bf081', '2025-10-26 02:28:49', '2025-11-26 03:28:49'),
(12, '9907a01fb64b5f9cf1a7489ce2200c98', '2025-10-26 17:15:13', '2025-11-26 18:15:13'),
(13, 'viewer_192eb46a3b847f549ac1dada04be33f2', '2025-10-27 22:49:58', '2025-11-27 23:49:58'),
(14, 'viewer_3ec6c638c064e999b14e588e5abb5f86', '2025-10-27 23:11:05', '2025-11-28 00:11:05'),
(21, 'viewer_cdac4abf9ff8032865d33a14457a3f5d', '2026-02-20 16:52:04', '2026-03-20 15:52:04'),
(22, 'viewer_9ccef7d96f13edf720090d341ee48884', '2026-02-20 16:52:08', '2026-03-20 15:52:08'),
(23, 'viewer_4d97be5cd21ed23d82d9d53e530ecb6f', '2026-02-20 17:16:04', '2026-03-20 16:16:04'),
(24, 'viewer_13841a5be7cc2aacee39056de7e48586', '2026-03-08 01:18:53', '2026-04-08 00:18:53'),
(25, 'viewer_6faa36f566c62728e44a94a20b9e16d4', '2026-03-08 01:19:31', '2026-04-08 00:19:31'),
(26, 'viewer_a82874038e60e02b3736d37f71a4e601', '2026-03-08 01:21:21', '2026-04-08 00:21:21'),
(27, 'viewer_c19131a743ba079e2444b28de08ee9f0', '2026-03-08 03:08:47', '2026-04-08 02:08:47');

-- --------------------------------------------------------

--
-- Структура таблицы `streamer`
--

CREATE TABLE `streamer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `stream_key_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `streamer`
--

INSERT INTO `streamer` (`id`, `name`, `stream_key_id`) VALUES
(1, 'konektem_admin', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `streamkey`
--

CREATE TABLE `streamkey` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stream_key` varchar(20) DEFAULT NULL,
  `date_start` timestamp NULL DEFAULT current_timestamp(),
  `date_end` timestamp NULL DEFAULT (current_timestamp() + interval 1 month)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `streamkey`
--

INSERT INTO `streamkey` (`id`, `stream_key`, `date_start`, `date_end`) VALUES
(1, 'konektem_lovestream', '2025-10-18 13:31:56', '2025-11-18 14:31:56');

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `ticket_number` varchar(50) NOT NULL,
  `status` enum('active','used','cancelled') DEFAULT 'active',
  `used_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `UserActivityLog`
--

CREATE TABLE `UserActivityLog` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `activity_date` timestamp NULL DEFAULT current_timestamp(),
  `item_name` varchar(50) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `UserActivityLog`
--

INSERT INTO `UserActivityLog` (`id`, `activity_name`, `activity_date`, `item_name`, `item_id`, `user_id`, `username`) VALUES
(1, 'like', '2026-01-28 13:26:20', 'News', 16, 49, 'masekoz'),
(2, 'like', '2025-11-24 12:54:08', 'Music', 24, 42, 'AbdullahMode'),
(3, 'share', '2025-11-28 08:44:46', 'Music', 24, 41, 'Jorbyart'),
(4, 'like', '2025-11-28 08:46:04', 'Music', 24, 41, 'Jorbyart'),
(5, 'share', '2026-01-01 22:31:52', 'Music', 15, 49, 'masekoz'),
(6, 'share', '2026-01-01 22:31:56', 'Music', 14, 49, 'masekoz'),
(7, 'share', '2026-01-01 22:56:03', 'actuality', 16, 49, 'masekoz'),
(8, 'share', '2026-01-01 22:56:21', 'actuality', 18, 49, 'masekoz'),
(9, 'share', '2026-01-01 22:57:21', 'actuality', 5, 49, 'masekoz'),
(10, 'share', '2026-01-01 22:57:52', 'interviews', 10, 49, 'masekoz'),
(11, 'share', '2026-01-01 22:57:55', 'interviews', 9, 49, 'masekoz'),
(12, 'download', '2026-01-01 23:12:19', 'Music', 18, 49, 'masekoz'),
(13, 'like', '2026-01-01 23:12:49', 'Music', 18, 49, 'masekoz'),
(14, 'download', '2026-01-01 23:13:21', 'Music', 17, 49, 'masekoz'),
(15, 'download', '2026-01-01 23:13:24', 'Music', 17, 49, 'masekoz'),
(16, 'like', '2026-01-01 23:13:41', 'Music', 17, 49, 'masekoz'),
(17, 'like', '2026-01-01 23:21:01', 'interview', 9, 49, 'masekoz'),
(18, 'like', '2026-01-01 23:21:03', 'interview', 8, 49, 'masekoz'),
(19, 'share', '2026-01-01 23:25:51', 'Events', 8, 49, 'masekoz'),
(20, 'share', '2026-01-01 23:27:28', 'News', 16, 49, 'masekoz'),
(21, 'like', '2026-01-01 23:28:14', 'News', 15, 49, 'masekoz'),
(22, 'like', '2026-01-01 23:31:27', 'interview', 10, 49, 'masekoz'),
(23, 'like', '2026-01-01 23:31:46', 'interview', 5, 49, 'masekoz'),
(24, 'like', '2026-01-01 23:31:50', 'interview', 4, 49, 'masekoz'),
(25, 'like', '2026-01-01 23:31:54', 'interview', 11, 49, 'masekoz'),
(26, 'share', '2026-01-23 23:35:38', 'News', 18, 49, 'masekoz'),
(27, 'share', '2026-01-23 23:37:40', 'interview', 9, 49, 'masekoz'),
(28, 'share', '2026-01-23 23:37:54', 'Music', 18, 49, 'masekoz'),
(29, 'share', '2026-01-23 23:39:56', 'Music', 17, 49, 'masekoz'),
(30, 'like', '2026-01-28 13:26:20', 'News', 16, 49, 'masekoz'),
(31, 'like', '2025-11-07 18:40:18', 'Music', 5, 36, 'ShellerValmiro'),
(32, 'like', '2025-11-07 18:40:23', 'Music', 18, 36, 'ShellerValmiro'),
(33, 'like', '2025-11-07 18:40:28', 'Music', 19, 36, 'ShellerValmiro'),
(34, 'share', '2025-11-28 00:50:43', 'Music', 22, 36, 'ShellerValmiro'),
(35, 'download', '2025-11-17 21:33:57', 'Music', 19, 37, 'adminkonektemnet'),
(36, 'like', '2025-11-17 21:38:16', 'Music', 19, 37, 'adminkonektemnet'),
(37, 'download', '2025-11-17 21:42:16', 'Music', 19, 37, 'adminkonektemnet'),
(38, 'download', '2025-11-17 21:42:25', 'Music', 19, 37, 'adminkonektemnet'),
(39, 'download', '2025-11-20 11:20:27', 'Music', 18, 37, 'adminkonektemnet'),
(40, 'download', '2025-11-23 23:41:06', 'Music', 18, 37, 'adminkonektemnet'),
(41, 'share', '2025-11-30 20:52:28', 'Music', 20, 37, 'adminkonektemnet'),
(42, 'download', '2025-11-30 20:52:35', 'Music', 20, 37, 'adminkonektemnet'),
(43, 'download', '2025-11-30 20:52:35', 'Music', 20, 37, 'adminkonektemnet'),
(44, 'like', '2025-11-30 20:52:45', 'Music', 20, 37, 'adminkonektemnet'),
(45, 'download', '2025-11-30 20:52:48', 'Music', 20, 37, 'adminkonektemnet'),
(46, 'download', '2025-11-30 20:52:48', 'Music', 20, 37, 'adminkonektemnet'),
(47, 'download', '2025-11-30 20:53:07', 'Music', 21, 37, 'adminkonektemnet'),
(48, 'download', '2025-11-30 20:53:07', 'Music', 21, 37, 'adminkonektemnet'),
(49, 'download', '2025-11-30 22:02:32', 'Music', 24, 37, 'adminkonektemnet'),
(50, 'download', '2025-12-02 15:37:15', 'Music', 24, 37, 'adminkonektemnet'),
(51, 'download', '2025-12-02 15:37:15', 'Music', 24, 37, 'adminkonektemnet'),
(52, 'share', '2025-12-02 15:59:37', 'Music', 24, 37, 'adminkonektemnet'),
(53, 'like', '2025-12-02 15:59:41', 'Music', 24, 37, 'adminkonektemnet'),
(54, 'like', '2025-12-02 15:59:45', 'Music', 23, 37, 'adminkonektemnet'),
(55, 'download', '2025-12-02 15:59:48', 'Music', 23, 37, 'adminkonektemnet'),
(56, 'download', '2025-12-02 15:59:48', 'Music', 23, 37, 'adminkonektemnet'),
(57, 'like', '2025-11-07 18:34:21', 'Music', 16, 31, 'JoFlungBouzy'),
(58, 'like', '2025-11-07 18:34:23', 'Music', 17, 31, 'JoFlungBouzy'),
(59, 'like', '2025-11-07 18:34:25', 'Music', 18, 31, 'JoFlungBouzy'),
(60, 'like', '2025-11-07 18:35:41', 'Music', 19, 31, 'JoFlungBouzy'),
(61, 'download', '2025-11-09 17:34:55', 'Music', 17, 31, 'JoFlungBouzy'),
(62, 'like', '2025-11-23 21:38:37', 'Music', 23, 31, 'JoFlungBouzy'),
(63, 'like', '2025-11-23 21:38:41', 'Music', 22, 31, 'JoFlungBouzy'),
(64, 'like', '2025-11-23 21:38:45', 'Music', 21, 31, 'JoFlungBouzy'),
(65, 'like', '2025-11-23 21:38:46', 'Music', 20, 31, 'JoFlungBouzy'),
(66, 'like', '2025-11-23 21:52:10', 'Music', 24, 31, 'JoFlungBouzy'),
(67, 'share', '2025-11-30 04:16:53', 'Music', 20, 31, 'JoFlungBouzy'),
(68, 'share', '2025-12-03 20:24:12', 'Music', 21, 31, 'JoFlungBouzy'),
(69, 'download', '2025-12-04 14:29:52', 'Music', 24, 31, 'JoFlungBouzy'),
(70, 'download', '2025-12-04 21:35:02', 'Music', 18, 31, 'JoFlungBouzy'),
(71, 'download', '2025-12-04 21:35:27', 'Music', 18, 31, 'JoFlungBouzy'),
(72, 'download', '2025-12-07 13:30:21', 'Music', 18, 31, 'JoFlungBouzy'),
(73, 'share', '2025-12-11 18:29:32', 'Music', 22, 31, 'JoFlungBouzy'),
(74, 'share', '2025-12-19 16:42:55', 'Music', 18, 31, 'JoFlungBouzy'),
(75, 'like', '2026-01-07 03:59:30', 'Music', 35, 31, 'JoFlungBouzy'),
(76, 'like', '2026-01-09 13:47:47', 'Music', 34, 31, 'JoFlungBouzy'),
(77, 'download', '2026-01-20 01:59:26', 'Music', 35, 31, 'JoFlungBouzy'),
(78, 'like', '2026-01-28 19:46:47', 'News', 18, 16, 'zwelakhemaseko02_konektem'),
(79, 'download', '2026-01-31 15:40:56', 'Music', 35, 41, 'Jorbyart'),
(80, 'download', '2026-01-31 15:41:08', 'Music', 18, 41, 'Jorbyart'),
(81, 'like', '2026-02-01 22:34:17', 'Books', 2, 49, 'masekozw'),
(82, 'like', '2026-02-01 22:34:19', 'Books', 1, 49, 'masekozw'),
(83, 'share', '2026-02-01 22:34:24', 'Books', 2, 49, 'masekozw'),
(84, 'download', '2026-02-01 22:34:34', 'Music', 20, 49, 'masekozw'),
(85, 'like', '2026-02-01 23:12:24', 'Books', 2, 41, 'Jorbyart'),
(86, 'share', '2026-02-01 23:12:25', 'Books', 2, 41, 'Jorbyart'),
(87, 'share', '2026-02-01 23:12:29', 'Books', 1, 41, 'Jorbyart'),
(88, 'like', '2026-02-01 23:12:56', 'Books', 1, 41, 'Jorbyart'),
(93, 'download', '2026-02-13 22:34:20', 'music', 32, 49, 'masekozw'),
(94, 'like', '2026-02-13 22:35:40', 'music', 32, 49, 'masekozw'),
(95, 'share', '2026-02-13 22:36:03', 'music', 32, 49, 'masekozw'),
(96, 'like', '2026-02-13 22:36:31', 'music', 24, 49, 'masekozw'),
(97, 'like', '2026-02-13 22:36:39', 'music', 20, 49, 'masekozw'),
(98, 'like', '2026-02-13 22:37:42', 'music', 17, 49, 'masekozw'),
(99, 'like', '2026-02-13 22:38:40', 'interview', 13, 49, 'masekozw'),
(100, 'like', '2026-02-13 22:38:43', 'interview', 11, 49, 'masekozw'),
(101, 'like', '2026-02-13 22:39:38', 'interview', 10, 49, 'masekozw'),
(102, 'share', '2026-02-13 23:38:03', 'music', 17, 49, 'masekozw'),
(103, 'download', '2026-02-13 23:43:57', 'music', 17, 49, 'masekozw'),
(104, 'download', '2026-02-13 23:51:20', 'music', 17, 49, 'masekozw'),
(105, 'download', '2026-02-14 00:05:59', 'music', 22, 49, 'masekozw'),
(106, 'download', '2026-02-14 00:07:56', 'music', 20, 49, 'masekozw'),
(107, 'download', '2026-02-14 01:08:06', 'music', 18, 49, 'masekozw'),
(108, 'like', '2026-02-14 01:08:31', 'music', 18, 49, 'masekozw'),
(109, 'share', '2026-02-14 17:24:45', 'news', 18, NULL, 'admin@konektem.net'),
(110, 'like', '2026-02-15 10:48:53', 'music', 18, 48, 'zwelakheMaseko'),
(111, 'download', '2026-02-15 10:49:43', 'music', 18, 48, 'zwelakheMaseko'),
(112, 'download', '2026-02-16 15:04:53', 'music', 17, 49, 'masekozw'),
(113, 'like', '2026-02-16 17:13:44', 'music', 39, 49, 'masekozw'),
(114, 'like', '2026-02-17 04:42:10', 'music', 40, NULL, 'admin@konektem.net'),
(115, 'like', '2026-02-17 14:29:01', 'music', 44, 60, 'MINISTREVALEURDELHOMMETV'),
(116, 'like', '2026-02-19 04:34:44', 'music', 43, NULL, 'admin@konektem.net'),
(117, 'like', '2026-02-19 04:34:46', 'music', 17, NULL, 'admin@konektem.net'),
(118, 'like', '2026-02-19 04:34:48', 'music', 22, NULL, 'admin@konektem.net'),
(119, 'like', '2026-02-19 16:28:27', 'music', 43, 49, 'masekozw'),
(120, 'like', '2026-02-19 16:32:45', 'music', 22, 49, 'masekozw'),
(121, 'share', '2026-02-22 21:08:16', 'music', 17, 31, 'JoFlungBouzy'),
(122, 'share', '2026-03-01 16:43:14', 'news', 18, 49, 'masekozw'),
(123, 'share', '2026-03-01 17:52:26', 'news', 15, 49, 'masekozw'),
(124, 'share', '2026-03-01 17:55:27', 'news', 18, 49, 'masekozw'),
(125, 'share', '2026-03-01 17:55:38', 'news', 18, 49, 'masekozw'),
(126, 'share', '2026-03-01 17:57:57', 'news', 18, 49, 'masekozw'),
(127, 'share', '2026-03-01 17:58:11', 'news', 16, 49, 'masekozw'),
(128, 'share', '2026-03-01 17:58:23', 'interview', 6, 49, 'masekozw'),
(129, 'share', '2026-03-01 17:58:57', 'interview', 7, 49, 'masekozw'),
(130, 'share', '2026-03-01 18:01:13', 'news', 18, 49, 'masekozw'),
(131, 'share', '2026-03-01 18:01:23', 'news', 16, 49, 'masekozw'),
(132, 'share', '2026-03-01 18:02:36', 'news', 18, 49, 'masekozw'),
(133, 'share', '2026-03-03 18:42:11', 'events', 4, 49, 'masekozw'),
(134, 'share', '2026-03-04 22:55:13', 'music', 22, 49, 'masekozw'),
(135, 'download', '2026-03-21 21:23:34', 'music', 20, 37, 'adminkonektemnet'),
(136, 'download', '2026-03-21 21:26:42', 'music', 20, 49, 'masekozw'),
(137, 'download', '2026-04-04 17:20:31', 'music', 18, 37, 'adminkonektemnet'),
(138, 'download', '2026-04-05 07:05:29', 'music', 18, 49, 'masekozw'),
(139, 'download', '2026-04-05 16:07:38', 'music', 18, 49, 'masekozw'),
(140, 'download', '2026-04-05 17:02:00', 'music', 43, 49, 'masekozw'),
(141, 'download', '2026-04-05 17:02:09', 'music', 43, 49, 'masekozw'),
(142, 'download', '2026-04-05 17:02:13', 'music', 17, 49, 'masekozw');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `name` varchar(100) NOT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `id` bigint(20) UNSIGNED NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar_url` text DEFAULT NULL,
  `role` enum('admin','guest') DEFAULT 'guest'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`name`, `last_name`, `email`, `password_hash`, `created_at`, `id`, `google_id`, `avatar_url`, `role`) VALUES
('', NULL, '', '$2y$10$3qtJen4bBP4i9KAlMsEEs.HNDNquND3cceB2Smh6kNxJposze0Ony', '2025-10-15 21:35:03', 1, NULL, NULL, 'guest'),
('konektem_admin', NULL, NULL, '$2y$10$2trJqpsC0u1peyBN1e2fQeLWeuyxvv8Vll0/faNPcZYLqieJqnbKu', '2025-10-18 13:31:56', 2, NULL, NULL, 'guest'),
('zwelakhemaseko02_konektem', NULL, 'zwelakhemaseko02@gmail.com', '$2y$10$8op1duK41TWI3be.tGXuJuTRqCeo95yH5ru5jTXKso3x0TPwope8.', '2025-10-26 00:59:17', 16, '116742316388041503225', '/admin/uploads/images/695642039c096.jpg', 'guest'),
('JoFlungBouzy', NULL, 'joflungbouzy@gmail.com', NULL, '2025-11-07 18:32:47', 31, '116883229385212460809', '/admin/uploads/images/695dd81861bd0.jpeg', 'guest'),
('ShellerValmiro', NULL, 'shellervalmiro2@gmail.com', NULL, '2025-11-15 21:43:50', 36, '113617597052192062007', NULL, 'guest'),
('adminkonektemnet', NULL, 'zweklakhe.mzwet@gmail.com', '$2y$10$Z/Ascy707U2mX2qsagl2vubd4OoWyJgiwmPGQ4TRsXGZ6Skaha8Jy', '2025-11-17 21:16:21', 37, '116636382122397190488', NULL, 'guest'),
('adminkonektem', NULL, 'zweklakhe.zwet@gmail.com', '$2y$10$lOKLZZvh7Y8jBWo2CTg9pejyweqaeRou2ABK/GZIpqrflj9txGd8q', '2025-11-17 21:17:11', 38, NULL, NULL, 'guest'),
('wewe234rtqwe4', NULL, 'konektemtv@gmail.com', '$2y$10$mpa8tDtNAAiO6jc3pUy2JuGiAWGPgMOcGwsd41fCn0OXtWFsbMDJ6', '2025-11-23 12:48:08', 39, '114354100468045266595', NULL, 'guest'),
('iyutlyyl86rl6', NULL, 'tiet7kk685k6', '$2y$10$fEJjDQJnYVZGIk7PMwRtNOKvM9PiBvNWQs4Cc.RPpX1aJ4mh19ga6', '2025-11-23 12:54:29', 40, NULL, NULL, 'guest'),
('Jorbyart', NULL, 'jeanjoberne@gmail.com', '$2y$10$daER7cZW7XbZu3mZ4cQG8O1/PUF//m/BJCNw.xHLfEVc2SXFd1sGO', '2025-11-23 14:45:06', 41, '110523920880736279190', '/admin/uploads/images/695521ba6a0f2.jpeg', 'guest'),
('AbdullahMode', NULL, 'modeabdullah451@gmail.com', NULL, '2025-11-24 12:53:50', 42, '117623405616246842645', NULL, 'guest'),
('zwelakheMaseko', NULL, 'zwelakhe.mzwet@gmail.com', NULL, '2025-12-20 18:49:50', 48, '114183917938627615118', NULL, 'guest'),
('masekozw', NULL, 'zweeklakhe.mzwet@gmail.com', '$2y$10$Y2oWnd1m.IRcyzBtEDHSiOZIqu4DCYRrVnTtATBWs0pPOAO0Bjhku', '2026-01-01 22:30:29', 49, NULL, '/admin/uploads/images/6957e3f66d235.jpg', 'guest'),
('Samantha', NULL, 'samylus99@gmail.com', NULL, '2026-02-04 18:26:54', 50, '114032295297367937911', NULL, 'guest'),
('EmmanuelPIERRE', NULL, 'emmanuelpierre457@gmail.com', NULL, '2026-02-05 03:02:15', 51, '108345803225240889895', NULL, 'guest'),
('DannyCrood', NULL, 'dannycrood500@gmail.com', NULL, '2026-02-08 16:42:16', 58, '102914263626477579370', NULL, 'guest'),
('Ninas', NULL, 'nina.hairstyling.biz@gmail.com', NULL, '2026-02-16 02:52:18', 59, '116566416097621233324', NULL, 'guest'),
('MINISTREVALEURDELHOMMETV', NULL, 'saintilienrobenson@gmail.com', NULL, '2026-02-17 14:11:12', 60, '107022167591730889902', NULL, 'guest'),
('SadwineloizStsurin', NULL, 'stsurinsadwineloiz8@gmail.com', NULL, '2026-02-22 21:00:46', 61, '105786544755482724405', '/admin/uploads/images/699b6f0230f6a.jpeg', 'guest'),
('DjouneJeanlouis', NULL, 'djounejeanlouis98@gmail.com', NULL, '2026-02-23 20:33:52', 62, '118146905329931816573', NULL, 'guest'),
('Jorby', NULL, 'jjorby00@gmail.com', '$2y$10$7wKOHXq3sKTUDULlujLBTOsXU/yKPYKM6AqJ0ked7.LP.9Z8RPrLO', '2026-02-27 15:47:40', 63, '113161503760011568798', NULL, 'guest'),
('PunisherJustice', NULL, 'punisherjustice365@gmail.com', NULL, '2026-03-11 01:09:31', 64, '105923298706225502345', NULL, 'guest'),
('WahyuniDesi', NULL, 'wahyunjesi@gmail.com', NULL, '2026-03-22 16:06:42', 65, '101492296243979735887', NULL, 'guest'),
('LucksonPetitfrre', NULL, 'lucksonpetitfrere0@gmail.com', NULL, '2026-03-29 08:23:25', 66, '102616222610279153509', NULL, 'guest'),
('AndersonDestrat', NULL, 'destrataderson@gmail.com', NULL, '2026-04-02 11:08:04', 67, '103201146793363208020', NULL, 'guest'),
('DanielGustin', NULL, 'gustindaniel17@gmail.com', NULL, '2026-04-02 22:09:20', 68, '103907384976439397500', NULL, 'guest'),
('FrLucksonZnPaFMoun', NULL, 'lucksonjeanzonpafemoun@gmail.com', NULL, '2026-04-07 14:39:42', 69, '111520476111987550387', NULL, 'guest'),
('Vava', NULL, 'p.vanauscheca@gmail.com', '$2y$10$J5wsRX.Jidq9Ts6Sn7tja.Eh6y2UdWNCJiE2kuRwEBJedX11oRzKK', '2026-04-07 15:06:52', 70, NULL, NULL, 'guest'),
('LowensLeger', NULL, 'lbk428polivayan@gmail.com', NULL, '2026-04-09 06:57:25', 71, '108854654484041604587', NULL, 'guest'),
('JrichoPierrenol', NULL, 'pierrenoeljericho@gmail.com', NULL, '2026-04-11 18:47:59', 72, '114898504294289027664', NULL, 'guest');

-- --------------------------------------------------------

--
-- Структура таблицы `Videos`
--

CREATE TABLE `Videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vidImg` bigint(20) UNSIGNED DEFAULT NULL,
  `vidTitle` mediumtext DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `downloads` int(11) DEFAULT 0,
  `plays` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `Videos`
--

INSERT INTO `Videos` (`id`, `vidImg`, `vidTitle`, `location`, `mime_type`, `likes`, `downloads`, `plays`) VALUES
(1, 6, 'Eminem - Inner War (feat. Joyner Lucas) [Music Video 2025]', '/media/../media/videos/vid_68a366f43c0048.92364707_po3.mp4', 'video/mp4', 0, 0, 0),
(2, 7, 'Tyga x Lil Wayne - Pop It Off [Official Video]', '/media/../media/videos/vid_68a3672715ad26.69245638_po2.mp4', 'video/mp4', 0, 0, 0),
(12, 201, 'Test', '/admin/controllers/../uploads/videos/695dcaf630ee9.mp4', 'video/mp4', 0, 0, 0),
(13, 163, 'Melomania Group : \"A Night of Worship with Sinach\"', '/admin/controllers/../uploads/videos/6962b97c4643d.mp4', 'video/mp4', 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `viewers`
--

CREATE TABLE `viewers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `access_key_id` bigint(20) UNSIGNED NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `viewers`
--

INSERT INTO `viewers` (`id`, `name`, `access_key_id`, `password_hash`) VALUES
(1, 'konektem_admin', 1, NULL),
(12, 'jjorby00_68fe1_konektem', 12, NULL),
(14, 'jjorby00_faf92f_konektem', 14, NULL),
(16, '_93daa8_konektem', 21, '$2y$10$35pThK8Wam0rM2ATW46I/.229y20qIx/8ELzaa49THPuTRRwsVzsS'),
(17, 'zwelakhemaseko02_99891d_konektem', 22, '$2y$10$h2Hzn4AopVwjllLxhQVEie/bbnn76VwXtRMCOU0bIml2KID6rAR9.'),
(18, '_9980f3_konektem', 23, '$2y$10$oP4rPl4kqpfky7rzGmTac.WVOgGGxh4lYZwIR.M3WddZ809u1M23G'),
(19, '_9ccd4a_konektem', 24, '$2y$10$pOkK.ESD0kcUaP2XF8FB8.sCHiQsS24FlLYfwAtYphHuMM.xKm2Jq'),
(20, '_69c93f_konektem', 25, '$2y$10$5zC.BFZfsww8dNaQoVGKPugKEQNEWCQ1cHTONgk0JF46n1SGQS7XC'),
(21, '_9ac0a3_konektem', 26, '$2y$10$3afZBAa/sWLFatGE5aYVbOCgouTiYNPDNMIiUX9/lbzODTdUmO5/a'),
(22, '_6ac925_konektem', 27, '$2y$10$JEboca.u86gGBWC5u1V9GectFH18xAhddx.A0sUt17O/Oexv.ETwe');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Индексы таблицы `Artists`
--
ALTER TABLE `Artists`
  ADD PRIMARY KEY (`name`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- Индексы таблицы `document_categories`
--
ALTER TABLE `document_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `document_category_relations`
--
ALTER TABLE `document_category_relations`
  ADD PRIMARY KEY (`document_id`,`category_id`),
  ADD KEY `fk_dcr_category` (`category_id`);

--
-- Индексы таблицы `document_images`
--
ALTER TABLE `document_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `document_image_unique` (`document_id`,`image_id`),
  ADD KEY `fk_document_images_document` (`document_id`),
  ADD KEY `fk_document_images_image` (`image_id`);

--
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_events_images` (`eventImage`);

--
-- Индексы таблицы `example`
--
ALTER TABLE `example`
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `Images`
--
ALTER TABLE `Images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `unq_images` (`location`);

--
-- Индексы таблицы `interview`
--
ALTER TABLE `interview`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image_id` (`image_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Индексы таблицы `livestream`
--
ALTER TABLE `livestream`
  ADD PRIMARY KEY (`id`),
  ADD KEY `connected` (`connected`);

--
-- Индексы таблицы `mainpagecontent`
--
ALTER TABLE `mainpagecontent`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `mainpagecontent_ibfk_1` (`music`),
  ADD KEY `mainpagecontent_ibfk_2` (`newsSlide`),
  ADD KEY `mainpagecontent_ibfk_3` (`fadeNews`);

--
-- Индексы таблицы `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`id`,`track_name`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_music_images` (`track_img_id`),
  ADD KEY `idx_music_album_id` (`album_id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_news_images` (`newsImg`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Индексы таблицы `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image_id` (`image_id`);

--
-- Индексы таблицы `PremiumSubscribers`
--
ALTER TABLE `PremiumSubscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `Services`
--
ALTER TABLE `Services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_key` (`setting_key`),
  ADD KEY `idx_group` (`setting_group`),
  ADD KEY `idx_public` (`is_public`);

--
-- Индексы таблицы `streamAccesskeys`
--
ALTER TABLE `streamAccesskeys`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `streamer`
--
ALTER TABLE `streamer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `streamkey`
--
ALTER TABLE `streamkey`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_number` (`ticket_number`),
  ADD KEY `order_id` (`order_id`);

--
-- Индексы таблицы `UserActivityLog`
--
ALTER TABLE `UserActivityLog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`,`name`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `password_hash` (`password_hash`,`email`);

--
-- Индексы таблицы `Videos`
--
ALTER TABLE `Videos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_videos_images` (`vidImg`);

--
-- Индексы таблицы `viewers`
--
ALTER TABLE `viewers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `password_hash` (`password_hash`),
  ADD KEY `fk_viewers_streamAccessKeys` (`access_key_id`),
  ADD KEY `viewers_ibfk_1` (`name`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `albums`
--
ALTER TABLE `albums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Artists`
--
ALTER TABLE `Artists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `document_categories`
--
ALTER TABLE `document_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `document_images`
--
ALTER TABLE `document_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `example`
--
ALTER TABLE `example`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Images`
--
ALTER TABLE `Images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=324;

--
-- AUTO_INCREMENT для таблицы `interview`
--
ALTER TABLE `interview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `livestream`
--
ALTER TABLE `livestream`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `mainpagecontent`
--
ALTER TABLE `mainpagecontent`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT для таблицы `music`
--
ALTER TABLE `music`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `PremiumSubscribers`
--
ALTER TABLE `PremiumSubscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `Services`
--
ALTER TABLE `Services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `streamAccesskeys`
--
ALTER TABLE `streamAccesskeys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `streamer`
--
ALTER TABLE `streamer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `streamkey`
--
ALTER TABLE `streamkey`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `UserActivityLog`
--
ALTER TABLE `UserActivityLog`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT для таблицы `Videos`
--
ALTER TABLE `Videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `viewers`
--
ALTER TABLE `viewers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `api_keys_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `document_category_relations`
--
ALTER TABLE `document_category_relations`
  ADD CONSTRAINT `fk_dcr_category` FOREIGN KEY (`category_id`) REFERENCES `document_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dcr_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `document_images`
--
ALTER TABLE `document_images`
  ADD CONSTRAINT `fk_document_images_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_document_images_image` FOREIGN KEY (`image_id`) REFERENCES `Images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_images` FOREIGN KEY (`eventImage`) REFERENCES `Images` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `livestream`
--
ALTER TABLE `livestream`
  ADD CONSTRAINT `livestream_ibfk_1` FOREIGN KEY (`connected`) REFERENCES `viewers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `music`
--
ALTER TABLE `music`
  ADD CONSTRAINT `music_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `partners`
--
ALTER TABLE `partners`
  ADD CONSTRAINT `partners_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `Images` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `partners_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `Images` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `PremiumSubscribers`
--
ALTER TABLE `PremiumSubscribers`
  ADD CONSTRAINT `PremiumSubscribers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `UserActivityLog`
--
ALTER TABLE `UserActivityLog`
  ADD CONSTRAINT `UserActivityLog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `viewers`
--
ALTER TABLE `viewers`
  ADD CONSTRAINT `fk_viewers_streamAccessKeys` FOREIGN KEY (`access_key_id`) REFERENCES `streamAccesskeys` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
