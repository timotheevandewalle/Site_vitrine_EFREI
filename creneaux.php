<?php
require_once 'includes/session.php';
require_once 'database/config.php';
require_once 'includes/config.php';

requireTeacher();

$pdo = getDBConnection();
$message = '';

// Ajouter un créneau
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $type_seance = $_POST['type_seance'];
    $matiere_id = intval($_POST['matiere_id']);
    $capacite_max = intval($_POST['capacite_max']);
    
    if (strtotime($date_fin) <= strtotime($date_debut)) {
        $message = '<div class="alert alert-error">La date de fin doit être postérieure à la date de début.</div>';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO creneau (dateheure_debut, dateheure_fin, type_seance, enseignant_id, matiere_id, capacite_max) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([$date_debut, $date_fin, $type_seance, $_SESSION['user_id'], $matiere_id, $capacite_max])) {
            $message = '<div class="alert alert-success">Créneau ajouté avec succès !</div>';
        } else {
            $message = '<div class="alert alert-error">Erreur lors de l\'ajout.</div>';
        }
    }
}

// Récupérer les matières
$matieres = [];
$stmt = $pdo->query("SELECT * FROM matiere ORDER BY nom");
$matieres = $stmt->fetchAll();

// Récupérer les créneaux de l'enseignant
$creneaux = [];
$stmt = $pdo->prepare("
    SELECT c.*, m.nom as matiere_nom, 
           (SELECT COUNT(*) FROM reservation r WHERE r.creneau_id = c.id AND r.statut != 'annulee') as nb_reservations
    FROM creneau c
    JOIN matiere m ON c.matiere_id = m.id
    WHERE c.enseignant_id = ?
    ORDER BY c.dateheure_debut DESC
");
$stmt->execute([$_SESSION['user_id']]);
$creneaux = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des créneaux - <?= SITE_NAME ?></title>
    <link rel="icon" href="assets/images/logo/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <section class="creneaux-section">
            <div class="container">
                <h1>Gestion des créneaux</h1>
                
                <?= $message ?>
                
                <!-- Formulaire d'ajout -->
                <div class="form-ajout">
                    <h2>Ajouter un créneau</h2>
                    <form method="POST" action="">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_debut">Début</label>
                                <input type="datetime-local" id="date_debut" name="date_debut" required>
                            </div>
                            <div class="form-group">
                                <label for="date_fin">Fin</label>
                                <input type="datetime-local" id="date_fin" name="date_fin" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="type_seance">Type de séance</label>
                                <select id="type_seance" name="type_seance">
                                    <option value="collective">Collective</option>
                                    <option value="individu">Individuelle</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="matiere_id">Matière</label>
                                <select id="matiere_id" name="matiere_id" required>
                                    <?php foreach ($matieres as $m): ?>
                                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="capacite_max">Capacité max</label>
                                <input type="number" id="capacite_max" name="capacite_max" value="20" min="1">
                            </div>
                        </div>
                        
                        <button type="submit" name="ajouter" class="btn btn-primary">Ajouter le créneau</button>
                    </form>
                </div>
                
                <!-- Liste des créneaux -->
                <div class="mes-creneaux">
                    <h2>Mes créneaux</h2>
                    
                    <?php if (empty($creneaux)): ?>
                        <p>Vous n'avez pas encore créé de créneaux.</p>
                    <?php