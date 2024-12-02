<?php
require(dirname($_SERVER['DOCUMENT_ROOT']) . '/php_files/connect_params.php'); // Inclusion des paramètres de connexion à la base de données

// Définition d'une classe abstraite BDD qui sert de modèle pour les classes qui interagiront avec la base de données.
abstract class BDD
{
    // Propriété pour stocker l'instance de la connexion à la base de données
    static public $db;
    static public $isInit = false;

    /**
     * Constructeur de la classe BDD
     * Initialise une connexion PDO à la base de données en utilisant les paramètres fournis
     */
    static public function initBDD()
    {
        if (self::$isInit === false) {
            // Récupération des paramètres de connexion globaux définis dans connect_params.php
            global $driver, $server, $port, $dbname, $user, $pass;

            // Création d'une nouvelle connexion PDO avec les paramètres de la base de données
            self::$db = new PDO("$driver:host=$server;port=$port;dbname=$dbname", $user, $pass);

            // Configuration de l'instance PDO pour lancer des exceptions en cas d'erreur SQL
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::makeConstraintsDeferrable();
            self::$isInit = true;
        }
    }

    static private function makeConstraintsDeferrable()
    {
        $sql = "DO $$ 
            DECLARE 
                r RECORD;
            BEGIN
                FOR r IN (
                    SELECT conname, conrelid::regclass
                    FROM pg_constraint
                    WHERE contype IN ('f', 'u', 'p') AND condeferrable = false
                ) LOOP
                    EXECUTE 'ALTER TABLE ' || r.conrelid || ' ALTER CONSTRAINT ' || r.conname || ' DEFERRABLE INITIALLY IMMEDIATE';
                END LOOP;
            END $$;
            ";
        self::$db->exec($sql);
    }

    static public function startTransaction()
    {
        self::$db->beginTransaction();
        $result = self::$db->exec('SET CONSTRAINTS ALL DEFERRED');
        echo "*** BEGIN SET CONSTRAINTS ALL DEFERRED ***<br>";
        echo "Result : $result<br>";
        echo "*** END SET CONSTRAINTS ALL DEFERRED ***<br>";
    }

    static public function commitTransaction()
    {
        self::$db->commit();
    }

    static public function rollbackTransaction()
    {
        self::$db->rollBack();
    }
}
