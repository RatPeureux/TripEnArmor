<?php
// Vérifier que l'on est connecté (membre ou pro)
require dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
$id_compte = verifyMember()['id_compte'] ?? verifyPro()['id_compte'];

require dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
require dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use OTPHP\TOTP;

$totp = TOTP::generate();
$totp->setLabel('TripEnArmor');
$secret = $totp->getSecret();
$QrCodeUri = $totp->getQrCodeUri(
    'https://api.qrserver.com/v1/create-qr-code/?color=F2771B&bgcolor=FFFFFF&data=[DATA]&qzone=2&margin=0&size=300x300&ecc=M',
    '[DATA]'
);

// Inscrire les valeurs en BDD
$stmt = $dbh->prepare("
    UPDATE sae_db._compte
    SET uri_activation = :uri_activation
    WHERE id_compte = :id_compte
    AND uri_activation IS NULL
");
$stmt->bindParam(':uri_activation', $secret);
$stmt->bindParam(':id_compte', $id_compte);
if ($stmt->execute()) {
    // Retourner les valeurs (le script ne doit être appelé qu'une fois par compte !!!)
    $to_ret = [
        "secret" => $secret,
        "qr_code_uri" => $QrCodeUri
    ];
    echo json_encode($to_ret);
}
