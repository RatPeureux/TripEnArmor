<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/../model/compte.php";

class CompteController {

    private $model;

    function __construct() {
        $this->model = 'Compte';
    }

    public function getInfoCompte($id){
        $compte = $this->model::getCompteById($id);

        $result = [
            "id_compte" => $compte["id_compte"],
            "email" => $compte["email"],
            "tel" => $compte["num_tel"],
            "adresse" => $compte["id_adresse"],
        ];

        return $result;
    }

    public function createCompte($email, $mdp, $tel, $id_adresse) {
        $id_compte = $this->model::createCompte($email, $mdp, $tel, $id_adresse);
        return $id_compte;
    }

    public function updateCompte($id, $email = false, $mdp =false, $tel = false, $id_adresse = false) {
        if ($email === false && $mdp === false && $tel === false && $id_adresse === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $compte = $this->model::getCompteById($id);
            
            $updatedid_compte = $this->model::updateCompte(
                $id, 
                $email !== false ? $email : $compte["email"], 
                $mdp !== false ? $mdp : $compte["mdp_hash"], 
                $tel !== false ? $tel : $compte["num_tel"], 
                $id_adresse !== false ? $id_adresse : $compte["id_adresse"]
            );
            return $updatedid_compte;
        }
    }
}