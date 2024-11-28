<?php

require dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

session_start();
function isConnectedAsMember(): bool
{
    return isset($_SESSION['id_membre']);
}

function isConnectedAsPro(): bool
{
    return isset($_SESSION['id_pro']);
}

function verifyPro()
{
    // Vérifie si l'utilisateur est connecté en tant que pro, sinon le renvoie à la page de connexion
    if (!isConnectedAsPro()) {
        header('location: /pro/connexion');
        exit();
    } else {
        require dirname($_SERVER["DOCUMENT_ROOT"]) . "/controller/pro_prive_controller.php";
        $proController = new ProPriveController();

        $pro = $proController->getInfosProPrive($_SESSION['id_pro']);
        if (!$pro) {
            require dirname($_SERVER["DOCUMENT_ROOT"]) . "/controller/pro_public_controller.php";
            $proController = new ProPublicController();

            $pro = $proController->getInfosProPublic($_SESSION["id_pro"]);
            if (!$pro) {
                header('location: /pro/connexion');
                exit();
            }
        }

        return $pro;
    }
}

function verifyMember()
{
    // Vérifie si l'utilisateur est connecté en tant que membre, sinon le renvoie à la page de connexion
    if (!isset($_SESSION['id_membre'])) {
        header('location: /connexion');
        exit();
    }
}
