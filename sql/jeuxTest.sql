-- 1. Insertion valide : réussit
INSERT INTO utilisateur (id, identifiant, mdp_hash) VALUES (1, 'test', 'x');
INSERT INTO liste (id, nom, utilisateur_id) VALUES (1, 'Courses', 1);
INSERT INTO tache (liste_id, titre) VALUES (1, 'Pain');

INSERT INTO utilisateur (identifiant, mdp_hash) VALUES
('alex', '$2y$10$ZpXx6DnMbSXwxQktHcv6zOm/9pKmJnEHH1f12LMmal2u7E4WpozAK');