<?php
// Requêtes SQL regroupées : table utilisateur
// Le PDO est passé en paramètre, la connexion est créée dans connexion.php

/**
 * Renvoie l'utilisateur correspondant à l'identifiant, ou null.
 */
function trouverUtilisateurParIdentifiant(PDO $pdo, string $identifiant): ?array
{
    $stmt = $pdo->prepare('SELECT id, identifiant, mdp_hash FROM utilisateur WHERE identifiant = ?');
    $stmt->execute([$identifiant]);
    $utilisateur = $stmt->fetch();

    return $utilisateur ?: null;
}

/**
 * Indique si l'identifiant est déjà pris (la comparaison ignore la casse).
 */
function identifiantExiste(PDO $pdo, string $identifiant): bool
{
    return trouverUtilisateurParIdentifiant($pdo, $identifiant) !== null;
}

/**
 * Crée un utilisateur et renvoie son id.
 * Lève une PDOException (SQLSTATE 23000) si l'identifiant existe déjà.
 */
function creerUtilisateur(PDO $pdo, string $identifiant, string $motDePasse): int
{
    $stmt = $pdo->prepare('INSERT INTO utilisateur (identifiant, mdp_hash) VALUES (?, ?)');
    $stmt->execute([$identifiant, password_hash($motDePasse, PASSWORD_DEFAULT)]);

    return (int) $pdo->lastInsertId();
}

/**
 * Renvoie ['id' => ..., 'identifiant' => ...] si les identifiants sont corrects, sinon null.
 * Le hash du mot de passe n'est jamais renvoyé.
 */
function authentifierUtilisateur(PDO $pdo, string $identifiant, string $motDePasse): ?array
{
    $utilisateur = trouverUtilisateurParIdentifiant($pdo, $identifiant);

    if ($utilisateur === null || !password_verify($motDePasse, $utilisateur['mdp_hash'])) {
        return null;
    }

    return [
        'id'          => (int) $utilisateur['id'],
        'identifiant' => $utilisateur['identifiant'],
    ];
}

/**
 * Renvoie les listes d'un utilisateur avec le nombre de tâches,
 * le nombre de tâches terminées et le pourcentage de complétion.
 */
function listesAvecStatistiques(PDO $pdo, int $utilisateurId): array
{
    $stmt = $pdo->prepare(
        'SELECT l.id,
                l.nom,
                COUNT(t.id) AS nb_taches,
                SUM(CASE WHEN t.statut = \'terminee\' THEN 1 ELSE 0 END) AS nb_terminees
           FROM liste l
           LEFT JOIN tache t ON t.liste_id = l.id
          WHERE l.utilisateur_id = ?
          GROUP BY l.id, l.nom, l.date_creation
          ORDER BY l.date_creation, l.id'
    );
    $stmt->execute([$utilisateurId]);

    $listes = [];
    foreach ($stmt->fetchAll() as $ligne) {
        $nbTaches    = (int) $ligne['nb_taches'];
        $nbTerminees = (int) $ligne['nb_terminees'];

        $listes[] = [
            'id'           => (int) $ligne['id'],
            'nom'          => $ligne['nom'],
            'nb_taches'    => $nbTaches,
            'nb_terminees' => $nbTerminees,
            'pourcentage'  => $nbTaches > 0 ? (int) round($nbTerminees * 100 / $nbTaches) : 0,
        ];
    }

    return $listes;
}

/**
 * Renvoie la liste (id, nom) si elle appartient à l'utilisateur, sinon null.
 */
function trouverListePourUtilisateur(PDO $pdo, int $listeId, int $utilisateurId): ?array
{
    $stmt = $pdo->prepare('SELECT id, nom FROM liste WHERE id = ? AND utilisateur_id = ?');
    $stmt->execute([$listeId, $utilisateurId]);
    $liste = $stmt->fetch();

    return $liste ?: null;
}

/**
 * Renvoie les tâches d'une liste, à faire d'abord, puis par échéance.
 * Ne vérifie pas le propriétaire : à appeler après trouverListePourUtilisateur().
 */
function tachesDeListe(PDO $pdo, int $listeId): array
{
    $stmt = $pdo->prepare(
        'SELECT id, titre, description, priorite, statut, echeance
           FROM tache
          WHERE liste_id = ?
          ORDER BY FIELD(statut, \'a_faire\', \'en_cours\', \'terminee\'),
                   echeance IS NULL, echeance, id'
    );
    $stmt->execute([$listeId]);

    return $stmt->fetchAll();
}