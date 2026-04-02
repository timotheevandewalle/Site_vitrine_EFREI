<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header('Location: ../login.php?error=1');
    exit;
}

// Connexion à la base de données
$pdo = getDBConnection();

// Si la BDD n'est pas disponible, on simule une connexion pour les tests
if (!$pdo) {
    // Mode test : on accepte n'importe quel identifiant
    $_SESSION['user_id'] = 1;
    $_SESSION['user_nom'] = 'Test';
    $_SESSION['user_prenom'] = 'Utilisateur';
    $_SESSION['user_role'] = 'etudiant';
    $_SESSION['user_email'] = $email;
    header('Location: ../dashboard.php');
    exit;
}

// Vérifier les identifiants
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['mot_de_passe'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nom'] = $user['nom'];
    $_SESSION['user_prenom'] = $user['prenom'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_email'] = $user['email'];
    header('Location: ../dashboard.php');
    exit;
} else {
    header('Location: ../login.php?error=1');
    exit;
}
?>