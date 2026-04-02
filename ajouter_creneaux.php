<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/config.php';

$pdo = getDBConnection();
if (!$pdo) {
    die("❌ Connexion à la base de données impossible.");
}

// Récupérer un enseignant existant
$stmt = $pdo->query("SELECT id FROM utilisateur WHERE role = 'enseignant' LIMIT 1");
$enseignant = $stmt->fetch();
if (!$enseignant) {
    die("❌ Aucun enseignant trouvé dans la table utilisateur.");
}
$enseignant_id = $enseignant['id'];

// Récupérer une matière existante
$stmt = $pdo->query("SELECT id FROM matiere LIMIT 1");
$matiere = $stmt->fetch();
if (!$matiere) {
    die("❌ Aucune matière trouvée dans la table matiere.");
}
$matiere_id = $matiere['id'];

// Supprimer d'abord les anciens créneaux pour éviter les doublons (optionnel)
$pdo->exec("DELETE FROM creneau WHERE dateheure_debut > NOW()");

// Ajouter 3 créneaux avec des dates futures
$sql = "INSERT INTO creneau (dateheure_debut, dateheure_fin, type_seance, enseignant_id, matiere_id, capacite_max) VALUES
        (NOW() + INTERVAL 1 DAY,  NOW() + INTERVAL 1 DAY + INTERVAL 2 HOUR,  'collective', ?, ?, 10),
        (NOW() + INTERVAL 2 DAY,  NOW() + INTERVAL 2 DAY + INTERVAL 1 HOUR,  'individu',   ?, ?, 3),
        (NOW() + INTERVAL 3 DAY,  NOW() + INTERVAL 3 DAY + INTERVAL 3 HOUR,  'collective', ?, ?, 15)";

$stmt = $pdo->prepare($sql);
if ($stmt->execute([$enseignant_id, $matiere_id, $enseignant_id, $matiere_id, $enseignant_id, $matiere_id])) {
    echo "✅ 3 créneaux futurs ajoutés avec succès.<br>";
    echo "👉 <a href='permanences.php'>Retourner à la page Permanences</a> pour voir les nouveaux créneaux.";
} else {
    echo "❌ Erreur lors de l'insertion : " . implode(" ", $stmt->errorInfo());
}