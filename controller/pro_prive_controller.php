<?php

require_once "../model/pro_prive.php";

class ProPriveController {

    private $model;

    function __construct() {
        $this->model = 'ProPrive';
    }

    public function createProPrive($email, $mdp, $tel, $adresseId, $nom_pro, $num_siren) {
        $proPriveID = $this->model::createProPublic($email, $mdp, $tel, $adresseId, $nom_pro, $num_siren);
        return $proPriveID;
    }

    public function getInfosProPrive($id){
        $proPrive = $this->model::getProPriveById($id);

        $result = [
            "id_compte" => $proPrive["id_compte"],
            "email" => $proPrive["email"],
            "tel" => $proPrive["num_tel"],
            "adresse" => $proPrive["id_adresse"],
            "nom_pro" => $proPrive["nom_pro"],
            "num_siren" => $proPrive["num_siren"],
        ];

        return $result;
    }

    public function updateProPrive($id, $email = false, $mdp =false, $tel = false, $adresseId = false, $nom_pro = false, $num_siren = false) {  
        if ($email === false && $mdp === false && $tel === false && $adresseId === false && $nom_pro === false && $num_siren === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $proPrive = $this->model::getProPriveById($id);
            
            $updatedProPriveId = $this->model::updateProPrive(
                $id, 
                $email !== false ? $email : $proPrive["email"], 
                $mdp !== false ? $mdp : $proPrive["mdp_hash"], 
                $tel !== false ? $tel : $proPrive["num_tel"], 
                $adresseId !== false ? $adresseId : $proPrive["id_adresse"]
            );
            return $updatedProPriveId;
        }
    }
}