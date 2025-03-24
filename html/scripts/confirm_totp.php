<?php
// Vérifier que l'on est connecté (membre ou pro)
require dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/authentification.php';
[$id_compte, $role_compte] = (isConnectedAsMember()) ? [verifyMember()['id_compte'], 'Membre'] : [verifyPro()['id_compte'], 'Profesionnel'];

require dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_to_bdd.php';
require dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

// Avons-nous reçu une valeur pour le code secret ?
if (isset($_GET['secret']) && $_GET['secret']) {
    $secret = $_GET['secret'];
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Erreur : Aucune donnée reçue.']);
}

// Le code secret est-il le bon ?
$stmt = $dbh->prepare("
        SELECT * FROM sae_db._compte
        WHERE secret_totp = :secret_totp
    ");
$stmt->bindParam(':secret_totp', $secret);
$stmt->execute();
if ($stmt->rowCount() == 0) {
    http_response_code(500);
    echo json_encode(['rowCount'=> $stmt->rowCount()]);
    echo json_encode(['message' => 'Erreur : Le code reçu en base de données n\'est pas le bon']);
}

try {
    // Inscrire les valeurs en BDD
    $stmt = $dbh->prepare("
        UPDATE sae_db._compte
        SET totp_active = TRUE
        WHERE id_compte = :id_compte
    ");
    $stmt->bindParam(':id_compte', $id_compte);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'L\'option TOTP est activée avec succès !']);
    }
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
