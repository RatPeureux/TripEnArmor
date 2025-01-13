<?php
try {
    $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=nom_de_la_base;user=utilisateur;password=mot_de_passe');
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}