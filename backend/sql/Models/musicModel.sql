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
  `image_id` bigint UNSIGNED DEFAULT NULL,
  `public` boolean DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE music ADD `public` boolean DEFAULT 1;