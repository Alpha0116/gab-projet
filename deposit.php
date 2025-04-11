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

// Traitement du dépôt
if (isset($_POST['submit'])) {
    $montant = $_POST['montant'];

    // Vérifie si le montant est valide
    if ($montant <= 0) {
        $_SESSION['error_message'] = "Le montant doit être supérieur à zéro.";
    } else {
        // Récupère les informations de l'utilisateur connecté
        $userId = $_SESSION['user_id'];

        // Effectuer le dépôt dans la base de données (mise à jour du solde et ajout d'une transaction)
        try {
            $stmt = $conn->prepare("UPDATE utilisateurs SET solde = solde + :montant WHERE utilisateur_id = :user_id");
            $stmt->bindParam(':montant', $montant);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            // Enregistrer la transaction dans la table transactions
            $stmt = $conn->prepare("INSERT INTO transactions (utilisateur_id, type, montant) VALUES (:user_id, 'dépôt', :montant)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':montant', $montant);
            $stmt->execute();

            $_SESSION['message'] = "Dépôt effectué avec succès.";
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur lors du dépôt : " . $e->getMessage();
        }
    }

    // Redirige vers la page de dépôt pour afficher le message
    header('Location: deposit.php');
    exit;
}

// Récupère les informations de l'utilisateur connecté
$user = getUserInfo($_SESSION['user_id'], $conn);
?>

<?php include('header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Effectuer un dépôt</h2>
    
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

    <form action="deposit.php" method="POST">
        <div class="mb-3">
            <label for="montant" class="form-label">Montant à déposer (en FCFA)</label>
            <input type="number" class="form-control" id="montant" name="montant" min="0.01" step="0.01" required>
        </div>

        <button type="submit" name="submit" class="btn btn-success w-100">Effectuer le dépôt</button>
    </form>

    <div class="mt-3">
        <a href="dashboard.php" class="btn btn-primary w-100">Retour au tableau de bord</a>
    </div>
</div>

<?php include('footer.php'); ?>
