<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/prestation.php";

class PrestationController
{

    private $model;

    function __construct()
    {
        $this->model = "Prestation";
    }

    public function getPrestationById($id)
    {
        $prestation = $this->model::getPrestationById($id);

        $this->model::log("Les informations de la prestation $id ont été lues.");
        return $prestation;
    }

    public function getPrestationByName($name)
    {
        $prestation = $this->model::getPrestationByName($name);

        $this->model::log("Les informations de la prestation $name ont été lues.");
        return $prestation;
    }

    public function createPrestation($name, $isIncluded)
    {
        $prestation = $this->model::createPrestation($name, $isIncluded);

        $this->model::log("La prestation $name a été créée.");
        return $prestation;
    }

    public function updatePrestation($id, $name = false, $isIncluded = false)
    {
        if ($name === false && $isIncluded === false) {
            $this->model::log("Aucune information n'a été modifiée.");
            return -1;
        } else {
            $prestation = $this->model::getPrestationById($id);

            $updatedPrestationId = $this->model::createPrestation(
                $id,
                $name !== false ? $name : $prestation["nom"],
                $isIncluded !== false ? $isIncluded : $prestation["is_included"]
            );
            $this->model::log("Les informations de la prestation $id ont été modifiées.");
            return $updatedPrestationId;
        }
    }

    public function deletePrestation($id)
    {
        $this->model::log("La prestation $id a été supprimée.");
        return $this->model::deletePrestationById($id);
    }
}