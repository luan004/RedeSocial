/* CREATE TABLE ---------------- */
CREATE TABLE users (
    id int PRIMARY KEY AUTO_INCREMENT,
    name varchar(64) NOT NULL,
    user varchar(32) NOT NULL,
    pass varchar(64) NOT NULL,
    avatar varchar(200),
    banner varchar(200),
    dt datetime
);

CREATE TABLE sesstokens (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int,
    token varchar(64),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE posts(
	id int PRIMARY KEY AUTO_INCREMENT,
    user_id int,
    text varchar(280),
    image varchar(190),
    likes int not null,
    dt DATETIME not null,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE followers (
    follower_id INT,
    user_id INT,
    FOREIGN KEY (follower_id) REFERENCES users(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    PRIMARY KEY (follower_id, user_id)
);


/* INSERT --------------------- */
INSERT INTO `posts`(
    `id`,
    `user_id`,
    `text`,
    `image`,
    `likes`,
    `dt`
) VALUES (
    null,
    4,
    'Esse é o primeiro post dessa rede social!',
    null,
    0,
    NOW()
);