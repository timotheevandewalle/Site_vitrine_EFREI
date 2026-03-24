<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
require_once 'data/team.php'; // équipe projet
?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Contactez-nous</h1>
            <p>et découvrez notre équipe</p>
        </div>
    </section>

    <!-- Section À propos de nous (équipe projet) -->
    <section class="about-team">
        <div class="container">
            <h2>À propos de nous</h2>
            <div class="team-grid">
                <?php foreach ($team as $m): ?>
                <div class="team-member">
                    <img src="assets/images/team/<?= $m['photo'] ?>" alt="<?= $m['prenom'] ?>">
                    <h3><?= $m['prenom'] ?> <?= $m['nom'] ?></h3>
                    <p class="role"><?= $m['role'] ?></p>
                    <p class="contrib"><?= $m['contributions'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Formulaire de contact -->
    <section class="contact-form-section">
        <div class="container">
            <h2>Envoyez-nous un message</h2>
            <form id="contact-form" action="includes/contact-handler.php" method="POST">
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" id="nom" name="nom" required>
                    <span class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="sujet">Sujet</label>
                    <input type="text" id="sujet" name="sujet" required>
                    <span class="error-message"></span>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                    <span class="error-message"></span>
                </div>
                <div class="form-group checkbox">
                    <input type="checkbox" id="rgpd" name="rgpd" required>
                    <label for="rgpd">J'accepte que mes données soient traitées</label>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer le message</button>
            </form>
        </div>
    </section>

    <!-- Informations pratiques -->
    <section class="info-pratique">
        <div class="container">
            <div class="info-grid">
                <div class="map-placeholder">Carte</div>
                <div class="contact-details">
                    <h3>Adresse</h3>
                    <p>30-32 Avenue de la République, 94800 Villejuif</p>
                    <h3>Téléphone</h3>
                    <p>01 42 11 11 11</p>
                    <h3>Email</h3>
                    <p>info.informatique@efrei.fr</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>