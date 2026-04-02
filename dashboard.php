<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

requireLogin();

$pdo = getDBConnection();
$user = getCurrentUser($pdo);

// Récupérer les réservations de l'étudiant
$reservations = [];
if (isStudent() && $pdo) {
    $stmt = $pdo->prepare("
        SELECT r.id as reservation_id, r.*, c.dateheure_debut, c.dateheure_fin, 
               m.nom as matiere_nom, u.nom as enseignant_nom, u.prenom as enseignant_prenom
        FROM reservation r
        JOIN creneau c ON r.creneau_id = c.id
        JOIN matiere m ON r.matiere_id = m.id
        LEFT JOIN utilisateur u ON c.enseignant_id = u.id
        WHERE r.etudiant_id = ?
        ORDER BY c.dateheure_debut ASC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $reservations = $stmt->fetchAll();
}

// Statistiques
$stats = [];
if (isStudent() && $pdo) {
    // Nombre total de réservations
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM reservation WHERE etudiant_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['total_reservations'] = $stmt->fetch()['total'];
    
    // Nombre de réservations à venir
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as a_venir 
        FROM reservation r
        JOIN creneau c ON r.creneau_id = c.id
        WHERE r.etudiant_id = ? AND c.dateheure_debut > NOW()
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['a_venir'] = $stmt->fetch()['a_venir'];
}
?>

<main>
    <section class="dashboard-section">
        <div class="container">
            <h1>Mon tableau de bord</h1>
            <p class="welcome-message">
                Bonjour <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong> ! 
                Bienvenue sur votre espace personnel.
            </p>
            
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    
                    <h3>Mes informations</h3>
                    <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Rôle :</strong> <?= $user['role'] === 'enseignant' ? 'Enseignant' : 'Étudiant' ?></p>
                    <p><strong>Inscrit depuis :</strong> <?= date('d/m/Y', strtotime($user['date_inscription'])) ?></p>
                </div>
                
                <div class="dashboard-card">
                    
                    <h3>Mes statistiques</h3>
                    <?php if (isStudent()): ?>
                        <p><strong> Réservations totales :</strong> <?= $stats['total_reservations'] ?? 0 ?></p>
                        <p><strong> À venir :</strong> <?= $stats['a_venir'] ?? 0 ?></p>
                    <?php else: ?>
                        <p> Vous êtes enseignant</p>
                        <p> Gérez vos créneaux de permanence</p>
                    <?php endif; ?>
                </div>
                
                <div class="dashboard-card">
                    <h3>Actions rapides</h3>
                    <?php if (isStudent()): ?>
                        <p> Consulter les permanences</p>
                        <a href="permanences.php" class="btn btn-secondary">Voir les permanences</a>
                    <?php elseif (isTeacher()): ?>
                        <p> Gérer mes créneaux</p>
                        <a href="creneaux.php" class="btn btn-secondary">Gérer les créneaux</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (isStudent()): ?>
            <!-- Mes réservations -->
            <div class="mes-reservations-dashboard">
                <h2> Mes réservations</h2>
                <?php if (empty($reservations)): ?>
                    <p>Vous n'avez aucune réservation pour le moment.</p>
                    <a href="permanences.php" class="btn btn-primary">Réserver un créneau</a>
                <?php else: ?>
                    <div class="reservations-list-dashboard">
                        <?php foreach ($reservations as $r): ?>
                            <div class="reservation-card-dashboard">
                                <div class="reservation-info">
                                    <strong><?= htmlspecialchars($r['matiere_nom']) ?></strong>
                                    <span class="statut-badge">
                                        <?= $r['statut'] === 'validee' ? ' Validée' : ($r['statut'] === 'en_attente' ? ' En attente' : ' À déclarer') ?>
                                    </span>
                                </div>
                                <div class="reservation-details">
                                    <p> <?= date('d/m/Y H:i', strtotime($r['dateheure_debut'])) ?> - <?= date('H:i', strtotime($r['dateheure_fin'])) ?></p>
                                    <p> Enseignant : <?= htmlspecialchars($r['enseignant_prenom'] . ' ' . $r['enseignant_nom']) ?></p>
                                </div>
                                <form method="POST" action="permanences.php">
                                    <input type="hidden" name="reservation_id" value="<?= $r['reservation_id'] ?>">
                                    <button type="submit" name="annuler" class="btn btn-small btn-danger">Annuler</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>