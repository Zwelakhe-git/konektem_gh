-- already added
CREATE TABLE `artists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO artists SELECT * FROM Artists;
ALTER TABLE artists MODIFY COLUMN id BIGINT AUTO_INCREMENT PRIMARY KEY;


-- 
CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `owner` varchar(255) NOT NULL DEFAULT 'admin@konektem.net',
  `likes` int(10) DEFAULT 0,
  `shares` int(10) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO events SELECT * FROM events;

CREATE TABLE `images` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `url` varchar(200) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO images SELECT * FROM Images;

-- the interview table has already been updated.
-- CREATE TABLE `interview` (
--   `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
--   `title` varchar(255) NOT NULL,
--   `description` mediumtext DEFAULT NULL,
--   `created_at` timestamp NULL DEFAULT current_timestamp(),
--   `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
--   `image_id` bigint(20) DEFAULT NULL,
--   `views` int(11) DEFAULT 0,
--   `shares` int(11) DEFAULT 0,
--   `likes` int(11) DEFAULT 0,
--   `guest_name` varchar(100) DEFAULT NULL,
--   `guest_title` varchar(100) DEFAULT NULL,
--   `duration` varchar(50) DEFAULT NULL,
--   `interview_date` date DEFAULT NULL,
--   `video_id` bigint(20) UNSIGNED DEFAULT NULL,
--   `title_hash` CHAR(32) GENERATED ALWAYS AS (MD5(title)) STORED
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- INSERT INTO `interview` SELECT * FROM `interview`;

-- added
CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL PRIMARY KEY,
  `name` varchar(50) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `serviceImg` bigint(20) UNSIGNED DEFAULT NULL,
  `price` int(10) NOT NULL DEFAULT 0,
  `owner` varchar(255) NOT NULL DEFAULT 'admin@konektem.net',
  `cancellations` int(11) DEFAULT 0,
  `orders` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE services ADD image_id bigint UNSIGNED;
ALTER TABLE services ADD FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE RESTRICT ON UPDATE CASCADE;
--ALTER TABLE services ADD FOREIGN KEY (serviceImg) REFERENCES images (id) ON DELETE RESTRICT ON UPDATE CASCADE;


CREATE TABLE `videos` (
  `id` bigint(20) UNSIGNED NOT NULL PRIMARY KEY,
  `image_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` mediumtext DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `downloads` int(11) DEFAULT 0,
  `plays` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- keys
ALTER TABLE `services` ADD FOREIGN KEY (serviceImg) REFERENCES images (id) ON DELETE RESTRICT ON UPDATE CASCADE;
