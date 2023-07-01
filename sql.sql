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
    followed_id INT,
    FOREIGN KEY (follower_id) REFERENCES users(id),
    FOREIGN KEY (followed_id) REFERENCES users(id),
    PRIMARY KEY (follower_id, followed_id)
);

CREATE TABLE comments (
	id int PRIMARY KEY AUTO_INCREMENT,
    post_id int,
    user_id int,
    text varchar(100) NOT NULL,
    dt DATETIME NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE likes (
    user_id int,
    post_id int,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (post_id) REFERENCES posts(id),
    PRIMARY KEY (user_id, post_id)
);

/* INSERT --------------------- */
INSERT INTO `users`(
    `id`,
    `name`,
    `user`,
    `pass`,
    `avatar`,
    `banner`,
    `dt`
) VALUES (
    null,
    'Admin',
    'admin',
    'admin',
    'https://i.imgur.com/1qZ0QZB.png',
    'https://i.imgur.com/1qZ0QZB.png',
    NOW()
);

INSERT INTO `posts`(
    `id`,
    `user_id`,
    `text`,
    `image`,
    `likes`,
    `dt`
) VALUES (
    null,
    1,
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisl eget aliquam ultricies, nunc nunc ultricies nunc, vitae ultricies nisl nunc eget nunc. Sed euismod, nisl eget aliquam ultricies, nunc nunc ultricies nunc, vitae ultricies nisl nunc eget nunc.',
    'https://i.imgur.com/1qZ0QZB.png',
    0,
    NOW()
);

INSERT INTO `comments`(
    `id`,
    `post_id`,
    `user_id`,
    `text`,
    `dt`
) VALUES (
    null,
    1,
    1,
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisl eget aliquam ultricies, nunc nunc ultricies nunc, vitae ultricies nisl nunc eget nunc. Sed euismod, nisl eget aliquam ultricies, nunc nunc ultricies nunc, vitae ultricies nisl nunc eget nunc.',
    NOW()
);