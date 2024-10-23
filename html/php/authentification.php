<?php
function activeLogout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Démarre la session pour utiliser les variables de session
    }

    // Vérifie si l'utilisateur est connecté et si le token de session est valide
    if (!isset($_SESSION['user_id']) || !isset($_GET['token']) || $_SESSION['token'] !== $_GET['token']) {
        // Si l'utilisateur n'est pas connecté ou si le token ne correspond pas
        return false;
    }
    // Sinon
    return true;
}

function verifyUserPro() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Démarre la session pour utiliser les variables de session
    }
    ob_start(); // Active la mise en mémoire tampon de sortie

    // Vérifie si l'utilisateur est connecté et si le token de session est valide
    if (!isset($_SESSION['user_id']) || !isset($_GET['token']) || $_SESSION['token'] !== $_GET['token']) {
        // Si l'utilisateur n'est pas connecté ou si le token ne correspond pas
        header('location: login-pro.php');
        exit(); // Termine le script pour s'assurer que rien d'autre ne s'exécute après la redirection
    }

    // Assigne le pseudo de l'utilisateur à la variable $pseudo, ou 'Invité' s'il n'est pas défini
    $pseudo = $_SESSION['user_pseudo'] ?? 'Invité';
}

function verifyUserMember() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Démarre la session pour utiliser les variables de session
    }
    ob_start(); // Active la mise en mémoire tampon de sortie

    // Vérifie si l'utilisateur est connecté et si le token de session est valide
    if (!isset($_SESSION['user_id']) || !isset($_GET['token']) || $_SESSION['token'] !== $_GET['token']) {
        // Si l'utilisateur n'est pas connecté ou si le token ne correspond pas
        header('location: login-membre.php');
        exit(); // Termine le script pour s'assurer que rien d'autre ne s'exécute après la redirection
    }

    // Assigne le pseudo de l'utilisateur à la variable $pseudo, ou 'Invité' s'il n'est pas défini
    $pseudo = $_SESSION['user_pseudo'] ?? 'Invité';
}
?>

