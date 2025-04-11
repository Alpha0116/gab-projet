<?php
// Inclure la connexion à la base de données
require_once 'db.php';

// Vérifie si l'utilisateur est connecté
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Récupère les informations de l'utilisateur
function getUserInfo($user_id, $conn) {
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE utilisateur_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Traitement du retrait
function processWithdrawal($user_id, $amount, $conn) {
    $user = getUserInfo($user_id, $conn); // Récupère les infos utilisateur
    if ($user['solde'] >= $amount) {
        // Met à jour le solde de l'utilisateur
        $new_balance = $user['solde'] - $amount;
        $stmt = $conn->prepare("UPDATE utilisateurs SET solde = :solde WHERE utilisateur_id = :user_id");
        $stmt->execute(['solde' => $new_balance, 'user_id' => $user_id]);
        
        // Enregistre la transaction
        $stmt = $conn->prepare("INSERT INTO transactions (utilisateur_id, type, montant) VALUES (:user_id, 'retrait', :montant)");
        $stmt->execute(['user_id' => $user_id, 'montant' => $amount]);
        
        return true;
    } else {
        return false; // Solde insuffisant
    }
}

// Traitement du dépôt
function processDeposit($user_id, $amount, $conn) {
    // Met à jour le solde de l'utilisateur
    $stmt = $conn->prepare("UPDATE utilisateurs SET solde = solde + :montant WHERE utilisateur_id = :user_id");
    $stmt->execute(['montant' => $amount, 'user_id' => $user_id]);
    
    // Enregistre la transaction
    $stmt = $conn->prepare("INSERT INTO transactions (utilisateur_id, type, montant) VALUES (:user_id, 'dépôt', :montant)");
    $stmt->execute(['user_id' => $user_id, 'montant' => $amount]);
    
    return true;
}

// Récupère l'historique des transactions
function getTransactionHistory($user_id, $conn) {
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE utilisateur_id = :user_id ORDER BY date_creation DESC");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Récupérer les 5 dernières transactions
function getTransaction($userId, $conn) {
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE utilisateur_id = :user_id ORDER BY date_creation DESC LIMIT 5");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
