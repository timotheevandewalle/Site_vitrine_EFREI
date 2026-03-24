<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
require_once 'data/courses.php'; // contient $formations
?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Nos formations en informatique</h1>
            <p>Découvrez tous nos diplômes et spécialités</p>
        </div>
    </section>

    <!-- Tableau comparatif -->
    <section class="table-section">
        <div class="container">
            <h2>Tableau comparatif</h2>
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th>Diplôme</th>
                        <th>Durée</th>
                        <th>Spécialités</th>
                        <th>Pré-requis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($formations as $f): ?>
                    <tr>
                        <td><?= htmlspecialchars($f['titre']) ?></td>
                        <td><?= $f['duree'] ?></td>
                        <td><?= implode(', ', $f['specialites']) ?></td>
                        <td><?= $f['niveau'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Filtres rapides -->
    <section class="filters-section">
        <div class="container">
            <h2>Filtrer par domaine</h2>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">Tous</button>
                <button class="filter-btn" data-filter="ia">IA</button>
                <button class="filter-btn" data-filter="cyber">Cybersécurité</button>
                <button class="filter-btn" data-filter="data">Data</button>
                <button class="filter-btn" data-filter="dev">Développement</button>
            </div>
        </div>
    </section>

    <!-- Liste détaillée des formations -->
    <section class="courses-list">
        <div class="container">
            <?php foreach ($formations as $f): ?>
            <div class="course-card" data-categories="<?= implode(' ', $f['categories']) ?>">
                <h3><?= $f['titre'] ?></h3>
                <p><strong>Durée :</strong> <?= $f['duree'] ?></p>
                <p><strong>Niveau :</strong> <?= $f['niveau'] ?></p>
                <p><strong>Spécialités :</strong> <?= implode(', ', $f['specialites']) ?></p>
                <p><?= $f['description'] ?></p>
                <a href="#" class="btn btn-secondary">Télécharger la brochure</a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>