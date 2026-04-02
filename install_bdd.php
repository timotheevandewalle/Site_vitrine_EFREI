<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$port = 3307;
$user = 'root';
$pass = 'root';
$dbname = 'efrei_reservations';

try {
    // Connexion avec le bon port
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Lire le fichier database.sql
    $sql = file_get_contents('database/database.sql');
    if ($sql === false) {
        die("❌ Impossible de lire le fichier database/database.sql");
    }
    
    // Exécuter le script SQL
    $pdo->exec($sql);
    echo "✅ Base de données '$dbname' créée et tables installées avec succès !<br>";
    echo "👉 <a href='ajouter_creneaux.php'>Clique ici pour ajouter des créneaux de test</a>";
    
} catch (PDOException $e) {
    die("❌ Erreur : " . $e->getMessage());
}
?>