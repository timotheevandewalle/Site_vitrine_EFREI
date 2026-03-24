<?php
require_once 'includes/session.php';
require_once 'database/config.php';
require_once 'includes/config.php';

requireLogin();

$pdo = getDBConnection();

// Récupérer toutes les matières
$matieres = [];
$stmt = $pdo->query("SELECT * FROM matiere ORDER BY nom");
$matieres = $stmt->fetchAll();

// Récupérer les statistiques pour chaque matière
foreach ($matieres as $key => $matiere) {
    // Nombre de créneaux disponibles
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as nb_creneaux 
        FROM creneau 
        WHERE matiere_id = ? AND dateheure_debut > NOW()
    ");
    $stmt->execute([$matiere['id']]);
    $matieres[$key]['nb_creneaux'] = $stmt->fetch()['nb_creneaux'];
    
    // Nombre total d'heures de cours
    $stmt = $pdo->prepare("
        SELECT SUM(TIMESTAMPDIFF(HOUR, dateheure_debut, dateheure_fin)) as total_heures
        FROM creneau 
        WHERE matiere_id = ?
    ");
    $stmt->execute([$matiere['id']]);
    $matieres[$key]['total_heures'] = $stmt->fetch()['total_heures'] ?? 0;
}

// Récupérer les matières les plus populaires (les plus réservées)
$matieresPopulaires = [];
$stmt = $pdo->query("
    SELECT m.id, m.nom, COUNT(r.id) as nb_reservations
    FROM matiere m
    LEFT JOIN reservation r ON m.id = r.matiere_id
    GROUP BY m.id
    ORDER BY nb_reservations DESC
    LIMIT 5
");
$matieresPopulaires = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matières - <?= SITE_NAME ?></title>
    <link rel="icon" href="assets/images/logo/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <section class="matieres-section">
            <div class="container">
                <h1>Matières disponibles</h1>
                <p class="section-subtitle">Découvrez toutes les matières enseignées au département informatique</p>
                
                <?php if (empty($matieres)): ?>
                    <p>Aucune matière disponible pour le moment.</p>
                <?php else: ?>
                    <!-- Grille des matières -->
                    <div class="matieres-grid">
                        <?php foreach ($matieres as $m): ?>
                            <div class="matiere-card">
                                <div class="matiere-icon">
                                    <?php
                                    // Icône en fonction du nom de la matière
                                    $icon = '📘';
                                    if (strpos($m['nom'], 'Intelligence') !== false) $icon = '🧠';
                                    elseif (strpos($m['nom'], 'Cyber') !== false) $icon = '🔒';
                                    elseif (strpos($m['nom'], 'Data') !== false) $icon = '📊';
                                    elseif (strpos($m['nom'], 'Web') !== false) $icon = '💻';
                                    elseif (strpos($m['nom'], 'Réseaux') !== false) $icon = '🌐';
                                    elseif (strpos($m['nom'], 'Java') !== false) $icon = '☕';
                                    elseif (strpos($m['nom'], 'Base') !== false) $icon = '🗄️';
                                    elseif (strpos($m['nom'], 'DevOps') !== false) $icon = '⚙️';
                                    echo $icon;
                                    ?>
                                </div>
                                <h3><?= htmlspecialchars($m['nom']) ?></h3>
                                <p class="matiere-description"><?= htmlspecialchars($m['description']) ?></p>
                                <div class="matiere-stats">
                                    <span class="stat">
                                        <strong><?= $m['nb_creneaux'] ?></strong> créneaux
                                    </span>
                                    <span class="stat">
                                        <strong><?= $m['total_heures'] ?></strong> heures
                                    </span>
                                </div>
                                <?php if (isStudent()): ?>
                                    <a href="reservations.php" class="btn btn-secondary btn-small">Voir les créneaux</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Matières populaires -->
                    <?php if (!empty($matieresPopulaires)): ?>
                    <div class="matieres-populaires">
                        <h2>Matières les plus populaires</h2>
                        <div class="popular-list">
                            <?php foreach ($matieresPopulaires as $mp): ?>
                                <div class="popular-item">
                                    <span class="popular-name"><?= htmlspecialchars($mp['nom']) ?></span>
                                    <div class="popular-bar">
                                        <div class="popular-fill" style="width: <?= min(100, $mp['nb_reservations'] * 5) ?>%"></div>
                                    </div>
                                    <span class="popular-count"><?= $mp['nb_reservations'] ?> réservations</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>