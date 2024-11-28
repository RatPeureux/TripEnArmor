<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/activite.php";

class ActiviteController {
    private $model;  

    function __construct() {
        $this->model = 'Activite';
    }

    public function getAllActivite() {
        $activite = $this->model::getAllActivite();

        return $activite;
    }

    public function getInfosActivite($id) {
        $activite = $this->model::getActiviteById($id);

        $res = [
            "est_en_ligne" => $activite["est_en_ligne"],
            "description" => $activite["description"],
            "resume" => $activite["resume"],
            "prix_mini" => $activite["prix_mini"],
            "titre" => $activite["titre"],
            'id_pro'=> $activite['id_pro'],
            "id_type_offre" => $activite["id_type_offre"],
            "id_adresse" => $activite["id_adresse"],
            "duree" => $activite["duree"],
            "age_requis" => $activite["age_requis"],
            "prestations" => $activite["prestations"]
        ];

        return $res;
    }

    public function createActivite($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $duree, $age_requis, $prestations) {
        $activite = $this->model::createActivite($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $duree, $age_requis, $prestations);

        return $activite;
    }
    
    public function updateActivite($id, $est_en_ligne, $description = false, $resume = false, $prix_mini = false, $titre = false, $id_pro = false, $id_type_offre = false, $id_adresse = false, $duree = false, $age_requis = false, $prestations = false) {
        if ($description === false && $resume === false && $prix_mini === false && $titre === false && $id_pro === false && $id_type_offre === false && $id_adresse === false && $duree === false && $age_requis === false && $prestations === false) {
            echo "ERREUR : Aucun champ à modifier";
            return -1;
        } else {
            $activite = $this->model::getActiviteById($id);
            
            $res = $this->model::updateActivite(
                $id, 
                $est_en_ligne,
                $description !== false ? $description : $activite["description"], 
                $resume !== false ? $resume : $activite["resume"], 
                $prix_mini !== false ? $prix_mini : $activite["prix_mini"], 
                $titre !== false ? $titre : $activite["titre"], 
                $id_pro !== false ? $id_pro : $activite['id_pro'], 
                $id_type_offre !== false ? $id_type_offre : $activite["id_type_offre"], 
                $id_adresse !== false ? $id_adresse : $activite["id_adresse"],
                $duree !== false ? $duree : $activite["duree"], 
                $age_requis !== false ? $age_requis : $activite["age_requis"], 
                $prestations !== false ? $prestations : $activite["prestations"]
            );

            return $res;
        }
    }

    public function deleteActivite($id) {
        $activite = $this->model::deleteActivite($id);

        return $activite;
    }

    public function toggleOnline($id) {
        $activite = $this->model::getActiviteById($id);
        
        $res = $this->model::updateActivite(
            $id,
            !$activite["est_en_ligne"],
            $activite["description"],
            $activite["resume"],
            $activite["prix_mini"],
            $activite["titre"],
            $activite['id_pro'],
            $activite["id_type_offre"],
            $activite["id_adresse"],
            $activite["duree"],
            $activite["age_requis"],
            $activite["prestations"]
        );

        return $res;
    }
}