<?php
// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Vérifier si l'utilisateur est enseignant
function isTeacher() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'enseignant';
}

// Vérifier si l'utilisateur est étudiant
function isStudent() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant';
}

// Rediriger si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Rediriger si pas enseignant
function requireTeacher() {
    requireLogin();
    if (!isTeacher()) {
        header('Location: index.php');
        exit;
    }
}

// Rediriger si pas étudiant
function requireStudent() {
    requireLogin();
    if (!isStudent()) {
        header('Location: index.php');
        exit;
    }
}

// Récupérer l'utilisateur connecté
function getCurrentUser($pdo) {
    if (!isLoggedIn()) return null;
    
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
?>