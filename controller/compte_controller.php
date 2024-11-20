<?php

require_once "../model/compte.php";

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
            "adresse" => $compte["adresse_id"],
        ];

        return $result;
    }

    public function createCompte($email, $mdp, $tel, $adresseId) {
        $compteID = $this->model::createCompte($email, $mdp, $tel, $adresseId);
        return $compteID;
    }

    public function updateCompte($id, $email = false, $mdp =false, $tel = false, $adresseId = false) {
        if ($email === false && $mdp === false && $tel === false && $adresseId === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $compte = $this->model::getCompteById($id);
            
            $updatedCompteId = $this->model::updateCompte(
                $id, 
                $email !== false ? $email : $compte["email"], 
                $mdp !== false ? $mdp : $compte["mdp_hash"], 
                $tel !== false ? $tel : $compte["num_tel"], 
                $adresseId !== false ? $adresseId : $compte["adresse_id"]
            );
            return $updatedCompteId;
        }
    }
}