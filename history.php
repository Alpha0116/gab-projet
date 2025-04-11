<?php
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

// Récupère l'historique des transactions de l'utilisateur
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM transactions WHERE utilisateur_id = :user_id ORDER BY date_creation DESC");
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Historique des Transactions</h2>

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

    <!-- Tableau des transactions -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Type</th>
                <th scope="col">Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($transactions) > 0): ?>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction['date_creation']); ?></td>
                        <td><?php echo ucfirst($transaction['type']); ?></td>
                        <td><?php echo number_format($transaction['montant'], 2, ',', ' '); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">Aucune transaction effectuée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-3">
        <a href="dashboard.php" class="btn btn-primary w-100">Retour au tableau de bord</a>
    </div>
</div>

<?php include('footer.php'); ?>
