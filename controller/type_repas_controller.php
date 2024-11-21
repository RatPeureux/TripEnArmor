<?php

require_once "../model/type_repas.php";

class TypeRepasController {

    private $model;

    function __construct() {
        $this->model = 'TypeRepas';
    }

    public function getInfoTypeRepas($id){
        $typeRepas = $this->model::getTypeRepasById($id);

        $result = [
            "type_repas_id" => $typeRepas["type_repas_id"],
            "nom_type_repas" => $typeRepas["nom_type_repas"],
        ];

        return $result;
    }

    public function createTypeRepas($nom_type_repas) {
        $typeRepasID = $this->model::createTypeRepas($nom_type_repas);
        return $typeRepasID;
    }

    public function updateTypeRepas($id, $nom_type_repas = false) {
        if ($nom_type_repas === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $typeRepas = $this->model::getTypeRepasById($id);
            
            $updatedTypeRepasId = $this->model::updateTypeRepas(
                $id, 
                $nom_type_repas !== false ? $nom_type_repas : $typeRepas["nom_type_repas"]
            );
            return $updatedTypeRepasId;
        }
    }
}