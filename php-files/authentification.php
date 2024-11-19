<?php
function activeLogout()
{
    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        // Si l'utilisateur n'est pas connecté
        return false;
    }
    // Sinon
    return true;
}

function verifyUserPro()
{
    ob_start(); // Active la mise en mémoire tampon de sortie

    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['id_pro'])) {
        // Si l'utilisateur n'est pas connecté ou si le token ne correspond pas
        header('location: /pro/connexion');
        exit(); // Termine le script pour s'assurer que rien d'autre ne s'exécute après la redirection
    }
}

function verifyUserMember()
{
    ob_start(); // Active la mise en mémoire tampon de sortie

    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        // Si l'utilisateur n'est pas connecté ou si le token ne correspond pas
        header('location: /connexion');
        exit(); // Termine le script pour s'assurer que rien d'autre ne s'exécute après la redirection
    }
}

