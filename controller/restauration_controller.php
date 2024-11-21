<?php

require_once "../model/restauration.php";

class RestaurationController {
    static private $model;  

    function __construct() {
        $this->model = 'Restauration';
    }

    public function getInfosRestauration($id) {
        $restauration = $this->model::getRestaurationById($id);

        $res = [
            "est_en_ligne" => $activite["est_en_ligne"],
            "description" => $activite["description"],
            "resume" => $activite["resume"],
            "prix_mini" => $activite["prix_mini"],
            "titre" => $activite["titre"],
            "id_pro"=> $activite["id_pro"],
            "id_type_offre" => $activite["id_type_offre"],
            "id_adresse" => $activite["id_adresse"],
            "duree" => $activite["duree"],
            "age_requis" => $activite["age_requis"],
            "prestations" => $activite["prestations"]
        ];

        return $res;
    }

    public function createRestauration($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $duree, $age_requis, $prestations) {
        $restauration = $this->model::createRestauration($titre, $description, $resume, $prix_mini, $id_pro, $type_offre_id, $adresse_id);

        return $restauration;
    }
    
    public function updateRestauration($id, $est_en_ligne, $description = false, $resume = false, $prix_mini = false, $titre = false, $id_pro = false, $id_type_offre = false, $id_adresse = false, $duree = false, $age_requis = false, $prestations = false) {
        if ($description === false && $resume === false && $prix_mini === false && $titre === false && $id_pro === false && $id_type_offre === false && $id_adresse === false && $duree === false && $age_requis === false && $prestations === false) {
            echo "ERREUR : Aucun champ à modifier";
            return -1;
        } else {
            $restauration = $this->model::getRestaurationById($id);
            
            $res = $this->model::updateRestauration(
                $id, 
                $est_en_ligne,
                $description !== false ? $description : $restauration["description"], 
                $resume !== false ? $resume : $restauration["resume"], 
                $prix_mini !== false ? $prix_mini : $restauration["prix_mini"], 
                $titre !== false ? $titre : $restauration["titre"], 
                $id_pro !== false ? $id_pro : $restauration["id_pro"], 
                $id_type_offre !== false ? $type_offre_id : $restauration["id_type_offre"], 
                $id_adresse !== false ? $adresse_id : $restauration["adresse_id"],
                $duree !== false ? $duree : $restauration["duree"], 
                $age_requis !== false ? $age_requis : $restauration["age_requis"], 
                $prestations !== false ? $prestations : $restauration["prestations"]
            );

            return $res;
        }
    }

    public function deleteRestauration($id) {
        $restauration = $this->model::deleteRestauration($id);

        return $restauration;
    }

    public function toggleOnline($id) {
        $restauration = $this->model::getRestaurationById($id);
        
        $res = $this->model::updateRestauration(
            $id,
            !$restauration["est_en_ligne"],
            $restauration["description"],
            $restauration["resume"],
            $restauration["prix_mini"],
            $restauration["titre"],
            $restauration["id_pro"],
            $restauration["id_type_offre"],
            $restauration["id_adresse"],
            $restauration["duree"],
            $restauration["age_requis"],
            $restauration["prestations"]
        );

        return $res;
    }
}