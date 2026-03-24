<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?></title>
    <link rel="icon" href="assets/images/logo/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/images/logo/efrei-logo.png" alt="EFREI" class="logo-light">
                    <img src="assets/images/logo/efrei-logo-white.png" alt="EFREI" class="logo-dark">
                </a>
            </div>
            <div class="site-title"><?= SITE_NAME ?></div>
            <button id="dark-mode-toggle" class="dark-mode-btn">🌙</button>
            <?php require_once 'nav.php'; ?>
        </div>
    </header>
    <main>