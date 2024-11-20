<?php
include('connect_params.php');

abstract class BDD {
    public $db;

    public function __construct() {
        global $driver, $server, $port, $dbname, $user, $pass;
        $dbh = new PDO("$driver:host=$server;port=$port;dbname=$dbname", $user, $pass);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}