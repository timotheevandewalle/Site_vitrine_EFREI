<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/header.php';
require_once 'database/courses.php'; // contient $formations

// Test de débogage
echo '<!-- Fichier courses.php chargé : ' . (file_exists('database/courses.php') ? 'OUI' : 'NON') . ' -->';
echo '<!-- $formations défini : ' . (isset($formations) ? 'OUI' : 'NON') . ' -->';
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
                    <?php if (isset($formations) && is_array($formations)): ?>
                        <?php foreach ($formations as $f): ?>
                            <tr>
                                <td><?= htmlspecialchars($f['titre']) ?></td>
                                <td><?= $f['duree'] ?></td>
                                <td><?= implode(', ', $f['specialites']) ?></td>
                                <td><?= $f['niveau'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
            <?php if (isset($formations) && is_array($formations)): ?>
                <?php foreach ($formations as $f): ?>
                    <div class="course-card" data-categories="<?= implode(' ', $f['categories']) ?>">
                        <h3><?= htmlspecialchars($f['titre']) ?></h3>
                        <p><strong>Durée :</strong> <?= $f['duree'] ?></p>
                        <p><strong>Niveau :</strong> <?= $f['niveau'] ?></p>
                        <p><strong>Spécialités :</strong> <?= implode(', ', $f['specialites']) ?></p>
                        <p><?= htmlspecialchars($f['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Erreur : Aucune formation trouvée.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>