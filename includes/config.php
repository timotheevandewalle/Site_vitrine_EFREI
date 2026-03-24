<?php
// Configuration générale du site
define('SITE_NAME', 'Département Informatique EFREI');
define('CONTACT_EMAIL', 'info.informatique@efrei.fr');

// Activation de l'affichage des erreurs en développement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fuseau horaire
date_default_timezone_set('Europe/Paris');
?>