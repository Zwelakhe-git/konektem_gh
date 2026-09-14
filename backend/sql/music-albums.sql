CREATE TABLE albums(
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image_url VARCHAR(255),
    songs_count INT,
    release_year DATE NOT NULL,
    owner VARCHAR(255),
    user_id BIGINT(20) UNSIGNED NULL,
    description TEXT,
    donwloads INT DEFAULT 0,
    likes INT DEFAULT 0,
    shares INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=INNODB;
ALTER TABLE music ADD album_id BIGINT(20) UNSIGNED NULL;
ALTER TABLE music ADD FOREIGN KEY (album_id) REFERENCES albums (id)
ON DELETE SET NULL ON UPDATE CASCADE;