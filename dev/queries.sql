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
