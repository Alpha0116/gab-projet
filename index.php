<?php
require_once 'includes/session.php'; // Inclure la gestion des sessions et des CSRF tokens

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    // Si l'utilisateur est déjà connecté, le rediriger vers le dashboard
    header('Location: dashboard.php');
    exit;
}

// Si l'utilisateur n'est pas connecté, afficher la page de connexion
checkSession();
?>

<?php include('header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Connexion au GAB</h2>
    
    <!-- Formulaire de connexion -->
    <form action="login.php" method="POST">
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
        
        <!-- Affichage des erreurs -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php 
                    echo htmlspecialchars($_SESSION['error_message']); 
                    unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>

        <button type="submit" name="login" class="btn btn-primary w-100">Se connecter</button>
    </form>

    <!-- Liens supplémentaires -->
    <div class="mt-3 text-center">
        <a href="register.php" class="btn btn-link">Créer un nouveau compte</a> 
    </div>
</div>

<?php include('footer.php'); ?>
