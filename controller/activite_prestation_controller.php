<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/model/activite_prestation.php';

class ActivitePrestationController
{
    private $model;

    function __construct()
    {
        $this->model = "ActivitePrestation";
    }

    public function getActivitesByIdPrestation($id_prestation)
    {
        $activites = $this->model::getActivitesByIdPrestation($id_prestation);

        $result = [
            "id_activite" => $activites["id_activite"],
        ];

        $this->model::log("Les activités de la prestation $id_prestation ont été lues.");
        return $result;
    }

    public function getPrestationsByIdActivite($id_activite)
    {
        $prestations = $this->model::getPrestationsByIdActivite($id_activite);

        $this->model::log("Les prestations de l'activité $id_activite ont été lues.");
        return $prestations;
    }

    public function linkActiviteAndPrestation($id_prestation, $id_activite)
    {
        if ($this->model::checkIfLinkExists($id_prestation, $id_activite)) {
            $this->model::log("Le lien entre l'activité $id_activite et la prestation $id_prestation a été créé.");
            return $this->model::createActivitePrestation($id_prestation, $id_activite);
        } else {
            $this->model::log("Le lien entre l'activité $id_activite et la prestation $id_prestation existe déjà.");
            return -1;
        }
    }

    public function unlinkActiviteAndPrestation($id_prestation, $id_activite)
    {
        if ($this->model::checkIfLinkExists($id_prestation, $id_activite)) {
            $this->model::log("Le lien entre l'activité $id_activite et la prestation $id_prestation a été supprimé.");
            return $this->model::deleteActivitePrestation($id_prestation, $id_activite);
        } else {
            $this->model::log("Le lien entre l'activité $id_activite et la prestation $id_prestation n'existe pas.");
            return false;
        }
    }

}