<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<main>
    <!-- Section Hero avec animation de texte -->
    <section class="hero">
        <div class="container">
            <h1 class="hero-title">
                Bienvenue au <br>
                <span class="typing-animation">Département Informatique</span>
            </h1>
            <p class="hero-subtitle">Formez-vous aux métiers du numérique avec l'EFREI</p>
            <a href="formations.php" class="btn btn-primary">Découvrir nos formations →</a>
        </div>
    </section>

    <!-- Carrousel d'actualités -->
    <section class="carousel-section">
        <div class="container">
            <h2>Actualités</h2>
            <div class="carousel-container">
                <button class="carousel-btn prev">←</button>
                <div class="carousel-track">
                    <div class="carousel-slide">
                        <img src="assets/images/carousel/hackathon.jpg" alt="Hackathon 2026">
                        <h3>Hackathon 2026</h3>
                        <p>48h pour innover</p>
                    </div>
                    <div class="carousel-slide">
                        <img src="assets/images/carousel/seminar.jpg" alt="Séminaire Cybersécurité">
                        <h3>Séminaire Cybersécurité</h3>
                        <p>Intervention d'experts</p>
                    </div>
                    <div class="carousel-slide">
                        <img src="assets/images/carousel/open-days.jpg" alt="Portes Ouvertes">
                        <h3>Portes Ouvertes</h3>
                        <p>Venez nous rencontrer</p>
                    </div>
                </div>
                <button class="carousel-btn next">→</button>
                <div class="carousel-dots"></div>
            </div>
        </div>
    </section>

    <!-- Cartes formations -->
    <section class="formations-home">
        <div class="container">
            <h2>Nos formations</h2>
            <div class="cards-grid">
                <div class="card">
                    <div class="card-icon">🧠</div>
                    <h3>Intelligence Artificielle</h3>
                    <p>Spécialisez-vous en IA et machine learning</p>
                    <a href="formations.php#ia" class="card-link">En savoir plus →</a>
                </div>
                <div class="card">
                    <div class="card-icon">🔒</div>
                    <h3>Cybersécurité</h3>
                    <p>Protégez les systèmes d'information</p>
                    <a href="formations.php#cyber" class="card-link">En savoir plus →</a>
                </div>
                <div class="card">
                    <div class="card-icon">📊</div>
                    <h3>Data Science</h3>
                    <p>Analysez et valorisez les données</p>
                    <a href="formations.php#data" class="card-link">En savoir plus →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Chiffres clés avec compteurs animés -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number" data-target="50">0</span><span>+</span>
                    <p>Années d'existence</p>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-target="95">0</span><span>%</span>
                    <p>Taux d'insertion</p>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-target="10">0</span>
                    <p>Associations étudiantes</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Partenaires -->
<section class="partners-section">
    <div class="container">
        <h2>Nos partenaires</h2>
        <p class="section-subtitle">Des entreprises et institutions qui nous accompagnent</p>
        <div class="partners-grid">
            <div class="partner-card">
                <img src="assets/images/partners/airbus.png" alt="Airbus">
            </div>
            <div class="partner-card">
                <img src="assets/images/partners/atos.png" alt="Atos">
            </div>
            <div class="partner-card">
                <img src="assets/images/partners/axa.png" alt="AXA">
            </div>
            <div class="partner-card">
                <img src="assets/images/partners/bnp-paribas.png" alt="BNP Paribas">
            </div>
            <div class="partner-card">
                <img src="assets/images/partners/capgemini.png" alt="Capgemini">
            </div>
            <div class="partner-card">
                <img src="assets/images/partners/edf.png" alt="EDF">
            </div>
            <div class="partner-card">
                <img src="assets/images/partners/orange.png" alt="Orange">
            </div>
            <div class="partner-card">
                <img src="assets/images/partners/sopra-steria.png" alt="Sopra Steria">
            </div>
        </div>
    </div>
</section>
    <!-- Agenda des permanences avec onglets -->
    <section class="agenda-section">
        <div class="container">
            <h2>Permanences des enseignants</h2>
            <div class="agenda-tabs">
                <button class="tab-btn active" data-day="lundi">Lundi</button>
                <button class="tab-btn" data-day="mardi">Mardi</button>
                <button class="tab-btn" data-day="mercredi">Mercredi</button>
                <button class="tab-btn" data-day="jeudi">Jeudi</button>
                <button class="tab-btn" data-day="vendredi">Vendredi</button>
            </div>
            <div class="agenda-content" id="agenda-content">
                <!-- Chargé via JS ou PHP -->
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>