<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/spectacle.php";

class SpectacleController
{
    private $model;

    public function __construct()
    {
        $this->model = 'Spectacle';
    }

    public function getInfosSpectacle($id)
    {
        $spectacle = $this->model::getSpectacleById($id);

        $res = [
            "est_en_ligne" => $spectacle["est_en_ligne"],
            "description" => $spectacle["description"],
            "resume" => $spectacle["resume"],
            "prix_mini" => $spectacle["prix_mini"],
            "titre" => $spectacle["titre"],
            'id_pro' => $spectacle['id_pro'],
            "id_type_offre" => $spectacle["id_type_offre"],
            "id_adresse" => $spectacle["id_adresse"],
            "capacite" => $spectacle["capacite"],
            "duree" => $spectacle["duree"]
        ];

        $this->model::log("Les informations du spectacle $id ont été lues.");
        return $res;
    }

    public function createSpectacle($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $capacite, $duree)
    {
        $spectacle = $this->model::createSpectacle($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $capacite, $duree);

        $this->model::log("Un spectacle a été créé.");
        return $spectacle;
    }

    public function updateSpectacle($id, $est_en_ligne, $description = false, $resume = false, $prix_mini = false, $titre = false, $id_pro = false, $id_type_offre = false, $id_adresse = false, $capacite = false, $duree = false, $avec_guide = false)
    {
        if ($description === false && $resume === false && $prix_mini === false && $titre === false && $id_pro === false && $id_type_offre === false && $id_adresse === false && $capacite === false && $duree === false && $avec_guide === false) {
            $this->model::log("Aucune information n'a été modifiée.");
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
                $duree !== false ? $duree : $spectacle['duree'],
                $avec_guide !== false ? $avec_guide : $spectacle["avec_guide"]
            );

            $this->model::log("Les informations du spectacle $id ont été modifiées.");
            return $res;
        }
    }

    public function deleteSpectacle($id)
    {
        $spectacle = $this->model::deleteSpectacle($id);

        $this->model::log("Le spectacle $id a été supprimé.");
        return $spectacle;
    }

    public function toggleOnline($id)
    {
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

        $this->model::log("Les informations du spectacle $id ont été modifiées.");
        return $res;
    }
}