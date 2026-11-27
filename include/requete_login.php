<?php
session_start();
require_once __DIR__ . '/connexion.php';

// Enregistre l'erreur en session puis renvoie sur le formulaire
function retourConnexion(string $erreur): void
{
    $_SESSION['erreur_connexion'] = $erreur;
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$identifiant = trim($_POST['identifiant'] ?? '');
$motDePasse  = $_POST['mot_de_passe'] ?? '';

if ($identifiant === '' || $motDePasse === '') {
    retourConnexion('Identifiant et mot de passe requis.');
}

try {
    $stmt = $pdo->prepare('SELECT id, identifiant, mdp_hash FROM utilisateur WHERE identifiant = ?');
    $stmt->execute([$identifiant]);
    $utilisateur = $stmt->fetch();
} catch (PDOException $e) {
    error_log('Requête de connexion : ' . $e->getMessage());
    retourConnexion('Erreur lors de la connexion.');
}

if (!$utilisateur || !password_verify($motDePasse, $utilisateur['mdp_hash'])) {
    retourConnexion('Identifiant ou mot de passe incorrect.');
}

session_regenerate_id(true);
$_SESSION['utilisateur_id'] = $utilisateur['id'];
$_SESSION['identifiant']    = $utilisateur['identifiant'];

header('Location: ../index.php');
exit;