<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/modifier_offre.php";

class ModifierOffreController
{

    private $model;

    function __construct()
    {
        $this->model = 'ModifierOffre';
    }

    public function getOffreById($id)
    {
        $offre = $this->model::getOffreById($id);
        $this->model::log("Les informations de l'offre $id ont été lues.");
        return $offre;
    }

    public function updateOffre($id, $titre, $description, $prix_mini, $prix_max, $id_type_offre, $id_pro)
    {
        $updatedOffreId = $this->model::updateOffre($id, $titre, $description, $prix_mini, $prix_max, $id_type_offre, $id_pro);
        $this->model::log("L'offre $id a été modifiée.");
        return $updatedOffreId;
    }
}