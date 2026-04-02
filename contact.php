<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
require_once 'database/team.php'; // équipe projet
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
                <div class="<div class="info-grid">
    <div class="map-container">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2628.642148035137!2d2.3611817413833207!3d48.788721705725465!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e673e24e04a9c3%3A0xc55cb3e676f95321!2sEfrei!5e0!3m2!1sfr!2sfr!4v1774990457282!5m2!1sfr!2sfr" 
            width="100%" 
            height="250" 
            style="border:0; border-radius: 1rem;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
    <div class="contact-details">
        <h3>Adresse</h3>
        <p>30-32 Avenue de la République, 94800 Villejuif</p>
        <h3>Téléphone</h3>
        <p>01 42 11 11 11</p>
        <h3>Email</h3>
        <p>info.informatique@efrei.fr</p>
    </div>
</div></div>
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