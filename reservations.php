<?php
require_once 'includes/session.php';
require_once 'database/config.php';
require_once 'includes/config.php';

requireStudent();

$pdo = getDBConnection();
$user = getCurrentUser($pdo);
$message = '';

// Traitement de la réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserver'])) {
    $creneau_id = intval($_POST['creneau_id']);
    $matiere_id = intval($_POST['matiere_id']);
    
    // Vérifier que le créneau n'est pas complet
    $stmt = $pdo->prepare("
        SELECT c.*, COUNT(r.id) as nb_reservations 
        FROM creneau c 
        LEFT JOIN reservation r ON c.id = r.creneau_id AND r.statut != 'annulee'
        WHERE c.id = ? 
        GROUP BY c.id
    ");
    $stmt->execute([$creneau_id]);
    $creneau = $stmt->fetch();
    
    if ($creneau && $creneau['nb_reservations'] < $creneau['capacite_max']) {
        // Vérifier que l'étudiant n'a pas déjà réservé
        $stmt = $pdo->prepare("SELECT id FROM reservation WHERE creneau_id = ? AND etudiant_id = ?");
        $stmt->execute([$creneau_id, $_SESSION['user_id']]);
        
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO reservation (creneau_id, etudiant_id, matiere_id, statut) VALUES (?, ?, ?, 'validee')");
            if ($stmt->execute([$creneau_id, $_SESSION['user_id'], $matiere_id])) {
                $message = '<div class="alert alert-success">Réservation effectuée avec succès !</div>';
            } else {
                $message = '<div class="alert alert-error">Erreur lors de la réservation.</div>';
            }
        } else {
            $message = '<div class="alert alert-error">Vous avez déjà réservé ce créneau.</div>';
        }
    } else {
        $message = '<div class="alert alert-error">Ce créneau est complet.</div>';
    }
}

// Récupérer les réservations de l'étudiant
$reservations = [];
$stmt = $pdo->prepare("
    SELECT r.*, c.dateheure_debut, c.dateheure_fin, c.type_seance, 
           m.nom as matiere_nom, u.nom as enseignant_nom, u.prenom as enseignant_prenom
    FROM reservation r
    JOIN creneau c ON r.creneau_id = c.id
    JOIN matiere m ON r.matiere_id = m.id
    LEFT JOIN utilisateur u ON c.enseignant_id = u.id
    WHERE r.etudiant_id = ?
    ORDER BY c.dateheure_debut DESC
");
$stmt->execute([$_SESSION['user_id']]);
$reservations = $stmt->fetchAll();

// Récupérer les créneaux disponibles
$creneauxDisponibles = [];
$stmt = $pdo->prepare("
    SELECT c.*, m.nom as matiere_nom, u.nom as enseignant_nom, u.prenom as enseignant_prenom,
           (SELECT COUNT(*) FROM reservation r WHERE r.creneau_id = c.id AND r.statut != 'annulee') as nb_reservations
    FROM creneau c
    JOIN matiere m ON c.matiere_id = m.id
    LEFT JOIN utilisateur u ON c.enseignant_id = u.id
    WHERE c.dateheure_debut > NOW()
    ORDER BY c.dateheure_debut ASC
");
$stmt->execute();
$creneauxDisponibles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservations - <?= SITE_NAME ?></title>
    <link rel="icon" href="assets/images/logo/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <section class="reservations-section">
            <div class="container">
                <h1>Gestion des réservations</h1>
                
                <?= $message ?>
                
                <!-- Créneaux disponibles -->
                <div class="creneaux-disponibles">
                    <h2>Créneaux disponibles</h2>
                    
                    <?php if (empty($creneauxDisponibles)): ?>
                        <p>Aucun créneau disponible pour le moment.</p>
                    <?php else: ?>
                        <div class="creneaux-grid">
                            <?php foreach ($creneauxDisponibles as $c): ?>
                                <?php $placesRestantes = $c['capacite_max'] - $c['nb_reservations']; ?>
                                <div class="creneau-card">
                                    <div class="creneau-header">
                                        <span class="matiere"><?= htmlspecialchars($c['matiere_nom']) ?></span>
                                        <span class="type <?= $c['type_seance'] ?>"><?= $c['type_seance'] === 'individu' ? '👤 Individuel' : '👥 Collectif' ?></span>
                                    </div>
                                    <div class="creneau-info">
                                        <p><strong>📅 Date :</strong> <?= date('d/m/Y', strtotime($c['dateheure_debut'])) ?></p>
                                        <p><strong>⏰ Horaire :</strong> <?= date('H:i', strtotime($c['dateheure_debut'])) ?> - <?= date('H:i', strtotime($c['dateheure_fin'])) ?></p>
                                        <p><strong>👨‍🏫 Enseignant :</strong> <?= htmlspecialchars($c['enseignant_prenom'] . ' ' . $c['enseignant_nom']) ?></p>
                                        <p><strong>📊 Places :</strong> <?= $placesRestantes ?> / <?= $c['capacite_max'] ?></p>
                                    </div>
                                    <?php if ($placesRestantes > 0): ?>
                                        <form method="POST" action="">
                                            <input type="hidden" name="creneau_id" value="<?= $c['id'] ?>">
                                            <input type="hidden" name="matiere_id" value="<?= $c['matiere_id'] ?>">
                                            <button type="submit" name="reserver" class="btn btn-primary">Réserver</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>Complet</button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Mes réservations -->
                <div class="mes-reservations">
                    <h2>Mes réservations</h2>
                    
                    <?php if (empty($reservations)): ?>
                        <p>Vous n'avez aucune réservation.</p>
                    <?php else: ?>
                        <div class="reservations-list">
                            <?php foreach ($reservations as $r): ?>
                                <div class="reservation-card statut-<?= $r['statut'] ?>">
                                    <div class="reservation-info">
                                        <strong><?= htmlspecialchars($r['matiere_nom']) ?></strong>
                                        <span class="statut-badge"><?= $r['statut'] === 'validee' ? '✅ Validée' : ($r['statut'] === 'en_attente' ? '⏳ En attente' : '⚠️ À déclarer') ?></span>
                                    </div>
                                    <div class="reservation-details">
                                        <p><?= date('d/m/Y H:i', strtotime($r['dateheure_debut'])) ?> - <?= date('H:i', strtotime($r['dateheure_fin'])) ?></p>
                                        <p>Enseignant : <?= htmlspecialchars($r['enseignant_prenom'] . ' ' . $r['enseignant_nom']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>