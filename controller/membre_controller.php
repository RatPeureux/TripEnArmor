<?php

require_once "../model/membre.php";

class MembreController {

    private $model;

    function __construct() {
        $this->model = 'Membre';
    }

    public function createMembre($email, $mdp, $tel, $adresseId, $pseudo, $prenom, $nom) {
        $membreID = $this->model::createCompte($email, $mdp, $tel, $adresseId, $pseudo, $prenom, $nom);
        return $membreID;
    }
    public function getInfosMembre($id){
        $membre = $this->model::getMembreById($id);

        $result = [
            "id_compte" => $membre["id_compte"],
            "email" => $membre["email"],
            "tel" => $membre["num_tel"],
            "adresse" => $membre["adresse_id"],
            "pseudo" => $membre["pseudo"],
            "prenom" => $membre["prenom"],
            "nom" => $membre["nom"]
        ];

        return $result;
    }

    public function updateMembre($id, $email = false, $mdp =false, $tel = false, $adresseId = false, $pseudo = false, $prenom = false, $nom = false) {  
        if ($email === false && $mdp === false && $tel === false && $adresseId === false && $pseudo === false && $prenom === false && $nom === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $membre = $this->model::getMembreById($id);
            
            $updatedMembreId = $this->model::updateMembre(
                $id, 
                $email !== false ? $email : $membre["email"], 
                $mdp !== false ? $mdp : $membre["mdp_hash"], 
                $tel !== false ? $tel : $membre["num_tel"], 
                $adresseId !== false ? $adresseId : $membre["adresse_id"]
            );
            return $updatedMembreId;
        }
    }
}