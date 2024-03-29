CREATE TABLE IF NOT EXISTS users
(
    id         INT PRIMARY KEY AUTO_INCREMENT,
    login      VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME     NULL ON UPDATE CURRENT_TIMESTAMP,
    last_login DATETIME     NULL,
    2fa_token  VARCHAR(255) NULL     DEFAULT NULL,
    INDEX (login),
    INDEX (email),
    INDEX (created_at),
    CONSTRAINT unique_login UNIQUE (login),
    CONSTRAINT unique_email UNIQUE (email)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_520_ci;

CREATE TABLE usermeta
(
    id         INT PRIMARY KEY AUTO_INCREMENT,
    user_id    INT          NOT NULL,
    meta_key   VARCHAR(255) NULL,
    meta_value LONGTEXT     NULL,
    INDEX (user_id),
    INDEX (meta_key),
    CONSTRAINT fk_usermeta_user_id
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_520_ci;