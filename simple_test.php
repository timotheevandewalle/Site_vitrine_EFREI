<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$port = 3307;
$user = 'root';
$pass = 'root';

echo "Tentative de connexion avec :<br>";
echo "Host: $host<br>";
echo "Port: $port<br>";
echo "User: $user<br>";
echo "Pass: $pass<br><br>";

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion MySQL réussie !";
} catch (PDOException $e) {
    echo "❌ Échec : " . $e->getMessage();
}
?>