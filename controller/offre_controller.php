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
            "id_offre" => $offre["id"],
            "titre" => $offre["titre"],
            "resume" => $offre["resume"],
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
    public function createOffre($titre, $description, $resume, $prix_mini, $id_pro, $type_offre_id, $adresse_id) {
        if (!is_string($titre)) {
            echo "ERREUR: Le titre doit être une chaîne de caractères";
            return -1;
        } else if (!is_string($description)) {
            echo "ERREUR: La description doit être une chaîne de caractères";
            return -1;
        } else if (!is_string($resume)) {
            echo "ERREUR: Le résumé doit être une chaîne de caractères";
            return -1;
        } else if (!is_float($prix_mini)) {
            echo "ERREUR: Le prix mini doit être un nombre";
            return -1;
        } else if (!is_numeric($id_pro)) {
            echo "ERREUR: L'ID du professionnel doit être un nombre";
            return -1;
        } else if (!is_numeric($type_offre_id)) {
            echo "ERREUR: L'ID du type d'offre doit être un nombre";
            return -1;
        } else if (!is_numeric($adresse_id)) {
            echo "ERREUR: L'ID de l'adresse doit être un nombre";
            return -1;
        }

        $offreID = $this->model::createOffre($titre, $description, $resume, $prix_mini, $id_pro, $type_offre_id, $adresse_id);
        return $offreID;
    }
    
    public function updateOffre($id, $titre=false, $description=false, $resume=false, $prix_mini=false, $id_pro=false, $type_offre_id=false, $adresse_id=false) {
        if ($titre === false && $description === false && $resume === false && $prix_mini === false && $id_pro === false && $type_offre_id === false && $adresse_id === false) {
            echo "ERREUR: Aucun champ à modifier";
            return -1;
        } else {
            if ($titre !== false && !is_string($titre)) {
                echo "ERREUR: Le titre doit être une chaîne de caractères";
                return -1;
            } else if ($description !== false && !is_string($description)) {
                echo "ERREUR: La description doit être une chaîne de caractères";
                return -1;
            } else if ($resume !== false && !is_string($resume)) {
                echo "ERREUR: Le résumé doit être une chaîne de caractères";
                return -1;
            } else if ($prix_mini !== false && !is_float($prix_mini)) {
                echo "ERREUR: Le prix mini doit être un nombre";
                return -1;
            } else if ($id_pro !== false && !is_numeric($id_pro)) {
                echo "ERREUR: L'ID du professionnel doit être un nombre";
                return -1;
            } else if ($type_offre_id !== false && !is_numeric($type_offre_id)) {
                echo "ERREUR: L'ID du type d'offre doit être un nombre";
                return -1;
            } else if ($adresse_id !== false && !is_numeric($adresse_id)) {
                echo "ERREUR: L'ID de l'adresse doit être un nombre";
                return -1;
            }

            $offre = $this->model::getOffreById($id);
            
            $updatedOffreId = $this->model::updateOffre(
                $id, 
                $titre !== false ? $titre : $offre["titre"], 
                $description !== false ? $description : $offre["description"], 
                $resume !== false ? $resume : $offre["resume"], 
                $prix_mini !== false ? $prix_mini : $offre["prix_mini"], 
                $id_pro !== false ? $id_pro : $offre["id_pro"], 
                $type_offre_id !== false ? $type_offre_id : $offre["type_offre_id"], 
                $adresse_id !== false ? $adresse_id : $offre["adresse_id"]
            );

            return $updatedOffreId;
        }
    }
}