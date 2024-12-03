<?php

require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . "/model/visite.php";

class VisiteController
{
    private $model;

    function __construct()
    {
        $this->model = 'Visite';
    }

    public function getInfosVisite($id)
    {
        $visite = $this->model::getVisiteById($id);

        $res = [
            "est_en_ligne" => $visite["est_en_ligne"],
            "description" => $visite["description"],
            "resume" => $visite["resume"],
            "prix_mini" => $visite["prix_mini"],
            "titre" => $visite["titre"],
            'id_pro' => $visite['id_pro'],
            "id_type_offre" => $visite["id_type_offre"],
            "id_adresse" => $visite["id_adresse"],
            "duree" => $visite["duree"],
            "avec_guide" => $visite["avec_guide"]
        ];

        return $res;
    }

    public function createVisite($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $duree, $avec_guide)
    {
        echo "In Create Visite";
        echo "description : " . $description;
        echo "resume : " . $resume;
        echo "prix_mini : " . $prix_mini;
        echo "titre : " . $titre;
        echo "id_pro : " . $id_pro;
        echo "id_type_offre : " . $id_type_offre;
        echo "id_adresse : " . $id_adresse;
        echo "duree : " . $duree;
        echo "avec_guide : " . $avec_guide;
        $visite = $this->model::createVisite($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $duree, $avec_guide);
        return $visite;
    }

    public function updateVisite($id, $est_en_ligne, $description = false, $resume = false, $prix_mini = false, $titre = false, $id_pro = false, $id_type_offre = false, $id_adresse = false, $duree = false, $avec_guide = false)
    {
        if ($description === false && $resume === false && $prix_mini === false && $titre === false && $id_pro === false && $id_type_offre === false && $id_adresse === false && $duree === false && $avec_guide === false) {
            echo "ERREUR : Aucun champ à modifier";
            return -1;
        } else {
            $visite = $this->model::getVisiteById($id);

            $res = $this->model::updateVisite(
                $id,
                $est_en_ligne,
                $description !== false ? $description : $visite["description"],
                $resume !== false ? $resume : $visite["resume"],
                $prix_mini !== false ? $prix_mini : $visite["prix_mini"],
                $titre !== false ? $titre : $visite["titre"],
                $id_pro !== false ? $id_pro : $visite['id_pro'],
                $id_type_offre !== false ? $id_type_offre : $visite["id_type_offre"],
                $id_adresse !== false ? $id_adresse : $visite["id_adresse"],
                $duree !== false ? $duree : $visite["duree"],
                $avec_guide !== false ? $avec_guide : $visite["avec_guide"]
            );

            return $res;
        }
    }

    public function deleteVisite($id)
    {
        $visite = $this->model::deleteVisite($id);

        return $visite;
    }

    public function toggleOnline($id)
    {
        $visite = $this->model::getVisiteById($id);

        $res = $this->model::updateVisite(
            $id,
            !$visite["est_en_ligne"],
            $visite["description"],
            $visite["resume"],
            $visite["prix_mini"],
            $visite["titre"],
            $visite['id_pro'],
            $visite["id_type_offre"],
            $visite["id_adresse"],
            $visite["duree"],
            $visite["avec_guide"]
        );

        return $res;
    }
}