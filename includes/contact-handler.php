<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $sujet = htmlspecialchars(trim($_POST['sujet']));
    $message = htmlspecialchars(trim($_POST['message']));
    $rgpd = isset($_POST['rgpd']);

    // Validation simple
    $errors = [];
    if (empty($nom)) $errors[] = "Le nom est requis.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if (empty($sujet)) $errors[] = "Le sujet est requis.";
    if (empty($message)) $errors[] = "Le message est requis.";
    if (!$rgpd) $errors[] = "Vous devez accepter le traitement des données.";

    if (empty($errors)) {
        // Simuler l'envoi (pas de vrai mail)
        // On pourrait enregistrer dans un fichier ou base de données
        // Redirection avec message de succès
        header('Location: ../contact.php?success=1');
        exit;
    } else {
        // Redirection avec erreurs
        $error_string = urlencode(implode(',', $errors));
        header('Location: ../contact.php?error=' . $error_string);
        exit;
    }
} else {
    header('Location: ../contact.php');
    exit;
}
?>