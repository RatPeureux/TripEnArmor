<?php
// Vérifier que l'on est connecté (membre ou pro)
require dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
require dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
[$id_compte, $role_compte] = (isConnectedAsMember()) ? [verifyMember()['id_compte'], 'Membre'] : [verifyPro()['id_compte'], 'Profesionnel'];

// Gérer l'OTP
require dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
use OTPHP\TOTP;

$totp = TOTP::generate();
$totp->setLabel('TripEnArmor' . $role_compte);
$secret = $totp->getSecret();
$QrCodeUri = $totp->getQrCodeUri(
    'https://api.qrserver.com/v1/create-qr-code/?color=F2771B&bgcolor=FFFFFF&data=[DATA]&qzone=2&margin=0&size=300x300&ecc=M',
    '[DATA]'
);

// Inscrire les valeurs en BDD
$stmt = $dbh->prepare("
    UPDATE sae_db._compte
    SET secret_totp = :secret_totp
    WHERE id_compte = :id_compte
    AND totp_active = FALSE
");
$stmt->bindParam(':secret_totp', $secret);
$stmt->bindParam(':id_compte', $id_compte);
if ($stmt->execute()) {
    // Retourner les valeurs (le script ne doit être appelé qu'une fois par compte !!!)
    $to_ret = [
        "secret" => $secret,
        "qr_code_uri" => $QrCodeUri
    ];
    echo json_encode($to_ret);
}
