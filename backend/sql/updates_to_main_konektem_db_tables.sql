-- Первый ALTER (добавление колонок)
ALTER TABLE news 
ADD image_id BIGINT(10) UNSIGNED,
ADD title TEXT,
ADD category VARCHAR(50),
ADD headline TEXT,
ADD content TEXT,
ADD published_at TIMESTAMP,
ADD created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Добавление хеша (после создания колонки title)
ALTER TABLE news ADD title_hash CHAR(32) 
GENERATED ALWAYS AS (MD5(title)) STORED;

-- Установка DEFAULT (после переименования в created_at)
-- ALTER TABLE news MODIFY created_at DATE DEFAULT (CURRENT_DATE);
-- Или для старых версий MySQL:
-- ALTER TABLE news MODIFY created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;


CREATE TABLE IF NOT EXISTS subscription_plans(
    id SERIAL PRIMARY KEY,
    period ENUM('monthly', 'yearly') DEFAULT 'monthly',
    description TEXT,
    features TEXT COMMENT "json string",
    price INT NOT NULL DEFAULT 10,
    currency VARCHAR(10) DEFAULT 'Dollars'
)ENGINE=INNODB;

CREATE TABLE subscriptions(
	id SERIAL PRIMARY KEY,
    user_id BIGINT(10) UNSIGNED NOT NULL,
    plan_id BIGINT(10) UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expire_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES subscription_plans (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_sub_plan (plan_id)
)ENGINE=INNODB;

ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL FIRST PRIMARY KEY;

ALTER TABLE interview ADD guest_name VARCHAR(50),
ADD guest_title VARCHAR(20),
ADD duration INT,
ADD interview_date DATE;


ALTER TABLE music ADD image_id BIGINT UNSIGNED,
ADD title VARCHAR(255),
ADD url VARCHAR(255);

ALTER TABLE events ADD image_id BIGINT UNSIGNED,
ADD event_date DATE;

UPDATE news SET image_id = newsImage, title = newsTitle, category = newsCategory, headline = newsHealine, content = fullContent;
UPDATE interview SET guest_name = personName, guest_title = personTitle, duration = interviewLength, interview_date = interviewDate;
UPDATE music SET image_id = track_img_id, title = track_name, url = location;
UPDATE events SET image_id = eventImage, event_date = eventDate;

CREATE TABLE IF NOT EXISTS news_categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50),
    display_text VARCHAR(50),
    INDEX (name)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS livestream_viewers (
    id SERIAL PRIMARY KEY,
    user_ids TEXT,
    stream_id BIGINT UNSIGNED,
    FOREIGN KEY (stream_id) REFERENCES livestream (id) ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=INNODB;

-- 2026/15/06
CREATE TABLE IF NOT EXISTS payments(
    id SERIAL PRIMARY KEY,
    order_id VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    paid_at TIMESTAMP,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(20)
)ENGINE=INNODB;

ALTER TABLE interview ADD title_hash CHAR(32) DEFAULT MD5(title);
UPDATE interview SET title_hash = MD5(title);

-- creating admin account
-- real password: adminkonektem
INSERT INTO users (name, role, email, password_hash) VALUES ('admin@konektem.net', 'admin', 'admin@konektem.net', '$2y$10$MptjEeU3yqGXyGvBOMzjree2.rbBdf9gTaIAnPyenyra7v9SvFLxm');


-- пробуем создать новые таблицы, которые являются копиями существующих, без удаления существующих.
--- короче, делаем миграции
-- будем выбирать только колонки, которые мы добавили в news.

INSERT INTO new_news () SELECT ... FROM news;

RENAME TABLE news TO old_news, new_news TO news;
DROP TABLE old_news;

-- Мы это можем делать для всех таблиц