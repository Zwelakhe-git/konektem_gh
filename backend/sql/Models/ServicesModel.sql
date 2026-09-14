CREATE TABLE `services` (
  `id` bigint NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `serviceImg` bigint UNSIGNED DEFAULT NULL,
  `price` int NOT NULL DEFAULT '0',
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin@konektem.net',
  `cancellations` int DEFAULT '0',
  `orders` int DEFAULT '0',
  `product_id` int DEFAULT '0',
  `estimated_days` int DEFAULT NULL,
  `requirements` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;