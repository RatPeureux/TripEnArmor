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
            "id_offre" => $offre["id_offre"],
            "titre" => $offre["titre"],
            "resume" => $offre["resume_offre"],
            "prix_mini" => $offre["prix_mini"],
            "id_pro" => $offre["id_pro"],
            "id_adresse"=> $offre["id_adresse"],
            "id_type_offre" => $offre["id_type_offre"],
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
    public function createOffre($titre, $description, $resume, $prix_mini, $id_pro, $id_type_offre, $id_adresse) {
        $id_offre = $this->model::createOffre($titre, $description, $resume, $prix_mini, $id_pro, $id_type_offre, $id_adresse);
        return $id_offre;
    }
    
    public function updateOffre($id, $titre=false, $description=false, $resume=false, $prix_mini=false, $id_pro=false, $id_type_offre=false, $id_adresse=false) {
        if ($titre === false && $description === false && $resume === false && $prix_mini === false && $id_pro === false && $id_type_offre === false && $id_adresse === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            $offre = $this->model::getOffreById($id);
            
            $updatedid_offre = $this->model::updateOffre(
                $id, 
                $titre !== false ? $titre : $offre["titre"], 
                $description !== false ? $description : $offre["description"], 
                $resume !== false ? $resume : $offre["resume"], 
                $prix_mini !== false ? $prix_mini : $offre["prix_mini"], 
                $id_pro !== false ? $id_pro : $offre["id_pro"], 
                $id_type_offre !== false ? $id_type_offre : $offre["id_type_offre"], 
                $id_adresse !== false ? $id_adresse : $offre["id_adresse"]
            );

            return $updatedid_offre;
        }
    }
}