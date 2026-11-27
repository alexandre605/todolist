<?php
session_start();
require_once __DIR__ . '/include/connexion.php';
require_once __DIR__ . '/include/fonctions_sql.php';

// Page réservée aux utilisateurs connectés
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php');
    exit;
}

$libellesStatut = [
    'a_faire'  => 'À faire',
    'en_cours' => 'En cours',
    'terminee' => 'Terminée',
];

$libellesPriorite = [
    'basse'   => 'Basse',
    'moyenne' => 'Moyenne',
    'haute'   => 'Haute',
];

$erreur = null;
$liste  = null;
$taches = [];

// Identifiant de liste absent ou invalide : retour à l'accueil
$listeId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$listeId) {
    header('Location: index.php');
    exit;
}

try {
    $liste = trouverListePourUtilisateur($pdo, $listeId, (int) $_SESSION['utilisateur_id']);

    if ($liste === null) {
        http_response_code(404);
        $erreur = 'Liste introuvable.';
    } else {
        $taches = tachesDeListe($pdo, $listeId);
    }
} catch (PDOException $e) {
    error_log('Chargement de la liste : ' . $e->getMessage());
    $erreur = 'Impossible de charger cette liste.';
}

$titre = ($liste !== null ? $liste['nom'] : 'Liste') . ' – ToDo List';
require __DIR__ . '/include/entete.php';
?>

<?php if ($erreur !== null): ?>
    <h1>Liste</h1>
    <p style="color: red;" role="alert"><?= htmlspecialchars($erreur) ?></p>
<?php else: ?>
    <h1><?= htmlspecialchars($liste['nom']) ?></h1>
    <p><a href="index.php">← Retour aux listes</a></p>

    <?php if ($taches === []): ?>
        <p>Cette liste ne contient encore aucune tâche.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Tâche</th>
                    <th>Description</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                    <th>Échéance</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($taches as $tache): ?>
                <tr>
                    <td><?= htmlspecialchars($tache['titre']) ?></td>
                    <td><?= $tache['description'] !== null ? nl2br(htmlspecialchars($tache['description'])) : '—' ?></td>
                    <td><?= htmlspecialchars($libellesPriorite[$tache['priorite']] ?? $tache['priorite']) ?></td>
                    <td><?= htmlspecialchars($libellesStatut[$tache['statut']] ?? $tache['statut']) ?></td>
                    <td><?= $tache['echeance'] !== null ? date('d/m/Y', strtotime($tache['echeance'])) : '—' ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
<?php endif ?>

<?php require __DIR__ . '/include/pied.php'; ?>