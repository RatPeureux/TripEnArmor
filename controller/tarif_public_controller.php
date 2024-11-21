<?php

require_once "../model/tarif_public.php";

class TarifPublicController {

    private $model;

    function __construct() {
        $this->model = 'TarifPublic';
    }

    public function getInfoTarifPublic($id){
        $tarifPublic = $this->model::getTarifPublicById($id);

        $result = [
            "id_tarif" => $tarifPublic["id_tarif"],
            "titre_tarif" => $tarifPublic["titre_tarif"],
            "age_min" => $tarifPublic["age_min"],
            "age_max" => $tarifPublic["age_max"],
            "prix" => $tarifPublic["prix"],
            "id_offre" => $tarifPublic["id_offre"],
        ];

        return $result;
    }

    public function createTarifPublic($titre_tarif, $age_min, $age_max, $prix, $id_offre) {
        $tarifPublicID = $this->model::createTarifPublic($titre_tarif, $age_min, $age_max, $prix, $id_offre);
        return $tarifPublicID;
    }

    public function updateTarifPublic($id, $titre_tarif = false, $age_min =false, $age_max = false, $prix = false, $id_offre = false) {
        if ($titre_tarif === false && $age_min === false && $age_max === false && $prix === false && $id_offre === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $tarifPublic = $this->model::getTarifPublicById($id);
            
            $updatedTarifPublicId = $this->model::createTarifPublic(
                $id, 
                $titre_tarif !== false ? $titre_tarif : $tarifPublic["titre_tarif"], 
                $age_min !== false ? $age_min : $tarifPublic["age_min"], 
                $age_max !== false ? $age_max : $tarifPublic["age_max"], 
                $prix !== false ? $prix : $tarifPublic["prix"],
                $id_offre !== false ? $id_offre : $tarifPublic["id_offre"]
            );
            return $updatedTarifPublicId;
        }
    }

}