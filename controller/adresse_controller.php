<?php

require dirname($_SERVER['DOCUMENT_ROOT']) . "/model/adresse.php";

class AdresseController {
    private $model;  

    function __construct() {
        $this->model = 'Adresse';
    }

    public function getInfosAdresse($id) {
        $adresse = $this->model::getAdresseById($id);

        $res = [
            "code_postal" => $adresse["code_postal"],
            "ville" => $adresse["ville"],
            "numero" => $adresse["numero"],
            "odonyme" => $adresse["odonyme"],
            "complement" => $adresse["complement"]
        ];

        return $res;
    }

    public function createAdresse($code_postal, $ville, $numero, $odonyme, $complement) {
        $adresse = $this->model::createActivite($code_postal, $ville, $numero, $odonyme, $complement);

        return $adresse;
    }
    
    public function updateAdresse($id, $code_postal = false, $ville = false, $numero = false, $odonyme = false, $complement = false) {
        if ($ville === false && $numero === false && $odonyme === false && $complement === false ) {
            echo "ERREUR : Aucun champ à modifier";
            return -1;
        } else {
            $adresse = $this->model::getAdresseById($id);
            
            $res = $this->model::updateAdresse(
                $id, 
                $code_postal !== false ? $code_postal : $adresse["code_postal"], 
                $ville !== false ? $ville : $adresse["ville"], 
                $numero !== false ? $numero : $adresse["numero"], 
                $odonyme !== false ? $odonyme : $adresse["odonyme"], 
                $complement !== false ? $complement : $adresse["complement"]
            );

            return $res;
        }
    }

    public function deleteAdresse($id) {
        $adresse = $this->model::deleteAdresse($id);

        return $adresse;
    }
}