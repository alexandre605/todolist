<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$identifiantConnecte = $_SESSION['identifiant'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titre ?? 'ToDo List') ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <strong>ToDo List</strong>
    <?php if ($identifiantConnecte !== null): ?>
        <span>Connecté : <?= htmlspecialchars($identifiantConnecte) ?></span>
    <?php endif ?>
    <nav>
        <a class="btn" href="index.php">Accueil</a>
    <?php if ($identifiantConnecte !== null): ?>
        <a class="btn" href="deconnexion.php">Déconnexion</a>
        <?php else: ?>
        <a class="btn" href="login.php">Connexion</a>
        <a class="btn" href="inscription.php">Inscription</a>
    <?php endif ?>
    </nav>
</header>
<main>
