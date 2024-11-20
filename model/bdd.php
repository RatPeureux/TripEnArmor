<?php
include('connect_params.php');

abstract class BDD {
    static public $db;
    static public $isInit = false;

    static function initBDD() {
        /*
        Vérifie si la connexion à la BDD est établie, se connecte si ce n'est pas le cas.
        */
        if (self::$isInit === false) {
            global $driver, $server, $port, $dbname, $user, $pass;
            $dbh = new PDO("$driver:host=$server;port=$port;dbname=$dbname", $user, $pass);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$isInit = true;
        } else {
            echo "ERREUR: Connexion déjà établie";
        }
    }
}