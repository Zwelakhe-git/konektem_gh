CREATE TABLE `mainpagecontent` (
  `id` bigint NOT NULL,
  `newsSlide` bigint UNSIGNED DEFAULT NULL,
  `fadeNews` bigint UNSIGNED DEFAULT NULL,
  `music` bigint UNSIGNED DEFAULT NULL,
  `events` bigint DEFAULT NULL,
  `interviews` bigint DEFAULT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE mainpagecontent ADD `interviews` BIGINT NULL;