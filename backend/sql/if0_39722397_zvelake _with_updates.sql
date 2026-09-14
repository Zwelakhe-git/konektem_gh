-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Июн 28 2026 г., 12:46
-- Версия сервера: 8.0.45-0ubuntu0.24.04.1
-- Версия PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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

--
-- Структура таблицы `albums`
--

CREATE TABLE `albums` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `songs_count` int DEFAULT NULL,
  `release_year` date NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `downloads` int DEFAULT '0',
  `likes` int DEFAULT '0',
  `shares` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `albums`
--

INSERT INTO `albums` (`id`, `name`, `image_url`, `songs_count`, `release_year`, `owner`, `user_id`, `description`, `downloads`, `likes`, `shares`) VALUES
(11, 'my album', '/konektem/config/../uploads/zvelake_album_myalbum/69d2965326642.png', 1, '2026-04-05', 'zvelake', 49, 'album', 2, 1, 1),
(14, 'Vwa Yo Pa Tande Yo', '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45879c.jpg', 4, '2026-04-07', 'masekozw', 49, '« Vwa Yo Pa Tande Yo » constitue la troisième production musicale de la Fondation Frère\r\nLuckson Zòn Pa Fò Moun.\r\nL\'album comprend douze titres originaux, interprétés par des enfants et adolescents issus de milieux vulnérables :\r\nJob, Diaman, Delson, Jonaider, Ketty, Fritzline, Lovemika, Ti Pasté, Ferlando, Ti Batè, BenBen et\r\nBòs Tayè.\r\n\r\nLeurs voix expriment la résilience, la foi et l\'espérance d\'une génération qui refuse de se résigner.', 48, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `api_keys`
--

CREATE TABLE `api_keys` (
  `id` bigint UNSIGNED NOT NULL,
  `api_key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `key_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_end` timestamp GENERATED ALWAYS AS ((`created_at` + interval 1 month)) STORED NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `status` enum('expired','active','cancelled') COLLATE utf8mb4_general_ci GENERATED ALWAYS AS ((case when (`date_end` < TIMESTAMP'2026-04-12 11:07:13') then _utf8mb4'expired' when (`cancelled_at` is not null) then _utf8mb4'cancelled' else _utf8mb4'active' end)) VIRTUAL,
  `owner_id` bigint UNSIGNED NOT NULL,
  `permissions` set('read','write','delete','insert','update','create') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `artists`
--

CREATE TABLE `artists` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `artists`
--

INSERT INTO `artists` (`id`, `name`) VALUES
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
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `pdfUrl` varchar(255) DEFAULT NULL,
  `owner` varchar(100) DEFAULT 'admin@konektem.net',
  `cover_image` bigint UNSIGNED DEFAULT NULL,
  `linked_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `likes` int DEFAULT '0',
  `shares` int DEFAULT '0',
  `views` int DEFAULT '0',
  `description` text,
  `genre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `release_date`, `isbn`, `pdfUrl`, `owner`, `cover_image`, `linked_images`, `likes`, `shares`, `views`, `description`, `genre`) VALUES
(1, 'MES SECRETS POUR UNE VIE RICHE ET EPANOUIE', 'SONY LAMARRE JOSEPH', NULL, NULL, NULL, 'admin@konektem.net', 241, NULL, 2, 1, 0, '<p>r&eacute;dig&eacute; pour devenir un ouvrage de r&eacute;f&eacute;rence pour toutes celles et ceux qui aspirent au bien-&ecirc;tre par la mise en valeur de leurs dons et de leurs talents mais qui sont sceptiques quant &agrave; la mat&eacute;rialisation de leurs r&ecirc;ves. L\'auteur a pu se frayer des voies comme tant de personnes ayant des histoires aussi touchantes et inspirantes que la sienne. Il est aussi possible pour beaucoup d&rsquo;autres. Il suffit de savoir ce qu&rsquo;on veut r&eacute;aliser vraiment et faire montre de volont&eacute; et de d&eacute;termination pour y parvenir.</p>', 'fantasy'),
(2, 'hnh', 'hhg', NULL, NULL, NULL, 'admin@konektem.net', 248, NULL, 2, 2, 0, '<p>gfhkjg</p>', 'Science'),
(4, 'C Notes for Professionals', 'StackOverflow Community', NULL, NULL, NULL, 'admin@konektem.net', 275, NULL, 0, 0, 0, '<p>The&nbsp;<em>C Notes for Professionals</em> book is compiled from &nbsp;Stack Overflow Documentation, the content is written by the beautiful people at Stack Overflow.</p>', 'Science'),
(5, 'ReactNative Notes for Professionals', 'StackOverflow Community', NULL, NULL, NULL, 'admin@konektem.net', 276, NULL, 0, 0, 0, '<p>The&nbsp;<em>React Native Notes for Professionals</em> book is compiled from StackOverflow documentation, the content is written by the beautiful people at Stack Overflow.</p>', 'Science'),
(6, 'Redis in Action', 'Josiah L. Carlson', NULL, NULL, '/admin/controllers/../uploads/documents/69aaff323c85f.pdf', 'admin@konektem.net', 277, NULL, 0, 0, 0, '<p>This book covers the use of Redis, an in-memory database/data structure server, originally written by Salvatore Sanfilippo, but recently patched through the open source<br>process</p>', 'Science'),
(8, 'The Mark Of Athena', 'Rick Riodan', NULL, NULL, '/admin/config/../../admin/uploads/documents/69ab1a45ec81c.pdf', 'admin@konektem.net', 279, NULL, 0, 0, 0, '<p>The Heroes of Olympus, Book Three</p>', 'Fiction');

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id` bigint NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
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
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` longtext,
  `content_json` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `document_categories`
--

CREATE TABLE `document_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `document_category_relations`
--

CREATE TABLE `document_category_relations` (
  `document_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `document_images`
--

CREATE TABLE `document_images` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` bigint UNSIGNED NOT NULL,
  `image_id` bigint UNSIGNED NOT NULL,
  `order_no` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE `events` (
  `id` bigint NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `event_date` date DEFAULT NULL,
  `location` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` int DEFAULT NULL,
  `image_id` bigint UNSIGNED DEFAULT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin@konektem.net',
  `likes` int DEFAULT '0',
  `shares` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `location`, `price`, `image_id`, `owner`, `likes`, `shares`) VALUES
(4, 'DETANT KRETYEN', 'Randevou chak vandredi nan Dèlma 75 pou w koze ak atis prefere w yo, tande istwa la vi yo, motivasyon ak pasyon yo.', '2025-12-24', 'DELMAS 75', 0, 37, 'admin@konektem.net', 2, 5),
(7, 'STAND UP FOR LYNDA JOSEPH', 'Dans un élan de solidarité qui dépasse les murs du sanctuaire, l’Église Rendez-Vous Christ, en collaboration avec Media Levanjil, Konektem, À l’Unisson et Lophane Project, convie le public à un concert exceptionnel le 14 décembre 2025. Proposé au prix symbolique de 1 000 gourdes, cet événement vise à soutenir Lynda Joseph à travers une soirée où musique, foi et compassion s’uniront pour porter un message d’espérance.\r\nLes participants seront également invités à renforcer cet acte de générosité par des dons additionnels, afin de contribuer encore davantage à cette noble cause.', '2025-12-14', 'Rendez-Vous Christ', 2, 128, 'admin@konektem.net', 1, 1),
(8, 'JEZI OOO - NESLIN DESTILHOMME GONAIVES', '<p>Nan vil Gonayiv, yon gwo randevou adorasyon tanmen pare pou make fen ane a. Jou k ap samdi 6 desanm 2025 lan, a 4ï¿½ nan lapremidi, nan Pak Vincent. @neslindestilhomme ap tann nou tout nan yon bouyon adorasyon, ki se Konsï¿½ ï¿½ï¿½Jezi Ohï¿½ï¿½ a. Yon Aktivite ki rasanble plizyï¿½ atis ak gwoup kretyen, tankou Rod Ume Dieujuste, @joyclerfderisier, @loutchina_music, Zï¿½n Pa Fï¿½ Moun Band ak Wency Le Slameur. Caribbean Worshippers envite piblik la, jï¿½n kou granmoun, fanmi kou zanmi, pou pa rate randevou sa a. Si w ap chï¿½che yon kote pou w rekonekte ak Bondye, renouvle lafwa w epi amize w nan prezans Bondye, Konsï¿½ ï¿½ï¿½Jezi Ohï¿½ï¿½, nan Gonayiv se kote w dwe ye a</p>', '2025-12-06', 'GONAIVES', 0, 131, 'admin@konektem.net', 0, 1),
(9, 'SIMIANE RUACH TOUR', 'SIMIANE Ruach Tour. live performance: Jean Jean, Esther Desi, Transfomasyon Band.\r\n\r\n \r\nRéservez Vos billets maintenant sur eventbrite 👇👇\r\nhttps://www.eventbrite.com/e/1966354383895?aff=oddtdtcreator', '2025-12-07', 'NEW JERUSALEM EVANGELICAL BAPTIST CHURCH', 0, 134, 'admin@konektem.net', 0, 0),
(10, '6e Ã©dtion Caribbean Worshippers', '<div class=\"xdj266r x14z9mp xat24cr x1lziwak x1vvkbs x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t1f/2/16/27a1.png\" alt=\"âž¡ï¸\" width=\"16\" height=\"16\"></span> Ce grand rendez-vous spirituel, organis&eacute; par le psalmiste Neslin Destilhomme, connu pour mobiliser des milliers de fid&egrave;les &agrave; chacune de ses initiatives, r&eacute;unira cette ann&eacute;e des adorateurs et des leaders chr&eacute;tiens venus d&rsquo;Ha&iuml;ti, des &Eacute;tats-Unis et du Canada.<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">Cette rencontre spirituelle vise &agrave; cr&eacute;er un espace de louange et de pri&egrave;re favorisant une connexion profonde &agrave; la pr&eacute;sence de Dieu et la croissance spirituelle des participants. La 6áµ‰ &eacute;dition, organis&eacute;e autour du th&egrave;me &laquo; BENI BENI N&Egrave;T &raquo;, annonce une programmation de haut niveau, selon les pr&eacute;cisions de <span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/profile.php?id=100063886113458&amp;__cft__[0]=AZZ9Mi5EY7jV6JhwKdvRWBCP7k2OfAsaKoJMZcqwH5LmR8zYphbZSwYw3yXYdRNrxPxDrQt3-dSNdrUfYKjnASY-XamECrDNSh6UH7EHGW4MooM1fsHkqRyHSbZhQTEOTSKqiKClzeD8QfwO6oVpcVikCRbwuptfY6s5XI932MDEQcTLB_-wgMGaI2GEfWE885A&amp;__tn__=-]K-R\"><span class=\"xt0psk2\"><span class=\"xjp7ctv\">Neslin Destilhomme</span></span></a></span></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">L&rsquo;&eacute;v&eacute;nement rassemblera un panel d&rsquo;adorateurs et de leaders spirituels engag&eacute;s &agrave; conduire le peuple dans un moment intense de communion et d&rsquo;&eacute;dification spirituelle.<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">KONEKTEM s&rsquo;engage &agrave; vous informer de chaque &eacute;tape et de tous les d&eacute;tails de ce grand rendez-vous spirituel. Restez connect&eacute;s.<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t4b/2/16/1f4cc.png\" alt=\"ðŸ“Œ\" width=\"16\" height=\"16\"></span> Informations et dons</div>\r\n<div dir=\"auto\">Zelle : 954 336 9155</div>\r\n<div dir=\"auto\">PayPal : <a href=\"mailto:neslindestilhome87@gmail.com\">neslindestilhome87@gmail.com</a><br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t63/2/16/270d.png\" alt=\"âœï¸\" width=\"16\" height=\"16\"></span> R&eacute;daction : Vanauscheca P. Bouzy<br><br></div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/hashtag/konektem?__eep__=6&amp;__cft__[0]=AZZ9Mi5EY7jV6JhwKdvRWBCP7k2OfAsaKoJMZcqwH5LmR8zYphbZSwYw3yXYdRNrxPxDrQt3-dSNdrUfYKjnASY-XamECrDNSh6UH7EHGW4MooM1fsHkqRyHSbZhQTEOTSKqiKClzeD8QfwO6oVpcVikCRbwuptfY6s5XI932MDEQcTLB_-wgMGaI2GEfWE885A&amp;__tn__=*NK-R\">#konektem</a></span></div>\r\n<div dir=\"auto\"><span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/hashtag/toutkotenenp%C3%B2tkil%C3%A8?__eep__=6&amp;__cft__[0]=AZZ9Mi5EY7jV6JhwKdvRWBCP7k2OfAsaKoJMZcqwH5LmR8zYphbZSwYw3yXYdRNrxPxDrQt3-dSNdrUfYKjnASY-XamECrDNSh6UH7EHGW4MooM1fsHkqRyHSbZhQTEOTSKqiKClzeD8QfwO6oVpcVikCRbwuptfY6s5XI932MDEQcTLB_-wgMGaI2GEfWE885A&amp;__tn__=*NK-R\">#toutkotenenp&ograve;tkil&egrave;</a></span></div>\r\n</div>', '2026-03-14', 'Nassau, Bahamas', 0, 208, 'admin@konektem.net', 0, 0),
(12, 'Samedi de la GrÃ¢ce', '<div class=\"xdj266r x14z9mp xat24cr x1lziwak x1vvkbs x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t1f/2/16/27a1.png\" alt=\"âž¡ï¸\" width=\"16\" height=\"16\"></span>D&eacute;j&agrave; tr&egrave;s attendu, ce rendez-vous figure parmi les &eacute;v&eacute;nements majeurs du mois de f&eacute;vrier au sein de la communaut&eacute; &eacute;vang&eacute;lique ha&iuml;tienne.</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">Cette deuxi&egrave;me &eacute;dition entend poursuivre les m&ecirc;mes objectifs : valoriser les talents artistiques chr&eacute;tiens, encourager l&rsquo;expression culturelle positive et diffuser un message fort de paix, d&rsquo;unit&eacute; et d&rsquo;esp&eacute;rance, dans un contexte social o&ugrave; les rep&egrave;res demeurent essentiels.</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">Le programme vise &eacute;galement &agrave; mettre en lumi&egrave;re les talents artistiques chr&eacute;tiens, tout en promouvant un message de paix, d&rsquo;unit&eacute; et d&rsquo;esp&eacute;rance, dans un contexte social marqu&eacute; par de nombreux d&eacute;fis.</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">La premi&egrave;re &eacute;dition de &laquo; Samedi de la Gr&acirc;ce &raquo; avait mobilis&eacute; des centaines de milliers de participants, en pr&eacute;sentiel comme en ligne, autour de moments de louange, d&rsquo;adoration et d&rsquo;action de gr&acirc;ce, r&eacute;unissant fid&egrave;les, organisateurs et partenaires dans un m&ecirc;me &eacute;lan spirituel.</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tf0/2/16/1f449.png\" alt=\"ðŸ‘‰\" width=\"16\" height=\"16\"></span> Plus de d&eacute;tails seront communiqu&eacute;s prochainement.</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\">Pour le sponsoring et les partenariats :</div>\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/tec/2/16/1f4de.png\" alt=\"ðŸ“ž\" width=\"16\" height=\"16\"></span> +509 38 48 70 85 | 31 22 33 37 | 37 55 84 26</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs x3nfvp2 x1j61x8r x1fcty0u xdj266r xat24cr xm2jcoa x1mpyi22 xxymvpz xlup9mm x1kky2od\"><img class=\"xz74otr x15mokao x1ga7v0g x16uus16 xbiv7yw\" src=\"https://static.xx.fbcdn.net/images/emoji.php/v9/t63/2/16/270d.png\" alt=\"âœï¸\" width=\"16\" height=\"16\"></span> R&eacute;daction : Vanauscheca P. Bouzy</div>\r\n<div dir=\"auto\">&nbsp;</div>\r\n</div>\r\n<div class=\"x14z9mp xat24cr x1lziwak x1vvkbs xtlvy1s x126k92a\">\r\n<div dir=\"auto\"><span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/hashtag/konektem?__eep__=6&amp;__cft__[0]=AZbqAYqCCQiggpzcnz7g5RIVGuw7Q6O5hXz2j4J3mBSVZ4PXuRX8AQWnWVBxhWqoOK0Z_S35n3i44kDEepy9MXWjEchG9j3eSXog1kqaqCYu6XThaJxMDRTtfVNhOdotV8lMG8ioorGlQLv17bJ6m2ZFTFkJAg1EIfjJlTQ4mMYoewJAF6RsQA1g_D-wdEDLzwI&amp;__tn__=*NK-R\">#Konektem</a></span></div>\r\n<div dir=\"auto\"><span class=\"html-span xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x1hl2dhg x16tdsg8 x1vvkbs\"><a class=\"x1i10hfl xjbqb8w x1ejq31n x18oe1m7 x1sy0etr xstzfhl x972fbf x10w94by x1qhh985 x14e42zd x9f619 x1ypdohk xt0psk2 x3ct3a4 xdj266r x14z9mp xat24cr x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x16tdsg8 x1hl2dhg xggy1nq x1a2a7pz xkrqix3 x1sur9pj x1fey0fg x1s688f\" tabindex=\"0\" role=\"link\" href=\"https://web.facebook.com/hashtag/toutkotenenpotkil%C3%A8?__eep__=6&amp;__cft__[0]=AZbqAYqCCQiggpzcnz7g5RIVGuw7Q6O5hXz2j4J3mBSVZ4PXuRX8AQWnWVBxhWqoOK0Z_S35n3i44kDEepy9MXWjEchG9j3eSXog1kqaqCYu6XThaJxMDRTtfVNhOdotV8lMG8ioorGlQLv17bJ6m2ZFTFkJAg1EIfjJlTQ4mMYoewJAF6RsQA1g_D-wdEDLzwI&amp;__tn__=*NK-R\">#ToutKotenenpotKil&egrave;</a></span></div>\r\n</div>', '2026-02-21', 'Henfrasa, Delmas 33, Port-au-Prince HaÃ¯ti', 0, 212, 'admin@konektem.net', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `example`
--

CREATE TABLE `example` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `id` int NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `images`
--

INSERT INTO `images` (`id`, `url`, `mime_type`) VALUES
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
(208, '/admin/controllers/../uploads/images/696ce3ad2f9b0.jpg', 'image/jpeg'),
(209, '208', NULL),
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
(323, '/admin/controllers/../uploads/images/6a06481f92c8e.jpeg', 'image/jpeg'),
(324, '/var/www/html/konektem/uploads/images/6a380a9508ccc.png', 'image/png'),
(325, '/var/www/html/konektem/uploads/images/6a380bf838f9a.png', 'image/png'),
(326, '/var/www/html/konektem/uploads/images/6a380c25cc5bb.png', 'image/png'),
(327, '/var/www/html/konektem/uploads/images/6a380c55bae7f.png', 'image/png'),
(328, '/konektem/uploads/images/6a380dcdde381.png', 'image/png'),
(329, '/konektem/uploads/images/6a38e01bc46fc.jpg', 'image/jpeg'),
(330, '/konektem/uploads/images/6a38e0b95f02a.jpg', 'image/jpeg'),
(331, '/konektem/uploads/images/6a38e0ce30cf7.jpg', 'image/jpeg'),
(332, '/konektem/uploads/images/6a38e70576a65.jpg', 'image/jpeg'),
(333, '/konektem/uploads/images/6a38ef4d9513b.jpg', 'image/jpeg'),
(338, '/konektem/uploads/admin@konektem.net_album_tythhj//6a395a0bc0e2f.jpg', 'image/jpeg'),
(339, '/konektem/uploads/images/6a395a62e2c5c.jpg', 'image/jpeg'),
(340, '/konektem/uploads/images/6a399f1c63b4b.jpg', 'image/jpeg'),
(341, '/konektem/uploads/admin@konektem.net_album_myalbum//6a399f85769d7.jpg', 'image/jpeg'),
(342, '/konektem/uploads/admin@konektem.net_album_myalbum//6a39a6fa02686.jpg', 'image/jpeg'),
(343, '/konektem/uploads/admin@konektem.net_album_myalbum//6a39a6fcdeb5c.jpg', 'image/jpeg'),
(344, '/konektem/uploads/admin@konektem.net_album_myalbum//6a39a700ebfcd.jpg', 'image/jpeg'),
(345, '/konektem/uploads/admin@konektem.net_album_myalbum//6a39a88563b2d.jpg', 'image/jpeg'),
(346, '/konektem/uploads/admin@konektem.net_album_myalbum//6a39a8d073604.jpg', 'image/jpeg'),
(347, '/konektem/uploads/admin@konektem.net_album_myalbum//6a39aa0c58672.jpg', 'image/jpeg'),
(348, '/konektem/uploads/images/6a39b3ae1c71d.jpg', 'image/jpeg'),
(349, '/konektem/uploads/images/6a39b3f71f946.jpg', 'image/jpeg'),
(350, '/konektem/uploads/images/6a3a153f60454.jpg', 'image/jpeg'),
(351, '/konektem/uploads/images/6a3a158420ed6.jpg', 'image/jpeg'),
(353, '/konektem/uploads/images/6a3a16876f623.jpg', 'image/jpeg'),
(354, '/konektem/uploads/images/6a3a17728843e.jpg', 'image/jpeg'),
(356, '/konektem/uploads/images/6a3a17b06a3ab.jpg', 'image/jpeg'),
(362, '/konektem/uploads/images/6a3c4dc52e5be.png', 'image/png'),
(364, '/konektem/uploads/images/6a3c4ef4d802e.png', 'image/png'),
(365, '/konektem/uploads/images/6a3c4efca60a1.png', 'image/png');

-- --------------------------------------------------------

--
-- Структура таблицы `interview`
--

CREATE TABLE `interview` (
  `id` bigint NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `image_id` bigint DEFAULT NULL,
  `views` int DEFAULT '0',
  `shares` int DEFAULT '0',
  `likes` int DEFAULT '0',
  `guest_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_title` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `interview_date` date DEFAULT NULL,
  `video_id` bigint UNSIGNED DEFAULT NULL,
  `title_hash` char(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `interview`
--

INSERT INTO `interview` (`id`, `title`, `description`, `created_at`, `updated_at`, `image_id`, `views`, `shares`, `likes`, `guest_name`, `guest_title`, `duration`, `interview_date`, `video_id`, `title_hash`) VALUES
(4, '“Plan Enmi an Echwe\": Une Mélodie Bénie d\'Espoir et de Foi.', 'Dans l\'étendue enrichissante de la musique évangélique haïtienne, une collaboration inspirée a pris forme, portant le nom significatif de \"Plan Enmi an Echwe\". Les talentueux artistes Jean Enock Louis et Sherline Dalberis largement reconnus dans le secteur évangélique, se sont unis pour créer une composition captivante, dédiée à inspirer et encourager les chrétiens à maintenir une foi inébranlable.\r\n\r\n\r\nÉvoluant au Canada tout en restant profondément liés à leur terre natale, Haïti, Jean Enock Louis et Sherline Dalberis, respectivement père et mère de famille, partagent leur talent et leur dévotion à travers un répertoire de musique évangélique très riche. La rencontre providentielle entre ces deux artistes a donné naissance à \"Plan Enmi an Echwe\".\r\n\r\n\r\nL\'histoire de cette collaboration exceptionnelle a pris racine et a pris vie grâce à une suggestion inspirée du maestro Samuel Joseph Pierre Paul, résidant au Canada. Témoin d\'une performance exceptionnelle de Sherline lors d\'un concert et conscient de la voix unique de Jean Enock Louis, l\'idée a germé et s\'est concrétisée.\r\n\r\n\r\nSelon les dires de Jean Enock Louis, compositeur de la chanson, son inspiration émane du Saint-Esprit, soulignant ainsi la direction divine dans la composition musicale. Sherline Dalberis, également imprégnée de la foi chrétienne, a joué un rôle essentiel en contribuant avec ses idées et ses encouragements.\r\n\r\n\"Plan Enmi an Echwe\" n\'est pas simplement une chanson, mais un hymne d\'espoir, porteur d\'un message profondément enraciné dans la foi, nous déclare Jean Enock Louis, ancien membre de la chorale Teknon Vox Mass Choir. Cette chanson évoque la protection divine face aux adversités. Sherline Dalberis, ancienne vedette du gospel Hoshama, aspire à encourager les croyants à rester confiants, conscients de la victoire divine sur le mal.\r\n\r\n\r\nMalgré les défis rencontrés par Enock et Sherline, la décision courageuse de créer un vidéoclip pour \"Plan Enmi an Echwe\" a été prise, démontrant leur engagement envers les mélomanes de la musique évangélique. L\'équipe derrière le clip vidéo a surmonté les obstacles de l\'hiver canadien avec dévouement, capturant l\'essence de la chanson. Jean Enock Louis et Sherline Dalberis privilégient la simplicité visuelle, permettant au message de la chanson de rayonner à travers chaque image. L\'énergie dégagée par les deux psalmistes dans la vidéo traduit l\'essence même du message qui compose la musique.\r\n\r\nEn dehors des projecteurs et de la scène, la production exceptionnelle de cette musique fortifiante est le fruit du travail d\'experts chevronnés. Le séquençage est réalisé avec habileté par Milot Joseph, accompagné d\'une chorale exceptionnelle composée de Sabine Lira, Marie Marthe Sima et Milot Joseph lui-même. Le studio d\'enregistrement responsable de cette création remarquable est affilié à Milot Music.\r\n\r\n\r\nLa collaboration entre Jean Enock Louis et Sherline Dalberis dans \"Plan Enmi an Echwe\" témoigne de leur engagement à créer des œuvres musicales évangéliques inspirantes et encourageantes pour les moments cruciaux.\r\n\r\n\r\nDes projets futurs sont envisagés, mais pour l\'instant, “Plan Enmi an Echwe” est disponible sur toutes les plateformes de streaming, notamment sur les chaînes YouTube de Jean Enock Louis et Sherline Dalberis.\r\n\r\n\r\nDans leur répertoire musical, Jean Enock Louis a enregistré trois (3) albums, tandis que Sherline Dalberis, avec deux (2) albums, voit chaque mélodie qu\'ils créent devenir une source d\'inspiration et de réconfort à travers le monde.\r\n\r\n\r\nKonekte M vous encourage à affermir votre foi à travers cette mélodie bénie d\'espoir et de foi.\r\n\r\nRédaction: Vanauscheca Bouzy\r\n\r\n#konektem\r\n#toutkotenenpotkile', '2025-11-29 02:13:27', '2026-06-20 19:52:52', 157, 0, 3, 2, NULL, NULL, NULL, NULL, NULL, 'd419d0be398f0e25190d2fae147eb395'),
(9, 'Bishop Gregory Toussaint lance la marche internationale « Souf pou Ayiti » après 110 000+ signatures.', 'Après avoir lancé une pétition qui a recueilli 110 000 signatures en moins d\'une semaine, Bishop Gregory Toussaint a lancé une marche qui a recueilli plus de 50 000 inscriptions dans ce même laps de temps.\r\n\r\nLa pétition «Souf Pou Ayiti » a atteint son objectif en l\'espace de quelques jours. Les organisateurs pensaient qu’elle prendrait 40 jours pour obtenir 100 000 signatures, mais c\'était sans compter l\'engouement suscité par cette initiative qui a eu un fort soutien en Haïti et dans la diaspora haïtienne. Résultat: jusqu\'à date, plus de 113 000 personnes ont déjà signé.\r\n\r\n\r\nPour capitaliser sur cette dynamique, Bishop Gregory Toussaint a proposé l\'organisation d\'une grande marche pour mobiliser les Haïtiens du monde entier sur la situation d\'Haïti.\r\n\r\n\r\n «En fait, indique le pasteur titulaire du Tabernacle de Gloire, j’ai reçu des directives de la part de Dieu pour une telle initiative, et plusieurs autres personnes me l’ont confirmé».\r\n\r\n\r\nCette marche, qui aura une ampleur internationale, aura plusieurs objectifs, selon Bishop Toussaint. Le premier objectif, indique-t-il, est d’inciter les citoyens ordinaires, les Amos, comme il les appelle en référence au prophète juif de la Bible, à prendre leur responsabilité dans la vie de la nation haïtienne.\r\n\r\n\r\n«Ce sont les citoyens ordinaires qui d’habitude apportent les changements, pas les élites qui, eux, préfèrent le statu quo. Le Mouvement des droits civiques aux USA a commencé par Rosa Parks, une couturière, une chrétienne», explique Gregory Toussaint.\r\n\r\n\r\n«Après l’arrestation de Parks, poursuit-il, les pasteurs américains se sont soulevés et c’est ainsi que le mouvement des droits civiques allait commencer avec l’église. Quand l’église combine la prière avec des actions, rien ne peut lui résister. L’église a été le fer de lance pour briser le système de ségrégation aux USA. \r\n Je crois que ce que l’église américaine a fait aux USA, l’église haïtienne peut le faire en Haïti».\r\n\r\n\r\nLe second objectif de la marche est de communiquer un message à la communauté internationale. «Le message est que nous sommes concernés par ce qui se passe en Haïti et que nous voulons faire partie de la solution», déclare le PDG de Radio Shekinah.\r\n\r\n\r\nIl a poursuivi en soulignant le rôle que la diaspora peut jouer notamment quand il s’agit d’exercer la pression sur la communauté internationale. «Haïti ne peut pas faire pression sur les Etats-Unis, le Canada et la France, mais la diaspora haïtienne le peut. Par exemple, aux USA, une grande partie de la diaspora haïtienne peut voter dans les élections américaines. Il y aura des élections en 2024. C’est le bon moment pour demander aux autorités américaines de mieux traiter Haïti car notre vote peut être décisif dans leurs élections».\r\n\r\n\r\n Troisièmement, la marche du 9 juillet vise à demander à identifier ceux qui financent les gangs en Haïti et à les pénaliser à travers le projet de loi S.396 du Sénat et le projet de loi HR 1684 de la Chambre des représentants, intitulé \"Haiti Criminal Collusion Transparency Act of 2023\". La marche appellera enfin au maintien du programme humanitaire de migration conditionnelle (Humanitarian Parole). \r\n\r\n\r\nRappelons que ce programme est remis en cause par plusieurs États américains qui ont intenté une action en justice contre l\'administration Biden, affirmant que le processus de migration conditionnelle est illégal.\r\n\r\n\r\nUn procès était précédemment prévu pour le 13 juin 2023 pour déterminer le sort du «Humanitarian Parole». Cependant une nouvelle date, le 24 août 2023, a été proposée pour ledit procès en attendant d\'être confirmée par une décision judiciaire.\r\n\r\n\r\n«La situation en Haïti est comparable à celle de l’Ukraine, donc si le programme est maintenu pour l’Ukraine, il ne fait aucun sens qu’il s\'arrête pour Haïti», a souligné Pasteur Toussaint.\r\n\r\n\r\nÀ ce jour, selon l\'ambassade américaine en Haïti, 39 000 Haïtiens ont été examinés et approuvés pour voyager dans le cadre du programme depuis le 5 janvier jusqu\'à la fin du mois d\'avril. \r\n\r\n\r\nLes organisateurs de la marche soutiennent que maintenir ce programme permettra de sauver des vies en offrant une voie légale et sûre aux Haïtiens cherchant refuge aux États-Unis.\r\n\r\n\r\nCette marche internationale se déroulera dans au moins plusieurs pays (Haïti, USA, Canada, République Dominicaine, Chili, France) et de nombreuses villes du monde (Miami, Orlando, Boston, New York, Atlanta, Philadelphie, New Jersey, Montréal, Paris, Santo-Domingo, Santiago) pour témoigner de l\'engagement de la communauté haïtienne mondiale à trouver une issue à la crise.\r\n\r\n\r\n«Le jour de l\'événement, qui sera un dimanche, nous allons marcher dans les rues après le service dominical avec des chaussures de sport», annonce Pasteur Toussaint.\r\n\r\n\r\n«Aux USA, poursuit-il, on marchera dans tous les États américains, principalement la Floride, New York, New Jersey, Massachusetts, où se trouve une forte population haïtienne. Pour les endroits où la population est moins importante, on leur demandera de se tenir devant un bâtiment gouvernemental. On marchera dans des villes comme Miami, Orlando, New York, Philadelphie, Paris, Montréal, Santo-Domingo, etc».\r\n\r\n\r\nEn Haïti, la marche se tiendra à Port-au-Prince, la capitale, à Grand-Goâve, Cap-Haïtien, Léogâne, Petit-Goâve, Jérémie, Les Cayes, Hinche, Jacmel, Gonaïves, Miragoâne, Mirebalais, Fort-Liberté et Saint-Marc.\r\n\r\n\r\nAussi, plus de 180 pasteurs d\'Haïti et de la diaspora se joindront à cette initiative. Parmi lesquels on peut citer: Samuel Robuste (Jacksonville), Eddy Gervais (Miami), Max Moïse Sauld (Haïti), Samuel Nicolas (New York), Phil Mercidieu (Fort Myers), Wilner Cayo (Canada), Wilner Prudent (Miami), Mullery Jean-Pierre (New York), Carlos Pierre (Miami), Malory Laurent (New York), Daniel Chery (Montréal), Caleb Barthélus (Montréal), Emmanuel Dessalines (Montréal), André Muscadin (Haïti), Delly Benson (Haïti).\r\n\r\n\r\nEn tout, 200 organisations, 350 pasteurs et 50 000 personnes se sont engagés à participer à la marche. Pour rappel, le ministère Shekinah avait lancé la pétition « Souf pou Ayiti » le 2 juin dernier, préoccupé par la nouvelle conjoncture haïtienne qui force les citoyens haïtiens à laisser leur quartier voire leur pays. Depuis juillet 2021, la situation en Haïti s\'est détériorée, marquée par une augmentation des enlèvements, des agressions, des violences sexuelles et des extorsions perpétrées par des gangs criminels, au point où les Nations unies ont comparé le niveau d\'insécurité du pays à celui d\'une zone en guerre.\r\n\r\n\r\nLa pétition « Souf pou Ayiti » avait été accueillie avec un grand enthousiasme par les Haïtiens de partout. Elle visait à aider les compatriotes en Haïti et ceux qui sont récemment entrés aux Etats-Unis par le biais du « Humanitarian Parole », soit le programme humanitaire de migration conditionnelle, lancé en janvier dernier par le président Joe Biden.\r\n\r\n\r\nLa marche se déroulera durant l\'événement «40 Jours de Jeûne» organisé par le ministère Shekinah dirigé par Bishop Gregory Toussaint.\r\n\r\nGregory Toussaint, PDG, entrepreneur, philanthrope, auteur de best-sellers et orateur haïtiano-américain, est le pasteur titulaire de Tabernacle de Gloire. Cette église, composée de 47 campus, compte 25 000 membres actifs locaux et 50 000 membres en ligne. \r\n\r\n\r\nEn tant que PDG et fondateur de Shekinah.fm, Gregory a accumulé plus de 4 millions d\'abonnés sur les médias sociaux et compte en moyenne 4 millions de vues par semaine. Ses émissions de radio touchent plus de 5 millions de foyers en Haïti. \r\n\r\n\r\nSon émission de télévision, \"Bishop G Live\", diffusée sur des réseaux en Amérique, en Europe, en Afrique et au Moyen-Orient, touche un nombre impressionnant de 500 millions de foyers dans le monde. Parlant couramment l\'anglais, le français, l\'espagnol et le créole haïtien, Gregory est diplômé en affaires (BS), en droit (LL.M) et en théologie (Th.M., D.E.A). Il est marié depuis 20 ans et a deux fils. Il réside avec sa famille à Miami, en Floride.\r\n\r\n\r\nRedaction: Jonel Juste', '2025-11-29 02:33:21', '2026-06-19 06:44:48', 162, 0, 2, 1, NULL, NULL, NULL, NULL, NULL, 'f3bb89310a1dcd640c06009c4d459a05'),
(5, 'Et si le S.O.S Tour de Rosena J. Orys devenait l’une des plus grandes initiatives musicales haïtiennes de l’année ?', 'Née le 27 avril 1992 dans une famille chrétienne, Rosena Jocelin a grandi avec un rêve aussi lumineux que sa voix : devenir une chanteuse de cœur, capable de toucher les âmes, guérir les blessures et élever les esprits. Dès l’âge de 11 ans, elle foule les scènes haïtiennes à travers diverses chorales telles que Hallelujah Gospel et Jimla Gospel.\r\n\r\n\r\nMais c’est en 2016 que le public découvre une Rosena en solo, vibrante et habitée, grâce au titre marquant « Ou fè m ap viv ». Depuis lors, elle n’a cessé de gravir les échelons, mêlant foi et vécu collectif pour résonner au cœur d’un peuple haïtien blessé, souvent abandonné, mais toujours debout. En 2025, Rosena frappe un grand coup.\r\n\r\n\r\nS.O.S : une chanson, un cri, une vague d’espoir.\r\n\r\nLe 26 avril 2025, sur un beat afro entraînant, Rosena lance « S.O.S ». En moins d’une semaine, le titre envahit réseaux sociaux, rues, églises et plateformes de streaming. Deux mois plus tard, il cumule plus de 3,1 millions de vues sur YouTube, un exploit rare dans le milieu évangélique haïtien.\r\n\r\n\r\nMais ce succès fulgurant ne repose pas uniquement sur sa musicalité : S.O.S est un cri collectif, un appel spirituel et social. Alors qu’Haïti est plongée dans l’insécurité, la peur et les déplacements forcés, Rosena élève un S.O.S vers Dieu, un appel auquel le peuple répond massivement. Entre challenges, reprises, vidéos de prières, enfants et adultes, la chanson franchit toutes les barrières religieuses et sociales. Elle devient un hymne, une mélodie partagée avec émotion, mains levées ou pieds dansants.\r\n\r\n\r\nLe S.O.S Tour : un cri d’adoration pour Haïti et au-delà.\r\n\r\nLe 24 juillet 2025, dans une salle comble de l’hôtel El Rancho, Rosena J. Orys annonce officiellement le lancement du S.O.S Tour, une tournée d’adoration qui traversera Haïti et plusieurs pays étrangers. Elle est entourée de ses collaborateurs Lophane Laurent, Barbara Cassamajor, Penter Orys et Audner Martin, tous mobilisés pour porter cette grande initiative.\r\n\r\n\r\nOrganisée par l’Agence Alexandre, Lophane Project et Acordia Shipping, cette tournée vise à élever une voix musicale et spirituelle forte pour Haïti.\r\n\r\n« La musique est un cri d’alerte face aux souffrances du peuple haïtien. Ce cri, nous voulons qu’il touche tous les cœurs, croyants ou non, enfants comme adultes », déclare Rosena avec émotion.\r\n\r\n\r\nAu-delà de la musique, le S.O.S Tour se veut aussi un projet social engagé, tourné vers les enfants, les femmes et les familles vulnérables. Rosena annonce également un prochain album, qualifié de « prophétique », destiné à insuffler foi, espérance et engagement à une Haïti en quête de renouveau.\r\n\r\n\r\nUn concert inaugural vibrant au Palais Municipal de Delmas.\r\n\r\nLe dimanche 27 juillet 2025, le Palais Municipal de Delmas vibre au rythme du S.O.S Tour. Le public, venu en grand nombre, chante, danse et prie avec ferveur tout au long de la soirée. Rosena, accompagnée d’une équipe musicale solide et d’artistes invités tels que Barbara Cassamajor, Joy Clerf Derisier et Wiliadel Denervil, offre une performance riche en émotions, mêlant louanges profondes et rythmes entraînants.\r\n\r\n\r\nParmi les moments forts : la puissante interprétation de « Je bénirai l’Éternel » par Barbara Cassamajor, les chorégraphies spirituelles du groupe Adassa Dance, et un hommage touchant à Rosena par l’économiste Etzer Émile, qui lui offre un tableau en reconnaissance de son engagement. Le concert s’achève par une prestation magistrale de Rosena accompagnée d’un saxophoniste, emportant le public dans une adoration intense.\r\n\r\n\r\nUne tournée qui se vit aussi par la compassion : visite au CERMICOL.\r\n\r\nLe lundi 28 juillet, après son triomphe à Delmas, Rosena et son équipe, en collaboration avec le Flamboyant Restaurant, visitent le Centre de Rééducation Pénitentiaire des Mineurs en Conflit avec la Loi (CERMICOL). Cette action réunit plusieurs figures engagées du milieu évangélique, telles que Barbara Cassamajor, Joy Clerf Derisier, Wiliadel Denervil et David Morinvil.\r\n\r\n\r\nLe CERMICOL héberge actuellement environ 630 détenus, dont 96 mineurs, 174 femmes et jeunes filles, et 360 hommes adultes.\r\n\r\nAu programme : mini-concert, temps de prière, évangélisation et repas partagé avec les détenus.\r\n\r\n« Tout le monde mérite une part d’amour. Peu importe les erreurs commises, Dieu est toujours prêt à pardonner », déclare Rosena avec émotion.\r\n\r\n\r\nPour l’équipe, cette visite fut un moment fort, une invitation profonde à réfléchir sur la véritable signification de la liberté.\r\n\r\n\r\nCap-Haïtien a chanté sous la pluie !\r\n\r\nCe dimanche 3 août 2025, malgré la pluie, les Capois se sont rassemblés en masse au Complexe Versailles pour vivre la deuxième étape du S.O.S Tour. Barbara met le feu dès les premières notes avec Louwe Louwe, Loutchina fait lever les mouchoirs avec L ap beni w, pendant que Wiliadel et Joy embrasent la salle avec leurs titres phares.\r\n\r\n\r\nRosena J. Orys, très attendue, est accueillie avec ferveur. Son passage sur scène transforme l’atmosphère en un véritable lieu d’adoration. Chaque chanson résonne comme un cri de foi, et le public, transporté, chante, prie, adore. Un concert marqué par la pluie… mais surtout par la présence palpable de Dieu.\r\n\r\n\r\nUne musique évangélique qui dérange… mais qui libère\r\n\r\nToute onde puissante crée des remous. Lorsque le célèbre influenceur UncleKendjy publie un challenge autour de S.O.S, une vague de critiques surgit dans le milieu protestant :\r\n\r\n« Rosena pa dwe poste videyo sa yo. Pa gen limyè ak tenèb ansanm. »\r\n\r\nCe débat théologique soulève une question essentielle : jusqu’où la musique chrétienne peut-elle aller pour toucher ceux qui sont “dehors” ?\r\n\r\nRosena répond avec foi et assurance :\r\n\r\n« Se pa mwen ki chwazi mizik sa, se BONDYE ki mete l nan kè mwen. Se pou li sèvi ak li, jan li vle, lè li vle, ak moun li vle. »\r\n\r\nUn réveil en marche : une tournée pour les oubliés, les blessés, les affamés de sens\r\n\r\nLe S.O.S Tour ne s’adresse pas seulement aux chrétiens. Il parle à ceux que la société rejette, à ceux que les églises oublient, à ceux qui ne trouvent pas Dieu dans les discours, mais qui espèrent encore le sentir dans un chant, une vibration, une parole qui les comprend.\r\n\r\nAlors, doit-on voir dans S.O.S et sa tournée une révolution musicale ou une simple mode passagère ?\r\n\r\n\r\nEt si c’était le début d’un réveil spirituel, porté par une femme, une voix, un peuple en détresse ?\r\n\r\nEt si, enfin, la musique évangélique haïtienne faisait tomber ses murs, pour entrer dans les rues, les foyers… et dans les douleurs réelles du peuple ?\r\n\r\n\r\n\r\nRedaksyon:\r\n\r\nRonalson Bryan Blanfort\r\n\r\n\r\n\r\n#Sos\r\n\r\n#RosenaOrys\r\n\r\n#konektem\r\n\r\n#toutkotenenpotkilè', '2025-11-29 02:19:06', '2026-06-19 06:44:48', 158, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, '6ff9d69366d718f89cf0d4e0f5476e09'),
(6, 'Samedi de la grâce: Le milieu en parle.', 'Les préparatifs de Samedi de la Grâce avancent avec passion et ferveur. Les tee-shirts officiels de l’événement sont déjà disponibles et la demande dépasse toutes les prévisions. Plusieurs églises, associations et groupes de jeunes portent fièrement ces couleurs, affirmant leur engagement avec enthousiasme. Les artistes confirmés continuent de mobiliser le public sur les réseaux sociaux et d’attiser l’excitation autour de ce grand rendez-vous spirituel.\r\n\r\n\r\nSur les réseaux, l’impatience se lit dans chaque commentaire, chaque réaction. Une jeune participante confie : « Je suis impatiente de vivre ce moment ! À chaque fois que je vois un nouveau tee-shirt ou un artiste annoncé, mon cœur s’emballe. » Un responsable d’église ajoute : « Malgré tout ce qui se passe dans le pays, Samedi de la Grâce est comme une lumière dans l’obscurité. Je suis fier de voir cette mobilisation. » Des témoignages qui traduisent l’importance et l’attachement du public à cette rencontre.\r\n\r\n\r\nMalgré les défis — pluie persistante, climat d’insécurité et autres obstacles — l’équipe organisatrice reste debout. Avec dévouement, détermination et foi, elle travaille sans relâche pour garantir la réussite de l’événement. Une autre voix témoigne : « Chaque affiche, chaque annonce me donne de l’espoir. On attend tous ce moment pour chanter, prier et sentir la présence de Dieu ensemble. » Ces paroles sincères révèlent la soif spirituelle d’une jeunesse avide d’espérance.\r\n\r\n\r\nSamedi de la Grâce dépasse ainsi le cadre d’un simple événement : c’est un acte collectif de foi, une déclaration d’espérance dans un contexte troublé. Alors que beaucoup vivent dans l’inquiétude et l’incertitude, cette journée s’annonce comme un souffle spirituel, un rappel que la grâce de Dieu reste accessible à tous, malgré les tempêtes.\r\n\r\n\r\nVous aussi, ne manquez pas ce rendez-vous. Enfilez votre tee-shirt, invitez vos amis et venez vivre un moment de louange, de prière et d’adoration dans une atmosphère unique. Participez pour dire : « J’y étais, j’ai senti la présence de Dieu, et j’ai ajouté ma voix à ce grand cri d’espérance. »\r\n\r\n\r\n\r\nRédaction: Ronalson BLANFORT\r\n\r\n\r\n\r\n#Samedidelagrace \r\n\r\n#konektem\r\n\r\n#toutkotenenpòtkilè\r\n\r\n', '2025-11-29 02:20:49', '2026-06-19 06:44:48', 159, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, '264a22baa21c6e57204ac74ec0c25199'),
(7, 'Caribbean Worshipers : yon randevou adorasyon moun Bahamas pa negosye.', 'Adore BonDye ak sove nanm se de (2) gwo objektif « Caribbean Worshipers » vize chak ane, yon konkennchenn evènman ki reyini kominote karayibeyèn nan, sou lidèchip Neslin Destilhomme. Pou yon katriyèm fwa, anfoul, moun Bahamas pare pou reponn prezan nan randevou sila.  Ekip Konekte M nan tanmen yon antrevi ak vizyonè inisyativ sila a, Neslin Destilhomme ki te pataje avèk nou motivasyon l ki pèmèt li reyalize aktivite sa a.\r\n\r\n\r\n\r\nJonathan Dorziaire, jounalis : Kisa « Caribbean Worshipers ye » epi ki kote lide a soti ?\r\n\r\n\r\nNeslin Destilhomme : Caribbean Worshipers se avan tou, yon aksyon de gras mwen ak madanm mwen vle ofri Letènèl pou montre L kijan nou rekonesan anvè tout sa L fè pou nou. Jeneralman aksyon de gras souvan gen fòm reyini moun chante, temwanye epi bay manje. Men nan ka pa nou pi bèl fason se te mete sou pye yon evènman k ap reyini tout kominote karayibeyèn nan, nan yon sèl espas, pandan n ap fè lwanj pou BonDye ansanm, epi chache nanm pou Sovè nou Senyè Jezi. Se sa ki fè, an Mas 2021, nou te deside lanse evènman sila sou non « Caribbean Worshipers».\r\n\r\nJD: Aprè twa (3) edisyon  èske w kwè « Caribbean Worshipers » jwenn yon plas nan kominote karayibeyèn nan ?\r\n\r\n\r\nND: Nou ka di wi gras a Dye, parapò ak jan moun yo toujou deplase an foul. Chak ane ekip la oblije nan chanje espas, paske espas yo toujou twò piti, ayisyen tankou bayameyen toujou debake an mas. Se pa sèlman afliyans moun ki patisipe nan aktivite a men se sitou «line up» la ki reflete lide pou mete talan karayibeyen yo anavan. Plizyè adoratè ak adoratris nan Bahamas ak lòt zile nan Karayib la toujou reponn prezan nan « Caribbean Worshipers ». Chak mwa Mas tout Bahamas, depi katran (4 an), konnen « Caribbean Worshipers » se yon moman adorasyon yo pa dwe rate.\r\n\r\nJD: 29 Mas 2025 se dat pou katriyèm edisyon Caraibean Worshipers, kisa k ap diferan parapò ak lòt edisyon avan yo?\r\n\r\n\r\nND: Tout edisyon Caraibean Worshipers yo toujou wololoy men edisyon sila pral san parèy. Dabò nou òganize katriyèm nan, nan youn nan pi gwo espas nan Bahamas: BFM Diplomat Center, ki gen kapasite pou pran plis pase 2 000 plas. Ane a n ap gen plis moun k ap vin adore parapò ak lòt ane yo avan. Men n ap egalman gen plis moun nan delegasyon k ap soti tribòpabò pou vin adore ak nou tankou Ayiti, Kanada, Etazini, Repiblik Dominikèn. Ane a tou, nou gen yon «line up» ki chaje jèn adoratè, adoratris tankou Loutchina Decius, Joy, Atis Fondation Frè Luckson Zòn Pa Fè Moun (FFLZPFM) ak lòt ankò. Ane sa a Nou ajoute yon moman kominyon fratènèl, aprè adorasyon n ap gen pou n manje ansanm.\r\n\r\nJD: Lè n konsidere vwayaj, ebèjman ak lòt obligasyon nou genyen pou n byen akeyi tout delegasyon nou yo, kisa ki eksplike « Caribbean Worshipers » gratis ?\r\n\r\n\r\nND: Caribbean Worshipers se pa sèlman yon aksyon degras, li se egalman yon evènman pou chache nanm pou BonDye se sa ki fè tout edisyon nou yo toujou gratis. Yon aksyon de gras ki vin tounen vizyon, ebyen BonDye fè pwovizyon. Pandan de (2) edisyon se grès kochon ki te kuit li. Men ane sa a, BonDye solisite plizyè moun toupatou pou kontribiye pou « Caribbean Worshipers » posib. N ap gen plis pase yon trantèn moun k ap nan delegasyon k ap soti Ayiti, Repiblik Dominikèn, Etazini, Kanada pou adore. BonDye ki bay vizyon an gentan fè pwovizyon pou tout bagay.\r\n\r\n\r\nNeslin Destilhomme, ki entèprete « Jezi ooo » ki soti an oktòb 2024, se pami mizik pandan fen lane 2024 la ak kòmansman ane 2025 lan ki beni plizyè nanm, pwomèt yon moman san parèy nan adorasyon ak louwanj. Vizyonè a rete kwè moman sa a ap pote fui, li rete kwè ap gen vi k ap transfòme, moun ap vin jwenn Jezi, paske Sentespri BonDye pral aji nan BFM Diplomat Center, samdi 29 mas la.\r\n\r\n\r\n\r\nRedaksyon: Jonathan Dorziaire\r\n\r\n\r\n\r\n#konektem\r\n\r\n#toutkotenenpòtkilè', '2025-11-29 02:23:19', '2026-06-19 06:44:48', 160, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'f6870e03ced5fbd8f2a4202800b86ba8'),
(8, 'Amazing Grace, au-delà d\'un spectacle à une dose de grâce.', 'Ce samedi 5 avril 2025, El Rancho Convention Center s’est mué en un véritable sanctuaire d’adoration. Pour sa deuxième édition, le concert Amazing Grace a uni voix, instruments et cœurs vers une même direction, l\'adoration. Plus qu’un simple spectacle, Amazing Grace s\'est illustré en une parenthèse sacrée, une immersion dans la grâce, la communion et la foi partagée. Derrière cette réussite, une équipe réunie promoteurs et maestro: Joslord Elmilus, Kervens Pierre, maestro David Morinvil et Real Louis. Quatre passionnés, portés par une vision commune : faire de la louange un langage universel, et de la scène un lieu où souffle la faveur divine.\r\n\r\nDès les premières minutes du concert, DJ Wonder, l’une des rares femmes à exceller dans cet univers, a joué un rôle central. Alors que le public était encore dispersé dans l’espace du El Rancho, elle a su, par des sélections musicales judicieuses et une énergie contagieuse, rassembler les participant.e.s et créer une atmosphère propice à la célébration. Par ses transitions soignées et son sens du rythme, elle a introduit avec finesse l\'évènement, maintenant une dynamique constante entre les artistes. DJ Wonder a ainsi brillamment assuré l’ambiance, tout en imprimant sa touche personnelle à cette soirée.\r\n\r\n\r\nDes performances habitées d’onction et d’énergie\r\n\r\n\r\nLa soirée s’est ouverte sur une atmosphère empreinte de spiritualité avec le groupe Belance, qui a insufflé une énergie rafraîchissante et un esprit d’adoration sincère. Carl Emile et son band malgré des défis techniques ont ensuite enchaîné avec une prestation dynamique et profonde, instaurant une ambiance soutenue de louange dès les premières minutes.\r\n\r\nLes Élus, tout de blanc vêtus, ont captivé l’audience avec une fusion audacieuse de gospel, de sonorités Lakay et de modernité. Leur interprétation de \"Avanse\" a donné le ton, suivie de \"Vin Selebre\", une création mêlant de sonorités indiennes et trap, qui a littéralement électrisé la salle. Dans cette même veine d’originalité. Lynda Joseph, avec sa voix angélique, a profondément ému avec \"Nan Bondye mwen kwè\" et \"Li pap janm lage m\", deux chants porteurs d’espérance.\r\n\r\nBarbara Cassamajor a enflammé la scène avec \"Mon âme bénit l’Éternel\", sur un rythme afro entraînant, entourée de danseurs. Elle a ensuite touché le public avec \"Pwomès pa m\", plongeant l’assemblée dans une adoration douce et sincère. Inspiré de Jean 5:17. Stanley Georges a livré une performance puissante. L’un des moments les plus marquants fut lorsqu’il a invité le public à allumer les flashs de leurs téléphones, symbolisant la lumière chassant les ténèbres d’Haïti. Il a ensuite entonné \"Mwen te deside pou m suiv Lesenyè, m pap tounen\", avant de conclure par \"Glwa Ou\", un chant empreint de foi et de puissance prophétique.\r\n\r\nAutre passage fort : JoyClerf. Avec \"Wi li fè l\", il  a conduit l’auditoire dans une adoration intense. L’émotion était d’autant plus forte que plusieurs des titres interprétés ont été composés par son père, Jean Claude Derisier “Zoom”, conférant une belle touche d’héritage et d’authenticité. Le groupe Holy Music a ensuite pris le relais avec une énergie maîtrisée et une belle cohésion scénique.\r\n\r\nAstharmonie a élevé davantage l’atmosphère dès \"M ap pwospere\", avant de déclencher une vague de louange dansante sur \"I Believe\" de Jonathan Nelson. Ensuite, la chorale DEG, habillée de cuir noir et de rouge grenat, a fait sensation avec \"Mèsi\", en collaboration avec Rutshelle Guillaume. Leur animation impeccable sur \"M a va wè li\" a enflammé la salle, confirmant leur signature musicale alliant innovation et performance scénique audacieuse.\r\n\r\n\r\nTaliana Lindor a profondément touché l’audience avec \"L’Éternel p ap renouvle kontra ankò\" et \"klere chimen\" du groupe Zetwal, offrant un moment d’intimité spirituelle.\r\n\r\nLoutchina a enchaîné avec un bouquet vibrant : \"À Dieu soit la gloire\", \"Jezi wo\" et \"L ap beni w\", entraînant l’assemblée dans une louange joyeuse et dansante. \r\n\r\nEnfin, Wiliadel a clôturé la soirée avec intensité. Son entrée solennelle, sous les flashs du public, a marqué les esprits. Avec \"Mwen poukont mwen\", \"M pa prale jan m vini an san m pa beni\" et \"Sòlda leve pye w\", elle a offert un final à la fois puissant, touchant et engagé.\r\n\r\nLa grâce au cœur de la tempête\r\n\r\n\r\nMalgré quelques retards et défis techniques (retours son défaillants…), l’équipe artistique est restée solide.  Et dans un contexte socio-politique fragile, Amazing Grace a été bien plus qu’un concert : un acte de foi collectif. Les flashs levés pour Haïti, les prières silencieuses, les voix unies dans l’espérance… tout rappelait que la faveur de Dieu transcende les circonstances. À El Rancho, un vent de paix, de force et de foi s’est levé – et il portait un nom : Amazing Grace.\r\n\r\n\r\nRedaksyon:\r\n\r\nRonalson Bryan Blanfort\r\n\r\n\r\n#konektem\r\n\r\n#toutkotenenpotkilè', '2025-11-29 02:29:33', '2026-06-19 06:44:48', 161, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, '614b3cd9b561b220d6635e14e9647e7c'),
(10, 'Melomania Group : \"A Night of Worship with Sinach\"', 'Depuis sa fondation en 2015 par Jonathan Laguerre, Melomania Group sï¿½est imposï¿½ comme une rï¿½fï¿½rence incontournable dans lï¿½ï¿½vï¿½nementiel ï¿½vangï¿½lique. Dï¿½abord basï¿½e en Rï¿½publique Dominicaine, lï¿½organisation a ï¿½tendu ses activitï¿½s aux ï¿½tats-Unis il y a seulement deux ans, tout en conservant sa mission premiï¿½re : crï¿½er des expï¿½riences dï¿½adoration puissantes et fï¿½dï¿½ratrices. En 10 ans, Melomania Group a portï¿½ sur scï¿½ne des artistes chrï¿½tiens de renom, ï¿½difiï¿½ des milliers de croyants et offert ï¿½ la communautï¿½ internationale des moments uniques de communion dans la louange.\r\n\r\nUn parcours jalonnï¿½ de rï¿½alisations mï¿½morables\r\n\r\nDe \"Haitianos En Alabanza\" en 2019, rassemblant Fre Gabe, Salil Lirah et Thamar Joseph ï¿½ Santo Domingo, ï¿½ la vente signature de lï¿½album de Loutchina Dï¿½cius en aoï¿½t 2023, Melomania Group a multipliï¿½ les ï¿½vï¿½nements marquants.\r\n\r\nLa tournï¿½e dominicaine de Rosena J. Orys en 2021, les Christmas Concerts en 2022 et les spectacles de 2024 et 2025 aux ï¿½tats-Unis avec Rosena Orys, Berhley Figaro, Spencer Brutus, Deborah Henristal, Tamare et Palmyre Sï¿½raphin illustrent leur capacitï¿½ ï¿½ rï¿½unir des talents variï¿½s autour dï¿½un seul but : exalter le nom de Dieu.\r\n\r\nUne nouvelle soirï¿½e dï¿½adoration internationale\r\n\r\nLe prochain grand rendez-vous de Melomania Group aura lieu le 5 octobre 2025 au Reggie Lewis Track and Athletic Center, ï¿½ Boston, Massachusetts (USA). Intitulï¿½ \"A Night of Worship with Sinach\", lï¿½ï¿½vï¿½nement mettra ï¿½ lï¿½honneur la cï¿½lï¿½bre artiste nigï¿½rienne, dont le ministï¿½re a dï¿½jï¿½ touchï¿½ des millions de vies ï¿½ travers le monde. Aux cï¿½tï¿½s de Sinach, le public vivra une expï¿½rience unique avec Jean Jean (Canada) et FRE Gabe (USA), dans une ambiance oï¿½ la diversitï¿½ culturelle deviendra un langage commun de louange.\r\n\r\nLes billets sont dï¿½jï¿½ disponibles sur eventbrite.com au prix de 44,52 USD jusquï¿½au 31 aoï¿½t, ainsi que des pass VIP ï¿½ 129,89 USD, distribuï¿½s dans certaines ï¿½glises de la rï¿½gion. Plus de 4 000 participants sont attendus pour cette nuit mï¿½morable.\r\n\r\n?? ?? Get your tickets now on eventbrite: https://www.eventbrite.com/e/night-of-worship-with-sinach-tickets-1507766603499?aff=oddtdtcreator\r\n\r\n\r\nUn impact spirituel et communautaire attendu\r\n\r\nPour Melomania Group, cette soirï¿½e est bien plus quï¿½un concert : cï¿½est un acte de foi. Avec une dï¿½cennie dï¿½expï¿½rience et deux annï¿½es dï¿½implantation rï¿½ussie aux ï¿½tats-Unis, lï¿½organisation veut prouver quï¿½ï¿½ travers la louange et lï¿½adoration, de grandes choses peuvent ï¿½tre accomplies pour la gloire de Dieu.\r\n\r\nLï¿½ï¿½vï¿½nement rassemblera Haï¿½tiens, Africains, Amï¿½ricains et Canadiens dans un mï¿½me ï¿½lan spirituel. Il pourrait aussi avoir une portï¿½e sociale, puisque les organisateurs envisagent de reverser une partie des recettes ï¿½ une ï¿½uvre humanitaire en Haï¿½ti.\r\n\r\n\r\nFoi, unitï¿½ et persï¿½vï¿½rance\r\n\r\nFranï¿½ois Ducheine, Event Manager de Melomania Group, se souvient encore du jour oï¿½, en 2017, lors dï¿½un concert de Delly Benson, il a senti lï¿½Esprit de Dieu le saisir au milieu de lï¿½assemblï¿½e, un moment qui a marquï¿½ son engagement dans ce ministï¿½re.\r\n\r\nAujourdï¿½hui, il voit dans cette Nuit dï¿½adoration une rï¿½ponse spirituelle aux dï¿½fis du temps :\r\n\r\n\r\n_ï¿½ Haï¿½ti traverse des moments difficiles, et beaucoup dï¿½Haï¿½tiens aux ï¿½tats-Unis sont ï¿½prouvï¿½s. Mais au-delï¿½ des agitations, seul Dieu, ï¿½ travers la louange, peut changer les cï¿½urs et donner la paix. ï¿½_\r\n\r\n\r\nInvitation ï¿½ vivre un moment unique\r\n\r\nMelomania Group vous donne rendez-vous pour \"A Night of Worship with Sinach\" le 5 octobre 2025 ï¿½ Boston. Venez et voyez combien le Seigneur est bon : une nuit oï¿½ les frontiï¿½res sï¿½effacent, oï¿½ les voix sï¿½unissent et oï¿½ chaque note ï¿½lï¿½ve lï¿½ï¿½me vers le ciel.\r\n\r\n\r\n\r\nRï¿½daction: Ronalson Bryan Blanfort\r\n\r\n\r\n\r\n#konektem\r\n\r\n#toutkotenenpï¿½tkilï¿½', '2025-11-29 02:35:56', '2026-06-19 23:16:40', 163, 0, 3, 5, 'unknown guest', NULL, NULL, '2026-01-06', NULL, '3e85927d7392b98324af161be4b44813'),
(11, 'Sherline Dalberis & Garby Mesidor, un chant qui parle au cÅ“ur de ceux qui doutent, espÃ¨rent ou nâ€™osent plus prier.', '<p data-start=\"143\" data-end=\"404\">Il y a des chants que l&rsquo;on &eacute;coute. D&rsquo;autres que l&rsquo;on chante. Et puis, il y a ceux que l&rsquo;on vit.<br data-start=\"238\" data-end=\"241\"><strong data-start=\"241\" data-end=\"253\">SEZON SA</strong>, c&rsquo;est une pri&egrave;re mise en m&eacute;lodie. Une r&eacute;ponse &agrave; ceux qui traversent une saison de fatigue int&eacute;rieure, de silences pesants, de &laquo; Dieu, o&ugrave; es-tu ? &raquo;.</p>\r\n<p data-start=\"406\" data-end=\"589\">Avec ce morceau, Sherline Dalberis et Garby Mesidor ne cherchent pas &agrave; s&eacute;duire. Ils tendent la main. &Agrave; ceux qui vacillent. &Agrave; ceux qui peinent &agrave; croire que le ciel les entend encore.</p>\r\n<p data-start=\"591\" data-end=\"1006\">Un chant n&eacute; dans l&rsquo;attente, nourri par la foi.<br data-start=\"637\" data-end=\"640\">L&rsquo;histoire commence par une inspiration. Un murmure de l&rsquo;Esprit, comme le d&eacute;crit Garby Mesidor. De ce souffle int&eacute;rieur est n&eacute;e une chanson. Il l&rsquo;a port&eacute;e, travaill&eacute;e, puis tendue &agrave; Sherline. Elle a &eacute;cout&eacute;, et elle a su.<br data-start=\"860\" data-end=\"863\">&laquo; D&egrave;s la premi&egrave;re &eacute;coute, j&rsquo;ai compris que ce chant avait une mission &raquo;, confie-t-elle. Il ne s&rsquo;agissait plus de performance, mais de v&eacute;rit&eacute;.</p>\r\n<p data-start=\"1008\" data-end=\"1354\">Quand la musique devient miroir d&rsquo;&acirc;me.<br data-start=\"1046\" data-end=\"1049\">Ce n&rsquo;est pas une chanson &eacute;crite pour le plaisir des mots. C&rsquo;est une chanson qui raconte leurs saisons : les leurs, et celles de tant d&rsquo;autres. Des temps de d&eacute;sert, d&rsquo;attente interminable, de pri&egrave;res sans r&eacute;ponse. Mais aussi des temps de perc&eacute;e, de rel&egrave;vement, de foi qui rena&icirc;t quand tout semblait fini.</p>\r\n<p data-start=\"1356\" data-end=\"1475\"><strong data-start=\"1356\" data-end=\"1368\">SEZON SA</strong>, c&rsquo;est le cri discret de ceux qui n&rsquo;ont plus de voix, mais qui gardent, malgr&eacute; tout, une flamme allum&eacute;e.</p>\r\n<p data-start=\"1477\" data-end=\"1720\">Deux temporalit&eacute;s, une seule esp&eacute;rance.<br data-start=\"1516\" data-end=\"1519\">Dans ce morceau, deux temps se rencontrent :<br data-start=\"1563\" data-end=\"1566\">&mdash; le temps humain, celui o&ugrave; chaque jour semble un combat ;<br data-start=\"1624\" data-end=\"1627\">&mdash; et le temps de Dieu, celui qu&rsquo;on ne comprend pas toujours, mais qui tombe toujours juste.</p>\r\n<p data-start=\"1722\" data-end=\"1887\">Au milieu de cette tension, la voix de Sherline et celle de Garby s&rsquo;&eacute;l&egrave;vent pour dire :<br data-start=\"1809\" data-end=\"1812\">&laquo; Le ciel a d&eacute;j&agrave; d&eacute;cid&eacute; en ta faveur, m&ecirc;me si tu ne le vois pas encore. &raquo;</p>\r\n<p data-start=\"1889\" data-end=\"2265\">Un clip sobre, habit&eacute;, centr&eacute; sur l&rsquo;essentiel.<br data-start=\"1935\" data-end=\"1938\">Capt&eacute; par l&rsquo;&oelig;il aiguis&eacute; de Milot Joseph et R&eacute;mi Hermoso, et port&eacute; par une r&eacute;alisation sobre et puissante sign&eacute;e <strong data-start=\"2050\" data-end=\"2062\">KONEKTEM</strong>, le clip de <strong data-start=\"2075\" data-end=\"2087\">SEZON SA</strong> frappe d&rsquo;embl&eacute;e par sa sinc&eacute;rit&eacute; brute. Pas d&rsquo;effets superflus. Aucun d&eacute;cor ostentatoire. Le langage de l&rsquo;image se veut d&eacute;pouill&eacute;, presque nu, pour mieux laisser parler l&rsquo;&acirc;me.</p>\r\n<p data-start=\"2267\" data-end=\"2404\">Chaque regard, chaque silence devient un plan fort, charg&eacute; de tension ou d&rsquo;apaisement. Les mots, eux, tombent comme des coups de gr&acirc;ce.</p>\r\n<p data-start=\"2406\" data-end=\"2685\">Un chant qui veille sur ceux qui n&rsquo;ont plus la force de prier.<br data-start=\"2468\" data-end=\"2471\">Pour Garby Mesidor, la louange n&rsquo;est pas un style, c&rsquo;est une mission. Et pour Sherline Dalberis, ce chant est un pont. Entre le ciel et ceux qui n&rsquo;arrivent plus &agrave; prier. Entre Dieu et ceux qui se croient oubli&eacute;s.</p>\r\n<p data-start=\"2687\" data-end=\"2925\"><strong data-start=\"2687\" data-end=\"2699\">SEZON SA</strong>, c&rsquo;est une voix lev&eacute;e au nom de toute une g&eacute;n&eacute;ration qui cherche des r&eacute;ponses. Une g&eacute;n&eacute;ration qui, peut-&ecirc;tre, n&rsquo;a pas besoin de nouveaux discours, mais d&rsquo;une simple v&eacute;rit&eacute; chant&eacute;e avec foi : Dieu n&rsquo;a jamais quitt&eacute; la pi&egrave;ce.</p>\r\n<p data-start=\"2927\" data-end=\"3090\">Une chanson, une semence.<br data-start=\"2952\" data-end=\"2955\">La sortie officielle du clip est pour tr&egrave;s bient&ocirc;t. Et si cette chanson n&rsquo;avait qu&rsquo;un seul but : raviver une foi, m&ecirc;me toute petite ?</p>\r\n<p data-start=\"3092\" data-end=\"3236\">Comme le dit Sherline, en toute simplicit&eacute; :<br data-start=\"3136\" data-end=\"3139\">&laquo; Si cette chanson peut toucher ne serait-ce qu&rsquo;une seule vie, elle aura accompli sa mission. &raquo;</p>\r\n<p data-start=\"3238\" data-end=\"3331\">Alors pr&eacute;parez vos c&oelig;urs. <strong data-start=\"3264\" data-end=\"3276\">SEZON SA</strong> arrive. Et cette saison pourrait bien &ecirc;tre la v&ocirc;tre.</p>\r\n<p data-start=\"3333\" data-end=\"3406\"><strong data-start=\"3333\" data-end=\"3372\">R&eacute;daction : Ronalson Bryan Blanfort</strong><br data-start=\"3372\" data-end=\"3375\">#konektem <br>#ToutKoteNenp&ograve;tKil&egrave;</p>', '2025-11-29 02:38:31', '2026-06-24 03:53:43', 164, 0, 6, 8, 'Sherline Dalberis & Garby Mesidor', NULL, NULL, '2025-11-28', NULL, '454747ebef8b444798b1d77b5782e4bc');

-- --------------------------------------------------------

--
-- Структура таблицы `livestream`
--

CREATE TABLE `livestream` (
  `id` bigint UNSIGNED NOT NULL,
  `stream_date` datetime NOT NULL,
  `stream_title` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `stream_key` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `connected` bigint UNSIGNED DEFAULT NULL,
  `owner` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT 'admin@konektem.net'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `livestream`
--

INSERT INTO `livestream` (`id`, `stream_date`, `stream_title`, `stream_key`, `connected`, `owner`) VALUES
(3, '2025-11-15 21:30:00', 'Stream test', 'ywYWI7xADZDdAW69', NULL, 'konektem'),
(4, '2026-06-14 02:15:18', 'Test', 'd29109ed555dba9c4ba9', NULL, 'konektem');

-- --------------------------------------------------------

--
-- Структура таблицы `livestream_viewers`
--

CREATE TABLE `livestream_viewers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_ids` text,
  `stream_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `mainpagecontent`
--

CREATE TABLE `mainpagecontent` (
  `id` bigint NOT NULL,
  `newsSlide` bigint UNSIGNED DEFAULT NULL,
  `fadeNews` bigint UNSIGNED DEFAULT NULL,
  `music` bigint UNSIGNED DEFAULT NULL,
  `events` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `mainpagecontent`
--

INSERT INTO `mainpagecontent` (`id`, `newsSlide`, `fadeNews`, `music`, `events`) VALUES
(5, 32, 14, 102, 8),
(6, 29, 59, 24, NULL),
(14, 33, 5, 17, NULL),
(15, 34, 60, 23, NULL),
(16, 27, 56, 20, NULL),
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
(37, 40, NULL, NULL, NULL),
(38, 61, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `music`
--

CREATE TABLE `music` (
  `id` bigint UNSIGNED NOT NULL,
  `artist_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `likes` int DEFAULT '0',
  `downloads` int DEFAULT '0',
  `plays` int DEFAULT '0',
  `shares` int DEFAULT '0',
  `order_no` int DEFAULT '0',
  `owner` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT 'admin@konektem.net',
  `genre` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `album_id` bigint UNSIGNED DEFAULT NULL,
  `audio_url` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `track_number` int DEFAULT NULL,
  `image_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `music`
--

INSERT INTO `music` (`id`, `artist_id`, `title`, `url`, `mime_type`, `likes`, `downloads`, `plays`, `shares`, `order_no`, `owner`, `genre`, `album_id`, `audio_url`, `track_number`, `image_id`) VALUES
(17, 9, 'PaPa Kenbe m', '/admin/controllers/../uploads/music/690d3d530791a.mp3', 'audio/mpeg', 12, 7, 39, 6, 2, 'admin@konektem.net', 'Afro Beats', NULL, '/admin/controllers/../uploads/music/690d3d530791a.mp3', NULL, 96),
(18, 10, 'Abba', '/admin/controllers/../uploads/music/690d41b68d2fd.mp3', 'audio/mpeg', 9, 16, 77, 6, 1, 'admin@konektem.net', 'Worship', NULL, '/admin/controllers/../uploads/music/690d41b68d2fd.mp3', NULL, 97),
(19, 11, 'Se nan ou Senyè mwen kapab viv', '/admin/controllers/../uploads/music/690d42d856d84.mp3', 'audio/mpeg', 6, 3, 22, 0, 6, 'admin@konektem.net', 'Jazz', NULL, '/admin/controllers/../uploads/music/690d42d856d84.mp3', NULL, 98),
(20, 12, 'Pi Bon Zanmi', '/admin/controllers/../uploads/music/692374e9b6b5a.mp3', 'audio/mpeg', 4, 8, 29, 2, 4, 'admin@konektem.net', 'gospel', NULL, '/admin/controllers/../uploads/music/692374e9b6b5a.mp3', NULL, 135),
(22, 14, 'Sa se lèm respire', '/admin/controllers/../uploads/music/69237e1b60754.mp3', 'audio/mpeg', 7, 4, 29, 6, 3, 'admin@konektem.net', 'Worship praisr', NULL, '/admin/controllers/../uploads/music/69237e1b60754.mp3', NULL, 137),
(24, 16, 'Un Ami Sûr', '/admin/controllers/../uploads/music/692380009583a.mp3', 'audio/mpeg', 6, 14, 57, 6, 5, 'admin@konektem.net', 'Pop', NULL, '/admin/controllers/../uploads/music/692380009583a.mp3', NULL, 139),
(32, 5, 'Hyg', '/admin/controllers/../uploads/music/694d7391454dd.mp3', 'video/mp4', 1, 1, 1, 1, 32, 'Jorbyart', NULL, NULL, '/admin/controllers/../uploads/music/694d7391454dd.mp3', NULL, 174),
(34, 17, 'Sezon sa', '/admin/controllers/../uploads/music/6954aeaf83e17.mp3', 'audio/mpeg', 1, 0, 2, 0, 34, 'JoFlungBouzy', NULL, NULL, '/admin/controllers/../uploads/music/6954aeaf83e17.mp3', NULL, 189),
(35, 18, 'KÃ²mandan ', '/admin/controllers/../uploads/music/695ab9d3220c5.mp3', 'audio/mpeg', 1, 2, 0, 0, 0, 'admin@konektem.net', 'gospel', NULL, '/admin/controllers/../uploads/music/695ab9d3220c5.mp3', NULL, 199),
(36, 18, 'KÃ²mandan ', '/admin/controllers/../uploads/music/695dd992c94a0.mp3', 'audio/mpeg', 0, 0, 0, 0, 36, 'AbdullahMode', NULL, NULL, '/admin/controllers/../uploads/music/695dd992c94a0.mp3', NULL, 203),
(37, 19, 'M PAKA ECHWE', '/admin/controllers/../uploads/music/69613e86e425f.mp3', 'audio/mpeg', 0, 0, 0, 0, 37, 'AbdullahMode', NULL, NULL, '/admin/controllers/../uploads/music/69613e86e425f.mp3', NULL, 204),
(38, 2, 'beautiful', '/admin/controllers/../uploads/music/6962b80d8824c.mp3', 'audio/mpeg', 0, 0, 0, 0, 38, 'zwelakhemaseko02_konektem', NULL, NULL, '/admin/controllers/../uploads/music/6962b80d8824c.mp3', NULL, 205),
(39, 1, 'Rap God', '/admin/controllers/../uploads/music/69934fb15ad08.mp3', 'audio/mpeg', 1, 0, 0, 0, 39, 'masekozw', 'Rap', NULL, '/admin/controllers/../uploads/music/69934fb15ad08.mp3', NULL, 264),
(40, 20, 'Mwen pa pou kont mwen', '/admin/controllers/../uploads/music/699376607044c.mp3', 'audio/mpeg', 3, 0, 0, 4, 1, 'admin@konektem.net', 'Worship', NULL, '/admin/controllers/../uploads/music/699376607044c.mp3', NULL, 265),
(43, 23, 'Siwo myèl', '/admin/controllers/../uploads/music/6993f4426adef.mp3', 'audio/mpeg', 4, 2, 0, 3, 1, 'admin@konektem.net', 'Konpa', NULL, '/admin/controllers/../uploads/music/6993f4426adef.mp3', NULL, 268),
(44, 24, 'Wa sonje nou', '/admin/controllers/../uploads/music/699478a75f1e1.mp3', 'audio/mpeg', 1, 0, 0, 0, 44, 'MINISTREVALEURDELHOMMETV', 'Worswip', NULL, '/admin/controllers/../uploads/music/699478a75f1e1.mp3', NULL, 269),
(52, 2, 'Sa se lèm respire', NULL, 'audio/mpeg', 0, 0, 0, 0, 0, 'zvelake', 'rap', 11, NULL, 1, NULL),
(58, 26, 'Kanpe goumen', '/admin/controllers/../uploads/music/69d4f65f7648d.mp3', 'audio/mpeg', 0, 0, 0, 0, 58, 'wewe234rtqwe4', 'Compas', NULL, '/admin/controllers/../uploads/music/69d4f65f7648d.mp3', NULL, 320),
(59, 25, '4- WI NOU KAPAB .mp3', '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebc731.mp3', 'audio/mpeg', 0, 0, 0, 0, 59, 'masekozw', 'Gospel', NULL, '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebc731.mp3', NULL, NULL),
(60, 25, '5- KOTE M TE YE A TE LWEN .mp3', '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebd29b.mp3', 'audio/mpeg', 0, 0, 0, 0, 60, 'masekozw', 'Gospel', NULL, '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebd29b.mp3', 5, NULL),
(61, 25, '6- SORRY MANMAN .mp3', '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebde8b.mp3', 'audio/mpeg', 0, 0, 0, 0, 61, 'masekozw', 'Gospel', NULL, '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebde8b.mp3', 6, NULL),
(62, 25, '7- SAW AP TANN MASTER .mp3', '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebe80c.mp3', 'audio/mpeg', 0, 0, 0, 0, 62, 'masekozw', 'Gospel', NULL, '/konektem/config/../uploads/masekozw_album_VwayopatandeyoMP3/69d4febebe80c.mp3', 7, NULL),
(63, 27, '1- VWA YO PA TANDE YO .mp3', '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45ac2c.mp3', 'audio/mpeg', 0, 0, 0, 0, 0, 'masekozw', 'Evanjelik', 14, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45ac2c.mp3', 1, NULL),
(64, 27, '2- GADON GRAS .mp3', '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45b546.mp3', 'audio/mpeg', 0, 0, 0, 0, 0, 'masekozw', 'Evanjelik', 14, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45b546.mp3', 2, NULL),
(65, 27, '3- PAPA KENBE M .mp3', '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45bd29.mp3', 'audio/mpeg', 0, 0, 0, 0, 0, 'masekozw', 'Evanjelik', 14, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45bd29.mp3', 3, NULL),
(66, 27, '4- WI NOU KAPAB .mp3', '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45c505.mp3', 'audio/mpeg', 0, 0, 0, 0, 0, 'masekozw', 'Evanjelik', 14, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45c505.mp3', 4, NULL),
(67, 27, '5- KOTE M TE YE A TE LWEN .mp3', '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d415e.mp3', 'audio/mpeg', 0, 0, 0, 0, 67, 'masekozw', 'Evanjelik', 14, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d415e.mp3', 5, NULL),
(68, 27, '6- SORRY MANMAN .mp3', '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d4c5c.mp3', 'audio/mpeg', 0, 0, 0, 0, 68, 'masekozw', 'Evanjelik', 14, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d4c5c.mp3', 6, NULL),
(69, 27, '7- SAW AP TANN MASTER .mp3', '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d5877.mp3', 'audio/mpeg', 0, 0, 0, 0, 69, 'masekozw', 'Evanjelik', 14, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d5877.mp3', 7, NULL),
(70, 27, '8- MIRAK .mp3', '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d618b.mp3', 'audio/mpeg', 0, 0, 0, 0, 70, 'masekozw', 'Evanjelik', 14, '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d50988d618b.mp3', 8, NULL),
(71, 27, '9- PRIYE .mp3', '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d292098d.mp3', 'audio/mpeg', 0, 0, 0, 0, 71, 'Jorby', 'Evanjelik', 14, '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d292098d.mp3', 9, NULL),
(72, 27, '10- MEN DYAMAN .mp3', '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d2921bb6.mp3', 'audio/mpeg', 0, 0, 0, 0, 72, 'Jorby', 'Evanjelik', 14, '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d2921bb6.mp3', 10, NULL),
(73, 27, '11- EWO .mp3', '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d29228ff.mp3', 'audio/mpeg', 0, 0, 0, 0, 73, 'Jorby', 'Evanjelik', 14, '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d29228ff.mp3', 11, NULL),
(74, 27, '12- POZE W .mp3', '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d29235cc.mp3', 'audio/mpeg', 0, 0, 0, 0, 74, 'Jorby', 'Evanjelik', 14, '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50d29235cc.mp3', 12, NULL),
(75, 27, 'INTRO .mp3', '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50dd5ed893.mp3', 'audio/mpeg', 0, 0, 0, 0, 75, 'Jorby', 'Evanjelik', 14, '/konektem/config/../uploads/Jorby_album_Vwayopatandeyo/69d50dd5ed893.mp3', 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `image_id` bigint UNSIGNED DEFAULT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `headline` text COLLATE utf8mb4_unicode_ci,
  `content` text COLLATE utf8mb4_unicode_ci,
  `reads` int NOT NULL DEFAULT '0',
  `likes` int NOT NULL DEFAULT '0',
  `shares` int NOT NULL DEFAULT '0',
  `featured_image_ids` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` date DEFAULT NULL,
  `title_hash` char(32) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (md5(coalesce(`title`,_utf8mb4''))) VIRTUAL,
  `author_id` bigint UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `image_id`, `title`, `created_at`, `category`, `headline`, `content`, `reads`, `likes`, `shares`, `featured_image_ids`, `published_at`, `author_id`, `updated_at`) VALUES
(5, 54, 'SPBPU - Peter The Great University With African Leaders To Launch Educational Programs in Africa', '2025-09-07 21:00:00', 'sports', 'Perhaps, it was about the cooperation of the Peter the Great St. Petersburg Polytechnic University (SPbPU) with African countries in the field of education.', 'In January 2025, it was reported that at the 12th meeting of the mixed intergovernmental Russian-Algerian commission, agreements on scientific exchanges and joint educational programs were signed between SPbPU and a number of Algerian universities.\r\n\r\nSPbPU is the coordinator of the consortium of Russian and African universities and research organizations \"Russian-African Network University\" (RAFU). The consortium implements, in particular, short-term educational programs for African citizens.                                                                                                                                        ', 1, 2, 1, NULL, '2025-09-08', NULL, NULL),
(14, 70, 'Tanpèt Twopikal Melissa menase divès Peyi nan Karayib la.', '2025-10-25 21:00:00', 'sports', 'Sant meteyowolojik Etazini ak Ewòp yo ap swiv ak anpil atansyon yon sistèm presyon ki ba k ap devlope nan lanmè Karayib la. Fenomèn sa a, yo rele \"Invest 98L\", t a kapab vin tounen \"tanpèt twopikal Melissa\" nan kèk èdtan, dapre \"National Hurricane Center (NHC)\".', 'Sant meteyowolojik Etazini ak Ewòp yo ap swiv ak anpil atansyon yon sistèm presyon ki ba k ap devlope nan lanmè Karayib la. Fenomèn sa a, yo rele \"Invest 98L\", t a kapab vin tounen \"tanpèt twopikal Melissa\" nan kèk èdtan, dapre \"National Hurricane Center (NHC)\".\r\n\r\n\r\nPou kounye a, gwo nyaj ak loraj ap ogmante pandan sistèm nan ap deplase direksyon nòdwès. NHC estime gen 100 % chans pou li devlope an siklòn, sa ki vle di risk la rive nan pi wo nivo. Menm si sant tanpèt la poko byen fòme, espesyalis yo avèti  kondisyon tanperati lanmè a ak van yo favorab anpil pou li vin pi fò byen vit.\r\n\r\n\r\nAyiti nan zòn ki anba menas Daprè meteyowològ ameriken Jeff Berardelli (WFLA / CBS News), li avanse pou fè konnen : Ayiti, Repiblik Dominikèn ak Bahamas rete nan zòn ki plis ekspoze si sistèm nan chanje direksyon epi monte pi pre direksyon nò nan jou k ap vini yo. Otorite nan rejyon an deja ankouraje popilasyon an pou yo rete vijilan, swiv bilten meteyo yo, epi pare plan ijans yo. Si tanpèt la fòme vre, li ka pote anpil lapli, van fò, ak risk inondasyon sou plizyè peyi nan Karayib la.\r\n\r\n\r\n#esansyèl\r\n\r\n#meteyo\r\n\r\n#konektem\r\n\r\n#toutkotenenpotkilè                                ', 0, 0, 0, NULL, '2025-10-26', NULL, NULL),
(15, 73, ' Kyria Salina Romusca ap reprezante Ayiti nan Miss Tourism World 2025 nan peyi Lachin.', '2025-10-27 21:00:00', 'sports', 'Kyria Salina Romusca, ki kouwonnen \"Miss Tourism World Haïti 2025\" depi 22 dawout, yon inisyativ Organisation Miss Haïti Caraïbes, li pral pote drapo', 'Miss Tourism World se yon platfòm entènasyonal ki rasanble plizyè peyi pou fè pwomosyon touris ak divèsite kiltirèl. Ane sa a, jèn modèl ayisyèn sila a, ki soti Latibonit, ap vin yon anbasadè pou Ayiti, nan objektif pou fè mond lan dekouvri richès atistik, mizikal, gastronomik ak ospitalite pèp ayisyen an.\r\n\r\n\r\nPou Kyria, patisipasyon sa a se plis pase yon kouwòn. Etidyan nan odontoloji ak kominikasyon, li mete lafwa li kòm pilye nan pakou l, pandan l ap pwone anmenm tan: konesans jeneral, ekspresyon sou sèn, bote fizik ak fòs espirityèl li. Li deklare : « Reprezante Ayiti se pote fyète, bèlte ak rezistans yon pèp ki merite klere pi plis toujou. »\r\n\r\n\r\n#esansyèl\r\n\r\n#aktyalite\r\n\r\n#konektem\r\n\r\n#toutkotenenpotkilè                ', 0, 1, 1, NULL, '2025-10-28', NULL, NULL),
(16, 74, 'ULCC fè bilan sou deklarasyon patrimwàn ajan piblik yo.', '2025-10-27 21:00:00', 'sports', 'Jodi premye septanm 2025 lan, L’Unité de Lutte Contre la Corruption (ULCC), fè konnen li konstate gwo avanse k ap fèt nan deklarasyon patrimwàn ajan piblik yo, jan sa mande dapre lwa 12 fevriye 2008 la. Nan yon nòt ki pibliye, enstitisyon an rapòte tout manm Konsèy Prezidansyèl Transisyon (KPT) deja ranpli obligasyon yo. Mete sou sa, 90% minis ak sekretè Deta deja depoze deklarasyon yo, malgre toujou gen 2 minis ak 2 sekretè Deta ki poko konfòme yo, malgre rapèl ki te fèt yo.', 'Pou rive nan rezilta sa yo, ULCC mete plizyè estrateji sou pye: jounen deklarasyon espesyal, piblikasyon yon gid pratik, kanpay sansibilizasyon,  epi voye 120 dosye nan lajistis pou defo deklarasyon. Enstitisyon an salye tou kontribisyon òganizasyon sosyete sivil yo nan pwosesis sila.\r\n\r\n\r\nRechèch ki te fèt an 2022 sou lwa deklarasyon patrimwàn nan pèmèt yo lanse plizyè refòm avèk sipò patnè teknik ak finansye, espesyalman pou ranfòse sistèm SYDEP III. Nan twa dènye ane yo, kantite deklarasyon yo ogmante pa 535%, yon pwogrè ULCC atribiye ak aksyon prevantif ak represif li yo.\r\n\r\n\r\nMalgre tout efò sa yo, gen toujou gwo fonksyonè ki evite respekte lwa a. Sa montre sistèm nan toujou gen limit li, e batay pou transparans total nan sektè piblik la rete yon defi.\r\n\r\n\r\n#esansyèl\r\n\r\n#aktyalite\r\n\r\n#konektem\r\n\r\n#toutkotenenpotkilè\r\n\r\nAktyalite        ', 0, 1, 4, NULL, '2025-10-28', NULL, NULL),
(18, 83, 'Melissa en Haïti : 24 morts, 18 disparus et des blessés.', '2025-10-28 21:00:00', 'sports', 'Haïti enregistre un lourd bilan provoqué par le passage de l’ouragan Melissa.', 'Haïti enregistre un lourd bilan provoqué par le passage de l’ouragan Melissa. Selon le bilan provisoire établi a la date du 29 octobre 2025 par les responsables de la protection civile on dénombre 24 décès, 18 personnes portées disparues et des blessés.\r\nC\'est la région de Petit-Goâve qui déplore le bilan le plus lourd. Des inondations provoquées par la rivière La Digue ont entraîné 20 morts et 18 disparus.         ', 0, 1, 10, NULL, '2025-10-29', NULL, NULL),
(19, 84, 'Palmarès Mizik/Videyo Evanjelik – Oktòb 2025', '2025-10-30 21:00:00', 'sports', 'Chak mwa, nan ane a, make ak bèl zèv atistik ki pote enspirasyon nan mond mizik evanjelik la. \r\nPalmarès Mizik/Videyo Evanjelik – oktòb 2025 ap prepare pou pibliye Premye novanm 2025.', 'Menm jan a chak edisyon, seleksyon sa pral mete an valè pwojè ki make milye evanjelik la, ak mizik ki pote mesaj favè, kouraj, lafwa.... ki itilize nan chak moman adorasyon. Se yon moman kote talan, lafwa ak kreyativite kontinye rankontre pou glorifye non Bondye epi enspire jenerasyon aktyèl la.\r\n\r\nKONEKTEM envite tout moun rete branche, pou dekouvri nouvo palmarès la, pandan pral gade videyo yo, tande ak pataje  mizik yo, epi soutni chak atis k ap sèvi ak mizik pou pote limyè ak espwa nan kè chak moun.\r\n\r\n👉 Randevou 1e oktòb 2025 — Palmarès la ap pare, pa rate l!        ', 0, 0, 0, NULL, '2025-10-31', NULL, NULL),
(20, 85, 'Toune pwomosyonè albòm \"Vwa Yo Pa tande yo\"', '2025-11-02 21:00:00', 'entertainment', 'Pandan n ap tann dat 16 novanm 2025 lan, toune « Vwa yo pa tande yo » kontinye avanse sou wout li.', 'Dimanch 2 novanm 2025 lan, gwoup Zòn Pa Fè Moun Band, vizite Tabernacle El Elyon ak Legliz Batis Delmas Béthel.\r\n\r\nVizit sa yo antre nan kad preparasyon pou lansman twazyèm albòm timoun yo, yon pwojè ki pote yon mesaj ki chaje ak espwa : fè tande vwa moun yo pa tande yo, epi reba w espwa ak sila yo ki dekouraje « Vwa Yo Pa Tande Yo ».\r\n\r\nKat pou konsè a deja disponib nan tout pwen vant ofisyèl yo.\r\n\r\nChak moun ki achte yon kat ap patisipe dirèkteman nan sipòte Fondasyon Fr. Luckson Zòn Pa Fè Moun, ki kontinye misyon li pou fòme, akonpaye ak ankouraje timoun yo atravè edikasyon, mizik ak lafwa.                ', 0, 0, 0, NULL, '2025-11-03', NULL, NULL),
(21, 114, 'Fatima Bosch : Miss Univers 2025', '2025-11-20 21:00:00', 'sports', 'Nan lannwit 20 pou rive 21 novanm 2025, Fatima Bosch, reprezantan Peyi Meksik, eli Miss Univers 2025 pandan 74yèm edisyon konkou a ki te fèt nan Impact Arena, Pak Kret, Thailand.', 'Nan lannwit 20 pou rive 21 novanm 2025, Fatima Bosch, reprezantan Peyi Meksik, eli Miss Univers 2025 pandan 74yèm edisyon konkou a ki te fèt nan Impact Arena, Pak Kret, Thailand. Li ranplase Victoria Kjær Theilvig (Denmark) e vin katriyèm Meksikèn ki genyen tit sa a.\r\n\r\nAvèk 25 lane, Bosch se modèl, kreyatè mòd dirab, ak aktivis sosyal. Viktwa li vini apre yon kontwovès piblik ak òganizatè konkou a, kote li te reponn avèk dinite, mete aksan sou respè ak fòs fanm.\r\n\r\nEdisyon 2025 lan te make tou pa eliminasyon Melissa Queenie Sapini, reprezantan Ayiti, anvan lis Top 30 la. Viktwa Bosch simbolize rezistans, diyite, ak detèminasyon nan yon konkou mondyal.                ', 0, 0, 0, NULL, '2025-11-21', NULL, NULL),
(22, 115, ' #OuvèPeyiA : Yon kanpay Grenadye yo lanse.', '2025-11-20 21:00:00', 'sports', 'Jou ki te madi 18 Novanm nan, apre gwo viktwa Grenadye yo, ki kalifye ayiti pou mondyal 2026 la, yon inisyativ te lanse \"Hashtag #OuvèPeyiA\". Kanpay sila te pran nesans li nan bis ki t ap mennen jwè seleksyon nasyonal Ayiti yo pou rive nan otèl la.', 'Jou ki te madi 18 Novanm nan, apre gwo viktwa Grenadye yo, ki kalifye Ayiti pou mondyal 2026 la, yon inisyativ te lanse \"Hashtag #OuvèPeyiA\". Kanpay sila te pran nesans li nan bis ki t ap mennen jwè seleksyon nasyonal Ayiti a pou rive nan otèl la. Sou ensistans defansè Ricardo Adé, tout jwè yo te mete vwa yo ansanm pou repete menm mesaj la : « #OuvèPeyiA ». Plis pase yon senp slogan, ekspresyon sa a se kri kè yon gwoup jèn gason ki sot bay tout enèji yo ak tout nanm yo pou fè Ayiti kalifye pou Mondyal 2026 la, aprè 52 lane san prezans nou nan pi gwo konpetisyon foutbòl mondyal la.\r\n\r\nNazon, Danley, Adé… pou n site kèk nan non sa yo sèlman, ensiste sou nesesite pou tout aktè nan sosyete a pran responsabilite yo pou retabli yon klima lapè nan peyi a. Pou yo, viktwa sa a dwe sèvi kòm yon apèl pou konsyans kolektif. “Nou bezwen vin nan peyi nou. Nou bezwen jwe nan Stad Sylvio Cator. Tout moun ki gen responsablite nan peyi a, pran responsablite nou. Ouvè wout yo, ouvè ayopò yo, kite moun yo viv,” se mesaj klè Grenadye yo voye bay tout otorite ak tout moun ki gen enfliyans sou sitiyasyon aktyèl la.\r\n\r\nJodi a, kalifikasyon sa a pa senpman yon rezilta espòtif ; li tounen yon senbòl espwa pou tout yon pèp k ap lite chak jou pou l respire, pou l sikile, pou l viv ak diyite. Hashtag #OuvèPeyiA vin tounen vwa jèn yo, vwa espò a, vwa nasyon an, ki mande yon bagay senp men esansyèl : Lapè, louvri peyi a, epi kite lavi reprann.        ', 0, 0, 0, NULL, '2025-11-21', NULL, NULL),
(23, 116, 'Deedson Louicius demanti rimè sou foto ak Lionel Messi an.', '2025-11-20 21:00:00', 'sports', 'Depi kèk tan, yon enfòmasyon ki t ap sikile sou rezo sosyal yo fè kwè jwè entènasyonal ayisyen Deedson Louicius se t a', '➡️ Depi kèk tan, yon enfòmasyon ki t ap sikile sou rezo sosyal yo fè kwè jwè entènasyonal ayisyen Deedson Louicius se t a timoun ki parèt sou foto Lionel Messi te pran lè li te vizite Ayiti an 2010. \r\n\r\nDeedson Louicius konfime pèsonèlman bay media Haiti-Tempo se yon rimè ki pa gen okenn rapò ak li. Li fè konnen istwa sa a pa konsène l ditou, li mande pou yo envite simaye enfòmasyon ki pa verifye.        ', 0, 0, 0, NULL, '2025-11-21', NULL, NULL),
(24, 140, 'Gesny Pierre Louis chanpyon nan konkou patinaj nan peyi Ekwatè.', '2025-11-23 21:00:00', 'sports', 'Pandan konpetisyon patinaj entènasyonal ki te fèt nan peyi Ekwatè, Ayiti te manke pèdi posiblite  pou l te monte sou podium nan, akoz yon vis ki te retire nan paten Gesny Pierre Louis.', 'Pandan konpetisyon patinaj entènasyonal ki te fèt nan peyi Ekwatè, Ayiti te manke pèdi posiblite  pou l te monte sou podium nan, akoz yon vis ki te retire nan paten Gesny Pierre Louis. Se te yon moman tansyon ak laperèz pou piblik la, men detèminasyon, konsantrasyon ak lanmou pou drapo a pa t kite l abandone. Malgre gwo difikilte sila yo, Gesny kontinye pèfòmans, yon jès kouraj ki te leve tout sal la kanpe pou aplodi l.\r\n\r\nSe konsa, kont tout atant, Ayiti rive pran premye plas la nan konpetisyon an, epi ranpòte meday lò a, drapo ble e wouj la flote byen wo sou tè Ekwatè. Viktwa sa a se pa sèlman yon siksè espòtif, men tou yon prèv klè pèp ayisyen an pa janm bay legen, menm lè sitiyasyon an sanble pèdi. Gesny Pierre Louis vin tounen yon senbòl fyète, kouraj ak rezistans pou tout yon jenerasyon.\r\n\r\nNan deklarasyon li, atlèt la eksprime gwo fyète l dèske li te kapab reprezante Ayiti nan gwo bout konpetisyon entènasyonal sa a, ki konte kòm yon etap enpòtan pou evalyasyon Mondyal la ki pral fèt nan Singapour, soti premye pou rive 4 desanm 2025. Li remèsye Viajespam ansanm ak Anbasad Ayiti nan Chili ki te rann vwayaj la posib, san bliye  inlinefreestyleecuador, quitopatina, @roxxy.1512 pou foto yo, ak tout patnè ki pa janm sispann kwè nan talan ayisyen an.        ', 0, 0, 0, NULL, '2025-11-24', NULL, NULL),
(25, 141, 'Gesny Pierre Louis chanpyon nan konkou patinaj nan peyi Ekwatè.', '2025-11-23 21:00:00', 'sports', 'Pandan konpetisyon patinaj entènasyonal ki te fèt nan peyi Ekwatè, Ayiti te manke pèdi posiblite  pou l te monte sou podium nan, akoz yon vis ki te retire nan paten Gesny Pierre Louis.', 'Pandan konpetisyon patinaj entènasyonal ki te fèt nan peyi Ekwatè, Ayiti te manke pèdi posiblite  pou l te monte sou podium nan, akoz yon vis ki te retire nan paten Gesny Pierre Louis. Se te yon moman tansyon ak laperèz pou piblik la, men detèminasyon, konsantrasyon ak lanmou pou drapo a pa t kite l abandone. Malgre gwo difikilte sila yo, Gesny kontinye pèfòmans, yon jès kouraj ki te leve tout sal la kanpe pou aplodi l.\r\n\r\nSe konsa, kont tout atant, Ayiti rive pran premye plas la nan konpetisyon an, epi ranpòte meday lò a, drapo ble e wouj la flote byen wo sou tè Ekwatè. Viktwa sa a se pa sèlman yon siksè espòtif, men tou yon prèv klè pèp ayisyen an pa janm bay legen, menm lè sitiyasyon an sanble pèdi. Gesny Pierre Louis vin tounen yon senbòl fyète, kouraj ak rezistans pou tout yon jenerasyon.\r\n\r\nNan deklarasyon li, atlèt la eksprime gwo fyète l dèske li te kapab reprezante Ayiti nan gwo bout konpetisyon entènasyonal sa a, ki konte kòm yon etap enpòtan pou evalyasyon Mondyal la ki pral fèt nan Singapour, soti premye pou rive 4 desanm 2025. Li remèsye Viajespam ansanm ak Anbasad Ayiti nan Chili ki te rann vwayaj la posib, san bliye  inlinefreestyleecuador, quitopatina, @roxxy.1512 pou foto yo, ak tout patnè ki pa janm sispann kwè nan talan ayisyen an.        ', 0, 0, 0, NULL, '2025-11-24', NULL, NULL),
(26, 142, 'DCPJ Lanse Yon sit entènèt pou idantifye Bandi ak Evade prizon.', '2025-11-23 21:00:00', 'technology', 'Direksyon Santral Polis Jidisyè a (DCPJ) anonse, jodi lendi 24 novanm nan , lansman ofisyèl sit entènèt li, kote popilasyon an ka jwenn enfòmasyon sou bandi ame ak moun ki chape prizon, depi 2004 pou rive jounen jodi a.', 'Direksyon Santral Polis Jidisyè a (DCPJ) anonse, jodi lendi 24 novanm nan , lansman ofisyèl sit entènèt li, kote popilasyon an ka jwenn enfòmasyon sou bandi ame ak moun ki chape prizon, depi 2004 pou rive jounen jodi a. Inisyativ sa a antre nan kad jefò lapolis ap mennen pou l konbat ensekirite a k ap vale teren nan plizyè zòn nan peyi a.\r\n\r\nNan yon kontèks kote gang yo ap fè lalwa nan plizyè rejyon, Lapolis deside konte sou teknoloji pou l pi byen enfòme sitwayen yo. Dapre otorite polisye yo, plizyè non moun y ap chèche, pami yo gen moun ki konsidere kòm danjere, disponib kounye a sou platfòm nan. Konsa, si yon moun antre non yon sispèk nan bwat rechèch la, li ka jwenn enfòmasyon ki konsène moun sa a, dapre done ki anrejistre yo.\r\n\r\nAtravè demach sa a, DCPJ vle modènize metòd rechèch li yo epi ofri popilasyon an yon zouti serye pou idantifye moun ki enplike nan aktivite kriminèl, dapre sa lapolis fè konnen. Anplis, sit la pèmèt tou pou moun konsilte enfòmasyon ki gen rapò ak kazye jidisyè, yon dokiman enpòtan nan plizyè pwosedi administratif, sitou pou dosye vwayaj.\r\n\r\nPou anpil sitwayen sou rezo sosyal yo, lansman sit sa a se yon bon avansman nan batay kont ensekirite a. Antretan, lapolis repete li pral itilize tout mwayen nesesè pou fasilite arestasyon moun ki enplike nan zak kriminèl epi retabli plis sekirite nan peyi a.\r\n', 0, 0, 0, NULL, '2025-11-24', NULL, NULL),
(27, 151, 'FAES distribiye 1500 kit Alimantè bay Fanmi ki nan Nesesite yo nan Maïssade.', '2025-11-25 21:00:00', 'sports', 'Nan kòmansman semèn sa a, Fon Asistans Ekonomik ak Sosyal (FAES) te renouvle angajman li bò kote popilasyon an, espesyalman nan depatman Sant, kote li te fè yon gwo operasyon distribisyon nan komin Maïssade.', 'Nan kòmansman semèn sa a, Fon Asistans Ekonomik ak Sosyal (FAES) te renouvle angajman li bò kote popilasyon an, espesyalman nan depatman Sant, kote li te fè yon gwo operasyon distribisyon nan komin Maïssade. Anviwon 1 500 kit alimantè te remèt bay moun ki deplase ansanm ak fanmi ki nan gwo bezwen yo. Sou direksyon Jean Sadrack Jean François, Direktè Lit kont Povrete nan FAES, yon delegasyon te deplase sou teren pou suiv operasyon an, tande plenyen ak bezwen sitwayen yo, epi asire èd la rive jwenn moun ki gen plis ijans yo.\r\n\r\nInisyativ sa a pa t sèlman yon kesyon distribisyon  manje. Li te vin tounen yon mesaj solidarite ak prezans, nan yon kontèks kote anpil fanmi ap goumen chak jou pou kenbe diyite yo fas ak lavi chè. FAES, ak soutyen Leta ak patnè li yo, vle montre li pa lage sila nan sosyete a ki pi fèb yo, men l ap kontinye apiye yo pou yo ka jwenn plis estabilite ak espwa pou lavni.\r\n\r\nAksyon sa a konfime ankò volonte enstitisyon an pou l rete toupre popilasyon an, aji ak disiplin, konpasyon ak respè pou lavi moun, nenpòt kote bezwen imanitè nesesite.        ', 0, 0, 0, NULL, '2025-11-26', NULL, NULL),
(28, 152, 'USCIS Anonse yo koupe TPS pou Ayisyen.', '2025-11-25 21:00:00', 'sports', 'Nan yon desizyon ki  pibliye jodi 26 novanm 2025 lan, DHS anonse  estati pwoteksyon tanporè (Temporary Protected Status, TPS) pou Ayiti ap pran fen, paske dapre yo peyi a “pa satisfè ankò kondisyon eksepsyonèl ki te jistifye TPS la”. ', 'Nan yon desizyon ki  pibliye jodi 26 novanm 2025 lan, DHS anonse  estati pwoteksyon tanporè (Temporary Protected Status, TPS) pou Ayiti ap pran fen, paske dapre yo peyi a “pa satisfè ankò kondisyon eksepsyonèl ki te jistifye TPS la”. \r\n\r\nDesizyon sa a, si pa gen chanjman oswa baz legal altènatif, ka riske mete plizyè milye Ayisyen Ozetazini an danje, jiska prepare pou yo kite peyi a. \r\n\r\nDHS fè konnen, pèmèt Ayisyen rete tanporèman Ozetazini “pa  nan enterè nasyonal ameriken.” Sekretè DHS la, Kristi Noem, prezante revokasyon sa a kòm yon mezi final. \r\n\r\nPou anpil moun, sa kapab vle di pèdi travay, risk depòtasyon, separasyon familyal, ak nesesite pou yo chache lòt mwayen legal pou rete, sa ki pa toujou fasil. Sitiyasyon an kreye presyon emosyonèl ak legal sou kominote Ayisyèn nan nan diaspora a.        ', 0, 0, 0, NULL, '2025-11-26', NULL, NULL),
(29, 153, 'Natcom remèt  Grenadye yo  yon chèk 13milyon goud. ', '2025-11-26 21:00:00', 'sports', 'Yï¿½ mï¿½kredi 26 novanm lan, nan yon seremoni natcom te ï¿½ganize , prezidan komite nï¿½malizasyon Federasyon an, Monique Andrï¿½, ansanm ak sekretï¿½ jeneral Patrick Massï¿½nat ak Yvon Sï¿½vï¿½re, te resevwa yon chï¿½k 13 milyon goud pou ekip nasyonal la. ', '<p>Y&egrave; m&egrave;kredi 26 novanm lan, nan yon seremoni natcom te &ograve;ganize , prezidan komite n&ograve;malizasyon Federasyon an, Monique Andr&egrave;, ansanm ak sekret&egrave; jeneral Patrick Mass&iuml;&iquest;&frac12;nat ak Yvon S&iuml;&iquest;&frac12;v&iuml;&iquest;&frac12;re, te resevwa yon ch&egrave;k 13 milyon goud pou ekip nasyonal la. Aksyon sa a te f&egrave;t k&ograve;m yon fason pou mete chapo ba devan eksplwa Grenadye yo reyalize nan dat 18 novanm lan, epi ofri sip&ograve; konkr&egrave; pou ekip la. Donasyon sa vini apre yon pery&ograve;d kote ekip la montre det&egrave;minasyon l sou teren an; j&egrave;s Natcom lan make siy konfyans li nan kapasite Grenadye yo, menm jan l mete konfyans li nan travay komite n&ograve;malizasyon an. Federasyon an di li apresye sip&ograve; sa anpil, yo konsidere li k&ograve;m yon pouse moral pou tout jw&egrave;, antren&egrave; ak ekip teknik la. Nan non federasyon an ak tout aksyon&egrave; yo, yon gwo m&egrave;si ale bay Natcom pou angajman finansye ak moral li. Av&egrave;k sip&ograve; sa a, Grenadye yo espere kontinye leve drapo peyi a pi wo toujou, pandan wout la pou Mondyal 2026 la.</p>', 0, 0, 0, NULL, '2025-11-27', NULL, NULL),
(32, 167, 'PalmarÃ¨s Mizik / Videyo Evanjelik - Novanm 2025', '2025-11-30 21:00:00', 'entertainment', 'Mwa novanm 2025 lan kite yon anprent espesyal nan mizik evanjelik ayisyÃ¨n nan. Ant mizik ki selebre fidelite Bondye, videyo ki pote temwayaj pÃ¨sonÃ¨l, ak pwojÃ¨ ki ranfÃ²se misyon evanjelik la, atis yo montre ankÃ² milye a vivan, kreyatif, ak pwofon nan mesaj y ap pote yo.', '<p>Mwa novanm 2025 lan kite yon anprent espesyal nan mizik evanjelik ayisyï¿½n nan. Ant mizik ki selebre fidelite Bondye, videyo ki pote temwayaj pï¿½sonï¿½l, ak pwojï¿½ ki ranfï¿½se misyon evanjelik la, atis yo montre ankï¿½ milye a vivan, kreyatif, ak pwofon nan mesaj y ap pote yo. Palmarï¿½s mwa sa a rasanble tout mizik ki make peryï¿½d la, kï¿½manse nan rap gospel rive nan lwanj tradisyonï¿½l, ak kolaborasyon ki bay nouvo enï¿½ji epi nouvo dimansyon nan pawï¿½l levanjil la. Men prensipal pwojï¿½ ki make mwa Novanm 2025 lan: 1. Fre Gabe &amp; Ed Dagodseed ï¿½ ï¿½PAPA M son Kingï¿½ Sï¿½ti: 3 novanm 2025 Rapï¿½ evanjelik ayisyen Frï¿½ Gabe kontinye ap eksplore mizik kï¿½m zouti espirityï¿½l epi mesaj sosyal parapï¿½ ak nouvo single li ï¿½PAPA M son Kingï¿½, ansanm ak kolaboratï¿½ li Ed Dagodseed. Mizik sa, ki soti anba label Inspiration Divine Studio ak pwodiksyon ReD Vision Plus, fï¿½ pati albï¿½m k ap vini an \"IDAW\" epi pote yon melanj ant rap, adorasyon, ak refleksyon sou pouvwa ak otorite Bondye nan lavi kwayan yo. Nan pawï¿½l yo, mizik la raple kwayan yo Jezi se sï¿½l veritab King, ke legliz la se yon espas pou repantans ak gerizon, kote fidï¿½l yo dwe mete konfyans yo nan pouvwa Bondye pou yo ka konbat tantasyon ak peche. Frï¿½ Gabe ak Ed Dagodseed sï¿½vi ak rap kï¿½m zouti koreksyon ak motivasyon, epi entegre yon langaj modï¿½n pandan y ap pote mesaj ki toujou an liy ak levanjil. ?? https://youtu.be/LM5RYja6Yzc?si=Xf1FCyq4-xa3YUh6 2. Jean Enock Louis ï¿½ ï¿½Gen Plis Toujouï¿½ Sï¿½ti: 5 novanm 2025 Jean Enock Louis lanse nouvo single li ï¿½Gen Plis Toujouï¿½, yon mizik ki pote mesaj pwofetik pou moun k ap chï¿½che direksyon ak benediksyon nan lavi yo. Mizik la mete aksan sou gras an abondans , onksyon, ak benediksyon Bondye, pandan li raple lavi etï¿½nï¿½l deja garanti pou moun ki mache nan prensip bon nouvï¿½l Kris la. Ekri ak konpoze pa Jean Enock Louis, ajoute ak yon videyo ofisyï¿½l ki pote siyati Desperaldo Beatz, ï¿½Gen Plis Toujouï¿½ envite Pï¿½p Bondye a louvri kï¿½ yo, elaji anviwï¿½nman espirityï¿½l yo, epi mete konfyans nan pwomï¿½s Bondye k ap akonpli nan lavi yo. ?? https://youtu.be/vSp3zp7CVIA?si=MtRjqU5kYHsjDMvt 3. Rachel C Poyeau feat. Spencer Brutus ï¿½ ï¿½VIKTWAï¿½ Sï¿½ti: 8 novanm 2025 Rachel C Poyeau prezante nouvo single li ï¿½VIKTWAï¿½, an kolaborasyon ak Spencer Brutus, yon atis k ap kite tras li nan mizik evanjelik. Mizik sa pote yon mesaj fï¿½ sou viktwa ak sipï¿½ Bondye nan lavi pitit li, pandan mizik la reflete eksperyans pï¿½sonï¿½l atis yo ki fï¿½ viktwa devan atak espirityï¿½l nan moman difisil. Viktwa se pa sï¿½lman yon temwayaj, men tou yon rapï¿½l ak Bondye bï¿½ kote nou, viktwa se yon reyalite pou tout moun, kï¿½lkeswa obstak yo. Pawï¿½l yo ankouraje nou pou mete konfyans nan pwoteksyon ak prezans Bondye, pou nou ka travï¿½se defi yo ak kouraj. ?? https://youtu.be/i4HKtGt0l6g?si=rlSqfZ-BtSIplbmq 4. James Alcindor feat. Lynda Joseph ï¿½ ï¿½Bonte Ou Pa Gen Rivalï¿½ Sï¿½ti: 14 novanm 2025 Bonte Ou Pa Gen Rivalï¿½ se nouvo mizik adorasyon James Alcindor lanse an kolaborasyon ak Lynda Joseph, yon atis ki renome pou kalite vokal li ak prezans espirityï¿½l lakay li. Se yon mizik ki leve konsyans sou jan bonte Bondye depase limit, depase konparezon, ak depase tout sa yon moun ka imajine. Li mete aksan sou fï¿½s ak fidelite Bondye ki pwoteje, klere, epi ki kenbe pï¿½p li djougan menm nan moman difisil yo. Mizik sa raple prezans Bondye se sous estabilite, limyï¿½, ak direksyon. Se li ki bay moun kapasite pou yo rete djanm epi kontinye mache avï¿½k konfyans. Bonte Ou Pa Gen Rivalï¿½ se yon mizik ki kapab sï¿½vi pou meditasyon, moman priyï¿½, oswa adorasyon. ?? https://youtu.be/lBQdN50Z7io?si=trszc5_QEqpxDgn6 5. Carmessita Rï¿½silien ï¿½ ï¿½ANYENï¿½ Sï¿½ti: 15 novanm 2025 Carmessita Rï¿½silien ofri piblik la ï¿½ANYENï¿½, yon mizik ki pote yon mesaj fï¿½ sou direksyon Bondye ak pouvwa men l nan lavi chak moun. ï¿½ANYENï¿½ se yon pwoklamasyon klï¿½: pyï¿½s baryï¿½ pa ka bloke sa Bondye sere pou yon moun, ni detounen chemen li trase. Mizik la raple lavni, desten, ak direksyon lavi yon moun rete anrasinen nan men Bondye, kote pa gen dout, pa gen laperï¿½z... se yon rapï¿½l desten nou pa depann de obstak yo, men de Bondye ki trase chimen nou. ?? https://youtu.be/pyMO6pZ5S7Y?si=W24QeKuducht6kHM 6. Pasteur Claudy Jean Louis ï¿½ ï¿½Se Sezon Pa mï¿½ Dat sï¿½ti: 16 novanm 2025 Pasteur Claudy Jean Louis prezante piblik la nouvo mizik li ï¿½Se Sezon Pamï¿½, yon mizik ki soti 16 novanm 2025 ak yon videyo ofisyï¿½l ki disponib sou youtube. Li plase tï¿½t li nan mouvman mizik evanjelik ayisyen an kï¿½m yon pyï¿½s ki mete aksan sou temwayaj, pï¿½severans ak konfyans nan entï¿½vansyon Bondye. Nan pawï¿½l yo, pastï¿½ Claudy devlope lide sezon espirityï¿½l la kï¿½m yon etap avansman okenn fï¿½s pa ka anpeche. Li fï¿½ referans ak pasaj biblik ki rakonte liberasyon pï¿½p Izrayï¿½l la, pou ilistre kijan obstak yo, menm lï¿½ yo parï¿½t kï¿½m gwo baryï¿½, pa anpeche manifestasyon pouvwa Bondye. ?? https://youtu.be/DHK0ZQ3KIGo?si=FYBRpXSELgItOduk 7. Frï¿½ Mendy ï¿½ ï¿½Pa Anpeche m Louweï¿½ sï¿½ti: 20 novanm 2025 Frï¿½ Mendy mete yon nouvo ton nan mizik evanjelik la ak ï¿½Pa Anpechem Louweï¿½, yon mizik ki mezire fï¿½s yon temwayaj pï¿½sonï¿½l ak pouvwa rekonesans devan Bondye. Videyo liriks la, ki parï¿½t 20 novanm 2025. Nan mizik la, Frï¿½ Mendy adrese yon reyalite anpil moun viv nan mitan legliz: jijman vizyï¿½l, prejije, ak move entï¿½pretasyon. ï¿½Si w wï¿½ demen m anlï¿½ nan tanp lanï¿½ mwen pa t a vle w panse se show-off map fï¿½ montre byen pozisyon atis la: louwanj pa fï¿½t pou enpresyone, men pou temwaye. Avï¿½k ï¿½Pa Anpeche m Louweï¿½, Frï¿½ Mendy pote yon mizik ki depase louwanj tradisyonï¿½l pou l antre nan teritwa temwayaj pï¿½sonï¿½l. ?? https://youtu.be/0kaMzp2bWJg?si=VGGd19GERyhMRUHO 8. Tom Karencini Jecrois feat. Deborah Henristal sï¿½ti: 25 Novanm 2025 Apre siksï¿½ mizik ï¿½Pi Bon Zanmi Mwen (Ou Fidï¿½l nan Pwomï¿½s Ou)ï¿½, ki te parï¿½t an novanm 2024, atis evanjelik Tom Karencini Jecrois retounen an 2025 ak yon nouvo prezantasyon mizik la: yon remix an kolaborasyon ak Deborah Henristal. Nouvo vï¿½syon sa a, ki disponib depi novanm 2025, pote yon pwojï¿½ modï¿½n ak fï¿½s vokal enpekab, pandan li rete fidï¿½l ak fondasyon espirityï¿½l mizik orijinal la. Mizik la prezante yon temwayaj pwofon kote atis la rekonï¿½t feblï¿½s li antanke moun, erï¿½ li yo, ak distans li konn pran ak Bondye; men malgre tout bagay, Bondye rete fidï¿½l. Remix 2025 la pote yon nouvo enï¿½ji: yon vibrasyon pi jï¿½n, epi yon melanj vokal ki fï¿½ kolaborasyon jwenn kalifikasyon ekstrawï¿½dinï¿½. ï¿½Ou Fidï¿½l nan Pwomï¿½s Ouï¿½ rete yon mizik sou fidelite Bondye, sou lanmou k ap grandi chak jou pi plis, ak sou rekonesans pou yon Zanmi ki pa janm lage li. ?? https://youtu.be/fxRMYq9rzJQ?si=UBpqRV3PlRZsFDPN 9. Bless Louis ï¿½ ï¿½Ensemble Louons le Seigneur / Comment ne pas te louerï¿½ (Cover) sï¿½ti: 26 novanm 2025 Nan okazyon anivï¿½sï¿½ li, 26 novanm 2025, Bless Louis ofri piblik la yon medley louwanj atravï¿½ yon chant tradisyonï¿½l ï¿½Ensemble louons le Seigneur il est vivantï¿½. Videyo a mete aksan sou adorasyon ak rekonesans pou Bondye, pandan li bay piblik la yon adaptasyon tou nï¿½f epi pï¿½sonï¿½l nan yon chant ki deja renome nan kominote kretyï¿½n nan. ï¿½Seigneur, il est vivant !ï¿½ pote chalï¿½ ak emosyon ki fasil pou konekte ak tout jenerasyon. Medley sa a pa sï¿½lman selebre anivï¿½sï¿½ jï¿½n atis la, men li vin tounen yon zouti pou rasanble fanmi, legliz, ak kominote nan yon moman adorasyon. ?? https://youtu.be/_64DUorcmAU? Palmarï¿½s Mizik/Videyo Evanjelik ï¿½ Novanm 2025 lan montre avidï¿½y enï¿½ji, kreyativite ak pwofondï¿½ ki kontinye defini mizik evanjelik ayisyï¿½n nan. Chak mizik pote mesaj pwï¿½p pa li, viktwa, koreksyon, rekonesans, adorasyon, direksyon, ak espwa. Se yon palmarï¿½s ki make avansman sektï¿½ a ak angajman atis yo pou sï¿½vi kominote a. Mizik yo disponib, mesaj yo klï¿½, epi atis yo pare pou kontinye enspire. Redaksyon: Vanauscheca Bouzy &amp; Ronalson Blanfort Konsepsyon Grafik: Jo-Flung Bouzy &amp; Abdullah Mode #palmarï¿½smizikvideyoevanjeliknov2025 #konektem #toutkotenenpotkilï¿½</p>', 0, 0, 0, '2111,90', '2025-12-01', NULL, NULL),
(36, 229, 'Des pavÃ©s de la rue aux lumiÃ¨res de la Chine :  Diamant brille ! Fr Luckson assure.', '2026-01-22 21:00:00', 'entertainment', 'Dans les rues poussiÃ©reuses et animÃ©es des montagnes de Pilboro, un enfant au sourire Ã©clatant attirait dÃ©jÃ  les regards â€” pas parce quâ€™il avait des richesses ou une vie facile, mais parce que son regard semblait contenir une lumiÃ¨re que nul ne pouvait ignorer.', '<p class=\"p1\"><span class=\"s1\">Dans les rues poussi&eacute;reuses et anim&eacute;es des montagnes de Pilboro, un enfant au sourire &eacute;clatant attirait d&eacute;j&agrave; les regards, pas parce qu&rsquo;il avait des richesses ou une vie facile, mais parce que son regard semblait contenir une lumi&egrave;re que nul ne pouvait ignorer.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ce gar&ccedil;on, que le photographe Vladimy Josu&eacute; Destin&eacute; a immortalis&eacute; dans un clich&eacute; devenu viral sur les r&eacute;seaux sociaux, n&rsquo;&eacute;tait encore qu&rsquo;un enfant des rues, livr&eacute; &agrave; luiâ€‘m&ecirc;me dans un monde souvent cruel pour les plus vuln&eacute;rables. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Cette image a &eacute;veill&eacute; les consciences et d&eacute;clench&eacute; une r&eacute;action qui allait changer une vie. C&rsquo;est ainsi qu&rsquo;est entr&eacute; en sc&egrave;ne la Fondation Fr&egrave; Luckson Z&ograve;n Pa F&egrave; Moun, une organisation ha&iuml;tienne &agrave; but non lucratif n&eacute;e du credo &laquo;â€¯Z&ograve;n pa f&egrave; mounâ€¯&raquo;, fond&eacute;e par Luckson Jean, un philanthrope profond&eacute;ment engag&eacute; &agrave; venir en aide aux enfants sans domicile fixe et &agrave; lutter contre l&rsquo;exclusion sociale. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">La mission de cette fondation est claire : offrir un refuge s&ucirc;r, une &eacute;ducation, un accompagnement moral et des opportunit&eacute;s nouvelles &agrave; ceux qui ont &eacute;t&eacute; oubli&eacute;s par la soci&eacute;t&eacute;. Elle ne se limite pas &agrave; r&eacute;cup&eacute;rer des enfants des rues : elle d&eacute;ploie des programmes &eacute;ducatifs, organise des activit&eacute;s sportives comme une &eacute;quipe de football pour renforcer l&rsquo;estime de soi, et cr&eacute;e des espaces de vie structur&eacute;s pour permettre &agrave; ces jeunes de grandir avec dignit&eacute; et ambition. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&laquo; Kounya mwen moute avyon plis ke kamyon&egrave;t &raquo;, a-t-il d&eacute;clar&eacute; dans un extrait d&rsquo;un single r&eacute;cemment partag&eacute; sur les r&eacute;seaux sociaux de la fondation. Devenue virale, cette d&eacute;claration annonce la sortie prochaine d&rsquo;un nouveau projet tr&egrave;s attendu.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Le parcours de Diamant est aujourd&rsquo;hui remarquable : chap&eacute; des griffes d&rsquo;un destin qui ne lui &eacute;tait pas destin&eacute;, il brille d&eacute;sormais d&rsquo;une lumi&egrave;re qui lui est propre et s&rsquo;impose aujourd&rsquo;hui comme une figure montante, dont la valeur artistique est per&ccedil;ue comme celle d&rsquo;un Diamant rare et incomparable.&nbsp;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">il n&rsquo;est plus d&eacute;fini par les pav&eacute;s o&ugrave; il errait, mais par les sc&egrave;nes sur lesquelles il brille. Artiste, symbole d&rsquo;espoir et d&eacute;sormais ambassadeur pour des causes sociales, il a sorti plusieurs musiques salu&eacute;es par le public et a m&ecirc;me eu l&rsquo;opportunit&eacute; de voyager, notamment aux Bahamas, pour participer &agrave; des &eacute;v&eacute;nements culturels et artistiques. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">En cette ann&eacute;e particuli&egrave;re, Diamant s&rsquo;appr&ecirc;te &agrave; vivre une nouvelle &eacute;tape exceptionnelle de sa vie : une visite en Chine, pr&eacute;vue durant cette saison dans le cadre d&rsquo;un programme sp&eacute;cial qui c&eacute;l&egrave;bre son talent et son parcours inspirant. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ce voyage repr&eacute;sente non seulement une &eacute;tape dans sa carri&egrave;re artistique, mais aussi une reconnaissance internationale d&rsquo;un jeune qui a refus&eacute; d&rsquo;&ecirc;tre d&eacute;fini par ses origines et qui a transform&eacute; son histoire en source d&rsquo;inspiration.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Redaksyon:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Jo-flung Bouzy</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p3\"><span class=\"s3\">#konektem</span></p>\r\n<p class=\"p3\"><span class=\"s3\">#toutkotenenp&ograve;tkil&egrave;</span></p>', 0, 0, 0, NULL, '2026-01-23', NULL, NULL),
(37, 230, 'KPT a Rete Djougan Anfas EntÃ¨diksyon Etazini ak Kanda ki opoze ak Desizyon Revokasyon Premye Minis Alix Didier Fils-AimÃ©.', '2026-01-22 21:00:00', 'politics', 'Jodi vandredi 23 janvye 2026 lan, Konseye Prezidan Leslie Voltaire ansanm ak Edgard Leblanc Fils te anime yon konferans pou laprÃ¨s, nan ViladakÃ¨y pou fÃ¨ pwen sou sitiyasyon politik peyi a ak fason KonsÃ¨y PrezidansyÃ¨l Tranzisyon (KPT) an ap jere dÃ¨nye jou manda li, ki gen pou bout nan dat 7 fevriye k ap vini la.', '<p class=\"p1\"><span class=\"s1\">Jodi vandredi 23 janvye 2026 lan, Konseye Prezidan Leslie Voltaire ansanm ak Edgard Leblanc Fils te anime yon konferans pou lapr&egrave;s, nan Viladak&egrave;y pou f&egrave; pwen sou sitiyasyon politik peyi a ak fason Kons&egrave;y Prezidansy&egrave;l Tranzisyon (KPT) an ap jere d&egrave;nye jou manda li, ki gen pou bout nan dat 7 fevriye k ap vini la. Nan diskou li a, Leslie Voltaire rekon&egrave;t malgre jef&ograve; KPT a pou soutni travay gouv&egrave;nman an, atant popilasyon an pa rive satisf&egrave; jan sa t a dwe f&egrave;t. Se nan kont&egrave;ks sa<span class=\"Apple-converted-space\">&nbsp; </span>Kons&egrave;y la pran desizyon pou revoke Premye minis Alix Didier Fils-Aim&eacute;, epi chwazi yon l&ograve;t Premye minis ki s&ograve;ti nan menm gouv&egrave;nman an, pou asire enterim&egrave; a pandan y ap ch&egrave;che pi bon f&ograve;mil pou jere pery&ograve;d tranzisyon politik la, apre depa KPT a.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Yon l&ograve;t b&ograve;, Konseye Prezidan Edgard Leblanc Fils f&egrave; konnen chanjman Premye minis lan vize p&egrave;m&egrave;t travay ki rete pou KPT a reyalize nan ti tan li genyen an f&egrave;t pi byen. Li anonse nouvo Ch&egrave;f gouv&egrave;nman an t a dwe rete nan fonksyon an pandan 30 jou, nan objektif pou fasilite tranzisyon politik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\"> Pandan konferans lan, Konseye Prezidan yo te reponn kesyon jounalis yo, tout pandan yo raple kominote ent&egrave;nasyonal la li pa gen pouvwa pou dikte desizyon ki dwe pran an Ayiti, nan ajoute se Ayisyen ki dwe pran rezolisyon pou Ayisyen, nan lide pou retabli estabilite nan peyi a.</span></p>', 0, 0, 0, NULL, '2026-01-23', NULL, NULL),
(38, 232, 'Konseye Prezidan  Laurent Saint-Cyr Reyafime Angajman Leta nan Sipòte FAD\'H.', '2026-01-25 21:00:00', 'politics', 'Jodi Lendi 26 janvye 2026 la, Prezidan Konsèy Prezidensyèl Transisyon an,  Laurent Saint-Cyr, ansanm ak Premye Minis Alix Didier Fils-Aimé ak Minis Defans lan, Jean Michel Moïse, te ale nan gran katye jeneral fòs lame peyi a, nan kad yon vizit ofisyèl ki te fèt.', '<p class=\"p1\"><span class=\"s1\">Jodi Lendi 26 janvye 2026 la, Prezidan Kons&egrave;y Prezidensy&egrave;l Transisyon an,<span class=\"Apple-converted-space\">&nbsp; </span>Laurent Saint-Cyr, ansanm ak Premye Minis Alix Didier Fils-Aim&eacute; ak Minis Defans lan, Jean Michel Mo&iuml;se, te ale nan gran katye jeneral f&ograve;s lame peyi a, nan kad yon vizit ofisy&egrave;l ki te f&egrave;t.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Vizit sa a te gen pou objektif pou reyafime angajman leta, nan bay F&ograve;s lame peyi a, tout sip&ograve; posib, sitou nan kad gwo travay an kolaborasyon ak Polis Nasyonal, y ap f&egrave; pou konbat gang krimin&egrave;l yo. Pandan rankont sila, Prezidan an te salye pwofesyonalis, disiplin, ak sans devwa s&ograve;lda yo, ki gen yon w&ograve;l esansy&egrave;l nan estabilite peyi a, pwoteksyon enfrastrikti yo, ak sip&ograve; pou enstitisyon leta yo. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pandan vizit la, li te ensiste sou enp&ograve;tans yon netralite b&ograve; kote f&ograve;s lame a, yon kondisyon ki esansy&egrave;l pou retabli konfyans p&egrave;p la ak kredibilite ent&egrave;nasyonal la nan enstitisyon milit&egrave; sila. Pou bout ak diskou l la, Prezidan Saint-Cyr eksprime konfyans li nan pwofesyonalis ak disiplin k&ograve;mandman ant&egrave;t la, pandan li reyafime sip&ograve; total leta pou F&ograve;s lame peyi a, nan kad misyon sekirite nasyonal yo. Li te ankouraje jef&ograve; yo pou retabli sekirite dirab, sa ki enp&ograve;tan pou &ograve;ganize eleksyon ki kredib ak pou amelyore byenn&egrave;t p&egrave;p ayisyen an.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">#esansy&egrave;l</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#politik</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#konektem</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#toutkotenenpotkil&egrave; </span></p>', 0, 0, 0, NULL, '2026-01-26', NULL, NULL),
(39, 233, 'PNH diplome 57 ajan nan teknik konba kont gang yo.', '2026-01-27 21:00:00', 'politics', 'Polis Nasyonal Ayiti (PNH), ak sipò International Narcotics and Law Enforcement Affairs (INL), te remèt sètifika bay 57 ajan, pami yo 29 polisye ki sòti nan plizyè inite…', '<p class=\"p1\"><span class=\"s1\">Polis Nasyonal Ayiti (PNH), ak sip&ograve; International Narcotics and Law Enforcement Affairs (INL), te rem&egrave;t s&egrave;tifika bay 57 ajan, pami yo 29 polisye ki s&ograve;ti nan plizy&egrave; inite espesyalize ak 28 teknisyen, apre yo fin konplete f&ograve;masyon espesyalize, 27 janvye 2026, nan Direksyon Jeneral PNH la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">F&ograve;masyon yo te pote sitou sou Combat Driving ak Tire Filling, av&egrave;k objektif pou ranf&ograve;se kapasite teknik ak operasyon&egrave;l lapolis la, pou li pi efikas nan repons kont gang ame yo ak l&ograve;t defi sekirite peyi a ap konfwonte.</span></p>', 0, 0, 0, NULL, '2026-01-28', NULL, NULL),
(40, 234, 'Dezyèm kowòt PAEF la lanse pou sipòte fanm antreprenè nan plizyè sektè.', '2026-01-27 21:00:00', 'politics', 'Ministè Kondisyon Fanm ak Dwa Fanm (MCFDF) anonse ouvèti dezyèm kowòt Pwogram Sipò pou Entreprenarya Fanm (PAEF), yon inisyativ ki soti nan Ministè Komès ak Endistri (MCI).', '<p class=\"p1\"><span class=\"s1\">Minist&egrave; Kondisyon Fanm ak Dwa Fanm (MCFDF) anonse ouv&egrave;ti dezy&egrave;m kow&ograve;t Pwogram Sip&ograve; pou Entreprenarya Fanm (PAEF), yon inisyativ ki soti nan Minist&egrave; Kom&egrave;s ak Endistri (MCI). Pwogram nan vize bay sip&ograve; teknik ak finansye pou antrepriz ki dirije pa fanm ak pwoj&egrave; inovatif k ap jenere revni, pou ranf&ograve;se otonomizasyon ekonomik fanm nan tout peyi a.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Fanm ki ka patisipe:</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Dirijan biznis ki egziste deja;</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Fanm ki pote pwoj&egrave; inovatif k ap jenere revni;</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Fanm ki angaje nan pwosesis ekspansyon oswa mod&egrave;nizasyon biznis yo;</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Biznis ki fizikman enstale nan peyi a;</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Fanm ki pa benefisye kounye a de yon l&ograve;t pwoj&egrave; MCI.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Sekt&egrave; priyorit&egrave;:</span></p>\r\n<p class=\"p1\"><span class=\"s1\">&bull; Agrikilti, elvaj ak pwason, endistri pwodiksyon ak transf&ograve;masyon, fabrikasyon, atizana, resiklaj (transf&ograve;masyon dech&egrave;), touris, teknoloji, lojistik, ansanm ak nenp&ograve;t l&ograve;t sekt&egrave; ki konsidere k&ograve;m enp&ograve;tan.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pery&ograve;d ak fason depo dosye:</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Dosye yo ap aksepte soti 27 rive 30 janvye 2026, epi yo dwe soum&egrave;t eksklizivman sou ent&egrave;n&egrave;t, atrav&egrave; sit MCI: www.mci.gouv.ht/paef. Plan biznis yo disponib sou sit la tou.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Minist&egrave; ankouraje tout fanm antrepren&egrave; ki kalifye nan tout peyi a pou pwofite op&ograve;tinite sa a, ki f&egrave; pati ef&ograve; gouv&egrave;nman an pou ankouraje lid&egrave;chip ekonomik fanm ak egalite chans.</span></p>', 0, 0, 0, NULL, '2026-01-28', NULL, NULL),
(41, 235, 'Ayiti ak Meksik ranfòse koperasyon elektoral yo atravè yon pwotokòl akò.', '2026-01-27 21:00:00', 'politics', 'Konsèy Elektoral Pwovizwa (CEP) ak Enstiti Nasyonal Elektoral Meksik (INE) siyen, jodi mèkredi 28 janvye 2026 la, yon pwotokòl akò ki vize ranfòse koperasyon ant de (2) enstitisyon yo, nan domèn elektoral.', '<p class=\"p1\"><span class=\"s1\">Kons&egrave;y Elektoral Pwovizwa (CEP) ak Enstiti Nasyonal Elektoral Meksik (INE) siyen, jodi m&egrave;kredi 28 janvye 2026 la, yon pwotok&ograve;l ak&ograve; ki vize ranf&ograve;se koperasyon ant de (2) enstitisyon yo, nan dom&egrave;n elektoral.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ak&ograve; sila te siyen pa prezidan CEP a, Jacques Desrosiers, ansanm ak anbasad&egrave; Meksik la an Ayiti, Jos&eacute; de Jes&uacute;s Cisneros Ch&aacute;vez. Li prevwa kolaborasyon sa sou plizy&egrave; asp&egrave; kle, nan sist&egrave;m elektoral la, tankou f&ograve;masyon ak ranf&ograve;sman kapasite operat&egrave; elektoral yo, jesyon ak mizajou rejis elektoral la, ansanm ak itilizasyon teknoloji nan &ograve;ganizasyon eleksyon yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">De (2) pati yo angaje yo pou pataje eksperyans ak eksp&egrave;tiz yo, nan yon lespri resp&egrave; ak aprantisaj mity&egrave;l, nan objektif pou ranf&ograve;se demokrasi epi asire chak v&ograve;t konte nan pwosesis elektoral yo.</span></p>', 0, 0, 0, NULL, '2026-01-28', NULL, NULL);
INSERT INTO `news` (`id`, `image_id`, `title`, `created_at`, `category`, `headline`, `content`, `reads`, `likes`, `shares`, `featured_image_ids`, `published_at`, `author_id`, `updated_at`) VALUES
(51, 250, '🎶 Palmarès Mizik Evanjelik – Janvye 2026.', '2026-01-31 21:00:00', 'music & video', 'Janvye 2026 make yon nouvo paj nan mizik evanjelik ayisyen an. Depi premye jou ane a, plizyè atis chwazi louvri kalandriye mizikal la ak chante ki pote mesaj lafwa, esperans, adorasyon ak konfyans total nan Bondye. Premye palmarès mwa janvye a montre klèman yon tandans kote mizik evanjelik la vin tounen yon espas temwayaj, rezistans espirityèl ak rekonesans, nan yon kontèks sosyal ak pèsonèl ki mande ankourajman.', '<p class=\"p1\"><span class=\"s1\">Yon k&ograve;mansman ane ki make pa lafwa, adorasyon ak deklarasyon espirity&egrave;l</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Janvye 2026 make yon nouvo paj nan mizik evanjelik ayisyen an. Depi premye jou ane a, plizy&egrave; atis chwazi louvri kalandriye mizikal la ak chante ki pote mesaj lafwa, esperans, adorasyon ak konfyans total nan Bondye. Premye palmar&egrave;s mwa janvye a montre kl&egrave;man yon tandans kote mizik evanjelik la vin tounen yon espas temwayaj, rezistans espirity&egrave;l ak rekonesans, nan yon kont&egrave;ks sosyal ak p&egrave;son&egrave;l ki mande ankourajman.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Men palmar&egrave;s mizik evanjelik pou mwa janvye 2026, ak pwodiksyon ki make k&ograve;mansman ane a sou s&egrave;n gospel ayisyen an.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">1. Apotre Robenson Joachim &ndash; &ldquo;Mpaka Echwe&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 1 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k &ldquo;Mpaka Echwe&rdquo;, Apotre Robenson Joachim ouvri ane a sou yon deklarasyon f&ograve;: ech&egrave;k pa gen d&egrave;nye mo a. Videyo ofisy&egrave;l la, ki soti 1 janvye 2026, vin bay yon l&ograve;t dimansyon a mizik la ki te deja disponib sou platf&ograve;m dijital depi novanm 2025.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a ab&ograve;de reyalite konba lavi yo, blesi, reta ak difikilte, men li kenbe yon mesaj santral kl&egrave;: tout gwo desten pase pa konba. Paw&ograve;l tankou &laquo; Menm si mwen blese, m pap echwe &raquo; vin tounen poto mitan mizik la, pandan videyo a s&egrave;vi ak metaf&ograve; pou montre kijan tanp&egrave;t yo ka leve yon moun pi wo olye yo kraze li.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/h2UmzuzrYZM?si=zWBF4c5OVNsEE6WB</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">2. Josue Elisme &ndash; &ldquo;Pitit Wa&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 1 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan menm jou a, Josue Elisme prezante &ldquo;Pitit Wa&rdquo;, yon chan ki pwoklame idantite kwayan yo k&ograve;m pitit Bondye. Mizik la melanje adorasyon ak motivasyon, raple ke, k&egrave;lkeswa defi oswa enkyetid, Bondye rete prezan pou gide, kouvri ak pwoteje.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Pitit Wa&rdquo; vini k&ograve;m yon mesaj espirity&egrave;l espesyal pou k&ograve;mansman ane a, envite piblik la avanse ak lafwa, konfyans ak asirans ke plan Bondye pa janm an reta.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/7M7pRBliZHA?si=CGNv_XKt4m4JPch4</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">3. Fabienne Payoute Bernadin &ndash; &ldquo;Ou Pa Ka Bare M&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 4 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ak &ldquo;Ou Pa Ka Bare M&rdquo;, Fabienne Payoute Bernadin livre yon deklarasyon espirity&egrave;l sou viktwa ak delivrans. Mizik la pale dir&egrave;kteman ak tout moun ki santi yo bloke, atake oswa ralanti nan avansman lavi yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a raple ke l&egrave; Bondye louvri yon p&ograve;t, pa gen okenn f&ograve;s imen oswa espirity&egrave;l ki ka f&egrave;men li. Anrejistre nan Jino Defralien Studio ak videyo pa Gabrielle Pierre, mizik la pote espwa ak asirans ke delivrans Bondye depase tout bary&egrave;.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/yD_01IXZVrE?si=Js4ZUTllBQnRIP_o</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">4. Evangeliste Trofort feat. Jude Samuel &ndash; &ldquo;Ou M&egrave;veye&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 7 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Ou M&egrave;veye&rdquo; se yon chante adorasyon ki santre sou sakrifis Jezi Kris, kwa a ak rezir&egrave;ksyon an. Evangeliste Trofort ak Jude Samuel ofri yon meditasyon pwofon sou lanmou Kris la, ki depase tout konpreyansyon imen.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la mete aksan sou rekonesans, adorasyon ak admirasyon devan yon Jezi ki pote chay lemonn, ki bay delivrans ak lavi.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/4gV04nqFoMw?si=O1a2aia7PbF1iRVZ</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">5. Marc Adens Brianvil feat. Deborah Henristal &ndash; &ldquo;Li Kenbe&rsquo;m&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 10 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ak &ldquo;Li Kenbe&rsquo;m&rdquo;, Marc Adens Brianvil ak Deborah Henristal pwopoze yon chan temwayaj sou pwoteksyon Bondye. Mizik la trav&egrave;se tout sezon lavi a, soti janvye rive desanm<span class=\"Apple-converted-space\">&nbsp; </span>pou raple ke men Bondye pa janm lage.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Repetisyon &ldquo;Bondye kenbe mwen&rdquo; vin tounen yon pwoklamasyon lafwa, espesyalman nan mitan ensekirite, danje ak kriz sosyete a ap trav&egrave;se.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/cJfhr45NiGs?si=S8rKGIZIIkqNNavp</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">6. Barbara Cassamajor feat. Les &Eacute;lus Haiti &ndash; &ldquo;C&rsquo;est Dieu&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 11 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;C&rsquo;est Dieu&rdquo; se youn nan pwen f&ograve; alb&ograve;m Renouveau de Barbara Cassamajor. K&ograve;m 11y&egrave;m chan sou alb&ograve;m 12 tit la, mizik la vini tankou yon deklarasyon lafwa kl&egrave; sou destin, avni ak direksyon lavi.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k refren &laquo; Ah oui, c&rsquo;est Dieu &raquo;, chante a pwoklame Bondye k&ograve;m s&egrave;l referans. Pwodiksyon Shegger Beats, ansanm ak vokal Les &Eacute;lus Haiti, ranf&ograve;se dimansyon espirity&egrave;l mizik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan kad pwomosyon alb&ograve;m nan, yon kons&egrave;<span class=\"Apple-converted-space\">&nbsp; </span>ap f&egrave;t 1 mas 2026 nan Pal&egrave; Minisipal. Alb&ograve;m Renouveau disponib sou tout platf&ograve;m dijital yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/76ymg2XwdcI?si=0xXrTjrQAnJ_jy91</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">7. Emmanuel Beliard ft. Spencer Brutus &ndash; &ldquo;ALL&Eacute;LUIA!&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 11 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;ALL&Eacute;LUIA!&rdquo; se yon chan adorasyon ak viktwa espirity&egrave;l. Emmanuel Beliard ak Spencer Brutus envite piblik la leve vwa yo nan louanj, menm nan mitan konfizyon, dezolasyon ak chenn espirity&egrave;l.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la s&egrave;vi k&ograve;m yon r&egrave;l lafwa ki anonse delivrans, limy&egrave; ak lib&egrave;te atrav&egrave; adorasyon.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/E8kny1HlCxY?si=UTeUzZQDT7ATZ9FU</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">8. Rod Ume &ndash; &ldquo;M&Egrave;SI PAPA&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 17 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Av&egrave;k &ldquo;M&Egrave;SI PAPA&rdquo;, Rod Ume ofri yon chan rekonesans kote li rem&egrave;sye Bondye pou swen, pwoteksyon ak prezans fid&egrave;l Li. Paw&ograve;l yo prezante Bondye k&ograve;m B&egrave;je ki pa janm abandone pitit li, menm anba atak ak difikilte.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la vin tounen yon chan delivrans, lapriy&egrave; ak adorasyon melanje.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/HJ_WSgVzhiE?si=bKwM4-ewFu_UU4GK</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">9. Fr&egrave; Mendy ft. Spencer Brutus &ndash; &ldquo;Mwen Depann de Ou&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 20 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&ldquo;Mwen Depann de Ou&rdquo; se yon priy&egrave; mizikal sou aband&ograve;n total. Fr&egrave; Mendy ak Spencer Brutus mete aksan sou relasyon dir&egrave;k ant l&ograve;m ak Bondye, san atifis, san pretansyon.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a raple ke f&ograve;s rey&egrave;l la k&ograve;manse l&egrave; yon moun rem&egrave;t tout kontw&ograve;l lavi li nan men Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/FeI_Ye8jak0?si=kHjF96owZAVz0-CR</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">10. Rosalinda Esmanga &ndash; &ldquo;Soufle Sou Mwen&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 25 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ak &ldquo;Soufle Sou Mwen&rdquo;, Rosalinda Esmanga pwopoze yon chan lapriy&egrave; kote li mande direksyon, pwoteksyon ak prezans Sentespri. T&egrave;ks la mete aksan sou renouv&egrave;lman entery&egrave; ak konfyans total nan Paw&ograve;l Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/lCbhXRp2rsE?si=aM6svqMG-pMAFATW</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">11. Mike-Lee Elminis &ndash; &ldquo;Men Lavi&rsquo;m Yahwey&rdquo;</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 31 janvye 2026</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pou f&egrave;men mwa a, Mike-Lee Elminis prezante &ldquo;Men Lavi&rsquo;m Yahwey&rdquo;, yon priy&egrave; adorasyon sou aband&ograve;n total. Ot&egrave;, konpozit&egrave; ak ent&egrave;pr&egrave;t chan an, atis la depoze lavi li n&egrave;t nan men Bondye, k&ograve;m s&egrave;l gid ak direksyon.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a tradwi doul&egrave;, fatig entery&egrave;, men sitou konfyans ak espwa nan fidelite Yahwey.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/K6AbZXcElRg?si=cq4fUiJGdkLTJcWn</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Palmar&egrave;s mizik evanjelik janvye 2026 la montre yon k&ograve;mansman ane ki rich an mesaj espirity&egrave;l, temwayaj ak adorasyon. Atis yo chwazi ouvri ane a ak chante ki pale de rezistans, delivrans, aband&ograve;n total ak konfyans nan Bondye.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Premye palmar&egrave;s ane a pa s&egrave;lman reflete dinamis mizik gospel ayisyen an, men li konfime w&ograve;l mizik evanjelik la k&ograve;m yon sous espwa, f&ograve;s ak direksyon pou piblik la pandan tout ane 2026 la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Redaksyon:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Vanauscheca Bouzy</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s2\">Konsepsyon Grafik:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Jo-Flung Bouzy &amp; Abdullah Mode</span></p>', 0, 0, 0, NULL, '2026-02-01', NULL, NULL),
(52, 251, 'Meri Dèlma : Remiz sètifika pou 20 jèn apre 3 mwa fòmasyon.', '2026-02-03 21:00:00', 'education', 'Jodi mèkredi 4 fevriye 2026 la, Majistra Wilson Jeudy, ansanm ak Direksyon Jeni ak Sèvis Enfòmatik, te remèt sètifika bay 20 jèn stajyè, ki sot konplete avèk siksè yon fòmasyon twa (3) mwa, nan domèn jeni ak enfòmatik. Seremoni sila te fèt nan Meri Delmas a, kote jèn yo te resevwa rekonesans ofisyèl pou jefò ak disiplin yo pandan peryòd fòmasyon an.', '<p id=\"wl18h1\" class=\"_0JjuK _5dsPU\" data-pm-slice=\"1 1 []\">Jodi m&egrave;kredi 4 fevriye 2026 la, Majistra Wilson Jeudy, ansanm ak Direksyon Jeni ak S&egrave;vis Enf&ograve;matik, te rem&egrave;t s&egrave;tifika bay 20 j&egrave;n stajy&egrave;, ki sot konplete av&egrave;k siks&egrave; yon f&ograve;masyon twa (3) mwa, nan dom&egrave;n jeni ak enf&ograve;matik. Seremoni sila te f&egrave;t nan Meri Delmas a, kote j&egrave;n yo te resevwa rekonesans ofisy&egrave;l pou jef&ograve; ak disiplin yo pandan pery&ograve;d f&ograve;masyon an.</p>\r\n<p id=\"w47pk3\" class=\"_0JjuK _5dsPU\"></p>\r\n<p id=\"3unvn4\" class=\"_0JjuK _5dsPU\">Nan okazyon sa, plizy&egrave; patisipan pa t kache satisfaksyon yo ni rekonesans yo anv&egrave; Majistra a ak ekip f&ograve;mat&egrave; yo, yo konsidere f&ograve;masyon sa k&ograve;m yon etap enp&ograve;tan ki pral gen enpak dir&egrave;k sou lavi pwofesyon&egrave;l ak p&egrave;son&egrave;l yo. Yo salye yon inisyativ ki vize bay j&egrave;n yo zouti teknik pou pi bon entegrasyon yo sou mache travay la.</p>\r\n<p id=\"bisi76\" class=\"_0JjuK _5dsPU\"></p>\r\n<p id=\"la0kq7\" class=\"_0JjuK _5dsPU\">Nan mesaj sikonstans li, Majistra Wilson Jeudy te pataje k&egrave;k asp&egrave; nan eksperyans p&egrave;son&egrave;l li ak etap nan pakou li ki mennen li rive kote li ye jodi a. Li te ensiste sou enp&ograve;tans vizyon, disiplin ak det&egrave;minasyon, tout pandan li ankouraje j&egrave;n yo pran desten yo an men, souliye ke avni peyi a depann anpil de angajman ak preparasyon jenerasyon sila.</p>', 0, 0, 0, NULL, '2026-02-04', NULL, NULL),
(53, 253, 'Kyria Salina Romusca sacrée Miss Eco International Haiti 2026', '2026-02-05 21:00:00', 'politics', 'Kyria Salina Romusca a été couronnée Miss Eco Haiti 2026, un titre qui fait d’elle la représentante officielle d’Haïti au concours international Miss Eco International 2026.', '<p class=\"p1\"><span class=\"s1\">Kyria Salina Romusca a &eacute;t&eacute; couronn&eacute;e Miss Eco Haiti 2026, un titre qui fait d&rsquo;elle la repr&eacute;sentante officielle d&rsquo;Ha&iuml;ti au concours &nbsp;Miss Eco International 2026.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&Acirc;g&eacute;e de 25 ans, la jeune ambassadrice incarne une g&eacute;n&eacute;ration engag&eacute;e, alliant &eacute;l&eacute;gance, intelligence et responsabilit&eacute; sociale. D&eacute;j&agrave; connue du public, elle avait remport&eacute; en 2025 le titre de Miss Tourism World Haiti, marquant ainsi un parcours ascendant dans l&rsquo;univers des concours de beaut&eacute; &agrave; dimension internationale.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Gr&acirc;ce &agrave; ce nouveau sacre, Kyria Salina Romusca repr&eacute;sentera Ha&iuml;ti &agrave; Miss Eco International 2026, pr&eacute;vu du 13 au 24 mai prochain &agrave; Alexandrie, en &Eacute;gypte. Cet &eacute;v&eacute;nement r&eacute;unira des candidates de plus de 20 pays autour des th&eacute;matiques de la durabilit&eacute;, de la protection de l&rsquo;environnement et de l&rsquo;impact social positif.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">&Eacute;tudiante en odontologie, Kyria d&eacute;veloppe &eacute;galement des comp&eacute;tences en communication et en relations humaines. Elle s&rsquo;engage activement en faveur de l&rsquo;&eacute;ducation, de l&rsquo;environnement et de l&rsquo;action humanitaire, tout en pla&ccedil;ant sa foi chr&eacute;tienne au c&oelig;ur de son engagement. &Agrave; travers ce titre, elle ambitionne de promouvoir la culture ha&iuml;tienne, la conscience &eacute;cologique et l&rsquo;espoir aupr&egrave;s des jeunes femmes d&rsquo;Ha&iuml;ti.</span></p>', 0, 0, 0, NULL, '2026-02-06', NULL, NULL),
(54, 254, 'LAFWA PI CHÈ, un morceau de Dieumet Maurice qui peint une réalité où l’amour excessif de l’argent finit par affaiblir notre foi en Dieu.', '2026-02-10 21:00:00', 'music & video', 'Souvent, l’amour incontrôlé de l’argent prend une place importante dans notre vie, au point de fragiliser notre foi. C’est une réalité qui touche de nombreux chrétiens aujourd’hui, où la recherche d’avantages matériels passe parfois avant la relation avec Dieu.', '<p class=\"p1\"><span class=\"s1\">Souvent, l&rsquo;amour incontr&ocirc;l&eacute; de l&rsquo;argent prend une place importante dans notre vie, au point de fragiliser notre foi. C&rsquo;est une r&eacute;alit&eacute; qui touche de nombreux chr&eacute;tiens aujourd&rsquo;hui, o&ugrave; la recherche d&rsquo;avantages mat&eacute;riels passe parfois avant la relation avec Dieu. C&rsquo;est autour de cette probl&eacute;matique que &laquo; LAFWA PI CH&Egrave; &raquo;, le nouveau titre de Dieumet Maurice, propose une r&eacute;flexion sensible mais bien r&eacute;elle. Sorti il y a environ deux (2) semaines, le morceau invite chaque chr&eacute;tien &agrave; r&eacute;fl&eacute;chir au v&eacute;ritable prix de la foi et au danger qui existe lorsque l&rsquo;argent devient le centre de tous nos int&eacute;r&ecirc;ts.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Apr&egrave;s plusieurs ann&eacute;es de silence dans sa carri&egrave;re musicale, dues &agrave; des difficult&eacute;s personnelles, Dieumet Maurice revient avec un message percutant. En seulement deux (2) minutes, il met le doigt sur un sujet d&eacute;licat, la relation que nous d&eacute;veloppons avec l&rsquo;argent, laquelle perturbe souvent notre relation avec Dieu. Les paroles du morceau sont explicites : &laquo; Lajan pa ka f&egrave; m mache, pou anyen lafwa m p ap janm boukante. &raquo; Une d&eacute;claration qui rappelle que la foi n&rsquo;a pas de prix et ne doit jamais devenir une monnaie d&rsquo;&eacute;change.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">La r&eacute;flexion ne se limite pas &agrave; la dimension personnelle. L&rsquo;artiste attire aussi l&rsquo;attention sur une r&eacute;alit&eacute; pr&eacute;sente dans certains espaces religieux, o&ugrave; l&rsquo;attachement excessif aux avantages mat&eacute;riels entra&icirc;ne parfois une perte de cr&eacute;dibilit&eacute; et de fid&eacute;lit&eacute;. Lorsqu&rsquo;il affirme &laquo; Gen legliz ki p&egrave;di fid&egrave;l &raquo;, il souligne le manque de transparence et les d&eacute;rives de priorit&eacute;s o&ugrave; la croissance spirituelle passe au second plan. Le morceau devient ainsi un appel &agrave; revenir &agrave; l&rsquo;essence m&ecirc;me de la foi.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Dieumet Maurice n&rsquo;est pas un novice dans le secteur. Il a commenc&eacute; la musique d&egrave;s son enfance, &agrave; travers la chorale d&rsquo;enfants de son &eacute;glise, avant de rejoindre des groupes a cappella, de se produire sur sc&egrave;ne puis en studio. Il a progressivement trac&eacute; son chemin, du groupe SALEM BAND &agrave; Apocalypse Time, une &eacute;tape marquante de la musique &eacute;vang&eacute;lique ha&iuml;tienne. Il a collabor&eacute; avec plusieurs artistes tels que Nicky Christ, Fr&egrave; Gabe, DANAJO, Aur&eacute;lien et Matthew Brouillet, tout en d&eacute;veloppant une carri&egrave;re solo comprenant plusieurs titres et un EP intitul&eacute; Initiation, sorti en 2023.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Aujourd&rsquo;hui, Dieumet Maurice annonce un nouveau d&eacute;part pour l&rsquo;ann&eacute;e 2026, avec plusieurs projets musicaux &agrave; venir. Avec &laquo; LAFWA PI CH&Egrave; &raquo;, il ne signe pas seulement son retour sur la sc&egrave;ne musicale ; il revient avec une mission, celle de rappeler que la foi demeure la plus grande richesse de chaque chr&eacute;tien, car aucun argent ne peut l&rsquo;acheter.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">D&eacute;couvrons ensemble ce projet et encourageons l&rsquo;artiste &eacute;vang&eacute;lique Dieumet Maurice en partageant et en soutenant son &oelig;uvre &agrave; travers ce lien :</span></p>\r\n<p class=\"p1\"><span class=\"s1\">https://youtu.be/V2wpRt4EgSA?si=o4NEILk3L-KeXJ27</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">R&eacute;daction ✍️: Ronalson Blanfort</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">#konektem </span></p>\r\n<p class=\"p1\"><span class=\"s1\">#ToutKoteNenp&ograve;tKil&egrave;</span></p>', 0, 0, 0, NULL, '2026-02-11', NULL, NULL),
(55, 271, 'Palmarès Mizik/Videyo Evanjelik Fevriye 2026, pote Mizik Lanmou pou Kè w ak Nanm ou.', '2026-02-27 21:00:00', 'politics', 'Apre yon mwa fevriye ki te chaje ak lanmou, adorasyon ak mesaj espirityèl, ekip redaksyon an anonse ke Palmarès Mizik/Videyo Evanjelik Fevriye 2026 la ap pibliye ofisyèlman dimanch k ap Premye Mas 2026 la. ', '<p class=\"p1\"><span class=\"s1\">Apre yon mwa fevriye ki te chaje ak lanmou, adorasyon ak mesaj espirity&egrave;l, ekip redaksyon an anonse ke Palmar&egrave;s Mizik/Videyo Evanjelik Fevriye 2026 la ap pibliye ofisy&egrave;lman dimanch k ap Premye Mas 2026 la. yon seleksyon ki vize mete an limy&egrave; pwodiksyon ki make dezy&egrave;m mwa ane a.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan yon pery&ograve;d ki souvan konsidere k&ograve;m mwa lanmou, atis evanjelik yo pa t rate okazyon sila pou prezante mizik ak videyo ki pote mesaj lanmou, konfyans, depandans espirity&egrave;l ak angajman. Soti 7 pou rive 28 fevriye 2026, plizy&egrave; lansman pwoj&egrave; te anime platf&ograve;m dijital yo ak kominote evanjelik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Seleksyon sa a reflete div&egrave;site ak dinamis mizik evanjelik ayisy&egrave;n<span class=\"Apple-converted-space\">&nbsp; </span>nan, kote adorasyon, lanmou, rekonesans ak deklarasyon lafwa pran plizy&egrave; f&ograve;m mizikal ak vizy&egrave;l. Plizy&egrave; nan videyo sa yo atire atansyon sou rezo sosyal ak platf&ograve;m dijital yo, swa akoz kalite pwodiksyon yo, oswa tou akoz pwofond&egrave; mesaj yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Palmar&egrave;s Fevriye 2026 la montre yon endistri ki pa s&egrave;lman ap pwodui, men k ap evolye. Estrikti mizikal yo ak pwofond&egrave; paw&ograve;l yo temwaye yon jenerasyon atis ki konprann w&ograve;l yo k&ograve;m minis mizik.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan mwa lanmou an, mizik evanjelik la pa limite t&egrave;t li, li chwazi pale sou angajman, fidelite ak relasyon ki bati sou prensip diven.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ekip redaksyon konektem nan salye jef&ograve; tout atis ak ekip teknik yo, ki patisipe nan pwodiksyon sa yo, pandan n ap envite piblik la dekouvri oswa dekouvri ank&ograve; videyo ki make mwa fevriye 2026 la, dimanch kap Premye Mas la.</span></p>', 0, 0, 0, NULL, '2026-02-28', NULL, NULL),
(56, 272, 'Palmarès Mizik/Videyo Evanjelik Fevriye 2026, pote Mizik Lanmou pou Kè w ak Nanm ou.', '2026-02-27 21:00:00', 'music & video', 'Apre yon mwa fevriye ki te chaje ak lanmou, adorasyon ak mesaj espirityèl, ekip redaksyon an anonse ke Palmarès Mizik/Videyo Evanjelik Fevriye 2026 la ap pibliye ofisyèlman dimanch k ap Premye Mas 2026 la.', '<p class=\"p1\"><span class=\"s1\">Apre yon mwa fevriye ki te chaje ak lanmou, adorasyon ak mesaj espirityèl, ekip redaksyon an anonse ke Palmarès Mizik/Videyo Evanjelik Fevriye 2026 la ap pibliye ofisyèlman dimanch k ap Premye Mas 2026 la. yon seleksyon ki vize mete an limyè pwodiksyon ki make dezyèm mwa ane a.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan yon peryòd ki souvan konsidere kòm mwa lanmou, atis evanjelik yo pa t rate okazyon sila pou prezante mizik ak videyo ki pote mesaj lanmou, konfyans, depandans espirityèl ak angajman. Soti 7 pou rive 28 fevriye 2026, plizyè lansman pwojè te anime platfòm dijital yo ak kominote evanjelik la.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Seleksyon sa a reflete divèsite ak dinamis mizik evanjelik ayisyèn<span class=\"Apple-converted-space\">  </span>nan, kote adorasyon, lanmou, rekonesans ak deklarasyon lafwa pran plizyè fòm mizikal ak vizyèl. Plizyè nan videyo sa yo atire atansyon sou rezo sosyal ak platfòm dijital yo, swa akoz kalite pwodiksyon yo, oswa tou akoz pwofondè mesaj yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Palmarès Fevriye 2026 la montre yon endistri ki pa sèlman ap pwodui, men k ap evolye. Estrikti mizikal yo ak pwofondè pawòl yo temwaye yon jenerasyon atis ki konprann wòl yo kòm minis mizik.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan mwa lanmou an, mizik evanjelik la pa limite tèt li, li chwazi pale sou angajman, fidelite ak relasyon ki bati sou prensip diven.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Ekip redaksyon konektem nan salye jefò tout atis ak ekip teknik yo, ki patisipe nan pwodiksyon sa yo, pandan n ap envite piblik la dekouvri oswa dekouvri ankò videyo ki make mwa fevriye 2026 la, dimanch kap Premye Mas la.</span></p>', 0, 0, 0, NULL, '2026-06-22', NULL, '2026-06-22 20:22:15');
INSERT INTO `news` (`id`, `image_id`, `title`, `created_at`, `category`, `headline`, `content`, `reads`, `likes`, `shares`, `featured_image_ids`, `published_at`, `author_id`, `updated_at`) VALUES
(57, 273, 'Palmarès Mizik Evanjelik – Fevriye 2026', '2026-02-28 21:00:00', 'cinema', 'Mwa fevriye 2026 la make pa yon seri lansman ki temwaye vitalite ak evolisyon mizik evanjelik ayisyen an. Nan yon kontèks kote kominote a ap fè fas ak anpil defi sosyal ak emosyonèl, plizyè atis chwazi sèvi ak mizik kòm yon zouti pou pote espwa, lanmou, temwayaj ak konsyans. Soti nan retou atis ki te pran tan silans, rive nan nouvo pwodiksyon ki adrese reyalite espirityèl ak sosyal peyi a, dezyèm mwa ane a konfime dinamis yon sektè ki pa sispann renouvle tèt li.', '<p class=\"p1\"><span class=\"s1\">Mwa fevriye 2026 la make pa yon seri lansman ki temwaye vitalite ak evolisyon mizik evanjelik ayisyen an. Nan yon kontèks kote kominote a ap fè fas ak anpil defi sosyal ak emosyonèl, plizyè atis chwazi sèvi ak mizik kòm yon zouti pou pote espwa, lanmou, temwayaj ak konsyans. Soti nan retou atis ki te pran tan silans, rive nan nouvo pwodiksyon ki adrese reyalite espirityèl ak sosyal peyi a, dezyèm mwa ane a konfime dinamis yon sektè ki pa sispann renouvle tèt li.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Palmarès Mizik Evanjelik – Fevriye 2026 la mete an limyè divèsite tematik ak stil ki make peryòd la: rekonesans pou lavi, deklarasyon idantite nan Kris la, angajman nan maryaj, apèl pou lapriyè, mesaj angaje sou sitiyasyon sosyal, ak reafimasyon konfyans nan fidelite Bondye. Chak lansman vini ak yon demach atistik ki chita swa sou temwayaj pèsonèl, swa sou yon refleksyon kolektif sou lafwa ak responsabilite.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\"><strong>1.</strong> <strong>Evelyne J.B Gesper make retou li ak “Mwen la” apre prèske dis lane silans</strong></span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 7 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Psalmis Evelyne J.B ouvè sezon an ak lansman single “Mwen la” ki soti 7 fevriye 2026, premye pwodiksyon li aprè prèske dis ane absans sou sèn mizik evanjelik la.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Mwen la prezante kòm yon mizik ki pran rasin nan eksperyans pèsonèl li, ki mete aksan sou yon verite: lefèt ke nou vivan jodi a se yon gras. Nan yon kontèks kote ensekirite ak difikilte makonnen ak reyalite chak jou.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Pou psalmis lan, mizik sa a depase kad yon senp pwodiksyon atistik. Li se premye paj yon nouvo chapit nan karyè li. Nan menm mouvman an, li anonse preparasyon albòm “Mwen la”, yon pwojè ki prevwa genyen anviwon dis tit. Dapre psalmis lan, albòm nan ap chita sitou sou temwayaj, lafwa ak eksperyans pèsonèl li pandan ane silans li yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Pwojè a, ki atann pou fen ane a, ta dwe genyen patisipasyon youn oubyen de atis nan sektè evanjelik la, epi pwopoze yon melanj ant chante espirityèl ak mizik ki pote refleksyon sosyal.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Avèk “Mwen la”, Evelyne J.B Gesper<span class=\"Apple-converted-space\">  </span>reafime vokasyon li kòm yon vwa ki chwazi mete rekonesans, temwayaj ak konsyans nan sant mizik li.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/crPE_YlbDvU?si=q1bGHWwuwtfTzed_</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">2. Mwen pa pou kont mwen” : Lovenson Clerveau relanse vwa li ak yon mesaj asirans</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 8 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Lovenson Clerveau relanse vwa li sou sèn mizik evanjelik la ak videyo “MWEN PA POU KONT MWEN” ki soti 8 fevriye 2026, yon pwojè ki vini nan yon moman kle nan chemen atistik li. Apre plizyè ane kote li te fè yon pa dèyè, atis la chwazi retounen ak yon mizik ki chaje ak konviksyon ak eksperyans lavi.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la konstwi sou yon mesaj: kwayan pa janm pou kont li. Pawòl yo mete aksan sou prezans Bondye kòm gid ak pwoteksyon, menm lè reyalite a bay enpresyon kontrè a. </span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Refren an, ki repete “Yahweh ak mwen”, bay mizik la fòs li.</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Dèyè pwojè sa a gen yon istwa pèsonèl, defi sante ansanm ak nouvo responsablite familyal, se nan mitan etap sa yo mizik la pran nesans. Olye silans lan tounen yon feblès, li sèvi kòm tan refleksyon ak matirite. “Mwen pa pou kont mwen” parèt konsa kòm rezilta yon sezon kote lafwa te vin pi konkrè pase pawòl.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Sou plan kreyatif, atis la siyen konpozisyon ak pwodiksyon an, sa ki montre volonte li pou kenbe kontwòl sou direksyon mizikal li.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Avèk nouvo pwodiksyon sa a, Lovenson Clerveau reafime angajman li pou sèvi ak mizik kòm zouti pou ranfòse lafwa ak pote konsolasyon. “Mwen pa pou kont mwen” deja pozisyone tèt li kòm yon mizik konfyans pou sila yo k ap travèse moman ensèten, menm nan silans ak solitid, prezans Bondye rete fidèl.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/jlEyomS1va8?si=cQN6K_K1PowhCio_</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">3. Loutchina Décius enspire odyans lan ak “Moun Pa Konn Renmen Konsa”</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 10 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Videyo ofisyèl “Moun Pa Konn Renmen Konsa”, ki</span></p>\r\n<p class=\"p1\"><span class=\"s1\">dire 4mn 19 s pibliye 10 fevriye 2026, make yon nouvo etap nan karyè Loutchina Décius sou sèn mizik kreyòl la. Chante sa a, ekri ak pwodui pa Genoldens Desulma, se yon melanj de emosyon, lanmou ak refleksyon sou rekonesans, kote mesaj espirityèl ak sansiblite imen rankontre nan yon fason natirèl.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan tèks la, atis la eksprime bèlte lanmou ki depase tout atant: “Moun pa konn renmen konsa”, “Ou konble m, fè m bliye tout pwoblèm mwen”, ak “Ou fè ti kè mwen kontan, sous bonè mwen”. Pawòl sa yo montre jan lanmou, kit li ant moun oswa nan prezans Bondye, kap pote sekirite, lapè ak rekonfò enteryè.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Sou plan vizyèl, kolaborasyon Alexis Eclesiaste ak Riché Richenn pote mizik la nan yon dimansyon plis entim ak vivan. Videyo a mete aksan sou emosyon, entèraksyon pèsonaj yo ak lajwa ki soti nan lanmou ak rekonesans, pandan refren an tounen yon pwen kote odyans lan fasil pou konekte ak mesaj la (Moun pa konn renmen konsa).</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Depi lansman li sou platfòm dijital yo, “Moun Pa Konn Renmen Konsa” resevwa yon repons trè pozitif, sitou nan mitan jèn ki idantifye ak mesaj lanmou sensè ak sans rekonesans li pote. Mizik la montre jan gospel kreyòl modèn ka kontinye evolye, enkòpore emosyon imen san pèdi pwofondè espirityèl li, e li etabli Loutchina Décius kòm yon vwa enpòtan nan mizik ki melanje lanmou, lafwa ak temwayaj pèsonèl.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/BxPNKzUY2ts?si=exszLctXY2p3qZTT</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">4. Celigny Dathus prezante “Mwen Paka Konte”, yon nouvo chan temwayaj ki make kòmansman ane 2026 la</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 12 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis evanjelik Celigny Dathus make kòmansman ane 2026 la ak lansman videyo ofisyèl “Mwen Paka Konte”, ki disponib depi 12 fevriye sou tout platfòm dijital yo. Single sa a, ki te deja pibliye nan fen 2025, vini kòm yon temwayaj mizikal ki chaje ak rekonesans, kote atis la ouvè kè li sou chemen lafwa li ak transfòmasyon li viv.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan tèks la, Celigny Dathus adopte yon ton entim ak refleksyon: “Mwen sonje kote’m te ye, mwen sonje kote’w te pran’m.” Pawòl sa yo trase yon liy klè ant yon pase ki make pa feblès ak yon prezan ki chita sou gras. Metafò “yon mouton ki kite patiraj li” a vin ranfòse dimansyon biblik mesaj la, pandan li ilistre eta yon moun ki te pèdi direksyon li avan li jwenn delivrans.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Refren an,“Mwen paka konte (tout mizè ou pase avè’m)”, pote nwayo emosyonèl mizik la. Deklarasyon tradui limit imen fas ak grandè sakrifis ak fidelite Bondye. Se yon rekonèsans ki pa kalkile, ki pa mezire, men ki soti nan konsyans yon lavi ki chanje.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan yon sektè ki ap evolye, mizik sa a pozisyone li kòm youn nan pwodiksyon ki make kòmansman ane a, pandan li kenbe esans espirityèl ki defini estil atis la.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/Wk4xwNi7R9U?si=WemImK0OPiDtUQ9h</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">5. Beethoven Chenet selebre lanmou ak lafwa nan “Siwomyèl”</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 12 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Beethoven Chenet make sèn mizik evanjelik ayisyen ak lansman videyo ofisyèl “Siwomyèl”, ki pibliye 12 fevriye 2026. Mizik sa a, yon konpa love ki dous ak melodi, pou sèvi kòm yon temwayaj pwofon sou lanmou, rekonesans ak respè nan relasyon maryaj, pandan li rete solidman anrasinen nan lafwa kretyen.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a se yon dedikasyon pèsonèl Beethoven Chenet fè pou madanm li, li dekri li kòm sous enspirasyon ak gid nan lavi li. Pawòl yo, tankou “kite siwo a koule”, prezante lanmou kòm yon eksperyans dous, sensè, valè espirityèl. Mizik la montre jan relasyon maryaj ka reflete lafwa, rekonesans, ak prezans Bondye nan lavi kwayan yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Sou plan espirityèl, “Siwomyèl” raple ke maryaj se yon apèl sakre: onore ak cheri patnè ou se yon fason pou montre lafwa ak rekonesans pou Bondye.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Videyo a, pwodui pa Productions Koulekreyol bay mizik la yon prezantasyon ki fasil pou odyans lan konekte avèk li.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">“Siwomyèl” tounen yon referans nan mizik kreyòl modèn kote lanmou, relasyon ak espirityalite rankontre.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/1oX3GjjCsMQ?si=0kBUNG-r_tN-ZXFQ</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">6. Althieu Jean Baptiste renouvle temwanyaj lafwa ak “Ou Fè’l Vre” (Acoustic Version)</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 14 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan yon epòk kote anpil moun ap chèche espwa, konsolasyon ak sans nan lavi yo, atis evanjelik Althieu Jean Baptiste retounen ak yon mesaj atravè “Ou Fè’l Vre (Acoustic Version)”, yon kantik rekonesans ki mete aksan sou fidelite Bondye nan lavi moun. Videyo ofisyèl la te parèt 14 fevriye 2026, yon dat senbolik ki selebre lanmou, men fwa sa a, lanmou divin ki pa janm febli.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">“Ou Fè’l Vre” soti nan albòm atis la ki te lanse an 2020, men nouvo aranjman acoustic sa a bay mizik la yon lòt dimansyon espirityèl. Li raple kijan Bondye kontinye akonpli pwomès Li yo, menm nan moman difisil yo. Se yon deklarasyon lafwa ki envite chak kwayan sonje mirak Bondye deja fè nan lavi yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atravè pwojè sa a, Althieu Jean Baptiste mete devan yon mesaj rekonesans ak adorasyon: Bondye toujou fidèl, e gras Li depase tout limit imen.</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Nan mizik evanjelik ayisyen an, “Ou Fè’l Vre (Acoustic Version)” parèt kòm yon zèv ki ini adorasyon, refleksyon ak espwa.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Se konsa, atravè mizik sa a, Althieu Jean Baptiste kontinye sèvi kòm yon vwa ki pote limyè, raple mond lan ke dèyè chak viktwa, chak gerizon ak chak delivrans, gen men Bondye ki toujou ap aji.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/sNNcUbmHslI?si=TMjVVIp3E3_mQ9Yw</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">7. Sony Delly leve idantite wayal kwayan an nan “Ou Defini’m”</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 15 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">15 fevriye 2026, Sony Delly mete disponib videyo ofisyèl “Ou Defini’m”, yon pwodiksyon ki plonje dirèkteman nan kesyon idantite espirityèl ak pozisyon wayal kwayan an nan Kris la. Atravè mizik sa a, atis la pwopoze yon deklarasyon lafwa solid, ki chita sou verite biblik konsènan diyite, otorite ak eritaj Bondye rezève pou pitit Li yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan devlopman tèks la, Sony Delly mete aksan sou transfòmasyon enteryè ki fèt lè yon moun rive konprann kiyès li ye nan Bondye. Idantite sa a pa sòti nan opinyon moun, ni nan limit sikonstans lavi a, men nan apèl wayòm Bondye a.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la pa limite li ak dimansyon otorite ak diyite sèlman, li mete tou aksan sou karaktè Bondye ki fòme ak matirite kwayan an. Lanmou Li bay sekirite, sajès Li pote direksyon, epi pasyans Li modle karaktè. Se pa yon kesyon pouvwa senpman, men yon pwosesis transfòmasyon enteryè kote moun nan vin reflete imaj Bondye pi plis chak jou.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/tSEtX5SM8_Q?si=6eQf24WE7mhArVNi</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">8. Dieumet Maurice Repran Sèn Mizik Evanjelik ak “Ou Bon”</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 15 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Apre plizyè ane silans ki te koze pa defi pèsonèl ak chanjman enpòtan nan lavi li, Dieumet Maurice fè yon retou remakab sou sèn mizik evanjelik ayisyen an avèk de nouvo single ki make kòmansman ane 2026 la. Nan mwa janvye, li lage “Lafwa Pi Chè”, yon mizik ki eksplore relasyon ant lafwa ak materyalis. Chante a raple kwayan yo ke lafwa pa gen pri e ke okenn avantaj materyèl pa dwe ranplase relasyon ak Bondye.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Swivan lansman sa a, 15 fevriye 2026, Dieumet prezante “Ou Bon” (Visualizer), yon mizik ki selebre lanmou ak fidelite Bondye. Mizik la envite odyans lan reflechi sou prezans inebranlabl Bondye nan lavi chak kwayan, pandan pawòl yo ranfòse rekonesans, konfyans ak adorasyon. Tit sa a vini tou pandan atis la ap prepare yon seri nouvo chante ak yon albòm kap vini.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Avèk “Lafwa Pi Chè” ak “Ou Bon”, Dieumet Maurice pa sèlman retounen sou sèn nan, li etabli yon nouvo faz nan karyè li, kote mizik vin yon zouti pou ranfòse lafwa, ankouraje refleksyon espirityèl, epi raple tout kwayan ke Bondye rete fidèl, inebranlabl, e toujou prezan nan lavi yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/ypzaOi2xtZ4?si=Y-qLc-KXv277Is7x</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">9. Pastor Jean‑Claude Dérisier lanse “Di Yon Mo Senyè Souple”</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 16 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">16 fevriye 2026, Pastor Jean‑Claude Dérisier prezante videyo ofisyèl “Di Yon Mo Senyè Souple”, yon mizik ki selebre pouvwa lapriyè ak lafwa nan Bondye. Chante a raple kwayan yo ke yon sèl mo Bondye ka chanje lavi, leve moun nan difikilte, epi pote rekonfò ak diyite.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Pawòl yo pran inspirasyon nan istwa biblik tankou Josef, Estè ak Abraham, mete aksan sou kapasite Bondye pou reponn lapriyè epi akonpaye pitit Li yo nan tout etap lavi yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">“Di Yon Mo Senyè Souple” se yon mesaj vivan ki konbine adorasyon ak temwayaj, ki ranfòse pozitifite ak konfyans nan Bondye pou tout odyans mizik evanjelik la.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/6ir9188wCIg?si=f-AXWoV_Pbr3aoZL</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">10. Ti Doudou” : Fre Gabe ak Sr Gabe mete lanmou kretyen an an valè nan yon videyo</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 18 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">18 fevriye 2026, Fre Gabe prezante videyo ofisyèl pou mizik “Ti Doudou”, yon kolaborasyon ak Sr Gabe, ki sòti nan albòm Inspiration Divine. Chante sa a vini kòm yon omaj pou lanmou ki bati sou fondasyon Kris la, yon lanmou ki pa kraze malgre eprèv, soufrans ak difikilte lavi a pote.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan “Ti Doudou”, mesaj la klè: lè JEZI se sant relasyon an, tanpèt pa ka detwi sa Bondye ini. Pawòl yo dekri reyalite plizyè koup kretyen ki fè fas ak moman difisil, men ki chwazi rete ini, padone, epi mache ansanm nan lafwa. Mizik la pote yon ton dous, men chaje ak espwa, yon envitasyon pou koup yo sonje rezon espirityèl ki fè yo te di “wi” devan Bondye.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Sou plan vizyèl, videyo a mete an avan yon istwa pwofon, ak patisipasyon JerryBed kòm figi prensipal. Reyalizasyon an pote siyati Fre Gabe li menm, ki asime plizyè wòl nan pwojè a: direktè, editè, coloriste, enjenyè mix ak mastering. Travay sinematografik la siyen pa KN Visuals.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Fre Gabe ak Sr Gabe ofri kominote evanjelik la yon mesaj ki klè: lè Jezi se fondasyon yon relasyon an, okenn difikilte pa ka fè li tonbe.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/U3x_YcZk3uU?si=XCt0YKJj0xYHk7wi</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">11. Mwen Deklare O Non de Jezi” – Yon deklarasyon lafwa ki mete Syèl la kòm objektif final</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 19 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan dat 19 fevriye 2026, Jean Claude Dérisier(Zoom), ansanm ak Joy Clerf Dérisier, prezante mizik “Mwen Deklare O Non de Jezi”, yon kantik ki chaje ak konviksyon espirityèl ak detèminasyon pou mache dwat sou chimen delivrans lan.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a bati sou yon bèl deklarasyon: “Anbisyon mwen se pou m sove.” Nan yon epòk kote anpil moun ap kouri dèyè reyalizasyon materyèl, mizik la vini kòm yon repons klè ke pi gwo objektif lavi a se antre nan wayòm Bondye. Pawòl yo ensiste sou yon verite biblik: lajan, lò, dyaman, bèl kay ak tout onè ki sou latè gen pou pase, sèl Pawòl Bondye ak glwa Li ki rete pou tout tan.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Avèk “Mwen Deklare O Non de Jezi”, Jean Claude Dérisier ak Joy Clerf Dérisier ofri kominote evanjelik la yon kantik ki ankouraje detèminasyon, disiplin espirityèl ak konsyans sou sa ki vrèman gen valè. Se yon mizik ki raple ke, nan mitan bri mond lan, desizyon ki pi enpòtan an rete sa a: chwazi Syèl la kòm destinasyon final.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/W6hCkh2kFiI?si=KCt-IIiZiw4F6B5D</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">12. Sou Pawòl Bondye, Astharmonie Chwazi Kanpe</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 20 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan yon moman kote anpil moun ap fè fas ak dout ak ensètitid, “KANPE” vini tankou yon souf ankourajman. Atravè videyo ofisyèl sa a ki soti 20 fevriye 2026, gwoup Astharmonie mete devan yon mesaj enpòtan : Bondye rete fidèl, kèlkeswa sezon an.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la pote siyati konpozitè Genoldens Desulma (Dens Music Haiti) ansanm ak Alexis Fortuné, ki asire vwa prensipal la. Depi kòmansman mizik la, pawòl yo trase yon verite ki depase tan : menm si tout bagay chanje, Bondye pa chanje. Li se “Dieu Israël” ki kenbe pawòl Li e ki reyalize sa Li pwomèt.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Refren an se kè mesaj la : Bondye pa janm pale pou Li pa aji. Li gen plan pou siksè pitit Li yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/w7nLTC2wQNs?si=Uk0GhANHaUD5ddCt</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">13. Les Elus ak David Deg Mete Lespri an Aksyon ak “La Flamma”</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\"> 🗓️ 22 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Gwoup Les Elus, ansanm ak David Deg, prezante videyo ofisyèl pou mizik “La Flamma” 22 fevriye 2026, yon single ki melanje enèji, espirityalite, ak yon apèl pou devouman nan wayòm Bondye. Mizik la transmèt yon mesaj fò pou kwayan yo: pou briye ak manifeste prezans Bondye nan lavi yo, fòk yo dakò boule, sakrifye, epi chèche wayòm Bondye an premye.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Pawòl yo ankouraje chak kwayan mete Jezi kòm modèl yo epi donnen fwi Lespri a nan lavi yo. “La Flamma” ofri yon eksperyans espirityèl ki envite moun pran angajman, epi viv yon lavi kote prezans Bondye klere nan chak aksyon. Videyo a, ak melodi dinamik ak koregrafi ki pote vizyon mizik la fè single sa a tounen yon zouti fò pou ankourajman ak adorasyon nan kominote evanjelik la.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/J1TsEF9xaOw?si=0QEnMN_DAq05w0IK</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">14. Fr Gabe frape feat Deborah “Viv Ansanm”, yon mesaj dirèk pou Babekyou</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 24 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Pandan l ap prepare pou vant siyati li ki pwograme pou 8 mas 2026 nan palè minipal , Fr Gabe ansanm ak Deborah Henristal lanse videyo ofisyèl “Viv Ansanm”, yon nouvo pwodiksyon ki soti 24 fevriye 2026. Mizik sa a vini jis kèk jou apre clip “Ti Doudou”. </span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan “Viv Ansanm”, Fr Gabe adopte yon stil rap angaje pou l adrese dirèkteman Babekyou, nan yon tèks ki chaje ak kesyonnman, fristrasyon ak apèl pou konsyans. Pawòl yo pa pase pa kat chemen: yo pale de ensekirite, doulè manman k ap kriye, jèn san avni, politisyen ki akize de konplisite, epi yon sosyete ki sanble ap tonbe anba pwòp pwa li.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Fr Gabe mete anfas reyalite sosyal la ak rèv yon Ayiti kote jenès la ta ka jwenn opòtinite, kote lekòl ak lopital ta fonksyone, kote zam pa ta ranplase liv. Li denonse sistèm nan, men li envite tou chak sitwayen gade tèt li nan glas, pran responsabilite li, epi chèche yon lòt direksyon.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Avèk videyo sa a, Fr Gabe montre yon lòt fas nan kreyasyon li: yon atis ki pa pè antre nan sijè sansib, ki melanje lafwa, angajman sosyal ak ekspresyon pèsonèl. “Viv Ansanm” deja anonse kòm yon mizik ki pral fè pale, reflechi, epi pwovoke deba nan mitan piblik la.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/9-aDkbKktBA?si=jhLrRnrTvZg-bAir</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">15. Derson Alcide lanse “Bondye Ou Bon”, yon mizik rekonesans ak adorasyon</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 25 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Derson Alcide prezante yon mizik ki selebre lanmou, pwoteksyon ak fidelite Bondye. “Bondye Ou Bon”, ki sòti 25 fevriye 2026, envite odyans lan reflechi sou prezans inebranlab Bondye nan lavi chak moun, lajounen kou lannwit.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Chante a se yon temwayaj pèsonèl kote atis la remèsye Bondye pou padon, jistis ak favè<span class=\"Apple-converted-space\">  </span>li. </span></p>\r\n<p class=\"p1\"><span class=\"s1\"> “Bondye Ou Bon” mete aksan sou rekonesans ki sòti nan kè, epi tounen yon zouti adorasyon ak ankourajman espirityèl pou tout moun k ap tande li.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/BZLXIG2CJEs?si=ZYVjknDlcX_PUU1l</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><strong><span class=\"s1\">16. Gospel Saphir Ministry reprann “Omemma” ak deklarasyon lafwa “Bondye m nan ap travay”</span></strong></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🗓️ 28 fevriye 2026</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Gospel Saphir Ministry prezante “Bondye m nan ap travay”, yon cover enspire de “Omemma”, konpozisyon orijinal Chandler Moore ak Tim Godfrey. Sòti 28 fevriye 2026, mizik la pote yon mesaj ankourajman: pwosesis la poko fini, e Bondye toujou ap aji menm lè nou pa wè sa.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan adaptasyon sa a, Gospel Saphir Ministry mete aksan sou konfyans ak pasyans nan mitan eprèv.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Sou plan mizikal, aranjman vwa yo pote siyati Peterson Luc ak Stanley Shegger Aris, pandan sekans, mixaj ak mastering fèt pa Shegger Beats. Pwodiksyon ak realizasyon videyo a asire pa GSM Studio.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Avèk cover sa a, Gospel Saphir Ministry pa sèlman reprann yon chan entènasyonal, yo adapte li nan yon kontèks kote anpil moun bezwen sonje ke, malgre presyon ak ensekirite lavi a, Bondye pa janm abandone pitit Li. </span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">“Bondye m nan ap travay” prezante kòm yon mizik ki ankouraje lafwa, epi ki envite chak moun rete fèm pandan Bondye ap kontinye fè travay Li.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">🔗 https://youtu.be/p0Wffv9DT5o?si=Z6HXvgE6tYF5fG2_</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Ansanm, pwodiksyon sa yo trase yon tablo ki montre yon endistri k ap grandi, k ap pran risk kreyatif, epi k ap chèche reponn a bezwen espirityèl yon jenerasyon k ap chèche direksyon. Mizik evanjelik la pa limite tèt li ak adorasyon tradisyonèl sèlman, li antre nan sijè lanmou, kriz sosyal, idantite, maryaj ak angajman pèsonèl.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Palmarès Fevriye 2026 se yon temwanyaj sou mouvman, sou kreyativite ak sou konsyans yon sektè ki konprann misyon li. Pandan ane 2026 la ap kontinye, pwodiksyon mwa fevriye yo deja poze yon baz solid, ki anonse yon sezon kote mizik ap rete yon vwa ki ankouraje, epi raple ke nan mitan tout bagay, lafwa kontinye rete pwen referans lan.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s2\">Redaksyon:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Vanauscheca Bouzy</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s2\">Konsepsyon Grafik:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Jo-Flung Bouzy & Abdullah Mode</span></p>', 0, 0, 0, NULL, '2026-06-22', NULL, '2026-06-22 20:20:23'),
(58, 280, 'Jo-Flung Bouzy pral kouvri, an eksklizivite, 6zyèm edisyon Caribbean Worshippers nan Peyi Bahamas pou konektem.', '2026-03-09 21:00:00', 'international', 'Nan yon moman kote inisyativ evanjelik yo ap pran plis anplè nan Karayib la, Zye plizyè milye fidèl ak obsèvatè fikse sou youn nan pi gwo rasanbleman espirityèl nan rejyon an,Caribbean Worshippers. Ane sa a make sizyèm edisyon evènman entènasyonal sa, ki pral dewoule ankò nan vil Nassau, nan Bahamas. Se yon edisyon ki deja pwomèt yon ansanm aktivite ki pral make listwa.', '<p class=\"p1\"><span class=\"s1\">Nan yon moman kote inisyativ evanjelik yo ap pran plis anpl&egrave; nan Karayib la, Zye plizy&egrave; milye fid&egrave;l ak obs&egrave;vat&egrave; fikse sou youn nan pi gwo rasanbleman espirity&egrave;l nan rejyon an,Caribbean Worshippers. Ane sa a make sizy&egrave;m edisyon ev&egrave;nman ent&egrave;nasyonal sa, ki pral dewoule ank&ograve; nan vil Nassau, nan Bahamas. Se yon edisyon ki deja pwom&egrave;t yon ansanm aktivite ki pral make listwa.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pou pa kite okenn ti detay pase, medya Konekte M pran angajman pou pote tout nouv&egrave;l yo, nan tan rey&egrave;l, pou piblik la.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan sans sa a, Media a deside deplase pami teknisyen ki pi devwe li yo, Jo-Flung Bouzy, ki pral sou plas pou kouvri aktivite a san manke yon l&ograve;sy&egrave;, nan objektif pou f&egrave; piblik la viv chak moman enp&ograve;tan yo.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Depi plizy&egrave; ane, Caribbean Worshippers etabli t&egrave;t li k&ograve;m yon gwo platf&ograve;m kote adorasyon, mesaj espirity&egrave;l ak inite nan mitan tout kretyen rankontre. Chak edisyon rasanble plizy&egrave; sant&egrave;n patisipan ki soti nan div&egrave;s peyi nan Karayib la ak l&ograve;t kote nan mond lan. </span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Ane sa a, Neslin Destilhomme, vizyon&egrave; prensipal Caribbean association nan, anonse yon pwogram ki gen plis anpl&egrave; toujou, ak prezans plizy&egrave; p&egrave;sonalite enp&ograve;tan nan milye evanjelik la, lid&egrave; relijye, atis gospel ak orat&egrave; ent&egrave;nasyonal.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Nan mitan gwo preparasyon sa yo kap f&egrave;t, prezans Jo-Flung Bouzy sou teren an reprezante yon avantaj estratejik pou Konekte M. Konnen pou disiplin li, dinamis li ak sans pwofesyonalis li, teknisyen an gen misyon pou asire pa gen okenn moman enp&ograve;tan ki rate, depi ouv&egrave;ti seremoni yo, rive sou gwo moman adorasyon yo, ent&egrave;vyou ak envite espesyal yo, jiska gwo moman nan ev&egrave;nman an k ap domine nan Nassau pandan tout dire ev&egrave;nman an.</span></p>\r\n<p class=\"p2\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Pou koulye a teknisyen an deja okap nan wout pou rantre Bahamas, jodi madi 10 Mas 2026 la.</span></p>\r\n<p class=\"p1\">&nbsp;</p>\r\n<p class=\"p1\"><span class=\"s1\">Redaksyon ✍️: Ronalson Blanfort</span></p>', 0, 0, 0, NULL, '2026-03-10', NULL, NULL);
INSERT INTO `news` (`id`, `image_id`, `title`, `created_at`, `category`, `headline`, `content`, `reads`, `likes`, `shares`, `featured_image_ids`, `published_at`, `author_id`, `updated_at`) VALUES
(59, 281, 'Palmarès Mizik/Videyo Evanjelik – Mas 2026', '2026-03-31 21:00:00', 'society', 'Mwa mas 2026 la make pa yon richès pwodiksyon nan mizik evanjelik ayisyen an, kote plizyè atis kontinye sèvi ak talan yo pou pote mesaj ki pale ak lavi chak jou. Nan yon kontèks sosyal ki souvan chaje ak ensètitid, mizik gospel la kontinye jwe yon wòl enpòtan kòm yon sous espwa, direksyon ak ankourajman.\r\n\r\nPalmarès sa a mete an avan mizik ak videyo ki make mwa a, pa sèlman pou kalite pwodiksyon yo, men sitou pou kapasite yo genyen pou transmèt mesaj ki klè, vivan e ki fasil konekte ak odyans lan.', '<p class=\"p1\"><span class=\"s1\">Mwa mas 2026 la make pa yon richès pwodiksyon nan mizik evanjelik ayisyen an, kote plizyè atis kontinye sèvi ak talan yo pou pote mesaj ki pale ak lavi chak jou. Nan yon kontèks sosyal ki souvan chaje ak ensètitid, mizik gospel la kontinye jwe yon wòl enpòtan kòm yon sous espwa, direksyon ak ankourajman.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Palmarès sa a mete an avan mizik ak videyo ki make mwa a, pa sèlman pou kalite pwodiksyon yo, men sitou pou kapasite yo genyen pou transmèt mesaj ki klè, vivan e ki fasil konekte ak odyans lan.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">1. “A Zewo” – Yon klasik ki reprann fòs li nan yon nouvo jenerasyon (2 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Frè Gabe & Jean René Charles</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Frè Gabe (direksyon atistik, mix & mastering)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Frè Gabe pote yon nouvo lavi nan “A Zewo”, yon chante ki deja make anpil jenerasyon. Mizik la kontinye pote menm verite a: viktwa Kris la deja akonpli, e okenn sitiyasyon pa ka chanje sa.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nouvo vèsyon an mete plis aksan sou aksè ak son modèn, pandan li kenbe sans espirityèl orijinal la. Patisipasyon Jean René Charles bay pwojè a yon dimansyon espesyal, paske li kreye yon pon ant jenerasyon yo. Rezilta a se yon mizik ki pa sèlman sonnen byen, men ki fè moun reprann konfyans yo nan lafwa yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/NydtNzZEODc?si=8X_-0gIZ0_Sx0oU0</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">2. “Semans Profetik” – Yon mesaj sou pasyans ak konfyans (5 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe & Holy Music</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan kolaborasyon ak Holy Music, Fre Gabe prezante yon mizik ki baze sou yon lide pwisan: pawòl Bondye pa janm pèdi.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">“Semans Profetik” mete aksan sou pwosesis la: sa Bondye di a pran tan pou li parèt, men li toujou rive. Mizik la pale ak moun ki poko wè rezilta yo, men ki bezwen kenbe lafwa yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/tgad9ssfoUE?si=J-aNb2P9zhDM-MGq</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">3. “Map Rive” – Lafwa ki refize bay legen (6 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Chrystelha</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Pierre Alan Gabriel(Frè Gabe)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Chrystelha pote yon mizik ki chaje ak detèminasyon. “Map Rive” pa inyore difikilte yo, men li mete aksan sou kapasite pou depase yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Pawòl yo montre yon moun ki konprann ke chemen an pa fasil, men ki refize rete bloke. Enèji vokal la ak ritm lan soutni mesaj la, sa ki fè mizik la tounen yon sous motivasyon pou moun k ap lite pou avanse.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/Gzfv-irU-Zw?si=70ld1zcghxTHrbYR</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">4. “Gras Enfini” – Lè lanmou Bondye depase limit imen (8 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe & Stanley Georges</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Fre Gabe ak Stanley Georges mete aksan sou yon reyalite enpòtan: gras Bondye pa depann de sa moun merite.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la devlope sou lide ke menm lè moun fè erè, Bondye toujou bay yon lòt chans. Entèpretasyon an dous, sa ki bay mizik la yon dimansyon ki ka touche nenpòt moun.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/ZGsIBYrGsFU?si=m1LybVUIVzEMocG0</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">5. “Pa Fè Sa” – Yon apèl klè pou chanje direksyon (9 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan mizik sa a, mesaj la dirèk e san konpwomi. Fre Gabe chwazi abòde konpòtman ki ka detwi lavi espirityèl yon moun, epi li envite odyans lan reflechi sou chwa yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Se yon mizik ki pa chèche fè moun santi yo alèz, men pito pouse yo pran konsyans. Fòs li chita nan jan li di bagay yo klèman.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/HaZdolLBfQM?si=2HN98HFvsebiPWqy</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">6. “JESUS CHRIST” – Mete Jezi nan sant lavi a<span class=\"Apple-converted-space\">  </span>(9 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe & Les Elus</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Ak patisipasyon Les Elus, mizik sa a prezante Jezi kòm sous lavi, delivrans ak direksyon.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Estrikti chante a ak referans biblik yo bay li yon dimansyon ansèyman, pandan li rete yon mizik adorasyon. Li envite odyans lan retounen nan fondasyon lafwa yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/ndZNuEThoII?si=hFwp6FQaROgm1Uqx</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">7. “Inspiration Divine” – Yon refleksyon sou valè ak misyon moun<span class=\"Apple-converted-space\">  </span>(10 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe & Chrystelha</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Nan kolaborasyon ak Chrystelha, mizik sa a mete aksan sou idantite ak objektif lavi.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Li montre ke chak moun gen yon plas ak yon misyon, e li ankouraje moun pran konsyans de valè yo. Ton an kalm, men mesaj la fò e klè.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/hhhd0CUEHkw?si=Pz4Z5H_LTr-97PiM</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">8. “Twòn Nan Gon Moun” – Espwa nan mitan difikilte (10 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe & Palmyre Seraphin</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: DonGad Beats, Fre Gabe, Dickson Guillaume</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Fre Gabe ak Palmyre Seraphin prezante yon mizik ki montre kijan Bondye ka chanje nenpòt sitiyasyon.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Pawòl yo mete aksan sou transfòmasyon: soti nan doulè pou rive nan viktwa. Mizik la pote yon mesaj espwa pou moun k ap travèse moman difisil.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/-at5-WGDHJE?si=jRvDGD3c8sOhNjFx</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">9. “Le Roi de Gloire” – Yon adorasyon ki rasanble<span class=\"Apple-converted-space\">  </span>(11 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe & Les Elus</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Kolaborasyon ak Les Elus a bay mizik sa a yon fòs espesyal.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Repete refren yo ak amoni vokal yo kreye yon santiman adorasyon ki fasil pou moun antre ladan l. Li mete aksan sou grandè Bondye san konplike mesaj la.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/oBe1LuQjPuM?si=fQvt5plHJGVvyUwU</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">10. “Sakrifi­s Mwen” – Yon relasyon pèsonèl ak Bondye (11 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Psalmiste Sterlande Etienne</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Mission Église de Dieu de la Conquête</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Sr Sterlande Etienne prezante yon mizik ki se yon priyè.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Pawòl yo montre yon moun ki deside bay tèt li nèt bay Bondye, pa sèlman nan pawòl, men nan lavi li. Mizik la tounen yon eksperyans pou kwayan k ap koute li.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/Kx150gXZqi8?si=mIGYxYtBfzUOYYW8</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">11. “Al Di Yo” – Yon mesaj ki mande aksyon (12 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe & Mike Lee Elminis</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Fre Gabe ak Mike Lee Elminis pote yon mizik ki gen yon sans ijans.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Li envite kominote a pa kenbe lafwa pou tèt yo sèlman, men pataje li. Mizik la mete aksan sou preparasyon ak responsablite espirityèl.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/tsn4dpytO4M?si=hNfx5V1efz7CoFw8</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">12. “Dife” – Yon lafwa ki pa ka etenn<span class=\"Apple-converted-space\">  </span>(14 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Fre Gabe</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Inspiration Divine Studio</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">“Dife” prezante lafwa kòm yon fòs ki vivan e ki pa ka disparèt.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la montre ke yon kretyen pa<span class=\"Apple-converted-space\">  </span>kraze fasil, paske li pote prezans Bondye anndan li. Pawòl yo montre idantite Kretyen kòm moun ki kanpe solid, menm lè lavi pote defi.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/Srquhyg0wq4?si=TCxI7Smh6XDQDmZ7</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">13. “Fe Kèw Kontan” – Kenbe lajwa a malgre tout bagay Jorvin Keyz (14 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Jorvin Keyz</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Jorvin Keyz mete aksan sou konfyans nan Bondye kòm sous kè kontan.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la raple ke menm nan moman difisil, gen rezon pou rete pozitif, paske Bondye toujou ap travay.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/aDoZwQsShfo?si=b_unxQXwL1K76_KB</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">14. “Tan Pou’m Temwanye” – Pataje sa Bondye fè Carl Emile (15 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Carl Emile</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: C-Films, Jean Daniel Pierre</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Carl Emile prezante temwayaj kòm yon zouti pou ankouraje lòt moun.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Mizik la montre kijan Bondye reponn priyè epi chanje lavi. Li ankouraje kwayan yo pale de eksperyans yo pou bati lafwa lòt moun.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/6nD5fz5evNY?si=LOjVKfXzU1bSaMWN</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">15. “Action de Grâce” – Rekonesans apre eprèv Yvensonn Ayiti (15 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Yvensonn Ayiti</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Yvensonn Ayiti pote yon mizik ki baze sou yon istwa vivan.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Li montre kijan difikilte pa dènye mo a, paske Bondye toujou gen kapasite pou retabli. Mizik la envite fanmi rete nan rekonesans.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/V6-RXeNUZ_E?si=kMofxNnUVI4JyTP_</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">16. “Yaweh” – Grandè Bondye san limit<span class=\"Apple-converted-space\">  </span>(19 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: D-MUSIC</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">D-MUSIC mete aksan sou pouvwa Bondye sou tout kreyasyon. Mizik la sèvi ak pawòl ki fò pou montre ke pa gen okenn fòs ki ka konpare ak li.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽<span class=\"Apple-converted-space\">  </span>https://youtu.be/0EqOsBuR7tc?si=rEm7toEvSlX8VTsz</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">17. “Original” – Asirans nan Jezi (21 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Jean Claude Derisier Zoom, Clavens Derisier, Dieudonné Derisier T.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Jean Claude Derisier Zoom ak kolaboratè li yo pote yon mizik ki chaje ak konfyans. Pawòl yo montre ke ak Jezi, yon moun pa ka fini mal. Mizik la vin yon deklarasyon lafwa.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/lrh2wbPjPnI?si=Muc6P7UHIDSSEx8d</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">18. “Sòlda” – Kanpe fèm nan lafwa<span class=\"Apple-converted-space\">  </span>(22 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Koral DEG feat Oliver Mathéo & Ariana Lafond</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Desulma Genoldens, Shegger Beat, David Morinvil</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Koral DEG prezante kwayan yo kòm sòlda ki pa pè batay. Mizik la mete aksan sou lafwa kòm zam prensipal, e li ankouraje kominote a rete fèm malgre difikilte.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/47CaSK8FlKQ?si=iVfDoL_H70BCOm1n</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">19. “Restore’m” – Yon lavi ki ka repare<span class=\"Apple-converted-space\">  </span>(26 mas 2026)</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Atis: Emmanuel CENE</span></p>\r\n<p class=\"p1\"><span class=\"s1\">Pwodiksyon: Rodberry Jacques</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Psalmiste Emmanuel CENE ofri yon mizik ki santre sou gerizon ak renouvèlman.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Pawòl yo montre yon moun ki vin devan Bondye ak senserite pou mande chanjman. Mizik la pote yon mesaj espwa pou moun ki santi yo kraze.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">👉🏽 https://youtu.be/UdY480M_Zwo?si=F5Du_RoWEayZFiij</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Palmarès mwa mas 2026 la montre yon mizik evanjelik ki pa sèlman ap evolye sou plan son, men ki kontinye kenbe fòs li nan mesaj li yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Chak mizik pote yon pati nan lavi: gen ki ankouraje, gen ki korije, gen ki raple, men yo tout gen menm objektif ede moun rete konekte ak lafwa yo.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s2\">Redaksyon:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Vanauscheca Bouzy</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s2\">Konsepsyon Grafik:</span></p>\r\n<p class=\"p1\"><span class=\"s2\">Jo-Flung Bouzy & Abdullah Mode</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">#palmarèsmizikvideyo</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#evanjelik</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#mas2026 </span></p>\r\n<p class=\"p1\"><span class=\"s1\"><a href=\"https://www.konektem.net/blog/hashtags/konektem\">#konekte</a>m</span></p>\r\n<p class=\"p1\"><span class=\"s1\">#toutkotenenpotkilè</span></p>', 0, 1, 1, NULL, '2026-06-22', NULL, '2026-06-22 20:20:57'),
(61, 323, 'Gala Nightingale White Night, soirée d’hommage et de reconnaissance au personnel infirmier haïtien.', '2026-05-13 21:00:00', 'health', 'руфвдшту', '<p class=\"p1\"><span class=\"s1\">À l’occasion de la Journée internationale des infirmières, le projet Nightingale White Night, en collaboration avec Lophane Project et DGD, a organisé, ce mardi 12 mai 2026, une soirée de gala à l’Hotel Royal Oasis autour du thème : « Célébrons la grandeur de la profession infirmière dans le monde ». Cette initiative visait à mettre en valeur le travail des infirmières et infirmiers haïtiens, encourager le leadership féminin dans le domaine de la santé, promouvoir l’excellence ainsi que la formation professionnelle, tout en mobilisant des ressources pour contribuer au développement du secteur sanitaire en Haïti et dans la Caraïbe.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Cette édition du Gala Nightingale White Night a réuni plusieurs personnalités du secteur médical, des étudiants, des délégations venues de différents départements du pays ainsi que des représentants d’institutions sanitaires. La cérémonie s’est déroulée en présence du ministre de la Santé publique et de la Population, monsieur Sinal Bertrand, du directeur général du MSPP Gabriel Timothée, de membres de la Direction centrale des soins infirmiers ainsi que de plusieurs invités venus soutenir cette initiative dédiée à l’excellence, au leadership et à l’humanisme dans la profession infirmière.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">La soirée a débuté avec les propos d’ouverture et de mise en contexte prononcés par la présidente du Gala Nightingale White Night, Madame Tayna Vilsaint, saluée pour sa vision et son engagement dans la valorisation du personnel infirmier haïtien. Plusieurs prestations artistiques ont ensuite rythmé l’événement, notamment des performances de danse assurées par Adassah Dance et Dance Art Académie, des prestations de slam avec Slamosophe ainsi qu’un défilé présenté au cours de la cérémonie. Monsieur Laurent Lophane a également pris la parole afin de rappeler la portée du projet et sa volonté de faire de Nightingale White Night une référence nationale et caribéenne pour la reconnaissance du secteur de la santé. Des interventions ont aussi été réalisées par la PDG de DGD, Madame Djina Guillet Delatour, ainsi que par la directrice des soins infirmiers, Madame Carine Réveil Jean-Baptiste.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Parmi les moments marquants de cette soirée figurait l’hommage rendu à deux femmes au parcours inspirant : Miss Phalone Louis et CLERVILUS Geniflore. Ancienne jeune fille chargée de nettoyer un hôpital avant de devenir infirmière licenciée, Miss Phalone Louis a particulièrement ému l’assistance à travers son témoignage de courage et de persévérance. Les deux lauréates ont reçu des distinctions honorifiques en reconnaissance de leurs parcours et de leurs contributions au secteur infirmier.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Le gala a également bénéficié d’une ouverture internationale grâce à la présence de Rechanka Dorestil et Stevens Vilus, venus spécialement des États-Unis pour participer à cette célébration. Impressionnés par la vision portée par le comité organisateur, ils ont exprimé leur volonté de devenir ambassadeurs du Gala Nightingale White Night dans plusieurs États américains afin de promouvoir cette initiative et le savoir-faire infirmier haïtien à l’échelle internationale.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Le comité organisateur, composé notamment de Laurent Lophane, Tayna Vilsaint, Djina Guillet Delatour et Daniela Tingué, a aussi profité de cette édition pour saluer la forte mobilisation des délégations venues de l’Artibonite et du Sud. Selon les responsables, cette participation témoigne de l’intérêt grandissant suscité par cette initiative nationale et renforce l’ambition d’étendre progressivement le Gala Nightingale White Night dans les dix départements du pays.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">À travers cette soirée riche en émotions, en distinctions et en témoignages inspirants, le Gala Nightingale White Night a confirmé sa volonté de promouvoir le mérite, l’excellence, l’unité et le leadership du personnel infirmier haïtien, tout en contribuant au rayonnement d’Haïti sur la scène nationale et internationale.</span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">Rédaction ✍️: Ronalson Blanfort </span></p>\r\n<p class=\"p2\"> </p>\r\n<p class=\"p1\"><span class=\"s1\">#konektem </span></p>\r\n<p class=\"p1\"><span class=\"s1\">#ToutKoteNenpòtKilè</span></p>', 0, 0, 0, NULL, '2026-06-22', NULL, '2026-06-22 20:40:52');

-- --------------------------------------------------------

--
-- Структура таблицы `news_categories`
--

CREATE TABLE `news_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `display_text` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` enum('pending','confirmed','cancelled','completed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_date` timestamp NULL DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin@konektem.net'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `total_amount`, `order_status`, `payment_status`, `order_date`, `payment_date`, `transaction_id`, `item_name`, `item_id`, `owner`) VALUES
(1, 4, 0.00, 'pending', 'pending', '2025-12-07 23:34:43', NULL, 'ORDER_6935e4e3bc6ac', 'service', 4, 'admin@konektem.net'),
(2, 4, 2.00, 'pending', 'paid', '2025-12-07 23:52:26', '2025-12-07 21:07:08', 'ORDER_6935e90a4219c', 'event', 7, 'admin@konektem.net'),
(3, 4, 2.00, 'pending', 'pending', '2025-12-08 00:04:21', NULL, 'ORDER_6935ebd56854e', 'event', 7, 'admin@konektem.net'),
(4, 5, 2.00, 'pending', 'pending', '2025-12-10 18:58:15', NULL, 'ORDER_6939989776ccf', 'event', 8, 'admin@konektem.net'),
(5, 5, 2.00, 'pending', 'pending', '2025-12-10 20:06:16', NULL, 'ORDER_6939a88831b8f', 'event', 8, 'admin@konektem.net'),
(6, 5, 2.00, 'pending', 'paid', '2025-12-10 20:09:52', '2025-12-10 18:13:30', 'ORDER_6939a96068bc6', 'event', 8, 'admin@konektem.net'),
(7, 6, 2.00, 'pending', 'paid', '2025-12-10 21:15:51', '2025-12-10 18:16:17', 'ORDER_6939b8d70c4c4', 'event', 4, 'admin@konektem.net'),
(8, 6, 0.00, 'pending', 'paid', '2025-12-10 21:19:14', '2025-12-10 18:19:37', 'ORDER_6939b9a21d8a8', 'service', 6, 'admin@konektem.net'),
(9, 5, 4.00, 'pending', 'paid', '2025-12-10 21:24:28', '2025-12-10 18:24:51', 'ORDER_6939badc89cd2', 'event', 7, 'admin@konektem.net'),
(10, 7, 2.00, 'pending', 'paid', '2026-01-31 20:41:08', '2026-01-31 17:41:30', 'ORDER_697e3eb47ad0c', 'event', 11, 'admin@konektem.net'),
(11, 8, 4.00, 'pending', 'paid', '2026-03-05 20:08:37', '2026-03-07 10:12:08', 'ORDER_69a9b895874ab', 'event', 7, 'admin@konektem.net'),
(12, 9, 2.00, 'pending', 'paid', '2026-03-05 20:13:28', '2026-03-05 17:13:49', 'ORDER_69a9b9b86c5eb', 'event', 4, 'admin@konektem.net'),
(13, 9, 2.00, 'pending', 'paid', '2026-03-05 20:16:33', '2026-03-05 17:16:54', 'ORDER_69a9ba7175fd3', 'event', 11, 'admin@konektem.net'),
(14, 10, 2.00, 'pending', 'paid', '2026-03-06 16:23:01', '2026-03-06 13:27:10', 'ORDER_69aad535914b7', 'event', 11, 'admin@konektem.net'),
(15, 8, 2.00, 'pending', 'paid', '2026-03-08 06:16:01', '2026-03-08 03:21:09', 'ORDER_69ace9f119cc3', 'event', 10, 'admin@konektem.net'),
(16, 8, 2.00, 'pending', 'pending', '2026-03-08 06:29:41', NULL, 'ORDER_69aced25672c3', 'event', 11, 'admin@konektem.net');

-- --------------------------------------------------------

--
-- Структура таблицы `partners`
--

CREATE TABLE `partners` (
  `id` bigint NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_bin DEFAULT 'konktem_partner',
  `image_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
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
-- Структура таблицы `services`
--

CREATE TABLE `services` (
  `id` bigint NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `serviceImg` bigint UNSIGNED DEFAULT NULL,
  `price` int NOT NULL DEFAULT '0',
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin@konektem.net',
  `cancellations` int DEFAULT '0',
  `orders` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `serviceImg`, `price`, `owner`, `cancellations`, `orders`) VALUES
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
  `id` int NOT NULL,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` mediumtext COLLATE utf8mb4_unicode_ci,
  `setting_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'text',
  `setting_group` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT '1',
  `display_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
(9, 'subscription_invitation', 'Abone ak Bilten nouvel', 'text', 'footer', '', 1, 0, '2025-12-13 19:25:29', '2025-12-13 19:25:29'),
(10, 'main_video', 'https://www.youtube.com/embed/qUfA_j2weEI?si=FRr-x22LFESfNGN0', 'video', 'general', NULL, 1, 0, '2026-05-24 05:56:45', '2026-05-24 05:56:45');

-- --------------------------------------------------------

--
-- Структура таблицы `streamAccesskeys`
--

CREATE TABLE `streamAccesskeys` (
  `id` bigint UNSIGNED NOT NULL,
  `access_key` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `date_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_end` timestamp NOT NULL DEFAULT ((now() + interval 1 month))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `streamAccesskeys`
--

INSERT INTO `streamAccesskeys` (`id`, `access_key`, `date_start`, `date_end`) VALUES
(1, 'konektem_lovestream', '2025-10-18 13:31:56', '2026-07-14 10:36:37'),
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
-- Структура таблицы `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expire_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `period` enum('monthly','yearly') DEFAULT 'monthly',
  `description` text,
  `features` text COMMENT 'json string',
  `price` int NOT NULL DEFAULT '10',
  `currency` varchar(10) DEFAULT 'Dollars'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `last_name` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `google_id` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `avatar_url` text COLLATE utf8mb4_bin,
  `role` enum('admin','guest') COLLATE utf8mb4_bin DEFAULT 'guest',
  `is_blocked` tinyint(1) DEFAULT '0',
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `last_name`, `email`, `password_hash`, `created_at`, `google_id`, `avatar_url`, `role`, `is_blocked`, `last_login`) VALUES
(1, '', NULL, '', '$2y$10$3qtJen4bBP4i9KAlMsEEs.HNDNquND3cceB2Smh6kNxJposze0Ony', '2025-10-15 18:35:03', NULL, NULL, 'guest', 0, NULL),
(2, 'admin@konektem.net', NULL, 'admin@konektem.net', '$2y$10$MptjEeU3yqGXyGvBOMzjree2.rbBdf9gTaIAnPyenyra7v9SvFLxm', '2025-10-18 10:31:56', NULL, '', 'admin', 0, NULL),
(16, 'zwelakhemaseko02_konektem', NULL, 'zwelakhemaseko02@gmail.com', '$2y$10$8op1duK41TWI3be.tGXuJuTRqCeo95yH5ru5jTXKso3x0TPwope8.', '2025-10-25 21:59:17', '116742316388041503225', '/admin/uploads/images/695642039c096.jpg', 'guest', 0, NULL),
(31, 'JoFlungBouzy', NULL, 'joflungbouzy@gmail.com', NULL, '2025-11-07 15:32:47', '116883229385212460809', '/admin/uploads/images/695dd81861bd0.jpeg', 'guest', 0, NULL),
(36, 'ShellerValmiro', NULL, 'shellervalmiro2@gmail.com', NULL, '2025-11-15 18:43:50', '113617597052192062007', NULL, 'guest', 0, NULL),
(37, 'adminkonektemnet', NULL, 'zweklakhe.mzwet@gmail.com', '$2y$10$Z/Ascy707U2mX2qsagl2vubd4OoWyJgiwmPGQ4TRsXGZ6Skaha8Jy', '2025-11-17 18:16:21', '116636382122397190488', NULL, 'guest', 0, NULL),
(38, 'adminkonektem', NULL, 'zweklakhe.zwet@gmail.com', '$2y$10$lOKLZZvh7Y8jBWo2CTg9pejyweqaeRou2ABK/GZIpqrflj9txGd8q', '2025-11-17 18:17:11', NULL, NULL, 'guest', 0, NULL),
(39, 'wewe234rtqwe4', NULL, 'konektemtv@gmail.com', '$2y$10$mpa8tDtNAAiO6jc3pUy2JuGiAWGPgMOcGwsd41fCn0OXtWFsbMDJ6', '2025-11-23 09:48:08', '114354100468045266595', NULL, 'guest', 0, NULL),
(40, 'iyutlyyl86rl6', NULL, 'tiet7kk685k6', '$2y$10$fEJjDQJnYVZGIk7PMwRtNOKvM9PiBvNWQs4Cc.RPpX1aJ4mh19ga6', '2025-11-23 09:54:29', NULL, NULL, 'guest', 0, NULL),
(41, 'Jorbyart', NULL, 'jeanjoberne@gmail.com', '$2y$10$daER7cZW7XbZu3mZ4cQG8O1/PUF//m/BJCNw.xHLfEVc2SXFd1sGO', '2025-11-23 11:45:06', '110523920880736279190', '/admin/uploads/images/695521ba6a0f2.jpeg', 'guest', 0, NULL),
(42, 'AbdullahMode', NULL, 'modeabdullah451@gmail.com', NULL, '2025-11-24 09:53:50', '117623405616246842645', NULL, 'guest', 0, NULL),
(48, 'zwelakheMaseko', NULL, 'zwelakhe.mzwet@gmail.com', NULL, '2025-12-20 15:49:50', '114183917938627615118', NULL, 'guest', 0, '2026-06-24 06:10:59'),
(49, 'masekozw', NULL, 'zweeklakhe.mzwet@gmail.com', '$2y$10$Y2oWnd1m.IRcyzBtEDHSiOZIqu4DCYRrVnTtATBWs0pPOAO0Bjhku', '2026-01-01 19:30:29', NULL, '/admin/uploads/images/6957e3f66d235.jpg', 'guest', 0, NULL),
(50, 'Samantha', NULL, 'samylus99@gmail.com', NULL, '2026-02-04 15:26:54', '114032295297367937911', NULL, 'guest', 0, NULL),
(51, 'EmmanuelPIERRE', NULL, 'emmanuelpierre457@gmail.com', NULL, '2026-02-05 00:02:15', '108345803225240889895', NULL, 'guest', 0, NULL),
(58, 'DannyCrood', NULL, 'dannycrood500@gmail.com', NULL, '2026-02-08 13:42:16', '102914263626477579370', NULL, 'guest', 0, NULL),
(59, 'Ninas', NULL, 'nina.hairstyling.biz@gmail.com', NULL, '2026-02-15 23:52:18', '116566416097621233324', NULL, 'guest', 0, NULL),
(60, 'MINISTREVALEURDELHOMMETV', NULL, 'saintilienrobenson@gmail.com', NULL, '2026-02-17 11:11:12', '107022167591730889902', NULL, 'guest', 0, NULL),
(61, 'SadwineloizStsurin', NULL, 'stsurinsadwineloiz8@gmail.com', NULL, '2026-02-22 18:00:46', '105786544755482724405', '/admin/uploads/images/699b6f0230f6a.jpeg', 'guest', 0, NULL),
(62, 'DjouneJeanlouis', NULL, 'djounejeanlouis98@gmail.com', NULL, '2026-02-23 17:33:52', '118146905329931816573', NULL, 'guest', 0, NULL),
(63, 'Jorby', NULL, 'jjorby00@gmail.com', '$2y$10$7wKOHXq3sKTUDULlujLBTOsXU/yKPYKM6AqJ0ked7.LP.9Z8RPrLO', '2026-02-27 12:47:40', '113161503760011568798', NULL, 'guest', 0, NULL),
(64, 'PunisherJustice', NULL, 'punisherjustice365@gmail.com', NULL, '2026-03-10 22:09:31', '105923298706225502345', NULL, 'guest', 0, NULL),
(65, 'WahyuniDesi', NULL, 'wahyunjesi@gmail.com', NULL, '2026-03-22 13:06:42', '101492296243979735887', NULL, 'guest', 0, NULL),
(66, 'LucksonPetitfrre', NULL, 'lucksonpetitfrere0@gmail.com', NULL, '2026-03-29 05:23:25', '102616222610279153509', NULL, 'guest', 0, NULL),
(67, 'AndersonDestrat', NULL, 'destrataderson@gmail.com', NULL, '2026-04-02 08:08:04', '103201146793363208020', NULL, 'guest', 0, NULL),
(68, 'DanielGustin', NULL, 'gustindaniel17@gmail.com', NULL, '2026-04-02 19:09:20', '103907384976439397500', NULL, 'guest', 0, NULL),
(69, 'FrLucksonZnPaFMoun', NULL, 'lucksonjeanzonpafemoun@gmail.com', NULL, '2026-04-07 11:39:42', '111520476111987550387', NULL, 'guest', 0, NULL),
(70, 'Vava', NULL, 'p.vanauscheca@gmail.com', '$2y$10$J5wsRX.Jidq9Ts6Sn7tja.Eh6y2UdWNCJiE2kuRwEBJedX11oRzKK', '2026-04-07 12:06:52', NULL, NULL, 'guest', 0, NULL),
(71, 'LowensLeger', NULL, 'lbk428polivayan@gmail.com', NULL, '2026-04-09 03:57:25', '108854654484041604587', NULL, 'guest', 0, NULL),
(72, 'JrichoPierrenol', NULL, 'pierrenoeljericho@gmail.com', NULL, '2026-04-11 15:47:59', '114898504294289027664', NULL, 'guest', 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `videos`
--

CREATE TABLE `videos` (
  `id` bigint UNSIGNED NOT NULL,
  `image_id` bigint UNSIGNED DEFAULT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `likes` int DEFAULT '0',
  `downloads` int DEFAULT '0',
  `plays` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `videos`
--

INSERT INTO `videos` (`id`, `image_id`, `title`, `url`, `mime_type`, `likes`, `downloads`, `plays`) VALUES
(1, 6, 'Eminem - Inner War (feat. Joyner Lucas) [Music Video 2025]', '/media/../media/videos/vid_68a366f43c0048.92364707_po3.mp4', 'video/mp4', 0, 0, 0),
(2, 7, 'Tyga x Lil Wayne - Pop It Off [Official Video]', '/media/../media/videos/vid_68a3672715ad26.69245638_po2.mp4', 'video/mp4', 0, 0, 0),
(12, 201, 'Test', '/admin/controllers/../uploads/videos/695dcaf630ee9.mp4', 'video/mp4', 0, 0, 0),
(13, 163, 'Melomania Group : \"A Night of Worship with Sinach\"', '/admin/controllers/../uploads/videos/6962b97c4643d.mp4', 'video/mp4', 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `viewers`
--

CREATE TABLE `viewers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `access_key_id` bigint UNSIGNED NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `viewers`
--

INSERT INTO `viewers` (`id`, `name`, `access_key_id`, `password_hash`) VALUES
(1, 'konektem_admin', 1, '$2y$10$/L/p9Uk8O.PJqTrwC50dV.bhpLNQSaQjg4TxE8w9BML3J2xMQUviC'),
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
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `interview`
--
ALTER TABLE `interview`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `livestream`
--
ALTER TABLE `livestream`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `livestream_viewers`
--
ALTER TABLE `livestream_viewers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `stream_id` (`stream_id`);

--
-- Индексы таблицы `mainpagecontent`
--
ALTER TABLE `mainpagecontent`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news_categories`
--
ALTER TABLE `news_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `name` (`name`);

--
-- Индексы таблицы `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `streamAccesskeys`
--
ALTER TABLE `streamAccesskeys`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_sub_plan` (`plan_id`);

--
-- Индексы таблицы `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=369;

--
-- AUTO_INCREMENT для таблицы `interview`
--
ALTER TABLE `interview`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `livestream_viewers`
--
ALTER TABLE `livestream_viewers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `mainpagecontent`
--
ALTER TABLE `mainpagecontent`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT для таблицы `news_categories`
--
ALTER TABLE `news_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `viewers`
--
ALTER TABLE `viewers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `livestream_viewers`
--
ALTER TABLE `livestream_viewers`
  ADD CONSTRAINT `livestream_viewers_ibfk_1` FOREIGN KEY (`stream_id`) REFERENCES `livestream` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `viewers`
--
ALTER TABLE `viewers`
  ADD CONSTRAINT `viewers_ibfk_1` FOREIGN KEY (`access_key_id`) REFERENCES `streamAccesskeys` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
