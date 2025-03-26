<?php
session_start();

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';

if (isConnectedAsPro()) {
    $pro = verifyPro();

    if ($pro['data']['type'] == 'prive') {
        include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_prive_controller.php';
        $controllerProPrive = new ProPriveController();
        $controllerProPrive->deleteProPrive($pro['id_compte']);
    } else {
        include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/pro_public_controller.php';
        $controllerProPublic = new ProPublicController();
        $controllerProPublic->deleteProPublic($pro['id_compte']);
    }
}

if (isConnectedAsMember()) {
    $membre = verifyMember();

    include_once dirname($_SERVER['DOCUMENT_ROOT']) . '/controller/membre_controller.php';
    $controllerMembre = new MembreController();
    $controllerMembre->deleteMembre($membre['id_compte']);
}

unset($_SESSION['id_membre']);
unset($_SESSION['id_pro']);

header('location: /');