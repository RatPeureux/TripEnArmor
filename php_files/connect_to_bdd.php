<?php
require dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php';

try {
    $dbh = new PDO("$driver:host=$server;port=$port;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    die();
}
