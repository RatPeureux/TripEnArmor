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
        echo "Not connected as pro";
        header('location: /pro/connexion');
        exit();
    } else {
        echo "Connected as pro";
        require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/controller/pro_prive_controller.php";
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
        echo "Pro prive: '" . $pro . "'<br>";
        if (!$pro) {
            echo "Pro Prive not found";
            require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/controller/pro_public_controller.php";
            $proController = new ProPublicController();

            $pro = $proController->getInfosProPublic($_SESSION["id_pro"]);
            echo "Pro public : '" . $pro . "'<br>";
            $result["id_compte"] = $pro["id_compte"];
            $result["nom_pro"] = $pro["denomination"];
            $result["email"] = $pro["email"];
            $result["tel"] = $pro["tel"];
            $result["id_adresse"] = $pro["adresse"];
            $result["data"]["type_orga"] = $pro["type_orga"];
            $result["data"]["type"] = "public";

            if (!$pro) {
                echo "Pro Public not found";
                header('location: /pro/connexion');
                exit();
            }
        } else {
            $result["id_compte"] = $pro["id_compte"];
            $result["nom_pro"] = $pro["nom_pro"];
            $result["email"] = $pro["email"];
            $result["tel"] = $pro["tel"];
            $result["id_adresse"] = $pro["adresse"];
            $result["data"]["numero_siren"] = $pro["num_siren"];
            $result["data"]["type"] = "prive";
        }

        return $result;
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
