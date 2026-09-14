CREATE TABLE IF NOT EXISTS `stream_details` (
  `product_id` bigint UNSIGNED NOT NULL,
  `stream_url` varchar(500) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `max_viewers` int DEFAULT NULL,
  `stream_key` VARCHAR(80),
  `cover_image` TEXT,
  PRIMARY KEY (`product_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
  UNIQUE KEY (`stream_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;