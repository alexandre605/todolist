<?php
session_start();
require_once __DIR__ . '/include/connexion.php';
require_once __DIR__ . '/include/fonctions_sql.php';

// Page réservée aux utilisateurs connectés
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php');
    exit;
}

$erreur = null;
$listes = [];

try {
    $listes = listesAvecStatistiques($pdo, (int) $_SESSION['utilisateur_id']);
} catch (PDOException $e) {
    error_log('Chargement des listes : ' . $e->getMessage());
    $erreur = 'Impossible de charger vos listes.';
}

$titre = 'Accueil – ToDo List';
require __DIR__ . '/include/entete.php';
?>

<h1>Mes listes</h1>

<?php if ($erreur !== null): ?>
    <p style="color: red;" role="alert"><?= htmlspecialchars($erreur) ?></p>
<?php elseif ($listes === []): ?>
    <p>Vous n'avez encore aucune liste.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Liste</th>
                <th>Tâches</th>
                <th>Terminées</th>
                <th>Complétion</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($listes as $liste): ?>
            <tr>
                <td><a href="liste.php?id=<?= $liste['id'] ?>"><?= htmlspecialchars($liste['nom']) ?></a></td>
                <td><?= $liste['nb_taches'] ?></td>
                <td><?= $liste['nb_terminees'] ?></td>
                <td>
                    <progress value="<?= $liste['pourcentage'] ?>" max="100"></progress>
                    <?= $liste['pourcentage'] ?> %
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>

<?php require __DIR__ . '/include/pied.php'; ?>