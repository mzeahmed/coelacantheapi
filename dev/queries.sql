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
       (3, 'lastname', 'Smith');