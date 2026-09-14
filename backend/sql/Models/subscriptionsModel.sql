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

ALTER TABLE subscription_plans ADD product_id BIGINT NOT NULL;
ALTER TABLE subscription_plans ADD FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE CASCADE ON DELETE RESTRICT;

-- CREATING A RANDOM PLAN FOR TESTING
START TRANSACTION;
INSERT INTO products (product_type, name, description, price) VALUES ('premium_subscription','konektem_premium','subscription', 9.9);
SELECT LAST_INSERT_ID() INTO @id;

INSERT INTO subscription_plans (product_id, description, features, price)
VALUES (@id,'premium subscription',"{'posting': 'make your content appear on the main page'}", 9.9);
SELECT LAST_INSERT_ID() AS 'SP_ID';
COMMIT;