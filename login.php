<?php
$titre = 'Connexion';
require __DIR__ . '/include/entete.php';

// Message d'erreur laissé par requete_login.php, affiché une seule fois
$erreur = $_SESSION['erreur_connexion'] ?? null;
unset($_SESSION['erreur_connexion']);
?>

<h1>Connexion</h1>

<?php if ($erreur !== null): ?>
    <p style="color: red;" role="alert"><?= htmlspecialchars($erreur) ?></p>
<?php endif ?>

<form method="post" action="include/requete_login.php">
    <div>
        <label for="identifiant">Identifiant :</label>
        <input type="text" id="identifiant" name="identifiant" required>
    </div>
    <div>
        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
    </div>
    <button type="submit">Se connecter</button>
</form>

<p>Pas encore inscrit ? <a href="inscription.php">Créer un compte</a></p>

<?php require __DIR__ . '/include/pied.php'; ?>