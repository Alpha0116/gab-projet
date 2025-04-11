<?php
// Paramètres de connexion
$host = 'localhost';
$dbname = 'gab_db';
$username = 'root';
$password = '';

try {
    // Connexion à la base avec PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Définir le mode d'erreur sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
