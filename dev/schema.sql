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

CREATE TABLE roles
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_520_ci;

CREATE TABLE user_roles
(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    CONSTRAINT fk_user_roles_user_id FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_user_roles_role_id FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_520_ci;

CREATE TABLE capabilities
(
    id   INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_520_ci;

CREATE TABLE role_capabilities
(
    id            INT PRIMARY KEY AUTO_INCREMENT,
    role_id       INT,
    capability_id INT,
    FOREIGN KEY (role_id) REFERENCES roles (id),
    FOREIGN KEY (capability_id) REFERENCES capabilities (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_520_ci;
