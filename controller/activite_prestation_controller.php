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

        return $result;
    }

    public function getPrestationsByIdActivite($id_activite)
    {
        $prestations = $this->model::getPrestationsByIdActivite($id_activite);

        return $prestations;
    }

    public function linkActiviteAndPrestation($id_prestation, $id_activite)
    {
        if ($this->model::checkIfLinkExists($id_prestation, $id_activite)) {
            return $this->model::createActivitePrestation($id_prestation, $id_activite);
        } else {
            echo "The link already exists";
            return -1;
        }
    }

    public function unlinkActiviteAndPrestation($id_prestation, $id_activite)
    {
        if ($this->model::checkIfLinkExists($id_prestation, $id_activite)) {
            return $this->model::deleteActivitePrestation($id_prestation, $id_activite);
        } else {
            echo "The link does not exist";
            return false;
        }
    }

}