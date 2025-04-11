<?php
// Vérifier si la session est déjà démarrée, sinon la démarrer
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est déjà connecté
function checkSession() {
    if (isset($_SESSION['user_id'])) {
        // Si l'utilisateur est déjà connecté, le rediriger vers le dashboard
        header('Location: dashboard.php');
        exit;
    }
}

// Générer un token CSRF
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

// Vérifier la validité du token CSRF
function verifyCsrfToken($csrf_token) {
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $csrf_token;
}
?>
