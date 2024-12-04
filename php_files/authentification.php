<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';
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
        header('location: /401');
        exit();
    } else {
        require_once dirname(path: $_SERVER["DOCUMENT_ROOT"]) . "/controller/pro_prive_controller.php";
        $result = [
            "id_compte" => "",
            "nom_pro" => "",
            "email" => "",
            "tel" => "",
            "id_adresse" => "",
            "data" => [
            ]
        ];
        $proController = new ProPriveController();

        $pro = $proController->getInfosProPrive($_SESSION['id_pro']);
        if (!$pro) {
            require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/controller/pro_public_controller.php";
            $proController = new ProPublicController();

            $pro = $proController->getInfosProPublic($_SESSION["id_pro"]);
            $result["id_compte"] = $pro["id_compte"];
            $result["nom_pro"] = $pro["nom_pro"];
            $result["email"] = $pro["email"];
            $result["tel"] = $pro["num_tel"];
            $result["id_adresse"] = $pro["id_adresse"];
            $result["data"]["type_orga"] = $pro["type_orga"];
            $result["data"]["type"] = "public";

            if (!$pro) {
                header('location: /pro/connexion');
                exit();
            }
        } else {
            $result["id_compte"] = $pro["id_compte"];
            $result["nom_pro"] = $pro["nom_pro"];
            $result["email"] = $pro["email"];
            $result["tel"] = $pro["tel"];
            $result["id_adresse"] = $pro["id_adresse"];
            $result["data"]["numero_siren"] = $pro["num_siren"];
            $result["data"]["id_rib"] = $pro["id_rib"];
            $result["data"]["type"] = "prive";
        }

        return $result;
    }
}

function verifyMember()
{
    // Vérifie si l'utilisateur est connecté en tant que membre, sinon le renvoie à la page de connexion
    if (!isset($_SESSION['id_membre'])) {
        header('location: /401');
        exit();
    } else {
        require_once dirname(path: $_SERVER["DOCUMENT_ROOT"]) . "/controller/membre_controller.php";
        $result = [
            "id_compte" => "",
            "pseudo" => "",
            "nom" => "",
            "prenom" => "",
            "email" => "",
            "tel" => "",
            "id_adresse" => "",
        ];
        $membreController = new MembreController();
        $membre = $membreController->getInfosMembre($_SESSION['id_membre']);

        $result["id_compte"] = $membre["id_compte"];
        $result["pseudo"] = $membre["pseudo"];
        $result["nom"] = $membre["nom"];
        $result["prenom"] = $membre["prenom"];
        $result["email"] = $membre["email"];
        $result["tel"] = $membre["num_tel"];
        $result["id_adresse"] = $membre["id_adresse"];

        return $result;
    }
}
