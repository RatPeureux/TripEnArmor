<?php

require dirname($_SERVER['DOCUMENT_ROOT']) . "/model/spectacle.php";

class SpectacleController {
    private $model;  

    function __construct() {
        $this->model = 'Spectacle';
    }

    public function getInfosSpectacle($id) {
        $spectacle = $this->model::getSpectacleById($id);

        $res = [
            "est_en_ligne" => $spectacle["est_en_ligne"],
            "description" => $spectacle["description"],
            "resume" => $spectacle["resume"],
            "prix_mini" => $spectacle["prix_mini"],
            "titre" => $spectacle["titre"],
            'id_pro'=> $spectacle['id_pro'],
            "id_type_offre" => $spectacle["id_type_offre"],
            "id_adresse" => $spectacle["id_adresse"],
            "capacite" => $spectacle["capacite"],
            "duree" => $spectacle["duree"]
        ];

        return $res;
    }

    public function createSpectacle($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $capacite, $duree) {
        $spectacle = $this->model::createActivite($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $capacite, $duree);

        return $spectacle;
    }
    
    public function updateSpectacle($id, $est_en_ligne, $description = false, $resume = false, $prix_mini = false, $titre = false, $id_pro = false, $id_type_offre = false, $id_adresse = false, $capacite = false, $avec_guide = false) {
        if ($description === false && $resume === false && $prix_mini === false && $titre === false && $id_pro === false && $id_type_offre === false && $id_adresse === false && $capacite === false && $avec_guide === false) {
            echo "ERREUR : Aucun champ à modifier";
            return -1;
        } else {
            $spectacle = $this->model::getSpectacleById($id);
            
            $res = $this->model::updateSpectacle(
                $id, 
                $est_en_ligne,
                $description !== false ? $description : $spectacle["description"], 
                $resume !== false ? $resume : $spectacle["resume"], 
                $prix_mini !== false ? $prix_mini : $spectacle["prix_mini"], 
                $titre !== false ? $titre : $spectacle["titre"], 
                $id_pro !== false ? $id_pro : $spectacle['id_pro'], 
                $id_type_offre !== false ? $id_type_offre : $spectacle["id_type_offre"], 
                $id_adresse !== false ? $id_adresse : $spectacle["id_adresse"],
                $capacite !== false ? $capacite : $spectacle["capacite"], 
                $avec_guide !== false ? $avec_guide : $spectacle["avec_guide"]
            );

            return $res;
        }
    }

    public function deleteSpectacle($id) {
        $spectacle = $this->model::deleteSpectacle($id);

        return $spectacle;
    }

    public function toggleOnline($id) {
        $spectacle = $this->model::getSpectacleById($id);
        
        $res = $this->model::updateSpectacle(
            $id,
            !$spectacle["est_en_ligne"],
            $spectacle["description"],
            $spectacle["resume"],
            $spectacle["prix_mini"],
            $spectacle["titre"],
            $spectacle['id_pro'],
            $spectacle["id_type_offre"],
            $spectacle["id_adresse"],
            $spectacle["capacite"],
            $spectacle["avec_guide"]
        );

        return $res;
    }
}