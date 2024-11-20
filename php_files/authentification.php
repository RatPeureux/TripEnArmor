<?php

function activeLogout()
{
    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['id_user'])) {
        // Si l'utilisateur n'est pas connecté
        return false;
    }
    // Sinon
    return true;
}

function verifyPro()
{
    ob_start(); // Active la mise en mémoire tampon de sortie

    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['id_pro'])) {
        // Si l'utilisateur n'est pas connecté ou si le token ne correspond pas
        header('location: /pro/401'); // TODO: ajouter un lien vers la page de connexion
        exit(); // Termine le script pour s'assurer que rien d'autre ne s'exécute après la redirection
    }
}

function verifyMember()
{
    ob_start(); // Active la mise en mémoire tampon de sortie

    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['id_user'])) {
        // Si l'utilisateur n'est pas connect
        header('location: /401');
        exit(); // Termine le script pour s'assurer que rien d'autre ne s'exécute après la redirection
    }
}
