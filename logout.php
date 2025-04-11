<?php
// Démarre la session
session_start();

// Inclut les fichiers nécessaires
require_once 'includes/session.php';  // Vérification de la session
require_once 'includes/db.php';  // Connexion à la base de données

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
    header('Location: index.php');
    exit;
}

// Détruire la session et déconnecter l'utilisateur
session_destroy();


// Rediriger l'utilisateur vers la page de connexion
header('Location: index.php');
exit;
?>
