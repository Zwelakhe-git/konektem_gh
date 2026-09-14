
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
