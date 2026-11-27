<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titre ?? 'ToDo List') ?></title>
    <link rel="stylesheet" href="/../css/style.css">
</head>
<body>
<header>
    <strong>ToDo List</strong>
    <nav>
        <a class="btn" href="index.php">Accueil</a>
        <a class="btn" href="login.php">Connexion</a>
        <a class="btn" href="inscription.php">Inscription</a>
    </nav>
</header>
<main>