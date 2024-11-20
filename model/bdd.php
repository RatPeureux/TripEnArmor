<?php
include('connect_params.php'); // Inclusion des paramètres de connexion à la base de données

// Définition d'une classe abstraite BDD qui sert de modèle pour les classes qui interagiront avec la base de données.
abstract class BDD {
    // Propriété pour stocker l'instance de la connexion à la base de données
    public $db;

    /**
     * Constructeur de la classe BDD
     * Initialise une connexion PDO à la base de données en utilisant les paramètres fournis
     */
    public function __construct() {
        // Récupération des paramètres de connexion globaux définis dans connect_params.php
        global $driver, $server, $port, $dbname, $user, $pass;

        // Création d'une nouvelle connexion PDO avec les paramètres de la base de données
        $dbh = new PDO("$driver:host=$server;port=$port;dbname=$dbname", $user, $pass);

        // Affectation de l'objet PDO à la propriété $db de la classe
        $this->db = $dbh;

        // Configuration de l'instance PDO pour lancer des exceptions en cas d'erreur SQL
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
