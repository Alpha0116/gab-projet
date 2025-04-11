<?php
// Démarre la session
session_start();

// Inclut les fichiers nécessaires
require_once 'includes/functions.php';
require_once 'includes/session.php';  // Vérification de la session
require_once 'includes/db.php';  // Connexion à la base de données

// Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
if (!isUserLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Récupère les informations de l'utilisateur connecté
$user = getUserInfo($_SESSION['user_id'], $conn);

// Récupère l'historique des 5 dernières transactions
$transactions = getTransaction($_SESSION['user_id'], $conn);
?>

<?php include('header.php'); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="dashboard-title">Bienvenue, <?php echo htmlspecialchars($user['numero_carte']); ?></h3>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h3>Votre solde : <?php echo number_format($user['solde'], 2); ?> FCFA</h3>
        </div>
    </div>

    <!-- Affichage des messages de succès ou d'erreur -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Boutons de retrait et dépôt -->
    <div class="row mb-4">
        <div class="col-md-4">
            <a href="withdraw.php" class="btn btn-warning">Effectuer un retrait</a>
        </div>
        <div class="col-md-4">
            <a href="deposit.php" class="btn btn-success">Effectuer un dépôt</a>
        </div>
        <div class="col-md-4">
            <a href="history.php" class="btn btn-info">Voir tout l'historique</a>
        </div>
    </div>

    <!-- Historique des 5 dernières transactions -->
    <h3>Vos 05 dernières transactions</h3>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Type</th>
                <th scope="col">Montant</th>
                <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transaction['type']); ?></td>
                    <td><?php echo number_format($transaction['montant'], 2); ?> FCFA</td>
                    <td><?php echo htmlspecialchars($transaction['date_creation']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
