<?php
$titre = 'Inscription';
require __DIR__ . '/include/entete.php';

// Message d'erreur laissé par include/requete_inscription.php, affiché une seule fois
$erreur = $_SESSION['erreur_inscription'] ?? null;
unset($_SESSION['erreur_inscription']);
?>

<h1>Inscription</h1>

<?php if ($erreur !== null): ?>
    <p style="color: red;" role="alert"><?= htmlspecialchars($erreur) ?></p>
<?php endif ?>

<form method="post" action="include/requete_inscription.php">
    <div>
        <label for="identifiant">Identifiant (3 à 50 caractères) :</label>
        <input type="text" id="identifiant" name="identifiant" minlength="3" maxlength="50" required>
    </div>
    <div>
        <label for="mot_de_passe">Mot de passe (8 caractères minimum) :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" minlength="8" required>
    </div>
    <div>
        <label for="confirmation">Confirmer le mot de passe :</label>
        <input type="password" id="confirmation" name="confirmation" minlength="8" required>
    </div>
    <button type="submit">Créer mon compte</button>
</form>

<p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>

<?php require __DIR__ . '/include/pied.php'; ?>