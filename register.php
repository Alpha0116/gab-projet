<?php
session_start();
require_once 'includes/db.php';  // Connexion à la base de données

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du token CSRF
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        // Récupérer les données du formulaire
        $numero_carte = $_POST['card_number'];
        $code_pin = $_POST['pin'];

        // Validation des entrées
        if (empty($numero_carte) || empty($code_pin)) {
            $_SESSION['error_message'] = 'Tous les champs sont requis.';
        } elseif (!preg_match('/^\d{16}$/', $numero_carte)) {
            $_SESSION['error_message'] = 'Le numéro de carte doit être composé de 16 chiffres.';
        } elseif (!preg_match('/^\d{4}$/', $code_pin)) {
            $_SESSION['error_message'] = 'Le code PIN doit être composé de 4 chiffres.';
        } else {
            // Hacher le code PIN avant de le stocker
            $hashed_pin = password_hash($code_pin, PASSWORD_BCRYPT);

            // Insérer l'utilisateur dans la base de données
            try {
                $stmt = $conn->prepare("INSERT INTO utilisateurs (numero_carte, code_pin) VALUES (:numero_carte, :code_pin)");
                $stmt->bindParam(':numero_carte', $numero_carte);
                $stmt->bindParam(':code_pin', $hashed_pin);
                $stmt->execute();

                $_SESSION['message'] = 'Utilisateur enregistré avec succès. Vous pouvez maintenant vous connecter.';
                header('Location: index.php');  // Rediriger vers la page de connexion
                exit;
            } catch (PDOException $e) {
                $_SESSION['error_message'] = 'Erreur lors de l\'enregistrement : ' . $e->getMessage();
            }
        }
    } else {
        $_SESSION['error_message'] = 'Token CSRF invalide.';
    }
}

// Générer un token CSRF pour la sécurité
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<?php include('header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Créer un Compte</h2>
    
    <!-- Affichage des messages d'erreur ou de succès -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    
    <form action="register.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="mb-3">
            <label for="card_number" class="form-label">Numéro de carte</label>
            <input type="text" class="form-control" name="card_number" 
                   pattern="\d{16}" title="16 chiffres requis" 
                   oninput="this.value = this.value.replace(/\D/g, '')" required>
        </div>

        <div class="mb-3">
            <label for="pin" class="form-label">Code PIN</label>
            <input type="password" class="form-control" name="pin" 
                   pattern="\d{4}" title="4 chiffres requis" 
                   maxlength="4" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Créer le compte</button>
        <!-- lien pour rediriger vers la page de connexion -->
        <div class="mt-3 text-center">
            <a href="index.php" class="btn btn-link">Se connecter</a> 
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
