<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Configuration générale
define('SITE_NAME', 'Département Informatique EFREI');
define('CONTACT_EMAIL', 'info.informatique@efrei.fr');
date_default_timezone_set('Europe/Paris');

// Configuration base de données
define('DB_HOST', 'localhost:3307');   // ← PORT CORRIGÉ !
define('DB_NAME', 'efrei_reservations');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonctions de session
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isTeacher() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'enseignant';
}

function isStudent() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireTeacher() {
    requireLogin();
    if (!isTeacher()) {
        header('Location: index.php');
        exit;
    }
}

function requireStudent() {
    requireLogin();
    if (!isStudent()) {
        header('Location: index.php');
        exit;
    }
}

function getCurrentUser($pdo) {
    if (!isLoggedIn()) return null;
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    return null;
}

// Connexion BDD
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        error_log("DB Connection Error: " . $e->getMessage());
        return null;
    }
}
?>