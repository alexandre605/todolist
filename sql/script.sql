-- Base de gestion de listes de tâches
-- Cible : WampServer, MySQL 8.0.16+ ou MariaDB 10.2.1+
-- (les versions antérieures de MySQL ignorent les contraintes CHECK)

CREATE DATABASE IF NOT EXISTS todo_app
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE todo_app;

CREATE TABLE utilisateur (
  id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  identifiant      VARCHAR(50)  NOT NULL,
  mdp_hash         VARCHAR(255) NOT NULL,
  date_inscription DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT uq_utilisateur_identifiant UNIQUE (identifiant)
) ENGINE=InnoDB;

CREATE TABLE liste (
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nom            VARCHAR(100) NOT NULL,
  utilisateur_id INT UNSIGNED NOT NULL,
  date_creation  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_liste_utilisateur
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
) ENGINE=InnoDB;

CREATE TABLE tache (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  liste_id      INT UNSIGNED NOT NULL,
  titre         VARCHAR(150) NOT NULL,
  description   TEXT         NULL,
  priorite      VARCHAR(10)  CHARACTER SET ascii COLLATE ascii_bin
                NOT NULL DEFAULT 'moyenne',
  statut        VARCHAR(10)  CHARACTER SET ascii COLLATE ascii_bin
                NOT NULL DEFAULT 'a_faire',
  echeance      DATE         NULL,
  date_creation DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_tache_liste
    FOREIGN KEY (liste_id) REFERENCES liste (id)
    ON DELETE CASCADE,
  CONSTRAINT chk_tache_priorite
    CHECK (priorite IN ('basse', 'moyenne', 'haute')),
  CONSTRAINT chk_tache_statut
    CHECK (statut IN ('a_faire', 'en_cours', 'terminee'))
) ENGINE=InnoDB;