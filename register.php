<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<main>
    <section class="register-section">
        <div class="container">
            <div class="register-container">
                <h1>Inscription</h1>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        Inscription réussie ! Vous pouvez maintenant <a href="login.php">vous connecter</a>.
                    </div>
                <?php endif; ?>

                <form method="POST" action="includes/register-handler.php">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmer</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role">Rôle</label>
                        <select id="role" name="role">
                            <option value="etudiant">Étudiant</option>
                            <option value="enseignant">Enseignant</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </form>

                <p class="login-link">
                    Déjà un compte ? <a href="login.php">Se connecter</a>
                </p>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>