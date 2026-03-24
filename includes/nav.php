<nav>
    <ul class="nav-menu">
        <li><a href="index.php" <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : '' ?>>Accueil</a></li>
        <li><a href="formations.php" <?= basename($_SERVER['PHP_SELF']) == 'formations.php' ? 'class="active"' : '' ?>>Formations</a></li>
        <li><a href="equipe.php" <?= basename($_SERVER['PHP_SELF']) == 'equipe.php' ? 'class="active"' : '' ?>>Équipe</a></li>
        <li><a href="contact.php" <?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'class="active"' : '' ?>>Contact</a></li>
    </ul>
</nav>