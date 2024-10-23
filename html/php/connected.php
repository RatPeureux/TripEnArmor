<?php
session_start(); // Démarre la session pour utiliser les variables de session
ob_start(); // Active la mise en mémoire tampon de sortie

// Vérifie si l'utilisateur est connecté et si le token de session est valide
if (!isset($_SESSION['user_id']) || !isset($_GET['token']) || $_SESSION['token'] !== $_GET['token']) {
    // Si l'utilisateur n'est pas connecté ou si le token ne correspond pas, redirige vers la page d'accès refusé
    header('location: membre/access_denied.php');
    exit(); // Termine le script pour s'assurer que rien d'autre ne s'exécute après la redirection
}

// Assigne le pseudo de l'utilisateur à la variable $pseudo, ou 'Invité' s'il n'est pas défini
$pseudo = isset($_SESSION['user_pseudo']) ? $_SESSION['user_pseudo'] : 'Invité';
?>
