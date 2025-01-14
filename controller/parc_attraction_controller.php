<?php

require_once dirname(path: $_SERVER['DOCUMENT_ROOT']) . "/model/parc_attraction.php";

class ParcAttractionController
{
    private $model;

    function __construct()
    {
        $this->model = 'ParcAttraction';
    }

    public function getInfosParcAttraction($id)
    {
        $parc_attraction = $this->model::getParcAttractionById($id);

        $res = [
            "est_en_ligne" => $parc_attraction["est_en_ligne"],
            "description" => $parc_attraction["description"],
            "resume" => $parc_attraction["resume"],
            "prix_mini" => $parc_attraction["prix_mini"],
            "titre" => $parc_attraction["titre"],
            'id_pro' => $parc_attraction['id_pro'],
            "id_type_offre" => $parc_attraction["id_type_offre"],
            "id_adresse" => $parc_attraction["id_adresse"],
            "nb_attractions" => $parc_attraction["nb_attractions"],
            "age_requis" => $parc_attraction["age_requis"]
        ];

        $this->model::log("Les informations du parc d'attraction $id ont été lues.");
        return $res;
    }

    public function createParcAttraction($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $nb_attractions, $age_requis)
    {
        $parc_attraction = $this->model::createParcAttraction($description, $resume, $prix_mini, $titre, $id_pro, $id_type_offre, $id_adresse, $nb_attractions, $age_requis);

        $this->model::log("Un parc d'attraction a été créé.");
        return $parc_attraction;
    }

    public function updateParcAttraction($id, $est_en_ligne, $description = false, $resume = false, $prix_mini = false, $titre = false, $id_pro = false, $id_type_offre = false, $id_adresse = false, $nb_attractions = false, $age_requis = false)
    {
        if ($description === false && $resume === false && $prix_mini === false && $titre === false && $id_pro === false && $id_type_offre === false && $id_adresse === false && $nb_attractions === false && $age_requis === false) {
            $this->model::log("Aucune information n'a été modifiée.");
            return -1;
        } else {
            $parc_attraction = $this->model::getParcAttractionById($id);

            $res = $this->model::updateParcAttraction(
                $id,
                $est_en_ligne,
                $description !== false ? $description : $parc_attraction["description"],
                $resume !== false ? $resume : $parc_attraction["resume"],
                $prix_mini !== false ? $prix_mini : $parc_attraction["prix_mini"],
                $titre !== false ? $titre : $parc_attraction["titre"],
                $id_pro !== false ? $id_pro : $parc_attraction['id_pro'],
                $id_type_offre !== false ? $id_type_offre : $parc_attraction["id_type_offre"],
                $id_adresse !== false ? $id_adresse : $parc_attraction["id_adresse"],
                $nb_attractions !== false ? $nb_attractions : $parc_attraction["nb_attractions"],
                $age_requis !== false ? $age_requis : $parc_attraction["age_requis"]
            );

            $this->model::log("Les informations du parc d'attraction $id ont été modifiées.");
            return $res;
        }
    }

    public function deleteParcAttraction($id)
    {
        $parc_attraction = $this->model::deleteParcAttraction($id);

        $this->model::log("Le parc d'attraction $id a été supprimé.");
        return $parc_attraction;
    }

    public function toggleOnline($id)
    {
        $parc_attraction = $this->model::getParcAttractionById($id);

        $res = $this->model::updateParcAttraction(
            $id,
            !$parc_attraction["est_en_ligne"],
            $parc_attraction["description"],
            $parc_attraction["resume"],
            $parc_attraction["prix_mini"],
            $parc_attraction["titre"],
            $parc_attraction['id_pro'],
            $parc_attraction["id_type_offre"],
            $parc_attraction["id_adresse"],
            $parc_attraction["nb_attractions"],
            $parc_attraction["age_requis"]
        );

        $this->model::log("Le parc d'attraction $id a été mis en ligne.");
        return $res;
    }
}