<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
require_once 'database/schedule.php';

$pdo = getDBConnection();
$user = null;
if (isLoggedIn()) {
    $user = getCurrentUser($pdo);
}

// Traitement de la réservation
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserver']) && isStudent()) {
    $creneau_id = intval($_POST['creneau_id']);
    $matiere_id = intval($_POST['matiere_id']);
    
    if ($pdo) {
        // Vérifier les places disponibles
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
            // Vérifier si l'étudiant n'a pas déjà réservé ce créneau
            $stmt = $pdo->prepare("SELECT id FROM reservation WHERE creneau_id = ? AND etudiant_id = ?");
            $stmt->execute([$creneau_id, $_SESSION['user_id']]);
            if (!$stmt->fetch()) {
                $stmt = $pdo->prepare("INSERT INTO reservation (creneau_id, etudiant_id, matiere_id, statut) VALUES (?, ?, ?, 'validee')");
                if ($stmt->execute([$creneau_id, $_SESSION['user_id'], $matiere_id])) {
                    $message = '<div class="alert alert-success">Réservation effectuée !</div>';
                } else {
                    $message = '<div class="alert alert-error">Erreur lors de la réservation.</div>';
                }
            } else {
                $message = '<div class="alert alert-warning">Vous avez déjà réservé ce créneau.</div>';
            }
        } else {
            $message = '<div class="alert alert-error">Ce créneau est complet.</div>';
        }
    } else {
        $message = '<div class="alert alert-error">Base de données non disponible.</div>';
    }
}

// Traitement de l'annulation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['annuler']) && isStudent()) {
    $reservation_id = intval($_POST['reservation_id']);
    if ($pdo) {
        $stmt = $pdo->prepare("DELETE FROM reservation WHERE id = ? AND etudiant_id = ?");
        if ($stmt->execute([$reservation_id, $_SESSION['user_id']])) {
            $message = '<div class="alert alert-success">Réservation annulée.</div>';
        } else {
            $message = '<div class="alert alert-error">Erreur lors de l\'annulation.</div>';
        }
    } else {
        $message = '<div class="alert alert-error">Base de données non disponible.</div>';
    }
}

// Récupérer les créneaux disponibles (futurs)
$creneauxDisponibles = [];
if ($pdo) {
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
}

// Récupérer les réservations de l'étudiant connecté
$reservations = [];
if (isStudent() && $pdo) {
    $stmt = $pdo->prepare("
        SELECT r.id as reservation_id, r.*, c.dateheure_debut, c.dateheure_fin, c.type_seance,
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
?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Permanences des enseignants</h1>
            <p>Réservez vos créneaux de soutien</p>
        </div>
    </section>

    <!-- Affichage des messages -->
    <?php if ($message): ?>
        <div class="container"><?= $message ?></div>
    <?php endif; ?>

    <!-- Permanences fixes (agenda) -->
    <section class="agenda-section">
        <div class="container">
            <h2>Permanences hebdomadaires</h2>
            <div class="agenda-tabs">
                <button class="tab-btn active" data-day="lundi">Lundi</button>
                <button class="tab-btn" data-day="mardi">Mardi</button>
                <button class="tab-btn" data-day="mercredi">Mercredi</button>
                <button class="tab-btn" data-day="jeudi">Jeudi</button>
                <button class="tab-btn" data-day="vendredi">Vendredi</button>
            </div>
            <div class="agenda-content" id="agenda-content"></div>
        </div>
    </section>

    <?php if (isStudent()): ?>
        <!-- Mes réservations -->
        <section class="mes-reservations">
            <div class="container">
                <h2>Mes réservations</h2>
                <?php if (empty($reservations)): ?>
                    <p>Vous n'avez aucune réservation.</p>
                <?php else: ?>
                    <div class="reservations-list">
                        <?php foreach ($reservations as $r): ?>
                            <div class="reservation-card">
                                <div class="reservation-info">
                                    <strong><?= htmlspecialchars($r['matiere_nom']) ?></strong>
                                    <span class="statut-badge"> Validée</span>
                                </div>
                                <div class="reservation-details">
                                    <p><?= date('d/m/Y H:i', strtotime($r['dateheure_debut'])) ?> - <?= date('H:i', strtotime($r['dateheure_fin'])) ?></p>
                                    <p>Enseignant : <?= htmlspecialchars($r['enseignant_prenom'] . ' ' . $r['enseignant_nom']) ?></p>
                                </div>
                                <form method="POST" action="">
                                    <input type="hidden" name="reservation_id" value="<?= $r['reservation_id'] ?>">
                                    <button type="submit" name="annuler" class="btn btn-small btn-danger">Annuler</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Créneaux dynamiques disponibles -->
    <section class="creneaux-dynamiques">
        <div class="container">
            <h2>Créneaux spéciaux à venir</h2>
            <?php if (empty($creneauxDisponibles)): ?>
                <p>Aucun créneau spécial pour le moment.</p>
            <?php else: ?>
                <div class="creneaux-grid">
                    <?php foreach ($creneauxDisponibles as $c): ?>
                        <?php $placesRestantes = $c['capacite_max'] - $c['nb_reservations']; ?>
                        <div class="creneau-card">
                            <div class="creneau-header">
                                <span class="matiere"><?= htmlspecialchars($c['matiere_nom']) ?></span>
                                <span class="type"><?= $c['type_seance'] === 'individu' ? ' Individuel' : ' Collectif' ?></span>
                            </div>
                            <div class="creneau-info">
                                <p><strong> Date :</strong> <?= date('d/m/Y', strtotime($c['dateheure_debut'])) ?></p>
                                <p><strong> Horaire :</strong> <?= date('H:i', strtotime($c['dateheure_debut'])) ?> - <?= date('H:i', strtotime($c['dateheure_fin'])) ?></p>
                                <p><strong> Enseignant :</strong> <?= htmlspecialchars($c['enseignant_prenom'] . ' ' . $c['enseignant_nom']) ?></p>
                                <p><strong> Places :</strong> <?= $placesRestantes ?> / <?= $c['capacite_max'] ?></p>
                            </div>
                            <?php if (isStudent()): ?>
                                <?php if ($placesRestantes > 0): ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="creneau_id" value="<?= $c['id'] ?>">
                                        <input type="hidden" name="matiere_id" value="<?= $c['matiere_id'] ?>">
                                        <button type="submit" name="reserver" class="btn btn-primary">Réserver</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>Complet</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-secondary">Connectez-vous pour réserver</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
    window.agendaData = <?= json_encode($permanences, JSON_UNESCAPED_UNICODE) ?>;
</script>

<?php require_once 'includes/footer.php'; ?>