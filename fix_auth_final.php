<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration
$host = 'localhost:3307';
$user = 'root';
$pass = 'root';

echo "1. Tentative de connexion à MySQL sur $host...<br>";

try {
    // Connexion à MySQL sans spécifier de base
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "2. Connexion réussie !<br>";
    
    // Vérifier la méthode actuelle
    $stmt = $pdo->query("SELECT user, host, plugin FROM mysql.user WHERE user = 'root'");
    $userInfo = $stmt->fetch();
    echo "3. Méthode actuelle : " . $userInfo['plugin'] . "<br>";
    
    // Changer la méthode d'authentification
    echo "4. Changement de la méthode d'authentification...<br>";
    $pdo->exec("ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY ''");
    $pdo->exec("FLUSH PRIVILEGES");
    
    // Vérifier après changement
    $stmt = $pdo->query("SELECT user, host, plugin FROM mysql.user WHERE user = 'root'");
    $userInfo = $stmt->fetch();
    echo "5. Nouvelle méthode : " . $userInfo['plugin'] . "<br>";
    
    echo "<strong style='color:green'>✅ Authentification modifiée avec succès !</strong><br>";
    echo "Redémarre MAMP (clique sur 'Use anyway' si demandé), puis teste test_connexion.php";
    
} catch (PDOException $e) {
    echo "<strong style='color:red'>❌ Erreur :</strong> " . $e->getMessage() . "<br>";
    
    // Afficher des conseils selon l'erreur
    if (strpos($e->getMessage(), '2054') !== false) {
        echo "<br>📌 <strong>Solution alternative :</strong><br>";
        echo "1. Ouvre MAMP<br>";
        echo "2. Clique sur 'Preferences' → 'MySQL'<br>";
        echo "3. Coche 'Allow network access to MySQL'<br>";
        echo "4. Redémarre MAMP<br>";
    } elseif (strpos($e->getMessage(), '1045') !== false) {
        echo "<br>📌 Mot de passe incorrect. Si tu as un mot de passe, modifie \$pass dans ce script.";
    }
}
?>