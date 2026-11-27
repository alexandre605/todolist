-- Jeu de test : compte "alex" (mot de passe : test1234)
-- À importer après schema_todo.sql, base todo_app
-- Compatible MySQL 8 et MariaDB 10.4 (WampServer)
-- Les tâches sont rattachées à alex par leur liste (tache -> liste -> utilisateur)

USE todo_app;

INSERT INTO liste (nom, utilisateur_id) VALUES
  ('Courses',    (SELECT id FROM utilisateur WHERE identifiant = 'alex')),
  ('Projet SIO', (SELECT id FROM utilisateur WHERE identifiant = 'alex'));

INSERT INTO tache (liste_id, titre, description, priorite, statut, echeance) VALUES
  ((SELECT l.id FROM liste l JOIN utilisateur u ON u.id = l.utilisateur_id
     WHERE u.identifiant = 'alex' AND l.nom = 'Courses'),
   'Acheter du pain', NULL, 'haute', 'a_faire', '2026-11-28'),
  ((SELECT l.id FROM liste l JOIN utilisateur u ON u.id = l.utilisateur_id
     WHERE u.identifiant = 'alex' AND l.nom = 'Courses'),
   'Acheter du lait', NULL, 'moyenne', 'terminee', NULL),
  ((SELECT l.id FROM liste l JOIN utilisateur u ON u.id = l.utilisateur_id
     WHERE u.identifiant = 'alex' AND l.nom = 'Courses'),
   'Acheter des oeufs', 'Format plein air, 12 unités', 'basse', 'en_cours', NULL),
  ((SELECT l.id FROM liste l JOIN utilisateur u ON u.id = l.utilisateur_id
     WHERE u.identifiant = 'alex' AND l.nom = 'Projet SIO'),
   'Finir le MCD', 'Modèle conceptuel des données', 'haute', 'en_cours', '2026-12-05'),
  ((SELECT l.id FROM liste l JOIN utilisateur u ON u.id = l.utilisateur_id
     WHERE u.identifiant = 'alex' AND l.nom = 'Projet SIO'),
   'Tester la connexion', NULL, 'basse', 'a_faire', NULL);