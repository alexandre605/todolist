<?php

$hote        = '127.0.0.1';
$port        = 3306;
$base        = 'todo_app';
$utilisateur = 'root';
$motDePasse  = '';

$dsn = "mysql:host=$hote;port=$port;dbname=$base;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $utilisateur, $motDePasse, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    // Une valeur invalide ou trop longue provoque une erreur
    $pdo->exec("SET SESSION sql_mode = 'STRICT_ALL_TABLES'");
} catch (PDOException $e) {
    // Détail dans le journal d'erreurs PHP, message neutre à l'écran
    error_log('Connexion BDD : ' . $e->getMessage());
    http_response_code(500);
    exit('Impossible de se connecter à la base de données.');
}