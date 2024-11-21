<?php

require_once "../model/offre.php";

class OffreController {
    static private $model;  

    function __construct() {
        $this->model = 'Offre';
    }

    // DATA: MODEL -> VIEW
    public function getInfosCarte($id) {
        $offre = $this->model::getOffreById($id);

        $result = [
            "offre_id" => $offre["offre_id"],
            "titre" => $offre["titre"],
            "resume" => $offre["resume_offre"],
            "prix_mini" => $offre["prix_mini"],
            "id_pro" => $offre["id_pro"],
            "adresse_id"=> $offre["adresse_id"],
            "type_offre_id" => $offre["type_offre_id"],
        ];

        return $result;
    }

    public function getInfosDetails($id) {
        $offre = $this->model::getOffreById($id);

        $result = [
            "id_offre" => $offre["id"],
            "titre" => $offre["titre"],
            "description" => $offre["description"],
            "id_pro" => $offre["id_pro"],
            "id_adresse"=> $offre["id_adresse"],
            "id_type_offre" => $offre["id_type_offre"],
        ];

        return $result;
    }

    // VIEW -> MODEL
    public function createOffre($titre, $description, $resume, $prix_mini, $id_pro, $type_offre_id, $adresse_id) {
        $offreID = $this->model::createOffre($titre, $description, $resume, $prix_mini, $id_pro, $type_offre_id, $adresse_id);
        return $offreID;
    }
    
    public function updateOffre($id, $titre=false, $description=false, $resume=false, $prix_mini=false, $id_pro=false, $type_offre_id=false, $adresse_id=false) {
        if ($titre === false && $description === false && $resume === false && $prix_mini === false && $id_pro === false && $type_offre_id === false && $adresse_id === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $offre = $this->model::getOffreById($id);
            
            $updatedOffreId = $this->model::updateOffre(
                $id, 
                $titre !== false ? $titre : $offre["titre"], 
                $description !== false ? $description : $offre["description"], 
                $resume !== false ? $resume : $offre["resume"], 
                $prix_mini !== false ? $prix_mini : $offre["prix_mini"], 
                $id_pro !== false ? $id_pro : $offre["id_pro"], 
                $type_offre_id !== false ? $type_offre_id : $offre["id_type_offre"], 
                $adresse_id !== false ? $adresse_id : $offre["adresse_id"],
                $offre["enLigne"]
            );

            return $updatedOffreId;
        }
    }

    public function toggleEnLigne($id) {
        $offre = $this->model::getOffreById($id);
        
        $updatedOffreId = $this->model::updateOffre(
            $id, 
            $offre["titre"], 
            $offre["description"], 
            $offre["resume"], 
            $offre["prix_mini"], 
            $offre["id_pro"], 
            $offre["id_type_offre"], 
            $offre["adresse_id"],
            !$offre["enLigne"],
        );

        return $updatedOffreId;
    }
}