-- The password is 'password' hashed with bcrypt
INSERT INTO users (login, email, password, `2fa_token`)
VALUES ('admin', 'admin@coelacanteapi.local', '$2y$10$PZ2CbbXACZ1K4lUQ1L7kyu5RfxyNieITECUCYeotomhLKVOeOZfne', 'token');