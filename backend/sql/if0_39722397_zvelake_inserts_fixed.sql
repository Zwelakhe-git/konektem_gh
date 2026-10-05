-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: sql101.infinityfree.com
-- Время создания: Окт 05 2026 г., 15:20
-- Версия сервера: 11.4.13-MariaDB
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

--
-- Дамп данных таблицы `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `data`, `created_at`) VALUES
(1, 1, 'grant_stream_access', '{\"user_id\":1,\"product_id\":1,\"order_id\":\"1\",\"access_id\":\"1\",\"days\":30,\"expires_at\":\"2026-08-07 03:05:17\"}', '2026-07-08 07:05:17');

--
-- Дамп данных таблицы `albums`
--

INSERT INTO `albums` (`id`, `name`, `image_url`, `songs_count`, `release_year`, `owner`, `user_id`, `description`, `downloads`, `likes`, `shares`) VALUES
(14, 'Vwa Yo Pa Tande Yo', '/konektem/config/../uploads/masekozw_album_Vwayopatandeyo/69d507e45879c.jpg', 4, '2026-04-07', 'masekozw', 49, '« Vwa Yo Pa Tande Yo » constitue la troisième production musicale de la Fondation Frère\r\nLuckson Zòn Pa Fò Moun.\r\nL\'album comprend douze titres originaux, interprétés par des enfants et adolescents issus de milieux vulnérables :\r\nJob, Diaman, Delson, Jonaider, Ketty, Fritzline, Lovemika, Ti Pasté, Ferlando, Ti Batè, BenBen et\r\nBòs Tayè.\r\n\r\nLeurs voix expriment la résilience, la foi et l\'espérance d\'une génération qui refuse de se résigner.', 57, 1, 6),
(17, 'Ombre de la lumiere', '/konektem/uploads/Vava_album_Ombredelalumiere/6a776058e1f00.png', 2, '2026-08-08', 'Vava', 70, 'Mizik ki ka transfòme', 2, 0, 3),
(18, 'Samouha', '/konektem/uploads/JoFlungBouzy_album_Samouha/6a91871448194.jpeg', 3, '2026-08-28', 'JoFlungBouzy', 31, 'Enjoy', 0, 0, 2);

--
