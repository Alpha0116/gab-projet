<?php
require_once 'includes/session.php'; // Inclure la gestion des sessions et des CSRF tokens
require_once 'includes/db.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est déjà connecté
checkSession();

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $card_number = $_POST['card_number'];
    $pin = $_POST['pin'];

    // Requête pour vérifier l'utilisateur dans la base de données
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE numero_carte = :card_number");
    $stmt->execute(['card_number' => $card_number]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et si le PIN est correct
    if ($user && password_verify($pin, $user['code_pin'])) {
        // Authentification réussie, enregistrer l'utilisateur dans la session
        $_SESSION['user_id'] = $user['utilisateur_id'];
        $_SESSION['card_number'] = $user['numero_carte'];

        // Rediriger vers le tableau de bord
        header('Location: dashboard.php');
        exit;
    } else {
        // Authentification échouée
        $_SESSION['error_message'] = "Numéro de carte ou PIN incorrect.";
        header('Location: index.php');
        exit;
    }
}

// Générer un token CSRF si nécessaire
generateCsrfToken();
