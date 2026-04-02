<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<main>
    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <h1>Connexion</h1>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error">Email ou mot de passe incorrect.</div>
                <?php endif; ?>

                <form method="POST" action="includes/login-handler.php">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>

                <p class="register-link">
                    Pas encore de compte ? <a href="register.php">S'inscrire</a>
                </p>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>