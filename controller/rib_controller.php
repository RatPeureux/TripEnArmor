<?php

require_once "../model/rib.php";

class RibController {
    static private $model;  

    function __construct() {
        $this->model = 'Rib';
    }

    public function getInfosRib($id) {
        $rib = $this->model::getRibById($id);

        $res = [
            "code_banque" => $rib["code_banque"],
            "code_guichet" => $rib["code_guichet"],
            "numero_compte" => $rib["numero_compte"],
            "cle_rib" => $rib["cle_rib"],
            "id_compte" => $rib["id_compte"]
        ];

        return $res;
    }

    public function createRib($code_banque, $code_guichet, $numero_compte, $cle_rib, $id_compte) {
        $rib = $this->model::createActivite($code_banque, $code_guichet, $numero_compte, $cle_rib, $id_compte);

        return $rib;
    }
    
    public function updateRib($id, $code_banque = false, $code_guichet = false, $numero_compte = false, $cle_rib = false, $id_compte = false) {
        if ($code_guichet === false && $numero_compte === false && $cle_rib === false && $id_compte === false ) {
            echo "ERREUR : Aucun champ à modifier";
            return -1;
        } else {
            $rib = $this->model::getRibById($id);
            
            $res = $this->model::updateRib(
                $id, 
                $code_banque !== false ? $code_banque : $rib["code_banque"], 
                $code_guichet !== false ? $code_guichet : $rib["code_guichet"], 
                $numero_compte !== false ? $numero_compte : $rib["numero_compte"], 
                $cle_rib !== false ? $cle_rib : $rib["cle_rib"], 
                $id_compte !== false ? $id_compte : $rib["id_compte"]
            );

            return $res;
        }
    }

    public function deleteRib($id) {
        $rib = $this->model::deleteRib($id);

        return $rib;
    }
}