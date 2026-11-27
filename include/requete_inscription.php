<?php
session_start();
require_once __DIR__ . '/connexion.php';
require_once __DIR__ . '/fonctions_sql.php';

// Enregistre l'erreur en session puis renvoie sur le formulaire
function retourInscription(string $erreur): void
{
    $_SESSION['erreur_inscription'] = $erreur;
    header('Location: ../inscription.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../inscription.php');
    exit;
}

$identifiant  = trim($_POST['identifiant'] ?? '');
$motDePasse   = $_POST['mot_de_passe'] ?? '';
$confirmation = $_POST['confirmation'] ?? '';

$longueurIdentifiant = mb_strlen($identifiant);
if ($longueurIdentifiant < 3 || $longueurIdentifiant > 50) {
    retourInscription("L'identifiant doit contenir entre 3 et 50 caractères.");
}

if (mb_strlen($motDePasse) < 8) {
    retourInscription('Le mot de passe doit contenir au moins 8 caractères.');
}

if ($motDePasse !== $confirmation) {
    retourInscription('Les deux mots de passe ne correspondent pas.');
}

try {
    if (identifiantExiste($pdo, $identifiant)) {
        retourInscription('Cet identifiant est déjà utilisé.');
    }

    $idNouveau = creerUtilisateur($pdo, $identifiant, $motDePasse);
} catch (PDOException $e) {
    // SQLSTATE 23000 : doublon détecté par la contrainte UNIQUE (inscriptions simultanées)
    if ($e->getCode() === '23000') {
        retourInscription('Cet identifiant est déjà utilisé.');
    }
    error_log("Requête d'inscription : " . $e->getMessage());
    retourInscription("Erreur lors de l'inscription.");
}

// Connexion automatique après création du compte
session_regenerate_id(true);
$_SESSION['utilisateur_id'] = $idNouveau;
$_SESSION['identifiant']    = $identifiant;

header('Location: ../index.php');
exit;