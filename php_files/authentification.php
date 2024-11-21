<?php
session_start();

function isConnectedAsMember(): bool
{
    return isset($_SESSION['id_member']);
}

function isConnectedAsPro(): bool {
    return isset($_SESSION['id_pro']);
}

function verifyPro()
{
    // Vérifie si l'utilisateur est connecté en tant que pro, sinon le renvoie à la page de connexion
    if (!isConnectedAsPro()) {
        header('location: /pro/connexion');
        exit();
    }
}

function verifyMember()
{
    // Vérifie si l'utilisateur est connecté en tant que membre, sinon le renvoie à la page de connexion
    if (!isset($_SESSION['id_member'])) {
        header('location: /connexion');
        exit();
    }
}
