<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/model/type_repas.php";

class TypeRepasController
{

    private $model;

    function __construct()
    {
        $this->model = 'TypeRepas';
    }

    public function getInfoTypeRepas($id)
    {
        $typeRepas = $this->model::getTypeRepasById($id);

        $this->model::log("Les informations du type de repas $id ont été lues.");
        return $typeRepas;
    }

    public function getTypeRepasByName($name)
    {
        $typeRepas = $this->model::getTypesRepasByName($name);

        if (count($typeRepas) == 0) {
            $this->model::log("Les informations du type de repas $name n'ont pas été lues.");
            return false;
        }

        $this->model::log("Les informations du type de repas $name ont été lues.");
        return $typeRepas;
    }

    public function createTypeRepas($nom_type_repas)
    {
        $typeRepasID = $this->model::createTypeRepas($nom_type_repas)[0]['id_type_repas'];

        $this->model::log("Un type de repas a été créé.");
        return $typeRepasID;
    }

    public function updateTypeRepas($id, $nom_type_repas = false)
    {
        if ($nom_type_repas === false) {
            echo "ERREUR: Aucun champ à modifier";
            $this->model::log("Le type de repas $id n'a pas été mis à jour.");
            return -1;
        } else {
            $typeRepas = $this->model::getTypeRepasById($id);

            $updatedTypeRepasId = $this->model::updateTypeRepas(
                $id,
                $nom_type_repas !== false ? $nom_type_repas : $typeRepas["nom_type_repas"]
            );
            $this->model::log("Les informations du type de repas $id ont été mises à jour.");
            return $updatedTypeRepasId;
        }
    }
}