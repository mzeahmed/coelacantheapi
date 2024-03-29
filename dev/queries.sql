-- The password is 'password' hashed with bcrypt
INSERT INTO users (login, email, password, `2fa_token`)
VALUES ('admin', 'admin@localhost', '$2y$10$PZ2CbbXACZ1K4lUQ1L7kyu5RfxyNieITECUCYeotomhLKVOeOZfne', '123456'),
       ('janedoe', 'janedoe@localhost', '$2y$10$PZ2CbbXACZ1K4lUQ1L7kyu5RfxyNieITECUCYeotomhLKVOeOZfne', '123456'),
       ('johnsmith', 'johnsmith@localhost', '$2y$10$PZ2CbbXACZ1K4lUQ1L7kyu5RfxyNieITECUCYeotomhLKVOeOZfne', '123456');

INSERT INTO usermeta (user_id, meta_key, meta_value)
VALUES (1, 'firstname', 'John'),
       (1, 'lastname', 'Doe'),
       (2, 'firstname', 'Jane'),
       (2, 'lastname', 'Doe'),
       (3, 'firstname', 'John'),
       (3, 'lastname', 'Smith'),
       (1, 'birthdate', '1985-01-25 00:00:00'),
       (2, 'birthdate', '1990-01-01 00:00:00');

INSERT INTO roles
VALUES (1, 'administator'),
       (2, 'moderator'),
       (3, 'user');

INSERT INTO capabilities (id, name)
VALUES (1, 'read'),
       (2, 'edit_object'),
       (3, 'create_object'),
       (4, 'delete_object'),
       (5, 'edit_user'),
       (6, 'delete_user'),
       (7, 'edit_post'),
       (8, 'delete_post');

INSERT INTO role_capabilities (role_id, capability_id)
VALUES (1, 1),
       (1, 2),
       (1, 3),
       (1, 4),
       (1, 5),
       (1, 6),
       (1, 7),
       (1, 8),
       (2, 5),
       (2, 7),
       (2, 8),
       (3, 1);
