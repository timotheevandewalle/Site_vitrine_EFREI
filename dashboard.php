<?php
require_once 'includes/session.php';
require_once 'database/config.php';
require_once 'includes/config.php';

requireLogin();

$pdo = getDBConnection();
$user = getCurrentUser($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - <?= SITE_NAME ?></title>
    <link rel="icon" href="assets/images/logo/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <section class="dashboard-section">
            <div class="container">
                <h1>Tableau de bord</h1>
                <p class="welcome-message">Bonjour <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong> !</p>
                
                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <div class="card-icon">📅</div>
                        <h3>Mes réservations</h3>
                        <p>Consultez et gérez vos créneaux réservés</p>
                        <a href="reservations.php" class="btn btn-secondary">Voir mes réservations</a>
                    </div>
                    
                    <?php if (isTeacher()): ?>
                    <div class="dashboard-card">
                        <div class="card-icon">👨‍🏫</div>
                        <h3>Mes créneaux</h3>
                        <p>Créez et gérez vos créneaux de permanence</p>
                        <a href="creneaux.php" class="btn btn-secondary">Gérer mes créneaux</a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="dashboard-card">
                        <div class="card-icon">📚</div>
                        <h3>Matières disponibles</h3>
                        <p>Découvrez toutes les matières proposées</p>
                        <a href="matieres.php" class="btn btn-secondary">Consulter</a>
                    </div>
                    
                    <div class="dashboard-card">
                        <div class="card-icon">🔓</div>
                        <h3>Déconnexion</h3>
                        <p>Quitter votre session</p>
                        <a href="logout.php" class="btn btn-secondary">Se déconnecter</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>