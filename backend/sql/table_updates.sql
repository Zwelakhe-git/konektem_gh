-- Первый ALTER (переименование колонок)
ALTER TABLE news 
CHANGE COLUMN newsImg image_id BIGINT(10) UNSIGNED,
CHANGE COLUMN newsTitle title TEXT,
CHANGE COLUMN newsDate created_at DATE,
CHANGE COLUMN newsCategory category VARCHAR(50),
CHANGE COLUMN newsHeadline headline TEXT,
CHANGE COLUMN fullContent content TEXT,
ADD COLUMN published_at DATE;

-- Добавление хеша (после создания колонки title)
ALTER TABLE news ADD title_hash CHAR(32) 
GENERATED ALWAYS AS (MD5(title)) STORED;

-- Установка DEFAULT (после переименования в created_at)
-- ALTER TABLE news MODIFY created_at DATE DEFAULT (CURRENT_DATE);
-- Или для старых версий MySQL:
ALTER TABLE news MODIFY created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

RENAME TABLE Images TO images, Services TO services, Artists TO artists;

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

ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL FIRST;

ALTER TABLE interviews CHANGE personName guest_name VARCHAR(50),
CHANGE personTitle guest_title VARCHAR(20),
CHANGE interviewLength duration INT,
CHANGE interviewDate interview_date DATE;

ALTER TABLE music CHANGE track_img_id image_id BIGINT UNSIGNED,
CHANGE track_name title VARCHAR(255),
CHANGE location url VARCHAR(255);

ALTER TABLE events CHANGE eventImage image_id BIGINT UNSIGNED,
CHANGE eventData event_date DATE;

CREATE TABLE news_categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50),
    display_text VARCHAR(50),
    INDEX (name)
)ENGINE=INNODB;