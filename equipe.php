<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
require_once 'database/teachers.php';
?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Notre équipe pédagogique</h1>
            <p>Des experts passionnés à votre service</p>
        </div>
    </section>

    <!-- Barre de recherche et filtres -->
    <section class="search-section">
        <div class="container">
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Rechercher un enseignant...">
                <button id="search-btn">🔍</button>
            </div>
            <div class="filter-tags">
                <button class="filter-tag active" data-specialite="all">Tous</button>
                <button class="filter-tag" data-specialite="IA">IA</button>
                <button class="filter-tag" data-specialite="Cyber">Cybersécurité</button>
                <button class="filter-tag" data-specialite="Data">Data</button>
                <button class="filter-tag" data-specialite="Dev">Développement</button>
            </div>
        </div>
    </section>

    <!-- Grille des enseignants -->
    <section class="teachers-grid-section">
        <div class="container">
            <div class="teachers-grid" id="teachers-grid">
                <?php foreach ($enseignants as $e): ?>
                <div class="teacher-card" data-nom="<?= strtolower($e['nom']).' '.strtolower($e['prenom']) ?>" data-specialite="<?= $e['specialite'] ?>">
                    <div class="teacher-img">
                        <img src="assets/images/teachers/<?= $e['photo'] ?>" alt="<?= $e['prenom'].' '.$e['nom'] ?>">
                    </div>
                    <h3><?= $e['prenom'] ?> <?= $e['nom'] ?></h3>
                    <p class="teacher-specialite"><?= $e['specialite'] ?></p>
                    <p class="teacher-email"><?= $e['email'] ?></p>
                    <p class="teacher-bio"><?= htmlspecialchars($e['bio']) ?></p>
                    <a href="#" class="btn btn-small voir-plus">Voir plus</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>